@extends('layouts.app')

@section('title', 'BiteSync | New Sale')

@php

/*
|--------------------------------------------------------------------------
| PAYMENT METHODS
|--------------------------------------------------------------------------
*/

$paymentMethods = [
    'Cash',
    'GCash',
    'Card',
    'Bank Transfer',
];


/*
|--------------------------------------------------------------------------
| PREPARE PRODUCT DATA
|--------------------------------------------------------------------------
*/

$productData = $products->map(function ($product) {
    return [
        'id' => $product->id,
        'name' => $product->name,
        'sku' => $product->sku,
        'selling_price' => (float) $product->selling_price,
        'category' => optional($product->category)->name,
        'image' => $product->image
            ? asset('storage/' . $product->image)
            : null,
        'has_recipe' => $product->recipe !== null,
    ];
})->values()->all();


/*
|--------------------------------------------------------------------------
| OLD SALE ITEMS
|--------------------------------------------------------------------------
*/

$oldSaleItems = old('items', []);


/*
|--------------------------------------------------------------------------
| JSON DATA
|--------------------------------------------------------------------------
*/

$productJson = json_encode(
    $productData,
    JSON_HEX_TAG |
    JSON_HEX_APOS |
    JSON_HEX_AMP |
    JSON_HEX_QUOT
);

$oldSaleItemsJson = json_encode(
    $oldSaleItems,
    JSON_HEX_TAG |
    JSON_HEX_APOS |
    JSON_HEX_AMP |
    JSON_HEX_QUOT
);

@endphp

@section('content')

<div class="sales-pos-page">

```
{{-- =========================================================
     PAGE HEADER
========================================================== --}}

<div class="sales-page-header">

    <div class="sales-page-header-left">

        <div class="sales-breadcrumb">

            <a href="{{ route('sales.index') }}">
                Sales
            </a>

            <span>
                /
            </span>

            <strong>
                New Sale
            </strong>

        </div>


        <div class="sales-heading-row">

            <div>

                <small class="sales-page-eyebrow">
                    Sales
                </small>

                <h1>
                    New Sale
                </h1>

                <p>
                    Select products and complete the customer transaction.
                </p>

            </div>

        </div>

    </div>


    <div class="sales-today-box">

        <div class="sales-today-icon">
            ◷
        </div>

        <div>

            <span>
                Today
            </span>

            <strong>
                {{ now()->format('F d, Y') }}
            </strong>

        </div>

    </div>

</div>


{{-- =========================================================
     VALIDATION ERRORS
========================================================== --}}

@if ($errors->any())

    <div class="sales-alert sales-alert-error">

        <div class="sales-alert-icon">
            !
        </div>

        <div>

            <strong>
                Please correct the following errors:
            </strong>

            <ul>

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    </div>

@endif


{{-- =========================================================
     NO PRODUCTS
========================================================== --}}

@if ($products->isEmpty())

    <div class="sales-alert sales-alert-warning">

        <div class="sales-alert-icon">
            !
        </div>

        <div>

            <strong>
                No active products available.
            </strong>

            <p>
                Please add an active product before creating a sale.
            </p>

        </div>

    </div>

@endif


{{-- =========================================================
     MAIN SALES FORM
========================================================== --}}

<form
    action="{{ route('sales.store') }}"
    method="POST"
    id="salesForm"
>

    @csrf

    <input
        type="hidden"
        name="sale_date"
        value="{{ old('sale_date', now()->format('Y-m-d')) }}"
    >


    <div class="sales-pos-layout">


        {{-- =================================================
             LEFT SIDE — PRODUCT CATALOG
        ================================================== --}}

        <main class="sales-catalog-panel">


            {{-- =================================================
                 CATALOG HEADER
            ================================================== --}}

            <div class="sales-catalog-header">

                <div>

                    <span class="sales-section-label">
                        PRODUCT CATALOG
                    </span>

                    <h2>
                        Choose Products
                    </h2>

                    <p class="sales-panel-description">
                        Select products to add them to the current sale.
                    </p>

                </div>

                <div class="sales-product-count">

                    <strong id="visibleProductCount">
                        {{ $products->count() }}
                    </strong>

                    <span>
                        products
                    </span>

                </div>

            </div>


            {{-- =================================================
                 SEARCH / FILTER BAR
            ================================================== --}}

            <div class="sales-catalog-tools">

                <div class="sales-search-box">

                    <span class="sales-search-icon">
                        ⌕
                    </span>

                    <input
                        type="text"
                        id="productSearch"
                        placeholder="Search product or SKU..."
                        autocomplete="off"
                    >

                    <button
                        type="button"
                        id="clearProductSearch"
                        class="sales-clear-search"
                        aria-label="Clear search"
                    >
                        ×
                    </button>

                </div>


                <div class="sales-category-filter">

                    <select
                        id="categoryFilter"
                        aria-label="Filter products by category"
                    >

                        <option value="all">
                            All Categories
                        </option>

                        @foreach ($products->pluck('category')->filter()->unique('id')->sortBy('name') as $category)

                            <option value="{{ strtolower($category->name) }}">
                                {{ $category->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            {{-- =================================================
                 PRODUCT GRID
            ================================================== --}}

            <div
                class="sales-product-grid"
                id="productGrid"
            >

                @foreach ($products as $product)

                    <button
                        type="button"
                        class="sales-product-card"
                        data-product-id="{{ $product->id }}"
                        data-product-name="{{ strtolower($product->name) }}"
                        data-product-sku="{{ strtolower($product->sku) }}"
                        data-product-category="{{ strtolower(optional($product->category)->name ?? '') }}"
                    >

                        <div class="sales-product-image">

                            @if ($product->image)

                                <img
                                    src="{{ asset('storage/' . $product->image) }}"
                                    alt="{{ $product->name }}"
                                    loading="lazy"
                                >

                            @else

                                <div class="sales-product-image-placeholder">

                                    <span>
                                        ◫
                                    </span>

                                </div>

                            @endif

                            <span class="sales-add-overlay">
                                +
                            </span>

                        </div>


                        <div class="sales-product-card-info">

                            <div class="sales-product-category">

                                {{ optional($product->category)->name ?? 'Uncategorized' }}

                            </div>

                            <h3>
                                {{ $product->name }}
                            </h3>

                            <div class="sales-product-meta">

                                <span>
                                    {{ $product->sku }}
                                </span>

                            </div>

                            <div class="sales-product-card-bottom">

                                <strong>
                                    ₱{{ number_format($product->selling_price, 2) }}
                                </strong>

                                <span class="sales-add-small">
                                    Add
                                </span>

                            </div>

                        </div>

                    </button>

                @endforeach


                {{-- EMPTY SEARCH RESULT --}}

                <div
                    class="sales-no-products"
                    id="noProductsFound"
                >

                    <div class="sales-no-products-icon">
                        ⌕
                    </div>

                    <strong>
                        No products found
                    </strong>

                    <p>
                        Try a different product name, SKU, or category.
                    </p>

                </div>

            </div>


            {{-- =================================================
                 CATALOG FOOTER
            ================================================== --}}

            <div class="sales-catalog-footer">

                <span class="sales-info-dot">
                    i
                </span>

                <span>
                    Click a product to add it to the current sale.
                </span>

            </div>

        </main>


        {{-- =================================================
             RIGHT SIDE — CURRENT SALE
        ================================================== --}}

        <aside class="sales-order-panel">


            {{-- =================================================
                 ORDER HEADER
            ================================================== --}}

            <div class="sales-order-header">

                <div>

                    <span class="sales-section-label">
                        CURRENT TRANSACTION
                    </span>

                    <h2>
                        Current Sale
                    </h2>

                    <p class="sales-panel-description">
                        Review products and payment details.
                    </p>

                </div>

                <div class="sales-cart-count">

                    <span id="cartItemCount">
                        0
                    </span>

                    <small>
                        items
                    </small>

                </div>

            </div>


            {{-- =================================================
                 ORDER ITEMS
            ================================================== --}}

            <div
                class="sales-order-items"
                id="orderItems"
            >

                <div
                    class="sales-order-empty"
                    id="emptyOrder"
                >

                    <div class="sales-empty-cart-icon">
                        🛒
                    </div>

                    <strong>
                        Your sale is empty
                    </strong>

                    <p>
                        Select a product from the catalog to start the transaction.
                    </p>

                </div>

            </div>


            {{-- =================================================
                 SALE DETAILS
            ================================================== --}}

            <div class="sales-order-details">


                {{-- PAYMENT METHOD --}}

                <div class="sales-order-field">

                    <label for="payment_method">
                        Payment Method
                    </label>

                    <select
                        id="payment_method"
                        name="payment_method"
                        class="sales-order-input @error('payment_method') is-invalid @enderror"
                        required
                    >

                        @foreach ($paymentMethods as $method)

                            <option
                                value="{{ $method }}"
                                @selected(old('payment_method', 'Cash') === $method)
                            >
                                {{ $method }}
                            </option>

                        @endforeach

                    </select>

                    <span class="sales-helper-text">
                        Select how the customer will pay for this sale.
                    </span>

                </div>


                {{-- DISCOUNT / TAX --}}

                <div class="sales-order-two-fields">

                    <div class="sales-order-field">

                        <label for="discount">
                            Discount
                        </label>

                        <div class="sales-money-input">

                            <span>
                                ₱
                            </span>

                            <input
                                type="number"
                                id="discount"
                                name="discount"
                                value="{{ old('discount', 0) }}"
                                min="0"
                                step="0.01"
                                class="@error('discount') is-invalid @enderror"
                            >

                        </div>

                    </div>


                    <div class="sales-order-field">

                        <label for="tax">
                            Tax
                        </label>

                        <div class="sales-money-input">

                            <span>
                                ₱
                            </span>

                            <input
                                type="number"
                                id="tax"
                                name="tax"
                                value="{{ old('tax', 0) }}"
                                min="0"
                                step="0.01"
                                class="@error('tax') is-invalid @enderror"
                            >

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     TOTALS
                ================================================== --}}

                <div class="sales-total-breakdown">

                    <div class="sales-total-line">

                        <span>
                            Subtotal
                        </span>

                        <strong id="summarySubtotal">
                            ₱0.00
                        </strong>

                    </div>


                    <div class="sales-total-line">

                        <span>
                            Discount
                        </span>

                        <strong id="summaryDiscount">
                            -₱0.00
                        </strong>

                    </div>


                    <div class="sales-total-line">

                        <span>
                            Tax
                        </span>

                        <strong id="summaryTax">
                            +₱0.00
                        </strong>

                    </div>


                    <div class="sales-total-divider"></div>


                    <div class="sales-grand-total">

                        <span>
                            Total
                        </span>

                        <strong id="summaryTotal">
                            ₱0.00
                        </strong>

                    </div>

                </div>


                {{-- =================================================
                     AMOUNT RECEIVED
                ================================================== --}}

                <div class="sales-received-section">

                    <label for="amount_received">
                        Amount Received
                    </label>

                    <div class="sales-received-input">

                        <span>
                            ₱
                        </span>

                        <input
                            type="number"
                            id="amount_received"
                            name="amount_received"
                            value="{{ old('amount_received', 0) }}"
                            min="0"
                            step="0.01"
                            class="@error('amount_received') is-invalid @enderror"
                            placeholder="0.00"
                        >

                    </div>

                    <span class="sales-helper-text">
                        Enter the amount received from the customer.
                    </span>

                </div>


                {{-- =================================================
                     CHANGE
                ================================================== --}}

                <div class="sales-change-box">

                    <div>

                        <span>
                            Change
                        </span>

                        <small>
                            Amount to return
                        </small>

                    </div>

                    <strong id="summaryChange">
                        ₱0.00
                    </strong>

                </div>


                {{-- =================================================
                     ACTIONS
                ================================================== --}}

                <div class="sales-order-actions">

                    <button
                        type="submit"
                        class="sales-complete-button"
                        id="completeSaleButton"
                        @disabled($products->isEmpty())
                    >

                        <span class="sales-complete-icon">
                            ✓
                        </span>

                        Complete Sale

                    </button>


                    <a
                        href="{{ route('sales.index') }}"
                        class="sales-cancel-button"
                    >
                        Cancel
                    </a>

                </div>


                {{-- =================================================
                     NOTE
                ================================================== --}}

                <div class="sales-order-note">

                    <span>
                        i
                    </span>

                    <p>
                        Completing this sale will record the transaction and deduct the required inventory based on each product's recipe.
                    </p>

                </div>

            </div>

        </aside>

    </div>


    {{-- =========================================================
         HIDDEN SALE ITEMS
    ========================================================== --}}

    <div id="hiddenSaleItems"></div>

</form>
```

</div>

@endsection

@push('styles')

<style>

/* ============================================================
   PAGE
============================================================ */

.sales-pos-page {
    width: 100%;
    max-width: 1500px;
    margin: 0 auto;
    padding-bottom: 30px;
    color: #342820;
}


/* ============================================================
   PAGE HEADER
============================================================ */

.sales-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 30px;
    margin-bottom: 18px;
}

.sales-page-header-left {
    min-width: 0;
}

.sales-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    color: #95887e;
    font-size: 12px;
}

.sales-breadcrumb a {
    color: var(--orange);
    text-decoration: none;
    font-weight: 600;
}

.sales-breadcrumb a:hover {
    text-decoration: underline;
}

.sales-breadcrumb strong {
    color: #5f5148;
    font-weight: 600;
}

.sales-page-eyebrow {
    display: block;
    margin-bottom: 5px;
    color: var(--orange);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.1px;
    text-transform: uppercase;
}

.sales-heading-row h1 {
    margin: 0;
    color: #2b1f17;
    font-size: 29px;
    line-height: 1.15;
    font-weight: 700;
    letter-spacing: 0;
}

.sales-heading-row p {
    margin: 7px 0 0;
    color: #84776d;
    font-size: 13px;
    line-height: 1.5;
}


/* ============================================================
   TODAY BOX
============================================================ */

.sales-today-box {
    display: flex;
    align-items: center;
    gap: 11px;
    flex: 0 0 auto;
    min-width: 165px;
    padding: 11px 14px;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: #ffffff;
    box-shadow: 0 4px 15px rgba(43, 31, 23, .035);
}

.sales-today-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 9px;
    background: var(--orange-light);
    color: var(--orange);
    font-size: 20px;
}

.sales-today-box span {
    display: block;
    margin-bottom: 2px;
    color: #978a80;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .7px;
}

.sales-today-box strong {
    display: block;
    color: #3a2c23;
    font-size: 12px;
    font-weight: 700;
}


/* ============================================================
   ALERTS
============================================================ */

.sales-alert {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 20px;
    padding: 13px 15px;
    border-radius: 11px;
    font-size: 12px;
}

.sales-alert-error {
    border: 1px solid #efcaca;
    background: #fff6f6;
    color: #8f3636;
}

.sales-alert-warning {
    border: 1px solid #ecd9b9;
    background: #fffaf1;
    color: #7d633c;
}

.sales-alert-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
    width: 23px;
    height: 23px;
    border-radius: 50%;
    background: #d9534f;
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
}

.sales-alert-warning .sales-alert-icon {
    background: #c99545;
}

.sales-alert strong {
    display: block;
    margin-bottom: 5px;
}

.sales-alert p {
    margin: 0;
}

.sales-alert ul {
    margin: 0;
    padding-left: 17px;
}


/* ============================================================
   MAIN POS LAYOUT
============================================================ */

.sales-pos-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 390px;
    gap: 18px;
    align-items: start;
}


/* ============================================================
   SHARED PANEL BASE
============================================================ */

.sales-catalog-panel,
.sales-order-panel {
    overflow: hidden;
    border: 1px solid var(--border);
    border-radius: 17px;
    background: #ffffff;
    box-shadow: 0 5px 18px rgba(43, 31, 23, .035);
}


/* ============================================================
   CATALOG PANEL
============================================================ */

.sales-catalog-panel {
    min-width: 0;
}

.sales-catalog-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    min-height: 68px;
    padding: 15px 18px;
    border-bottom: 1px solid #eee7e1;
}

.sales-section-label {
    display: block;
    margin-bottom: 4px;
    color: var(--orange);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.1px;
    text-transform: uppercase;
}

.sales-catalog-header h2,
.sales-order-header h2 {
    margin: 0;
    color: #35281f;
    font-size: 19px;
    line-height: 1.2;
    font-weight: 700;
}

.sales-panel-description {
    margin: 3px 0 0;
    color: #93877d;
    font-size: 12px;
    line-height: 1.4;
}

.sales-product-count {
    display: flex;
    align-items: baseline;
    gap: 4px;
    padding: 7px 10px;
    border-radius: 9px;
    background: #fcf9f5;
}

.sales-product-count strong {
    color: #60452f;
    font-size: 13px;
}

.sales-product-count span {
    color: #978a80;
    font-size: 10px;
}


/* ============================================================
   SEARCH / FILTER
============================================================ */

.sales-catalog-tools {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 190px;
    gap: 10px;
    padding: 14px 18px;
    border-bottom: 1px solid #f0ebe7;
    background: #fdfcfb;
}

.sales-search-box {
    position: relative;
    display: flex;
    align-items: center;
}

.sales-search-icon {
    position: absolute;
    left: 12px;
    z-index: 2;
    color: #9b8e84;
    font-size: 18px;
    pointer-events: none;
}

.sales-search-box input {
    display: block;
    width: 100%;
    height: 41px;
    box-sizing: border-box;
    padding: 0 38px 0 37px;
    border: 1px solid #ded4cb;
    border-radius: 9px;
    outline: none;
    background: #ffffff;
    color: #3b2e26;
    font-family: inherit;
    font-size: 13px;
    transition:
        border-color .18s ease,
        box-shadow .18s ease;
}

.sales-search-box input::placeholder {
    color: #b0a59d;
}

.sales-search-box input:focus {
    border-color: #d2a47b;
    box-shadow: 0 0 0 3px rgba(210, 164, 123, .12);
}

.sales-clear-search {
    position: absolute;
    right: 8px;
    display: none;
    align-items: center;
    justify-content: center;
    width: 25px;
    height: 25px;
    padding: 0;
    border: 0;
    border-radius: 6px;
    background: #f3eee9;
    color: #78695f;
    font-size: 17px;
    line-height: 1;
    cursor: pointer;
}

.sales-clear-search:hover {
    background: #ebe3dc;
}

.sales-category-filter select {
    display: block;
    width: 100%;
    height: 41px;
    padding: 0 11px;
    border: 1px solid #ded4cb;
    border-radius: 9px;
    outline: none;
    background: #ffffff;
    color: #3b2e26;
    font-family: inherit;
    font-size: 13px;
    cursor: pointer;
}

.sales-category-filter select:focus {
    border-color: #d2a47b;
    box-shadow: 0 0 0 3px rgba(210, 164, 123, .12);
}


/* ============================================================
   PRODUCT GRID
============================================================ */

.sales-product-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(150px, 1fr));
    gap: 12px;
    min-height: 300px;
    padding: 18px;
}

.sales-product-card {
    display: flex;
    flex-direction: column;
    min-width: 0;
    padding: 0;
    overflow: hidden;
    border: 1px solid #e8e0d9;
    border-radius: 13px;
    background: #ffffff;
    color: inherit;
    font-family: inherit;
    text-align: left;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(48, 36, 28, .025);
    transition:
        transform .18s ease,
        border-color .18s ease,
        box-shadow .18s ease;
}

.sales-product-card:hover {
    transform: translateY(-2px);
    border-color: #d8b99d;
    box-shadow: 0 7px 18px rgba(48, 36, 28, .08);
}

.sales-product-card:active {
    transform: translateY(0);
}

.sales-product-image {
    position: relative;
    width: 100%;
    height: 145px;
    overflow: hidden;
    background: #f5f1ed;
}

.sales-product-image img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .25s ease;
}

.sales-product-card:hover .sales-product-image img {
    transform: scale(1.035);
}

.sales-product-image-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    background:
        linear-gradient(
            135deg,
            #f7f3ef,
            #eee7e0
        );
}

.sales-product-image-placeholder span {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 13px;
    background: #ffffff;
    color: #b69a82;
    font-size: 24px;
    box-shadow: 0 3px 10px rgba(60, 43, 31, .06);
}

.sales-add-overlay {
    position: absolute;
    right: 9px;
    bottom: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 29px;
    height: 29px;
    border-radius: 50%;
    background: #ffffff;
    color: var(--orange);
    font-size: 20px;
    font-weight: 500;
    line-height: 1;
    box-shadow: 0 3px 10px rgba(35, 25, 18, .13);
}

.sales-product-card-info {
    display: flex;
    flex: 1;
    flex-direction: column;
    min-width: 0;
    padding: 11px 12px 12px;
}

.sales-product-category {
    overflow: hidden;
    margin-bottom: 4px;
    color: var(--orange);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .5px;
    text-overflow: ellipsis;
    text-transform: uppercase;
    white-space: nowrap;
}

.sales-product-card h3 {
    overflow: hidden;
    margin: 0;
    color: #3a2d25;
    font-size: 13px;
    line-height: 1.3;
    font-weight: 700;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.sales-product-meta {
    overflow: hidden;
    margin-top: 3px;
    color: #a0958c;
    font-size: 10px;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.sales-product-card-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    margin-top: auto;
    padding-top: 10px;
}

.sales-product-card-bottom strong {
    color: #4e3b2e;
    font-size: 14px;
    font-weight: 800;
}

.sales-add-small {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 27px;
    padding: 0 9px;
    border-radius: 7px;
    background: var(--orange-light);
    color: var(--orange);
    font-size: 10px;
    font-weight: 800;
}


/* ============================================================
   NO PRODUCTS
============================================================ */

.sales-no-products {
    display: none;
    grid-column: 1 / -1;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    min-height: 280px;
    text-align: center;
}

.sales-no-products-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 46px;
    height: 46px;
    margin-bottom: 10px;
    border-radius: 12px;
    background: #f5f0eb;
    color: #a89586;
    font-size: 22px;
}

.sales-no-products strong {
    color: #514239;
    font-size: 13px;
}

.sales-no-products p {
    margin: 5px 0 0;
    color: #9a8d84;
    font-size: 11px;
}


/* ============================================================
   CATALOG FOOTER
============================================================ */

.sales-catalog-footer {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-top: 1px solid #eee7e1;
    background: #fcfaf8;
    color: #968980;
    font-size: 11px;
}

.sales-info-dot {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: var(--orange-light);
    color: var(--orange);
    font-size: 10px;
    font-weight: 700;
}


/* ============================================================
   ORDER PANEL
============================================================ */

.sales-order-panel {
    position: sticky;
    top: 18px;
    min-width: 0;
}

.sales-order-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    min-height: 68px;
    padding: 15px 18px;
    border-bottom: 1px solid #eee7e1;
}

.sales-cart-count {
    display: flex;
    align-items: baseline;
    gap: 4px;
    padding: 7px 10px;
    border-radius: 9px;
    background: #fcf9f5;
}

.sales-cart-count span {
    color: var(--orange);
    font-size: 14px;
    font-weight: 800;
}

.sales-cart-count small {
    color: #978a80;
    font-size: 10px;
}


/* ============================================================
   ORDER ITEMS
============================================================ */

.sales-order-items {
    max-height: 315px;
    overflow-y: auto;
    padding: 4px 16px;
}

.sales-order-items::-webkit-scrollbar {
    width: 5px;
}

.sales-order-items::-webkit-scrollbar-track {
    background: transparent;
}

.sales-order-items::-webkit-scrollbar-thumb {
    border-radius: 10px;
    background: #ddd3ca;
}

.sales-order-empty {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    min-height: 190px;
    padding: 20px 18px;
    text-align: center;
}

.sales-empty-cart-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 52px;
    height: 52px;
    margin-bottom: 10px;
    border-radius: 15px;
    background: #f7f2ed;
    font-size: 23px;
}

.sales-order-empty strong {
    color: #4a3a30;
    font-size: 13px;
}

.sales-order-empty p {
    max-width: 230px;
    margin: 6px 0 0;
    color: #9a8d83;
    font-size: 11px;
    line-height: 1.55;
}


/* ============================================================
   CART ITEM
============================================================ */

.sales-cart-item {
    display: grid;
    grid-template-columns: 48px minmax(0, 1fr);
    gap: 10px;
    padding: 12px 0;
    border-bottom: 1px solid #eee8e2;
}

.sales-cart-item:last-child {
    border-bottom: 0;
}

.sales-cart-item-image {
    width: 48px;
    height: 48px;
    overflow: hidden;
    border-radius: 9px;
    background: #f4efeb;
}

.sales-cart-item-image img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.sales-cart-item-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    color: #ae9987;
    font-size: 18px;
}

.sales-cart-item-main {
    min-width: 0;
}

.sales-cart-item-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 8px;
}

.sales-cart-item-name {
    overflow: hidden;
    color: #44352c;
    font-size: 12px;
    line-height: 1.3;
    font-weight: 700;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.sales-cart-remove {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
    width: 23px;
    height: 23px;
    padding: 0;
    border: 0;
    border-radius: 6px;
    background: transparent;
    color: #b29f91;
    font-size: 17px;
    line-height: 1;
    cursor: pointer;
}

.sales-cart-remove:hover {
    background: #fff3f1;
    color: #c15e55;
}

.sales-cart-item-sku {
    margin-top: 2px;
    color: #a0968d;
    font-size: 9px;
}

.sales-cart-item-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    margin-top: 8px;
}

.sales-quantity-control {
    display: flex;
    align-items: center;
    height: 29px;
    overflow: hidden;
    border: 1px solid #ded5ce;
    border-radius: 8px;
    background: #ffffff;
}

.sales-quantity-button {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 27px;
    height: 29px;
    padding: 0;
    border: 0;
    background: #ffffff;
    color: #725c4d;
    font-family: inherit;
    font-size: 15px;
    line-height: 1;
    cursor: pointer;
}

.sales-quantity-button:hover {
    background: #f8f3ef;
}

.sales-quantity-value {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 27px;
    height: 29px;
    border-right: 1px solid #e5ddd6;
    border-left: 1px solid #e5ddd6;
    color: #49392f;
    font-size: 11px;
    font-weight: 700;
}

.sales-cart-item-price {
    color: #4c392c;
    font-size: 12px;
    font-weight: 800;
}


/* ============================================================
   ORDER DETAILS
============================================================ */

.sales-order-details {
    padding: 18px;
    border-top: 1px solid #eee7e1;
    background: #ffffff;
}

.sales-order-field {
    margin-bottom: 16px;
}

.sales-order-field label,
.sales-received-section label {
    display: block;
    margin-bottom: 7px;
    color: #4a3b31;
    font-size: 12px;
    font-weight: 700;
}

.sales-order-input {
    display: block;
    width: 100%;
    height: 41px;
    box-sizing: border-box;
    padding: 0 11px;
    border: 1px solid #ded4cb;
    border-radius: 9px;
    outline: none;
    background: #ffffff;
    color: #3b2e26;
    font-family: inherit;
    font-size: 13px;
}

.sales-order-input:focus {
    border-color: #d2a47b;
    box-shadow: 0 0 0 3px rgba(210, 164, 123, .12);
}

.sales-order-input.is-invalid,
.sales-money-input input.is-invalid,
.sales-received-input input.is-invalid {
    border-color: #d9534f;
}

.sales-helper-text {
    display: block;
    margin-top: 6px;
    color: #988b82;
    font-size: 11px;
    line-height: 1.45;
}


/* ============================================================
   TWO FIELDS
============================================================ */

.sales-order-two-fields {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 9px;
}

.sales-money-input {
    position: relative;
}

.sales-money-input > span {
    position: absolute;
    top: 0;
    left: 11px;
    z-index: 2;
    display: flex;
    align-items: center;
    height: 41px;
    color: #8a7b70;
    font-size: 13px;
    pointer-events: none;
}

.sales-money-input input {
    display: block;
    width: 100%;
    height: 41px;
    box-sizing: border-box;
    padding: 0 10px 0 28px;
    border: 1px solid #ded4cb;
    border-radius: 9px;
    outline: none;
    background: #ffffff;
    color: #3b2e26;
    font-family: inherit;
    font-size: 13px;
}

.sales-money-input input:focus {
    border-color: #d2a47b;
    box-shadow: 0 0 0 3px rgba(210, 164, 123, .12);
}


/* ============================================================
   TOTAL BREAKDOWN
============================================================ */

.sales-total-breakdown {
    margin-top: 16px;
    padding: 15px 0;
    border-top: 1px solid #eee7e1;
    border-bottom: 1px solid #eee7e1;
}

.sales-total-line {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 10px;
}

.sales-total-line:last-child {
    margin-bottom: 0;
}

.sales-total-line span {
    color: #75665d;
    font-size: 11px;
}

.sales-total-line strong {
    color: #4f3e32;
    font-size: 12px;
    font-weight: 700;
}

.sales-total-divider {
    height: 1px;
    margin: 12px 0;
    background: #eee7e1;
}

.sales-grand-total {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.sales-grand-total span {
    color: #403229;
    font-size: 14px;
    font-weight: 800;
}

.sales-grand-total strong {
    color: var(--orange);
    font-size: 22px;
    font-weight: 800;
}


/* ============================================================
   RECEIVED
============================================================ */

.sales-received-section {
    margin-top: 16px;
}

.sales-received-input {
    position: relative;
}

.sales-received-input > span {
    position: absolute;
    top: 0;
    left: 11px;
    z-index: 2;
    display: flex;
    align-items: center;
    height: 41px;
    color: #8a7b70;
    font-size: 13px;
    font-weight: 600;
    pointer-events: none;
}

.sales-received-input input {
    display: block;
    width: 100%;
    height: 41px;
    box-sizing: border-box;
    padding: 0 11px 0 30px;
    border: 1px solid #ded4cb;
    border-radius: 9px;
    outline: none;
    background: #ffffff;
    color: #3e3027;
    font-family: inherit;
    font-size: 13px;
    font-weight: 700;
}

.sales-received-input input:focus {
    border-color: #d2a47b;
    box-shadow: 0 0 0 3px rgba(210, 164, 123, .12);
}


/* ============================================================
   CHANGE
============================================================ */

.sales-change-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-top: 12px;
    padding: 10px 11px;
    border: 1px solid #dfe9df;
    border-radius: 9px;
    background: #f7fbf7;
}

.sales-change-box span {
    display: block;
    color: #4f674f;
    font-size: 12px;
    font-weight: 700;
}

.sales-change-box small {
    display: block;
    margin-top: 3px;
    color: #849584;
    font-size: 10px;
}

.sales-change-box strong {
    color: #4c895d;
    font-size: 17px;
    font-weight: 800;
}


/* ============================================================
   ACTIONS
============================================================ */

.sales-order-actions {
    display: flex;
    flex-direction: column;
    gap: 9px;
    margin-top: 16px;
}

.sales-complete-button {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    width: 100%;
    min-height: 41px;
    padding: 0 16px;
    border: 0;
    border-radius: 9px;
    background: linear-gradient(
        135deg,
        var(--orange),
        #bd8957
    );
    color: #ffffff;
    font-family: inherit;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 5px 12px rgba(180, 125, 75, .18);
    transition:
        transform .18s ease,
        box-shadow .18s ease,
        background .18s ease;
}

.sales-complete-button:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 7px 16px rgba(180, 125, 75, .24);
}

.sales-complete-button:disabled {
    opacity: .5;
    cursor: not-allowed;
}

.sales-complete-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: rgba(255,255,255,.18);
    font-size: 11px;
}

.sales-cancel-button {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    min-height: 41px;
    box-sizing: border-box;
    padding: 0 16px;
    border: 1px solid #ded4cb;
    border-radius: 9px;
    background: #ffffff;
    color: #66584f;
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
    transition:
        background .18s ease,
        transform .18s ease;
}

.sales-cancel-button:hover {
    background: #faf7f4;
    transform: translateY(-1px);
}


/* ============================================================
   NOTE
============================================================ */

.sales-order-note {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    margin-top: 12px;
    padding: 10px 11px;
    border-radius: 9px;
    background: #fcf9f5;
}

.sales-order-note > span {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: var(--orange-light);
    color: var(--orange);
    font-size: 10px;
    font-weight: 700;
}

.sales-order-note p {
    margin: 0;
    color: #8e8076;
    font-size: 10px;
    line-height: 1.5;
}


/* ============================================================
   RESPONSIVE — 1250
============================================================ */

@media (max-width: 1250px) {

    .sales-pos-layout {
        grid-template-columns: minmax(0, 1fr) 360px;
    }

    .sales-product-grid {
        grid-template-columns: repeat(3, minmax(150px, 1fr));
    }

}


/* ============================================================
   RESPONSIVE — 1050
============================================================ */

@media (max-width: 1050px) {

    .sales-pos-layout {
        grid-template-columns: 1fr;
    }

    .sales-order-panel {
        position: static;
    }

    .sales-product-grid {
        grid-template-columns: repeat(4, minmax(150px, 1fr));
    }

}


/* ============================================================
   RESPONSIVE — 850
============================================================ */

@media (max-width: 850px) {

    .sales-product-grid {
        grid-template-columns: repeat(3, minmax(145px, 1fr));
    }

}


/* ============================================================
   RESPONSIVE — 700
============================================================ */

@media (max-width: 700px) {

    .sales-page-header {
        flex-direction: column;
        gap: 15px;
    }

    .sales-today-box {
        width: 100%;
        box-sizing: border-box;
    }

    .sales-catalog-tools {
        grid-template-columns: 1fr;
    }

    .sales-product-grid {
        grid-template-columns: repeat(2, minmax(135px, 1fr));
        padding: 14px;
    }

    .sales-catalog-header {
        padding-left: 14px;
        padding-right: 14px;
    }

    .sales-catalog-footer {
        padding-left: 14px;
        padding-right: 14px;
    }

    .sales-order-details {
        padding: 14px;
    }

}


/* ============================================================
   RESPONSIVE — 480
============================================================ */

@media (max-width: 480px) {

    .sales-heading-row h1 {
        font-size: 25px;
    }

    .sales-catalog-header {
        align-items: flex-start;
    }

    .sales-product-grid {
        grid-template-columns: 1fr 1fr;
        gap: 9px;
    }

    .sales-product-image {
        height: 115px;
    }

    .sales-product-card-info {
        padding: 9px;
    }

    .sales-product-card h3 {
        font-size: 12px;
    }

    .sales-product-card-bottom strong {
        font-size: 12px;
    }

    .sales-order-two-fields {
        grid-template-columns: 1fr 1fr;
    }

}

</style>

@endpush

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* ============================================================
       PRODUCT DATA
    ============================================================ */

    const products = {!! $productJson ?: '[]' !!};

    const oldItems = {!! $oldSaleItemsJson ?: '[]' !!};


    /* ============================================================
       ELEMENTS
    ============================================================ */

    const salesForm =
        document.getElementById('salesForm');

    const productGrid =
        document.getElementById('productGrid');

    const productSearch =
        document.getElementById('productSearch');

    const clearProductSearch =
        document.getElementById('clearProductSearch');

    const categoryFilter =
        document.getElementById('categoryFilter');

    const noProductsFound =
        document.getElementById('noProductsFound');

    const visibleProductCount =
        document.getElementById('visibleProductCount');

    const orderItems =
        document.getElementById('orderItems');

    const emptyOrder =
        document.getElementById('emptyOrder');

    const hiddenSaleItems =
        document.getElementById('hiddenSaleItems');

    const cartItemCount =
        document.getElementById('cartItemCount');

    const discountInput =
        document.getElementById('discount');

    const taxInput =
        document.getElementById('tax');

    const amountReceivedInput =
        document.getElementById('amount_received');

    const completeSaleButton =
        document.getElementById('completeSaleButton');

    const summarySubtotal =
        document.getElementById('summarySubtotal');

    const summaryDiscount =
        document.getElementById('summaryDiscount');

    const summaryTax =
        document.getElementById('summaryTax');

    const summaryTotal =
        document.getElementById('summaryTotal');

    const summaryChange =
        document.getElementById('summaryChange');


    /* ============================================================
       CART
    ============================================================ */

    let cart = [];

    let rowIndex = 0;


    /* ============================================================
       MONEY FORMAT
    ============================================================ */

    function formatMoney(value) {

        const number =
            Number(value) || 0;

        return '₱' +
            number.toLocaleString(
                'en-PH',
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );

    }


    /* ============================================================
       ESCAPE HTML
    ============================================================ */

    function escapeHtml(value) {

        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    /* ============================================================
       GET PRODUCT
    ============================================================ */

    function getProduct(productId) {

        return products.find(function (product) {

            return String(product.id) ===
                String(productId);

        }) || null;

    }


    /* ============================================================
       GET CART ITEM
    ============================================================ */

    function getCartItem(productId) {

        return cart.find(function (item) {

            return String(item.product_id) ===
                String(productId);

        }) || null;

    }


    /* ============================================================
       FILTER PRODUCTS
    ============================================================ */

    function filterProducts() {

        const search =
            String(productSearch?.value || '')
                .trim()
                .toLowerCase();

        const category =
            String(categoryFilter?.value || 'all')
                .toLowerCase();


        const cards =
            productGrid
                ? productGrid.querySelectorAll(
                    '.sales-product-card'
                )
                : [];


        let visibleCount = 0;


        cards.forEach(function (card) {

            const name =
                card.dataset.productName || '';

            const sku =
                card.dataset.productSku || '';

            const cardCategory =
                card.dataset.productCategory || '';


            const matchesSearch =
                !search ||
                name.includes(search) ||
                sku.includes(search);

            const matchesCategory =
                category === 'all' ||
                cardCategory === category;


            const visible =
                matchesSearch &&
                matchesCategory;


            card.style.display =
                visible ? 'flex' : 'none';


            if (visible) {

                visibleCount++;

            }

        });


        if (visibleProductCount) {

            visibleProductCount.textContent =
                visibleCount;

        }


        if (noProductsFound) {

            noProductsFound.style.display =
                visibleCount === 0
                    ? 'flex'
                    : 'none';

        }


        if (clearProductSearch) {

            clearProductSearch.style.display =
                search
                    ? 'flex'
                    : 'none';

        }

    }


    /* ============================================================
       ADD PRODUCT TO CART
    ============================================================ */

    function addToCart(productId, quantity = 1) {

        const product =
            getProduct(productId);


        if (!product) {
            return;
        }


        const existing =
            getCartItem(productId);


        if (existing) {

            existing.quantity =
                Number(existing.quantity) +
                Number(quantity);

        } else {

            cart.push({
                product_id: product.id,
                quantity: Math.max(
                    1,
                    Number(quantity) || 1
                )
            });

        }


        renderCart();

        updateSummary();

    }


    /* ============================================================
       CHANGE QUANTITY
    ============================================================ */

    function changeQuantity(productId, amount) {

        const item =
            getCartItem(productId);


        if (!item) {
            return;
        }


        item.quantity =
            Math.max(
                1,
                Number(item.quantity) +
                Number(amount)
            );


        renderCart();

        updateSummary();

    }


    /* ============================================================
       SET QUANTITY
    ============================================================ */

    function setQuantity(productId, quantity) {

        const item =
            getCartItem(productId);


        if (!item) {
            return;
        }


        const newQuantity =
            Math.max(
                1,
                parseInt(quantity, 10) || 1
            );


        item.quantity =
            newQuantity;


        renderCart();

        updateSummary();

    }


    /* ============================================================
       REMOVE PRODUCT
    ============================================================ */

    function removeFromCart(productId) {

        cart =
            cart.filter(function (item) {

                return String(item.product_id) !==
                    String(productId);

            });


        renderCart();

        updateSummary();

    }


    /* ============================================================
       RENDER CART
    ============================================================ */

    function renderCart() {

        if (!orderItems) {
            return;
        }


        orderItems.innerHTML = '';


        if (cart.length === 0) {

            orderItems.innerHTML = `

                <div
                    class="sales-order-empty"
                    id="emptyOrder"
                >

                    <div class="sales-empty-cart-icon">
                        🛒
                    </div>

                    <strong>
                        Your sale is empty
                    </strong>

                    <p>
                        Select a product from the catalog to start the transaction.
                    </p>

                </div>

            `;


            updateCartCount();

            updateHiddenInputs();

            return;

        }


        cart.forEach(function (item) {

            const product =
                getProduct(item.product_id);


            if (!product) {
                return;
            }


            const itemTotal =
                Number(product.selling_price) *
                Number(item.quantity);


            const itemElement =
                document.createElement('div');


            itemElement.className =
                'sales-cart-item';


            let imageHtml;


            if (product.image) {

                imageHtml = `

                    <img
                        src="${escapeHtml(product.image)}"
                        alt="${escapeHtml(product.name)}"
                    >

                `;

            } else {

                imageHtml = `

                    <div class="sales-cart-item-placeholder">
                        ◫
                    </div>

                `;

            }


            itemElement.innerHTML = `

                <div class="sales-cart-item-image">

                    ${imageHtml}

                </div>


                <div class="sales-cart-item-main">

                    <div class="sales-cart-item-top">

                        <div
                            class="sales-cart-item-name"
                            title="${escapeHtml(product.name)}"
                        >
                            ${escapeHtml(product.name)}
                        </div>

                        <button
                            type="button"
                            class="sales-cart-remove"
                            data-remove-id="${escapeHtml(product.id)}"
                            title="Remove product"
                        >
                            ×
                        </button>

                    </div>


                    <div class="sales-cart-item-sku">

                        ${escapeHtml(product.sku)}

                    </div>


                    <div class="sales-cart-item-bottom">

                        <div class="sales-quantity-control">

                            <button
                                type="button"
                                class="sales-quantity-button"
                                data-action="decrease"
                                data-product-id="${escapeHtml(product.id)}"
                            >
                                −
                            </button>

                            <span class="sales-quantity-value">
                                ${escapeHtml(item.quantity)}
                            </span>

                            <button
                                type="button"
                                class="sales-quantity-button"
                                data-action="increase"
                                data-product-id="${escapeHtml(product.id)}"
                            >
                                +
                            </button>

                        </div>


                        <span class="sales-cart-item-price">

                            ${formatMoney(itemTotal)}

                        </span>

                    </div>

                </div>

            `;


            orderItems.appendChild(itemElement);

        });


        updateCartCount();

        updateHiddenInputs();

    }


    /* ============================================================
       CART COUNT
    ============================================================ */

    function updateCartCount() {

        const count =
            cart.reduce(
                function (total, item) {

                    return total +
                        Number(item.quantity || 0);

                },
                0
            );


        if (cartItemCount) {

            cartItemCount.textContent =
                count;

        }

    }


    /* ============================================================
       HIDDEN FORM INPUTS
    ============================================================ */

    function updateHiddenInputs() {

        if (!hiddenSaleItems) {
            return;
        }


        hiddenSaleItems.innerHTML = '';


        cart.forEach(function (item, index) {

            const productInput =
                document.createElement('input');

            productInput.type =
                'hidden';

            productInput.name =
                `items[${index}][product_id]`;

            productInput.value =
                item.product_id;


            const quantityInput =
                document.createElement('input');

            quantityInput.type =
                'hidden';

            quantityInput.name =
                `items[${index}][quantity]`;

            quantityInput.value =
                item.quantity;


            hiddenSaleItems.appendChild(
                productInput
            );

            hiddenSaleItems.appendChild(
                quantityInput
            );

        });

    }


    /* ============================================================
       UPDATE SUMMARY
    ============================================================ */

    function updateSummary() {

        let subtotal = 0;


        cart.forEach(function (item) {

            const product =
                getProduct(item.product_id);


            if (!product) {
                return;
            }


            subtotal +=
                Number(product.selling_price || 0) *
                Number(item.quantity || 0);

        });


        const discount =
            Math.max(
                0,
                Number(discountInput?.value) || 0
            );


        const tax =
            Math.max(
                0,
                Number(taxInput?.value) || 0
            );


        const received =
            Math.max(
                0,
                Number(amountReceivedInput?.value) || 0
            );


        const total =
            Math.max(
                0,
                subtotal - discount + tax
            );


        const change =
            Math.max(
                0,
                received - total
            );


        if (summarySubtotal) {

            summarySubtotal.textContent =
                formatMoney(subtotal);

        }


        if (summaryDiscount) {

            summaryDiscount.textContent =
                '-' + formatMoney(discount);

        }


        if (summaryTax) {

            summaryTax.textContent =
                '+' + formatMoney(tax);

        }


        if (summaryTotal) {

            summaryTotal.textContent =
                formatMoney(total);

        }


        if (summaryChange) {

            summaryChange.textContent =
                formatMoney(change);

        }

    }


    /* ============================================================
       PRODUCT CARD CLICK
    ============================================================ */

    if (productGrid) {

        productGrid.addEventListener(
            'click',
            function (event) {

                const card =
                    event.target.closest(
                        '.sales-product-card'
                    );


                if (!card) {
                    return;
                }


                const productId =
                    card.dataset.productId;


                if (!productId) {
                    return;
                }


                addToCart(
                    productId,
                    1
                );

            }
        );

    }


    /* ============================================================
       CART BUTTON EVENTS
    ============================================================ */

    if (orderItems) {

        orderItems.addEventListener(
            'click',
            function (event) {

                const removeButton =
                    event.target.closest(
                        '[data-remove-id]'
                    );


                if (removeButton) {

                    removeFromCart(
                        removeButton.dataset.removeId
                    );

                    return;

                }


                const quantityButton =
                    event.target.closest(
                        '[data-action]'
                    );


                if (!quantityButton) {
                    return;
                }


                const productId =
                    quantityButton.dataset.productId;

                const action =
                    quantityButton.dataset.action;


                if (action === 'increase') {

                    changeQuantity(
                        productId,
                        1
                    );

                }


                if (action === 'decrease') {

                    const item =
                        getCartItem(productId);


                    if (
                        item &&
                        Number(item.quantity) <= 1
                    ) {

                        removeFromCart(
                            productId
                        );

                    } else {

                        changeQuantity(
                            productId,
                            -1
                        );

                    }

                }

            }
        );

    }


    /* ============================================================
       SEARCH
    ============================================================ */

    if (productSearch) {

        productSearch.addEventListener(
            'input',
            filterProducts
        );

    }


    /* ============================================================
       CLEAR SEARCH
    ============================================================ */

    if (clearProductSearch) {

        clearProductSearch.addEventListener(
            'click',
            function () {

                if (productSearch) {

                    productSearch.value =
                        '';

                    productSearch.focus();

                }

                filterProducts();

            }
        );

    }


    /* ============================================================
       CATEGORY FILTER
    ============================================================ */

    if (categoryFilter) {

        categoryFilter.addEventListener(
            'change',
            filterProducts
        );

    }


    /* ============================================================
       DISCOUNT
    ============================================================ */

    if (discountInput) {

        discountInput.addEventListener(
            'input',
            updateSummary
        );

    }


    /* ============================================================
       TAX
    ============================================================ */

    if (taxInput) {

        taxInput.addEventListener(
            'input',
            updateSummary
        );

    }


    /* ============================================================
       AMOUNT RECEIVED
    ============================================================ */

    if (amountReceivedInput) {

        amountReceivedInput.addEventListener(
            'input',
            updateSummary
        );

    }


    /* ============================================================
       RESTORE OLD ITEMS
    ============================================================ */

    if (
        Array.isArray(oldItems) &&
        oldItems.length > 0
    ) {

        oldItems.forEach(function (item) {

            const product =
                getProduct(item.product_id);


            if (!product) {
                return;
            }


            const existing =
                getCartItem(item.product_id);


            const quantity =
                Math.max(
                    1,
                    parseInt(item.quantity, 10) || 1
                );


            if (existing) {

                existing.quantity +=
                    quantity;

            } else {

                cart.push({
                    product_id: product.id,
                    quantity: quantity
                });

            }

        });

    }


    /* ============================================================
       FORM SUBMIT
    ============================================================ */

    if (salesForm) {

        salesForm.addEventListener(
            'submit',
            function (event) {

                /*
                |----------------------------------------------------
                | CHECK CART
                |----------------------------------------------------
                */

                if (cart.length === 0) {

                    event.preventDefault();

                    alert(
                        'Please add at least one product to the sale.'
                    );

                    return;

                }


                /*
                |----------------------------------------------------
                | CHECK QUANTITIES
                |----------------------------------------------------
                */

                let hasInvalidQuantity =
                    false;


                cart.forEach(function (item) {

                    if (
                        !item.product_id ||
                        Number(item.quantity) <= 0
                    ) {

                        hasInvalidQuantity =
                            true;

                    }

                });


                if (hasInvalidQuantity) {

                    event.preventDefault();

                    alert(
                        'Please make sure every product has a valid quantity.'
                    );

                    return;

                }


                /*
                |----------------------------------------------------
                | CALCULATE TOTAL
                |----------------------------------------------------
                */

                let subtotal = 0;


                cart.forEach(function (item) {

                    const product =
                        getProduct(item.product_id);


                    if (!product) {
                        return;
                    }


                    subtotal +=
                        Number(product.selling_price || 0) *
                        Number(item.quantity || 0);

                });


                const discount =
                    Math.max(
                        0,
                        Number(discountInput?.value) || 0
                    );


                const tax =
                    Math.max(
                        0,
                        Number(taxInput?.value) || 0
                    );


                const total =
                    Math.max(
                        0,
                        subtotal - discount + tax
                    );


                const received =
                    Math.max(
                        0,
                        Number(amountReceivedInput?.value) || 0
                    );


                /*
                |----------------------------------------------------
                | CHECK PAYMENT
                |----------------------------------------------------
                */

                if (received < total) {

                    event.preventDefault();

                    alert(
                        'Amount received is not enough to complete this sale.'
                    );

                    if (amountReceivedInput) {

                        amountReceivedInput.focus();

                    }

                    return;

                }


                /*
                |----------------------------------------------------
                | UPDATE HIDDEN INPUTS
                |----------------------------------------------------
                */

                updateHiddenInputs();


                /*
                |----------------------------------------------------
                | PROCESSING STATE
                |----------------------------------------------------
                */

                if (completeSaleButton) {

                    completeSaleButton.disabled =
                        true;

                    completeSaleButton.innerHTML = `

                        <span class="sales-complete-icon">
                            ✓
                        </span>

                        Processing...

                    `;

                }

            }
        );

    }


    /* ============================================================
       INITIALIZE
    ============================================================ */

    filterProducts();

    renderCart();

    updateSummary();

});

</script>

@endpush
