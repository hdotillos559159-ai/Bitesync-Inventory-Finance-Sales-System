<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Purchase;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\View\View;

class FinanceController extends Controller
{
    public function index(): View
    {
        $from = Carbon::today()->subDays(6)->startOfDay();
        $to = Carbon::today()->endOfDay();

        $sales = Sale::whereBetween('sale_date', [$from, $to])
            ->orderByDesc('sale_date')
            ->get();
        $purchases = Purchase::with('supplier')
            ->whereBetween('purchase_date', [$from->toDateString(), $to->toDateString()])
            ->orderByDesc('purchase_date')
            ->get();
        $expenses = Expense::with('purchase')
            ->whereBetween('expense_date', [$from->toDateString(), $to->toDateString()])
            ->orderByDesc('expense_date')
            ->get();

        return view('finance.index', [
            'periodLabel' => $from->format('M j') . ' - ' . $to->format('M j, Y'),
            'sales' => $sales,
            'dailyTotals' => $sales->groupBy(fn ($sale) => $sale->sale_date->format('Y-m-d'))
                ->map(fn ($day) => $day->sum('total')),
            'purchases' => $purchases,
            'supplierSpend' => $purchases->groupBy('supplier_id')
                ->map(fn ($group) => $group->sum('total_cost')),
            'expenses' => $expenses,
            'categorySpend' => $expenses->groupBy('category')
                ->map(fn ($group) => $group->sum('amount')),
        ]);
    }
}
