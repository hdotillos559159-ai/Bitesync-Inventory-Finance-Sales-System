<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function index(): View
    {
        $sales = Sale::orderByDesc('sale_date')->paginate(15);

        return view('sales.index', compact('sales'));
    }

    public function create(): View
    {
        return view('sales.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sale_number' => ['required', 'string', 'max:255', 'unique:sales,sale_number'],
            'sale_date' => ['required', 'date'],
            'payment_method' => ['required', 'in:cash,gcash'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'total' => ['required', 'numeric', 'min:0'],
        ]);

        Sale::create([
            ...$validated,
            'subtotal' => $validated['total'],
            'tax' => 0,
            'amount_received' => $validated['total'],
            'change' => 0,
            'status' => 'Completed',
        ]);

        return redirect()
            ->route('sales.index')
            ->with('status', 'Sale recorded.');
    }

    public function edit(Sale $sale): View
    {
        return view('sales.edit', compact('sale'));
    }

    public function update(Request $request, Sale $sale): RedirectResponse
    {
        $validated = $request->validate([
            'sale_number' => ['required', 'string', 'max:255', 'unique:sales,sale_number,' . $sale->id],
            'sale_date' => ['required', 'date'],
            'payment_method' => ['required', 'in:cash,gcash'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'total' => ['required', 'numeric', 'min:0'],
        ]);

        $sale->update([
            ...$validated,
            'subtotal' => $validated['total'],
            'tax' => 0,
            'amount_received' => $validated['total'],
            'change' => 0,
        ]);

        return redirect()
            ->route('sales.index')
            ->with('status', 'Sale updated.');
    }

    public function destroy(Sale $sale): RedirectResponse
    {
        $sale->delete();

        return redirect()
            ->route('sales.index')
            ->with('status', 'Sale deleted.');
    }
}