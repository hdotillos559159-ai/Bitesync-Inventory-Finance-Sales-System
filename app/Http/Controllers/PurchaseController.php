<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
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
         *
         * IMPORTANT:
         * Creating a purchase does NOT change inventory.
         *
         * The unit_cost is permanently stored in purchase_items.
         *
         * This historical cost will later be used by Manual Stock In
         * when the same supplier and inventory item are selected.
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
             *
             * This is safe because Draft/Rejected purchases
             * cannot have received stock.
             */
            $purchase->items()->delete();

            /*
             * Create updated purchase items.
             *
             * The unit_cost is stored as historical purchase cost.
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
     * Receive stock from a purchase.
     *
     * This is the actual point where inventory increases.
     *
     * Purchase creation DOES NOT increase inventory.
     *
     * The purchase item's unit_cost remains stored as
     * the historical cost for that purchase.
     */
    public function receive(
        Request $request,
        Purchase $purchase
    ): RedirectResponse {
        $user = $request->user();

        /*
         * CEO/Admin and Procurement can receive purchases.
         */
        if (!in_array(
            $user->role,
            ['CEO/Admin', 'Procurement'],
            true
        )) {
            abort(
                403,
                'You are not authorized to receive purchases.'
            );
        }

        /*
         * Receiving is only allowed after the purchase
         * has been ordered or has already been partially received.
         */
        if (
            !$purchase->isOrdered() &&
            !$purchase->isPartiallyReceived()
        ) {
            abort(
                422,
                'Only ordered or partially received purchases can be received.'
            );
        }

        /*
         * Validate the receiving quantities.
         *
         * Format:
         *
         * received[PurchaseItem ID] = quantity being received now
         */
        $validated = $request->validate([
            'received' => [
                'required',
                'array',
                'min:1',
            ],

            'received.*' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ]);

        try {
            DB::transaction(function () use (
                $purchase,
                $validated,
                $user
            ) {
                /*
                 * Lock the purchase row.
                 */
                $lockedPurchase = Purchase::where(
                    'id',
                    $purchase->id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                /*
                 * Re-check the status after locking.
                 */
                if (
                    !$lockedPurchase->isOrdered() &&
                    !$lockedPurchase->isPartiallyReceived()
                ) {
                    throw new \RuntimeException(
                        'This purchase is no longer available for receiving.'
                    );
                }

                /*
                 * Load and lock all purchase items.
                 */
                $purchaseItems = PurchaseItem::where(
                    'purchase_id',
                    $lockedPurchase->id
                )
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                if ($purchaseItems->isEmpty()) {
                    throw new \RuntimeException(
                        'This purchase has no items to receive.'
                    );
                }

                /*
                 * Track whether at least one quantity
                 * is actually being received.
                 */
                $hasReceivingQuantity = false;

                /*
                 * =========================================================
                 * FIRST PASS
                 * Validate every submitted quantity before changing stock.
                 * =========================================================
                 */
                foreach (
                    $validated['received']
                    as $purchaseItemId => $receivedNow
                ) {
                    $receivedNow =
                        (float) ($receivedNow ?? 0);

                    /*
                     * Ignore empty/zero rows.
                     */
                    if ($receivedNow <= 0) {
                        continue;
                    }

                    $hasReceivingQuantity = true;

                    /*
                     * Make sure this purchase item actually
                     * belongs to this purchase.
                     */
                    if (!$purchaseItems->has($purchaseItemId)) {
                        throw new \RuntimeException(
                            'An invalid purchase item was submitted.'
                        );
                    }

                    $purchaseItem =
                        $purchaseItems->get(
                            $purchaseItemId
                        );

                    $orderedQuantity =
                        (float) $purchaseItem->quantity;

                    $alreadyReceived =
                        (float) $purchaseItem->received_quantity;

                    $remainingQuantity =
                        max(
                            0,
                            $orderedQuantity -
                            $alreadyReceived
                        );

                    /*
                     * Prevent receiving more than the
                     * remaining quantity.
                     */
                    if ($receivedNow > $remainingQuantity) {
                        throw new \RuntimeException(
                            'You cannot receive more than the remaining quantity for inventory item #'
                            . $purchaseItem->inventory_item_id
                            . '. Remaining quantity: '
                            . $remainingQuantity
                            . '.'
                        );
                    }
                }

                if (!$hasReceivingQuantity) {
                    throw new \RuntimeException(
                        'Please enter at least one quantity to receive.'
                    );
                }

                /*
                 * =========================================================
                 * SECOND PASS
                 * Actually update purchase items and inventory.
                 * =========================================================
                 */
                foreach (
                    $validated['received']
                    as $purchaseItemId => $receivedNow
                ) {
                    $receivedNow =
                        (float) ($receivedNow ?? 0);

                    /*
                     * Ignore empty/zero rows.
                     */
                    if ($receivedNow <= 0) {
                        continue;
                    }

                    $purchaseItem =
                        $purchaseItems->get(
                            $purchaseItemId
                        );

                    /*
                     * Lock the inventory item.
                     */
                    $inventoryItem =
                        InventoryItem::where(
                            'id',
                            $purchaseItem->inventory_item_id
                        )
                            ->lockForUpdate()
                            ->first();

                    if (!$inventoryItem) {
                        throw new \RuntimeException(
                            'The inventory item for purchase item #'
                            . $purchaseItem->id
                            . ' could not be found.'
                        );
                    }

                    /*
                     * Current inventory quantity.
                     */
                    $quantityBefore =
                        (float) $inventoryItem->quantity;

                    /*
                     * Add only the quantity being received NOW.
                     */
                    $quantityAfter =
                        $quantityBefore +
                        $receivedNow;

                    /*
                     * Update purchase received quantity.
                     */
                    $newReceivedQuantity =
                        (float) $purchaseItem->received_quantity +
                        $receivedNow;

                    /*
                     * Final safety check.
                     */
                    if (
                        $newReceivedQuantity >
                        (
                            (float) $purchaseItem->quantity
                            + 0.000001
                        )
                    ) {
                        throw new \RuntimeException(
                            'The received quantity cannot exceed the ordered quantity.'
                        );
                    }

                    /*
                     * =====================================================
                     * UPDATE PURCHASE ITEM
                     * =====================================================
                     */
                    $purchaseItem->update([
                        'received_quantity' =>
                            $newReceivedQuantity,
                    ]);

                    /*
                     * =====================================================
                     * UPDATE INVENTORY
                     * =====================================================
                     */
                    $inventoryItem->update([
                        'quantity' => $quantityAfter,
                    ]);

                    /*
                     * =====================================================
                     * CREATE STOCK MOVEMENT
                     * =====================================================
                     */
                    StockMovement::create([
                        'inventory_item_id' =>
                            $inventoryItem->id,

                        'user_id' =>
                            $user->id,

                        'type' =>
                            'stock_in',

                        'quantity' =>
                            $receivedNow,

                        'quantity_before' =>
                            $quantityBefore,

                        'quantity_after' =>
                            $quantityAfter,

                        'reference_type' =>
                            Purchase::class,

                        'reference_id' =>
                            $lockedPurchase->id,

                        'reason' =>
                            'Stock received from purchase '
                            . $lockedPurchase->purchase_number
                            . '.',
                    ]);
                }

                /*
                 * =========================================================
                 * DETERMINE PURCHASE STATUS
                 * =========================================================
                 */

                $updatedItems = PurchaseItem::where(
                    'purchase_id',
                    $lockedPurchase->id
                )
                    ->lockForUpdate()
                    ->get();

                $allFullyReceived = true;
                $anyReceived = false;

                foreach ($updatedItems as $item) {
                    $ordered =
                        (float) $item->quantity;

                    $received =
                        (float) $item->received_quantity;

                    if ($received > 0) {
                        $anyReceived = true;
                    }

                    if (
                        $received + 0.000001 <
                        $ordered
                    ) {
                        $allFullyReceived = false;
                    }
                }

                /*
                 * Every item has been completely received.
                 */
                if ($allFullyReceived) {
                    $lockedPurchase->update([
                        'status' =>
                            Purchase::STATUS_RECEIVED,
                    ]);
                }

                /*
                 * At least one item has been received,
                 * but something is still outstanding.
                 */
                elseif ($anyReceived) {
                    $lockedPurchase->update([
                        'status' =>
                            Purchase::STATUS_PARTIALLY_RECEIVED,
                    ]);
                }

                else {
                    throw new \RuntimeException(
                        'No stock was received.'
                    );
                }
            });

            return redirect()
                ->route(
                    'purchases.show',
                    $purchase
                )
                ->with(
                    'success',
                    'Purchase stock has been received successfully.'
                );
        } catch (\RuntimeException $exception) {
            return back()
                ->withErrors([
                    'receiving' =>
                        $exception->getMessage(),
                ])
                ->withInput();
        } catch (\Throwable $exception) {
            report($exception);

            return back()
                ->withErrors([
                    'receiving' =>
                        'The purchase could not be received. Please try again.',
                ])
                ->withInput();
        }
    }


    /**
     * Return the latest purchase cost for a supplier + inventory item.
     *
     * This is used by Manual Stock In.
     *
     * Example:
     *
     * Supplier:
     * ABC Foods
     *
     * Inventory:
     * Cooking Oil
     *
     * Latest purchase:
     * ₱185.00
     *
     * The Manual Stock In form can then automatically
     * populate the unit price with ₱185.00.
     *
     * IMPORTANT:
     *
     * Only purchases that have actually received stock
     * are considered historical purchase costs.
     *
     * Draft, pending, approved, ordered, rejected, and
     * cancelled purchases are not used as the historical
     * received cost.
     */
    public function latestCost(
        Request $request
    ): JsonResponse {
        $user = $request->user();

        /*
         * Only CEO/Admin and Procurement can use
         * the stock receiving / purchasing workflow.
         */
        if (!in_array(
            $user->role,
            ['CEO/Admin', 'Procurement'],
            true
        )) {
            abort(
                403,
                'You are not authorized to access purchase cost information.'
            );
        }

        /*
         * Validate the lookup.
         */
        $validated = $request->validate([
            'supplier_id' => [
                'required',
                'integer',
                'exists:suppliers,id',
            ],

            'inventory_item_id' => [
                'required',
                'integer',
                'exists:inventory_items,id',
            ],
        ]);

        /*
         * Find the most recent purchase item for:
         *
         * supplier + inventory item
         *
         * Only purchases with received stock are considered.
         *
         * The latest purchase date is used first, followed by
         * the latest purchase ID as a deterministic tie-breaker.
         */
        $purchaseItem = PurchaseItem::query()
            ->select([
                'purchase_items.id',
                'purchase_items.unit_cost',
                'purchase_items.purchase_id',
                'purchase_items.inventory_item_id',
            ])
            ->where(
                'purchase_items.inventory_item_id',
                $validated['inventory_item_id']
            )
            ->where(
                'purchase_items.received_quantity',
                '>',
                0
            )
            ->whereHas(
                'purchase',
                function ($purchaseQuery) use ($validated) {
                    $purchaseQuery
                        ->where(
                            'supplier_id',
                            $validated['supplier_id']
                        )
                        ->whereIn(
                            'status',
                            [
                                Purchase::STATUS_PARTIALLY_RECEIVED,
                                Purchase::STATUS_RECEIVED,
                            ]
                        );
                }
            )
            ->whereHas(
                'purchase',
                function ($purchaseQuery) {
                    $purchaseQuery
                        ->whereNotNull('purchase_date');
                }
            )
            ->with([
                'purchase:id,supplier_id,purchase_number,purchase_date',
            ])
            ->orderByDesc(
                Purchase::select('purchase_date')
                    ->whereColumn(
                        'purchases.id',
                        'purchase_items.purchase_id'
                    )
            )
            ->orderByDesc(
                'purchase_items.id'
            )
            ->first();

        /*
         * No purchase history exists for this
         * supplier + inventory item combination.
         */
        if (!$purchaseItem) {
            return response()->json([
                'found' => false,
                'unit_cost' => null,
                'formatted_cost' => null,
                'purchase_number' => null,
                'purchase_date' => null,
            ]);
        }

        /*
         * Return the historical unit cost.
         */
        $unitCost =
            (float) $purchaseItem->unit_cost;

        return response()->json([
            'found' => true,

            'unit_cost' =>
                number_format(
                    $unitCost,
                    2,
                    '.',
                    ''
                ),

            'formatted_cost' =>
                '₱' .
                number_format(
                    $unitCost,
                    2
                ),

            'purchase_number' =>
                $purchaseItem->purchase?->purchase_number,

            'purchase_date' =>
                $purchaseItem->purchase?->purchase_date
                    ?->format('Y-m-d'),
        ]);
    }


    /**
     * Cancel a purchase.
     *
     * A purchase that has already received stock cannot be
     * cancelled because doing so would leave inventory
     * increased while the purchase is marked cancelled.
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

        /*
         * A purchase with received stock must not be cancelled.
         */
        $hasReceivedStock = $purchase->items()
            ->where(
                'received_quantity',
                '>',
                0
            )
            ->exists();

        if ($hasReceivedStock) {
            abort(
                422,
                'This purchase cannot be cancelled because stock has already been received.'
            );
        }

        if ($purchase->isCancelled()) {
            abort(
                422,
                'This purchase has already been cancelled.'
            );
        }

        /*
         * A fully received purchase is also protected.
         */
        if ($purchase->isReceived()) {
            abort(
                422,
                'A received purchase cannot be cancelled.'
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