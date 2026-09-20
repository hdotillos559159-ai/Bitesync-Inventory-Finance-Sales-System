@extends('layouts.app')

@section('title', 'BiteSync | Edit Product')

@section('content')

<div class="product-form-page">

    <!-- =========================================================
         TOPBAR
    ========================================================== -->

    <div class="topbar">

        <div class="page-title">

            <small>
                Product Management
            </small>

            <h1>
                Edit Product
            </h1>

            <p>
                Update the information for this BiteSync menu product.
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
    ========================================================== -->

    <div class="product-breadcrumb">

        <a href="{{ route('products.index') }}">
            Products
        </a>

        <span>
            /
        </span>

        <strong>
            Edit Product
        </strong>

    </div>


    <!-- =========================================================
         VALIDATION ERRORS
    ========================================================== -->

    @if ($errors->any())

        <div class="product-error-alert">

            <div class="product-error-title">
                Please check the following:
            </div>

            <ul>

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <!-- =========================================================
         FORM
    ========================================================== -->

    <form
        method="POST"
        action="{{ route('products.update', $product) }}"
    >

        @csrf

        @method('PUT')


        <!-- =====================================================
             PRODUCT INFORMATION
        ====================================================== -->

        <div class="product-form-panel">

            <div class="product-form-panel-header">

                <div>

                    <div class="product-form-panel-title">
                        Product Information
                    </div>

                    <div class="product-form-panel-subtitle">
                        Update the basic information for this menu product.
                    </div>

                </div>

            </div>


            <div class="product-form-body">

                <div class="product-form-grid">


                    <!-- PRODUCT NAME -->

                    <div class="product-field">

                        <label for="name">
                            Product Name
                            <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $product->name) }}"
                            placeholder="e.g. Classic Burger"
                            maxlength="255"
                            required
                        >

                    </div>


                    <!-- SKU -->

                    <div class="product-field">

                        <label for="sku">
                            SKU
                            <span>*</span>
                        </label>

                        <input
                            type="text"
                            id="sku"
                            name="sku"
                            value="{{ old('sku', $product->sku) }}"
                            placeholder="e.g. PROD-001"
                            maxlength="100"
                            required
                        >

                        <small>
                            SKU must be unique among products.
                        </small>

                    </div>


                    <!-- CATEGORY -->

                    <div class="product-field">

                        <label for="category_id">
                            Category
                        </label>

                        <select
                            id="category_id"
                            name="category_id"
                        >

                            <option value="">
                                Select category
                            </option>

                            @foreach ($categories as $category)

                                <option
                                    value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}
                                >
                                    {{ $category->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- SELLING PRICE -->

                    <div class="product-field">

                        <label for="selling_price">
                            Selling Price
                            <span>*</span>
                        </label>

                        <div class="price-input">

                            <span>
                                ₱
                            </span>

                            <input
                                type="number"
                                id="selling_price"
                                name="selling_price"
                                value="{{ old('selling_price', $product->selling_price) }}"
                                placeholder="0.00"
                                min="0"
                                step="0.01"
                                required
                            >

                        </div>

                    </div>


                    <!-- STATUS -->

                    <div class="product-field">

                        <label for="is_active">
                            Product Status
                            <span>*</span>
                        </label>

                        <select
                            id="is_active"
                            name="is_active"
                            required
                        >

                            <option
                                value="1"
                                {{ old('is_active', $product->is_active ? '1' : '0') === '1' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="0"
                                {{ old('is_active', $product->is_active ? '1' : '0') === '0' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>

                        </select>

                        <small>
                            Inactive products remain in the system but are not currently available.
                        </small>

                    </div>


                    <!-- CREATED DATE -->

                    <div class="product-field">

                        <label>
                            Created
                        </label>

                        <div class="product-readonly-field">

                            {{ $product->created_at?->format('F d, Y h:i A') ?? '—' }}

                        </div>

                    </div>


                    <!-- DESCRIPTION -->

                    <div class="product-field product-field-full">

                        <label for="description">
                            Description
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="5"
                            placeholder="Enter a short description of the product..."
                        >{{ old('description', $product->description) }}</textarea>

                        <small>
                            Optional. This can describe the product for internal menu records.
                        </small>

                    </div>

                </div>

            </div>

        </div>


        <!-- =====================================================
             PRODUCT / INVENTORY NOTE
        ====================================================== -->

        <div class="product-information-note">

            <div class="product-note-icon">
                i
            </div>

            <div>

                <div class="product-note-title">
                    Product and Inventory
                </div>

                <div class="product-note-text">
                    Changing the product information does not change inventory
                    quantities. Ingredient usage will be managed through the
                    product's recipe and inventory records.
                </div>

            </div>

        </div>


        <!-- =====================================================
             ACTIONS
        ====================================================== -->

        <div class="product-form-actions">

            <a
                href="{{ route('products.index') }}"
                class="product-cancel-button"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="product-save-button"
            >

                <span>
                    ✓
                </span>

                Save Changes

            </button>

        </div>

    </form>

</div>

@endsection


@push('styles')

<style>

/* =========================================================
   PRODUCT FORM PAGE
========================================================= */

.product-form-page {
    width: 100%;
}


/* =========================================================
   PAGE TITLE
========================================================= */

.product-form-page .page-title h1 {
    font-size: clamp(1.75rem, 2.2vw, 2rem);
    line-height: 1.15;
    letter-spacing: -0.045rem;
    font-weight: 700;
}


/* =========================================================
   BREADCRUMB
========================================================= */

.product-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 17px;
    color: var(--muted);
    font-size: 10px;
    font-weight: 600;
}

.product-breadcrumb a {
    color: var(--orange);
    text-decoration: none;
    font-weight: 600;
}

.product-breadcrumb a:hover {
    color: var(--orange-dark);
}

.product-breadcrumb strong {
    color: var(--dark);
    font-weight: 600;
}


/* =========================================================
   ERROR ALERT
========================================================= */

.product-error-alert {
    margin-bottom: 18px;
    padding: 14px 17px;
    border: 1px solid #e8c9c4;
    border-radius: 11px;
    background: #fff7f6;
    color: #8b4c45;
    font-size: 12px;
    line-height: 1.5;
    font-weight: 400;
}

.product-error-title {
    font-size: 12px;
    font-weight: 700;
}

.product-error-alert ul {
    margin: 7px 0 0;
    padding-left: 17px;
}

.product-error-alert li {
    margin-bottom: 3px;
    font-size: 12px;
    font-weight: 400;
}


/* =========================================================
   FORM PANEL
========================================================= */

.product-form-panel {
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

.product-form-panel-header {
    min-height: 75px;
    display: flex;
    align-items: center;
    padding: 17px 21px;
    border-bottom: 1px solid var(--border);
}

.product-form-panel-title {
    color: var(--dark);
    font-size: 19px;
    line-height: 1.3;
    font-weight: 700;
}

.product-form-panel-subtitle {
    margin-top: 4px;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.45;
    font-weight: 400;
}


/* =========================================================
   FORM BODY
========================================================= */

.product-form-body {
    padding: 23px 21px 25px;
}

.product-form-grid {
    display: grid;
    grid-template-columns:
        repeat(2, minmax(0, 1fr));
    gap: 19px 20px;
}

.product-field {
    min-width: 0;
}

.product-field-full {
    grid-column: 1 / -1;
}


/* =========================================================
   LABELS
========================================================= */

.product-field label {
    display: block;
    margin-bottom: 7px;
    color: var(--dark);
    font-size: 13px;
    line-height: 1.35;
    font-weight: 600;
}

.product-field label span {
    color: var(--orange);
    font-weight: 600;
}


/* =========================================================
   INPUTS
========================================================= */

.product-field input,
.product-field select,
.product-field textarea {
    width: 100%;
    box-sizing: border-box;
    border: 1px solid var(--border);
    border-radius: 9px;
    background: white;
    color: var(--text);
    font-family: inherit;
    font-size: 13px;
    line-height: 1.4;
    font-weight: 400;
    outline: none;
    transition:
        border-color 0.18s ease,
        box-shadow 0.18s ease;
}

.product-field input,
.product-field select {
    height: 40px;
    padding: 0 12px;
}

.product-field textarea {
    min-height: 115px;
    padding: 11px 12px;
    resize: vertical;
    line-height: 1.5;
}

.product-field input:focus,
.product-field select:focus,
.product-field textarea:focus {
    border-color: #d5a77d;
    box-shadow:
        0 0 0 3px
        rgba(196, 122, 58, 0.08);
}

.product-field input::placeholder,
.product-field textarea::placeholder {
    color: #aaa29c;
    font-weight: 400;
}

.product-field select {
    cursor: pointer;
}


/* =========================================================
   FIELD HELP TEXT
========================================================= */

.product-field small {
    display: block;
    margin-top: 5px;
    color: #9d958f;
    font-size: 12px;
    line-height: 1.45;
    font-weight: 400;
}


/* =========================================================
   PRICE INPUT
========================================================= */

.price-input {
    position: relative;
}

.price-input > span {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--muted);
    font-size: 13px;
    line-height: 1;
    font-weight: 600;
    pointer-events: none;
}

.price-input input {
    padding-left: 29px;
}


/* =========================================================
   READONLY FIELD
========================================================= */

.product-readonly-field {
    width: 100%;
    min-height: 40px;
    display: flex;
    align-items: center;
    box-sizing: border-box;
    padding: 0 12px;
    border: 1px solid var(--border);
    border-radius: 9px;
    background: #faf8f5;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.4;
    font-weight: 400;
}


/* =========================================================
   INFORMATION NOTE
========================================================= */

.product-information-note {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-top: 17px;
    padding: 14px 16px;
    border: 1px solid #eadbcf;
    border-radius: 11px;
    background: #fdf8f3;
}

.product-note-icon {
    width: 25px;
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 8px;
    background: var(--orange-light);
    color: var(--orange);
    font-size: 11px;
    line-height: 1;
    font-weight: 700;
}

.product-note-title {
    color: var(--dark);
    font-size: 12px;
    line-height: 1.35;
    font-weight: 700;
}

.product-note-text {
    max-width: 850px;
    margin-top: 4px;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.5;
    font-weight: 400;
}


/* =========================================================
   FORM ACTIONS
========================================================= */

.product-form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 18px;
}

.product-cancel-button,
.product-save-button {
    min-height: 39px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 0 15px;
    border-radius: 9px;
    font-family: inherit;
    font-size: 13px;
    line-height: 1;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
}


/* =========================================================
   CANCEL
========================================================= */

.product-cancel-button {
    border:
        1px solid var(--border);

    background: white;

    color: var(--muted);

    transition:
        background-color 0.18s ease,
        color 0.18s ease;
}

.product-cancel-button:hover {
    background:
        #faf7f3;

    color:
        var(--dark);
}


/* =========================================================
   SAVE
========================================================= */

.product-save-button {
    border: none;

    background:
        linear-gradient(
            135deg,
            var(--orange),
            var(--orange-dark)
        );

    color: white;

    box-shadow:
        0 5px 13px
        rgba(168, 95, 40, 0.16);

    transition:
        background 0.18s ease,
        box-shadow 0.18s ease;
}

.product-save-button:hover {
    background:
        linear-gradient(
            135deg,
            var(--orange-dark),
            var(--orange-dark)
        );

    box-shadow:
        0 6px 14px
        rgba(168, 95, 40, 0.18);
}

.product-save-button span {
    font-size: 12px;
    line-height: 1;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 850px) {

    .product-form-grid {
        grid-template-columns: 1fr;
    }

    .product-field-full {
        grid-column: auto;
    }

}


@media (max-width: 600px) {

    .product-form-body {
        padding:
            19px 16px 21px;
    }

    .product-form-actions {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .product-cancel-button,
    .product-save-button {
        width: 100%;
    }

}

</style>

@endpush