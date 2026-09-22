<?php

namespace App\Http\Controllers;

use App\Models\CashRemittance;
use App\Models\Expense;
use App\Models\InventoryItem;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REPORT INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $reportData = $this->getReportData($request);

        return view(
            'reports.index',
            $reportData
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PDF / PRINT REPORT
    |--------------------------------------------------------------------------
    |
    | Opens a clean printable report page.
    |
    */

    public function pdf(Request $request): View
    {
        $reportData = $this->getReportData($request);

        return view(
            'reports.pdf',
            $reportData
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EXCEL REPORT
    |--------------------------------------------------------------------------
    |
    | Creates a downloadable Excel-compatible .xls file.
    |
    */

    public function excel(Request $request): Response
    {
        $reportData = $this->getReportData($request);

        $startDate =
            $reportData['startDate'];

        $endDate =
            $reportData['endDate'];

        $userRole =
            $reportData['userRole'];

        /*
        |--------------------------------------------------------------------------
        | AVAILABLE REPORTS
        |--------------------------------------------------------------------------
        */

        $showSales =
            $reportData['showSales'];

        $showPurchases =
            $reportData['showPurchases'];

        $showExpenses =
            $reportData['showExpenses'];

        $showInventory =
            $reportData['showInventory'];

        $showRemittance =
            $reportData['showRemittance'];


        /*
        |--------------------------------------------------------------------------
        | REPORT DATA
        |--------------------------------------------------------------------------
        */

        $totalSales =
            $reportData['totalSales'];

        $salesCount =
            $reportData['salesCount'];

        $totalPurchases =
            $reportData['totalPurchases'];

        $purchaseCount =
            $reportData['purchaseCount'];

        $totalExpenses =
            $reportData['totalExpenses'];

        $expenseCount =
            $reportData['expenseCount'];

        $netAmount =
            $reportData['netAmount'];

        $inventoryValue =
            $reportData['inventoryValue'];

        $inventoryCount =
            $reportData['inventoryCount'];

        $lowStockCount =
            $reportData['lowStockCount'];

        $outOfStockCount =
            $reportData['outOfStockCount'];

        $expectedRemittance =
            $reportData['expectedRemittance'];

        $actualRemittance =
            $reportData['actualRemittance'];

        $remittanceVariance =
            $reportData['remittanceVariance'];

        $remittanceCount =
            $reportData['remittanceCount'];

        $dailySales =
            $reportData['dailySales'];

        $recentExpenses =
            $reportData['recentExpenses'];

        $recentRemittances =
            $reportData['recentRemittances'];


        /*
        |--------------------------------------------------------------------------
        | FILE NAME
        |--------------------------------------------------------------------------
        */

        $fileName =
            'BiteSync_Report_' .
            $startDate .
            '_to_' .
            $endDate .
            '.xls';


        /*
        |--------------------------------------------------------------------------
        | ROLE LABEL
        |--------------------------------------------------------------------------
        */

        $roleLabel =
            $userRole === 'CEO/Admin'
                ? 'CEO / Admin'
                : $userRole;


        /*
        |--------------------------------------------------------------------------
        | HTML EXCEL CONTENT
        |--------------------------------------------------------------------------
        */

        $html = '
<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<style>

body {
    font-family: Arial, sans-serif;
    color: #241a14;
}

h1 {
    font-size: 22px;
    margin-bottom: 5px;
}

h2 {
    font-size: 16px;
    margin-top: 25px;
    margin-bottom: 8px;
}

p {
    margin: 4px 0;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 18px;
}

th {
    background: #f4e4d4;
    color: #241a14;
    font-weight: bold;
    border: 1px solid #d9cec5;
    padding: 8px;
    text-align: left;
}

td {
    border: 1px solid #e4dcd4;
    padding: 7px;
}

.summary-label {
    font-weight: bold;
    background: #fbf8f5;
}

.money {
    text-align: right;
}

.number {
    text-align: right;
}

</style>

</head>

<body>

<h1>BiteSync Reports</h1>

<p>
    <strong>Report Period:</strong>
    ' . e($startDate) . '
    to
    ' . e($endDate) . '
</p>

<p>
    <strong>Generated:</strong>
    ' . e(now()->format('F d, Y h:i A')) . '
</p>

<p>
    <strong>Report Access:</strong>
    ' . e($roleLabel) . '
</p>
';


        /*
        |--------------------------------------------------------------------------
        | FINANCIAL SUMMARY
        |--------------------------------------------------------------------------
        |
        | Only CEO/Admin and Finance can see the full financial summary.
        |
        */

        if (
            $showSales ||
            $showPurchases ||
            $showExpenses
        ) {

            $html .= '
<h2>Financial Summary</h2>

<table>

<tr>
    <th>Report</th>
    <th>Amount</th>
    <th>Records</th>
</tr>
';


            if ($showSales) {

                $html .= '
<tr>

    <td class="summary-label">
        Total Sales
    </td>

    <td class="money">
        ' . number_format(
            $totalSales,
            2
        ) . '
    </td>

    <td class="number">
        ' . $salesCount . '
    </td>

</tr>
';

            }


            if ($showPurchases) {

                $html .= '
<tr>

    <td class="summary-label">
        Total Purchases
    </td>

    <td class="money">
        ' . number_format(
            $totalPurchases,
            2
        ) . '
    </td>

    <td class="number">
        ' . $purchaseCount . '
    </td>

</tr>
';

            }


            if ($showExpenses) {

                $html .= '
<tr>

    <td class="summary-label">
        Total Expenses
    </td>

    <td class="money">
        ' . number_format(
            $totalExpenses,
            2
        ) . '
    </td>

    <td class="number">
        ' . $expenseCount . '
    </td>

</tr>
';

            }


            /*
            |--------------------------------------------------------------------------
            | NET AMOUNT
            |--------------------------------------------------------------------------
            |
            | Only show Net Amount when all three financial categories
            | are available.
            |
            */

            if (
                $showSales &&
                $showPurchases &&
                $showExpenses
            ) {

                $html .= '
<tr>

    <td class="summary-label">
        Net Amount
    </td>

    <td class="money">
        ' . number_format(
            $netAmount,
            2
        ) . '
    </td>

    <td>
        —
    </td>

</tr>
';

            }


            $html .= '
</table>
';

        }


        /*
        |--------------------------------------------------------------------------
        | INVENTORY SUMMARY
        |--------------------------------------------------------------------------
        */

        if ($showInventory) {

            $html .= '
<h2>Inventory Summary</h2>

<table>

<tr>
    <th>Metric</th>
    <th>Value</th>
</tr>

<tr>

    <td class="summary-label">
        Inventory Items
    </td>

    <td class="number">
        ' . $inventoryCount . '
    </td>

</tr>

<tr>

    <td class="summary-label">
        Inventory Value
    </td>

    <td class="money">
        ' . number_format(
            $inventoryValue,
            2
        ) . '
    </td>

</tr>

<tr>

    <td class="summary-label">
        Low Stock Items
    </td>

    <td class="number">
        ' . $lowStockCount . '
    </td>

</tr>

<tr>

    <td class="summary-label">
        Out of Stock Items
    </td>

    <td class="number">
        ' . $outOfStockCount . '
    </td>

</tr>

</table>
';

        }


        /*
        |--------------------------------------------------------------------------
        | CASH REMITTANCE SUMMARY
        |--------------------------------------------------------------------------
        */

        if ($showRemittance) {

            $html .= '
<h2>Cash Remittance Summary</h2>

<table>

<tr>
    <th>Metric</th>
    <th>Value</th>
</tr>

<tr>

    <td class="summary-label">
        Expected Remittance
    </td>

    <td class="money">
        ' . number_format(
            $expectedRemittance,
            2
        ) . '
    </td>

</tr>

<tr>

    <td class="summary-label">
        Actual Remittance
    </td>

    <td class="money">
        ' . number_format(
            $actualRemittance,
            2
        ) . '
    </td>

</tr>

<tr>

    <td class="summary-label">
        Variance
    </td>

    <td class="money">
        ' . number_format(
            $remittanceVariance,
            2
        ) . '
    </td>

</tr>

<tr>

    <td class="summary-label">
        Remittance Records
    </td>

    <td class="number">
        ' . $remittanceCount . '
    </td>

</tr>

</table>
';

        }


        /*
        |--------------------------------------------------------------------------
        | DAILY SALES REPORT
        |--------------------------------------------------------------------------
        */

        if ($showSales) {

            $html .= '
<h2>Daily Sales Report</h2>

<table>

<tr>
    <th>Date</th>
    <th>Sales Amount</th>
    <th>Transactions</th>
</tr>
';


            if ($dailySales->count() > 0) {

                foreach ($dailySales as $daily) {

                    $date =
                        $daily->report_date
                        ? \Carbon\Carbon::parse(
                            $daily->report_date
                        )->format('M d, Y')
                        : '—';


                    $html .= '
<tr>

    <td>
        ' . e($date) . '
    </td>

    <td class="money">
        ' . number_format(
            (float) $daily->total_sales,
            2
        ) . '
    </td>

    <td class="number">
        ' . (int) $daily->transaction_count . '
    </td>

</tr>
';

                }

            } else {

                $html .= '
<tr>

    <td colspan="3">
        No sales records found for this period.
    </td>

</tr>
';

            }


            $html .= '
</table>
';

        }


        /*
        |--------------------------------------------------------------------------
        | RECENT EXPENSES
        |--------------------------------------------------------------------------
        */

        if ($showExpenses) {

            $html .= '
<h2>Recent Expenses</h2>

<table>

<tr>
    <th>Date</th>
    <th>Category</th>
    <th>Description</th>
    <th>Amount</th>
    <th>Recorded By</th>
</tr>
';


            if ($recentExpenses->count() > 0) {

                foreach ($recentExpenses as $expense) {

                    $expenseDate =
                        $expense->expense_date
                        ? $expense->expense_date->format(
                            'M d, Y'
                        )
                        : '—';


                    $recordedBy = '—';


                    if ($expense->user) {

                        $recordedBy =
                            $expense->user->name
                            ?? $expense->user->full_name
                            ?? '—';

                    }


                    $html .= '
<tr>

    <td>
        ' . e($expenseDate) . '
    </td>

    <td>
        ' . e(
            $expense->category
            ?? '—'
        ) . '
    </td>

    <td>
        ' . e(
            $expense->description
            ?? '—'
        ) . '
    </td>

    <td class="money">
        ' . number_format(
            (float) $expense->amount,
            2
        ) . '
    </td>

    <td>
        ' . e($recordedBy) . '
    </td>

</tr>
';

                }

            } else {

                $html .= '
<tr>

    <td colspan="5">
        No expense records found for this period.
    </td>

</tr>
';

            }


            $html .= '
</table>
';

        }


        /*
        |--------------------------------------------------------------------------
        | RECENT CASH REMITTANCES
        |--------------------------------------------------------------------------
        */

        if ($showRemittance) {

            $html .= '
<h2>Recent Cash Remittances</h2>

<table>

<tr>
    <th>Date</th>
    <th>Expected Amount</th>
    <th>Actual Amount</th>
    <th>Variance</th>
</tr>
';


            if ($recentRemittances->count() > 0) {

                foreach (
                    $recentRemittances
                    as $remittance
                ) {

                    $remittanceDate =
                        $remittance->remittance_date
                        ? $remittance->remittance_date->format(
                            'M d, Y'
                        )
                        : '—';


                    $expected =
                        (float) (
                            $remittance->expected_amount
                            ?? 0
                        );


                    $actual =
                        (float) (
                            $remittance->actual_amount
                            ?? 0
                        );


                    $variance =
                        $actual -
                        $expected;


                    $html .= '
<tr>

    <td>
        ' . e($remittanceDate) . '
    </td>

    <td class="money">
        ' . number_format(
            $expected,
            2
        ) . '
    </td>

    <td class="money">
        ' . number_format(
            $actual,
            2
        ) . '
    </td>

    <td class="money">
        ' . number_format(
            $variance,
            2
        ) . '
    </td>

</tr>
';

                }

            } else {

                $html .= '
<tr>

    <td colspan="4">
        No cash remittance records found for this period.
    </td>

</tr>
';

            }


            $html .= '
</table>
';

        }


        $html .= '
</body>
</html>
';


        /*
        |--------------------------------------------------------------------------
        | RETURN DOWNLOAD
        |--------------------------------------------------------------------------
        */

        return response(
            $html,
            200,
            [
                'Content-Type' =>
                    'application/vnd.ms-excel; charset=UTF-8',

                'Content-Disposition' =>
                    'attachment; filename="' .
                    $fileName .
                    '"',

                'Cache-Control' =>
                    'max-age=0',
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BUILD REPORT DATA
    |--------------------------------------------------------------------------
    |
    | All report calculations are kept in one place.
    |
    */

    private function getReportData(
        Request $request
    ): array {

        /*
        |--------------------------------------------------------------------------
        | CURRENT USER
        |--------------------------------------------------------------------------
        */

        $user =
            $request->user();

        $userRole =
            $user->role;


        /*
        |--------------------------------------------------------------------------
        | REPORT PERMISSIONS
        |--------------------------------------------------------------------------
        |
        | CEO/Admin:
        | Everything.
        |
        | Finance:
        | Sales, Purchases, Expenses, Inventory and Remittance.
        |
        | Procurement:
        | Purchases and Inventory only.
        |
        */

        $showSales =
            in_array(
                $userRole,
                [
                    'CEO/Admin',
                    'Finance',
                ],
                true
            );


        $showPurchases =
            in_array(
                $userRole,
                [
                    'CEO/Admin',
                    'Finance',
                    'Procurement',
                ],
                true
            );


        $showExpenses =
            in_array(
                $userRole,
                [
                    'CEO/Admin',
                    'Finance',
                ],
                true
            );


        $showInventory =
            in_array(
                $userRole,
                [
                    'CEO/Admin',
                    'Finance',
                    'Procurement',
                ],
                true
            );


        $showRemittance =
            in_array(
                $userRole,
                [
                    'CEO/Admin',
                    'Finance',
                ],
                true
            );


        /*
        |--------------------------------------------------------------------------
        | DATE RANGE
        |--------------------------------------------------------------------------
        */

        $startDate =
            $request->input(
                'start_date',
                now()
                    ->startOfMonth()
                    ->format('Y-m-d')
            );


        $endDate =
            $request->input(
                'end_date',
                now()->format('Y-m-d')
            );


        if ($startDate > $endDate) {

            [$startDate, $endDate] = [
                $endDate,
                $startDate,
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | SALES
        |--------------------------------------------------------------------------
        */

        $salesDateColumn =
            $this->getExistingColumn(
                'sales',
                [
                    'sale_date',
                    'date',
                    'created_at',
                ]
            );


        $salesAmountColumn =
            $this->getExistingColumn(
                'sales',
                [
                    'total_amount',
                    'total',
                    'amount',
                    'grand_total',
                ]
            );


        $totalSales = 0;

        $salesCount = 0;

        $dailySales = collect();


        if (
            $showSales &&
            $salesDateColumn &&
            $salesAmountColumn
        ) {

            $salesQuery =
                Sale::query()
                    ->whereBetween(
                        $salesDateColumn,
                        [
                            $startDate,
                            $endDate,
                        ]
                    );


            $totalSales =
                (float) $salesQuery
                    ->sum(
                        $salesAmountColumn
                    );


            $salesCount =
                $salesQuery->count();


            $dailySales =
                Sale::query()
                    ->selectRaw(
                        "DATE($salesDateColumn) as report_date"
                    )
                    ->selectRaw(
                        "SUM($salesAmountColumn) as total_sales"
                    )
                    ->selectRaw(
                        "COUNT(*) as transaction_count"
                    )
                    ->whereBetween(
                        $salesDateColumn,
                        [
                            $startDate,
                            $endDate,
                        ]
                    )
                    ->groupByRaw(
                        "DATE($salesDateColumn)"
                    )
                    ->orderBy(
                        'report_date',
                        'desc'
                    )
                    ->get();

        }


        /*
        |--------------------------------------------------------------------------
        | PURCHASES
        |--------------------------------------------------------------------------
        */

        $purchaseDateColumn =
            $this->getExistingColumn(
                'purchases',
                [
                    'purchase_date',
                    'date',
                    'created_at',
                ]
            );


        /*
         * Your Purchase model uses "total".
         *
         * The fallback columns are retained so the report
         * remains compatible if the database changes.
         */
        $purchaseAmountColumn =
            $this->getExistingColumn(
                'purchases',
                [
                    'total_amount',
                    'total',
                    'amount',
                    'grand_total',
                ]
            );


        $totalPurchases = 0;

        $purchaseCount = 0;


        if (
            $showPurchases &&
            $purchaseDateColumn &&
            $purchaseAmountColumn
        ) {

            $purchaseQuery =
                Purchase::query()
                    ->whereBetween(
                        $purchaseDateColumn,
                        [
                            $startDate,
                            $endDate,
                        ]
                    );


            $totalPurchases =
                (float) $purchaseQuery
                    ->sum(
                        $purchaseAmountColumn
                    );


            $purchaseCount =
                $purchaseQuery->count();

        }


        /*
        |--------------------------------------------------------------------------
        | EXPENSES
        |--------------------------------------------------------------------------
        */

        $totalExpenses = 0;

        $expenseCount = 0;

        $recentExpenses = collect();


        if ($showExpenses) {

            $expenseQuery =
                Expense::query()
                    ->whereBetween(
                        'expense_date',
                        [
                            $startDate,
                            $endDate,
                        ]
                    )
                    ->where(
                        'status',
                        '!=',
                        'Cancelled'
                    );


            $totalExpenses =
                (float) $expenseQuery
                    ->sum('amount');


            $expenseCount =
                $expenseQuery->count();


            /*
            |--------------------------------------------------------------------------
            | RECENT EXPENSES
            |--------------------------------------------------------------------------
            */

            $recentExpenses =
                Expense::query()
                    ->with('user')
                    ->whereBetween(
                        'expense_date',
                        [
                            $startDate,
                            $endDate,
                        ]
                    )
                    ->where(
                        'status',
                        '!=',
                        'Cancelled'
                    )
                    ->latest(
                        'expense_date'
                    )
                    ->latest('id')
                    ->take(5)
                    ->get();

        }


        /*
        |--------------------------------------------------------------------------
        | NET AMOUNT
        |--------------------------------------------------------------------------
        |
        | Only calculated for roles that can see all three
        | financial components.
        |
        */

        $netAmount = 0;


        if (
            $showSales &&
            $showPurchases &&
            $showExpenses
        ) {

            $netAmount =
                $totalSales
                - $totalPurchases
                - $totalExpenses;

        }


        /*
        |--------------------------------------------------------------------------
        | INVENTORY
        |--------------------------------------------------------------------------
        */

        $inventoryItems =
            collect();


        $inventoryValue = 0;

        $inventoryCount = 0;

        $lowStockCount = 0;

        $outOfStockCount = 0;


        if ($showInventory) {

            $inventoryItems =
                InventoryItem::query()->get();


            $inventoryCount =
                $inventoryItems->count();


            foreach (
                $inventoryItems
                as $item
            ) {

                $quantity =
                    (float) (
                        $item->quantity
                        ?? 0
                    );


                $minimumStock =
                    (float) (
                        $item->minimum_stock
                        ?? 0
                    );


                $unitCost =
                    (float) (
                        $item->unit_cost
                        ?? 0
                    );


                $inventoryValue +=
                    $quantity *
                    $unitCost;


                if ($quantity <= 0) {

                    $outOfStockCount++;

                } elseif (
                    $minimumStock > 0 &&
                    $quantity <= $minimumStock
                ) {

                    $lowStockCount++;

                }

            }

        }


        /*
        |--------------------------------------------------------------------------
        | CASH REMITTANCE
        |--------------------------------------------------------------------------
        */

        $expectedRemittance = 0;

        $actualRemittance = 0;

        $remittanceVariance = 0;

        $remittanceCount = 0;

        $recentRemittances = collect();


        if (
            $showRemittance &&
            Schema::hasTable(
                'cash_remittances'
            )
        ) {

            $remittanceQuery =
                CashRemittance::query()
                    ->whereBetween(
                        'remittance_date',
                        [
                            $startDate,
                            $endDate,
                        ]
                    );


            $expectedRemittance =
                (float) $remittanceQuery
                    ->sum(
                        'expected_amount'
                    );


            $actualRemittance =
                (float) $remittanceQuery
                    ->sum(
                        'actual_amount'
                    );


            $remittanceVariance =
                $actualRemittance
                - $expectedRemittance;


            $remittanceCount =
                $remittanceQuery->count();


            $recentRemittances =
                CashRemittance::query()
                    ->with('user')
                    ->whereBetween(
                        'remittance_date',
                        [
                            $startDate,
                            $endDate,
                        ]
                    )
                    ->latest(
                        'remittance_date'
                    )
                    ->latest('id')
                    ->take(5)
                    ->get();

        }


        /*
        |--------------------------------------------------------------------------
        | RETURN DATA
        |--------------------------------------------------------------------------
        */

        return [

            /*
            |--------------------------------------------------------------------------
            | USER / PERMISSIONS
            |--------------------------------------------------------------------------
            */

            'user' =>
                $user,

            'userRole' =>
                $userRole,

            'showSales' =>
                $showSales,

            'showPurchases' =>
                $showPurchases,

            'showExpenses' =>
                $showExpenses,

            'showInventory' =>
                $showInventory,

            'showRemittance' =>
                $showRemittance,


            /*
            |--------------------------------------------------------------------------
            | DATE RANGE
            |--------------------------------------------------------------------------
            */

            'startDate' =>
                $startDate,

            'endDate' =>
                $endDate,


            /*
            |--------------------------------------------------------------------------
            | SALES
            |--------------------------------------------------------------------------
            */

            'totalSales' =>
                $totalSales,

            'salesCount' =>
                $salesCount,


            /*
            |--------------------------------------------------------------------------
            | PURCHASES
            |--------------------------------------------------------------------------
            */

            'totalPurchases' =>
                $totalPurchases,

            'purchaseCount' =>
                $purchaseCount,


            /*
            |--------------------------------------------------------------------------
            | EXPENSES
            |--------------------------------------------------------------------------
            */

            'totalExpenses' =>
                $totalExpenses,

            'expenseCount' =>
                $expenseCount,


            /*
            |--------------------------------------------------------------------------
            | NET
            |--------------------------------------------------------------------------
            */

            'netAmount' =>
                $netAmount,


            /*
            |--------------------------------------------------------------------------
            | INVENTORY
            |--------------------------------------------------------------------------
            */

            'inventoryValue' =>
                $inventoryValue,

            'inventoryCount' =>
                $inventoryCount,

            'lowStockCount' =>
                $lowStockCount,

            'outOfStockCount' =>
                $outOfStockCount,


            /*
            |--------------------------------------------------------------------------
            | CASH REMITTANCE
            |--------------------------------------------------------------------------
            */

            'expectedRemittance' =>
                $expectedRemittance,

            'actualRemittance' =>
                $actualRemittance,

            'remittanceVariance' =>
                $remittanceVariance,

            'remittanceCount' =>
                $remittanceCount,


            /*
            |--------------------------------------------------------------------------
            | DETAILS
            |--------------------------------------------------------------------------
            */

            'dailySales' =>
                $dailySales,

            'recentExpenses' =>
                $recentExpenses,

            'recentRemittances' =>
                $recentRemittances,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK IF DATABASE COLUMN EXISTS
    |--------------------------------------------------------------------------
    */

    private function getExistingColumn(
        string $table,
        array $columns
    ): ?string {

        foreach (
            $columns
            as $column
        ) {

            if (
                Schema::hasColumn(
                    $table,
                    $column
                )
            ) {

                return $column;
            }
        }


        return null;
    }
}