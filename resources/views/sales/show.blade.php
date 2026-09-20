@extends('layouts.app')

@section('title', 'Sale Details')

@section('content')

<style>
    .sale-show {
        padding: 28px 32px 40px;
        max-width: 1500px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .page-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .back-button {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        border: 1px solid #e5ded5;
        background: #fff;
        color: #5f554c;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 20px;
        transition: all .2s ease;
    }

    .back-button:hover {
        background: #f7f3ed;
        color: #8b5e34;
        transform: translateX(-2px);
    }

    .page-title {
        margin: 0;
        color: #2f2924;
        font-size: 26px;
        font-weight: 750;
        letter-spacing: -.4px;
    }

    .page-subtitle {
        margin: 4px 0 0;
        color: #8a8179;
        font-size: 13px;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .action-button {
        min-height: 42px;
        padding: 0 16px;
        border-radius: 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        box-sizing: border-box;
    }

    .secondary-button {
        border: 1px solid #ddd5cc;
        background: #fff;
        color: #665d55;
    }

    .secondary-button:hover {
        background: #faf7f4;
    }

    .danger-button {
        border: 1px solid #e7c6c0;
        background: #fff5f3;
        color: #a34f45;
    }

    .danger-button:hover {
        background: #f9e8e4;
    }

    .sale-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 370px;
        gap: 24px;
        align-items: start;
    }

    .card {
        background: #fff;
        border: 1px solid #e8e1d9;
        border-radius: 18px;
        box-shadow: 0 4px 18px rgba(43, 33, 24, .045);
    }

    .card + .card {
        margin-top: 20px;
    }

    .card-header {
        padding: 20px 22px;
        border-bottom: 1px solid #eee8e1;
    }

    .card-title {
        margin: 0;
        color: #332c27;
        font-size: 16px;
        font-weight: 700;
    }

    .card-description {
        margin: 4px 0 0;
        color: #948a82;
        font-size: 12px;
    }

    .card-body {
        padding: 22px;
    }

    .sale-info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
    }

    .info-item {
        padding: 14px 15px;
        border: 1px solid #eee7df;
        border-radius: 12px;
        background: #fcfaf8;
    }

    .info-label {
        margin-bottom: 5px;
        color: #938980;
        font-size: 10px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .55px;
    }

    .info-value {
        color: #403831;
        font-size: 13px;
        font-weight: 700;
        word-break: break-word;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        min-height: 25px;
        padding: 0 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 750;
    }

    .status-completed {
        background: #eaf6ef;
        color: #357553;
    }

    .status-cancelled {
        background: #fbecea;
        color: #a34f45;
    }

    .items-table-wrapper {
        overflow-x: auto;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
    }

    .items-table th {
        padding: 0 12px 12px;
        color: #938980;
        font-size: 10px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .55px;
        text-align: left;
        white-space: nowrap;
    }

    .items-table td {
        padding: 15px 12px;
        border-top: 1px solid #eee8e1;
        color: #514941;
        font-size: 13px;
        vertical-align: middle;
    }

    .items-table th:first-child,
    .items-table td:first-child {
        padding-left: 0;
    }

    .items-table th:last-child,
    .items-table td:last-child {
        padding-right: 0;
        text-align: right;
    }

    .product-name {
        color: #3d352f;
        font-weight: 700;
    }

    .product-sku {
        margin-top: 3px;
        color: #9a9087;
        font-size: 11px;
    }

    .category-text {
        color: #81776f;
        font-size: 12px;
    }

    .quantity-text {
        font-weight: 650;
    }

    .price-text {
        white-space: nowrap;
    }

    .subtotal-text {
        color: #403831;
        font-weight: 750;
        white-space: nowrap;
    }

    .summary-card {
        position: sticky;
        top: 24px;
    }

    .summary-body {
        padding: 22px;
    }

    .summary-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 9px 0;
        color: #6f665e;
        font-size: 13px;
    }

    .summary-row strong {
        color: #403831;
        font-weight: 700;
    }

    .summary-divider {
        height: 1px;
        margin: 10px 0;
        background: #eee8e1;
    }

    .summary-total {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 14px 0;
    }

    .summary-total span {
        color: #3c342e;
        font-size: 15px;
        font-weight: 700;
    }

    .summary-total strong {
        color: #8b5e34;
        font-size: 24px;
        font-weight: 800;
    }

    .payment-box {
        margin-top: 12px;
        padding: 14px;
        border-radius: 12px;
        background: #f8f5f1;
    }

    .payment-box-title {
        margin-bottom: 10px;
        color: #514941;
        font-size: 11px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .45px;
    }

    .payment-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 5px 0;
        font-size: 12px;
    }

    .payment-row span {
        color: #81776e;
    }

    .payment-row strong {
        color: #514941;
    }

    .creator-box {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .creator-avatar {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: #f1e9df;
        color: #795536;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        font-weight: 800;
        flex-shrink: 0;
    }

    .creator-name {
        color: #403831;
        font-size: 13px;
        font-weight: 700;
    }

    .creator-email {
        margin-top: 2px;
        color: #958b83;
        font-size: 11px;
    }

    .cancel-section {
        margin-top: 20px;
        padding-top: 18px;
        border-top: 1px solid #eee8e1;
    }

    .cancel-warning {
        margin-bottom: 12px;
        color: #8b746c;
        font-size: 11px;
        line-height: 1.55;
    }

    .cancel-form button {
        width: 100%;
        min-height: 43px;
        border: 1px solid #e4c3bd;
        border-radius: 11px;
        background: #fff4f2;
        color: #a34f45;
        font-size: 12px;
        font-weight: 750;
        cursor: pointer;
        transition: all .2s ease;
    }

    .cancel-form button:hover {
        background: #f9e5e1;
    }

    .cancelled-note {
        padding: 13px;
        border-radius: 11px;
        background: #fbecea;
        color: #a34f45;
        font-size: 12px;
        line-height: 1.5;
    }

    .empty-items {
        padding: 30px 15px;
        text-align: center;
        color: #958b83;
        font-size: 13px;
    }

    @media (max-width: 1050px) {
        .sale-grid {
            grid-template-columns: 1fr;
        }

        .summary-card {
            position: static;
        }
    }

    @media (max-width: 800px) {
        .sale-show {
            padding: 20px 16px 30px;
        }

        .page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions .action-button {
            flex: 1;
        }

        .sale-info-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 550px) {
        .page-header-left {
            align-items: flex-start;
        }

        .page-title {
            font-size: 22px;
        }

        .header-actions {
            flex-direction: column;
        }

        .header-actions .action-button {
            width: 100%;
        }

        .card-body,
        .summary-body,
        .card-header {
            padding: 17px;
        }
    }
</style>

<div class="sale-show">

    {{-- ================================================================
         PAGE HEADER
    ================================================================= --}}

    <div class="page-header">

        <div class="page-header-left">

            <a
                href="{{ route('sales.index') }}"
                class="back-button"
                title="Back to Sales"
            >
                ←
            </a>

            <div>

                <h1 class="page-title">
                    Sale Details
                </h1>

                <p class="page-subtitle">
                    {{ $sale->sale_number }}
                </p>

            </div>

        </div>


        <div class="header-actions">

            <a
                href="{{ route('sales.index') }}"
                class="action-button secondary-button"
            >
                Back to Sales
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
                        class="action-button danger-button"
                    >
                        Cancel Sale
                    </button>
                </form>

            @endif

        </div>

    </div>


    {{-- ================================================================
         MAIN LAYOUT
    ================================================================= --}}

    <div class="sale-grid">

        {{-- ============================================================
             LEFT CONTENT
        ============================================================= --}}

        <div>

            {{-- SALE INFORMATION --}}

            <div class="card">

                <div class="card-header">

                    <h2 class="card-title">
                        Sale Information
                    </h2>

                </div>

                <div class="card-body">

                    <div class="sale-info-grid">

                        <div class="info-item">

                            <div class="info-label">
                                Sale Number
                            </div>

                            <div class="info-value">
                                {{ $sale->sale_number }}
                            </div>

                        </div>


                        <div class="info-item">

                            <div class="info-label">
                                Sale Date
                            </div>

                            <div class="info-value">
                                {{ $sale->sale_date?->format('M d, Y h:i A') ?? '—' }}
                            </div>

                        </div>


                        <div class="info-item">

                            <div class="info-label">
                                Status
                            </div>

                            <div class="info-value">

                                @if ($sale->status === 'Completed')

                                    <span class="status-badge status-completed">
                                        Completed
                                    </span>

                                @elseif ($sale->status === 'Cancelled')

                                    <span class="status-badge status-cancelled">
                                        Cancelled
                                    </span>

                                @else

                                    <span class="status-badge">
                                        {{ $sale->status }}
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- PRODUCTS SOLD --}}

            <div class="card">

                <div class="card-header">

                    <h2 class="card-title">
                        Products Sold
                    </h2>

                    <p class="card-description">
                        {{ $sale->items->count() }} product{{ $sale->items->count() === 1 ? '' : 's' }}
                    </p>

                </div>

                <div class="card-body">

                    @if ($sale->items->isNotEmpty())

                        <div class="items-table-wrapper">

                            <table class="items-table">

                                <thead>

                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Subtotal</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach ($sale->items as $item)

                                        <tr>

                                            <td>

                                                <div class="product-name">
                                                    {{ $item->product?->name ?? 'Deleted Product' }}
                                                </div>

                                                @if ($item->product?->sku)
                                                    <div class="product-sku">
                                                        SKU: {{ $item->product->sku }}
                                                    </div>
                                                @endif

                                            </td>


                                            <td>

                                                <span class="category-text">
                                                    {{ $item->product?->category?->name ?? '—' }}
                                                </span>

                                            </td>


                                            <td>

                                                <span class="quantity-text">
                                                    {{ number_format((float) $item->quantity, 2) }}
                                                </span>

                                            </td>


                                            <td>

                                                <span class="price-text">
                                                    ₱{{ number_format((float) $item->unit_price, 2) }}
                                                </span>

                                            </td>


                                            <td>

                                                <span class="subtotal-text">
                                                    ₱{{ number_format((float) $item->subtotal, 2) }}
                                                </span>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="empty-items">
                            No products were recorded for this sale.
                        </div>

                    @endif

                </div>

            </div>


            {{-- CREATED BY --}}

            <div class="card">

                <div class="card-header">

                    <h2 class="card-title">
                        Sale Created By
                    </h2>

                </div>

                <div class="card-body">

                    @if ($sale->creator)

                        <div class="creator-box">

                            <div class="creator-avatar">

                                {{ strtoupper(substr($sale->creator->name ?? 'U', 0, 1)) }}

                            </div>

                            <div>

                                <div class="creator-name">
                                    {{ $sale->creator->name ?? 'Unknown User' }}
                                </div>

                                @if ($sale->creator->email)
                                    <div class="creator-email">
                                        {{ $sale->creator->email }}
                                    </div>
                                @endif

                            </div>

                        </div>

                    @else

                        <span class="category-text">
                            User information is no longer available.
                        </span>

                    @endif

                </div>

            </div>

        </div>


        {{-- ============================================================
             RIGHT SUMMARY
        ============================================================= --}}

        <div>

            <div class="card summary-card">

                <div class="card-header">

                    <h2 class="card-title">
                        Payment Summary
                    </h2>

                </div>

                <div class="summary-body">

                    <div class="summary-row">

                        <span>
                            Subtotal
                        </span>

                        <strong>
                            ₱{{ number_format((float) $sale->subtotal, 2) }}
                        </strong>

                    </div>


                    <div class="summary-row">

                        <span>
                            Discount
                        </span>

                        <strong>
                            ₱{{ number_format((float) $sale->discount, 2) }}
                        </strong>

                    </div>


                    <div class="summary-row">

                        <span>
                            Tax
                        </span>

                        <strong>
                            ₱{{ number_format((float) $sale->tax, 2) }}
                        </strong>

                    </div>


                    <div class="summary-divider"></div>


                    <div class="summary-total">

                        <span>
                            Total
                        </span>

                        <strong>
                            ₱{{ number_format((float) $sale->total, 2) }}
                        </strong>

                    </div>


                    <div class="payment-box">

                        <div class="payment-box-title">
                            Payment Details
                        </div>


                        <div class="payment-row">

                            <span>
                                Method
                            </span>

                            <strong>
                                {{ $sale->payment_method }}
                            </strong>

                        </div>


                        <div class="payment-row">

                            <span>
                                Amount Received
                            </span>

                            <strong>
                                ₱{{ number_format((float) $sale->amount_received, 2) }}
                            </strong>

                        </div>


                        <div class="payment-row">

                            <span>
                                Change
                            </span>

                            <strong>
                                ₱{{ number_format((float) $sale->change, 2) }}
                            </strong>

                        </div>

                    </div>


                    {{-- CANCELLATION --}}

                    @if ($sale->status === 'Completed')

                        <div class="cancel-section">

                            <div class="cancel-warning">
                                Cancelling this sale will restore the inventory consumed by its recipes and record the restoration as a stock movement.
                            </div>

                            <form
                                method="POST"
                                action="{{ route('sales.cancel', $sale) }}"
                                class="cancel-form"
                                onsubmit="return confirm('Are you sure you want to cancel this sale? Inventory will be restored automatically.');"
                            >
                                @csrf

                                <button type="submit">
                                    Cancel This Sale
                                </button>

                            </form>

                        </div>

                    @elseif ($sale->status === 'Cancelled')

                        <div class="cancel-section">

                            <div class="cancelled-note">
                                This sale has already been cancelled. Its related inventory was restored when the cancellation was processed.
                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection