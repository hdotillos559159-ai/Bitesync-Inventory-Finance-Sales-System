<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    public function index(): View
    {
        $purchases = Purchase::with('supplier')->orderByDesc('purchase_date')->paginate(15);

        return view('purchases.index', compact('purchases'));
    }

    public function create(): View
    {
        $suppliers = Supplier::orderBy('name')->get();

        return view('purchases.create', compact('suppliers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'purchase_no' => ['required', 'string', 'max:30', 'unique:purchases,purchase_no'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'purchase_date' => ['required', 'date'],
            'items_count' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:received,pending'],
            'total_cost' => ['required', 'numeric', 'min:0'],
        ]);

        Purchase::create($validated);

        return redirect()
            ->route('purchases.index')
            ->with('status', 'Purchase order recorded.');
    }

    public function edit(Purchase $purchase): View
    {
        $suppliers = Supplier::orderBy('name')->get();

        return view('purchases.edit', compact('purchase', 'suppliers'));
    }

    public function update(Request $request, Purchase $purchase): RedirectResponse
    {
        $validated = $request->validate([
            'purchase_no' => ['required', 'string', 'max:30', 'unique:purchases,purchase_no,' . $purchase->id],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'purchase_date' => ['required', 'date'],
            'items_count' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:received,pending'],
            'total_cost' => ['required', 'numeric', 'min:0'],
        ]);

        $purchase->update($validated);

        return redirect()
            ->route('purchases.index')
            ->with('status', 'Purchase order updated.');
    }

    public function destroy(Purchase $purchase): RedirectResponse
    {
        $purchase->delete();

        return redirect()
            ->route('purchases.index')
            ->with('status', 'Purchase order deleted.');
    }
}
