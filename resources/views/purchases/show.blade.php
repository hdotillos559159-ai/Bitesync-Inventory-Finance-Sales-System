```blade
@extends('layouts.app')

@section('title', 'BiteSync | Purchase Details')

@section('content')

<div class="purchase-show-page">

    {{-- =========================================================
         TOPBAR
    ========================================================== --}}

    <div class="topbar">

        <div class="page-title">

            <small>
                Purchasing
            </small>

            <h1>
                Purchase Details
            </h1>

            <p>
                Review the purchase information, items, and current status.
            </p>

        </div>

        <div class="date-box">

            <span class="date-icon">
                ◷
            </span>

            {{ now()->format('F d, Y') }}

        </div>

    </div>


    {{-- =========================================================
         BREADCRUMB
    ========================================================== --}}

    <div class="purchase-breadcrumb">

        <a href="{{ route('purchases.index') }}">
            Purchases
        </a>

        <span>/</span>

        <span>
            {{ $purchase->purchase_number }}
        </span>

    </div>


    {{-- =========================================================
         PURCHASE HEADER
    ========================================================== --}}

    <div class="purchase-header-panel">

        <div class="purchase-header-left">

            <div class="purchase-header-icon">
                🛒
            </div>

            <div>

                <div class="purchase-number">
                    {{ $purchase->purchase_number }}
                </div>

                <div class="purchase-header-meta">

                    <span>
                        {{ $purchase->purchase_date?->format('F d, Y') ?? 'No date' }}
                    </span>

                    <span class="meta-separator">
                        •
                    </span>

                    <span>
                        {{ $purchase->supplier->name ?? 'No supplier' }}
                    </span>

                </div>

            </div>

        </div>


        <div class="purchase-header-right">

            @php
                $statusClass = match ($purchase->status) {
                    'Draft' => 'status-draft',
                    'Pending Approval' => 'status-pending',
                    'Approved' => 'status-approved',
                    'Rejected' => 'status-rejected',
                    'Ordered' => 'status-ordered',
                    'Partially Received' => 'status-partial',
                    'Received' => 'status-received',
                    'Cancelled' => 'status-cancelled',
                    default => 'status-default',
                };
            @endphp

            <span class="purchase-status {{ $statusClass }}">
                {{ $purchase->status }}
            </span>

        </div>

    </div>


    {{-- =========================================================
         STATUS INFORMATION
    ========================================================== --}}

    @if ($purchase->status === 'Pending Approval')

        <div class="status-notice status-notice-pending">

            <div class="status-notice-icon">
                !
            </div>

            <div>

                <strong>
                    Awaiting Approval
                </strong>

                <p>
                    This purchase has been submitted and is waiting for approval.
                </p>

            </div>

        </div>

    @elseif ($purchase->status === 'Rejected')

        <div class="status-notice status-notice-rejected">

            <div class="status-notice-icon">
                !
            </div>

            <div>

                <strong>
                    Purchase Rejected
                </strong>

                <p>
                    This purchase was rejected and can be edited and resubmitted.
                </p>

            </div>

        </div>

    @elseif ($purchase->status === 'Approved')

        <div class="status-notice status-notice-approved">

            <div class="status-notice-icon">
                ✓
            </div>

            <div>

                <strong>
                    Purchase Approved
                </strong>

                <p>
                    This purchase has been approved and is ready to be ordered.
                </p>

            </div>

        </div>

    @elseif ($purchase->status === 'Ordered')

        <div class="status-notice status-notice-ordered">

            <div class="status-notice-icon">
                ✓
            </div>

            <div>

                <strong>
                    Purchase Ordered
                </strong>

                <p>
                    This purchase has been placed with the supplier.
                </p>

            </div>

        </div>

    @elseif ($purchase->status === 'Received')

        <div class="status-notice status-notice-received">

            <div class="status-notice-icon">
                ✓
            </div>

            <div>

                <strong>
                    Purchase Received
                </strong>

                <p>
                    All items from this purchase have been received.
                </p>

            </div>

        </div>

    @elseif ($purchase->status === 'Cancelled')

        <div class="status-notice status-notice-cancelled">

            <div class="status-notice-icon">
                ×
            </div>

            <div>

                <strong>
                    Purchase Cancelled
                </strong>

                <p>
                    This purchase is no longer active.
                </p>

            </div>

        </div>

    @endif


    {{-- =========================================================
         PURCHASE INFORMATION
    ========================================================== --}}

    <div class="information-grid">


        {{-- =====================================================
             SUPPLIER INFORMATION
        ====================================================== --}}

        <div class="form-panel">

            <div class="form-panel-header">

                <div class="form-panel-heading">

                    <div class="form-panel-icon">
                        🏢
                    </div>

                    <div>

                        <div class="form-panel-title">
                            Purchase Information
                        </div>

                        <div class="form-panel-subtitle">
                            Supplier and purchase schedule.
                        </div>

                    </div>

                </div>

            </div>


            <div class="detail-body">

                <div class="detail-grid">

                    <div class="detail-item">

                        <span>
                            Supplier
                        </span>

                        <strong>
                            {{ $purchase->supplier->name ?? '—' }}
                        </strong>

                    </div>


                    <div class="detail-item">

                        <span>
                            Purchase Date
                        </span>

                        <strong>
                            {{ $purchase->purchase_date?->format('F d, Y') ?? '—' }}
                        </strong>

                    </div>


                    <div class="detail-item">

                        <span>
                            Expected Delivery
                        </span>

                        <strong>
                            {{ $purchase->expected_date?->format('F d, Y') ?? 'Not specified' }}
                        </strong>

                    </div>


                    <div class="detail-item">

                        <span>
                            Created By
                        </span>

                        <strong>
                            {{ $purchase->creator->name ?? '—' }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             PURCHASE SUMMARY
        ====================================================== --}}

        <div class="form-panel">

            <div class="form-panel-header">

                <div class="form-panel-heading">

                    <div class="form-panel-icon">
                        ₱
                    </div>

                    <div>

                        <div class="form-panel-title">
                            Purchase Summary
                        </div>

                        <div class="form-panel-subtitle">
                            Financial summary of this purchase.
                        </div>

                    </div>

                </div>

            </div>


            <div class="summary-body">

                <div class="summary-row">

                    <span>
                        Subtotal
                    </span>

                    <strong>
                        ₱{{ number_format((float) $purchase->subtotal, 2) }}
                    </strong>

                </div>


                <div class="summary-row">

                    <span>
                        Tax
                    </span>

                    <strong>
                        ₱{{ number_format((float) $purchase->tax, 2) }}
                    </strong>

                </div>


                <div class="summary-divider"></div>


                <div class="summary-total-row">

                    <span>
                        Total
                    </span>

                    <strong>
                        ₱{{ number_format((float) $purchase->total, 2) }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         PURCHASE ITEMS
    ========================================================== --}}

    <div class="form-panel full-width-panel">

        <div class="form-panel-header">

            <div class="form-panel-heading">

                <div class="form-panel-icon">
                    📦
                </div>

                <div>

                    <div class="form-panel-title">
                        Purchase Items
                    </div>

                    <div class="form-panel-subtitle">
                        Items included in this purchase order.
                    </div>

                </div>

            </div>

            <div class="item-count">

                {{ $purchase->items->count() }}

                {{ $purchase->items->count() === 1 ? 'Item' : 'Items' }}

            </div>

        </div>


        <div class="items-table-container">

            <div class="items-table-scroll">

                <table class="items-table">

                    <thead>

                        <tr>

                            <th>
                                Inventory Item
                            </th>

                            <th>
                                Quantity
                            </th>

                            <th>
                                Unit
                            </th>

                            <th>
                                Unit Cost
                            </th>

                            <th>
                                Subtotal
                            </th>

                            <th>
                                Received
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($purchase->items as $item)

                            <tr>

                                <td>

                                    <div class="item-name-cell">

                                        <strong>
                                            {{ $item->inventoryItem->name ?? 'Unknown Item' }}
                                        </strong>

                                        @if ($item->inventoryItem?->category)

                                            <small>
                                                {{ $item->inventoryItem->category->name }}
                                            </small>

                                        @endif

                                    </div>

                                </td>


                                <td>

                                    {{ number_format((float) $item->quantity, 2) }}

                                </td>


                                <td>

                                    {{ $item->inventoryItem->unit->abbreviation ?? $item->inventoryItem->unit->name ?? '—' }}

                                </td>


                                <td>

                                    ₱{{ number_format((float) $item->unit_cost, 2) }}

                                </td>


                                <td>

                                    <strong>
                                        ₱{{ number_format((float) $item->subtotal, 2) }}
                                    </strong>

                                </td>


                                <td>

                                    @php
                                        $received = (float) $item->received_quantity;
                                        $quantity = (float) $item->quantity;
                                    @endphp

                                    @if ($received >= $quantity)

                                        <span class="received-badge received-complete">
                                            {{ number_format($received, 2) }}
                                        </span>

                                    @elseif ($received > 0)

                                        <span class="received-badge received-partial">
                                            {{ number_format($received, 2) }}
                                        </span>

                                    @else

                                        <span class="received-badge received-none">
                                            0.00
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="empty-items"
                                >

                                    No purchase items found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =========================================================
         ADDITIONAL INFORMATION
    ========================================================== --}}

    @if ($purchase->notes)

        <div class="form-panel full-width-panel">

            <div class="form-panel-header">

                <div class="form-panel-heading">

                    <div class="form-panel-icon">
                        📝
                    </div>

                    <div>

                        <div class="form-panel-title">
                            Additional Information
                        </div>

                        <div class="form-panel-subtitle">
                            Notes and additional purchase details.
                        </div>

                    </div>

                </div>

            </div>


            <div class="notes-body">

                {{ $purchase->notes }}

            </div>

        </div>

    @endif


    {{-- =========================================================
         APPROVAL INFORMATION
    ========================================================== --}}

    @if ($purchase->approved_by || $purchase->approved_at)

        <div class="form-panel full-width-panel">

            <div class="form-panel-header">

                <div class="form-panel-heading">

                    <div class="form-panel-icon">
                        ✓
                    </div>

                    <div>

                        <div class="form-panel-title">
                            Approval Information
                        </div>

                        <div class="form-panel-subtitle">
                            Approval details for this purchase.
                        </div>

                    </div>

                </div>

            </div>


            <div class="detail-body">

                <div class="detail-grid">

                    <div class="detail-item">

                        <span>
                            Approved By
                        </span>

                        <strong>
                            {{ $purchase->approver->name ?? '—' }}
                        </strong>

                    </div>


                    <div class="detail-item">

                        <span>
                            Approved At
                        </span>

                        <strong>
                            {{ $purchase->approved_at?->format('F d, Y h:i A') ?? '—' }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
         ACTIONS
    ========================================================== --}}

    <div class="purchase-actions">


        <a
            href="{{ route('purchases.index') }}"
            class="secondary-button"
        >
            ← Back to Purchases
        </a>


        <div class="purchase-actions-right">


            {{-- =================================================
                 EDIT
            ================================================== --}}

            @if (
                in_array(auth()->user()->role, ['CEO', 'Admin', 'Procurement'])
                && in_array($purchase->status, ['Draft', 'Rejected'])
            )

                <a
                    href="{{ route('purchases.edit', $purchase) }}"
                    class="secondary-button"
                >
                    Edit Purchase
                </a>

            @endif


            {{-- =================================================
                 SUBMIT
            ================================================== --}}

            @if (
                in_array(auth()->user()->role, ['CEO', 'Admin', 'Procurement'])
                && in_array($purchase->status, ['Draft', 'Rejected'])
            )

                <form
                    method="POST"
                    action="{{ route('purchases.submit', $purchase) }}"
                    class="inline-form"
                >

                    @csrf

                    <button
                        type="submit"
                        class="primary-button"
                        onclick="return confirm('Submit this purchase for approval?')"
                    >
                        Submit for Approval
                    </button>

                </form>

            @endif


            {{-- =================================================
                 APPROVE
            ================================================== --}}

            @if (
                in_array(auth()->user()->role, ['CEO', 'Admin'])
                && $purchase->status === 'Pending Approval'
            )

                <form
                    method="POST"
                    action="{{ route('purchases.approve', $purchase) }}"
                    class="inline-form"
                >

                    @csrf

                    <button
                        type="submit"
                        class="primary-button"
                        onclick="return confirm('Approve this purchase?')"
                    >
                        ✓ Approve
                    </button>

                </form>

            @endif


            {{-- =================================================
                 REJECT
            ================================================== --}}

            @if (
                in_array(auth()->user()->role, ['CEO', 'Admin'])
                && $purchase->status === 'Pending Approval'
            )

                <form
                    method="POST"
                    action="{{ route('purchases.reject', $purchase) }}"
                    class="inline-form"
                >

                    @csrf

                    <button
                        type="submit"
                        class="danger-button"
                        onclick="return confirm('Reject this purchase?')"
                    >
                        Reject
                    </button>

                </form>

            @endif


            {{-- =================================================
                 ORDER
            ================================================== --}}

            @if (
                in_array(auth()->user()->role, ['CEO', 'Admin', 'Procurement'])
                && $purchase->status === 'Approved'
            )

                <form
                    method="POST"
                    action="{{ route('purchases.order', $purchase) }}"
                    class="inline-form"
                >

                    @csrf

                    <button
                        type="submit"
                        class="primary-button"
                        onclick="return confirm('Mark this purchase as ordered?')"
                    >
                        Mark as Ordered
                    </button>

                </form>

            @endif


            {{-- =================================================
                 CANCEL
            ================================================== --}}

            @if (
                in_array(auth()->user()->role, ['CEO', 'Admin', 'Procurement'])
                && !in_array($purchase->status, ['Received', 'Cancelled'])
            )

                <form
                    method="POST"
                    action="{{ route('purchases.cancel', $purchase) }}"
                    class="inline-form"
                >

                    @csrf

                    <button
                        type="submit"
                        class="danger-button"
                        onclick="return confirm('Cancel this purchase? This action cannot be easily undone.')"
                    >
                        Cancel Purchase
                    </button>

                </form>

            @endif

        </div>

    </div>

</div>


@push('styles')

<style>

/* =========================================================
   PAGE
========================================================= */

.purchase-show-page {
    width: 100%;
}


/* =========================================================
   TOPBAR
========================================================= */

.purchase-show-page .topbar {
    margin-bottom: 12px;
}


/* =========================================================
   BREADCRUMB
========================================================= */

.purchase-breadcrumb {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 16px;
    color: var(--muted);
    font-size: 0.75rem;
    font-weight: 600;
}

.purchase-breadcrumb a {
    color: var(--orange);
    text-decoration: none;
}

.purchase-breadcrumb a:hover {
    text-decoration: underline;
}


/* =========================================================
   PURCHASE HEADER
========================================================= */

.purchase-header-panel {
    min-height: 78px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 18px;
    padding: 14px 18px;
    background: white;
    border: 1px solid var(--border);
    border-radius: 17px;
    box-shadow:
        0 5px 18px rgba(43, 31, 23, 0.035);
}

.purchase-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.purchase-header-icon {
    width: 40px;
    height: 40px;
    flex: 0 0 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: var(--orange-light);
    color: var(--orange);
    font-size: 1rem;
}

.purchase-number {
    color: var(--dark);
    font-size: 1.05rem;
    font-weight: 800;
}

.purchase-header-meta {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-top: 3px;
    color: var(--muted);
    font-size: 0.72rem;
}

.meta-separator {
    opacity: 0.5;
}

.purchase-header-right {
    display: flex;
    align-items: center;
}


/* =========================================================
   STATUS
========================================================= */

.purchase-status {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 29px;
    padding: 5px 10px;
    border-radius: 8px;
    font-size: 0.6875rem;
    font-weight: 800;
    white-space: nowrap;
}

.status-draft {
    color: #6e6259;
    background: #f1eeeb;
}

.status-pending {
    color: #986b29;
    background: #fff5df;
}

.status-approved {
    color: #48795c;
    background: #edf8f0;
}

.status-rejected {
    color: #a94f48;
    background: #fff0ee;
}

.status-ordered {
    color: #596d91;
    background: #eef3fb;
}

.status-partial {
    color: #896c34;
    background: #fff6df;
}

.status-received {
    color: #477b59;
    background: #eaf7ee;
}

.status-cancelled {
    color: #8d625e;
    background: #f6eded;
}

.status-default {
    color: var(--muted);
    background: #f3f0ed;
}


/* =========================================================
   STATUS NOTICE
========================================================= */

.status-notice {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    margin-bottom: 18px;
    padding: 12px 15px;
    border-radius: 11px;
    border: 1px solid;
}

.status-notice-icon {
    width: 27px;
    height: 27px;
    flex: 0 0 27px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 0.75rem;
    font-weight: 800;
}

.status-notice strong {
    display: block;
    font-size: 0.75rem;
    font-weight: 800;
}

.status-notice p {
    margin: 3px 0 0;
    font-size: 0.7rem;
    line-height: 1.4;
}

.status-notice-pending {
    color: #7e5e28;
    background: #fffaf0;
    border-color: #ead9b3;
}

.status-notice-pending .status-notice-icon {
    background: #f3dfad;
}

.status-notice-rejected {
    color: #91453e;
    background: #fff4f2;
    border-color: #eccfcb;
}

.status-notice-rejected .status-notice-icon {
    background: #f1d2ce;
}

.status-notice-approved,
.status-notice-received,
.status-notice-ordered {
    color: #477256;
    background: #f2faf4;
    border-color: #cee5d4;
}

.status-notice-approved .status-notice-icon,
.status-notice-received .status-notice-icon,
.status-notice-ordered .status-notice-icon {
    background: #d8ebdc;
}

.status-notice-cancelled {
    color: #805955;
    background: #faf4f3;
    border-color: #e6d4d1;
}

.status-notice-cancelled .status-notice-icon {
    background: #ead8d5;
}


/* =========================================================
   INFORMATION GRID
========================================================= */

.information-grid {
    display: grid;
    grid-template-columns:
        minmax(0, 1fr)
        minmax(0, 1fr);
    gap: 18px;
    margin-bottom: 18px;
}


/* =========================================================
   PANELS
========================================================= */

.form-panel {
    background: white;
    border: 1px solid var(--border);
    border-radius: 17px;
    overflow: hidden;
    box-shadow:
        0 5px 18px rgba(43, 31, 23, 0.035);
}

.full-width-panel {
    margin-bottom: 18px;
}

.form-panel-header {
    min-height: 68px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 11px;
    padding: 15px 18px;
    border-bottom: 1px solid var(--border);
}

.form-panel-heading {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 0;
}

.form-panel-icon {
    width: 32px;
    height: 32px;
    flex: 0 0 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: var(--orange-light);
    color: var(--orange);
    font-size: 0.9rem;
    font-weight: 800;
}

.form-panel-title {
    color: var(--dark);
    font-size: 1.0625rem;
    line-height: 1.3;
    font-weight: 700;
}

.form-panel-subtitle {
    margin-top: 3px;
    color: var(--muted);
    font-size: 0.75rem;
    line-height: 1.4;
}


/* =========================================================
   DETAIL BODY
========================================================= */

.detail-body {
    padding: 18px;
}

.detail-grid {
    display: grid;
    grid-template-columns:
        repeat(2, minmax(0, 1fr));
    gap: 18px;
}

.detail-item {
    min-width: 0;
}

.detail-item span {
    display: block;
    margin-bottom: 5px;
    color: var(--muted);
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.035rem;
}

.detail-item strong {
    display: block;
    color: var(--dark);
    font-size: 0.8125rem;
    font-weight: 700;
    word-break: break-word;
}


/* =========================================================
   SUMMARY
========================================================= */

.summary-body {
    padding: 18px;
}

.summary-row,
.summary-total-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.summary-row {
    margin-bottom: 13px;
}

.summary-row span {
    color: var(--muted);
    font-size: 0.8125rem;
    font-weight: 650;
}

.summary-row strong {
    color: var(--dark);
    font-size: 0.8125rem;
    font-weight: 700;
}

.summary-divider {
    height: 1px;
    margin: 17px 0;
    background: var(--border);
}

.summary-total-row span {
    color: var(--dark);
    font-size: 0.875rem;
    font-weight: 800;
}

.summary-total-row strong {
    color: var(--orange);
    font-size: 1.35rem;
    font-weight: 800;
}


/* =========================================================
   ITEM COUNT
========================================================= */

.item-count {
    padding: 5px 9px;
    border-radius: 7px;
    background: #f6f2ee;
    color: var(--muted);
    font-size: 0.6875rem;
    font-weight: 800;
}


/* =========================================================
   ITEMS TABLE
========================================================= */

.items-table-container {
    width: 100%;
}

.items-table-scroll {
    width: 100%;
    overflow-x: auto;
}

.items-table {
    width: 100%;
    min-width: 820px;
    border-collapse: collapse;
}

.items-table th {
    padding: 11px 14px;
    background: #fbf9f6;
    color: var(--muted);
    border-bottom: 1px solid var(--border);
    text-align: left;
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.045rem;
    white-space: nowrap;
}

.items-table td {
    padding: 13px 14px;
    border-bottom: 1px solid #f0ebe6;
    color: var(--text);
    font-size: 0.8125rem;
    vertical-align: middle;
}

.items-table tbody tr:last-child td {
    border-bottom: none;
}

.item-name-cell strong {
    display: block;
    color: var(--dark);
    font-size: 0.8125rem;
    font-weight: 700;
}

.item-name-cell small {
    display: block;
    margin-top: 3px;
    color: var(--muted);
    font-size: 0.6875rem;
}

.items-table td strong {
    color: var(--dark);
    font-weight: 800;
}


/* =========================================================
   RECEIVED BADGES
========================================================= */

.received-badge {
    display: inline-flex;
    align-items: center;
    min-width: 48px;
    justify-content: center;
    padding: 4px 7px;
    border-radius: 6px;
    font-size: 0.6875rem;
    font-weight: 800;
}

.received-complete {
    color: #477256;
    background: #edf8f0;
}

.received-partial {
    color: #896c34;
    background: #fff6df;
}

.received-none {
    color: var(--muted);
    background: #f1eeeb;
}


/* =========================================================
   EMPTY ITEMS
========================================================= */

.empty-items {
    padding: 30px !important;
    color: var(--muted) !important;
    text-align: center;
}


/* =========================================================
   NOTES
========================================================= */

.notes-body {
    padding: 18px;
    color: var(--text);
    font-size: 0.8125rem;
    line-height: 1.65;
    white-space: pre-wrap;
}


/* =========================================================
   ACTIONS
========================================================= */

.purchase-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-top: 18px;
    padding-bottom: 5px;
}

.purchase-actions-right {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    flex-wrap: wrap;
}

.inline-form {
    display: inline-flex;
    margin: 0;
}

.secondary-button,
.primary-button,
.danger-button {
    min-height: 39px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 0 13px;
    border-radius: 9px;
    font-family: inherit;
    font-size: 0.75rem;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    box-sizing: border-box;
}

.secondary-button {
    border: 1px solid var(--border);
    background: white;
    color: var(--muted);
}

.secondary-button:hover {
    background: #faf7f3;
    color: var(--dark);
}

.primary-button {
    border: none;
    background:
        linear-gradient(
            135deg,
            var(--orange),
            var(--orange-dark)
        );
    color: white;
    box-shadow:
        0 4px 11px rgba(168, 95, 40, 0.14);
}

.primary-button:hover {
    color: white;
    box-shadow:
        0 5px 13px rgba(168, 95, 40, 0.18);
}

.danger-button {
    border: 1px solid #e5cbc6;
    background: #fff8f6;
    color: #a94f48;
}

.danger-button:hover {
    background: #fff0ed;
    border-color: #dfb9b2;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1000px) {

    .information-grid {
        grid-template-columns: 1fr;
    }

}


@media (max-width: 700px) {

    .purchase-header-panel {
        align-items: flex-start;
        flex-direction: column;
    }

    .purchase-header-right {
        width: 100%;
    }

    .purchase-status {
        width: 100%;
    }

    .detail-grid {
        grid-template-columns: 1fr;
    }

    .purchase-actions {
        align-items: stretch;
        flex-direction: column;
    }

    .purchase-actions-right {
        width: 100%;
        flex-direction: column;
    }

    .secondary-button,
    .primary-button,
    .danger-button,
    .inline-form {
        width: 100%;
    }

    .inline-form button {
        width: 100%;
    }

}


@media (max-width: 480px) {

    .purchase-header-panel {
        padding: 13px 14px;
    }

    .form-panel-header,
    .detail-body,
    .summary-body,
    .notes-body {
        padding-left: 14px;
        padding-right: 14px;
    }

}

</style>

@endpush

@endsection