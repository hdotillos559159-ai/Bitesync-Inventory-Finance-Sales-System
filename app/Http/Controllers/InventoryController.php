<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\InventoryItem;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Models\StockReceipt;
use App\Models\StockReceiptItem;
use App\Models\Supplier;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class InventoryController extends Controller
{
    /**
     * =============================================================
     * INVENTORY INDEX
     * =============================================================
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $search = $request->input('search');
        $status = $request->input('status');

        $query = InventoryItem::with([
            'category',
            'unit',
        ]);

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%');
            });
        }

        if ($status === 'out') {
            $query->where('quantity', '<=', 0);
        }

        if ($status === 'low') {
            $query
                ->where('quantity', '>', 0)
                ->whereColumn(
                    'quantity',
                    '<=',
                    'minimum_stock'
                );
        }

        if ($status === 'normal') {
            $query
                ->where('quantity', '>', 0)
                ->whereColumn(
                    'quantity',
                    '>',
                    'minimum_stock'
                );
        }

        $inventoryItems = $query
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $totalItems = InventoryItem::count();

        $lowStockItems = InventoryItem::where(
            'quantity',
            '>',
            0
        )
            ->whereColumn(
                'quantity',
                '<=',
                'minimum_stock'
            )
            ->count();

        $outOfStockItems = InventoryItem::where(
            'quantity',
            '<=',
            0
        )->count();

        $totalInventoryValue = InventoryItem::selectRaw(
            'SUM(quantity * unit_cost) as total'
        )->value('total') ?? 0;

        /*
         * =========================================================
         * INVENTORY CATEGORIES ONLY
         * =========================================================
         */
        $categories = Category::where(
            'type',
            Category::TYPE_INVENTORY
        )
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $units = Unit::where(
            'is_active',
            true
        )
            ->orderBy('name')
            ->get();

        return view('inventory.index', [
            'user' =>
                $user,

            'inventoryItems' =>
                $inventoryItems,

            'categories' =>
                $categories,

            'units' =>
                $units,

            'totalItems' =>
                $totalItems,

            'lowStockItems' =>
                $lowStockItems,

            'outOfStockItems' =>
                $outOfStockItems,

            'totalInventoryValue' =>
                $totalInventoryValue,

            'search' =>
                $search,

            'status' =>
                $status,
        ]);
    }


    /**
     * =============================================================
     * CREATE INVENTORY ITEM
     * =============================================================
     */
    public function create(Request $request): View
    {
        $user = $request->user();

        if (!in_array(
            $user->role,
            ['CEO/Admin', 'Procurement'],
            true
        )) {
            abort(
                403,
                'You are not authorized to add inventory items.'
            );
        }

        /*
         * =========================================================
         * INVENTORY CATEGORIES ONLY
         * =========================================================
         */
        $categories = Category::where(
            'type',
            Category::TYPE_INVENTORY
        )
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $units = Unit::where(
            'is_active',
            true
        )
            ->orderBy('name')
            ->get();

        return view('inventory.create', [
            'user' =>
                $user,

            'categories' =>
                $categories,

            'units' =>
                $units,
        ]);
    }


    /**
     * =============================================================
     * STORE INVENTORY ITEM
     * =============================================================
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (!in_array(
            $user->role,
            ['CEO/Admin', 'Procurement'],
            true
        )) {
            abort(
                403,
                'You are not authorized to add inventory items.'
            );
        }

        $validated = $request->validate([
            /*
             * =====================================================
             * CATEGORY MUST BE AN ACTIVE INVENTORY CATEGORY
             * =====================================================
             */
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')
                    ->where(function ($query) {
                        $query
                            ->where(
                                'type',
                                Category::TYPE_INVENTORY
                            )
                            ->where(
                                'is_active',
                                true
                            );
                    }),
            ],

            'unit_id' => [
                'required',
                Rule::exists('units', 'id')
                    ->where(function ($query) {
                        $query->where(
                            'is_active',
                            true
                        );
                    }),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'sku' => [
                'required',
                'string',
                'max:100',
                'unique:inventory_items,sku',
            ],

            'quantity' => [
                'required',
                'numeric',
                'min:0',
            ],

            'minimum_stock' => [
                'required',
                'numeric',
                'min:0',
            ],

            'maximum_stock' => [
                'nullable',
                'numeric',
                'min:0',
                'gte:minimum_stock',
            ],

            'unit_cost' => [
                'required',
                'numeric',
                'min:0',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        DB::transaction(function () use (
            $validated,
            $user
        ) {
            $initialQuantity =
                (float) $validated['quantity'];

            $inventoryItem = InventoryItem::create([
                'category_id' =>
                    $validated['category_id'],

                'unit_id' =>
                    $validated['unit_id'],

                'name' =>
                    $validated['name'],

                'sku' =>
                    $validated['sku'],

                'quantity' =>
                    $initialQuantity,

                'minimum_stock' =>
                    $validated['minimum_stock'],

                'maximum_stock' =>
                    $validated['maximum_stock'] ?? null,

                'unit_cost' =>
                    $validated['unit_cost'],

                'location' =>
                    $validated['location'] ?? null,

                'description' =>
                    $validated['description'] ?? null,

                'is_active' =>
                    true,
            ]);

            /*
             * =====================================================
             * INITIAL STOCK AUDIT
             * =====================================================
             */
            if ($initialQuantity > 0) {
                StockMovement::create([
                    'inventory_item_id' =>
                        $inventoryItem->id,

                    'user_id' =>
                        $user->id,

                    'type' =>
                        'stock_in',

                    'quantity' =>
                        $initialQuantity,

                    'quantity_before' =>
                        0,

                    'quantity_after' =>
                        $initialQuantity,

                    'reference_type' =>
                        'initial_stock',

                    'reference_id' =>
                        $inventoryItem->id,

                    'reason' =>
                        'Initial inventory stock.',
                ]);
            }
        });

        return redirect()
            ->route('inventory.index')
            ->with(
                'success',
                'Inventory item added successfully.'
            );
    }


    /**
     * =============================================================
     * EDIT INVENTORY ITEM
     * =============================================================
     */
    public function edit(
        Request $request,
        InventoryItem $inventoryItem
    ): View {
        $user = $request->user();

        if (!in_array(
            $user->role,
            ['CEO/Admin', 'Procurement'],
            true
        )) {
            abort(
                403,
                'You are not authorized to edit inventory items.'
            );
        }

        /*
         * =========================================================
         * INVENTORY CATEGORIES ONLY
         * =========================================================
         */
        $categories = Category::where(
            'type',
            Category::TYPE_INVENTORY
        )
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $units = Unit::where(
            'is_active',
            true
        )
            ->orderBy('name')
            ->get();

        return view('inventory.edit', [
            'user' =>
                $user,

            'inventoryItem' =>
                $inventoryItem,

            'categories' =>
                $categories,

            'units' =>
                $units,
        ]);
    }


    /**
     * =============================================================
     * UPDATE INVENTORY ITEM
     * =============================================================
     *
     * Quantity is intentionally NOT changed here.
     */
    public function update(
        Request $request,
        InventoryItem $inventoryItem
    ): RedirectResponse {
        $user = $request->user();

        if (!in_array(
            $user->role,
            ['CEO/Admin', 'Procurement'],
            true
        )) {
            abort(
                403,
                'You are not authorized to edit inventory items.'
            );
        }

        $validated = $request->validate([
            /*
             * =====================================================
             * CATEGORY MUST BE AN ACTIVE INVENTORY CATEGORY
             * =====================================================
             */
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')
                    ->where(function ($query) {
                        $query
                            ->where(
                                'type',
                                Category::TYPE_INVENTORY
                            )
                            ->where(
                                'is_active',
                                true
                            );
                    }),
            ],

            'unit_id' => [
                'required',
                Rule::exists('units', 'id')
                    ->where(function ($query) {
                        $query->where(
                            'is_active',
                            true
                        );
                    }),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'sku' => [
                'required',
                'string',
                'max:100',

                Rule::unique(
                    'inventory_items',
                    'sku'
                )->ignore($inventoryItem->id),
            ],

            'minimum_stock' => [
                'required',
                'numeric',
                'min:0',
            ],

            'maximum_stock' => [
                'nullable',
                'numeric',
                'min:0',
                'gte:minimum_stock',
            ],

            'unit_cost' => [
                'required',
                'numeric',
                'min:0',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ]);

        $inventoryItem->update([
            'category_id' =>
                $validated['category_id'],

            'unit_id' =>
                $validated['unit_id'],

            'name' =>
                $validated['name'],

            'sku' =>
                $validated['sku'],

            'minimum_stock' =>
                $validated['minimum_stock'],

            'maximum_stock' =>
                $validated['maximum_stock'] ?? null,

            'unit_cost' =>
                $validated['unit_cost'],

            'location' =>
                $validated['location'] ?? null,

            'description' =>
                $validated['description'] ?? null,

            'is_active' =>
                $validated['is_active'],
        ]);

        return redirect()
            ->route('inventory.index')
            ->with(
                'success',
                'Inventory item updated successfully.'
            );
    }


    /**
     * =============================================================
     * STOCK TRANSACTIONS
     * =============================================================
     */
    public function stockForm(
        Request $request,
        InventoryItem $inventoryItem
    ): View {
        $user = $request->user();

        if (!in_array(
            $user->role,
            [
                'CEO/Admin',
                'Procurement',
                'Finance',
            ],
            true
        )) {
            abort(
                403,
                'You are not authorized to view inventory stock transactions.'
            );
        }

        $inventoryItem->load([
            'category',
            'unit',
        ]);

        /*
         * =========================================================
         * ACTIVE INVENTORY ITEMS
         * =========================================================
         *
         * Used by the multi-item Stock In form.
         */
        $inventoryItems = InventoryItem::with([
            'unit',
            'category',
        ])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        /*
         * =========================================================
         * ACTIVE SUPPLIERS
         * =========================================================
         *
         * Used when Source Type = Supplier.
         */
        $suppliers = Supplier::where(
            'status',
            'Active'
        )
            ->orderBy('name')
            ->get();

        /*
         * =========================================================
         * STOCK MOVEMENTS
         * =========================================================
         */
        $stockMovements = StockMovement::where(
            'inventory_item_id',
            $inventoryItem->id
        )
            ->with('user')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
         * =========================================================
         * PURCHASE REFERENCES
         * =========================================================
         */
        $purchaseIds = $stockMovements
            ->getCollection()
            ->filter(function ($movement) {
                return $movement->reference_type === Purchase::class
                    && !empty($movement->reference_id);
            })
            ->pluck('reference_id')
            ->unique()
            ->values();

        $purchases = collect();

        if ($purchaseIds->isNotEmpty()) {
            $purchases = Purchase::whereIn(
                'id',
                $purchaseIds
            )
                ->get()
                ->keyBy('id');
        }

        /*
         * =========================================================
         * SALE REFERENCES
         * =========================================================
         */
        $saleIds = $stockMovements
            ->getCollection()
            ->filter(function ($movement) {
                return $movement->reference_type === Sale::class
                    && !empty($movement->reference_id);
            })
            ->pluck('reference_id')
            ->unique()
            ->values();

        $sales = collect();

        if ($saleIds->isNotEmpty()) {
            $sales = Sale::whereIn(
                'id',
                $saleIds
            )
                ->get()
                ->keyBy('id');
        }

        /*
         * =========================================================
         * STOCK RECEIPT REFERENCES
         * =========================================================
         */
        $stockReceiptIds = $stockMovements
            ->getCollection()
            ->filter(function ($movement) {
                return $movement->reference_type === StockReceipt::class
                    && !empty($movement->reference_id);
            })
            ->pluck('reference_id')
            ->unique()
            ->values();

        $stockReceipts = collect();

        if ($stockReceiptIds->isNotEmpty()) {
            $stockReceipts = StockReceipt::with([
                'supplier',
            ])
                ->whereIn(
                    'id',
                    $stockReceiptIds
                )
                ->get()
                ->keyBy('id');
        }

        /*
         * =========================================================
         * BUILD LEDGER REFERENCE LABELS
         * =========================================================
         */
        $stockMovements
            ->getCollection()
            ->transform(
                function ($movement) use (
                    $purchases,
                    $sales,
                    $stockReceipts
                ) {
                    /*
                     * -------------------------------------------------
                     * PURCHASE
                     * -------------------------------------------------
                     */
                    if (
                        $movement->reference_type
                        === Purchase::class
                    ) {
                        $purchase =
                            $purchases->get(
                                $movement->reference_id
                            );

                        $movement->reference_label =
                            $purchase
                            && !empty(
                                $purchase->purchase_number
                            )
                                ? 'Purchase '
                                    . $purchase->purchase_number
                                : 'Purchase #'
                                    . $movement->reference_id;

                        return $movement;
                    }

                    /*
                     * -------------------------------------------------
                     * SALE
                     * -------------------------------------------------
                     */
                    if (
                        $movement->reference_type
                        === Sale::class
                    ) {
                        $sale =
                            $sales->get(
                                $movement->reference_id
                            );

                        $movement->reference_label =
                            $sale
                            && !empty(
                                $sale->sale_number
                            )
                                ? 'Sale '
                                    . $sale->sale_number
                                : 'Sale #'
                                    . $movement->reference_id;

                        return $movement;
                    }

                    /*
                     * -------------------------------------------------
                     * MULTI-ITEM STOCK RECEIPT
                     * -------------------------------------------------
                     */
                    if (
                        $movement->reference_type
                        === StockReceipt::class
                    ) {
                        $stockReceipt =
                            $stockReceipts->get(
                                $movement->reference_id
                            );

                        if ($stockReceipt) {
                            $movement->reference_label =
                                'Stock Receipt '
                                . $stockReceipt->reference_number;
                        } else {
                            $movement->reference_label =
                                'Stock Receipt #'
                                . $movement->reference_id;
                        }

                        return $movement;
                    }

                    /*
                     * -------------------------------------------------
                     * INITIAL STOCK
                     * -------------------------------------------------
                     */
                    if (
                        $movement->reference_type
                        === 'initial_stock'
                    ) {
                        $movement->reference_label =
                            'Initial Stock';

                        return $movement;
                    }

                    /*
                     * -------------------------------------------------
                     * LEGACY MANUAL STOCK IN
                     * -------------------------------------------------
                     */
                    if (
                        $movement->reference_type
                        === 'manual_stock_in'
                    ) {
                        $movement->reference_label =
                            'Manual Stock Receipt';

                        return $movement;
                    }

                    /*
                     * -------------------------------------------------
                     * MANUAL STOCK OUT
                     * -------------------------------------------------
                     */
                    if (
                        $movement->reference_type
                        === 'manual_stock_out'
                    ) {
                        $movement->reference_label =
                            'Manual Stock Out';

                        return $movement;
                    }

                    /*
                     * -------------------------------------------------
                     * PHYSICAL ADJUSTMENT
                     * -------------------------------------------------
                     */
                    if (
                        $movement->reference_type
                        === 'inventory_adjustment'
                    ) {
                        $movement->reference_label =
                            'Physical Count Adjustment';

                        return $movement;
                    }

                    /*
                     * -------------------------------------------------
                     * FALLBACK
                     * -------------------------------------------------
                     */
                    $movement->reference_label =
                        $movement->reference_type
                        ?? 'System Transaction';

                    return $movement;
                }
            );

        return view('inventory.stock', [
            'user' =>
                $user,

            'inventoryItem' =>
                $inventoryItem,

            'inventoryItems' =>
                $inventoryItems,

            'suppliers' =>
                $suppliers,

            'stockMovements' =>
                $stockMovements,

            'movements' =>
                $stockMovements,
        ]);
    }


    /**
     * =============================================================
     * LATEST PURCHASE COST
     * =============================================================
     *
     * Returns the most recent recorded purchase price for a
     * specific supplier + inventory item combination.
     *
     * Used by the Manual Stock Receipt form to automatically
     * populate Unit Price.
     *
     * Example:
     *
     * GET /purchases/latest-cost?supplier_id=1&inventory_item_id=5
     *
     * The lookup considers only purchases that are already valid
     * purchasing records:
     *
     * Approved
     * Ordered
     * Partially Received
     * Received
     *
     * Draft, Pending Approval, Rejected and Cancelled purchases
     * are intentionally ignored.
     */
    public function latestPurchaseCost(
        Request $request
    ): JsonResponse {
        $user = $request->user();

        /*
         * =========================================================
         * AUTHORIZATION
         * =========================================================
         */
        if (!in_array(
            $user->role,
            ['CEO/Admin', 'Procurement'],
            true
        )) {
            abort(
                403,
                'You are not authorized to view purchase costs.'
            );
        }

        /*
         * =========================================================
         * VALIDATE INPUT
         * =========================================================
         */
        $validated = $request->validate([
            'supplier_id' => [
                'required',
                'integer',
                Rule::exists('suppliers', 'id')
                    ->where(function ($query) {
                        $query->where(
                            'status',
                            'Active'
                        );
                    }),
            ],

            'inventory_item_id' => [
                'required',
                'integer',
                Rule::exists(
                    'inventory_items',
                    'id'
                ),
            ],
        ]);

        /*
         * =========================================================
         * FIND MOST RECENT PURCHASE ITEM
         * =========================================================
         *
         * The price is taken from PurchaseItem.unit_cost.
         */
        $purchaseItem = PurchaseItem::query()
            ->where(
                'inventory_item_id',
                $validated['inventory_item_id']
            )
            ->whereHas(
                'purchase',
                function ($query) use ($validated) {
                    $query
                        ->where(
                            'supplier_id',
                            $validated['supplier_id']
                        )
                        ->whereIn(
                            'status',
                            [
                                Purchase::STATUS_APPROVED,
                                Purchase::STATUS_ORDERED,
                                Purchase::STATUS_PARTIALLY_RECEIVED,
                                Purchase::STATUS_RECEIVED,
                            ]
                        );
                }
            )
            ->with([
                'purchase:id,purchase_number,purchase_date,status,supplier_id',
            ])
            ->orderByDesc(
                Purchase::select('purchase_date')
                    ->whereColumn(
                        'purchases.id',
                        'purchase_items.purchase_id'
                    )
            )
            ->orderByDesc('id')
            ->first();

        /*
         * =========================================================
         * NO PURCHASE HISTORY
         * =========================================================
         */
        if (!$purchaseItem) {
            return response()->json([
                'success' =>
                    true,

                'found' =>
                    false,

                'unit_cost' =>
                    null,

                'purchase_number' =>
                    null,

                'purchase_date' =>
                    null,

                'purchase_status' =>
                    null,

                'message' =>
                    'No previous purchase price was found for this supplier and inventory item.',
            ]);
        }

        /*
         * =========================================================
         * RETURN PURCHASE COST
         * =========================================================
         */
        return response()->json([
            'success' =>
                true,

            'found' =>
                true,

            'unit_cost' =>
                (float) $purchaseItem->unit_cost,

            'purchase_number' =>
                $purchaseItem->purchase?->purchase_number,

            'purchase_date' =>
                $purchaseItem->purchase?->purchase_date?->format(
                    'Y-m-d'
                ),

            'purchase_status' =>
                $purchaseItem->purchase?->status,

            'message' =>
                'Previous purchase price found.',
        ]);
    }


    /**
     * =============================================================
     * MULTI-ITEM MANUAL STOCK RECEIPT
     * =============================================================
     *
     * Creates ONE receipt containing MULTIPLE inventory items.
     *
     * Example:
     *
     * South Valley Food Supply
     * Reference: SVFS-2026-001
     *
     * Chicken Breast    20 kg     ₱185.00
     * Fresh Potatoes    25 kg     ₱85.00
     * Burger Buns       20 pack   ₱120.00
     * Soy Sauce         12 L      ₱75.00
     *
     * The system creates:
     *
     * 1 StockReceipt
     * 4 StockReceiptItems
     * 4 StockMovements
     *
     * and updates 4 inventory quantities.
     */
    public function stockReceiptStore(
        Request $request
    ): RedirectResponse {
        $user = $request->user();

        if (!in_array(
            $user->role,
            ['CEO/Admin', 'Procurement'],
            true
        )) {
            abort(
                403,
                'You are not authorized to record stock receipts.'
            );
        }

        /*
         * =========================================================
         * VALIDATE RECEIPT HEADER
         * =========================================================
         */
        $validated = $request->validate([
            /*
             * -----------------------------------------------------
             * SOURCE TYPE
             * -----------------------------------------------------
             */
            'source_type' => [
                'required',
                Rule::in([
                    'supplier',
                    'store',
                    'other',
                ]),
            ],

            /*
             * -----------------------------------------------------
             * SUPPLIER
             * -----------------------------------------------------
             *
             * Required only when Source Type = Supplier.
             */
            'supplier_id' => [
                'nullable',
                'integer',

                Rule::requiredIf(function () use ($request) {
                    return $request->input('source_type')
                        === 'supplier';
                }),

                Rule::exists('suppliers', 'id')
                    ->where(function ($query) {
                        $query->where(
                            'status',
                            'Active'
                        );
                    }),
            ],

            /*
             * -----------------------------------------------------
             * STORE / OTHER SOURCE NAME
             * -----------------------------------------------------
             *
             * Required when Source Type is Store or Other.
             */
            'source_name' => [
                'nullable',
                'string',
                'max:255',

                Rule::requiredIf(function () use ($request) {
                    return in_array(
                        $request->input('source_type'),
                        ['store', 'other'],
                        true
                    );
                }),
            ],

            /*
             * -----------------------------------------------------
             * RECEIPT / REFERENCE NUMBER
             * -----------------------------------------------------
             */
            'reference_number' => [
                'required',
                'string',
                'max:100',
            ],

            /*
             * -----------------------------------------------------
             * RECEIPT DATE
             * -----------------------------------------------------
             */
            'receipt_date' => [
                'required',
                'date',
            ],

            /*
             * -----------------------------------------------------
             * NOTES
             * -----------------------------------------------------
             */
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],

            /*
             * =====================================================
             * RECEIPT ITEMS
             * =====================================================
             */
            'items' => [
                'required',
                'array',
                'min:1',
            ],

            /*
             * -----------------------------------------------------
             * INVENTORY ITEM
             * -----------------------------------------------------
             */
            'items.*.inventory_item_id' => [
                'required',
                'integer',
                'distinct',
                'exists:inventory_items,id',
            ],

            /*
             * -----------------------------------------------------
             * QUANTITY
             * -----------------------------------------------------
             */
            'items.*.quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],

            /*
             * -----------------------------------------------------
             * UNIT PRICE
             * -----------------------------------------------------
             */
            'items.*.unit_price' => [
                'required',
                'numeric',
                'gte:0',
            ],
        ]);

        DB::transaction(function () use (
            $validated,
            $user
        ) {
            /*
             * =====================================================
             * LOCK INVENTORY ITEMS
             * =====================================================
             *
             * Sorting IDs before locking helps prevent
             * simultaneous transactions from locking rows
             * in different orders.
             */
            $itemIds = collect(
                $validated['items']
            )
                ->pluck('inventory_item_id')
                ->map(fn ($id) => (int) $id)
                ->sort()
                ->values();

            $lockedItems = InventoryItem::whereIn(
                'id',
                $itemIds
            )
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            /*
             * =====================================================
             * VERIFY ALL ITEMS
             * =====================================================
             */
            foreach ($itemIds as $itemId) {
                $inventoryItem =
                    $lockedItems->get($itemId);

                if (!$inventoryItem) {
                    throw ValidationException::withMessages([
                        'items' =>
                            'One or more selected inventory items could not be found.',
                    ]);
                }

                if (!$inventoryItem->is_active) {
                    throw ValidationException::withMessages([
                        'items' =>
                            'Inactive inventory items cannot receive stock.',
                    ]);
                }
            }

            /*
             * =====================================================
             * VERIFY SOURCE DATA
             * =====================================================
             */
            $sourceType =
                $validated['source_type'];

            if (
                $sourceType === 'supplier'
                && empty($validated['supplier_id'])
            ) {
                throw ValidationException::withMessages([
                    'supplier_id' =>
                        'Please select a supplier.',
                ]);
            }

            if (
                in_array(
                    $sourceType,
                    ['store', 'other'],
                    true
                )
                && empty(
                    trim(
                        $validated['source_name'] ?? ''
                    )
                )
            ) {
                throw ValidationException::withMessages([
                    'source_name' =>
                        'Please enter the source name.',
                ]);
            }

            /*
             * =====================================================
             * CREATE RECEIPT HEADER
             * =====================================================
             */
            $stockReceipt = StockReceipt::create([
                'source_type' =>
                    $sourceType,

                'supplier_id' =>
                    $sourceType === 'supplier'
                        ? $validated['supplier_id']
                        : null,

                'source_name' =>
                    in_array(
                        $sourceType,
                        ['store', 'other'],
                        true
                    )
                        ? trim(
                            $validated['source_name']
                        )
                        : null,

                'reference_number' =>
                    trim(
                        $validated['reference_number']
                    ),

                'receipt_date' =>
                    $validated['receipt_date'],

                'notes' =>
                    !empty(
                        $validated['notes']
                    )
                        ? trim(
                            $validated['notes']
                        )
                        : null,

                'total_amount' =>
                    0,

                'created_by' =>
                    $user->id,
            ]);

            /*
             * =====================================================
             * RUNNING RECEIPT TOTAL
             * =====================================================
             */
            $receiptTotal = 0;


            /*
             * =====================================================
             * SUPPLIER NAME
             * =====================================================
             *
             * Retrieve it once instead of querying inside every
             * receipt-item loop.
             */
            $supplierName = null;

            if ($sourceType === 'supplier') {
                $supplierName = Supplier::where(
                    'id',
                    $validated['supplier_id']
                )->value('name');
            }


            /*
             * =====================================================
             * PROCESS EVERY RECEIPT ITEM
             * =====================================================
             */
            foreach ($validated['items'] as $receiptItemData) {
                $itemId =
                    (int) $receiptItemData[
                        'inventory_item_id'
                    ];

                $quantityAdded =
                    (float) $receiptItemData[
                        'quantity'
                    ];

                $unitPrice =
                    (float) $receiptItemData[
                        'unit_price'
                    ];

                /*
                 * -------------------------------------------------
                 * GET LOCKED INVENTORY ITEM
                 * -------------------------------------------------
                 */
                $inventoryItem =
                    $lockedItems->get($itemId);

                /*
                 * -------------------------------------------------
                 * CALCULATE LINE TOTAL
                 * -------------------------------------------------
                 *
                 * Never trust the total sent by JavaScript.
                 * The server calculates it.
                 */
                $lineTotal =
                    round(
                        $quantityAdded * $unitPrice,
                        2
                    );

                $receiptTotal += $lineTotal;

                /*
                 * -------------------------------------------------
                 * CURRENT STOCK
                 * -------------------------------------------------
                 */
                $quantityBefore =
                    (float) $inventoryItem->quantity;

                /*
                 * -------------------------------------------------
                 * NEW STOCK
                 * -------------------------------------------------
                 */
                $quantityAfter =
                    $quantityBefore
                    + $quantityAdded;

                /*
                 * -------------------------------------------------
                 * UPDATE INVENTORY QUANTITY
                 * -------------------------------------------------
                 */
                $inventoryItem->update([
                    'quantity' =>
                        $quantityAfter,
                ]);

                /*
                 * =================================================
                 * UPDATE CURRENT INVENTORY COST
                 * =================================================
                 *
                 * For a supplier receipt, the actual received
                 * purchase price becomes the current inventory
                 * unit cost.
                 *
                 * This keeps the Inventory value consistent with
                 * the latest stock receipt.
                 */
                if ($unitPrice >= 0) {
                    $inventoryItem->update([
                        'unit_cost' =>
                            $unitPrice,
                    ]);
                }

                /*
                 * =================================================
                 * CREATE RECEIPT ITEM
                 * =================================================
                 */
                StockReceiptItem::create([
                    'stock_receipt_id' =>
                        $stockReceipt->id,

                    'inventory_item_id' =>
                        $inventoryItem->id,

                    'quantity' =>
                        $quantityAdded,

                    'unit_price' =>
                        $unitPrice,

                    'total_amount' =>
                        $lineTotal,
                ]);

                /*
                 * =================================================
                 * BUILD AUDIT REASON
                 * =================================================
                 */
                $sourceLabel = '';

                if ($sourceType === 'supplier') {
                    $sourceLabel =
                        $supplierName
                        ?? 'Supplier';
                } elseif ($sourceType === 'store') {
                    $sourceLabel =
                        $validated['source_name'];
                } else {
                    $sourceLabel =
                        $validated['source_name'];
                }

                $auditReason =
                    'Stock Receipt'
                    . ' | Source: '
                    . $sourceLabel
                    . ' | Reference: '
                    . $validated['reference_number']
                    . ' | Receipt Date: '
                    . $validated['receipt_date']
                    . ' | Quantity: '
                    . $quantityAdded
                    . ' | Unit Price: ₱'
                    . number_format(
                        $unitPrice,
                        2
                    )
                    . ' | Line Total: ₱'
                    . number_format(
                        $lineTotal,
                        2
                    );

                if (
                    !empty(
                        $validated['notes']
                    )
                ) {
                    $auditReason .=
                        ' | Notes: '
                        . $validated['notes'];
                }

                /*
                 * =================================================
                 * CREATE STOCK MOVEMENT
                 * =================================================
                 *
                 * This movement now points to the receipt header.
                 */
                StockMovement::create([
                    'inventory_item_id' =>
                        $inventoryItem->id,

                    'user_id' =>
                        $user->id,

                    'type' =>
                        'stock_in',

                    'quantity' =>
                        $quantityAdded,

                    'quantity_before' =>
                        $quantityBefore,

                    'quantity_after' =>
                        $quantityAfter,

                    'reference_type' =>
                        StockReceipt::class,

                    'reference_id' =>
                        $stockReceipt->id,

                    'reason' =>
                        $auditReason,
                ]);
            }

            /*
             * =====================================================
             * UPDATE RECEIPT TOTAL
             * =====================================================
             */
            $stockReceipt->update([
                'total_amount' =>
                    round(
                        $receiptTotal,
                        2
                    ),
            ]);
        });

        return redirect()
            ->route(
                'inventory.stock',
                $request->input(
                    'items.0.inventory_item_id'
                )
            )
            ->with(
                'success',
                'Stock receipt recorded successfully. All selected inventory quantities have been updated.'
            );
    }


    /**
     * =============================================================
     * LEGACY SINGLE-ITEM STOCK IN
     * =============================================================
     *
     * Kept for compatibility with the old Stock In form/route.
     *
     * The new Stock In UI will use stockReceiptStore().
     */
    public function stockIn(
        Request $request,
        InventoryItem $inventoryItem
    ): RedirectResponse {
        $user = $request->user();

        if (!in_array(
            $user->role,
            ['CEO/Admin', 'Procurement'],
            true
        )) {
            abort(
                403,
                'You are not authorized to perform Manual Stock In.'
            );
        }

        if (!$inventoryItem->is_active) {
            abort(
                422,
                'Inactive inventory items cannot receive stock.'
            );
        }

        $validated = $request->validate([
            'quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'source' => [
                'required',
                'string',
                'max:255',
            ],

            'reference_number' => [
                'required',
                'string',
                'max:100',
            ],

            'reason' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        DB::transaction(function () use (
            $validated,
            $inventoryItem,
            $user
        ) {
            $lockedInventoryItem =
                InventoryItem::where(
                    'id',
                    $inventoryItem->id
                )
                    ->lockForUpdate()
                    ->first();

            if (!$lockedInventoryItem) {
                abort(
                    404,
                    'Inventory item not found.'
                );
            }

            if (!$lockedInventoryItem->is_active) {
                abort(
                    422,
                    'Inactive inventory items cannot receive stock.'
                );
            }

            $quantityBefore =
                (float) $lockedInventoryItem->quantity;

            $quantityAdded =
                (float) $validated['quantity'];

            $quantityAfter =
                $quantityBefore
                + $quantityAdded;

            $lockedInventoryItem->update([
                'quantity' =>
                    $quantityAfter,
            ]);

            $auditReason =
                'Manual Stock In'
                . ' | Source: '
                . $validated['source']
                . ' | Reference: '
                . $validated['reference_number']
                . ' | Details: '
                . $validated['reason'];

            StockMovement::create([
                'inventory_item_id' =>
                    $lockedInventoryItem->id,

                'user_id' =>
                    $user->id,

                'type' =>
                    'stock_in',

                'quantity' =>
                    $quantityAdded,

                'quantity_before' =>
                    $quantityBefore,

                'quantity_after' =>
                    $quantityAfter,

                'reference_type' =>
                    'manual_stock_in',

                'reference_id' =>
                    $lockedInventoryItem->id,

                'reason' =>
                    $auditReason,
            ]);
        });

        return redirect()
            ->route(
                'inventory.stock',
                $inventoryItem
            )
            ->with(
                'success',
                'Manual stock receipt recorded successfully.'
            );
    }


    /**
     * =============================================================
     * MANUAL STOCK OUT
     * =============================================================
     */
    public function stockOut(
        Request $request,
        InventoryItem $inventoryItem
    ): RedirectResponse {
        $user = $request->user();

        if (!in_array(
            $user->role,
            ['CEO/Admin', 'Procurement'],
            true
        )) {
            abort(
                403,
                'You are not authorized to perform Manual Stock Out.'
            );
        }

        $validated = $request->validate([
            'quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'reason_category' => [
                'required',
                Rule::in([
                    'damaged',
                    'expired',
                    'spoiled',
                    'lost',
                    'contaminated',
                    'other',
                ]),
            ],

            'reason' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        if (
            $validated['reason_category'] === 'other'
            && mb_strlen(
                trim($validated['reason'])
            ) < 5
        ) {
            throw ValidationException::withMessages([
                'reason' =>
                    'Please provide a clear explanation when using Other.',
            ]);
        }

        DB::transaction(function () use (
            $validated,
            $inventoryItem,
            $user
        ) {
            $lockedInventoryItem =
                InventoryItem::where(
                    'id',
                    $inventoryItem->id
                )
                    ->lockForUpdate()
                    ->first();

            if (!$lockedInventoryItem) {
                abort(
                    404,
                    'Inventory item not found.'
                );
            }

            if (!$lockedInventoryItem->is_active) {
                abort(
                    422,
                    'Inactive inventory items cannot have stock removed.'
                );
            }

            $quantityBefore =
                (float) $lockedInventoryItem->quantity;

            $quantityRemoved =
                (float) $validated['quantity'];

            if ($quantityRemoved > $quantityBefore) {
                throw ValidationException::withMessages([
                    'quantity' =>
                        'The quantity removed cannot be greater than the current stock.',
                ]);
            }

            $quantityAfter =
                $quantityBefore
                - $quantityRemoved;

            $lockedInventoryItem->update([
                'quantity' =>
                    $quantityAfter,
            ]);

            $categoryLabels = [
                'damaged' =>
                    'Damaged',

                'expired' =>
                    'Expired',

                'spoiled' =>
                    'Spoiled',

                'lost' =>
                    'Lost',

                'contaminated' =>
                    'Contaminated',

                'other' =>
                    'Other',
            ];

            $categoryLabel =
                $categoryLabels[
                    $validated['reason_category']
                ];

            $auditReason =
                'Manual Stock Out'
                . ' | Reason: '
                . $categoryLabel
                . ' | Details: '
                . $validated['reason'];

            StockMovement::create([
                'inventory_item_id' =>
                    $lockedInventoryItem->id,

                'user_id' =>
                    $user->id,

                'type' =>
                    'stock_out',

                'quantity' =>
                    $quantityRemoved,

                'quantity_before' =>
                    $quantityBefore,

                'quantity_after' =>
                    $quantityAfter,

                'reference_type' =>
                    'manual_stock_out',

                'reference_id' =>
                    $lockedInventoryItem->id,

                'reason' =>
                    $auditReason,
            ]);
        });

        return redirect()
            ->route(
                'inventory.stock',
                $inventoryItem
            )
            ->with(
                'success',
                'Manual stock loss recorded successfully.'
            );
    }


    /**
     * =============================================================
     * PHYSICAL COUNT ADJUSTMENT
     * =============================================================
     *
     * CEO/Admin ONLY.
     */
    public function adjust(
        Request $request,
        InventoryItem $inventoryItem
    ): RedirectResponse {
        $user = $request->user();

        if ($user->role !== 'CEO/Admin') {
            abort(
                403,
                'Only CEO/Admin can perform physical inventory adjustments.'
            );
        }

        $validated = $request->validate([
            'quantity' => [
                'required',
                'numeric',
                'min:0',
            ],

            'reason' => [
                'required',
                'string',
                'min:5',
                'max:1000',
            ],
        ]);

        DB::transaction(function () use (
            $validated,
            $inventoryItem,
            $user
        ) {
            $lockedInventoryItem =
                InventoryItem::where(
                    'id',
                    $inventoryItem->id
                )
                    ->lockForUpdate()
                    ->first();

            if (!$lockedInventoryItem) {
                abort(
                    404,
                    'Inventory item not found.'
                );
            }

            if (!$lockedInventoryItem->is_active) {
                abort(
                    422,
                    'Inactive inventory items cannot be adjusted.'
                );
            }

            $quantityBefore =
                (float) $lockedInventoryItem->quantity;

            $physicalQuantity =
                (float) $validated['quantity'];

            $difference =
                $physicalQuantity
                - $quantityBefore;

            if (abs($difference) < 0.000001) {
                throw ValidationException::withMessages([
                    'quantity' =>
                        'The physical count is the same as the current system quantity. No adjustment is required.',
                ]);
            }

            $lockedInventoryItem->update([
                'quantity' =>
                    $physicalQuantity,
            ]);

            $adjustmentDirection =
                $difference > 0
                    ? 'Increase'
                    : 'Decrease';

            $auditReason =
                'Physical Count Adjustment'
                . ' | Direction: '
                . $adjustmentDirection
                . ' | System Quantity: '
                . $quantityBefore
                . ' | Physical Count: '
                . $physicalQuantity
                . ' | Variance: '
                . $difference
                . ' | Details: '
                . $validated['reason'];

            StockMovement::create([
                'inventory_item_id' =>
                    $lockedInventoryItem->id,

                'user_id' =>
                    $user->id,

                'type' =>
                    'adjustment',

                'quantity' =>
                    $difference,

                'quantity_before' =>
                    $quantityBefore,

                'quantity_after' =>
                    $physicalQuantity,

                'reference_type' =>
                    'inventory_adjustment',

                'reference_id' =>
                    $inventoryItem->id,

                'reason' =>
                    $auditReason,
            ]);
        });

        return redirect()
            ->route(
                'inventory.stock',
                $inventoryItem
            )
            ->with(
                'success',
                'Physical inventory count adjustment recorded successfully.'
            );
    }
}