<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SalesController extends Controller
{
    /**
     * Display the sales records.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Sales query
        |--------------------------------------------------------------------------
        */

        $query = Sale::with([
            'creator',
            'items.product',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where(
                    'sale_number',
                    'like',
                    "%{$search}%"
                )
                    ->orWhereHas('creator', function ($creatorQuery) use ($search) {
                        $creatorQuery->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );
                    })
                    ->orWhereHas('items.product', function ($productQuery) use ($search) {
                        $productQuery
                            ->where(
                                'name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'sku',
                                'like',
                                "%{$search}%"
                            );
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->input('status')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Payment method filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_method')) {
            $query->where(
                'payment_method',
                $request->input('payment_method')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Sales records
        |--------------------------------------------------------------------------
        */

        $sales = $query
            ->latest('sale_date')
            ->latest('id')
            ->paginate(10)
            ->appends(
                $request->only([
                    'search',
                    'status',
                    'payment_method',
                ])
            );

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $stats = [
            'total' => Sale::count(),

            'completed' => Sale::where(
                'status',
                'Completed'
            )->count(),

            'today' => Sale::where(
                'status',
                'Completed'
            )
                ->whereDate(
                    'sale_date',
                    today()
                )
                ->count(),

            'revenue' => Sale::where(
                'status',
                'Completed'
            )->sum('total'),
        ];

        /*
        |--------------------------------------------------------------------------
        | Filter options
        |--------------------------------------------------------------------------
        */

        $statuses = [
            'Completed',
            'Cancelled',
        ];

        $paymentMethods = [
            'Cash',
            'GCash',
            'Card',
            'Bank Transfer',
        ];

        return view('sales.index', [
            'user' => $user,
            'sales' => $sales,
            'stats' => $stats,
            'statuses' => $statuses,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    /**
     * Show the create sale form.
     */
    public function create(Request $request): View
    {
        $user = $request->user();

        $products = Product::with([
            'category',
            'recipe.items.inventoryItem.unit',
        ])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('sales.create', [
            'user' => $user,
            'products' => $products,
        ]);
    }

    /**
     * Store a new sale.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Validate basic sale information
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'sale_date' => [
                'required',
                'date',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'tax' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'payment_method' => [
                'required',
                'string',
                'max:50',
            ],

            'amount_received' => [
                'required',
                'numeric',
                'min:0',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],

            'items.*.quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Normalize values
        |--------------------------------------------------------------------------
        */

        $discount = (float) (
            $validated['discount'] ?? 0
        );

        $tax = (float) (
            $validated['tax'] ?? 0
        );

        $amountReceived = (float) (
            $validated['amount_received'] ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | Start database transaction
        |--------------------------------------------------------------------------
        */

        try {
            $sale = DB::transaction(function () use (
                $validated,
                $user,
                $discount,
                $tax,
                $amountReceived
            ) {
                /*
                |--------------------------------------------------------------------------
                | Load selected products
                |--------------------------------------------------------------------------
                */

                $productIds = collect(
                    $validated['items']
                )
                    ->pluck('product_id')
                    ->unique()
                    ->values();

                $products = Product::with([
                    'recipe.items.inventoryItem.unit',
                ])
                    ->whereIn(
                        'id',
                        $productIds
                    )
                    ->where(
                        'is_active',
                        true
                    )
                    ->get()
                    ->keyBy('id');

                /*
                |--------------------------------------------------------------------------
                | Make sure every submitted product exists
                |--------------------------------------------------------------------------
                */

                foreach ($validated['items'] as $item) {
                    if (!isset(
                        $products[$item['product_id']]
                    )) {
                        throw new \RuntimeException(
                            'One of the selected products is no longer available.'
                        );
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Calculate subtotal
                |--------------------------------------------------------------------------
                */

                $subtotal = 0;

                foreach ($validated['items'] as $item) {
                    $product = $products[
                        $item['product_id']
                    ];

                    $quantity = (float) $item['quantity'];

                    $unitPrice = (float) $product->selling_price;

                    $itemSubtotal =
                        $quantity * $unitPrice;

                    $subtotal += $itemSubtotal;
                }

                /*
                |--------------------------------------------------------------------------
                | Calculate total
                |--------------------------------------------------------------------------
                */

                $total =
                    $subtotal
                    - $discount
                    + $tax;

                if ($total < 0) {
                    $total = 0;
                }

                /*
                |--------------------------------------------------------------------------
                | Validate payment
                |--------------------------------------------------------------------------
                */

                if ($amountReceived < $total) {
                    throw new \RuntimeException(
                        'The amount received is not enough to complete this sale.'
                    );
                }

                $change =
                    $amountReceived - $total;

                /*
                |--------------------------------------------------------------------------
                | Generate sale number
                |--------------------------------------------------------------------------
                */

                $saleNumber = $this->generateSaleNumber();

                /*
                |--------------------------------------------------------------------------
                | Create sale
                |--------------------------------------------------------------------------
                */

                $sale = Sale::create([
                    'sale_number' => $saleNumber,
                    'sale_date' => $validated['sale_date'],
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'tax' => $tax,
                    'total' => $total,
                    'payment_method' =>
                        $validated['payment_method'],
                    'amount_received' =>
                        $amountReceived,
                    'change' => $change,
                    'status' => 'Completed',
                    'created_by' => $user->id,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Create sale items
                |--------------------------------------------------------------------------
                */

                foreach ($validated['items'] as $item) {
                    $product = $products[
                        $item['product_id']
                    ];

                    $quantity = (float) $item['quantity'];

                    $unitPrice = (float) $product->selling_price;

                    $itemSubtotal =
                        $quantity * $unitPrice;

                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'subtotal' => $itemSubtotal,
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Deduct inventory using product recipes
                |--------------------------------------------------------------------------
                */

                $this->deductInventoryForSale(
                    $validated['items'],
                    $products,
                    $sale,
                    $user->id
                );

                return $sale;
            });

            return redirect()
                ->route(
                    'sales.show',
                    $sale
                )
                ->with(
                    'success',
                    'Sale completed successfully.'
                );
        } catch (\RuntimeException $exception) {
            return back()
                ->withErrors([
                    'sale' => $exception->getMessage(),
                ])
                ->withInput();
        } catch (\Throwable $exception) {
            report($exception);

            return back()
                ->withErrors([
                    'sale' =>
                        'The sale could not be completed. Please try again.',
                ])
                ->withInput();
        }
    }

    /**
     * Display a single sale.
     */
    public function show(
        Request $request,
        Sale $sale
    ): View {
        $user = $request->user();

        $sale->load([
            'creator',
            'items.product.category',
        ]);

        return view('sales.show', [
            'user' => $user,
            'sale' => $sale,
        ]);
    }

    /**
     * Cancel a completed sale.
     *
     * Inventory is restored based on the
     * original product recipes.
     */
    public function cancel(
        Request $request,
        Sale $sale
    ): RedirectResponse {
        $user = $request->user();

        if ($sale->status === 'Cancelled') {
            return back()
                ->withErrors([
                    'sale' =>
                        'This sale has already been cancelled.',
                ]);
        }

        try {
            DB::transaction(function () use (
                $sale,
                $user
            ) {
                $sale->load([
                    'items.product.recipe.items.inventoryItem',
                ]);

                foreach ($sale->items as $saleItem) {
                    $product = $saleItem->product;

                    if (
                        !$product ||
                        !$product->recipe
                    ) {
                        continue;
                    }

                    foreach (
                        $product->recipe->items
                        as $recipeItem
                    ) {
                        $inventoryItem =
                            $recipeItem->inventoryItem;

                        if (!$inventoryItem) {
                            continue;
                        }

                        $restoreQuantity =
                            (float) $recipeItem->quantity
                            * (float) $saleItem->quantity;

                        /*
                        |--------------------------------------------------------------------------
                        | Lock inventory row
                        |--------------------------------------------------------------------------
                        */

                        $inventoryItem = InventoryItem::where(
                            'id',
                            $inventoryItem->id
                        )
                            ->lockForUpdate()
                            ->firstOrFail();

                        /*
                        |--------------------------------------------------------------------------
                        | Record quantity before restoration
                        |--------------------------------------------------------------------------
                        */

                        $quantityBefore =
                            (float) $inventoryItem->quantity;

                        /*
                        |--------------------------------------------------------------------------
                        | Calculate quantity after restoration
                        |--------------------------------------------------------------------------
                        */

                        $quantityAfter =
                            $quantityBefore
                            + $restoreQuantity;

                        /*
                        |--------------------------------------------------------------------------
                        | Restore inventory
                        |--------------------------------------------------------------------------
                        */

                        $inventoryItem->update([
                            'quantity' => $quantityAfter,
                        ]);

                        /*
                        |--------------------------------------------------------------------------
                        | Record stock movement
                        |--------------------------------------------------------------------------
                        */

                        StockMovement::create([
                            'inventory_item_id' =>
                                $inventoryItem->id,

                            'user_id' =>
                                $user->id,

                            'type' =>
                                'stock_in',

                            'quantity' =>
                                $restoreQuantity,

                            'quantity_before' =>
                                $quantityBefore,

                            'quantity_after' =>
                                $quantityAfter,

                            'reference_type' =>
                                Sale::class,

                            'reference_id' =>
                                $sale->id,

                            'reason' =>
                                'Inventory restored from cancelled sale '
                                . $sale->sale_number,
                        ]);
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Mark sale as cancelled
                |--------------------------------------------------------------------------
                */

                $sale->update([
                    'status' => 'Cancelled',
                ]);
            });

            return redirect()
                ->route(
                    'sales.show',
                    $sale
                )
                ->with(
                    'success',
                    'Sale cancelled and inventory restored successfully.'
                );
        } catch (\Throwable $exception) {
            report($exception);

            return back()
                ->withErrors([
                    'sale' =>
                        'The sale could not be cancelled.',
                ]);
        }
    }

    /**
     * Deduct inventory according to product recipes.
     */
    private function deductInventoryForSale(
        array $items,
        $products,
        Sale $sale,
        int $userId
    ): void {
        /*
        |--------------------------------------------------------------------------
        | Calculate total ingredient consumption
        |--------------------------------------------------------------------------
        |
        | If the same product appears more than once,
        | its ingredient requirements are combined.
        |
        */

        $requiredInventory = [];

        foreach ($items as $item) {
            $product = $products[
                $item['product_id']
            ];

            $saleQuantity =
                (float) $item['quantity'];

            /*
            |--------------------------------------------------------------------------
            | Product without a recipe
            |--------------------------------------------------------------------------
            |
            | A product may exist without a recipe.
            | In that case there is no inventory ingredient
            | to deduct.
            |
            */

            if (!$product->recipe) {
                continue;
            }

            foreach (
                $product->recipe->items
                as $recipeItem
            ) {
                $inventoryItem =
                    $recipeItem->inventoryItem;

                if (!$inventoryItem) {
                    continue;
                }

                $requiredQuantity =
                    (float) $recipeItem->quantity
                    * $saleQuantity;

                if (!isset(
                    $requiredInventory[
                        $inventoryItem->id
                    ]
                )) {
                    $requiredInventory[
                        $inventoryItem->id
                    ] = 0;
                }

                $requiredInventory[
                    $inventoryItem->id
                ] += $requiredQuantity;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Check inventory availability
        |--------------------------------------------------------------------------
        */

        foreach (
            $requiredInventory as $inventoryItemId => $requiredQuantity
        ) {
            $inventoryItem = InventoryItem::where(
                'id',
                $inventoryItemId
            )
                ->lockForUpdate()
                ->first();

            if (!$inventoryItem) {
                throw new \RuntimeException(
                    'An inventory item required by the sale could not be found.'
                );
            }

            $availableQuantity =
                (float) $inventoryItem->quantity;

            if (
                $availableQuantity
                < $requiredQuantity
            ) {
                throw new \RuntimeException(
                    'Insufficient stock for '
                    . $inventoryItem->name
                    . '. Available: '
                    . $availableQuantity
                    . ', Required: '
                    . $requiredQuantity
                    . '.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Deduct inventory
        |--------------------------------------------------------------------------
        */

        foreach (
            $requiredInventory as $inventoryItemId => $requiredQuantity
        ) {
            $inventoryItem = InventoryItem::where(
                'id',
                $inventoryItemId
            )
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Capture quantity before deduction
            |--------------------------------------------------------------------------
            */

            $quantityBefore =
                (float) $inventoryItem->quantity;

            /*
            |--------------------------------------------------------------------------
            | Calculate quantity after deduction
            |--------------------------------------------------------------------------
            */

            $quantityAfter =
                $quantityBefore
                - $requiredQuantity;

            /*
            |--------------------------------------------------------------------------
            | Prevent negative inventory
            |--------------------------------------------------------------------------
            */

            if ($quantityAfter < 0) {
                throw new \RuntimeException(
                    'Insufficient stock for '
                    . $inventoryItem->name
                    . '.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Update inventory
            |--------------------------------------------------------------------------
            */

            $inventoryItem->update([
                'quantity' => $quantityAfter,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Record stock movement
            |--------------------------------------------------------------------------
            */

            StockMovement::create([
                'inventory_item_id' =>
                    $inventoryItem->id,

                'user_id' =>
                    $userId,

                'type' =>
                    'stock_out',

                'quantity' =>
                    $requiredQuantity,

                'quantity_before' =>
                    $quantityBefore,

                'quantity_after' =>
                    $quantityAfter,

                'reference_type' =>
                    Sale::class,

                'reference_id' =>
                    $sale->id,

                'reason' =>
                    'Inventory used for sale '
                    . $sale->sale_number,
            ]);
        }
    }

    /**
     * Generate a unique sale number.
     */
    private function generateSaleNumber(): string
    {
        $lastSale = Sale::latest('id')->first();

        $nextNumber = $lastSale
            ? $lastSale->id + 1
            : 1;

        do {
            $saleNumber =
                'SALE-'
                . str_pad(
                    (string) $nextNumber,
                    6,
                    '0',
                    STR_PAD_LEFT
                );

            $exists = Sale::where(
                'sale_number',
                $saleNumber
            )->exists();

            if ($exists) {
                $nextNumber++;
            }
        } while ($exists);

        return $saleNumber;
    }
}