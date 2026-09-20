@extends('layouts.app')

@section('title', 'BiteSync | Sales')

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | SALES PAGE DATA
    |--------------------------------------------------------------------------
    */

    $role = $user->role;


    /*
    |--------------------------------------------------------------------------
    | STATUS CLASSES
    |--------------------------------------------------------------------------
    */

    $statusClasses = [

        'Completed'
            => 'sale-status-completed',

        'Cancelled'
            => 'sale-status-cancelled',

    ];


    /*
    |--------------------------------------------------------------------------
    | STATUS ICONS
    |--------------------------------------------------------------------------
    */

    $statusIcons = [

        'Completed'
            => '✓',

        'Cancelled'
            => '⊘',

    ];


    /*
    |--------------------------------------------------------------------------
    | PAYMENT METHOD ICONS
    |--------------------------------------------------------------------------
    */

    $paymentIcons = [

        'Cash'
            => '₱',

        'GCash'
            => 'G',

        'Card'
            => '▣',

        'Bank Transfer'
            => '↔',

    ];

@endphp


<div class="sales-page">


    <!-- =========================================================
         SALES TOPBAR

         Same structure as Purchases
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
    ========================================================== -->

    <section class="sale-stats">


        <!-- =====================================================
             TOTAL SALES
        ====================================================== -->

        <div class="sale-stat">

            <div>

                <div class="sale-stat-label">
                    TOTAL SALES
                </div>

                <div class="sale-stat-value">
                    {{ $stats['total'] }}
                </div>

                <div class="sale-stat-note">
                    All sales records
                </div>

            </div>


            <div class="sale-stat-icon">
                ▦
            </div>

        </div>


        <!-- =====================================================
             COMPLETED
        ====================================================== -->

        <div class="sale-stat">

            <div>

                <div class="sale-stat-label">
                    COMPLETED
                </div>

                <div class="sale-stat-value">
                    {{ $stats['completed'] }}
                </div>

                <div class="sale-stat-note">
                    Successfully completed sales
                </div>

            </div>


            <div class="sale-stat-icon">
                ✓
            </div>

        </div>


        <!-- =====================================================
             TODAY
        ====================================================== -->

        <div class="sale-stat">

            <div>

                <div class="sale-stat-label">
                    TODAY
                </div>

                <div class="sale-stat-value">
                    {{ $stats['today'] }}
                </div>

                <div class="sale-stat-note">
                    Completed sales today
                </div>

            </div>


            <div class="sale-stat-icon">
                ◷
            </div>

        </div>


        <!-- =====================================================
             REVENUE
        ====================================================== -->

        <div class="sale-stat">

            <div>

                <div class="sale-stat-label">
                    REVENUE
                </div>

                <div class="sale-stat-value sale-revenue-value">
                    ₱{{ number_format(
                        (float) $stats['revenue'],
                        2
                    ) }}
                </div>

                <div class="sale-stat-note">
                    Revenue from completed sales
                </div>

            </div>


            <div class="sale-stat-icon">
                ₱
            </div>

        </div>


    </section>


    <!-- =========================================================
         SALES RECORDS PANEL
    ========================================================== -->

    <div class="sales-panel">


        <!-- =====================================================
             PANEL HEADER

             Same structure as Purchases
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


            <!-- =================================================
                 ADD SALE

                 CEO/Admin only
            ================================================== -->

            @if ($role === 'CEO/Admin')

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


            <!-- =================================================
                 SEARCH
            ================================================== -->

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


            <!-- =================================================
                 STATUS
            ================================================== -->

            <select name="status">

                <option value="">
                    All Status
                </option>


                @foreach ($statuses as $status)

                    <option
                        value="{{ $status }}"
                        {{ request('status') === $status ? 'selected' : '' }}
                    >
                        {{ $status }}
                    </option>

                @endforeach

            </select>


            <!-- =================================================
                 PAYMENT METHOD
            ================================================== -->

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


            <!-- =================================================
                 FILTER
            ================================================== -->

            <button
                type="submit"
                class="sale-filter-button"
            >
                Filter
            </button>


            <!-- =================================================
                 CLEAR
            ================================================== -->

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
                                $sale->items->sum(
                                    'quantity'
                                );

                        @endphp


                        <tr>


                            <!-- =================================================
                                 SALE NUMBER
                            ================================================== -->

                            <td>

                                <a
                                    href="{{ route(
                                        'sales.show',
                                        $sale
                                    ) }}"
                                    class="sale-number"
                                >

                                    {{ $sale->sale_number }}

                                </a>

                            </td>


                            <!-- =================================================
                                 SALE DATE
                            ================================================== -->

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


                            <!-- =================================================
                                 ITEMS
                            ================================================== -->

                            <td>

                                <span class="sale-items-count">

                                    {{ number_format(
                                        (float) $itemCount,
                                        0
                                    ) }}

                                    {{ $itemCount == 1 ? 'item' : 'items' }}

                                </span>

                            </td>


                            <!-- =================================================
                                 TOTAL
                            ================================================== -->

                            <td>

                                <span class="sale-price">

                                    ₱{{ number_format(
                                        (float) $sale->total,
                                        2
                                    ) }}

                                </span>

                            </td>


                            <!-- =================================================
                                 PAYMENT METHOD
                            ================================================== -->

                            <td>

                                <span class="sale-payment">

                                    <span class="sale-payment-icon">

                                        {{ $paymentIcon }}

                                    </span>

                                    {{ $sale->payment_method }}

                                </span>

                            </td>


                            <!-- =================================================
                                 STATUS
                            ================================================== -->

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


                            <!-- =================================================
                                 CREATED BY
                            ================================================== -->

                            <td>

                                @if ($sale->creator)

                                    {{ $sale->creator->name }}

                                @else

                                    <span class="sale-muted">
                                        System
                                    </span>

                                @endif

                            </td>


                            <!-- =================================================
                                 ACTIONS
                            ================================================== -->

                            <td>

                                <div class="sale-table-actions">


                                    <!-- =========================================
                                         VIEW
                                    ========================================== -->

                                    <a
                                        href="{{ route(
                                            'sales.show',
                                            $sale
                                        ) }}"
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


                        <!-- =================================================
                             EMPTY STATE
                        ================================================== -->

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

                                        Try changing your search
                                        or filter.

                                    @else

                                        Your sales records
                                        will appear here.

                                    @endif

                                </div>


                                @if (
                                    $role === 'CEO/Admin' &&
                                    !request('search') &&
                                    !request('status') &&
                                    !request('payment_method')
                                )

                                    <div style="margin-top: 16px;">

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

   Same layout structure as Purchases
========================================================= */

.sales-page {

    width: 100%;

}


/* =========================================================
   PAGE TITLE
========================================================= */

.sales-page .page-title h1 {

    font-size: clamp(
        1.75rem,
        2.2vw,
        2rem
    );

    line-height: 1.15;

    letter-spacing: -0.045rem;

}


/* =========================================================
   SUMMARY CARDS
========================================================= */

.sale-stats {

    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 17px;

    margin-bottom: 22px;

}


.sale-stat {

    min-height: 135px;

    display: flex;

    align-items: flex-start;

    justify-content: space-between;

    gap: 15px;

    padding: 20px;

    border: 1px solid var(--border);

    border-radius: 17px;

    background: white;

    box-shadow:
        0 7px 22px
        rgba(43, 31, 23, 0.055);

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

    width: 4px;

    background:
        linear-gradient(
            180deg,
            var(--orange),
            #e2a16c
        );

}


.sale-stat-label {

    color: var(--muted);

    font-size: 0.8125rem;

    line-height: 1.3;

    font-weight: 800;

    letter-spacing: 0.05rem;

}


.sale-stat-value {

    margin-top: 14px;

    color: var(--dark);

    font-size: 1.875rem;

    line-height: 1;

    font-weight: 800;

}


.sale-revenue-value {

    font-size: 1.55rem;

}


.sale-stat-note {

    margin-top: 8px;

    color: #9d958f;

    font-size: 0.8125rem;

    line-height: 1.4;

}


.sale-stat-icon {

    width: 40px;

    height: 40px;

    display: flex;

    align-items: center;

    justify-content: center;

    flex-shrink: 0;

    border-radius: 11px;

    background:
        linear-gradient(
            135deg,
            #fbf1e7,
            #f4e3d4
        );

    color: var(--orange);

    font-size: 0.875rem;

    font-weight: 800;

}


/* =========================================================
   SALES PANEL
========================================================= */

.sales-panel {

    background: white;

    border: 1px solid var(--border);

    border-radius: 17px;

    overflow: hidden;

    box-shadow:
        0 5px 18px
        rgba(43, 31, 23, 0.035);

}


/* =========================================================
   PANEL HEADER
========================================================= */

.sales-panel-header {

    min-height: 75px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    padding: 17px 21px;

    border-bottom: 1px solid var(--border);

}


.sales-panel-title {

    color: var(--dark);

    font-size: 1rem;

    line-height: 1.3;

    font-weight: 700;

}


.sales-panel-subtitle {

    margin-top: 4px;

    color: var(--muted);

    font-size: 0.8125rem;

    line-height: 1.4;

}


/* =========================================================
   ADD SALE
========================================================= */

.sale-add-button {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 6px;

    padding: 8px 12px;

    border-radius: 8px;

    background:
        linear-gradient(
            135deg,
            var(--orange),
            var(--orange-dark)
        );

    color: white;

    text-decoration: none;

    font-size: 0.8125rem;

    line-height: 1.2;

    font-weight: 700;

    box-shadow:
        0 4px 11px
        rgba(168, 95, 40, 0.14);

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
        0 5px 12px
        rgba(168, 95, 40, 0.16);

}


.sale-add-button span {

    font-size: 0.9375rem;

    line-height: 1;

}


/* =========================================================
   FILTERS

   Same structure as Purchases
========================================================= */

.sale-filters {

    display: flex;

    align-items: center;

    gap: 10px;

    padding: 15px 21px;

    background: var(--card-soft);

    border-bottom: 1px solid var(--border);

}


.sale-search-wrapper {

    flex: 1;

    position: relative;

    min-width: 180px;

}


.sale-search-wrapper input {

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


.sale-search-wrapper input:focus {

    border-color: #d5a77d;

    box-shadow:
        0 0 0 3px
        rgba(196, 122, 58, 0.08);

}


.sale-search-wrapper input::placeholder {

    color: #aaa19a;

}


.sale-search-icon {

    position: absolute;

    left: 13px;

    top: 50%;

    transform: translateY(-50%);

    color: var(--muted);

    font-size: 0.9375rem;

    pointer-events: none;

}


.sale-filters select {

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

.sale-filter-button,
.sale-clear-button {

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

    border: 1px solid var(--border);

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

    margin: 15px 21px 0;

    padding: 10px 13px;

    border-radius: 8px;

    font-size: 0.75rem;

    font-weight: 650;

}


.sale-alert-success {

    background: #edf8f0;

    border: 1px solid #cfe5d5;

    color: #39704d;

}


.sale-alert-error {

    background: #fff0ee;

    border: 1px solid #eccfcb;

    color: #9e4942;

}


/* =========================================================
   TABLE
========================================================= */

.sale-table-wrapper {

    width: 100%;

    overflow-x: auto;

}


.sales-table {

    width: 100%;

    min-width: 900px;

    border-collapse: collapse;

}


/* =========================================================
   TABLE HEADER

   Same as Purchases
========================================================= */

.sales-table th {

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


/* =========================================================
   TABLE BODY
========================================================= */

.sales-table td {

    padding: 13px 14px;

    color: #625951;

    border-bottom: 1px solid #f0ebe6;

    font-size: 0.8125rem;

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

    font-size: 0.8125rem;

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

    font-size: 0.8125rem;

    font-weight: 600;

    white-space: nowrap;

}


/* =========================================================
   MUTED
========================================================= */

.sale-muted {

    color: #a39b95;

    font-size: 0.8125rem;

}


/* =========================================================
   SALE PRICE
========================================================= */

.sale-price {

    color: var(--dark);

    font-size: 0.8125rem;

    font-weight: 800;

    white-space: nowrap;

}


/* =========================================================
   PAYMENT
========================================================= */

.sale-payment {

    display: inline-flex;

    align-items: center;

    gap: 6px;

    color: #625951;

    font-size: 0.75rem;

    font-weight: 700;

    white-space: nowrap;

}


.sale-payment-icon {

    width: 21px;

    height: 21px;

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

    padding: 5px 8px;

    border-radius: 7px;

    font-size: 0.6875rem;

    line-height: 1.2;

    font-weight: 800;

    white-space: nowrap;

    letter-spacing: 0.02rem;

}


.sale-status-icon {

    font-size: 0.6875rem;

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

   Same compact style as Purchases
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

    height: 28px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 4px;

    padding: 0 7px;

    border: 1px solid #dfd0c3;

    border-radius: 7px;

    background: #faf7f3;

    color: #79583f;

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


.sale-view-button:hover {

    background: #f5eee7;

    border-color: #cdb49f;

    color: #68482f;

}


.sale-view-button span {

    font-size: 0.75rem;

    line-height: 1;

}


/* =========================================================
   EMPTY STATE

   Same as Purchases
========================================================= */

.sale-empty-state {

    padding: 65px 20px !important;

    text-align: center !important;

}


.sale-empty-icon {

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


.sale-empty-title {

    color: var(--dark);

    font-size: 0.875rem;

    font-weight: 700;

}


.sale-empty-description {

    margin-top: 5px;

    color: var(--muted);

    font-size: 0.8125rem;

    line-height: 1.4;

}


/* =========================================================
   PAGINATION

   Same as Purchases
========================================================= */

.sale-pagination-wrapper {

    padding: 15px 21px;

    border-top: 1px solid var(--border);

}


.sale-pagination-wrapper nav {

    display: flex;

    justify-content: center;

}


.sale-pagination-wrapper svg {

    width: 15px;

    height: 15px;

}


/* =========================================================
   RESPONSIVE

   Same as Purchases
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

}

</style>

@endpush