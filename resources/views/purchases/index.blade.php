@extends('layouts.app')

@section('title', 'BiteSync | Purchases')

@section('content')

@php

    use App\Models\Purchase;

    /*
    |--------------------------------------------------------------------------
    | CURRENT USER
    |--------------------------------------------------------------------------
    */

    $currentUser = auth()->user();

    $role = $currentUser?->role;

    $isAdmin = $role === 'CEO/Admin';

    $canManagePurchases = in_array(
        $role,
        ['CEO/Admin', 'Procurement'],
        true
    );


    /*
    |--------------------------------------------------------------------------
    | PURCHASE STATUS STYLES
    |--------------------------------------------------------------------------
    */

    $statusClasses = [

        'Draft' =>
            'purchase-status-draft',

        'Pending Approval' =>
            'purchase-status-pending',

        'Approved' =>
            'purchase-status-approved',

        'Rejected' =>
            'purchase-status-rejected',

        'Ordered' =>
            'purchase-status-ordered',

        'Partially Received' =>
            'purchase-status-partial',

        'Received' =>
            'purchase-status-received',

        'Cancelled' =>
            'purchase-status-cancelled',

    ];


    /*
    |--------------------------------------------------------------------------
    | PURCHASE STATUS ICONS
    |--------------------------------------------------------------------------
    */

    $statusIcons = [

        'Draft' =>
            '📝',

        'Pending Approval' =>
            '⏳',

        'Approved' =>
            '✓',

        'Rejected' =>
            '✕',

        'Ordered' =>
            '📦',

        'Partially Received' =>
            '◐',

        'Received' =>
            '✓',

        'Cancelled' =>
            '⊘',

    ];


    /*
    |--------------------------------------------------------------------------
    | STATUS FILTER OPTIONS
    |--------------------------------------------------------------------------
    */

    $statuses = [

        Purchase::STATUS_DRAFT,

        Purchase::STATUS_PENDING_APPROVAL,

        Purchase::STATUS_APPROVED,

        Purchase::STATUS_REJECTED,

        Purchase::STATUS_ORDERED,

        Purchase::STATUS_PARTIALLY_RECEIVED,

        Purchase::STATUS_RECEIVED,

        Purchase::STATUS_CANCELLED,

    ];

@endphp


<div class="purchases-page">


    <!-- =========================================================
         PURCHASES TOPBAR
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
                Manage supplier orders, approvals, receiving, and purchasing records.
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

    <section class="purchases-stats">


        <!-- TOTAL PURCHASES -->

        <div class="purchases-stat">

            <div class="purchases-stat-left">

                <div class="purchases-stat-label">
                    TOTAL PURCHASES
                </div>

                <div class="purchases-stat-note">
                    All purchase records
                </div>

            </div>


            <div class="purchases-stat-right">

                <div class="purchases-stat-icon">
                    ▦
                </div>

                <div class="purchases-stat-value">
                    {{ $stats['total'] }}
                </div>

            </div>

        </div>



        <!-- DRAFT PURCHASES -->

        <div class="purchases-stat">

            <div class="purchases-stat-left">

                <div class="purchases-stat-label">
                    DRAFT PURCHASES
                </div>

                <div class="purchases-stat-note">
                    Currently being prepared
                </div>

            </div>


            <div class="purchases-stat-right">

                <div class="purchases-stat-icon">
                    📝
                </div>

                <div class="purchases-stat-value">
                    {{ $stats['draft'] }}
                </div>

            </div>

        </div>



        <!-- PENDING PURCHASES -->

        <div class="purchases-stat">

            <div class="purchases-stat-left">

                <div class="purchases-stat-label">
                    PENDING PURCHASES
                </div>

                <div class="purchases-stat-note">
                    Awaiting approval
                </div>

            </div>


            <div class="purchases-stat-right">

                <div class="purchases-stat-icon">
                    ⏳
                </div>

                <div class="purchases-stat-value">
                    {{ $stats['pending'] }}
                </div>

            </div>

        </div>



        <!-- RECEIVED PURCHASES -->

        <div class="purchases-stat">

            <div class="purchases-stat-left">

                <div class="purchases-stat-label">
                    RECEIVED PURCHASES
                </div>

                <div class="purchases-stat-note">
                    Completed purchase records
                </div>

            </div>


            <div class="purchases-stat-right">

                <div class="purchases-stat-icon">
                    ✓
                </div>

                <div class="purchases-stat-value">
                    {{ $stats['received'] }}
                </div>

            </div>

        </div>

    </section>



    <!-- =========================================================
         PURCHASE RECORDS PANEL
    ========================================================== -->

    <div class="purchases-panel">


        <!-- =====================================================
             PANEL HEADER
        ====================================================== -->

        <div class="purchases-panel-header">

            <div>

                <div class="purchases-panel-title">
                    Purchase Records
                </div>

                <div class="purchases-panel-subtitle">
                    Search supplier orders and manage each purchase according to its current status.
                </div>

            </div>


            @if ($canManagePurchases)

                <a
                    href="{{ route('purchases.create') }}"
                    class="purchases-add-button"
                >

                    <span>
                        +
                    </span>

                    Add Purchase

                </a>

            @endif

        </div>



        <!-- =====================================================
             SEARCH / FILTERS
        ====================================================== -->

        <form
            method="GET"
            action="{{ route('purchases.index') }}"
            class="purchases-filters"
        >


            <!-- SEARCH -->

            <div class="purchases-search-wrapper">

                <span class="purchases-search-icon">
                    ⌕
                </span>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search', '') }}"
                    placeholder="Search purchase number or supplier..."
                >

            </div>



            <!-- STATUS -->

            <select name="status">

                <option value="">
                    All Status
                </option>

                @foreach ($statuses as $purchaseStatus)

                    <option
                        value="{{ $purchaseStatus }}"
                        {{ request('status') === $purchaseStatus ? 'selected' : '' }}
                    >
                        {{ $purchaseStatus }}
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
                class="purchases-filter-button"
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
                    class="purchases-clear-button"
                >
                    Clear
                </a>

            @endif

        </form>



        <!-- =====================================================
             PURCHASE TABLE
        ====================================================== -->

        <div class="purchases-table-wrapper">

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

                        <th class="purchases-actions-header">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($purchases as $purchase)

                        @php

                            $purchaseStatus =
                                $purchase->status ?? Purchase::STATUS_DRAFT;

                            $statusClass =
                                $statusClasses[$purchaseStatus]
                                ?? 'purchase-status-default';

                            $statusIcon =
                                $statusIcons[$purchaseStatus]
                                ?? '•';


                            /*
                            |--------------------------------------------------------------------------
                            | STATUS-AWARE ACTIONS
                            |--------------------------------------------------------------------------
                            */

                            $canEditPurchase =
                                $canManagePurchases &&
                                (
                                    $purchase->isDraft() ||
                                    $purchase->isRejected()
                                );


                            $canSubmitPurchase =
                                $canManagePurchases &&
                                (
                                    $purchase->isDraft() ||
                                    $purchase->isRejected()
                                );


                            $canApprovePurchase =
                                $isAdmin &&
                                $purchase->isPendingApproval();


                            $canRejectPurchase =
                                $isAdmin &&
                                $purchase->isPendingApproval();


                            $canOrderPurchase =
                                $canManagePurchases &&
                                $purchase->isApproved();


                            $canReceivePurchase =
                                $canManagePurchases &&
                                (
                                    $purchase->isOrdered() ||
                                    $purchase->isPartiallyReceived()
                                );


                            $canCancelPurchase =
                                $canManagePurchases &&
                                !$purchase->isCancelled() &&
                                !$purchase->isReceived() &&
                                !$purchase->items()
                                    ->where('received_quantity', '>', 0)
                                    ->exists();

                        @endphp


                        <tr>


                            <!-- =================================================
                                 PURCHASE NUMBER
                            ================================================== -->

                            <td>

                                <a
                                    href="{{ route('purchases.show', $purchase) }}"
                                    class="purchases-number"
                                >
                                    {{ $purchase->purchase_number }}
                                </a>

                            </td>



                            <!-- =================================================
                                 SUPPLIER
                            ================================================== -->

                            <td>

                                @if ($purchase->supplier)

                                    <div class="purchases-supplier">

                                        <div class="purchases-supplier-name">
                                            {{ $purchase->supplier->name }}
                                        </div>

                                        @if ($purchase->supplier->contact_person)

                                            <div class="purchases-supplier-contact">
                                                {{ $purchase->supplier->contact_person }}
                                            </div>

                                        @endif

                                    </div>

                                @else

                                    <span class="purchases-muted">
                                        —
                                    </span>

                                @endif

                            </td>



                            <!-- =================================================
                                 PURCHASE DATE
                            ================================================== -->

                            <td>

                                <span class="purchases-date">

                                    {{ $purchase->purchase_date
                                        ? \Carbon\Carbon::parse($purchase->purchase_date)->format('M d, Y')
                                        : '—'
                                    }}

                                </span>

                            </td>



                            <!-- =================================================
                                 EXPECTED DATE
                            ================================================== -->

                            <td>

                                <span class="purchases-date">

                                    {{ $purchase->expected_date
                                        ? \Carbon\Carbon::parse($purchase->expected_date)->format('M d, Y')
                                        : '—'
                                    }}

                                </span>

                            </td>



                            <!-- =================================================
                                 TOTAL
                            ================================================== -->

                            <td>

                                <span class="purchases-total">

                                    ₱{{ number_format((float) $purchase->total, 2) }}

                                </span>

                            </td>



                            <!-- =================================================
                                 STATUS
                            ================================================== -->

                            <td>

                                <span
                                    class="purchases-status {{ $statusClass }}"
                                >

                                    <span class="purchases-status-icon">
                                        {{ $statusIcon }}
                                    </span>

                                    {{ $purchaseStatus }}

                                </span>

                            </td>



                            <!-- =================================================
                                 CREATED BY
                            ================================================== -->

                            <td>

                                @if ($purchase->creator)

                                    <div class="purchases-creator">

                                        <div class="purchases-creator-name">
                                            {{ $purchase->creator->name }}
                                        </div>

                                    </div>

                                @else

                                    <span class="purchases-muted">
                                        System
                                    </span>

                                @endif

                            </td>



                            <!-- =================================================
                                 ACTIONS
                            ================================================== -->

                            <td>

                                <div class="purchases-table-actions">


                                    <!-- VIEW -->

                                    <a
                                        href="{{ route('purchases.show', $purchase) }}"
                                        class="purchase-action purchase-action-view"
                                        title="View purchase details"
                                        aria-label="View purchase details"
                                    >
                                        <span>◉</span>
                                    </a>



                                    <!-- EDIT -->

                                    @if ($canEditPurchase)

                                        <a
                                            href="{{ route('purchases.edit', $purchase) }}"
                                            class="purchase-action purchase-action-edit"
                                            title="Edit purchase"
                                            aria-label="Edit purchase"
                                        >
                                            <span>✎</span>
                                        </a>

                                    @endif



                                    <!-- SUBMIT -->

                                    @if ($canSubmitPurchase)

                                        <form
                                            method="POST"
                                            action="{{ route('purchases.submit', $purchase) }}"
                                            class="purchase-action-form"
                                            onsubmit="return confirm('Submit {{ $purchase->purchase_number }} for approval?');"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="purchase-action purchase-action-submit"
                                                title="Submit for approval"
                                                aria-label="Submit for approval"
                                            >
                                                <span>↑</span>
                                            </button>

                                        </form>

                                    @endif



                                    <!-- APPROVE -->

                                    @if ($canApprovePurchase)

                                        <form
                                            method="POST"
                                            action="{{ route('purchases.approve', $purchase) }}"
                                            class="purchase-action-form"
                                            onsubmit="return confirm('Approve {{ $purchase->purchase_number }}?');"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="purchase-action purchase-action-approve"
                                                title="Approve purchase"
                                                aria-label="Approve purchase"
                                            >
                                                <span>✓</span>
                                            </button>

                                        </form>

                                    @endif



                                    <!-- REJECT -->

                                    @if ($canRejectPurchase)

                                        <form
                                            method="POST"
                                            action="{{ route('purchases.reject', $purchase) }}"
                                            class="purchase-action-form"
                                            onsubmit="return confirm('Reject {{ $purchase->purchase_number }}?');"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="purchase-action purchase-action-reject"
                                                title="Reject purchase"
                                                aria-label="Reject purchase"
                                            >
                                                <span>✕</span>
                                            </button>

                                        </form>

                                    @endif



                                    <!-- ORDER -->

                                    @if ($canOrderPurchase)

                                        <form
                                            method="POST"
                                            action="{{ route('purchases.order', $purchase) }}"
                                            class="purchase-action-form"
                                            onsubmit="return confirm('Mark {{ $purchase->purchase_number }} as ordered?');"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="purchase-action purchase-action-order"
                                                title="Mark purchase as ordered"
                                                aria-label="Mark purchase as ordered"
                                            >
                                                <span>📦</span>
                                            </button>

                                        </form>

                                    @endif



                                    <!-- RECEIVE -->

                                    @if ($canReceivePurchase)

                                        <a
                                            href="{{ route('purchases.show', $purchase) }}"
                                            class="purchase-action purchase-action-receive"
                                            title="Receive purchase stock"
                                            aria-label="Receive purchase stock"
                                        >
                                            <span>↓</span>
                                        </a>

                                    @endif



                                    <!-- CANCEL -->

                                    @if ($canCancelPurchase)

                                        <form
                                            method="POST"
                                            action="{{ route('purchases.cancel', $purchase) }}"
                                            class="purchase-action-form"
                                            onsubmit="return confirm('Cancel {{ $purchase->purchase_number }}? This action cannot be undone.');"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="purchase-action purchase-action-cancel"
                                                title="Cancel purchase"
                                                aria-label="Cancel purchase"
                                            >
                                                <span>⊘</span>
                                            </button>

                                        </form>

                                    @endif


                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="purchases-empty-state"
                            >

                                <div class="purchases-empty-icon">
                                    ▦
                                </div>


                                <div class="purchases-empty-title">
                                    No purchase records found
                                </div>


                                <div class="purchases-empty-description">

                                    @if (
                                        request('search') ||
                                        request('status') ||
                                        request('supplier_id')
                                    )

                                        Try changing your search or filter.

                                    @else

                                        Your purchase records will appear here.

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

        @if ($purchases->hasPages())

            <div class="purchases-pagination-wrapper">

                <div class="purchases-pagination">

                    <div class="purchases-pagination-info">

                        Showing
                        <strong>{{ $purchases->firstItem() }}</strong>
                        to
                        <strong>{{ $purchases->lastItem() }}</strong>
                        of
                        <strong>{{ $purchases->total() }}</strong>
                        purchases

                    </div>

                    <div class="purchases-pagination-links">

                        {{ $purchases->links() }}

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
   PURCHASES PAGE
========================================================= */

.purchases-page {
    width: 100%;
}



/* =========================================================
   PAGE TITLE
========================================================= */

.purchases-page .page-title h1 {

    font-size:
        clamp(1.6rem, 2vw, 1.9rem);

    line-height: 1.15;

    letter-spacing: -0.04rem;
}



/* =========================================================
   SUMMARY CARDS
========================================================= */

.purchases-stats {

    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 17px;

    margin-bottom: 17px;
}


.purchases-stat {

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


.purchases-stat::before {

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


.purchases-stat-left {

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


.purchases-stat-label {

    color: var(--muted);

    font-size: 0.6875rem;

    line-height: 1.3;

    font-weight: 800;

    letter-spacing: 0.045rem;

    white-space: nowrap;
}


.purchases-stat-note {

    margin-top: 58px;

    color: #9d958f;

    font-size: 0.6875rem;

    line-height: 1.4;

    max-width: 155px;
}


.purchases-stat-right {

    min-width: 82px;

    display: flex;

    flex-direction: column;

    align-items: flex-end;

    justify-content: flex-start;

    padding-top: 3px;

    flex-shrink: 0;
}


.purchases-stat-icon {

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


.purchases-stat-value {

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
   PURCHASE PANEL
========================================================= */

.purchases-panel {

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

.purchases-panel-header {

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


.purchases-panel-title {

    color: var(--dark);

    font-size: 0.875rem;

    line-height: 1.3;

    font-weight: 700;
}


.purchases-panel-subtitle {

    margin-top: 3px;

    color: var(--muted);

    font-size: 0.6875rem;

    line-height: 1.4;
}



/* =========================================================
   ADD PURCHASE
========================================================= */

.purchases-add-button {

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


.purchases-add-button:hover {

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


.purchases-add-button span {

    font-size: 0.8125rem;

    line-height: 1;
}



/* =========================================================
   FILTERS
========================================================= */

.purchases-filters {

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


.purchases-search-wrapper {

    flex: 1;

    position: relative;

    min-width: 180px;
}


.purchases-search-wrapper input {

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


.purchases-search-wrapper input:focus {

    border-color: #d5a77d;

    box-shadow:
        0 0 0 3px
        rgba(196, 122, 58, 0.07);
}


.purchases-search-wrapper input::placeholder {

    color: #aaa19a;
}


.purchases-search-icon {

    position: absolute;

    left: 11px;

    top: 50%;

    transform:
        translateY(-50%);

    color: var(--muted);

    font-size: 0.875rem;

    pointer-events: none;
}


.purchases-filters select {

    height: 35px;

    min-width: 145px;

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

.purchases-filter-button,
.purchases-clear-button {

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


.purchases-filter-button {

    border: none;

    background: var(--dark);

    color: white;

    transition:
        background-color 0.18s ease;
}


.purchases-filter-button:hover {

    background: var(--dark-soft);
}


.purchases-clear-button {

    border:
        1px solid var(--border);

    background: white;

    color: var(--muted);

    transition:
        background-color 0.18s ease,
        color 0.18s ease;
}


.purchases-clear-button:hover {

    background: #faf7f3;

    color: var(--dark);
}



/* =========================================================
   TABLE
========================================================= */

.purchases-table-wrapper {

    width: 100%;

    overflow-x: auto;
}


.purchases-table {

    width: 100%;

    min-width: 1120px;

    border-collapse: collapse;
}


.purchases-table th {

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


.purchases-table td {

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


.purchases-table tbody tr {

    background: white;
}


.purchases-table tbody tr:hover {

    background: #fdfaf7;
}



/* =========================================================
   PURCHASE NUMBER
========================================================= */

.purchases-number {

    color: var(--dark);

    font-size: 0.75rem;

    font-weight: 800;

    text-decoration: none;

    white-space: nowrap;
}


.purchases-number:hover {

    color: var(--orange);
}



/* =========================================================
   SUPPLIER
========================================================= */

.purchases-supplier {

    min-width: 130px;
}


.purchases-supplier-name {

    color: var(--dark);

    font-size: 0.75rem;

    line-height: 1.4;

    font-weight: 700;
}


.purchases-supplier-contact {

    margin-top: 2px;

    color: var(--muted);

    font-size: 0.625rem;

    line-height: 1.35;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;

    max-width: 150px;
}



/* =========================================================
   DATES
========================================================= */

.purchases-date {

    color: #625951;

    font-size: 0.6875rem;

    white-space: nowrap;
}



/* =========================================================
   TOTAL
========================================================= */

.purchases-total {

    color: var(--dark);

    font-size: 0.75rem;

    font-weight: 800;

    white-space: nowrap;
}



/* =========================================================
   CREATED BY
========================================================= */

.purchases-creator-name {

    color: #625951;

    font-size: 0.6875rem;

    white-space: nowrap;
}


.purchases-muted {

    color: #a39b95;

    font-size: 0.75rem;
}



/* =========================================================
   STATUS
========================================================= */

.purchases-status {

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

    letter-spacing: 0.01rem;
}


.purchases-status-icon {

    font-size: 0.625rem;

    line-height: 1;
}



/* =========================================================
   STATUS COLORS
========================================================= */

.purchase-status-draft {

    color: #806d5c;

    background: #f4eee8;
}


.purchase-status-pending {

    color: #956d2f;

    background: #fff4df;
}


.purchase-status-approved {

    color: #39704d;

    background: #edf7f0;
}


.purchase-status-rejected {

    color: #9a4d43;

    background: #faeeee;
}


.purchase-status-ordered {

    color: #536f8a;

    background: #edf3f8;
}


.purchase-status-partial {

    color: #8a7042;

    background: #f7f1e4;
}


.purchase-status-received {

    color: var(--green);

    background: var(--green-light);
}


.purchase-status-cancelled {

    color: #817a75;

    background: #f1eeeb;
}


.purchase-status-default {

    color: var(--muted);

    background: #f1eeeb;
}



/* =========================================================
   ACTIONS
   Minimal icon-only actions
========================================================= */

.purchases-actions-header {

    text-align: center !important;
}


.purchases-table-actions {

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 9px;

    min-width: 150px;
}


.purchase-action-form {

    display: inline-flex;

    margin: 0;

    padding: 0;
}


.purchase-action {

    width: 22px;

    height: 22px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    padding: 0;

    margin: 0;

    border: none;

    border-radius: 5px;

    background: transparent;

    font-family: inherit;

    font-size: 0.75rem;

    line-height: 1;

    font-weight: 700;

    text-decoration: none;

    cursor: pointer;

    transition:
        color 0.15s ease,
        background-color 0.15s ease,
        transform 0.15s ease;
}


.purchase-action span {

    display: block;

    font-size: 0.75rem;

    line-height: 1;
}


.purchase-action:hover {

    transform: translateY(-1px);

    text-decoration: none;
}



/* =========================================================
   VIEW
========================================================= */

.purchase-action-view {

    color: var(--green);
}


.purchase-action-view:hover {

    color: #2e6c46;

    background: #edf8f1;
}



/* =========================================================
   EDIT
========================================================= */

.purchase-action-edit {

    color: #806047;
}


.purchase-action-edit:hover {

    color: #68482f;

    background: #faf5ef;
}



/* =========================================================
   SUBMIT
========================================================= */

.purchase-action-submit {

    color: #926c2e;
}


.purchase-action-submit:hover {

    color: #79551e;

    background: #fff3dc;
}



/* =========================================================
   APPROVE
========================================================= */

.purchase-action-approve {

    color: #39704d;
}


.purchase-action-approve:hover {

    color: #2e633f;

    background: #e8f6ed;
}



/* =========================================================
   REJECT
========================================================= */

.purchase-action-reject {

    color: #9a4d43;
}


.purchase-action-reject:hover {

    color: #843e36;

    background: #fbeceb;
}



/* =========================================================
   ORDER
========================================================= */

.purchase-action-order {

    color: #536f8a;
}


.purchase-action-order:hover {

    color: #405c75;

    background: #eaf1f7;
}



/* =========================================================
   RECEIVE
========================================================= */

.purchase-action-receive {

    color: #8a7042;
}


.purchase-action-receive:hover {

    color: #735b31;

    background: #f5eedc;
}



/* =========================================================
   CANCEL
========================================================= */

.purchase-action-cancel {

    color: #817a75;
}


.purchase-action-cancel:hover {

    color: #625b56;

    background: #f3f0ed;
}



/* =========================================================
   EMPTY STATE
========================================================= */

.purchases-empty-state {

    padding:
        55px
        20px !important;

    text-align: center !important;
}


.purchases-empty-icon {

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


.purchases-empty-title {

    color: var(--dark);

    font-size: 0.8125rem;

    font-weight: 700;
}


.purchases-empty-description {

    margin-top: 4px;

    color: var(--muted);

    font-size: 0.6875rem;

    line-height: 1.4;
}



/* =========================================================
   PAGINATION
========================================================= */

.purchases-pagination-wrapper {

    padding:
        13px
        18px;

    border-top:
        1px solid var(--border);

    background: white;
}


.purchases-pagination {

    width: 100%;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;
}


.purchases-pagination-info {

    color: var(--muted);

    font-size: 0.6875rem;

    line-height: 1.4;

    white-space: nowrap;
}


.purchases-pagination-info strong {

    color: var(--dark);

    font-weight: 800;
}


.purchases-pagination-links {

    display: flex;

    align-items: center;

    justify-content: flex-end;

    flex: 1;
}


.purchases-pagination-links nav {

    display: flex;

    align-items: center;

    justify-content: flex-end;

    width: 100%;
}


.purchases-pagination-links nav > div {

    display: flex;

    align-items: center;

    justify-content: flex-end;

    gap: 4px;

    width: 100%;
}


/*
|--------------------------------------------------------------------------
| Laravel pagination text/info section
|--------------------------------------------------------------------------
*/

.purchases-pagination-links nav p {

    margin: 0;

    color: var(--muted);

    font-size: 0.6875rem;
}


.purchases-pagination-links nav p span {

    color: var(--dark);

    font-weight: 700;
}


/*
|--------------------------------------------------------------------------
| Pagination links and buttons
|--------------------------------------------------------------------------
*/

.purchases-pagination-links nav a,
.purchases-pagination-links nav button,
.purchases-pagination-links nav span[aria-current="page"],
.purchases-pagination-links nav span[aria-disabled="true"] {

    min-width: 30px;

    height: 30px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    padding:
        0
        8px;

    border:
        1px solid var(--border);

    border-radius: 7px;

    background: white;

    color: var(--muted);

    font-family: inherit;

    font-size: 0.6875rem;

    line-height: 1;

    font-weight: 700;

    text-decoration: none;

    box-sizing: border-box;
}


/*
|--------------------------------------------------------------------------
| Pagination links hover
|--------------------------------------------------------------------------
*/

.purchases-pagination-links nav a:hover {

    background: #faf5ef;

    border-color: #d9b18d;

    color: var(--orange);

    text-decoration: none;
}


/*
|--------------------------------------------------------------------------
| Current page
|--------------------------------------------------------------------------
*/

.purchases-pagination-links nav span[aria-current="page"] {

    background: var(--orange);

    border-color: var(--orange);

    color: white;

    box-shadow:
        0 2px 7px
        rgba(168, 95, 40, 0.12);
}


/*
|--------------------------------------------------------------------------
| Current page inner span
|--------------------------------------------------------------------------
*/

.purchases-pagination-links nav span[aria-current="page"] > span {

    color: white;
}


/*
|--------------------------------------------------------------------------
| Disabled previous / next
|--------------------------------------------------------------------------
*/

.purchases-pagination-links nav span[aria-disabled="true"] {

    background: #faf9f7;

    color: #b4ada7;

    border-color: #eee9e5;

    cursor: not-allowed;

    opacity: 0.75;
}


/*
|--------------------------------------------------------------------------
| Pagination SVG arrows
|--------------------------------------------------------------------------
*/

.purchases-pagination-links nav svg {

    width: 13px;

    height: 13px;

    display: block;
}


/*
|--------------------------------------------------------------------------
| Remove unnecessary default Tailwind spacing
|--------------------------------------------------------------------------
*/

.purchases-pagination-links nav .relative {

    position: relative;
}


.purchases-pagination-links nav .inline-flex {

    display: inline-flex;
}


.purchases-pagination-links nav .items-center {

    align-items: center;
}



/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1200px) {

    .purchases-stats {

        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

}


@media (max-width: 700px) {

    .purchases-stats {

        grid-template-columns: 1fr;
    }


    .purchases-stat {

        min-height: 115px;
    }


    .purchases-stat-left {

        padding-top: 1px;
    }


    .purchases-stat-right {

        min-width: 72px;

        padding-top: 3px;
    }


    .purchases-stat-value {

        font-size: 1.45rem;
    }


    .purchases-stat-note {

        margin-top: 55px;
    }


    .purchases-panel-header {

        align-items: flex-start;

        flex-direction: column;
    }


    .purchases-add-button {

        width: 100%;
    }


    .purchases-filters {

        align-items: stretch;

        flex-direction: column;
    }


    .purchases-search-wrapper {

        width: 100%;
    }


    .purchases-filters select,
    .purchases-filter-button,
    .purchases-clear-button {

        width: 100%;
    }


    .purchases-table-actions {

        justify-content: flex-start;

        gap: 8px;
    }


    .purchases-pagination {

        align-items: flex-start;

        flex-direction: column;
    }


    .purchases-pagination-info {

        width: 100%;

        text-align: left;
    }


    .purchases-pagination-links {

        width: 100%;

        justify-content: flex-start;
    }


    .purchases-pagination-links nav {

        justify-content: flex-start;

        overflow-x: auto;

        padding-bottom: 2px;
    }


    .purchases-pagination-links nav > div {

        justify-content: flex-start;

        width: auto;
    }

}

</style>

@endpush