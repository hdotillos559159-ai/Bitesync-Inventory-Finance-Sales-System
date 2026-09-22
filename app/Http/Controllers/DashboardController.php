<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\InventoryItem;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * ================================================================
     * CEO / ADMIN DASHBOARD
     * ================================================================
     *
     * This dashboard uses real BiteSync database records.
     */
    public function admin(Request $request): View
    {
        $today = Carbon::today();

        $monthStart = $today->copy()->startOfMonth();
        $monthEnd = $today->copy()->endOfMonth();

        /*
        |--------------------------------------------------------------------------
        | SALES
        |--------------------------------------------------------------------------
        */

        $totalSales = (float) Sale::query()
            ->where('status', 'Completed')
            ->sum('total');

        $monthlySales = (float) Sale::query()
            ->where('status', 'Completed')
            ->whereBetween('sale_date', [
                $monthStart->copy()->startOfDay(),
                $monthEnd->copy()->endOfDay(),
            ])
            ->sum('total');

        $salesCount = Sale::query()
            ->where('status', 'Completed')
            ->count();

        $monthlySalesCount = Sale::query()
            ->where('status', 'Completed')
            ->whereBetween('sale_date', [
                $monthStart->copy()->startOfDay(),
                $monthEnd->copy()->endOfDay(),
            ])
            ->count();

        /*
        |--------------------------------------------------------------------------
        | PURCHASES
        |--------------------------------------------------------------------------
        */

        $totalPurchases = (float) Purchase::query()
            ->where(
                'status',
                '!=',
                Purchase::STATUS_CANCELLED
            )
            ->sum('total');

        $monthlyPurchases = (float) Purchase::query()
            ->where(
                'status',
                '!=',
                Purchase::STATUS_CANCELLED
            )
            ->whereBetween('purchase_date', [
                $monthStart->toDateString(),
                $monthEnd->toDateString(),
            ])
            ->sum('total');

        $purchaseCount = Purchase::query()
            ->where(
                'status',
                '!=',
                Purchase::STATUS_CANCELLED
            )
            ->count();

        $pendingPurchases = Purchase::query()
            ->where(
                'status',
                Purchase::STATUS_PENDING_APPROVAL
            )
            ->count();

        $orderedPurchases = Purchase::query()
            ->where(
                'status',
                Purchase::STATUS_ORDERED
            )
            ->count();

        $receivedPurchases = Purchase::query()
            ->where(
                'status',
                Purchase::STATUS_RECEIVED
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | EXPENSES
        |--------------------------------------------------------------------------
        */

        $totalExpenses = (float) Expense::query()
            ->where('status', 'Recorded')
            ->sum('amount');

        $monthlyExpenses = (float) Expense::query()
            ->where('status', 'Recorded')
            ->whereBetween('expense_date', [
                $monthStart->toDateString(),
                $monthEnd->toDateString(),
            ])
            ->sum('amount');

        $expenseCount = Expense::query()
            ->where('status', 'Recorded')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | INVENTORY
        |--------------------------------------------------------------------------
        */

        $inventoryItems = InventoryItem::query()
            ->get([
                'id',
                'quantity',
                'minimum_stock',
                'unit_cost',
            ]);

        $inventoryCount = $inventoryItems->count();

        $inventoryValue = (float) $inventoryItems->sum(
            function ($item) {
                return (float) $item->quantity
                    * (float) $item->unit_cost;
            }
        );

        $lowStockCount = $inventoryItems->filter(
            function ($item) {
                return (float) $item->quantity > 0
                    && (float) $item->quantity
                        <= (float) $item->minimum_stock;
            }
        )->count();

        $outOfStockCount = $inventoryItems->filter(
            function ($item) {
                return (float) $item->quantity <= 0;
            }
        )->count();

        $normalStockCount = max(
            0,
            $inventoryCount
                - $lowStockCount
                - $outOfStockCount
        );

        /*
        |--------------------------------------------------------------------------
        | FINANCIAL POSITION
        |--------------------------------------------------------------------------
        */

        $netPosition =
            $totalSales
            - $totalPurchases
            - $totalExpenses;

        $monthlyNetPosition =
            $monthlySales
            - $monthlyPurchases
            - $monthlyExpenses;

        /*
        |--------------------------------------------------------------------------
        | 30-DAY SALES VS PURCHASES GRAPH
        |--------------------------------------------------------------------------
        */

        $trendStart = $today->copy()->subDays(29);

        $salesByDay = Sale::query()
            ->where('status', 'Completed')
            ->whereBetween('sale_date', [
                $trendStart->copy()->startOfDay(),
                $today->copy()->endOfDay(),
            ])
            ->selectRaw(
                'DATE(sale_date) as report_date, SUM(total) as total'
            )
            ->groupBy(DB::raw('DATE(sale_date)'))
            ->pluck('total', 'report_date');

        $purchasesByDay = Purchase::query()
            ->where(
                'status',
                '!=',
                Purchase::STATUS_CANCELLED
            )
            ->whereBetween('purchase_date', [
                $trendStart->toDateString(),
                $today->toDateString(),
            ])
            ->selectRaw(
                'purchase_date as report_date, SUM(total) as total'
            )
            ->groupBy('purchase_date')
            ->pluck('total', 'report_date');

        $salesPurchaseTrend = [];

        for (
            $date = $trendStart->copy();
            $date->lte($today);
            $date->addDay()
        ) {
            $key = $date->toDateString();

            $salesPurchaseTrend[] = [
                'date' => $date->format('M d'),

                'sales' => round(
                    (float) ($salesByDay[$key] ?? 0),
                    2
                ),

                'purchases' => round(
                    (float) ($purchasesByDay[$key] ?? 0),
                    2
                ),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | EXPENSE BREAKDOWN GRAPH
        |--------------------------------------------------------------------------
        */

        $expenseBreakdown = Expense::query()
            ->where('status', 'Recorded')
            ->select(
                'category',
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('category')
            ->orderByDesc('total')
            ->get()
            ->map(
                function ($expense) {
                    return [
                        'category' => $expense->category,

                        'total' => round(
                            (float) $expense->total,
                            2
                        ),
                    ];
                }
            )
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | PURCHASE STATUS GRAPH
        |--------------------------------------------------------------------------
        */

        $purchaseStatusOrder = [
            Purchase::STATUS_DRAFT,
            Purchase::STATUS_PENDING_APPROVAL,
            Purchase::STATUS_APPROVED,
            Purchase::STATUS_REJECTED,
            Purchase::STATUS_ORDERED,
            Purchase::STATUS_PARTIALLY_RECEIVED,
            Purchase::STATUS_RECEIVED,
            Purchase::STATUS_CANCELLED,
        ];

        $purchaseStatusCounts = Purchase::query()
            ->select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('status')
            ->pluck(
                'total',
                'status'
            );

        $purchaseStatuses = [];

        foreach ($purchaseStatusOrder as $status) {
            $purchaseStatuses[] = [
                'status' => $status,

                'count' => (int) (
                    $purchaseStatusCounts[$status] ?? 0
                ),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | INVENTORY HEALTH GRAPH
        |--------------------------------------------------------------------------
        */

        $inventoryHealth = [
            [
                'status' => 'Normal',
                'count' => $normalStockCount,
            ],

            [
                'status' => 'Low Stock',
                'count' => $lowStockCount,
            ],

            [
                'status' => 'Out of Stock',
                'count' => $outOfStockCount,
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | RECENT SALES
        |--------------------------------------------------------------------------
        */

        $recentSales = Sale::query()
            ->with('creator')
            ->where('status', 'Completed')
            ->latest('sale_date')
            ->limit(6)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RECENT PURCHASES
        |--------------------------------------------------------------------------
        */

        $recentPurchases = Purchase::query()
            ->with([
                'supplier',
                'creator',
            ])
            ->where(
                'status',
                '!=',
                Purchase::STATUS_CANCELLED
            )
            ->latest('purchase_date')
            ->limit(6)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RECENT EXPENSES
        |--------------------------------------------------------------------------
        */

        $recentExpenses = Expense::query()
            ->with('user')
            ->where('status', 'Recorded')
            ->latest('expense_date')
            ->limit(6)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | ADMIN DASHBOARD DATA
        |--------------------------------------------------------------------------
        */

        return view('dashboard.admin', [

            'user' => $request->user(),

            // Sales
            'totalSales' => $totalSales,
            'monthlySales' => $monthlySales,
            'salesCount' => $salesCount,
            'monthlySalesCount' => $monthlySalesCount,

            // Purchases
            'totalPurchases' => $totalPurchases,
            'monthlyPurchases' => $monthlyPurchases,
            'purchaseCount' => $purchaseCount,
            'pendingPurchases' => $pendingPurchases,
            'orderedPurchases' => $orderedPurchases,
            'receivedPurchases' => $receivedPurchases,

            // Expenses
            'totalExpenses' => $totalExpenses,
            'monthlyExpenses' => $monthlyExpenses,
            'expenseCount' => $expenseCount,

            // Inventory
            'inventoryCount' => $inventoryCount,
            'inventoryValue' => $inventoryValue,
            'normalStockCount' => $normalStockCount,
            'lowStockCount' => $lowStockCount,
            'outOfStockCount' => $outOfStockCount,

            // Financial position
            'netPosition' => $netPosition,
            'monthlyNetPosition' => $monthlyNetPosition,

            // Graphs
            'salesPurchaseTrend' => $salesPurchaseTrend,
            'expenseBreakdown' => $expenseBreakdown,
            'purchaseStatuses' => $purchaseStatuses,
            'inventoryHealth' => $inventoryHealth,

            // Recent activity
            'recentSales' => $recentSales,
            'recentPurchases' => $recentPurchases,
            'recentExpenses' => $recentExpenses,
        ]);
    }


    /**
     * ================================================================
     * FINANCE DASHBOARD
     * ================================================================
     */
    public function finance(Request $request): View
    {
        return view('dashboard.finance', [
            'user' => $request->user(),
        ]);
    }


    /**
     * ================================================================
     * PROCUREMENT DASHBOARD
     * ================================================================
     *
     * Procurement monitors ALL purchasing activity in BiteSync.
     *
     * IMPORTANT:
     *
     * Purchases are NOT filtered by created_by.
     *
     * Therefore purchases created by:
     *
     * - CEO / Admin
     * - Procurement
     * - Other authorized users
     *
     * are included in the Procurement Dashboard.
     */
    public function procurement(Request $request): View
    {
        $today = Carbon::today();

        /*
        |--------------------------------------------------------------------------
        | PURCHASE OVERVIEW
        |--------------------------------------------------------------------------
        |
        | All non-cancelled purchases are counted regardless of creator.
        |
        */

        $purchaseCount = Purchase::query()
            ->where(
                'status',
                '!=',
                Purchase::STATUS_CANCELLED
            )
            ->count();

        $pendingApprovalCount = Purchase::query()
            ->where(
                'status',
                Purchase::STATUS_PENDING_APPROVAL
            )
            ->count();

        $toReceiveCount = Purchase::query()
            ->whereIn('status', [
                Purchase::STATUS_ORDERED,
                Purchase::STATUS_PARTIALLY_RECEIVED,
            ])
            ->count();

        $receivedPurchaseCount = Purchase::query()
            ->where(
                'status',
                Purchase::STATUS_RECEIVED
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | PURCHASE VALUE
        |--------------------------------------------------------------------------
        */

        $totalPurchases = (float) Purchase::query()
            ->where(
                'status',
                '!=',
                Purchase::STATUS_CANCELLED
            )
            ->sum('total');

        $monthlyPurchases = (float) Purchase::query()
            ->where(
                'status',
                '!=',
                Purchase::STATUS_CANCELLED
            )
            ->whereBetween('purchase_date', [
                $today->copy()
                    ->startOfMonth()
                    ->toDateString(),

                $today->copy()
                    ->endOfMonth()
                    ->toDateString(),
            ])
            ->sum('total');


        /*
        |--------------------------------------------------------------------------
        | INVENTORY HEALTH
        |--------------------------------------------------------------------------
        */

        $inventoryItems = InventoryItem::query()
            ->get([
                'id',
                'quantity',
                'minimum_stock',
                'unit_cost',
                'name',
            ]);

        $inventoryCount = $inventoryItems->count();

        $lowStockCount = $inventoryItems->filter(
            function ($item) {
                return (float) $item->quantity > 0
                    && (float) $item->quantity
                        <= (float) $item->minimum_stock;
            }
        )->count();

        $outOfStockCount = $inventoryItems->filter(
            function ($item) {
                return (float) $item->quantity <= 0;
            }
        )->count();

        $normalStockCount = max(
            0,
            $inventoryCount
                - $lowStockCount
                - $outOfStockCount
        );


        /*
        |--------------------------------------------------------------------------
        | INVENTORY ITEMS NEEDING ATTENTION
        |--------------------------------------------------------------------------
        */

        $stockAlerts = InventoryItem::query()
            ->with([
                'category',
                'unit',
            ])
            ->where(
                function ($query) {
                    $query
                        ->where(
                            'quantity',
                            '<=',
                            0
                        )
                        ->orWhere(
                            function ($lowQuery) {
                                $lowQuery
                                    ->where(
                                        'quantity',
                                        '>',
                                        0
                                    )
                                    ->whereColumn(
                                        'quantity',
                                        '<=',
                                        'minimum_stock'
                                    );
                            }
                        );
                }
            )
            ->orderBy('quantity')
            ->limit(6)
            ->get();

        $stockAlertCount =
            $lowStockCount
            + $outOfStockCount;


        /*
        |--------------------------------------------------------------------------
        | 30-DAY PURCHASE ACTIVITY
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | This query includes ALL non-cancelled purchases.
        |
        | There is intentionally NO:
        |
        | where('created_by', ...)
        |
        | filter.
        |
        | Therefore an Admin-created purchase is included.
        |
        | SUM(total) is used because the graph represents DAILY PURCHASE
        | VALUE, not simply the number of purchase orders.
        |
        */

        $trendStart = $today->copy()->subDays(29);

        $purchasesByDay = Purchase::query()
            ->where(
                'status',
                '!=',
                Purchase::STATUS_CANCELLED
            )
            ->whereBetween('purchase_date', [
                $trendStart->toDateString(),
                $today->toDateString(),
            ])
            ->selectRaw(
                'purchase_date as report_date, SUM(total) as total'
            )
            ->groupBy('purchase_date')
            ->pluck(
                'total',
                'report_date'
            );

        $purchaseActivity = [];

        for (
            $date = $trendStart->copy();
            $date->lte($today);
            $date->addDay()
        ) {
            $key = $date->toDateString();

            $purchaseActivity[] = [
                'date' => $date->format('M d'),

                'total' => round(
                    (float) ($purchasesByDay[$key] ?? 0),
                    2
                ),
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | PURCHASE STATUS
        |--------------------------------------------------------------------------
        */

        $purchaseStatusOrder = [
            Purchase::STATUS_DRAFT,
            Purchase::STATUS_PENDING_APPROVAL,
            Purchase::STATUS_APPROVED,
            Purchase::STATUS_REJECTED,
            Purchase::STATUS_ORDERED,
            Purchase::STATUS_PARTIALLY_RECEIVED,
            Purchase::STATUS_RECEIVED,
            Purchase::STATUS_CANCELLED,
        ];

        $purchaseStatusCounts = Purchase::query()
            ->select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('status')
            ->pluck(
                'total',
                'status'
            );

        $purchaseStatuses = [];

        foreach ($purchaseStatusOrder as $status) {

            $purchaseStatuses[] = [
                'status' => $status,

                'count' => (int) (
                    $purchaseStatusCounts[$status] ?? 0
                ),
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | RECENT PURCHASES
        |--------------------------------------------------------------------------
        |
        | All non-cancelled purchases are displayed.
        |
        | The creator relationship is still loaded so the actual creator
        | remains available.
        |
        */

        $recentPurchases = Purchase::query()
            ->with([
                'supplier',
                'creator',
            ])
            ->where(
                'status',
                '!=',
                Purchase::STATUS_CANCELLED
            )
            ->latest('purchase_date')
            ->latest('id')
            ->limit(6)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RECENT STOCK MOVEMENTS
        |--------------------------------------------------------------------------
        */

        $recentStockMovements = StockMovement::query()
            ->with([
                'inventoryItem',
                'user',
            ])
            ->latest()
            ->limit(6)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | SUPPLIERS
        |--------------------------------------------------------------------------
        */

        $supplierCount = Supplier::query()
            ->count();


        /*
        |--------------------------------------------------------------------------
        | INVENTORY VALUE
        |--------------------------------------------------------------------------
        */

        $inventoryValue = (float) $inventoryItems->sum(
            function ($item) {
                return (float) $item->quantity
                    * (float) $item->unit_cost;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | INVENTORY HEALTH DATA
        |--------------------------------------------------------------------------
        */

        $inventoryHealth = [
            [
                'status' => 'Normal',
                'count' => $normalStockCount,
            ],

            [
                'status' => 'Low Stock',
                'count' => $lowStockCount,
            ],

            [
                'status' => 'Out of Stock',
                'count' => $outOfStockCount,
            ],
        ];


        /*
        |--------------------------------------------------------------------------
        | PROCUREMENT DASHBOARD DATA
        |--------------------------------------------------------------------------
        */

        return view('dashboard.procurement', [

            'user' => $request->user(),

            // Purchase overview
            'purchaseCount' => $purchaseCount,
            'pendingApprovalCount' => $pendingApprovalCount,
            'toReceiveCount' => $toReceiveCount,
            'receivedPurchaseCount' => $receivedPurchaseCount,

            // Purchase value
            'totalPurchases' => $totalPurchases,
            'monthlyPurchases' => $monthlyPurchases,

            // Inventory
            'inventoryCount' => $inventoryCount,
            'inventoryValue' => $inventoryValue,
            'normalStockCount' => $normalStockCount,
            'lowStockCount' => $lowStockCount,
            'outOfStockCount' => $outOfStockCount,

            // Inventory alerts
            'stockAlerts' => $stockAlerts,
            'stockAlertCount' => $stockAlertCount,

            // Charts
            'purchaseActivity' => $purchaseActivity,
            'purchaseStatuses' => $purchaseStatuses,
            'inventoryHealth' => $inventoryHealth,

            // Recent activity
            'recentPurchases' => $recentPurchases,
            'recentStockMovements' => $recentStockMovements,

            // Suppliers
            'supplierCount' => $supplierCount,
        ]);
    }
}