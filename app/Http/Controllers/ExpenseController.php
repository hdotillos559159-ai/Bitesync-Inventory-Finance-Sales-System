<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    /**
     * Expense categories used throughout the module.
     */
    private const CATEGORIES = [
        'Utilities',
        'Rent',
        'Transportation',
        'Supplies',
        'Maintenance',
        'Salaries',
        'Marketing',
        'Food & Beverages',
        'Equipment',
        'Other',
    ];

    /**
     * Display the expense records.
     */
    public function index(Request $request): View
    {
        $query = Expense::with('user')
            ->latest('expense_date')
            ->latest('id');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('category', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('reference_no', 'like', '%' . $search . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        /*
        |--------------------------------------------------------------------------
        | Month Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('month')) {
            $month = $request->input('month');

            if (preg_match('/^\d{4}-\d{2}$/', $month)) {
                $query->whereRaw(
                    "DATE_FORMAT(expense_date, '%Y-%m') = ?",
                    [$month]
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Expense Records
        |--------------------------------------------------------------------------
        */

        $expenses = $query->paginate(10);
        /*
        |--------------------------------------------------------------------------
        | Dashboard Statistics
        |--------------------------------------------------------------------------
        */

        $totalExpenses = Expense::where('status', '!=', 'Cancelled')
            ->sum('amount');

        $monthlyExpenses = Expense::where('status', '!=', 'Cancelled')
            ->whereYear('expense_date', now()->year)
            ->whereMonth('expense_date', now()->month)
            ->sum('amount');

        $expenseCount = Expense::count();

        $averageExpense = Expense::where('status', '!=', 'Cancelled')
            ->avg('amount') ?? 0;

        return view('expenses.index', [
            'expenses' => $expenses,
            'totalExpenses' => $totalExpenses,
            'monthlyExpenses' => $monthlyExpenses,
            'expenseCount' => $expenseCount,
            'averageExpense' => $averageExpense,
            'categories' => self::CATEGORIES,
        ]);
    }

    /**
     * Show the form for creating a new expense.
     */
    public function create(): View
    {
        return view('expenses.create', [
            'categories' => self::CATEGORIES,
        ]);
    }

    /**
     * Store a newly created expense.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => [
                'required',
                'string',
                'max:100',
                'in:' . implode(',', self::CATEGORIES),
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:9999999999.99',
            ],

            'expense_date' => [
                'required',
                'date',
            ],

            'description' => [
                'nullable',
                'string',
                'max:500',
            ],

            'reference_no' => [
                'nullable',
                'string',
                'max:100',
            ],

            'status' => [
                'required',
                'string',
                'in:Draft,Recorded,Cancelled',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Automatically identify the logged-in user.
        |--------------------------------------------------------------------------
        */

        $validated['user_id'] = Auth::id();

        Expense::create($validated);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense recorded successfully.');
    }

    /**
     * Display a single expense.
     */
    public function show(Expense $expense): View
    {
        $expense->load('user');

        return view('expenses.show', [
            'expense' => $expense,
        ]);
    }

    /**
     * Show the form for editing an expense.
     */
    public function edit(Expense $expense): View
    {
        return view('expenses.edit', [
            'expense' => $expense,
            'categories' => self::CATEGORIES,
        ]);
    }

    /**
     * Update an existing expense.
     */
    public function update(
        Request $request,
        Expense $expense
    ): RedirectResponse {
        $validated = $request->validate([
            'category' => [
                'required',
                'string',
                'max:100',
                'in:' . implode(',', self::CATEGORIES),
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:9999999999.99',
            ],

            'expense_date' => [
                'required',
                'date',
            ],

            'description' => [
                'nullable',
                'string',
                'max:500',
            ],

            'reference_no' => [
                'nullable',
                'string',
                'max:100',
            ],

            'status' => [
                'required',
                'string',
                'in:Draft,Recorded,Cancelled',
            ],
        ]);

        $expense->update($validated);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    /**
     * Delete an expense.
     */
    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}