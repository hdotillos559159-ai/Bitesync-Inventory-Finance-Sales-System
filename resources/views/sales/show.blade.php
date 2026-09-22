@extends('layouts.app')

@section('title', 'BiteSync | Sale Details')

@section('content')

<div class="sale-show-page">

    <!-- =========================================================
         TOPBAR
         MATCH PRODUCT PAGE
    ========================================================== -->

    <div class="topbar">

        <div class="page-title">

            <small>
                Sales Management
            </small>

            <h1>
                Sale Details
            </h1>

            <p>
                View the complete details and payment information for this sale.
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
         BREADCRUMB
         MATCH PRODUCT PAGE
    ========================================================== -->

    <div class="sale-breadcrumb">

        <a href="{{ route('sales.index') }}">
            Sales
        </a>

        <span>
            /
        </span>

        <strong>
            Sale Details
        </strong>

    </div>


    <!-- =========================================================
         SALE HEADER ACTIONS
    ========================================================== -->

    <div class="sale-header-actions">

        <div class="sale-header-reference">

            <span>
                Sale Number
            </span>

            <strong>
                {{ $sale->sale_number }}
            </strong>

        </div>


        <div class="sale-header-buttons">

            <a
                href="{{ route('sales.index') }}"
                class="sale-secondary-button"
            >
                ←&nbsp; Back to Sales
            </a>


            @if ($sale->status === 'Completed')

                <form
                    method="POST"
                    action="{{ route('sales.cancel', $sale) }}"
                    onsubmit="return confirm('Are you sure you want to cancel this sale? Inventory will be restored automatically.');"
                >

                    @csrf

                    <button
                        type="submit"
                        class="sale-danger-button"
                    >
                        ⊘&nbsp; Cancel Sale
                    </button>

                </form>

            @endif

        </div>

    </div>


    <!-- =========================================================
         MAIN CONTENT
    ========================================================== -->

    <div class="sale-grid">


        <!-- =====================================================
             LEFT COLUMN
        ====================================================== -->

        <div class="sale-left-column">


            <!-- =================================================
                 SALE INFORMATION
            ================================================== -->

            <div class="sale-card">

                <div class="sale-card-header">

                    <div>

                        <h2>
                            Sale Information
                        </h2>

                        <p>
                            Basic information and current status of this transaction.
                        </p>

                    </div>

                </div>


                <div class="sale-card-body">

                    <div class="sale-info-grid">


                        <div class="sale-info-item">

                            <div class="sale-info-label">
                                Sale Number
                            </div>

                            <div class="sale-info-value">
                                {{ $sale->sale_number }}
                            </div>

                        </div>


                        <div class="sale-info-item">

                            <div class="sale-info-label">
                                Sale Date
                            </div>

                            <div class="sale-info-value">
                                {{ $sale->sale_date?->format('M d, Y h:i A') ?? '—' }}
                            </div>

                        </div>


                        <div class="sale-info-item">

                            <div class="sale-info-label">
                                Status
                            </div>

                            <div class="sale-info-value">

                                @if ($sale->status === 'Completed')

                                    <span class="sale-status sale-status-completed">
                                        Completed
                                    </span>

                                @elseif ($sale->status === 'Cancelled')

                                    <span class="sale-status sale-status-cancelled">
                                        Cancelled
                                    </span>

                                @else

                                    <span class="sale-status sale-status-default">
                                        {{ $sale->status }}
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 PRODUCTS SOLD
            ================================================== -->

            <div class="sale-card">

                <div class="sale-card-header">

                    <div>

                        <h2>
                            Products Sold
                        </h2>

                        <p>
                            {{ $sale->items->count() }}
                            product{{ $sale->items->count() === 1 ? '' : 's' }}
                            included in this sale.
                        </p>

                    </div>

                </div>


                <div class="sale-card-body">

                    @if ($sale->items->isNotEmpty())

                        <div class="sale-items-wrapper">

                            <table class="sale-items-table">

                                <thead>

                                    <tr>

                                        <th>
                                            Product
                                        </th>

                                        <th>
                                            Category
                                        </th>

                                        <th>
                                            Quantity
                                        </th>

                                        <th>
                                            Unit Price
                                        </th>

                                        <th>
                                            Subtotal
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach ($sale->items as $item)

                                        <tr>

                                            <td>

                                                <div class="sale-product-name">
                                                    {{ $item->product?->name ?? 'Deleted Product' }}
                                                </div>


                                                @if ($item->product?->sku)

                                                    <div class="sale-product-sku">
                                                        SKU: {{ $item->product->sku }}
                                                    </div>

                                                @endif

                                            </td>


                                            <td>

                                                <span class="sale-category">
                                                    {{ $item->product?->category?->name ?? '—' }}
                                                </span>

                                            </td>


                                            <td>

                                                <span class="sale-quantity">
                                                    {{ number_format((float) $item->quantity, 2) }}
                                                </span>

                                            </td>


                                            <td>

                                                <span class="sale-price">
                                                    ₱{{ number_format((float) $item->unit_price, 2) }}
                                                </span>

                                            </td>


                                            <td>

                                                <span class="sale-subtotal">
                                                    ₱{{ number_format((float) $item->subtotal, 2) }}
                                                </span>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="sale-empty-items">
                            No products were recorded for this sale.
                        </div>

                    @endif

                </div>

            </div>


            <!-- =================================================
                 CREATED BY
            ================================================== -->

            <div class="sale-card">

                <div class="sale-card-header">

                    <div>

                        <h2>
                            Sale Created By
                        </h2>

                        <p>
                            User account responsible for recording this transaction.
                        </p>

                    </div>

                </div>


                <div class="sale-card-body">

                    @if ($sale->creator)

                        <div class="sale-creator">

                            <div class="sale-creator-avatar">

                                {{ strtoupper(substr($sale->creator->name ?? 'U', 0, 1)) }}

                            </div>


                            <div>

                                <div class="sale-creator-name">
                                    {{ $sale->creator->name ?? 'Unknown User' }}
                                </div>


                                @if ($sale->creator->email)

                                    <div class="sale-creator-email">
                                        {{ $sale->creator->email }}
                                    </div>

                                @endif

                            </div>

                        </div>

                    @else

                        <span class="sale-category">
                            User information is no longer available.
                        </span>

                    @endif

                </div>

            </div>

        </div>


        <!-- =====================================================
             RIGHT COLUMN
        ====================================================== -->

        <div class="sale-right-column">


            <!-- =================================================
                 PAYMENT SUMMARY
            ================================================== -->

            <div class="sale-card sale-summary-card">

                <div class="sale-card-header">

                    <div>

                        <h2>
                            Payment Summary
                        </h2>

                        <p>
                            Financial breakdown of this transaction.
                        </p>

                    </div>

                </div>


                <div class="sale-summary-body">


                    <div class="sale-summary-row">

                        <span>
                            Subtotal
                        </span>

                        <strong>
                            ₱{{ number_format((float) $sale->subtotal, 2) }}
                        </strong>

                    </div>


                    <div class="sale-summary-row">

                        <span>
                            Discount
                        </span>

                        <strong>
                            ₱{{ number_format((float) $sale->discount, 2) }}
                        </strong>

                    </div>


                    <div class="sale-summary-row">

                        <span>
                            Tax
                        </span>

                        <strong>
                            ₱{{ number_format((float) $sale->tax, 2) }}
                        </strong>

                    </div>


                    <div class="sale-summary-divider"></div>


                    <div class="sale-summary-total">

                        <span>
                            Total
                        </span>

                        <strong>
                            ₱{{ number_format((float) $sale->total, 2) }}
                        </strong>

                    </div>


                    <!-- PAYMENT DETAILS -->

                    <div class="sale-payment-box">

                        <div class="sale-payment-title">
                            Payment Details
                        </div>


                        <div class="sale-payment-row">

                            <span>
                                Method
                            </span>

                            <strong>
                                {{ $sale->payment_method }}
                            </strong>

                        </div>


                        <div class="sale-payment-row">

                            <span>
                                Amount Received
                            </span>

                            <strong>
                                ₱{{ number_format((float) $sale->amount_received, 2) }}
                            </strong>

                        </div>


                        <div class="sale-payment-row">

                            <span>
                                Change
                            </span>

                            <strong>
                                ₱{{ number_format((float) $sale->change, 2) }}
                            </strong>

                        </div>

                    </div>


                    <!-- CANCELLATION -->

                    @if ($sale->status === 'Completed')

                        <div class="sale-cancel-section">

                            <div class="sale-cancel-warning">

                                Cancelling this sale will restore the inventory
                                consumed by its recipes and record the restoration
                                as a stock movement.

                            </div>


                            <form
                                method="POST"
                                action="{{ route('sales.cancel', $sale) }}"
                                class="sale-cancel-form"
                                onsubmit="return confirm('Are you sure you want to cancel this sale? Inventory will be restored automatically.');"
                            >

                                @csrf

                                <button type="submit">
                                    ⊘&nbsp; Cancel This Sale
                                </button>

                            </form>

                        </div>


                    @elseif ($sale->status === 'Cancelled')

                        <div class="sale-cancel-section">

                            <div class="sale-cancelled-note">

                                This sale has already been cancelled.
                                Its related inventory was restored when the
                                cancellation was processed.

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@push('styles')

<style>

/* =========================================================
   PAGE
   PRODUCT PAGE HAS:
   width: 100%
   max-width: 1120px
   margin: 0 auto
   padding-bottom: 30px

   SALES USES THE SAME STRUCTURE.
========================================================= */

.sale-show-page {

    width: 100%;

    max-width: 1120px;

    margin: 0 auto;

    padding-bottom: 30px;

    box-sizing: border-box;

}


/* =========================================================
   TOPBAR
   SAME PRODUCT WHITESPACE
========================================================= */

.sale-show-page .topbar {

    display: flex;

    align-items: flex-start;

    justify-content: space-between;

    gap: 25px;

    margin-bottom: 14px;

}


.sale-show-page .page-title {

    min-width: 0;

}


.sale-show-page .page-title small {

    display: block;

    margin-bottom: 5px;

    color: #a9825b;

    font-size: 10px;

    font-weight: 700;

    letter-spacing: .12em;

    text-transform: uppercase;

}


.sale-show-page .page-title h1 {

    margin: 0;

    color: #2d241d;

    font-size: clamp(1.6rem, 2vw, 1.9rem);

    line-height: 1.15;

    font-weight: 700;

    letter-spacing: -.04rem;

}


.sale-show-page .page-title p {

    margin: 6px 0 0;

    color: #8a8179;

    font-size: 12px;

    line-height: 1.5;

    font-weight: 400;

}


/* =========================================================
   DATE BOX
========================================================= */

.sale-show-page .date-box {

    display: inline-flex;

    align-items: center;

    gap: 9px;

    min-width: 145px;

    padding: 9px 12px;

    border: 1px solid #e8e0d7;

    border-radius: 10px;

    background: #ffffff;

    color: #8a8179;

    font-size: 10px;

    font-weight: 600;

    white-space: nowrap;

    box-shadow:
        0 3px 12px rgba(43,31,23,.025);

    box-sizing: border-box;

}


.sale-show-page .date-icon {

    display: flex;

    align-items: center;

    justify-content: center;

    width: 30px;

    height: 30px;

    flex-shrink: 0;

    border-radius: 8px;

    background: #fbf1e7;

    color: #a87542;

    font-size: 17px;

}


/* =========================================================
   BREADCRUMB
   SAME PRODUCT MARGIN
========================================================= */

.sale-breadcrumb {

    display: flex;

    align-items: center;

    gap: 7px;

    margin-bottom: 17px;

    color: #9b9188;

    font-size: 10px;

    line-height: 1.3;

}


.sale-breadcrumb a {

    color: #9a7048;

    font-weight: 600;

    text-decoration: none;

}


.sale-breadcrumb a:hover {

    text-decoration: underline;

}


.sale-breadcrumb span {

    color: #c2b5aa;

}


.sale-breadcrumb strong {

    color: #655a51;

    font-weight: 700;

}


/* =========================================================
   HEADER ACTIONS
   SAME 17px VERTICAL SPACING USED BY PRODUCT
========================================================= */

.sale-header-actions {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    margin-bottom: 17px;

    padding: 12px 14px;

    border: 1px solid #e8e0d8;

    border-radius: 10px;

    background: #fffdfa;

    box-sizing: border-box;

}


.sale-header-reference {

    display: flex;

    align-items: center;

    gap: 8px;

    min-width: 0;

}


.sale-header-reference span {

    color: #9a918b;

    font-size: 10px;

    line-height: 1.3;

    font-weight: 600;

}


.sale-header-reference strong {

    color: #51463d;

    font-size: 11px;

    line-height: 1.3;

    font-weight: 700;

}


.sale-header-buttons {

    display: flex;

    align-items: center;

    justify-content: flex-end;

    gap: 9px;

    flex-shrink: 0;

}


.sale-header-buttons form {

    margin: 0;

}


.sale-secondary-button,
.sale-danger-button {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-height: 36px;

    padding: 0 15px;

    border-radius: 8px;

    font-family: inherit;

    font-size: 11px;

    line-height: 1;

    font-weight: 700;

    text-decoration: none;

    cursor: pointer;

    box-sizing: border-box;

}


.sale-secondary-button {

    border: 1px solid #ddd5cd;

    background: #ffffff;

    color: #71665d;

    transition:
        background .18s ease,
        border-color .18s ease,
        color .18s ease;

}


.sale-secondary-button:hover {

    border-color: #d4c6ba;

    background: #faf7f4;

    color: #4d4036;

}


.sale-danger-button {

    border: 1px solid #e4c3bd;

    background: #fff4f2;

    color: #a34f45;

    transition:
        background .18s ease,
        border-color .18s ease,
        color .18s ease;

}


.sale-danger-button:hover {

    background: #f9e5e1;

    border-color: #dcb3ac;

}


/* =========================================================
   MAIN GRID
   PRODUCT SPACING = 17px
========================================================= */

.sale-grid {

    display: grid;

    grid-template-columns:
        minmax(0, 1fr)
        320px;

    gap: 17px;

    align-items: start;

}


.sale-left-column,
.sale-right-column {

    min-width: 0;

}


/* =========================================================
   CARDS
   PRODUCT CARD SPACING
========================================================= */

.sale-card {

    width: 100%;

    overflow: hidden;

    border: 1px solid #e8e0d8;

    border-radius: 15px;

    background: #ffffff;

    box-shadow:
        0 4px 16px rgba(67,52,38,.045);

    box-sizing: border-box;

}


.sale-card + .sale-card {

    margin-top: 17px;

}


/* =========================================================
   CARD HEADER
   PRODUCT:
   min-height 65px
   padding 14px 19px
========================================================= */

.sale-card-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    min-height: 65px;

    padding: 14px 19px;

    border-bottom: 1px solid #eee8e1;

    background: #fffdfa;

    box-sizing: border-box;

}


.sale-card-header h2 {

    margin: 0;

    color: #342a22;

    font-size: 17px;

    line-height: 1.2;

    font-weight: 700;

}


.sale-card-header p {

    margin: 3px 0 0;

    color: #938a82;

    font-size: 11px;

    line-height: 1.35;

}


.sale-card-body {

    padding: 18px;

}


/* =========================================================
   SALE INFORMATION
========================================================= */

.sale-info-grid {

    display: grid;

    grid-template-columns:
        repeat(3, minmax(0, 1fr));

    gap: 13px;

}


.sale-info-item {

    min-width: 0;

    padding: 13px 14px;

    border: 1px solid #eee7df;

    border-radius: 9px;

    background: #fcfaf8;

    box-sizing: border-box;

}


.sale-info-label {

    margin-bottom: 6px;

    color: #938980;

    font-size: 10px;

    line-height: 1.2;

    font-weight: 700;

    letter-spacing: .07em;

    text-transform: uppercase;

}


.sale-info-value {

    color: #403831;

    font-size: 12px;

    line-height: 1.45;

    font-weight: 700;

    word-break: break-word;

}


/* =========================================================
   STATUS
========================================================= */

.sale-status {

    display: inline-flex;

    align-items: center;

    gap: 6px;

    min-height: 23px;

    padding: 0 8px;

    border-radius: 20px;

    font-size: 9px;

    line-height: 1;

    font-weight: 700;

    white-space: nowrap;

}


.sale-status::before {

    content: "";

    width: 5px;

    height: 5px;

    border-radius: 50%;

    background: currentColor;

}


.sale-status-completed {

    background: #eaf6ef;

    color: #357553;

}


.sale-status-cancelled {

    background: #fbecea;

    color: #a34f45;

}


.sale-status-default {

    background: #f3eee9;

    color: #74685e;

}


/* =========================================================
   PRODUCTS TABLE
========================================================= */

.sale-items-wrapper {

    width: 100%;

    overflow-x: auto;

}


.sale-items-table {

    width: 100%;

    min-width: 650px;

    border-collapse: collapse;

}


.sale-items-table th {

    padding: 0 10px 10px;

    color: #938980;

    font-size: 10px;

    line-height: 1.3;

    font-weight: 700;

    letter-spacing: .07em;

    text-align: left;

    text-transform: uppercase;

    white-space: nowrap;

}


.sale-items-table td {

    padding: 13px 10px;

    border-top: 1px solid #eee8e1;

    color: #514941;

    font-size: 12px;

    line-height: 1.4;

    vertical-align: middle;

}


.sale-items-table th:first-child,
.sale-items-table td:first-child {

    padding-left: 0;

}


.sale-items-table th:last-child,
.sale-items-table td:last-child {

    padding-right: 0;

    text-align: right;

}


.sale-items-table tbody tr {

    transition: background .15s ease;

}


.sale-items-table tbody tr:hover {

    background: #fcfaf8;

}


.sale-product-name {

    color: #3d352f;

    font-size: 12px;

    line-height: 1.35;

    font-weight: 700;

}


.sale-product-sku {

    margin-top: 3px;

    color: #9a9087;

    font-size: 9px;

    line-height: 1.3;

}


.sale-category {

    color: #81776f;

    font-size: 10px;

    line-height: 1.35;

}


.sale-quantity {

    color: #514941;

    font-size: 12px;

    font-weight: 700;

}


.sale-price {

    color: #5e554e;

    font-size: 12px;

    white-space: nowrap;

}


.sale-subtotal {

    color: #2d241d;

    font-size: 12px;

    font-weight: 700;

    white-space: nowrap;

}


.sale-empty-items {

    padding: 30px 15px;

    text-align: center;

    color: #958b83;

    font-size: 11px;

}


/* =========================================================
   CREATOR
========================================================= */

.sale-creator {

    display: flex;

    align-items: center;

    gap: 11px;

}


.sale-creator-avatar {

    display: flex;

    align-items: center;

    justify-content: center;

    width: 38px;

    height: 38px;

    flex-shrink: 0;

    border-radius: 9px;

    background: #f1e9df;

    color: #795536;

    font-size: 12px;

    font-weight: 700;

}


.sale-creator-name {

    color: #403831;

    font-size: 12px;

    line-height: 1.3;

    font-weight: 700;

}


.sale-creator-email {

    margin-top: 3px;

    color: #958b83;

    font-size: 9px;

    line-height: 1.3;

}


/* =========================================================
   PAYMENT SUMMARY
========================================================= */

.sale-summary-card {

    position: sticky;

    top: 20px;

}


.sale-summary-body {

    padding: 18px;

}


.sale-summary-row {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    padding: 7px 0;

    color: #6f665e;

    font-size: 11px;

    line-height: 1.4;

}


.sale-summary-row strong {

    color: #403831;

    font-size: 11px;

    font-weight: 700;

    white-space: nowrap;

}


.sale-summary-divider {

    height: 1px;

    margin: 9px 0;

    background: #eee8e1;

}


.sale-summary-total {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    padding: 11px 0;

}


.sale-summary-total span {

    color: #3c342e;

    font-size: 13px;

    line-height: 1.3;

    font-weight: 700;

}


.sale-summary-total strong {

    color: #aa8250;

    font-size: 20px;

    line-height: 1;

    font-weight: 700;

    white-space: nowrap;

}


/* =========================================================
   PAYMENT DETAILS
========================================================= */

.sale-payment-box {

    margin-top: 10px;

    padding: 12px;

    border: 1px solid #eee7df;

    border-radius: 9px;

    background: #faf7f4;

}


.sale-payment-title {

    margin-bottom: 7px;

    color: #514941;

    font-size: 10px;

    line-height: 1.3;

    font-weight: 700;

    letter-spacing: .06em;

    text-transform: uppercase;

}


.sale-payment-row {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 12px;

    padding: 5px 0;

    font-size: 10px;

    line-height: 1.35;

}


.sale-payment-row span {

    color: #81776e;

}


.sale-payment-row strong {

    color: #514941;

    font-size: 10px;

    font-weight: 700;

    text-align: right;

}


/* =========================================================
   CANCELLATION
========================================================= */

.sale-cancel-section {

    margin-top: 17px;

    padding-top: 15px;

    border-top: 1px solid #eee8e1;

}


.sale-cancel-warning {

    margin-bottom: 10px;

    color: #8b746c;

    font-size: 9px;

    line-height: 1.55;

}


.sale-cancel-form {

    margin: 0;

}


.sale-cancel-form button {

    width: 100%;

    min-height: 36px;

    border: 1px solid #e4c3bd;

    border-radius: 8px;

    background: #fff4f2;

    color: #a34f45;

    font-family: inherit;

    font-size: 11px;

    line-height: 1;

    font-weight: 700;

    cursor: pointer;

    transition:
        background .18s ease,
        border-color .18s ease;

}


.sale-cancel-form button:hover {

    background: #f9e5e1;

    border-color: #dcb3ac;

}


.sale-cancelled-note {

    padding: 10px;

    border-radius: 8px;

    background: #fbecea;

    color: #a34f45;

    font-size: 10px;

    line-height: 1.5;

}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 1100px) {

    .sale-show-page {

        max-width: 100%;

    }


    .sale-grid {

        grid-template-columns:
            minmax(0, 1fr)
            300px;

    }

}


/* =========================================================
   MEDIUM TABLET
========================================================= */

@media (max-width: 950px) {

    .sale-grid {

        grid-template-columns: 1fr;

    }


    .sale-summary-card {

        position: static;

    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 760px) {

    .sale-show-page .topbar {

        flex-direction: column;

        gap: 12px;

    }


    .sale-show-page .date-box {

        min-width: 145px;

    }


    .sale-header-actions {

        align-items: flex-start;

        flex-direction: column;

    }


    .sale-header-buttons {

        width: 100%;

    }


    .sale-header-buttons .sale-secondary-button,
    .sale-header-buttons form {

        flex: 1;

    }


    .sale-header-buttons form .sale-danger-button {

        width: 100%;

    }


    .sale-info-grid {

        grid-template-columns: 1fr;

    }

}


/* =========================================================
   SMALL MOBILE
========================================================= */

@media (max-width: 600px) {

    .sale-show-page .page-title h1 {

        font-size: 25px;

    }


    .sale-header-buttons {

        flex-direction: column;

        align-items: stretch;

    }


    .sale-header-buttons .sale-secondary-button,
    .sale-header-buttons form,
    .sale-header-buttons form .sale-danger-button {

        width: 100%;

    }


    .sale-card-header {

        align-items: flex-start;

        flex-direction: column;

        gap: 7px;

        padding: 13px 15px;

    }


    .sale-card-body,
    .sale-summary-body {

        padding: 15px;

    }


    .sale-header-actions {

        padding: 11px;

    }

}


/* =========================================================
   VERY SMALL MOBILE
========================================================= */

@media (max-width: 480px) {

    .sale-show-page .date-box {

        width: 100%;

        justify-content: flex-start;

    }


    .sale-card {

        border-radius: 13px;

    }


    .sale-card-header {

        padding: 13px 15px;

    }


    .sale-card-body,
    .sale-summary-body {

        padding: 14px;

    }


    .sale-summary-total strong {

        font-size: 18px;

    }

}

</style>

@endpush