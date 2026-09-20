@extends('layouts.app')

@section('title', 'BiteSync | Purchases')

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | PURCHASE PAGE DATA
    |--------------------------------------------------------------------------
    */

    $role = $user->role;

    $statusClasses = [

        'Draft'
            => 'purchase-status-draft',

        'Pending Approval'
            => 'purchase-status-pending',

        'Approved'
            => 'purchase-status-approved',

        'Rejected'
            => 'purchase-status-rejected',

        'Ordered'
            => 'purchase-status-ordered',

        'Partially Received'
            => 'purchase-status-partial',

        'Received'
            => 'purchase-status-received',

        'Cancelled'
            => 'purchase-status-cancelled',

    ];


    $statusIcons = [

        'Draft'
            => '📝',

        'Pending Approval'
            => '⏳',

        'Approved'
            => '✓',

        'Rejected'
            => '✕',

        'Ordered'
            => '📦',

        'Partially Received'
            => '◐',

        'Received'
            => '✓',

        'Cancelled'
            => '⊘',

    ];

@endphp


<div class="purchases-page">


    <!-- =========================================================
         PURCHASES TOPBAR

         Same structure as Products
    ========================================================== -->

    <div class="topbar">

        <div class="page-title">

            <small>
                Purchasing
            </small>

            <h1>
                Purchases
            </h1>

            <p>
                Manage supplier orders, approvals, and purchasing records.
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

    <section class="purchase-stats">


        <!-- TOTAL PURCHASES -->

        <div class="purchase-stat">

            <div>

                <div class="purchase-stat-label">
                    TOTAL PURCHASES
                </div>

                <div class="purchase-stat-value">
                    {{ $stats['total'] }}
                </div>

                <div class="purchase-stat-note">
                    All purchase records
                </div>

            </div>


            <div class="purchase-stat-icon">
                ▦
            </div>

        </div>


        <!-- DRAFTS -->

        <div class="purchase-stat">

            <div>

                <div class="purchase-stat-label">
                    DRAFTS
                </div>

                <div class="purchase-stat-value">
                    {{ $stats['draft'] }}
                </div>

                <div class="purchase-stat-note">
                    Purchases still being prepared
                </div>

            </div>


            <div class="purchase-stat-icon">
                📝
            </div>

        </div>


        <!-- PENDING APPROVAL -->

        <div class="purchase-stat">

            <div>

                <div class="purchase-stat-label">
                    PENDING APPROVAL
                </div>

                <div class="purchase-stat-value">
                    {{ $stats['pending'] }}
                </div>

                <div class="purchase-stat-note">
                    Awaiting approval
                </div>

            </div>


            <div class="purchase-stat-icon">
                ⏳
            </div>

        </div>


        <!-- RECEIVED -->

        <div class="purchase-stat">

            <div>

                <div class="purchase-stat-label">
                    RECEIVED
                </div>

                <div class="purchase-stat-value">
                    {{ $stats['received'] }}
                </div>

                <div class="purchase-stat-note">
                    Successfully received
                </div>

            </div>


            <div class="purchase-stat-icon">
                ✓
            </div>

        </div>


    </section>


    <!-- =========================================================
         PURCHASE RECORDS PANEL
    ========================================================== -->

    <div class="purchases-panel">


        <!-- =====================================================
             PANEL HEADER

             Same structure as Products
        ====================================================== -->

        <div class="purchases-panel-header">

            <div>

                <div class="purchases-panel-title">
                    Purchase Records
                </div>

                <div class="purchases-panel-subtitle">
                    Search and manage your supplier purchase records.
                </div>

            </div>


            <!-- ADD PURCHASE -->

            @if (
                $role === 'CEO/Admin' ||
                $role === 'Procurement'
            )

                <a
                    href="{{ route('purchases.create') }}"
                    class="purchase-add-button"
                >

                    <span>
                        +
                    </span>

                    Add Purchase

                </a>

            @endif

        </div>


        <!-- =====================================================
             SEARCH / FILTER
        ====================================================== -->

        <form
            method="GET"
            action="{{ route('purchases.index') }}"
            class="purchase-filters"
        >


            <!-- SEARCH -->

            <div class="purchase-search-wrapper">

                <span class="purchase-search-icon">
                    ⌕
                </span>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search purchase number or supplier..."
                >

            </div>


            <!-- STATUS -->

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


            <!-- SUPPLIER -->

            <select name="supplier_id">

                <option value="">
                    All Suppliers
                </option>


                @foreach ($suppliers as $supplier)

                    <option
                        value="{{ $supplier->id }}"
                        {{ (string) request('supplier_id') === (string) $supplier->id ? 'selected' : '' }}
                    >
                        {{ $supplier->name }}
                    </option>

                @endforeach

            </select>


            <!-- FILTER -->

            <button
                type="submit"
                class="purchase-filter-button"
            >
                Filter
            </button>


            <!-- CLEAR -->

            @if (
                request('search') ||
                request('status') ||
                request('supplier_id')
            )

                <a
                    href="{{ route('purchases.index') }}"
                    class="purchase-clear-button"
                >
                    Clear
                </a>

            @endif

        </form>


        <!-- =====================================================
             SUCCESS MESSAGE
        ====================================================== -->

        @if (session('success'))

            <div class="purchase-alert purchase-alert-success">

                {{ session('success') }}

            </div>

        @endif


        <!-- =====================================================
             ERROR MESSAGE
        ====================================================== -->

        @if ($errors->any())

            <div class="purchase-alert purchase-alert-error">

                {{ $errors->first() }}

            </div>

        @endif


        <!-- =====================================================
             PURCHASE TABLE
        ====================================================== -->

        <div class="purchase-table-wrapper">

            <table class="purchases-table">


                <thead>

                    <tr>

                        <th>
                            Purchase No.
                        </th>

                        <th>
                            Supplier
                        </th>

                        <th>
                            Purchase Date
                        </th>

                        <th>
                            Expected Date
                        </th>

                        <th>
                            Total
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Created By
                        </th>

                        <th class="purchase-actions-header">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>


                    @forelse ($purchases as $purchase)


                        @php

                            $statusClass =
                                $statusClasses[$purchase->status]
                                ?? 'purchase-status-draft';


                            $statusIcon =
                                $statusIcons[$purchase->status]
                                ?? '•';

                        @endphp


                        <tr>


                            <!-- =================================================
                                 PURCHASE NUMBER
                            ================================================== -->

                            <td>

                                <a
                                    href="{{ route(
                                        'purchases.show',
                                        $purchase
                                    ) }}"
                                    class="purchase-number"
                                >

                                    {{ $purchase->purchase_number }}

                                </a>

                            </td>


                            <!-- =================================================
                                 SUPPLIER
                            ================================================== -->

                            <td>

                                @if ($purchase->supplier)

                                    <div class="purchase-supplier">

                                        {{ $purchase->supplier->name }}

                                    </div>

                                @else

                                    <span class="purchase-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            <!-- =================================================
                                 PURCHASE DATE
                            ================================================== -->

                            <td>

                                @if ($purchase->purchase_date)

                                    {{ $purchase->purchase_date->format('M d, Y') }}

                                @else

                                    <span class="purchase-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            <!-- =================================================
                                 EXPECTED DATE
                            ================================================== -->

                            <td>

                                @if ($purchase->expected_date)

                                    {{ $purchase->expected_date->format('M d, Y') }}

                                @else

                                    <span class="purchase-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            <!-- =================================================
                                 TOTAL
                            ================================================== -->

                            <td>

                                <span class="purchase-price">

                                    ₱{{ number_format(
                                        (float) $purchase->total,
                                        2
                                    ) }}

                                </span>

                            </td>


                            <!-- =================================================
                                 STATUS
                            ================================================== -->

                            <td>

                                <span
                                    class="purchase-status {{ $statusClass }}"
                                >

                                    <span class="purchase-status-icon">
                                        {{ $statusIcon }}
                                    </span>

                                    {{ $purchase->status }}

                                </span>

                            </td>


                            <!-- =================================================
                                 CREATED BY
                            ================================================== -->

                            <td>

                                @if ($purchase->creator)

                                    {{ $purchase->creator->name }}

                                @else

                                    <span class="purchase-muted">
                                        System
                                    </span>

                                @endif

                            </td>


                            <!-- =================================================
                                 ACTIONS
                            ================================================== -->

                            <td>

                                <div class="purchase-table-actions">


                                    <!-- VIEW -->

                                    <a
                                        href="{{ route(
                                            'purchases.show',
                                            $purchase
                                        ) }}"
                                        class="purchase-view-button"
                                    >

                                        <span>
                                            ◉
                                        </span>

                                        View

                                    </a>


                                    <!-- EDIT -->

                                    @if (
                                        (
                                            $role === 'CEO/Admin' ||
                                            $role === 'Procurement'
                                        )
                                        &&
                                        (
                                            $purchase->isDraft() ||
                                            $purchase->isRejected()
                                        )
                                    )

                                        <a
                                            href="{{ route(
                                                'purchases.edit',
                                                $purchase
                                            ) }}"
                                            class="purchase-edit-button"
                                        >

                                            <span>
                                                ✎
                                            </span>

                                            Edit

                                        </a>

                                    @endif


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
                                class="purchase-empty-state"
                            >

                                <div class="purchase-empty-icon">
                                    ▦
                                </div>


                                <div class="purchase-empty-title">
                                    No purchase records found
                                </div>


                                <div class="purchase-empty-description">

                                    @if (
                                        request('search') ||
                                        request('status') ||
                                        request('supplier_id')
                                    )

                                        Try changing your search
                                        or filter.

                                    @else

                                        Your purchase records
                                        will appear here.

                                    @endif

                                </div>


                                @if (
                                    (
                                        $role === 'CEO/Admin' ||
                                        $role === 'Procurement'
                                    )
                                    &&
                                    !request('search')
                                    &&
                                    !request('status')
                                    &&
                                    !request('supplier_id')
                                )

                                    <div style="margin-top: 16px;">

                                        <a
                                            href="{{ route('purchases.create') }}"
                                            class="purchase-add-button"
                                        >

                                            <span>
                                                +
                                            </span>

                                            Add First Purchase

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

        @if ($purchases->hasPages())

            <div class="purchase-pagination-wrapper">

                {{ $purchases->links() }}

            </div>

        @endif


    </div>

</div>

@endsection


@push('styles')

<style>

/* =========================================================
   PURCHASES PAGE

   Same layout structure as Products
========================================================= */

.purchases-page {

    width: 100%;

}


/* =========================================================
   PAGE TITLE
========================================================= */

.purchases-page .page-title h1 {

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

.purchase-stats {

    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 17px;

    margin-bottom: 22px;

}


.purchase-stat {

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


/* LEFT ACCENT */

.purchase-stat::before {

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


.purchase-stat-label {

    color: var(--muted);

    font-size: 0.8125rem;

    line-height: 1.3;

    font-weight: 800;

    letter-spacing: 0.05rem;

}


.purchase-stat-value {

    margin-top: 14px;

    color: var(--dark);

    font-size: 1.875rem;

    line-height: 1;

    font-weight: 800;

}


.purchase-stat-note {

    margin-top: 8px;

    color: #9d958f;

    font-size: 0.8125rem;

    line-height: 1.4;

}


.purchase-stat-icon {

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
   PURCHASE PANEL
========================================================= */

.purchases-panel {

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

.purchases-panel-header {

    min-height: 75px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    padding: 17px 21px;

    border-bottom: 1px solid var(--border);

}


.purchases-panel-title {

    color: var(--dark);

    font-size: 1rem;

    line-height: 1.3;

    font-weight: 700;

}


.purchases-panel-subtitle {

    margin-top: 4px;

    color: var(--muted);

    font-size: 0.8125rem;

    line-height: 1.4;

}


/* =========================================================
   ADD PURCHASE
========================================================= */

.purchase-add-button {

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


.purchase-add-button:hover {

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


.purchase-add-button span {

    font-size: 0.9375rem;

    line-height: 1;

}


/* =========================================================
   FILTERS

   Same structure as Products
========================================================= */

.purchase-filters {

    display: flex;

    align-items: center;

    gap: 10px;

    padding: 15px 21px;

    background: var(--card-soft);

    border-bottom: 1px solid var(--border);

}


.purchase-search-wrapper {

    flex: 1;

    position: relative;

    min-width: 180px;

}


.purchase-search-wrapper input {

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


.purchase-search-wrapper input:focus {

    border-color: #d5a77d;

    box-shadow:
        0 0 0 3px
        rgba(196, 122, 58, 0.08);

}


.purchase-search-wrapper input::placeholder {

    color: #aaa19a;

}


.purchase-search-icon {

    position: absolute;

    left: 13px;

    top: 50%;

    transform: translateY(-50%);

    color: var(--muted);

    font-size: 0.9375rem;

    pointer-events: none;

}


.purchase-filters select {

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

.purchase-filter-button,
.purchase-clear-button {

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


.purchase-filter-button {

    border: none;

    background: var(--dark);

    color: white;

    transition:
        background-color 0.18s ease;

}


.purchase-filter-button:hover {

    background: var(--dark-soft);

}


.purchase-clear-button {

    border: 1px solid var(--border);

    background: white;

    color: var(--muted);

    transition:
        background-color 0.18s ease,
        color 0.18s ease;

}


.purchase-clear-button:hover {

    background: #faf7f3;

    color: var(--dark);

}


/* =========================================================
   ALERTS
========================================================= */

.purchase-alert {

    margin: 15px 21px 0;

    padding: 10px 13px;

    border-radius: 8px;

    font-size: 0.75rem;

    font-weight: 650;

}


.purchase-alert-success {

    background: #edf8f0;

    border: 1px solid #cfe5d5;

    color: #39704d;

}


.purchase-alert-error {

    background: #fff0ee;

    border: 1px solid #eccfcb;

    color: #9e4942;

}


/* =========================================================
   TABLE
========================================================= */

.purchase-table-wrapper {

    width: 100%;

    overflow-x: auto;

}


.purchases-table {

    width: 100%;

    min-width: 900px;

    border-collapse: collapse;

}


/* =========================================================
   TABLE HEADER

   Same as Products
========================================================= */

.purchases-table th {

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

.purchases-table td {

    padding: 13px 14px;

    color: #625951;

    border-bottom: 1px solid #f0ebe6;

    font-size: 0.8125rem;

    line-height: 1.4;

    vertical-align: middle;

}


.purchases-table tbody tr {

    background: white;

}


.purchases-table tbody tr:hover {

    background: #fdfaf7;

}


/* =========================================================
   PURCHASE NUMBER
========================================================= */

.purchase-number {

    color: var(--orange);

    font-size: 0.8125rem;

    line-height: 1.4;

    font-weight: 700;

    text-decoration: none;

    white-space: nowrap;

}


.purchase-number:hover {

    color: var(--orange-dark);

    text-decoration: underline;

}


/* =========================================================
   SUPPLIER
========================================================= */

.purchase-supplier {

    color: var(--dark);

    font-size: 0.8125rem;

    line-height: 1.4;

    font-weight: 700;

}


.purchase-muted {

    color: #a39b95;

    font-size: 0.8125rem;

}


/* =========================================================
   PURCHASE PRICE
========================================================= */

.purchase-price {

    color: var(--dark);

    font-size: 0.8125rem;

    font-weight: 800;

    white-space: nowrap;

}


/* =========================================================
   STATUS
========================================================= */

.purchase-status {

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


.purchase-status-icon {

    font-size: 0.6875rem;

    line-height: 1;

}


.purchase-status-draft {

    color: var(--muted);

    background: #f1eeeb;

}


.purchase-status-pending {

    color: #a66b22;

    background: #fff2dc;

}


.purchase-status-approved {

    color: var(--green);

    background: var(--green-light);

}


.purchase-status-rejected {

    color: #a94f48;

    background: #fff0ee;

}


.purchase-status-ordered {

    color: #4d7095;

    background: #eaf1f8;

}


.purchase-status-partial {

    color: #765998;

    background: #f2ebf8;

}


.purchase-status-received {

    color: var(--green);

    background: var(--green-light);

}


.purchase-status-cancelled {

    color: var(--muted);

    background: #f1eeeb;

}


/* =========================================================
   ACTIONS

   Same compact style as Products
========================================================= */

.purchase-actions-header {

    text-align: center !important;

}


.purchase-table-actions {

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 4px;

    white-space: nowrap;

}


.purchase-view-button,
.purchase-edit-button {

    height: 28px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 4px;

    padding: 0 7px;

    border: 1px solid #dfd0c3;

    border-radius: 7px;

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


.purchase-view-button {

    background: #faf7f3;

    color: #79583f;

}


.purchase-view-button:hover {

    background: #f5eee7;

    border-color: #cdb49f;

    color: #68482f;

}


.purchase-edit-button {

    background: white;

    color: #7d5b42;

}


.purchase-edit-button:hover {

    background: #faf5ef;

    border-color: #cdb49f;

    color: #68482f;

}


.purchase-view-button span,
.purchase-edit-button span {

    font-size: 0.75rem;

    line-height: 1;

}


/* =========================================================
   EMPTY STATE

   Same as Products
========================================================= */

.purchase-empty-state {

    padding: 65px 20px !important;

    text-align: center !important;

}


.purchase-empty-icon {

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


.purchase-empty-title {

    color: var(--dark);

    font-size: 0.875rem;

    font-weight: 700;

}


.purchase-empty-description {

    margin-top: 5px;

    color: var(--muted);

    font-size: 0.8125rem;

    line-height: 1.4;

}


/* =========================================================
   PAGINATION

   Same as Products
========================================================= */

.purchase-pagination-wrapper {

    padding: 15px 21px;

    border-top: 1px solid var(--border);

}


.purchase-pagination-wrapper nav {

    display: flex;

    justify-content: center;

}


.purchase-pagination-wrapper svg {

    width: 15px;

    height: 15px;

}


/* =========================================================
   RESPONSIVE

   Same as Products
========================================================= */

@media (max-width: 1200px) {

    .purchase-stats {

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

    }

}


@media (max-width: 700px) {

    .purchase-stats {

        grid-template-columns: 1fr;

    }


    .purchases-panel-header {

        align-items: flex-start;

        flex-direction: column;

    }


    .purchase-add-button {

        width: 100%;

    }


    .purchase-filters {

        align-items: stretch;

        flex-direction: column;

    }


    .purchase-search-wrapper {

        width: 100%;

    }


    .purchase-filters select,
    .purchase-filter-button,
    .purchase-clear-button {

        width: 100%;

    }


    .purchase-table-actions {

        justify-content: flex-start;

    }

}

</style>

@endpush