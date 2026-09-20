@extends('layouts.app')

@section('title', 'BiteSync | Inventory')

@section('content')

<div class="inventory-page">

    <!-- =========================================================
         INVENTORY TOPBAR
    ========================================================== -->

    <div class="topbar">

        <div class="page-title">

            <small>
                Inventory Management
            </small>

            <h1>
                Inventory
            </h1>

            <p>
                Monitor and manage your BiteSync inventory records.
            </p>

        </div>

        <div class="date-box">

            <span class="date-icon">
                ◷
            </span>

            {{ now()->format('F d, Y') }}

        </div>

    </div>


    <!-- =========================================================
         SUMMARY CARDS
    ========================================================== -->

    <section class="inventory-stats">

        <!-- TOTAL ITEMS -->
        <div class="inventory-stat">

            <div>
                <div class="inventory-stat-label">TOTAL ITEMS</div>
                <div class="inventory-stat-value">{{ $totalItems }}</div>
                <div class="inventory-stat-note">
                    All inventory records
                </div>
            </div>

            <div class="inventory-stat-icon">▦</div>

        </div>


        <!-- LOW STOCK -->
        <div class="inventory-stat">

            <div>
                <div class="inventory-stat-label">LOW STOCK</div>
                <div class="inventory-stat-value">{{ $lowStockItems }}</div>
                <div class="inventory-stat-note">
                    Items needing attention
                </div>
            </div>

            <div class="inventory-stat-icon">!</div>

        </div>


        <!-- OUT OF STOCK -->
        <div class="inventory-stat">

            <div>
                <div class="inventory-stat-label">OUT OF STOCK</div>
                <div class="inventory-stat-value">{{ $outOfStockItems }}</div>
                <div class="inventory-stat-note">
                    Items with no stock
                </div>
            </div>

            <div class="inventory-stat-icon">×</div>

        </div>


        <!-- INVENTORY VALUE -->
        <div class="inventory-stat">

            <div>
                <div class="inventory-stat-label">INVENTORY VALUE</div>

                <div class="inventory-stat-value inventory-money">
                    ₱{{ number_format((float) $totalInventoryValue, 2) }}
                </div>

                <div class="inventory-stat-note">
                    Current stock value
                </div>
            </div>

            <div class="inventory-stat-icon">₱</div>

        </div>

    </section>


    <!-- =========================================================
         INVENTORY RECORDS PANEL
    ========================================================== -->

    <div class="inventory-panel">


        <!-- =====================================================
             PANEL HEADER
        ====================================================== -->

        <div class="inventory-panel-header">

            <div>
                <div class="inventory-panel-title">
                    Inventory Records
                </div>

                <div class="inventory-panel-subtitle">
                    Search and monitor your current inventory stock.
                </div>
            </div>


            @if (
                $user->role === 'CEO/Admin' ||
                $user->role === 'Procurement'
            )

                <a
                    href="{{ route('inventory.create') }}"
                    class="inventory-add-button"
                >
                    <span>+</span>
                    Add Inventory
                </a>

            @endif

        </div>


        <!-- =====================================================
             SEARCH / FILTER
        ====================================================== -->

        <form
            method="GET"
            action="{{ route('inventory.index') }}"
            class="inventory-filters"
        >

            <div class="inventory-search-wrapper">

                <span class="inventory-search-icon">
                    ⌕
                </span>

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search inventory..."
                >

            </div>


            <select name="status">

                <option value="">
                    All Status
                </option>

                <option
                    value="normal"
                    {{ $status === 'normal' ? 'selected' : '' }}
                >
                    Normal
                </option>

                <option
                    value="low"
                    {{ $status === 'low' ? 'selected' : '' }}
                >
                    Low Stock
                </option>

                <option
                    value="out"
                    {{ $status === 'out' ? 'selected' : '' }}
                >
                    Out of Stock
                </option>

            </select>


            <button
                type="submit"
                class="inventory-filter-button"
            >
                Filter
            </button>


            @if ($search || $status)

                <a
                    href="{{ route('inventory.index') }}"
                    class="inventory-clear-button"
                >
                    Clear
                </a>

            @endif

        </form>


        <!-- =====================================================
             INVENTORY TABLE
        ====================================================== -->

        <div class="inventory-table-wrapper">

            <table class="inventory-table">

                <thead>

                    <tr>

                        <th>Item</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Minimum</th>
                        <th>Unit Cost</th>
                        <th>Location</th>
                        <th>Status</th>

                        @if (
                            $user->role === 'CEO/Admin' ||
                            $user->role === 'Procurement'
                        )

                            <th class="inventory-actions-header">
                                Actions
                            </th>

                        @endif

                    </tr>

                </thead>


                <tbody>

                    @forelse ($inventoryItems as $item)

                        @php

                            $quantity = (float) $item->quantity;

                            $minimumStock = (float) $item->minimum_stock;

                            $maximumStock = $item->maximum_stock !== null
                                ? (float) $item->maximum_stock
                                : null;


                            /*
                            |--------------------------------------------------------------------------
                            | STOCK STATUS
                            |--------------------------------------------------------------------------
                            */

                            if ($quantity <= 0) {

                                $itemStatus = 'out';

                                $itemStatusLabel = 'OUT OF STOCK';

                            } elseif ($quantity <= $minimumStock) {

                                $itemStatus = 'low';

                                $itemStatusLabel = 'LOW STOCK';

                            } else {

                                $itemStatus = 'normal';

                                $itemStatusLabel = 'NORMAL';

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | STOCK PROGRESS
                            |--------------------------------------------------------------------------
                            */

                            if (
                                $maximumStock !== null &&
                                $maximumStock > 0
                            ) {

                                $stockPercent = min(
                                    100,
                                    max(
                                        0,
                                        ($quantity / $maximumStock) * 100
                                    )
                                );

                            } elseif ($minimumStock > 0) {

                                $stockPercent = min(
                                    100,
                                    max(
                                        0,
                                        ($quantity / ($minimumStock * 2)) * 100
                                    )
                                );

                            } else {

                                $stockPercent = $quantity > 0
                                    ? 100
                                    : 0;

                            }

                        @endphp


                        <tr>

                            <!-- =================================================
                                 ITEM
                            ================================================== -->

                            <td>

                                <div class="inventory-item-cell">

                                    <div class="inventory-item-image">

                                        @if ($item->image)

                                            <img
                                                src="{{ asset('storage/' . $item->image) }}"
                                                alt="{{ $item->name }}"
                                            >

                                        @else

                                            @php
                                                $categoryName = strtolower(
                                                    $item->category->name ?? ''
                                                );
                                            @endphp


                                            @if (
                                                str_contains($categoryName, 'meat') ||
                                                str_contains($categoryName, 'chicken') ||
                                                str_contains($categoryName, 'beef') ||
                                                str_contains($categoryName, 'pork')
                                            )

                                                <span class="inventory-fallback-icon meat">
                                                    🐔
                                                </span>

                                            @elseif (
                                                str_contains($categoryName, 'seafood') ||
                                                str_contains($categoryName, 'fish')
                                            )

                                                <span class="inventory-fallback-icon seafood">
                                                    🐟
                                                </span>

                                            @elseif (
                                                str_contains($categoryName, 'vegetable') ||
                                                str_contains($categoryName, 'veggie')
                                            )

                                                <span class="inventory-fallback-icon vegetable">
                                                    🥦
                                                </span>

                                            @elseif (
                                                str_contains($categoryName, 'dairy') ||
                                                str_contains($categoryName, 'milk') ||
                                                str_contains($categoryName, 'cheese')
                                            )

                                                <span class="inventory-fallback-icon dairy">
                                                    🧀
                                                </span>

                                            @elseif (
                                                str_contains($categoryName, 'fruit')
                                            )

                                                <span class="inventory-fallback-icon fruit">
                                                    🍎
                                                </span>

                                            @else

                                                <span class="inventory-fallback-icon default">
                                                    📦
                                                </span>

                                            @endif

                                        @endif

                                    </div>


                                    <div class="inventory-item-info">

                                        <div class="inventory-name">
                                            {{ $item->name }}
                                        </div>


                                        @if ($item->description)

                                            <div class="inventory-description">
                                                {{ $item->description }}
                                            </div>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            <!-- =================================================
                                 SKU
                            ================================================== -->

                            <td>

                                <span class="inventory-sku">
                                    {{ $item->sku }}
                                </span>

                            </td>


                            <!-- =================================================
                                 CATEGORY
                            ================================================== -->

                            <td>

                                @if ($item->category)

                                    <span class="inventory-category">
                                        {{ $item->category->name }}
                                    </span>

                                @else

                                    <span class="inventory-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            <!-- =================================================
                                 STOCK
                            ================================================== -->

                            <td>

                                <div class="inventory-stock">

                                    <div class="inventory-stock-main">

                                        <span class="inventory-stock-number">
                                            {{ number_format($quantity, 2) }}
                                        </span>


                                        @if ($item->unit)

                                            <span class="inventory-unit">
                                                {{ $item->unit->abbreviation }}
                                            </span>

                                        @endif

                                    </div>


                                    <div class="inventory-stock-note">

                                        of
                                        {{ number_format($minimumStock, 2) }}

                                        {{ $item->unit?->abbreviation ?? '' }}

                                        min

                                    </div>


                                    <div class="inventory-progress">

                                        <div
                                            class="inventory-progress-bar inventory-progress-{{ $itemStatus }}"
                                            style="width: {{ $stockPercent }}%;"
                                        ></div>

                                    </div>

                                </div>

                            </td>


                            <!-- =================================================
                                 MINIMUM
                            ================================================== -->

                            <td>

                                <span class="inventory-minimum">

                                    {{ number_format($minimumStock, 2) }}

                                    @if ($item->unit)

                                        <span class="inventory-unit">
                                            {{ $item->unit->abbreviation }}
                                        </span>

                                    @endif

                                </span>

                            </td>


                            <!-- =================================================
                                 UNIT COST
                            ================================================== -->

                            <td>

                                <span class="inventory-price">
                                    ₱{{ number_format((float) $item->unit_cost, 2) }}
                                </span>

                            </td>


                            <!-- =================================================
                                 LOCATION
                            ================================================== -->

                            <td>

                                @if ($item->location)

                                    <span class="inventory-location">
                                        {{ $item->location }}
                                    </span>

                                @else

                                    <span class="inventory-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            <!-- =================================================
                                 STATUS
                            ================================================== -->

                            <td>

                                @if ($itemStatus === 'normal')

                                    <span class="inventory-status inventory-status-normal">
                                        {{ $itemStatusLabel }}
                                    </span>

                                @elseif ($itemStatus === 'low')

                                    <span class="inventory-status inventory-status-low">
                                        {{ $itemStatusLabel }}
                                    </span>

                                @else

                                    <span class="inventory-status inventory-status-out">
                                        {{ $itemStatusLabel }}
                                    </span>

                                @endif

                            </td>


                            <!-- =================================================
                                 ACTIONS
                            ================================================== -->

                            @if (
                                $user->role === 'CEO/Admin' ||
                                $user->role === 'Procurement'
                            )

                                <td>

                                    <div class="inventory-table-actions">

                                        <!-- STOCK OPERATIONS -->

                                        <a
                                            href="{{ route('inventory.stock', $item) }}"
                                            class="inventory-stock-button"
                                        >
                                            <span>↕</span>
                                            Stock
                                        </a>


                                        <!-- EDIT -->

                                        <a
                                            href="{{ route('inventory.edit', $item) }}"
                                            class="inventory-edit-button"
                                        >
                                            <span>✎</span>
                                            Edit
                                        </a>

                                    </div>

                                </td>

                            @endif

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="{{
                                    (
                                        $user->role === 'CEO/Admin' ||
                                        $user->role === 'Procurement'
                                    )
                                        ? 9
                                        : 8
                                }}"
                                class="inventory-empty-state"
                            >

                                <div class="inventory-empty-icon">
                                    ▦
                                </div>


                                <div class="inventory-empty-title">
                                    No inventory records found
                                </div>


                                <div class="inventory-empty-description">

                                    @if ($search || $status)

                                        Try changing your search or filter.

                                    @else

                                        Your inventory records will appear here.

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <!-- =====================================================
             PAGINATION
        ====================================================== -->

        @if ($inventoryItems->hasPages())

            <div class="inventory-pagination-wrapper">

                {{ $inventoryItems->links() }}

            </div>

        @endif

    </div>

</div>

@endsection


@push('styles')

<style>

/* =========================================================
   INVENTORY PAGE
========================================================= */

.inventory-page {
    width: 100%;
}


/* =========================================================
   PAGE TITLE
========================================================= */

.inventory-page .page-title h1 {
    font-size: clamp(1.75rem, 2.2vw, 2rem);
    line-height: 1.15;
    letter-spacing: -0.045rem;
}


/* =========================================================
   SUMMARY CARDS
========================================================= */

.inventory-stats {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 17px;
    margin-bottom: 22px;
}

.inventory-stat {
    min-height: 135px;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 15px;
    padding: 20px;
    border: 1px solid var(--border);
    border-radius: 17px;
    background: white;
    box-shadow: 0 7px 22px rgba(43, 31, 23, 0.055);
    position: relative;
    overflow: hidden;
}

.inventory-stat::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 4px;
    background: linear-gradient(
        180deg,
        var(--orange),
        #e2a16c
    );
}

.inventory-stat-label {
    color: var(--muted);
    font-size: 0.8125rem;
    line-height: 1.3;
    font-weight: 800;
    letter-spacing: 0.05rem;
}

.inventory-stat-value {
    margin-top: 14px;
    color: var(--dark);
    font-size: 1.875rem;
    line-height: 1;
    font-weight: 800;
}

.inventory-money {
    font-size: 1.375rem;
}

.inventory-stat-note {
    margin-top: 8px;
    color: #9d958f;
    font-size: 0.8125rem;
    line-height: 1.4;
}

.inventory-stat-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 11px;
    background: linear-gradient(
        135deg,
        #fbf1e7,
        #f4e3d4
    );
    color: var(--orange);
    font-size: 0.875rem;
    font-weight: 800;
}


/* =========================================================
   INVENTORY PANEL
========================================================= */

.inventory-panel {
    background: white;
    border: 1px solid var(--border);
    border-radius: 17px;
    overflow: hidden;
    box-shadow: 0 5px 18px rgba(43, 31, 23, 0.035);
}


/* =========================================================
   PANEL HEADER
========================================================= */

.inventory-panel-header {
    min-height: 75px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 17px 21px;
    border-bottom: 1px solid var(--border);
}

.inventory-panel-title {
    color: var(--dark);
    font-size: 1rem;
    line-height: 1.3;
    font-weight: 700;
}

.inventory-panel-subtitle {
    margin-top: 4px;
    color: var(--muted);
    font-size: 0.8125rem;
    line-height: 1.4;
}


/* =========================================================
   ADD INVENTORY
========================================================= */

.inventory-add-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 8px 12px;
    border-radius: 8px;
    background: linear-gradient(
        135deg,
        var(--orange),
        var(--orange-dark)
    );
    color: white;
    text-decoration: none;
    font-size: 0.8125rem;
    line-height: 1.2;
    font-weight: 700;
    box-shadow: 0 4px 11px rgba(168, 95, 40, 0.14);
    transition:
        background 0.18s ease,
        box-shadow 0.18s ease;
}

.inventory-add-button:hover {
    color: white;
    background: linear-gradient(
        135deg,
        var(--orange-dark),
        var(--orange-dark)
    );
    box-shadow: 0 5px 12px rgba(168, 95, 40, 0.16);
}

.inventory-add-button span {
    font-size: 0.9375rem;
    line-height: 1;
}


/* =========================================================
   FILTERS
========================================================= */

.inventory-filters {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px 21px;
    background: var(--card-soft);
    border-bottom: 1px solid var(--border);
}

.inventory-search-wrapper {
    flex: 1;
    position: relative;
    min-width: 180px;
}

.inventory-search-wrapper input {
    width: 100%;
    height: 37px;
    padding: 0 13px 0 37px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: white;
    color: var(--text);
    font-family: inherit;
    font-size: 0.8125rem;
    outline: none;
    transition:
        border-color 0.18s ease,
        box-shadow 0.18s ease;
}

.inventory-search-wrapper input:focus {
    border-color: #d5a77d;
    box-shadow: 0 0 0 3px rgba(196, 122, 58, 0.08);
}

.inventory-search-wrapper input::placeholder {
    color: #aaa19a;
}

.inventory-search-icon {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--muted);
    font-size: 0.9375rem;
    pointer-events: none;
}

.inventory-filters select {
    height: 37px;
    min-width: 135px;
    padding: 0 11px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: white;
    color: var(--text);
    font-family: inherit;
    font-size: 0.8125rem;
    outline: none;
    cursor: pointer;
}


/* =========================================================
   FILTER / CLEAR
========================================================= */

.inventory-filter-button,
.inventory-clear-button {
    height: 37px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 13px;
    border-radius: 8px;
    font-family: inherit;
    font-size: 0.8125rem;
    font-weight: 700;
    line-height: 1.2;
    text-decoration: none;
    cursor: pointer;
}

.inventory-filter-button {
    border: none;
    background: var(--dark);
    color: white;
    transition: background-color 0.18s ease;
}

.inventory-filter-button:hover {
    background: var(--dark-soft);
}

.inventory-clear-button {
    border: 1px solid var(--border);
    background: white;
    color: var(--muted);
    transition:
        background-color 0.18s ease,
        color 0.18s ease;
}

.inventory-clear-button:hover {
    background: #faf7f3;
    color: var(--dark);
}


/* =========================================================
   TABLE
========================================================= */

.inventory-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.inventory-table {
    width: 100%;
    min-width: 1150px;
    border-collapse: collapse;
}

.inventory-table th {
    padding: 11px 14px;
    background: #fbf9f6;
    color: var(--muted);
    border-bottom: 1px solid var(--border);
    text-align: left;
    font-size: 0.6875rem;
    line-height: 1.3;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.045rem;
    white-space: nowrap;
}

.inventory-table td {
    padding: 13px 14px;
    color: #625951;
    border-bottom: 1px solid #f0ebe6;
    font-size: 0.8125rem;
    line-height: 1.4;
    vertical-align: middle;
}

.inventory-table tbody tr {
    background: white;
}

.inventory-table tbody tr:hover {
    background: #fdfaf7;
}


/* =========================================================
   ITEM WITH IMAGE
========================================================= */

.inventory-item-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.inventory-item-image {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    background: #f8f1e9;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #f0e6db;
}

.inventory-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.inventory-fallback-icon {
    font-size: 1.25rem;
    line-height: 1;
}

.inventory-item-info {
    min-width: 0;
}

.inventory-name {
    color: var(--dark);
    font-size: 0.8125rem;
    line-height: 1.4;
    font-weight: 700;
}

.inventory-description {
    max-width: 220px;
    margin-top: 3px;
    color: var(--muted);
    font-size: 0.75rem;
    line-height: 1.35;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.inventory-muted {
    color: #a39b95;
    font-size: 0.8125rem;
}


/* =========================================================
   SKU
========================================================= */

.inventory-sku {
    display: inline-block;
    padding: 3px 6px;
    border-radius: 5px;
    background: #f5f0eb;
    color: var(--brown);
    font-family: monospace;
    font-size: 0.6875rem;
}


/* =========================================================
   CATEGORY
========================================================= */

.inventory-category {
    display: inline-flex;
    align-items: center;
    padding: 4px 7px;
    border-radius: 6px;
    background: #f6f3f0;
    color: #75675d;
    font-size: 0.6875rem;
    line-height: 1.2;
    font-weight: 600;
}


/* =========================================================
   STOCK
========================================================= */

.inventory-stock {
    min-width: 125px;
}

.inventory-stock-main {
    display: flex;
    align-items: baseline;
    gap: 4px;
}

.inventory-stock-number {
    color: var(--dark);
    font-size: 0.875rem;
    line-height: 1.2;
    font-weight: 800;
}

.inventory-unit {
    color: var(--muted);
    font-size: 0.6875rem;
    line-height: 1;
    font-weight: 600;
}

.inventory-stock-note {
    margin-top: 3px;
    color: #9b938c;
    font-size: 0.6875rem;
    line-height: 1.3;
}

.inventory-progress {
    width: 100%;
    height: 4px;
    margin-top: 7px;
    overflow: hidden;
    border-radius: 999px;
    background: #eee9e4;
}

.inventory-progress-bar {
    height: 100%;
    border-radius: inherit;
}

.inventory-progress-normal {
    background: var(--green);
}

.inventory-progress-low {
    background: var(--yellow);
}

.inventory-progress-out {
    background: var(--red);
}


/* =========================================================
   MINIMUM
========================================================= */

.inventory-minimum {
    color: #625951;
    font-size: 0.8125rem;
    font-weight: 600;
    white-space: nowrap;
}


/* =========================================================
   UNIT COST
========================================================= */

.inventory-price {
    color: var(--dark);
    font-size: 0.8125rem;
    font-weight: 800;
    white-space: nowrap;
}


/* =========================================================
   LOCATION
========================================================= */

.inventory-location {
    color: #625951;
    font-size: 0.8125rem;
}


/* =========================================================
   STATUS
========================================================= */

.inventory-status {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 5px 8px;
    border-radius: 7px;
    font-size: 0.6875rem;
    line-height: 1.2;
    font-weight: 800;
    white-space: nowrap;
    letter-spacing: 0.02rem;
}

.inventory-status::before {
    content: "";
    width: 6px;
    height: 6px;
    flex-shrink: 0;
    border-radius: 50%;
    background: currentColor;
}

.inventory-status-normal {
    color: var(--green);
    background: var(--green-light);
}

.inventory-status-low {
    color: #a56b00;
    background: var(--yellow-light);
}

.inventory-status-out {
    color: var(--red);
    background: var(--red-light);
}


/* =========================================================
   ACTIONS
========================================================= */

.inventory-actions-header {
    text-align: center !important;
}

.inventory-table-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    white-space: nowrap;
}


/* =========================================================
   STOCK BUTTON
========================================================= */

.inventory-stock-button {
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    padding: 0 8px;
    border: 1px solid #d7e5dc;
    border-radius: 7px;
    background: #f6fbf8;
    color: var(--green);
    text-decoration: none;
    font-family: inherit;
    font-size: 0.6875rem;
    line-height: 1.2;
    font-weight: 700;
    cursor: pointer;
    transition:
        background-color 0.18s ease,
        border-color 0.18s ease,
        color 0.18s ease;
}

.inventory-stock-button:hover {
    background: #edf8f1;
    border-color: #c4dccd;
    color: #2e6c46;
}

.inventory-stock-button span {
    font-size: 0.75rem;
    line-height: 1;
}


/* =========================================================
   EDIT BUTTON
========================================================= */

.inventory-edit-button {
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    padding: 0 8px;
    border: 1px solid #e1d4c8;
    border-radius: 7px;
    background: white;
    color: #7d5b42;
    text-decoration: none;
    font-family: inherit;
    font-size: 0.6875rem;
    line-height: 1.2;
    font-weight: 700;
    cursor: pointer;
    transition:
        background-color 0.18s ease,
        border-color 0.18s ease,
        color 0.18s ease;
}

.inventory-edit-button:hover {
    background: #faf5ef;
    border-color: #cdb49f;
    color: #68482f;
}

.inventory-edit-button span {
    font-size: 0.75rem;
    line-height: 1;
}


/* =========================================================
   EMPTY STATE
========================================================= */

.inventory-empty-state {
    padding: 65px 20px !important;
    text-align: center !important;
}

.inventory-empty-icon {
    width: 52px;
    height: 52px;
    margin: 0 auto 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    background: var(--orange-light);
    color: var(--orange);
    font-size: 1.1875rem;
}

.inventory-empty-title {
    color: var(--dark);
    font-size: 0.875rem;
    font-weight: 700;
}

.inventory-empty-description {
    margin-top: 5px;
    color: var(--muted);
    font-size: 0.8125rem;
    line-height: 1.4;
}


/* =========================================================
   PAGINATION
========================================================= */

.inventory-pagination-wrapper {
    padding: 15px 21px;
    border-top: 1px solid var(--border);
}

.inventory-pagination-wrapper nav {
    display: flex;
    justify-content: center;
}

.inventory-pagination-wrapper svg {
    width: 15px;
    height: 15px;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1200px) {

    .inventory-stats {
        grid-template-columns: repeat(
            2,
            minmax(0, 1fr)
        );
    }

}


@media (max-width: 700px) {

    .inventory-stats {
        grid-template-columns: 1fr;
    }


    .inventory-panel-header {
        align-items: flex-start;
        flex-direction: column;
    }


    .inventory-add-button {
        width: 100%;
    }


    .inventory-filters {
        align-items: stretch;
        flex-direction: column;
    }


    .inventory-search-wrapper {
        width: 100%;
    }


    .inventory-filters select,
    .inventory-filter-button,
    .inventory-clear-button {
        width: 100%;
    }


    .inventory-table-actions {
        justify-content: flex-start;
    }

}

</style>

@endpush