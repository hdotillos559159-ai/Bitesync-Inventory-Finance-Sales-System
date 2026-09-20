@extends('layouts.app')

@section('title', 'BiteSync | Products')

@section('content')

<div class="products-page">

    <!-- =========================================================
         PRODUCTS TOPBAR
         Shared Dashboard layout controls the topbar typography
    ========================================================== -->

    <div class="topbar">

        <div class="page-title">

            <small>
                Product Management
            </small>

            <h1>
                Products
            </h1>

            <p>
                Manage your BiteSync menu and product records.
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

    <section class="product-stats">

        <!-- TOTAL PRODUCTS -->

        <div class="product-stat">

            <div>

                <div class="product-stat-label">
                    TOTAL PRODUCTS
                </div>

                <div class="product-stat-value">
                    {{ $totalProducts }}
                </div>

                <div class="product-stat-note">
                    All product records
                </div>

            </div>

            <div class="product-stat-icon">
                ▦
            </div>

        </div>


        <!-- ACTIVE PRODUCTS -->

        <div class="product-stat">

            <div>

                <div class="product-stat-label">
                    ACTIVE PRODUCTS
                </div>

                <div class="product-stat-value">
                    {{ $activeProducts }}
                </div>

                <div class="product-stat-note">
                    Currently available
                </div>

            </div>

            <div class="product-stat-icon">
                ✓
            </div>

        </div>


        <!-- INACTIVE PRODUCTS -->

        <div class="product-stat">

            <div>

                <div class="product-stat-label">
                    INACTIVE PRODUCTS
                </div>

                <div class="product-stat-value">
                    {{ $inactiveProducts }}
                </div>

                <div class="product-stat-note">
                    Not currently available
                </div>

            </div>

            <div class="product-stat-icon">
                —
            </div>

        </div>


        <!-- ACTIVE SELLING VALUE -->

        <div class="product-stat">

            <div>

                <div class="product-stat-label">
                    ACTIVE SELLING VALUE
                </div>

                <div class="product-stat-value product-money">
                    ₱{{ number_format((float) $totalProductValue, 2) }}
                </div>

                <div class="product-stat-note">
                    Sum of active selling prices
                </div>

            </div>

            <div class="product-stat-icon">
                ₱
            </div>

        </div>

    </section>


    <!-- =========================================================
         PRODUCT RECORDS PANEL
    ========================================================== -->

    <div class="products-panel">


        <!-- =====================================================
             PANEL HEADER
        ====================================================== -->

        <div class="products-panel-header">

            <div>

                <div class="products-panel-title">
                    Product Records
                </div>

                <div class="products-panel-subtitle">
                    Search and manage your café product records.
                </div>

            </div>


            <!-- ADD PRODUCT -->

            @if ($user->role === 'CEO/Admin')

                <a
                    href="{{ route('products.create') }}"
                    class="product-add-button"
                >

                    <span>
                        +
                    </span>

                    Add Product

                </a>

            @endif

        </div>


        <!-- =====================================================
             SEARCH / FILTER
        ====================================================== -->

        <form
            method="GET"
            action="{{ route('products.index') }}"
            class="product-filters"
        >

            <!-- SEARCH -->

            <div class="product-search-wrapper">

                <span class="product-search-icon">
                    ⌕
                </span>

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search products..."
                >

            </div>


            <!-- STATUS -->

            <select name="status">

                <option value="">
                    All Status
                </option>

                <option
                    value="active"
                    {{ $status === 'active' ? 'selected' : '' }}
                >
                    Active
                </option>

                <option
                    value="inactive"
                    {{ $status === 'inactive' ? 'selected' : '' }}
                >
                    Inactive
                </option>

            </select>


            <!-- FILTER -->

            <button
                type="submit"
                class="product-filter-button"
            >
                Filter
            </button>


            <!-- CLEAR -->

            @if ($search || $status)

                <a
                    href="{{ route('products.index') }}"
                    class="product-clear-button"
                >
                    Clear
                </a>

            @endif

        </form>


        <!-- =====================================================
             PRODUCT TABLE
        ====================================================== -->

        <div class="product-table-wrapper">

            <table class="products-table">

                <thead>

                    <tr>

                        <th>
                            Product
                        </th>

                        <th>
                            SKU
                        </th>

                        <th>
                            Category
                        </th>

                        <th>
                            Selling Price
                        </th>

                        <th>
                            Status
                        </th>

                        @if ($user->role === 'CEO/Admin')

                            <th class="product-actions-header">
                                Actions
                            </th>

                        @endif

                    </tr>

                </thead>


                <tbody>

                    @forelse ($products as $product)

                        <tr>

                            <!-- =================================================
                                 PRODUCT
                            ================================================== -->

                            <td>

                                <div class="product-name">
                                    {{ $product->name }}
                                </div>

                                @if ($product->description)

                                    <div class="product-description">
                                        {{ $product->description }}
                                    </div>

                                @endif

                            </td>


                            <!-- =================================================
                                 SKU
                            ================================================== -->

                            <td>

                                <span class="product-sku">
                                    {{ $product->sku }}
                                </span>

                            </td>


                            <!-- =================================================
                                 CATEGORY
                            ================================================== -->

                            <td>

                                @if ($product->category)

                                    {{ $product->category->name }}

                                @else

                                    <span class="product-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            <!-- =================================================
                                 SELLING PRICE
                            ================================================== -->

                            <td>

                                <span class="product-price">
                                    ₱{{ number_format((float) $product->selling_price, 2) }}
                                </span>

                            </td>


                            <!-- =================================================
                                 STATUS
                            ================================================== -->

                            <td>

                                @if ($product->is_active)

                                    <span class="product-status product-status-active">
                                        ACTIVE
                                    </span>

                                @else

                                    <span class="product-status product-status-inactive">
                                        INACTIVE
                                    </span>

                                @endif

                            </td>


                            <!-- =================================================
                                 ACTIONS
                            ================================================== -->

                            @if ($user->role === 'CEO/Admin')

                                <td>

                                    <div class="product-table-actions">

                                        <!-- RECIPE -->

                                        <a
                                            href="{{ route('recipes.edit', $product) }}"
                                            class="product-recipe-button"
                                        >

                                            <span>
                                                ≡
                                            </span>

                                            Recipe

                                        </a>


                                        <!-- EDIT -->

                                        <a
                                            href="{{ route('products.edit', $product) }}"
                                            class="product-edit-button"
                                        >

                                            <span>
                                                ✎
                                            </span>

                                            Edit

                                        </a>

                                    </div>

                                </td>

                            @endif

                        </tr>


                    @empty

                        <!-- =================================================
                             EMPTY STATE
                        ================================================== -->

                        <tr>

                            <td
                                colspan="{{ $user->role === 'CEO/Admin' ? 6 : 5 }}"
                                class="product-empty-state"
                            >

                                <div class="product-empty-icon">
                                    ▦
                                </div>

                                <div class="product-empty-title">
                                    No product records found
                                </div>

                                <div class="product-empty-description">

                                    @if ($search || $status)

                                        Try changing your search
                                        or filter.

                                    @else

                                        Your product records
                                        will appear here.

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

        @if ($products->hasPages())

            <div class="product-pagination-wrapper">

                {{ $products->links() }}

            </div>

        @endif

    </div>

</div>

@endsection


@push('styles')

<style>

/* =========================================================
   PRODUCTS PAGE

   Shared Dashboard layout controls:
   - .main
   - .topbar
   - .page-title
   - .date-box

   Do not override those here.
========================================================= */

.products-page {

    width: 100%;

}


/* =========================================================
   PAGE TITLE
   Slightly smaller for a cleaner management-system look
========================================================= */

.products-page .page-title h1 {

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

.product-stats {

    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 17px;

    margin-bottom: 22px;

}


.product-stat {

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

.product-stat::before {

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


.product-stat-label {

    color: var(--muted);

    font-size: 0.8125rem;

    line-height: 1.3;

    font-weight: 800;

    letter-spacing: 0.05rem;

}


.product-stat-value {

    margin-top: 14px;

    color: var(--dark);

    font-size: 1.875rem;

    line-height: 1;

    font-weight: 800;

}


.product-money {

    font-size: 1.375rem;

}


.product-stat-note {

    margin-top: 8px;

    color: #9d958f;

    font-size: 0.8125rem;

    line-height: 1.4;

}


.product-stat-icon {

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
   PRODUCT PANEL
========================================================= */

.products-panel {

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

.products-panel-header {

    min-height: 75px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    padding: 17px 21px;

    border-bottom: 1px solid var(--border);

}


.products-panel-title {

    color: var(--dark);

    font-size: 1rem;

    line-height: 1.3;

    font-weight: 700;

}


.products-panel-subtitle {

    margin-top: 4px;

    color: var(--muted);

    font-size: 0.8125rem;

    line-height: 1.4;

}


/* =========================================================
   ADD PRODUCT
========================================================= */

.product-add-button {

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


.product-add-button:hover {

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


.product-add-button span {

    font-size: 0.9375rem;

    line-height: 1;

}


/* =========================================================
   FILTERS
========================================================= */

.product-filters {

    display: flex;

    align-items: center;

    gap: 10px;

    padding: 15px 21px;

    background: var(--card-soft);

    border-bottom: 1px solid var(--border);

}


.product-search-wrapper {

    flex: 1;

    position: relative;

    min-width: 180px;

}


.product-search-wrapper input {

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


.product-search-wrapper input:focus {

    border-color: #d5a77d;

    box-shadow:
        0 0 0 3px
        rgba(196, 122, 58, 0.08);

}


.product-search-wrapper input::placeholder {

    color: #aaa19a;

}


.product-search-icon {

    position: absolute;

    left: 13px;

    top: 50%;

    transform: translateY(-50%);

    color: var(--muted);

    font-size: 0.9375rem;

    pointer-events: none;

}


.product-filters select {

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

.product-filter-button,
.product-clear-button {

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


.product-filter-button {

    border: none;

    background: var(--dark);

    color: white;

    transition: background-color 0.18s ease;

}


.product-filter-button:hover {

    background: var(--dark-soft);

}


.product-clear-button {

    border: 1px solid var(--border);

    background: white;

    color: var(--muted);

    transition:
        background-color 0.18s ease,
        color 0.18s ease;

}


.product-clear-button:hover {

    background: #faf7f3;

    color: var(--dark);

}


/* =========================================================
   TABLE
========================================================= */

.product-table-wrapper {

    width: 100%;

    overflow-x: auto;

}


.products-table {

    width: 100%;

    min-width: 900px;

    border-collapse: collapse;

}


/* =========================================================
   TABLE HEADER
   Matches Supplier table
========================================================= */

.products-table th {

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

.products-table td {

    padding: 13px 14px;

    color: #625951;

    border-bottom: 1px solid #f0ebe6;

    font-size: 0.8125rem;

    line-height: 1.4;

    vertical-align: middle;

}


.products-table tbody tr {

    background: white;

}


.products-table tbody tr:hover {

    background: #fdfaf7;

}


/* =========================================================
   PRODUCT INFORMATION
========================================================= */

.product-name {

    color: var(--dark);

    font-size: 0.8125rem;

    line-height: 1.4;

    font-weight: 700;

}


.product-description {

    max-width: 270px;

    margin-top: 3px;

    color: var(--muted);

    font-size: 0.75rem;

    line-height: 1.35;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;

}


.product-muted {

    color: #a39b95;

    font-size: 0.8125rem;

}


/* =========================================================
   SKU
========================================================= */

.product-sku {

    display: inline-block;

    padding: 3px 6px;

    border-radius: 5px;

    background: #f5f0eb;

    color: var(--brown);

    font-family: monospace;

    font-size: 0.6875rem;

}


/* =========================================================
   SELLING PRICE
========================================================= */

.product-price {

    color: var(--dark);

    font-size: 0.8125rem;

    font-weight: 800;

    white-space: nowrap;

}


/* =========================================================
   STATUS
========================================================= */

.product-status {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    padding: 5px 8px;

    border-radius: 7px;

    font-size: 0.6875rem;

    line-height: 1.2;

    font-weight: 800;

    white-space: nowrap;

    letter-spacing: 0.02rem;

}


.product-status-active {

    color: var(--green);

    background: var(--green-light);

}


.product-status-inactive {

    color: var(--muted);

    background: #f1eeeb;

}


/* =========================================================
   ACTIONS
========================================================= */

.product-actions-header {

    text-align: center !important;

}


.product-table-actions {

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 4px;

    white-space: nowrap;

}


/* =========================================================
   RECIPE BUTTON
========================================================= */

.product-recipe-button {

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


.product-recipe-button:hover {

    background: #f5eee7;

    border-color: #cdb49f;

    color: #68482f;

}


.product-recipe-button span {

    font-size: 0.75rem;

    line-height: 1;

}


/* =========================================================
   EDIT BUTTON
========================================================= */

.product-edit-button {

    height: 28px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 4px;

    padding: 0 7px;

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


.product-edit-button:hover {

    background: #faf5ef;

    border-color: #cdb49f;

    color: #68482f;

}


.product-edit-button span {

    font-size: 0.75rem;

    line-height: 1;

}


/* =========================================================
   EMPTY STATE
========================================================= */

.product-empty-state {

    padding: 65px 20px !important;

    text-align: center !important;

}


.product-empty-icon {

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


.product-empty-title {

    color: var(--dark);

    font-size: 0.875rem;

    font-weight: 700;

}


.product-empty-description {

    margin-top: 5px;

    color: var(--muted);

    font-size: 0.8125rem;

    line-height: 1.4;

}


/* =========================================================
   PAGINATION
========================================================= */

.product-pagination-wrapper {

    padding: 15px 21px;

    border-top: 1px solid var(--border);

}


.product-pagination-wrapper nav {

    display: flex;

    justify-content: center;

}


.product-pagination-wrapper svg {

    width: 15px;

    height: 15px;

}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1200px) {

    .product-stats {

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

    }

}


@media (max-width: 700px) {

    .product-stats {

        grid-template-columns: 1fr;

    }


    .products-panel-header {

        align-items: flex-start;

        flex-direction: column;

    }


    .product-add-button {

        width: 100%;

    }


    .product-filters {

        align-items: stretch;

        flex-direction: column;

    }


    .product-search-wrapper {

        width: 100%;

    }


    .product-filters select,
    .product-filter-button,
    .product-clear-button {

        width: 100%;

    }


    .product-table-actions {

        justify-content: flex-start;

    }

}

</style>

@endpush