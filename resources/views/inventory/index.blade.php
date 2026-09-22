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

    <div class="inventory-stat-left">

        <div class="inventory-stat-label">
            TOTAL ITEMS
        </div>

        <div class="inventory-stat-note">
            All inventory records
        </div>

    </div>


    <div class="inventory-stat-right">

        <div class="inventory-stat-icon">
            ▦
        </div>

        <div class="inventory-stat-value">
            {{ $totalItems }}
        </div>

    </div>

</div>


<!-- LOW STOCK -->

<div class="inventory-stat">

    <div class="inventory-stat-left">

        <div class="inventory-stat-label">
            LOW STOCK
        </div>

        <div class="inventory-stat-note">
            Items needing attention
        </div>

    </div>


    <div class="inventory-stat-right">

        <div class="inventory-stat-icon">
            !
        </div>

        <div class="inventory-stat-value">
            {{ $lowStockItems }}
        </div>

    </div>

</div>


<!-- OUT OF STOCK -->

<div class="inventory-stat">

    <div class="inventory-stat-left">

        <div class="inventory-stat-label">
            OUT OF STOCK
        </div>

        <div class="inventory-stat-note">
            Items with no stock
        </div>

    </div>


    <div class="inventory-stat-right">

        <div class="inventory-stat-icon">
            ×
        </div>

        <div class="inventory-stat-value">
            {{ $outOfStockItems }}
        </div>

    </div>

</div>


<!-- INVENTORY VALUE -->

<div class="inventory-stat">

    <div class="inventory-stat-left">

        <div class="inventory-stat-label">
            INVENTORY VALUE
        </div>

        <div class="inventory-stat-note">
            Current stock value
        </div>

    </div>


    <div class="inventory-stat-right">

        <div class="inventory-stat-icon">
            ₱
        </div>

        <div class="inventory-stat-value inventory-money">
            ₱{{ number_format((float) $totalInventoryValue, 2) }}
        </div>

    </div>

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


                    <!-- SKU -->

                    <td>

                        <span class="inventory-sku">
                            {{ $item->sku }}
                        </span>

                    </td>


                    <!-- CATEGORY -->

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


                    <!-- STOCK -->

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


                    <!-- MINIMUM -->

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


                    <!-- UNIT COST -->

                    <td>

                        <span class="inventory-price">
                            ₱{{ number_format((float) $item->unit_cost, 2) }}
                        </span>

                    </td>


                    <!-- LOCATION -->

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


                    <!-- STATUS -->

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


                    <!-- ACTIONS -->

                    @if (
                        $user->role === 'CEO/Admin' ||
                        $user->role === 'Procurement'
                    )

                        <td>

                            <div class="inventory-table-actions">

                                <a
                                    href="{{ route('inventory.stock', $item) }}"
                                    class="inventory-stock-button"
                                >

                                    <span>↕</span>

                                    Stock

                                </a>


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

        <div class="inventory-pagination-inner">

            <div class="inventory-pagination-info">

                Showing
                <strong>{{ $inventoryItems->firstItem() }}</strong>
                to
                <strong>{{ $inventoryItems->lastItem() }}</strong>
                of
                <strong>{{ $inventoryItems->total() }}</strong>
                records

            </div>


            <div class="inventory-pagination-links">

                {{ $inventoryItems->links() }}

            </div>

        </div>

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

    font-size:
        clamp(1.6rem, 2vw, 1.9rem);

    line-height: 1.15;

    letter-spacing: -0.04rem;
}


/* =========================================================
   SUMMARY CARDS
========================================================= */

.inventory-stats {

    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 17px;

    margin-bottom: 17px;
}


.inventory-stat {

    min-height: 125px;

    display: flex;

    align-items: flex-start;

    justify-content: space-between;

    gap: 15px;

    padding:
        16px
        17px
        15px;

    border:
        1px solid var(--border);

    border-radius: 15px;

    background: white;

    box-shadow:
        0 5px 18px
        rgba(43, 31, 23, 0.045);

    position: relative;

    overflow: hidden;
}


/* =========================================================
   LEFT ACCENT
========================================================= */

.inventory-stat::before {

    content: "";

    position: absolute;

    top: 0;
    left: 0;
    bottom: 0;

    width: 3px;

    background:
        linear-gradient(
            180deg,
            var(--orange),
            #e2a16c
        );
}


/* =========================================================
   LEFT SIDE
========================================================= */

.inventory-stat-left {

    min-width: 0;

    flex: 1;

    display: flex;

    flex-direction: column;

    align-items: flex-start;

    justify-content: flex-start;

    align-self: stretch;

    padding-left: 1px;

    padding-top: 2px;
}


/* =========================================================
   HEADER LABEL
========================================================= */

.inventory-stat-label {

    color: var(--muted);

    font-size: 0.6875rem;

    line-height: 1.3;

    font-weight: 800;

    letter-spacing: 0.045rem;

    white-space: nowrap;
}


/* =========================================================
   SUBTEXT
========================================================= */

.inventory-stat-note {

    margin-top: 58px;

    color: #9d958f;

    font-size: 0.6875rem;

    line-height: 1.4;

    max-width: 155px;
}


/* =========================================================
   RIGHT SIDE
========================================================= */

.inventory-stat-right {

    min-width: 82px;

    display: flex;

    flex-direction: column;

    align-items: flex-end;

    justify-content: flex-start;

    padding-top: 3px;

    flex-shrink: 0;
}


/* =========================================================
   ICON
========================================================= */

.inventory-stat-icon {

    width: 34px;

    height: 34px;

    display: flex;

    align-items: center;

    justify-content: center;

    flex-shrink: 0;

    border-radius: 9px;

    background:
        linear-gradient(
            135deg,
            #fbf1e7,
            #f4e3d4
        );

    color: var(--orange);

    font-size: 0.8125rem;

    font-weight: 800;
}


/* =========================================================
   VALUE
========================================================= */

.inventory-stat-value {

    margin-top: 13px;

    color: var(--dark);

    font-size:
        clamp(1.45rem, 1.8vw, 1.75rem);

    line-height: 1;

    font-weight: 800;

    text-align: right;

    white-space: nowrap;
}


/* =========================================================
   MONEY VALUE
========================================================= */

.inventory-money {

    font-size:
        clamp(1rem, 1.3vw, 1.25rem);

    letter-spacing: -0.02rem;
}


/* =========================================================
   INVENTORY PANEL
========================================================= */

.inventory-panel {

    background: white;

    border:
        1px solid var(--border);

    border-radius: 15px;

    overflow: hidden;

    box-shadow:
        0 4px 16px
        rgba(43, 31, 23, 0.035);
}


/* =========================================================
   PANEL HEADER
========================================================= */

.inventory-panel-header {

    min-height: 65px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 17px;

    padding:
        14px
        18px;

    border-bottom:
        1px solid var(--border);
}


.inventory-panel-title {

    color: var(--dark);

    font-size: 0.875rem;

    line-height: 1.3;

    font-weight: 700;
}


.inventory-panel-subtitle {

    margin-top: 3px;

    color: var(--muted);

    font-size: 0.6875rem;

    line-height: 1.4;
}


/* =========================================================
   ADD INVENTORY
========================================================= */

.inventory-add-button {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 5px;

    min-height: 32px;

    padding:
        0
        10px;

    border-radius: 8px;

    background:
        linear-gradient(
            135deg,
            var(--orange),
            var(--orange-dark)
        );

    color: white;

    text-decoration: none;

    font-size: 0.6875rem;

    line-height: 1.2;

    font-weight: 700;

    box-shadow:
        0 3px 9px
        rgba(168, 95, 40, 0.12);

    transition:
        background 0.18s ease,
        box-shadow 0.18s ease;
}


.inventory-add-button:hover {

    color: white;

    background:
        linear-gradient(
            135deg,
            var(--orange-dark),
            var(--orange-dark)
        );

    box-shadow:
        0 4px 10px
        rgba(168, 95, 40, 0.15);
}


.inventory-add-button span {

    font-size: 0.8125rem;

    line-height: 1;
}


/* =========================================================
   FILTERS
========================================================= */

.inventory-filters {

    display: flex;

    align-items: center;

    gap: 8px;

    padding:
        12px
        18px;

    background: var(--card-soft);

    border-bottom:
        1px solid var(--border);
}


.inventory-search-wrapper {

    flex: 1;

    position: relative;

    min-width: 180px;
}


.inventory-search-wrapper input {

    width: 100%;

    height: 35px;

    padding:
        0
        11px
        0
        34px;

    border:
        1px solid var(--border);

    border-radius: 8px;

    background: white;

    color: var(--text);

    font-family: inherit;

    font-size: 0.75rem;

    outline: none;

    transition:
        border-color 0.18s ease,
        box-shadow 0.18s ease;
}


.inventory-search-wrapper input:focus {

    border-color: #d5a77d;

    box-shadow:
        0 0 0 3px
        rgba(196, 122, 58, 0.07);
}


.inventory-search-wrapper input::placeholder {

    color: #aaa19a;
}


.inventory-search-icon {

    position: absolute;

    left: 11px;

    top: 50%;

    transform:
        translateY(-50%);

    color: var(--muted);

    font-size: 0.875rem;

    pointer-events: none;
}


.inventory-filters select {

    height: 35px;

    min-width: 125px;

    padding:
        0
        10px;

    border:
        1px solid var(--border);

    border-radius: 8px;

    background: white;

    color: var(--text);

    font-family: inherit;

    font-size: 0.75rem;

    outline: none;

    cursor: pointer;
}


/* =========================================================
   FILTER BUTTON
========================================================= */

.inventory-filter-button,
.inventory-clear-button {

    height: 35px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    padding:
        0
        11px;

    border-radius: 8px;

    font-family: inherit;

    font-size: 0.75rem;

    font-weight: 700;

    line-height: 1.2;

    text-decoration: none;

    cursor: pointer;
}


.inventory-filter-button {

    border: none;

    background: var(--dark);

    color: white;

    transition:
        background-color 0.18s ease;
}


.inventory-filter-button:hover {

    background: var(--dark-soft);
}


.inventory-clear-button {

    border:
        1px solid var(--border);

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

    min-width: 1080px;

    border-collapse: collapse;
}


.inventory-table th {

    padding:
        10px
        12px;

    background: #fbf9f6;

    color: var(--muted);

    border-bottom:
        1px solid var(--border);

    text-align: left;

    font-size: 0.625rem;

    line-height: 1.3;

    font-weight: 800;

    text-transform: uppercase;

    letter-spacing: 0.04rem;

    white-space: nowrap;
}


.inventory-table td {

    padding:
        11px
        12px;

    color: #625951;

    border-bottom:
        1px solid #f0ebe6;

    font-size: 0.75rem;

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
   ITEM
========================================================= */

.inventory-item-cell {

    display: flex;

    align-items: center;

    gap: 10px;
}


.inventory-item-image {

    width: 38px;

    height: 38px;

    border-radius: 50%;

    overflow: hidden;

    flex-shrink: 0;

    background: #f8f1e9;

    display: flex;

    align-items: center;

    justify-content: center;

    border:
        1px solid #f0e6db;
}


.inventory-item-image img {

    width: 100%;

    height: 100%;

    object-fit: cover;
}


.inventory-fallback-icon {

    font-size: 1.05rem;

    line-height: 1;
}


.inventory-item-info {

    min-width: 0;
}


.inventory-name {

    color: var(--dark);

    font-size: 0.75rem;

    line-height: 1.4;

    font-weight: 700;
}


.inventory-description {

    max-width: 210px;

    margin-top: 2px;

    color: var(--muted);

    font-size: 0.625rem;

    line-height: 1.35;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;
}


.inventory-muted {

    color: #a39b95;

    font-size: 0.75rem;
}


/* =========================================================
   SKU
========================================================= */

.inventory-sku {

    display: inline-block;

    padding:
        3px
        5px;

    border-radius: 5px;

    background: #f5f0eb;

    color: var(--brown);

    font-family: monospace;

    font-size: 0.625rem;
}


/* =========================================================
   CATEGORY
========================================================= */

.inventory-category {

    display: inline-flex;

    align-items: center;

    padding:
        4px
        6px;

    border-radius: 6px;

    background: #f6f3f0;

    color: #75675d;

    font-size: 0.625rem;

    line-height: 1.2;

    font-weight: 600;
}


/* =========================================================
   STOCK
========================================================= */

.inventory-stock {

    min-width: 115px;
}


.inventory-stock-main {

    display: flex;

    align-items: baseline;

    gap: 3px;
}


.inventory-stock-number {

    color: var(--dark);

    font-size: 0.8125rem;

    line-height: 1.2;

    font-weight: 800;
}


.inventory-unit {

    color: var(--muted);

    font-size: 0.625rem;

    line-height: 1;

    font-weight: 600;
}


.inventory-stock-note {

    margin-top: 2px;

    color: #9b938c;

    font-size: 0.625rem;

    line-height: 1.3;
}


.inventory-progress {

    width: 100%;

    height: 3px;

    margin-top: 6px;

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

    font-size: 0.75rem;

    font-weight: 600;

    white-space: nowrap;
}


/* =========================================================
   UNIT COST
========================================================= */

.inventory-price {

    color: var(--dark);

    font-size: 0.75rem;

    font-weight: 800;

    white-space: nowrap;
}


/* =========================================================
   LOCATION
========================================================= */

.inventory-location {

    color: #625951;

    font-size: 0.75rem;
}


/* =========================================================
   STATUS
========================================================= */

.inventory-status {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 4px;

    padding:
        4px
        7px;

    border-radius: 7px;

    font-size: 0.625rem;

    line-height: 1.2;

    font-weight: 800;

    white-space: nowrap;

    letter-spacing: 0.015rem;
}


.inventory-status::before {

    content: "";

    width: 5px;

    height: 5px;

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

    gap: 4px;

    white-space: nowrap;
}


/* =========================================================
   STOCK BUTTON
========================================================= */

.inventory-stock-button {

    height: 27px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 3px;

    padding:
        0
        7px;

    border:
        1px solid #d7e5dc;

    border-radius: 7px;

    background: #f6fbf8;

    color: var(--green);

    text-decoration: none;

    font-family: inherit;

    font-size: 0.625rem;

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

    font-size: 0.6875rem;

    line-height: 1;
}


/* =========================================================
   EDIT BUTTON
========================================================= */

.inventory-edit-button {

    height: 27px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 3px;

    padding:
        0
        7px;

    border:
        1px solid #e1d4c8;

    border-radius: 7px;

    background: white;

    color: #7d5b42;

    text-decoration: none;

    font-family: inherit;

    font-size: 0.625rem;

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

    font-size: 0.6875rem;

    line-height: 1;
}


/* =========================================================
   EMPTY STATE
========================================================= */

.inventory-empty-state {

    padding:
        55px
        20px !important;

    text-align: center !important;
}


.inventory-empty-icon {

    width: 46px;

    height: 46px;

    margin:
        0
        auto
        11px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 12px;

    background: var(--orange-light);

    color: var(--orange);

    font-size: 1.05rem;
}


.inventory-empty-title {

    color: var(--dark);

    font-size: 0.8125rem;

    font-weight: 700;
}


.inventory-empty-description {

    margin-top: 4px;

    color: var(--muted);

    font-size: 0.6875rem;

    line-height: 1.4;
}


/* =========================================================
   PAGINATION
========================================================= */

.inventory-pagination-wrapper {

    padding:
        12px
        18px;

    border-top:
        1px solid var(--border);

    background: white;
}


.inventory-pagination-inner {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;
}


.inventory-pagination-info {

    color: var(--muted);

    font-size: 0.6875rem;

    line-height: 1.4;

    white-space: nowrap;
}


.inventory-pagination-info strong {

    color: var(--dark);

    font-weight: 700;
}


.inventory-pagination-links {

    display: flex;

    align-items: center;

    justify-content: flex-end;
}


.inventory-pagination-links nav {

    display: flex;

    align-items: center;

    justify-content: center;
}


.inventory-pagination-links nav > div {

    display: flex;

    align-items: center;

    justify-content: center;
}


.inventory-pagination-links nav > div > span,
.inventory-pagination-links nav > div > a {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-width: 30px;

    height: 30px;

    margin-left: 4px;

    padding:
        0
        8px;

    border:
        1px solid var(--border);

    border-radius: 7px;

    background: white;

    color: var(--muted);

    font-size: 0.6875rem;

    line-height: 1;

    font-weight: 700;

    text-decoration: none;

    transition:
        background-color 0.18s ease,
        border-color 0.18s ease,
        color 0.18s ease;
}


.inventory-pagination-links nav > div > a:hover {

    background: #faf5ef;

    border-color: #d8c2ae;

    color: var(--orange);
}


/*
|--------------------------------------------------------------------------
| ACTIVE PAGE
|--------------------------------------------------------------------------
*/

.inventory-pagination-links nav > div > span[aria-current="page"] {

    border-color: var(--orange);

    background:
        linear-gradient(
            135deg,
            var(--orange),
            var(--orange-dark)
        );

    color: white;

    box-shadow:
        0 3px 8px
        rgba(168, 95, 40, 0.12);
}


/*
|--------------------------------------------------------------------------
| DISABLED BUTTONS
|--------------------------------------------------------------------------
*/

.inventory-pagination-links nav > div > span[aria-disabled="true"] {

    opacity: 0.45;

    cursor: not-allowed;

    background: #faf8f6;

    color: #aaa19a;
}


/*
|--------------------------------------------------------------------------
| PAGINATION SVG
|--------------------------------------------------------------------------
*/

.inventory-pagination-links svg {

    width: 14px;

    height: 14px;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1200px) {

    .inventory-stats {

        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

}


@media (max-width: 700px) {

    .inventory-stats {

        grid-template-columns: 1fr;
    }


    .inventory-stat {

        min-height: 115px;
    }


    .inventory-stat-left {

        padding-top: 1px;
    }


    .inventory-stat-right {

        min-width: 72px;

        padding-top: 3px;
    }


    .inventory-stat-value {

        font-size: 1.45rem;
    }


    .inventory-money {

        font-size: 1.05rem;
    }


    .inventory-stat-note {

        margin-top: 55px;
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


    /* =====================================================
       MOBILE PAGINATION
    ===================================================== */

    .inventory-pagination-inner {

        align-items: center;

        flex-direction: column;

        justify-content: center;

        gap: 9px;
    }


    .inventory-pagination-info {

        text-align: center;
    }


    .inventory-pagination-links {

        width: 100%;

        justify-content: center;
    }


    .inventory-pagination-links nav > div > span,
    .inventory-pagination-links nav > div > a {

        min-width: 28px;

        height: 28px;

        padding:
            0
            6px;

        font-size: 0.625rem;
    }

}

</style>

@endpush