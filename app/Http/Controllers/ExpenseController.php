<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Purchase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(): View
    {
        $expenses = Expense::with('purchase')->orderByDesc('expense_date')->paginate(15);

        return view('expenses.index', compact('expenses'));
    }

    public function create(): View
    {
        $purchases = Purchase::orderByDesc('purchase_date')->get();

        return view('expenses.create', compact('purchases'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'expense_no' => ['required', 'string', 'max:30', 'unique:expenses,expense_no'],
            'category' => ['required', 'string', 'max:50'],
            'expense_date' => ['required', 'date'],
            'recorded_by' => ['required', 'string', 'max:100'],
            'purchase_id' => ['nullable', 'exists:purchases,id'],
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        Expense::create($validated);

        return redirect()
            ->route('expenses.index')
            ->with('status', 'Expense recorded.');
    }

    public function edit(Expense $expense): View
    {
        $purchases = Purchase::orderByDesc('purchase_date')->get();

        return view('expenses.edit', compact('expense', 'purchases'));
    }

    public function update(Request $request, Expense $expense): RedirectResponse
    {
        $validated = $request->validate([
            'expense_no' => ['required', 'string', 'max:30', 'unique:expenses,expense_no,' . $expense->id],
            'category' => ['required', 'string', 'max:50'],
            'expense_date' => ['required', 'date'],
            'recorded_by' => ['required', 'string', 'max:100'],
            'purchase_id' => ['nullable', 'exists:purchases,id'],
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        $expense->update($validated);

        return redirect()
            ->route('expenses.index')
            ->with('status', 'Expense updated.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return redirect()
            ->route('expenses.index')
            ->with('status', 'Expense deleted.');
    }
}
