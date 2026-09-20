<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\InventoryItem;
use App\Models\StockMovement;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InventoryController extends Controller
{
    /**
     * Display the inventory management page.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $search = $request->input('search');
        $status = $request->input('status');

        // =========================================================
        // INVENTORY QUERY
        // =========================================================

        $query = InventoryItem::with([
            'category',
            'unit',
        ]);

        // SEARCH
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%');
            });
        }

        // STATUS FILTER
        if ($status === 'low') {
            $query->whereColumn(
                'quantity',
                '<=',
                'minimum_stock'
            );
        }

        if ($status === 'normal') {
            $query->whereColumn(
                'quantity',
                '>',
                'minimum_stock'
            );
        }

        if ($status === 'out') {
            $query->where(
                'quantity',
                '<=',
                0
            );
        }

        // =========================================================
        // INVENTORY ITEMS
        // =========================================================

        $inventoryItems = $query
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        // =========================================================
        // SUMMARY VALUES
        // =========================================================

        $totalItems = InventoryItem::count();

        $lowStockItems = InventoryItem::whereColumn(
            'quantity',
            '<=',
            'minimum_stock'
        )->count();

        $outOfStockItems = InventoryItem::where(
            'quantity',
            '<=',
            0
        )->count();

        $totalInventoryValue = InventoryItem::selectRaw(
            'SUM(quantity * unit_cost) as total'
        )->value('total') ?? 0;

        // =========================================================
        // CATEGORIES AND UNITS
        // =========================================================

        $categories = Category::where(
            'is_active',
            true
        )
            ->orderBy('name')
            ->get();

        $units = Unit::where(
            'is_active',
            true
        )
            ->orderBy('name')
            ->get();

        // =========================================================
        // RETURN VIEW
        // =========================================================

        return view('inventory.index', [
            'user' => $user,
            'inventoryItems' => $inventoryItems,
            'categories' => $categories,
            'units' => $units,
            'totalItems' => $totalItems,
            'lowStockItems' => $lowStockItems,
            'outOfStockItems' => $outOfStockItems,
            'totalInventoryValue' => $totalInventoryValue,
            'search' => $search,
            'status' => $status,
        ]);
    }


    /**
     * Show the Add Inventory Item page.
     */
    public function create(Request $request): View
    {
        $user = $request->user();

        $categories = Category::where(
            'is_active',
            true
        )
            ->orderBy('name')
            ->get();

        $units = Unit::where(
            'is_active',
            true
        )
            ->orderBy('name')
            ->get();

        return view('inventory.create', [
            'user' => $user,
            'categories' => $categories,
            'units' => $units,
        ]);
    }


    /**
     * Store a new inventory item.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Only CEO/Admin and Procurement can add inventory.
        if (!in_array($user->role, ['CEO/Admin', 'Procurement'], true)) {
            abort(
                403,
                'You are not authorized to add inventory items.'
            );
        }

        $validated = $request->validate([
            'category_id' => [
                'required',
                'exists:categories,id',
            ],

            'unit_id' => [
                'required',
                'exists:units,id',
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

        DB::transaction(function () use ($validated, $user) {

            $initialQuantity = (float) $validated['quantity'];

            $inventoryItem = InventoryItem::create([
                'category_id' => $validated['category_id'],
                'unit_id' => $validated['unit_id'],
                'name' => $validated['name'],
                'sku' => $validated['sku'],
                'quantity' => $initialQuantity,
                'minimum_stock' => $validated['minimum_stock'],
                'maximum_stock' => $validated['maximum_stock'] ?? null,
                'unit_cost' => $validated['unit_cost'],
                'location' => $validated['location'] ?? null,
                'description' => $validated['description'] ?? null,
                'is_active' => true,
            ]);

            // =====================================================
            // RECORD INITIAL STOCK
            // =====================================================

            if ($initialQuantity > 0) {
                StockMovement::create([
                    'inventory_item_id' => $inventoryItem->id,
                    'user_id' => $user->id,
                    'type' => 'stock_in',
                    'quantity' => $initialQuantity,
                    'quantity_before' => 0,
                    'quantity_after' => $initialQuantity,
                    'reference_type' => 'initial_stock',
                    'reference_id' => $inventoryItem->id,
                    'reason' => 'Initial inventory stock.',
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
     * Show the Edit Inventory Item page.
     */
    public function edit(
        Request $request,
        InventoryItem $inventoryItem
    ): View {
        $user = $request->user();

        // Only CEO/Admin and Procurement can edit.
        if (!in_array($user->role, ['CEO/Admin', 'Procurement'], true)) {
            abort(
                403,
                'You are not authorized to edit inventory items.'
            );
        }

        $categories = Category::where(
            'is_active',
            true
        )
            ->orderBy('name')
            ->get();

        $units = Unit::where(
            'is_active',
            true
        )
            ->orderBy('name')
            ->get();

        return view('inventory.edit', [
            'user' => $user,
            'inventoryItem' => $inventoryItem,
            'categories' => $categories,
            'units' => $units,
        ]);
    }


    /**
     * Update inventory item information.
     *
     * Quantity is intentionally NOT updated here.
     *
     * Quantity changes must go through:
     * - Stock In
     * - Stock Out
     * - Adjustment
     *
     * This ensures that every stock quantity change
     * is recorded in stock_movements.
     */
    public function update(
        Request $request,
        InventoryItem $inventoryItem
    ): RedirectResponse {
        $user = $request->user();

        // Only CEO/Admin and Procurement can edit.
        if (!in_array($user->role, ['CEO/Admin', 'Procurement'], true)) {
            abort(
                403,
                'You are not authorized to edit inventory items.'
            );
        }

        $validated = $request->validate([
            'category_id' => [
                'required',
                'exists:categories,id',
            ],

            'unit_id' => [
                'required',
                'exists:units,id',
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
                Rule::unique('inventory_items', 'sku')
                    ->ignore($inventoryItem->id),
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
            'category_id' => $validated['category_id'],
            'unit_id' => $validated['unit_id'],
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'minimum_stock' => $validated['minimum_stock'],
            'maximum_stock' => $validated['maximum_stock'] ?? null,
            'unit_cost' => $validated['unit_cost'],
            'location' => $validated['location'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'],
        ]);

        return redirect()
            ->route('inventory.index')
            ->with(
                'success',
                'Inventory item updated successfully.'
            );
    }


    /**
     * Show the Stock Operations page.
     *
     * This page is used to:
     * - Add stock
     * - Remove stock
     * - Adjust stock
     * - View stock movement history
     */
    public function stockForm(
        Request $request,
        InventoryItem $inventoryItem
    ): View {
        $user = $request->user();

        // Only CEO/Admin and Procurement can manage stock.
        if (!in_array($user->role, ['CEO/Admin', 'Procurement'], true)) {
            abort(
                403,
                'You are not authorized to manage inventory stock.'
            );
        }

        // Load item information needed by the stock page.
        $inventoryItem->load([
            'category',
            'unit',
        ]);

        // =========================================================
        // STOCK MOVEMENT HISTORY
        // =========================================================

        $stockMovements = StockMovement::where(
            'inventory_item_id',
            $inventoryItem->id
        )
            ->latest()
            ->paginate(10);

        // =========================================================
        // RETURN STOCK OPERATIONS VIEW
        // =========================================================

        return view('inventory.stock', [
            'user' => $user,
            'inventoryItem' => $inventoryItem,
            'stockMovements' => $stockMovements,
        ]);
    }


    /**
     * Add stock to an inventory item.
     */
    public function stockIn(
        Request $request,
        InventoryItem $inventoryItem
    ): RedirectResponse {
        $user = $request->user();

        // Only CEO/Admin and Procurement can add stock.
        if (!in_array($user->role, ['CEO/Admin', 'Procurement'], true)) {
            abort(
                403,
                'You are not authorized to add stock.'
            );
        }

        $validated = $request->validate([
            'quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'reason' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        DB::transaction(function () use (
            $validated,
            $inventoryItem,
            $user
        ) {
            $inventoryItem->refresh();

            $quantityBefore = (float) $inventoryItem->quantity;
            $quantityAdded = (float) $validated['quantity'];
            $quantityAfter = $quantityBefore + $quantityAdded;

            $inventoryItem->update([
                'quantity' => $quantityAfter,
            ]);

            StockMovement::create([
                'inventory_item_id' => $inventoryItem->id,
                'user_id' => $user->id,
                'type' => 'stock_in',
                'quantity' => $quantityAdded,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityAfter,
                'reference_type' => 'manual_stock_in',
                'reference_id' => $inventoryItem->id,
                'reason' => $validated['reason']
                    ?? 'Manual stock in.',
            ]);
        });

        return redirect()
            ->route('inventory.stock', $inventoryItem)
            ->with(
                'success',
                'Stock added successfully.'
            );
    }


    /**
     * Remove stock from an inventory item.
     */
    public function stockOut(
        Request $request,
        InventoryItem $inventoryItem
    ): RedirectResponse {
        $user = $request->user();

        // Only CEO/Admin and Procurement can remove stock.
        if (!in_array($user->role, ['CEO/Admin', 'Procurement'], true)) {
            abort(
                403,
                'You are not authorized to remove stock.'
            );
        }

        $validated = $request->validate([
            'quantity' => [
                'required',
                'numeric',
                'gt:0',
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
            $inventoryItem->refresh();

            $quantityBefore = (float) $inventoryItem->quantity;
            $quantityRemoved = (float) $validated['quantity'];

            // Prevent negative inventory.
            if ($quantityRemoved > $quantityBefore) {
                abort(
                    422,
                    'Stock Out quantity cannot be greater than the current stock.'
                );
            }

            $quantityAfter = $quantityBefore - $quantityRemoved;

            $inventoryItem->update([
                'quantity' => $quantityAfter,
            ]);

            StockMovement::create([
                'inventory_item_id' => $inventoryItem->id,
                'user_id' => $user->id,
                'type' => 'stock_out',
                'quantity' => $quantityRemoved,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityAfter,
                'reference_type' => 'manual_stock_out',
                'reference_id' => $inventoryItem->id,
                'reason' => $validated['reason'],
            ]);
        });

        return redirect()
            ->route('inventory.stock', $inventoryItem)
            ->with(
                'success',
                'Stock removed successfully.'
            );
    }


    /**
     * Adjust inventory quantity.
     *
     * This is used when the physical count does not match
     * the quantity currently recorded by the system.
     */
    public function adjust(
        Request $request,
        InventoryItem $inventoryItem
    ): RedirectResponse {
        $user = $request->user();

        // Only CEO/Admin and Procurement can adjust stock.
        if (!in_array($user->role, ['CEO/Admin', 'Procurement'], true)) {
            abort(
                403,
                'You are not authorized to adjust stock.'
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
                'max:1000',
            ],
        ]);

        DB::transaction(function () use (
            $validated,
            $inventoryItem,
            $user
        ) {
            $inventoryItem->refresh();

            $quantityBefore = (float) $inventoryItem->quantity;
            $newQuantity = (float) $validated['quantity'];

            $difference = $newQuantity - $quantityBefore;

            // No actual change.
            if ($difference == 0) {
                abort(
                    422,
                    'The adjusted quantity is the same as the current stock.'
                );
            }

            $inventoryItem->update([
                'quantity' => $newQuantity,
            ]);

            StockMovement::create([
                'inventory_item_id' => $inventoryItem->id,
                'user_id' => $user->id,
                'type' => 'adjustment',
                'quantity' => $difference,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $newQuantity,
                'reference_type' => 'inventory_adjustment',
                'reference_id' => $inventoryItem->id,
                'reason' => $validated['reason'],
            ]);
        });

        return redirect()
            ->route('inventory.stock', $inventoryItem)
            ->with(
                'success',
                'Inventory quantity adjusted successfully.'
            );
    }
}