@extends('layouts.app')

@section('title', 'BiteSync | Edit Product')

@section('content')

<div class="product-form-page">

    <!-- =========================================================
         TOP BAR
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

            <span>
                {{ now()->format('F d, Y') }}
            </span>

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

            <div class="alert-icon">
                !
            </div>

            <div>

                <div class="product-error-title">
                    Please check the form.
                </div>

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


                <div class="product-form-panel-icon">
                    ▦
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
                            rows="4"
                            maxlength="2000"
                            placeholder="Enter a short description of the product..."
                        >{{ old('description', $product->description) }}</textarea>

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

/* ================================================================
   PAGE
================================================================ */

.product-form-page {

    width: 100%;

    max-width: 1120px;

    margin: 0 auto;

    padding-bottom: 30px;
}


/* ================================================================
   TOP BAR
================================================================ */

.product-form-page .topbar {

    display: flex;

    align-items: flex-start;

    justify-content: space-between;

    gap: 20px;

    margin-bottom: 14px;
}


.product-form-page .page-title small {

    display: block;

    margin-bottom: 5px;

    color: #a9825b;

    font-size: 10px;

    font-weight: 700;

    letter-spacing: 0.12em;

    line-height: 1.3;

    text-transform: uppercase;
}


.product-form-page .page-title h1 {

    margin: 0;

    color: var(--dark);

    font-size: 29px;

    font-weight: 700;

    line-height: 1.15;

    letter-spacing: -0.045rem;
}


.product-form-page .page-title p {

    margin: 6px 0 0;

    color: var(--muted);

    font-size: 12px;

    font-weight: 400;

    line-height: 1.5;
}


/* ================================================================
   DATE BOX
================================================================ */

.product-form-page .date-box {

    min-width: 145px;

    display: inline-flex;

    align-items: center;

    gap: 7px;

    padding: 9px 12px;

    border: 1px solid var(--border);

    border-radius: 10px;

    background: #ffffff;

    color: var(--muted);

    font-size: 12px;

    font-weight: 400;

    white-space: nowrap;
}


.product-form-page .date-icon {

    width: 30px;

    height: 30px;

    display: flex;

    align-items: center;

    justify-content: center;

    flex-shrink: 0;

    border-radius: 8px;

    background: var(--orange-light);

    color: var(--orange);

    font-size: 14px;
}


/* ================================================================
   BREADCRUMB
================================================================ */

.product-breadcrumb {

    display: flex;

    align-items: center;

    gap: 8px;

    margin-bottom: 17px;

    color: #96877b;

    font-size: 11px;

    font-weight: 600;
}


.product-breadcrumb a {

    color: #a16e42;

    font-weight: 600;

    text-decoration: none;

    transition: color 0.18s ease;
}


.product-breadcrumb a:hover {

    color: #7d4e29;
}


.product-breadcrumb span {

    color: #c2b5aa;
}


.product-breadcrumb strong {

    color: #6f6259;

    font-weight: 600;
}


/* ================================================================
   ERROR ALERT
================================================================ */

.product-error-alert {

    display: flex;

    align-items: flex-start;

    gap: 11px;

    margin-bottom: 16px;

    padding: 11px 13px;

    border: 1px solid #efd4cf;

    border-radius: 10px;

    background: #fff7f5;

    color: #7d433b;

    font-size: 12px;

    line-height: 1.5;
}


.product-error-alert .alert-icon {

    width: 24px;

    height: 24px;

    flex-shrink: 0;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: #f3d4cf;

    color: #8a4339;

    font-size: 11px;

    font-weight: 700;
}


.product-error-title {

    margin-bottom: 5px;

    font-size: 12px;

    font-weight: 700;
}


.product-error-alert ul {

    margin: 0;

    padding-left: 17px;

    line-height: 1.55;
}


.product-error-alert li {

    margin-bottom: 2px;

    font-size: 12px;

    font-weight: 400;
}


/* ================================================================
   FORM PANEL
================================================================ */

.product-form-panel {

    overflow: hidden;

    border: 1px solid var(--border);

    border-radius: 15px;

    background: #ffffff;

    box-shadow:
        0 5px 18px
        rgba(43, 31, 23, 0.035);
}


/* ================================================================
   PANEL HEADER
================================================================ */

.product-form-panel-header {

    min-height: 65px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    padding: 14px 19px;

    border-bottom: 1px solid var(--border);

    background: #ffffff;
}


.product-form-panel-title {

    color: var(--dark);

    font-size: 17px;

    font-weight: 700;

    line-height: 1.3;
}


.product-form-panel-subtitle {

    margin-top: 3px;

    color: var(--muted);

    font-size: 11px;

    font-weight: 400;

    line-height: 1.45;
}


.product-form-panel-icon {

    width: 30px;

    height: 30px;

    flex-shrink: 0;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 8px;

    background: var(--orange-light);

    color: var(--orange);

    font-size: 13px;

    font-weight: 700;
}


/* ================================================================
   FORM BODY
================================================================ */

.product-form-body {

    padding: 18px;
}


.product-form-grid {

    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 17px 15px;
}


.product-field {

    min-width: 0;
}


.product-field-full {

    grid-column: 1 / -1;
}


/* ================================================================
   LABELS
================================================================ */

.product-field label {

    display: block;

    margin-bottom: 6px;

    color: #4c3c31;

    font-size: 11px;

    font-weight: 600;

    line-height: 1.35;
}


.product-field label span {

    color: #b65f45;

    font-weight: 600;
}


/* ================================================================
   INPUTS
================================================================ */

.product-field input,
.product-field select,
.product-field textarea {

    width: 100%;

    box-sizing: border-box;

    border: 1px solid #ded4cb;

    border-radius: 8px;

    background: #ffffff;

    color: var(--text);

    font-family: inherit;

    font-size: 12px;

    font-weight: 400;

    line-height: 1.4;

    outline: none;

    transition:
        border-color 0.18s ease,
        box-shadow 0.18s ease;
}


.product-field input,
.product-field select {

    height: 39px;

    padding: 0 11px;
}


.product-field textarea {

    min-height: 105px;

    padding: 10px 11px;

    resize: vertical;

    line-height: 1.5;
}


.product-field input::placeholder,
.product-field textarea::placeholder {

    color: #b8aaa0;

    font-weight: 400;
}


.product-field input:focus,
.product-field select:focus,
.product-field textarea:focus {

    border-color: #d2a47b;

    box-shadow:
        0 0 0 3px
        rgba(196, 122, 58, 0.08);
}


.product-field select {

    cursor: pointer;
}


/* ================================================================
   PRICE INPUT
================================================================ */

.price-input {

    position: relative;
}


.price-input > span {

    position: absolute;

    top: 50%;

    left: 11px;

    transform: translateY(-50%);

    color: #8c684b;

    font-size: 12px;

    font-weight: 600;

    pointer-events: none;
}


.price-input input {

    padding-left: 27px;
}


/* ================================================================
   READONLY CREATED FIELD
================================================================ */

.product-readonly-field {

    width: 100%;

    min-height: 39px;

    display: flex;

    align-items: center;

    box-sizing: border-box;

    padding: 0 11px;

    border: 1px solid #ded4cb;

    border-radius: 8px;

    background: #faf8f5;

    color: var(--muted);

    font-size: 12px;

    font-weight: 400;

    line-height: 1.4;
}


/* ================================================================
   INFORMATION NOTE
================================================================ */

.product-information-note {

    display: flex;

    align-items: flex-start;

    gap: 10px;

    margin-top: 17px;

    padding: 11px 12px;

    border: 1px solid #e9dfd5;

    border-radius: 9px;

    background: #fbf8f4;
}


.product-note-icon {

    width: 23px;

    height: 23px;

    flex-shrink: 0;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: #eadbc9;

    color: #855b38;

    font-size: 11px;

    font-weight: 700;
}


.product-note-title {

    margin-bottom: 3px;

    color: #594536;

    font-size: 11px;

    font-weight: 700;

    line-height: 1.35;
}


.product-note-text {

    max-width: 850px;

    color: #95867b;

    font-size: 11px;

    font-weight: 400;

    line-height: 1.45;
}


/* ================================================================
   ACTIONS
================================================================ */

.product-form-actions {

    display: flex;

    align-items: center;

    justify-content: flex-end;

    gap: 9px;

    margin-top: 17px;

    padding-top: 0;

    padding-bottom: 4px;
}


.product-cancel-button,
.product-save-button {

    min-height: 37px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 7px;

    padding: 0 14px;

    border-radius: 8px;

    font-family: inherit;

    font-size: 11px;

    font-weight: 700;

    text-decoration: none;

    cursor: pointer;
}


/* ================================================================
   CANCEL
================================================================ */

.product-cancel-button {

    border: 1px solid var(--border);

    background: #ffffff;

    color: var(--muted);

    transition:
        background-color 0.18s ease,
        border-color 0.18s ease,
        color 0.18s ease;
}


.product-cancel-button:hover {

    border-color: #d4c6ba;

    background: #faf7f4;

    color: var(--dark);
}


/* ================================================================
   SAVE
================================================================ */

.product-save-button {

    border: none;

    background:
        linear-gradient(
            135deg,
            var(--orange),
            var(--orange-dark)
        );

    color: #ffffff;

    box-shadow:
        0 4px 10px
        rgba(145, 97, 55, 0.14);

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
        0 5px 12px
        rgba(145, 97, 55, 0.17);
}


.product-save-button span {

    font-size: 11px;

    font-weight: 400;
}


/* ================================================================
   TABLET
================================================================ */

@media (max-width: 850px) {

    .product-form-grid {

        grid-template-columns: 1fr;
    }


    .product-field-full {

        grid-column: auto;
    }

}


/* ================================================================
   MOBILE
================================================================ */

@media (max-width: 700px) {

    .product-form-page {

        padding-bottom: 25px;
    }


    .product-form-page .topbar {

        flex-direction: column;

        align-items: flex-start;
    }


    .product-form-page .date-box {

        align-self: flex-start;
    }


    .product-form-body {

        padding: 15px;
    }


    .product-form-panel-header {

        padding: 14px 15px;
    }


    .product-form-actions {

        justify-content: stretch;
    }


    .product-cancel-button,
    .product-save-button {

        flex: 1;
    }

}


/* ================================================================
   SMALL MOBILE
================================================================ */

@media (max-width: 480px) {

    .product-form-page .page-title h1 {

        font-size: 28px;

        font-weight: 700;
    }


    .product-form-panel-title {

        font-size: 17px;

        font-weight: 700;
    }


    .product-form-panel-subtitle {

        max-width: 220px;

        font-size: 11px;

        font-weight: 400;
    }


    .product-form-actions {

        flex-direction: column-reverse;
    }


    .product-cancel-button,
    .product-save-button {

        width: 100%;
    }

}

</style>

@endpush