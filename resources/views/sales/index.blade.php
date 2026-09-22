@extends('layouts.app')

@section('title', 'BiteSync | Sales')

@section('content')

@php

/*
|--------------------------------------------------------------------------
| SALES PAGE DATA
|--------------------------------------------------------------------------
*/

$currentUser = auth()->user();

$role = $currentUser?->role;

$canManageSales = $role === 'CEO/Admin';


/*
|--------------------------------------------------------------------------
| STATUS CLASSES
|--------------------------------------------------------------------------
*/

$statusClasses = [

    'Completed' => 'sale-status-completed',

    'Cancelled' => 'sale-status-cancelled',

];


/*
|--------------------------------------------------------------------------
| STATUS ICONS
|--------------------------------------------------------------------------
*/

$statusIcons = [

    'Completed' => '✓',

    'Cancelled' => '⊘',

];


/*
|--------------------------------------------------------------------------
| PAYMENT METHOD ICONS
|--------------------------------------------------------------------------
*/

$paymentIcons = [

    'Cash' => '₱',

    'GCash' => 'G',

    'Card' => '▣',

    'Bank Transfer' => '↔',

];


/*
|--------------------------------------------------------------------------
| FALLBACK STATUS OPTIONS
|--------------------------------------------------------------------------
|
| Prevents the view from depending on a separate $statuses variable.
|
*/

$saleStatuses = array_keys($statusClasses);

@endphp

<div class="sales-page">

<!-- =========================================================
     SALES TOPBAR
     MATCHES INVENTORY
========================================================== -->

<div class="topbar">

    <div class="page-title">

        <small>
            Sales Management
        </small>

        <h1>
            Sales
        </h1>

        <p>
            Manage completed sales, payments, and sales records.
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
     MATCHES INVENTORY EXACTLY
========================================================== -->

<section class="sale-stats">


    <!-- TOTAL SALES -->

    <div class="sale-stat">

        <div class="sale-stat-left">

            <div class="sale-stat-label">
                TOTAL SALES
            </div>

            <div class="sale-stat-note">
                All sales records
            </div>

        </div>


        <div class="sale-stat-right">

            <div class="sale-stat-icon">
                ▦
            </div>

            <div class="sale-stat-value">
                {{ $stats['total'] }}
            </div>

        </div>

    </div>


    <!-- COMPLETED -->

    <div class="sale-stat">

        <div class="sale-stat-left">

            <div class="sale-stat-label">
                COMPLETED
            </div>

            <div class="sale-stat-note">
                Successfully completed sales
            </div>

        </div>


        <div class="sale-stat-right">

            <div class="sale-stat-icon">
                ✓
            </div>

            <div class="sale-stat-value">
                {{ $stats['completed'] }}
            </div>

        </div>

    </div>


    <!-- TODAY -->

    <div class="sale-stat">

        <div class="sale-stat-left">

            <div class="sale-stat-label">
                TODAY
            </div>

            <div class="sale-stat-note">
                Completed sales today
            </div>

        </div>


        <div class="sale-stat-right">

            <div class="sale-stat-icon">
                ◷
            </div>

            <div class="sale-stat-value">
                {{ $stats['today'] }}
            </div>

        </div>

    </div>


    <!-- REVENUE -->

    <div class="sale-stat">

        <div class="sale-stat-left">

            <div class="sale-stat-label">
                REVENUE
            </div>

            <div class="sale-stat-note">
                Revenue from completed sales
            </div>

        </div>


        <div class="sale-stat-right">

            <div class="sale-stat-icon">
                ₱
            </div>

            <div class="sale-stat-value sale-revenue-value">
                ₱{{ number_format((float) $stats['revenue'], 2) }}
            </div>

        </div>

    </div>


</section>


<!-- =========================================================
     SALES RECORDS PANEL
     MATCHES INVENTORY
========================================================== -->

<div class="sales-panel">


    <!-- =====================================================
         PANEL HEADER
    ====================================================== -->

    <div class="sales-panel-header">

        <div>

            <div class="sales-panel-title">
                Sales Records
            </div>

            <div class="sales-panel-subtitle">
                Search and manage your sales transactions.
            </div>

        </div>


        @if ($canManageSales)

            <a
                href="{{ route('sales.create') }}"
                class="sale-add-button"
            >

                <span>
                    +
                </span>

                Add Sale

            </a>

        @endif

    </div>


    <!-- =====================================================
         SEARCH / FILTER
    ====================================================== -->

    <form
        method="GET"
        action="{{ route('sales.index') }}"
        class="sale-filters"
    >


        <div class="sale-search-wrapper">

            <span class="sale-search-icon">
                ⌕
            </span>

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search sale number or product..."
            >

        </div>


        <select name="status">

            <option value="">
                All Status
            </option>


            @foreach ($saleStatuses as $status)

                <option
                    value="{{ $status }}"
                    {{ request('status') === $status ? 'selected' : '' }}
                >
                    {{ $status }}
                </option>

            @endforeach

        </select>


        <select name="payment_method">

            <option value="">
                All Payment Methods
            </option>


            @foreach ($paymentMethods as $paymentMethod)

                <option
                    value="{{ $paymentMethod }}"
                    {{ request('payment_method') === $paymentMethod ? 'selected' : '' }}
                >
                    {{ $paymentMethod }}
                </option>

            @endforeach

        </select>


        <button
            type="submit"
            class="sale-filter-button"
        >
            Filter
        </button>


        @if (
            request('search') ||
            request('status') ||
            request('payment_method')
        )

            <a
                href="{{ route('sales.index') }}"
                class="sale-clear-button"
            >
                Clear
            </a>

        @endif

    </form>


    <!-- =====================================================
         SUCCESS MESSAGE
    ====================================================== -->

    @if (session('success'))

        <div class="sale-alert sale-alert-success">

            {{ session('success') }}

        </div>

    @endif


    <!-- =====================================================
         ERROR MESSAGE
    ====================================================== -->

    @if ($errors->any())

        <div class="sale-alert sale-alert-error">

            {{ $errors->first() }}

        </div>

    @endif


    <!-- =====================================================
         SALES TABLE
    ====================================================== -->

    <div class="sale-table-wrapper">

        <table class="sales-table">

            <thead>

                <tr>

                    <th>
                        Sale No.
                    </th>

                    <th>
                        Sale Date
                    </th>

                    <th>
                        Items
                    </th>

                    <th>
                        Total
                    </th>

                    <th>
                        Payment
                    </th>

                    <th>
                        Status
                    </th>

                    <th>
                        Created By
                    </th>

                    <th class="sale-actions-header">
                        Actions
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse ($sales as $sale)

                    @php

                        $statusClass =
                            $statusClasses[$sale->status]
                            ?? 'sale-status-cancelled';


                        $statusIcon =
                            $statusIcons[$sale->status]
                            ?? '•';


                        $paymentIcon =
                            $paymentIcons[$sale->payment_method]
                            ?? '₱';


                        $itemCount =
                            $sale->items->sum('quantity');

                    @endphp


                    <tr>


                        <!-- SALE NUMBER -->

                        <td>

                            <a
                                href="{{ route('sales.show', $sale) }}"
                                class="sale-number"
                            >

                                {{ $sale->sale_number }}

                            </a>

                        </td>


                        <!-- SALE DATE -->

                        <td>

                            @if ($sale->sale_date)

                                {{ \Carbon\Carbon::parse(
                                    $sale->sale_date
                                )->format('M d, Y') }}

                            @else

                                <span class="sale-muted">
                                    —
                                </span>

                            @endif

                        </td>


                        <!-- ITEMS -->

                        <td>

                            <span class="sale-items-count">

                                {{ number_format(
                                    (float) $itemCount,
                                    0
                                ) }}

                                {{ $itemCount == 1 ? 'item' : 'items' }}

                            </span>

                        </td>


                        <!-- TOTAL -->

                        <td>

                            <span class="sale-price">

                                ₱{{ number_format(
                                    (float) $sale->total,
                                    2
                                ) }}

                            </span>

                        </td>


                        <!-- PAYMENT -->

                        <td>

                            <span class="sale-payment">

                                <span class="sale-payment-icon">
                                    {{ $paymentIcon }}
                                </span>

                                {{ $sale->payment_method }}

                            </span>

                        </td>


                        <!-- STATUS -->

                        <td>

                            <span
                                class="sale-status {{ $statusClass }}"
                            >

                                <span class="sale-status-icon">
                                    {{ $statusIcon }}
                                </span>

                                {{ $sale->status }}

                            </span>

                        </td>


                        <!-- CREATED BY -->

                        <td>

                            @if ($sale->creator)

                                {{ $sale->creator->name }}

                            @else

                                <span class="sale-muted">
                                    System
                                </span>

                            @endif

                        </td>


                        <!-- ACTIONS -->

                        <td>

                            <div class="sale-table-actions">

                                <a
                                    href="{{ route('sales.show', $sale) }}"
                                    class="sale-view-button"
                                >

                                    <span>
                                        ◉
                                    </span>

                                    View

                                </a>

                            </div>

                        </td>


                    </tr>


                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="sale-empty-state"
                        >

                            <div class="sale-empty-icon">
                                ₱
                            </div>


                            <div class="sale-empty-title">
                                No sales records found
                            </div>


                            <div class="sale-empty-description">

                                @if (
                                    request('search') ||
                                    request('status') ||
                                    request('payment_method')
                                )

                                    Try changing your search or filter.

                                @else

                                    Your sales records will appear here.

                                @endif

                            </div>


                            @if (
                                $canManageSales &&
                                !request('search') &&
                                !request('status') &&
                                !request('payment_method')
                            )

                                <div style="margin-top: 13px;">

                                    <a
                                        href="{{ route('sales.create') }}"
                                        class="sale-add-button"
                                    >

                                        <span>
                                            +
                                        </span>

                                        Add First Sale

                                    </a>

                                </div>

                            @endif

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    <!-- =====================================================
         PAGINATION
    ====================================================== -->

    @if ($sales->hasPages())

        <div class="sale-pagination-wrapper">

            {{ $sales->links() }}

        </div>

    @endif


</div>

</div>

@endsection

@push('styles')

<style>

/* =========================================================
   SALES PAGE
========================================================= */

.sales-page {
    width: 100%;
}


/* =========================================================
   PAGE TITLE
   MATCHES INVENTORY
========================================================= */

.sales-page .page-title h1 {

    font-size:
        clamp(1.6rem, 2vw, 1.9rem);

    line-height: 1.15;

    letter-spacing: -0.04rem;
}


/* =========================================================
   SUMMARY CARDS
   MATCHES INVENTORY EXACTLY
========================================================= */

.sale-stats {

    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 17px;

    margin-bottom: 17px;
}


.sale-stat {

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

.sale-stat::before {

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

.sale-stat-left {

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
   LABEL
========================================================= */

.sale-stat-label {

    color: var(--muted);

    font-size: 0.6875rem;

    line-height: 1.3;

    font-weight: 800;

    letter-spacing: 0.045rem;

    white-space: nowrap;
}


/* =========================================================
   NOTE
   SAME POSITION AS INVENTORY
========================================================= */

.sale-stat-note {

    margin-top: 58px;

    color: #9d958f;

    font-size: 0.6875rem;

    line-height: 1.4;

    max-width: 155px;
}


/* =========================================================
   RIGHT SIDE
========================================================= */

.sale-stat-right {

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

.sale-stat-icon {

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

.sale-stat-value {

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
   REVENUE VALUE
========================================================= */

.sale-revenue-value {

    font-size:
        clamp(1rem, 1.3vw, 1.25rem);

    letter-spacing: -0.02rem;
}


/* =========================================================
   SALES PANEL
   MATCHES INVENTORY
========================================================= */

.sales-panel {

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

.sales-panel-header {

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


.sales-panel-title {

    color: var(--dark);

    font-size: 0.875rem;

    line-height: 1.3;

    font-weight: 700;
}


.sales-panel-subtitle {

    margin-top: 3px;

    color: var(--muted);

    font-size: 0.6875rem;

    line-height: 1.4;
}


/* =========================================================
   ADD SALE
   MATCHES INVENTORY
========================================================= */

.sale-add-button {

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


.sale-add-button:hover {

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


.sale-add-button span {

    font-size: 0.8125rem;

    line-height: 1;
}


/* =========================================================
   FILTERS
   MATCHES INVENTORY
========================================================= */

.sale-filters {

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


.sale-search-wrapper {

    flex: 1;

    position: relative;

    min-width: 180px;
}


.sale-search-wrapper input {

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


.sale-search-wrapper input:focus {

    border-color: #d5a77d;

    box-shadow:
        0 0 0 3px
        rgba(196, 122, 58, 0.07);
}


.sale-search-wrapper input::placeholder {

    color: #aaa19a;
}


.sale-search-icon {

    position: absolute;

    left: 11px;

    top: 50%;

    transform:
        translateY(-50%);

    color: var(--muted);

    font-size: 0.875rem;

    pointer-events: none;
}


/* =========================================================
   SELECT
========================================================= */

.sale-filters select {

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
   FILTER / CLEAR BUTTONS
========================================================= */

.sale-filter-button,
.sale-clear-button {

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


.sale-filter-button {

    border: none;

    background: var(--dark);

    color: white;

    transition:
        background-color 0.18s ease;
}


.sale-filter-button:hover {

    background: var(--dark-soft);
}


.sale-clear-button {

    border:
        1px solid var(--border);

    background: white;

    color: var(--muted);

    transition:
        background-color 0.18s ease,
        color 0.18s ease;
}


.sale-clear-button:hover {

    background: #faf7f3;

    color: var(--dark);
}


/* =========================================================
   ALERTS
========================================================= */

.sale-alert {

    margin:
        12px
        18px
        0;

    padding:
        9px
        12px;

    border-radius: 8px;

    font-size: 0.6875rem;

    font-weight: 650;
}


.sale-alert-success {

    background: #edf8f0;

    border:
        1px solid #cfe5d5;

    color: #39704d;
}


.sale-alert-error {

    background: #fff0ee;

    border:
        1px solid #eccfcb;

    color: #9e4942;
}


/* =========================================================
   TABLE
   MATCHES INVENTORY
========================================================= */

.sale-table-wrapper {

    width: 100%;

    overflow-x: auto;
}


.sales-table {

    width: 100%;

    min-width: 1080px;

    border-collapse: collapse;
}


.sales-table th {

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


.sales-table td {

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


.sales-table tbody tr {

    background: white;
}


.sales-table tbody tr:hover {

    background: #fdfaf7;
}


/* =========================================================
   SALE NUMBER
========================================================= */

.sale-number {

    color: var(--orange);

    font-size: 0.75rem;

    line-height: 1.4;

    font-weight: 700;

    text-decoration: none;

    white-space: nowrap;
}


.sale-number:hover {

    color: var(--orange-dark);

    text-decoration: underline;
}


/* =========================================================
   ITEMS
========================================================= */

.sale-items-count {

    color: #625951;

    font-size: 0.75rem;

    font-weight: 600;

    white-space: nowrap;
}


/* =========================================================
   MUTED
========================================================= */

.sale-muted {

    color: #a39b95;

    font-size: 0.75rem;
}


/* =========================================================
   PRICE
========================================================= */

.sale-price {

    color: var(--dark);

    font-size: 0.75rem;

    font-weight: 800;

    white-space: nowrap;
}


/* =========================================================
   PAYMENT
========================================================= */

.sale-payment {

    display: inline-flex;

    align-items: center;

    gap: 5px;

    color: #625951;

    font-size: 0.6875rem;

    font-weight: 700;

    white-space: nowrap;
}


.sale-payment-icon {

    width: 20px;

    height: 20px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    border-radius: 6px;

    background: #f7eee7;

    color: var(--orange);

    font-size: 0.625rem;

    font-weight: 800;
}


/* =========================================================
   STATUS
========================================================= */

.sale-status {

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


.sale-status-icon {

    font-size: 0.625rem;

    line-height: 1;
}


.sale-status-completed {

    color: var(--green);

    background: var(--green-light);
}


.sale-status-cancelled {

    color: var(--muted);

    background: #f1eeeb;
}


/* =========================================================
   ACTIONS
========================================================= */

.sale-actions-header {

    text-align: center !important;
}


.sale-table-actions {

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 4px;

    white-space: nowrap;
}


.sale-view-button {

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


.sale-view-button:hover {

    background: #faf5ef;

    border-color: #cdb49f;

    color: #68482f;
}


.sale-view-button span {

    font-size: 0.6875rem;

    line-height: 1;
}


/* =========================================================
   EMPTY STATE
   MATCHES INVENTORY
========================================================= */

.sale-empty-state {

    padding:
        55px
        20px !important;

    text-align: center !important;
}


.sale-empty-icon {

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


.sale-empty-title {

    color: var(--dark);

    font-size: 0.8125rem;

    font-weight: 700;
}


.sale-empty-description {

    margin-top: 4px;

    color: var(--muted);

    font-size: 0.6875rem;

    line-height: 1.4;
}


/* =========================================================
   PAGINATION
   MATCHES INVENTORY / PRODUCTS
========================================================= */

.sale-pagination-wrapper {

    padding:
        13px
        18px;

    border-top:
        1px solid var(--border);
}


.sale-pagination-wrapper nav {

    display: flex;

    align-items: center;

    justify-content: center;

    width: 100%;
}


.sale-pagination-wrapper nav > div {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 12px;

    width: 100%;
}


.sale-pagination-wrapper nav > div > div {

    display: flex;

    align-items: center;

    gap: 4px;
}


.sale-pagination-wrapper nav a,
.sale-pagination-wrapper nav button,
.sale-pagination-wrapper nav span[aria-current="page"],
.sale-pagination-wrapper nav span[aria-disabled="true"] {

    min-width: 30px;

    height: 30px;

    padding:
        0
        9px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    border:
        1px solid var(--border);

    border-radius: 7px;

    background: white;

    color: var(--muted);

    font-family: inherit;

    font-size: 0.6875rem;

    font-weight: 700;

    line-height: 1;

    text-decoration: none;
}


.sale-pagination-wrapper nav a:hover {

    border-color: #d5a77d;

    background: #faf7f3;

    color: var(--orange);
}


.sale-pagination-wrapper nav span[aria-current="page"] {

    border-color: var(--orange);

    background: var(--orange);

    color: white;
}


.sale-pagination-wrapper nav span[aria-current="page"] > span {

    color: white;
}


.sale-pagination-wrapper nav span[aria-disabled="true"] {

    color: #b8b0aa;

    background: #faf9f7;

    cursor: default;
}


.sale-pagination-wrapper nav svg {

    width: 13px;

    height: 13px;
}


.sale-pagination-wrapper nav p {

    margin: 0;

    color: var(--muted);

    font-size: 0.6875rem;

    line-height: 1.4;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1200px) {

    .sale-stats {

        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

}


@media (max-width: 700px) {

    .sale-stats {

        grid-template-columns: 1fr;
    }


    .sale-stat {

        min-height: 115px;
    }


    .sale-stat-left {

        padding-top: 1px;
    }


    .sale-stat-right {

        min-width: 72px;

        padding-top: 3px;
    }


    .sale-stat-value {

        font-size: 1.45rem;
    }


    .sale-revenue-value {

        font-size: 1.05rem;
    }


    .sale-stat-note {

        margin-top: 55px;
    }


    .sales-panel-header {

        align-items: flex-start;

        flex-direction: column;
    }


    .sale-add-button {

        width: 100%;
    }


    .sale-filters {

        align-items: stretch;

        flex-direction: column;
    }


    .sale-search-wrapper {

        width: 100%;
    }


    .sale-filters select,
    .sale-filter-button,
    .sale-clear-button {

        width: 100%;
    }


    .sale-table-actions {

        justify-content: flex-start;
    }


    .sale-pagination-wrapper {

        padding:
            12px;
    }


    .sale-pagination-wrapper nav > div {

        flex-direction: column;

        align-items: center;
    }

}

</style>

@endpush