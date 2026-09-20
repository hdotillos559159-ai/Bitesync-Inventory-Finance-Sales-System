<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    /**
     * Display the purchase list.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Purchase::with([
            'supplier',
            'creator',
        ]);

        /*
         * Search by purchase number or supplier name.
         */
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($purchaseQuery) use ($search) {
                $purchaseQuery
                    ->where(
                        'purchase_number',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhereHas(
                        'supplier',
                        function ($supplierQuery) use ($search) {
                            $supplierQuery->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            );
                        }
                    );
            });
        }

        /*
         * Filter by purchase status.
         */
        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        /*
         * Filter by supplier.
         */
        if ($request->filled('supplier_id')) {
            $query->where(
                'supplier_id',
                $request->supplier_id
            );
        }

        /*
         * Get paginated purchases.
         *
         * appends() keeps the current search and filter
         * values when moving between pages.
         */
        $purchases = $query
            ->latest('purchase_date')
            ->latest('id')
            ->paginate(10)
            ->appends(
                $request->only([
                    'search',
                    'status',
                    'supplier_id',
                ])
            );

        /*
         * Get active suppliers for the filter.
         */
        $suppliers = Supplier::where(
            'status',
            'Active'
        )
            ->orderBy('name')
            ->get();

        /*
         * Purchase statistics.
         */
        $stats = [
            'total' => Purchase::count(),

            'draft' => Purchase::where(
                'status',
                Purchase::STATUS_DRAFT
            )->count(),

            'pending' => Purchase::where(
                'status',
                Purchase::STATUS_PENDING_APPROVAL
            )->count(),

            'approved' => Purchase::whereIn(
                'status',
                [
                    Purchase::STATUS_APPROVED,
                    Purchase::STATUS_ORDERED,
                    Purchase::STATUS_PARTIALLY_RECEIVED,
                ]
            )->count(),

            'received' => Purchase::where(
                'status',
                Purchase::STATUS_RECEIVED
            )->count(),
        ];

        return view('purchases.index', [
            'user' => $user,
            'purchases' => $purchases,
            'suppliers' => $suppliers,
            'stats' => $stats,
            'statuses' => [
                Purchase::STATUS_DRAFT,
                Purchase::STATUS_PENDING_APPROVAL,
                Purchase::STATUS_APPROVED,
                Purchase::STATUS_REJECTED,
                Purchase::STATUS_ORDERED,
                Purchase::STATUS_PARTIALLY_RECEIVED,
                Purchase::STATUS_RECEIVED,
                Purchase::STATUS_CANCELLED,
            ],
        ]);
    }

    /**
     * Show the create purchase form.
     */
    public function create(Request $request): View
    {
        $user = $request->user();

        /*
         * Only CEO/Admin and Procurement can create purchases.
         */
        if (!in_array(
            $user->role,
            ['CEO/Admin', 'Procurement'],
            true
        )) {
            abort(
                403,
                'You are not authorized to create purchases.'
            );
        }

        $suppliers = Supplier::where(
            'status',
            'Active'
        )
            ->orderBy('name')
            ->get();

        $inventoryItems = InventoryItem::with([
            'category',
            'unit',
        ])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('purchases.create', [
            'user' => $user,
            'suppliers' => $suppliers,
            'inventoryItems' => $inventoryItems,
        ]);
    }

    /**
     * Store a new purchase.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        /*
         * Only CEO/Admin and Procurement can create purchases.
         */
        if (!in_array(
            $user->role,
            ['CEO/Admin', 'Procurement'],
            true
        )) {
            abort(
                403,
                'You are not authorized to create purchases.'
            );
        }

        $validated = $request->validate([
            'supplier_id' => [
                'required',
                'integer',
                'exists:suppliers,id',
            ],

            'purchase_date' => [
                'required',
                'date',
            ],

            'expected_date' => [
                'nullable',
                'date',
                'after_or_equal:purchase_date',
            ],

            'tax' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.inventory_item_id' => [
                'required',
                'integer',
                'distinct',
                'exists:inventory_items,id',
            ],

            'items.*.quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'items.*.unit_cost' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        /*
         * Make sure the selected supplier is active.
         */
        $supplier = Supplier::findOrFail(
            $validated['supplier_id']
        );

        if ($supplier->status !== 'Active') {
            return back()
                ->withInput()
                ->withErrors([
                    'supplier_id' =>
                        'The selected supplier is inactive.',
                ]);
        }

        /*
         * Make sure all selected inventory items are active.
         */
        $inventoryItemIds = collect(
            $validated['items']
        )
            ->pluck('inventory_item_id')
            ->values()
            ->all();

        $activeItemCount = InventoryItem::whereIn(
            'id',
            $inventoryItemIds
        )
            ->where('is_active', true)
            ->count();

        if ($activeItemCount !== count($inventoryItemIds)) {
            return back()
                ->withInput()
                ->withErrors([
                    'items' =>
                        'One or more selected inventory items are inactive.',
                ]);
        }

        /*
         * Calculate purchase totals.
         */
        $subtotal = 0;

        foreach ($validated['items'] as $item) {
            $subtotal +=
                (float) $item['quantity'] *
                (float) $item['unit_cost'];
        }

        $tax = (float) ($validated['tax'] ?? 0);

        $total = $subtotal + $tax;

        /*
         * Generate a unique purchase number.
         */
        $purchaseNumber = $this->generatePurchaseNumber();

        /*
         * Save purchase and purchase items together.
         */
        $purchase = DB::transaction(function () use (
            $validated,
            $user,
            $purchaseNumber,
            $subtotal,
            $tax,
            $total
        ) {
            $purchase = Purchase::create([
                'supplier_id' => $validated['supplier_id'],
                'purchase_number' => $purchaseNumber,
                'purchase_date' => $validated['purchase_date'],
                'expected_date' =>
                    $validated['expected_date'] ?? null,
                'status' => Purchase::STATUS_DRAFT,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'notes' => $validated['notes'] ?? null,
                'created_by' => $user->id,
            ]);

            foreach ($validated['items'] as $item) {
                $quantity = (float) $item['quantity'];
                $unitCost = (float) $item['unit_cost'];

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'inventory_item_id' =>
                        $item['inventory_item_id'],
                    'quantity' => $quantity,
                    'unit_cost' => $unitCost,
                    'subtotal' => $quantity * $unitCost,
                    'received_quantity' => 0,
                ]);
            }

            return $purchase;
        });

        return redirect()
            ->route('purchases.show', $purchase)
            ->with(
                'success',
                'Purchase ' .
                $purchase->purchase_number .
                ' has been created as a draft.'
            );
    }

    /**
     * Display a purchase.
     */
    public function show(
        Request $request,
        Purchase $purchase
    ): View {
        $user = $request->user();

        $purchase->load([
            'supplier',
            'creator',
            'approver',
            'items.inventoryItem.category',
            'items.inventoryItem.unit',
        ]);

        return view('purchases.show', [
            'user' => $user,
            'purchase' => $purchase,
        ]);
    }

    /**
     * Show the edit purchase form.
     */
    public function edit(
        Request $request,
        Purchase $purchase
    ): View {
        $user = $request->user();

        if (!in_array(
            $user->role,
            ['CEO/Admin', 'Procurement'],
            true
        )) {
            abort(
                403,
                'You are not authorized to edit purchases.'
            );
        }

        /*
         * Only Draft and Rejected purchases can be edited.
         */
        if (
            !$purchase->isDraft() &&
            !$purchase->isRejected()
        ) {
            abort(
                422,
                'Only draft or rejected purchases can be edited.'
            );
        }

        $purchase->load('items');

        $suppliers = Supplier::where(
            'status',
            'Active'
        )
            ->orderBy('name')
            ->get();

        $inventoryItems = InventoryItem::with([
            'category',
            'unit',
        ])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('purchases.edit', [
            'user' => $user,
            'purchase' => $purchase,
            'suppliers' => $suppliers,
            'inventoryItems' => $inventoryItems,
        ]);
    }

    /**
     * Update a purchase.
     */
    public function update(
        Request $request,
        Purchase $purchase
    ): RedirectResponse {
        $user = $request->user();

        if (!in_array(
            $user->role,
            ['CEO/Admin', 'Procurement'],
            true
        )) {
            abort(
                403,
                'You are not authorized to update purchases.'
            );
        }

        if (
            !$purchase->isDraft() &&
            !$purchase->isRejected()
        ) {
            abort(
                422,
                'Only draft or rejected purchases can be updated.'
            );
        }

        $validated = $request->validate([
            'supplier_id' => [
                'required',
                'integer',
                'exists:suppliers,id',
            ],

            'purchase_date' => [
                'required',
                'date',
            ],

            'expected_date' => [
                'nullable',
                'date',
                'after_or_equal:purchase_date',
            ],

            'tax' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.inventory_item_id' => [
                'required',
                'integer',
                'distinct',
                'exists:inventory_items,id',
            ],

            'items.*.quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'items.*.unit_cost' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        /*
         * Make sure the selected supplier is active.
         */
        $supplier = Supplier::findOrFail(
            $validated['supplier_id']
        );

        if ($supplier->status !== 'Active') {
            return back()
                ->withInput()
                ->withErrors([
                    'supplier_id' =>
                        'The selected supplier is inactive.',
                ]);
        }

        /*
         * Make sure all selected inventory items are active.
         */
        $inventoryItemIds = collect(
            $validated['items']
        )
            ->pluck('inventory_item_id')
            ->values()
            ->all();

        $activeItemCount = InventoryItem::whereIn(
            'id',
            $inventoryItemIds
        )
            ->where('is_active', true)
            ->count();

        if ($activeItemCount !== count($inventoryItemIds)) {
            return back()
                ->withInput()
                ->withErrors([
                    'items' =>
                        'One or more selected inventory items are inactive.',
                ]);
        }

        /*
         * Calculate purchase totals.
         */
        $subtotal = 0;

        foreach ($validated['items'] as $item) {
            $subtotal +=
                (float) $item['quantity'] *
                (float) $item['unit_cost'];
        }

        $tax = (float) ($validated['tax'] ?? 0);

        $total = $subtotal + $tax;

        /*
         * Update purchase and replace its items.
         */
        DB::transaction(function () use (
            $purchase,
            $validated,
            $subtotal,
            $tax,
            $total
        ) {
            $purchase->update([
                'supplier_id' => $validated['supplier_id'],
                'purchase_date' => $validated['purchase_date'],
                'expected_date' =>
                    $validated['expected_date'] ?? null,
                'status' => Purchase::STATUS_DRAFT,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'notes' => $validated['notes'] ?? null,
                'approved_by' => null,
                'approved_at' => null,
            ]);

            /*
             * Remove old purchase items.
             */
            $purchase->items()->delete();

            /*
             * Create updated purchase items.
             */
            foreach ($validated['items'] as $item) {
                $quantity = (float) $item['quantity'];
                $unitCost = (float) $item['unit_cost'];

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'inventory_item_id' =>
                        $item['inventory_item_id'],
                    'quantity' => $quantity,
                    'unit_cost' => $unitCost,
                    'subtotal' => $quantity * $unitCost,
                    'received_quantity' => 0,
                ]);
            }
        });

        return redirect()
            ->route('purchases.show', $purchase)
            ->with(
                'success',
                'Purchase has been updated successfully.'
            );
    }

    /**
     * Submit a draft purchase for approval.
     */
    public function submit(
        Request $request,
        Purchase $purchase
    ): RedirectResponse {
        $user = $request->user();

        if (!in_array(
            $user->role,
            ['CEO/Admin', 'Procurement'],
            true
        )) {
            abort(
                403,
                'You are not authorized to submit purchases.'
            );
        }

        if (
            !$purchase->isDraft() &&
            !$purchase->isRejected()
        ) {
            abort(
                422,
                'Only draft or rejected purchases can be submitted.'
            );
        }

        if ($purchase->items()->count() === 0) {
            abort(
                422,
                'A purchase must contain at least one item.'
            );
        }

        $purchase->update([
            'status' => Purchase::STATUS_PENDING_APPROVAL,
        ]);

        return back()->with(
            'success',
            'Purchase has been submitted for approval.'
        );
    }

    /**
     * Approve a purchase.
     */
    public function approve(
        Request $request,
        Purchase $purchase
    ): RedirectResponse {
        $user = $request->user();

        if ($user->role !== 'CEO/Admin') {
            abort(
                403,
                'Only CEO/Admin can approve purchases.'
            );
        }

        if (!$purchase->isPendingApproval()) {
            abort(
                422,
                'Only pending purchases can be approved.'
            );
        }

        $purchase->update([
            'status' => Purchase::STATUS_APPROVED,
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        return back()->with(
            'success',
            'Purchase has been approved.'
        );
    }

    /**
     * Reject a purchase.
     */
    public function reject(
        Request $request,
        Purchase $purchase
    ): RedirectResponse {
        $user = $request->user();

        if ($user->role !== 'CEO/Admin') {
            abort(
                403,
                'Only CEO/Admin can reject purchases.'
            );
        }

        if (!$purchase->isPendingApproval()) {
            abort(
                422,
                'Only pending purchases can be rejected.'
            );
        }

        $purchase->update([
            'status' => Purchase::STATUS_REJECTED,
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        return back()->with(
            'success',
            'Purchase has been rejected.'
        );
    }

    /**
     * Mark an approved purchase as ordered.
     */
    public function order(
        Request $request,
        Purchase $purchase
    ): RedirectResponse {
        $user = $request->user();

        if (!in_array(
            $user->role,
            ['CEO/Admin', 'Procurement'],
            true
        )) {
            abort(
                403,
                'You are not authorized to mark purchases as ordered.'
            );
        }

        if (!$purchase->isApproved()) {
            abort(
                422,
                'Only approved purchases can be marked as ordered.'
            );
        }

        $purchase->update([
            'status' => Purchase::STATUS_ORDERED,
        ]);

        return back()->with(
            'success',
            'Purchase has been marked as ordered.'
        );
    }

    /**
     * Cancel a purchase.
     */
    public function cancel(
        Request $request,
        Purchase $purchase
    ): RedirectResponse {
        $user = $request->user();

        if (!in_array(
            $user->role,
            ['CEO/Admin', 'Procurement'],
            true
        )) {
            abort(
                403,
                'You are not authorized to cancel purchases.'
            );
        }

        if (
            $purchase->isReceived() ||
            $purchase->isCancelled()
        ) {
            abort(
                422,
                'This purchase cannot be cancelled.'
            );
        }

        $purchase->update([
            'status' => Purchase::STATUS_CANCELLED,
        ]);

        return back()->with(
            'success',
            'Purchase has been cancelled.'
        );
    }

    /**
     * Generate a unique purchase number.
     */
    private function generatePurchaseNumber(): string
    {
        do {
            $number =
                'PO-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(
                    substr(
                        uniqid(),
                        -6
                    )
                );

            $exists = Purchase::where(
                'purchase_number',
                $number
            )->exists();

        } while ($exists);

        return $number;
    }
}