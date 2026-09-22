@extends('layouts.app')

@section('title', 'BiteSync | Add Product')

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
            Add Product
        </h1>

        <p>
            Create a new product record for the BiteSync menu.
        </p>

    </div>


    <!-- DATE -->

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
        Add Product
    </strong>

</div>


<!-- =========================================================
     VALIDATION ERRORS
========================================================== -->

@if ($errors->any())

    <div class="product-error-alert">

        <div class="product-error-icon">
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
    action="{{ route('products.store') }}"
    enctype="multipart/form-data"
>

    @csrf


    <!-- =====================================================
         FORM CARD
    ====================================================== -->

    <div class="product-form-card">


        <!-- =================================================
             CARD HEADER
        ================================================== -->

        <div class="product-form-card-header">

            <div>

                <h2>
                    Product Information
                </h2>

                <p>
                    Enter the details needed to create this menu product.
                </p>

            </div>

            <div class="product-required-note">

                <span>*</span>

                Required fields

            </div>

        </div>


        <!-- =================================================
             CARD CONTENT
        ================================================== -->

        <div class="product-form-content">


            <!-- =============================================
                 LEFT CONTENT
            ============================================== -->

            <div class="product-main-fields">


                <!-- PRODUCT NAME -->

                <div class="product-field">

                    <label for="name">

                        Product Name

                        <span>
                            *
                        </span>

                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="e.g. Classic Burger"
                        maxlength="255"
                        required
                    >

                </div>


                <!-- SKU + CATEGORY -->

                <div class="product-two-column">


                    <!-- SKU -->

                    <div class="product-field">

                        <label for="sku">

                            SKU

                            <span>
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            id="sku"
                            name="sku"
                            value="{{ old('sku') }}"
                            placeholder="e.g. PROD-001"
                            maxlength="100"
                            required
                        >

                    </div>


                    <!-- CATEGORY -->

                    <div class="product-field">

                        <label for="category_id">
                            Product Category
                        </label>

                        <select
                            id="category_id"
                            name="category_id"
                        >

                            <option value="">
                                Select product category
                            </option>

                            @foreach ($categories as $category)

                                <option
                                    value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}
                                >
                                    {{ $category->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                <!-- SELLING PRICE -->

                <div class="product-field">

                    <label for="selling_price">

                        Selling Price

                        <span>
                            *
                        </span>

                    </label>

                    <div class="product-price-input">

                        <span>
                            ₱
                        </span>

                        <input
                            type="number"
                            id="selling_price"
                            name="selling_price"
                            value="{{ old('selling_price') }}"
                            placeholder="0.00"
                            min="0"
                            step="0.01"
                            required
                        >

                    </div>

                </div>


                <!-- DESCRIPTION -->

                <div class="product-field">

                    <label for="description">
                        Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        placeholder="Enter a short description of the product..."
                    >{{ old('description') }}</textarea>

                </div>

            </div>


            <!-- =============================================
                 RIGHT IMAGE PANEL
            ============================================== -->

            <div class="product-image-section">

                <div class="product-section-label">
                    Product Image
                </div>

                <div class="product-section-description">
                    Add a product photo for the Sales POS.
                </div>


                <!-- IMAGE PREVIEW -->

                <div
                    class="product-image-preview"
                    id="productImagePreview"
                >

                    <div class="product-image-placeholder">

                        <div class="product-placeholder-icon">
                            ▧
                        </div>

                        <strong>
                            No image
                        </strong>

                        <span>
                            Preview
                        </span>

                    </div>

                </div>


                <!-- IMAGE INPUT -->

                <label
                    for="image"
                    class="product-image-button"
                >

                    <span>
                        +
                    </span>

                    Choose Image

                </label>

                <input
                    type="file"
                    id="image"
                    name="image"
                    accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                    hidden
                >


                <!-- FILE NAME -->

                <div
                    class="product-image-file-name"
                    id="productImageFileName"
                >
                    No file selected
                </div>


                <!-- IMAGE HELP -->

                <div class="product-image-help">

                    JPG, PNG or WEBP

                    <span>
                        •
                    </span>

                    Maximum 2 MB

                </div>


                <!-- IMAGE NOTE -->

                <div class="product-image-note">

                    <span>
                        i
                    </span>

                    <p>
                        A product image helps staff identify items faster when creating a sale.
                    </p>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         INFORMATION NOTE
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
                A product represents a menu item, while inventory items
                represent its ingredients or stock materials. Recipe
                ingredients can be assigned after the product is created.
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
                +
            </span>

            Add Product

        </button>

    </div>

</form>

</div>

@endsection


@push('styles')

<style>

/* =========================================================
   PAGE
========================================================= */

.product-form-page {
    width: 100%;
    max-width: 1120px;
    margin: 0 auto;
    padding-bottom: 30px;
    box-sizing: border-box;
}


/* =========================================================
   TOPBAR
========================================================= */

.product-form-page .topbar {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 25px;
    margin-bottom: 14px;
}

.product-form-page .page-title {
    min-width: 0;
}

.product-form-page .page-title small {
    display: block;
    margin-bottom: 5px;

    color: #a9825b;

    font-size: 10px;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
}

.product-form-page .page-title h1 {
    margin: 0;

    color: #2d241d;

    font-size: clamp(1.6rem, 2vw, 1.9rem);
    line-height: 1.15;

    font-weight: 700;
    letter-spacing: -.04rem;
}

.product-form-page .page-title p {
    margin: 6px 0 0;

    color: #8a8179;

    font-size: 12px;
    line-height: 1.5;

    font-weight: 400;
}


/* =========================================================
   DATE BOX
========================================================= */

.product-form-page .date-box {
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

.product-form-page .date-icon {
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
========================================================= */

.product-breadcrumb {
    display: flex;
    align-items: center;

    gap: 7px;

    margin-bottom: 17px;

    color: #9b9188;

    font-size: 10px;
    line-height: 1.3;
}

.product-breadcrumb a {
    color: #9a7048;

    font-weight: 600;

    text-decoration: none;
}

.product-breadcrumb a:hover {
    text-decoration: underline;
}

.product-breadcrumb span {
    color: #c2b5aa;
}

.product-breadcrumb strong {
    color: #655a51;
    font-weight: 700;
}


/* =========================================================
   ERROR ALERT
========================================================= */

.product-error-alert {
    display: flex;
    align-items: flex-start;

    gap: 9px;

    margin-bottom: 17px;
    padding: 11px;

    border: 1px solid #efd0c8;
    border-radius: 10px;

    background: #fff7f5;
    color: #7c3f35;

    font-size: 11px;
    line-height: 1.45;

    box-sizing: border-box;
}

.product-error-icon {
    display: flex;
    align-items: center;
    justify-content: center;

    flex: 0 0 22px;

    width: 22px;
    height: 22px;

    border-radius: 50%;

    background: #e9a08e;
    color: #ffffff;

    font-size: 11px;
    font-weight: 700;
}

.product-error-title {
    margin-bottom: 3px;

    font-weight: 700;
}

.product-error-alert ul {
    margin: 0;
    padding-left: 16px;
}

.product-error-alert li {
    margin: 2px 0;
}


/* =========================================================
   MAIN FORM CARD
========================================================= */

.product-form-card {
    width: 100%;

    overflow: hidden;

    border: 1px solid #e8e0d8;
    border-radius: 15px;

    background: #ffffff;

    box-shadow:
        0 4px 16px rgba(67, 52, 38, .045);

    box-sizing: border-box;
}


/* =========================================================
   CARD HEADER
========================================================= */

.product-form-card-header {
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

.product-form-card-header h2 {
    margin: 0;

    color: #342a22;

    font-size: 17px;
    line-height: 1.2;
    font-weight: 700;
}

.product-form-card-header p {
    margin: 3px 0 0;

    color: #938a82;

    font-size: 11px;
    line-height: 1.35;
}

.product-required-note {
    flex-shrink: 0;

    color: #9a918b;

    font-size: 10px;
    font-weight: 500;
}

.product-required-note span {
    color: #b65f45;
    font-weight: 700;
}


/* =========================================================
   FORM CONTENT
========================================================= */

.product-form-content {
    display: grid;

    grid-template-columns:
        minmax(0, 1fr)
        275px;

    gap: 28px;

    padding: 18px;

    box-sizing: border-box;
}


/* =========================================================
   LEFT FIELDS
========================================================= */

.product-main-fields {
    min-width: 0;
}

.product-field {
    min-width: 0;
    margin-bottom: 14px;
}

.product-field:last-child {
    margin-bottom: 0;
}

.product-two-column {
    display: grid;

    grid-template-columns:
        minmax(0, 1fr)
        minmax(0, 1fr);

    gap: 13px;
}


/* =========================================================
   LABELS
========================================================= */

.product-field > label,
.product-section-label {
    display: block;

    margin-bottom: 6px;

    color: #51463d;

    font-size: 11px;
    line-height: 1.2;
    font-weight: 700;
}

.product-field > label span {
    color: #b65d3c;
}


/* =========================================================
   INPUTS
========================================================= */

.product-field input,
.product-field select,
.product-field textarea {
    width: 100%;

    box-sizing: border-box;

    border: 1px solid #ddd5cd;
    border-radius: 8px;

    background: #ffffff;
    color: #3d342d;

    font-family: inherit;
    font-size: 12px;

    outline: none;

    transition:
        border-color .18s ease,
        box-shadow .18s ease,
        background .18s ease;
}

.product-field input,
.product-field select {
    height: 39px;
    padding: 0 11px;
}

.product-field textarea {
    min-height: 92px;

    padding: 10px 11px;

    resize: vertical;

    line-height: 1.45;
}

.product-field input::placeholder,
.product-field textarea::placeholder {
    color: #b2aaa3;
}

.product-field input:focus,
.product-field select:focus,
.product-field textarea:focus {
    border-color: #c99b6b;

    box-shadow:
        0 0 0 3px
        rgba(201, 155, 107, .12);
}

.product-field select {
    cursor: pointer;
}


/* =========================================================
   PRICE
========================================================= */

.product-price-input {
    position: relative;
}

.product-price-input > span {
    position: absolute;

    top: 50%;
    left: 11px;

    z-index: 2;

    transform: translateY(-50%);

    color: #8c684b;

    font-size: 12px;
    line-height: 1;
    font-weight: 600;

    pointer-events: none;
}

.product-price-input input {
    padding-left: 27px;
}


/* =========================================================
   IMAGE SECTION
========================================================= */

.product-image-section {
    min-width: 0;

    padding-left: 25px;

    border-left: 1px solid #eee8e2;

    box-sizing: border-box;
}

.product-section-label {
    margin-bottom: 4px;
}

.product-section-description {
    margin-bottom: 15px;

    color: #8a8179;

    font-size: 10px;
    line-height: 1.45;
}


/* =========================================================
   IMAGE PREVIEW
========================================================= */

.product-image-preview {
    width: 155px;
    height: 155px;

    margin-bottom: 12px;

    overflow: hidden;

    border: 1px solid #e5ddd6;
    border-radius: 11px;

    background: #f7f4f1;

    box-sizing: border-box;
}

.product-image-preview img {
    display: block;

    width: 100%;
    height: 100%;

    object-fit: cover;
}


/* =========================================================
   IMAGE PLACEHOLDER
========================================================= */

.product-image-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    width: 100%;
    height: 100%;

    text-align: center;
}

.product-placeholder-icon {
    display: flex;
    align-items: center;
    justify-content: center;

    width: 36px;
    height: 36px;

    margin-bottom: 8px;

    border-radius: 9px;

    background: #eee9e4;
    color: #a99e96;

    font-size: 19px;
}

.product-image-placeholder strong {
    color: #675a51;

    font-size: 11px;
    line-height: 1.3;
    font-weight: 700;
}

.product-image-placeholder span {
    margin-top: 3px;

    color: #aaa19b;

    font-size: 9px;
}


/* =========================================================
   IMAGE BUTTON
========================================================= */

.product-image-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 5px;

    min-height: 35px;

    padding: 0 12px;

    border: 1px solid #dcd3cb;
    border-radius: 8px;

    background: #ffffff;
    color: #5d5048;

    font-family: inherit;

    font-size: 10px;
    line-height: 1;
    font-weight: 700;

    cursor: pointer;

    transition:
        border-color .18s ease,
        background .18s ease,
        color .18s ease;

    box-sizing: border-box;
}

.product-image-button:hover {
    border-color: #d1a37b;
    background: #fdf9f5;
    color: #966f43;
}

.product-image-button span {
    color: #a87542;
    font-size: 13px;
}


/* =========================================================
   FILE NAME
========================================================= */

.product-image-file-name {
    max-width: 230px;

    margin-top: 6px;

    overflow: hidden;

    color: #918780;

    font-size: 9px;
    line-height: 1.4;

    text-overflow: ellipsis;
    white-space: nowrap;
}


/* =========================================================
   IMAGE HELP
========================================================= */

.product-image-help {
    margin-top: 5px;

    color: #9b928c;

    font-size: 9px;
    line-height: 1.4;
}

.product-image-help span {
    padding: 0 3px;

    color: #c2b7af;
}


/* =========================================================
   IMAGE NOTE
========================================================= */

.product-image-note {
    display: flex;
    align-items: flex-start;

    gap: 7px;

    max-width: 230px;

    margin-top: 13px;

    padding: 9px;

    border: 1px solid #eee4dc;
    border-radius: 8px;

    background: #fcf9f6;

    box-sizing: border-box;
}

.product-image-note > span {
    display: flex;
    align-items: center;
    justify-content: center;

    flex: 0 0 auto;

    width: 17px;
    height: 17px;

    border-radius: 5px;

    background: #fbf2e8;
    color: #a66f3e;

    font-size: 8px;
    font-weight: 700;
}

.product-image-note p {
    margin: 0;

    color: #8e8279;

    font-size: 9px;
    line-height: 1.45;
}


/* =========================================================
   INFORMATION NOTE
========================================================= */

.product-information-note {
    display: flex;
    align-items: flex-start;

    gap: 10px;

    width: 100%;

    margin-top: 17px;

    padding: 12px 14px;

    border: 1px solid #eadfce;
    border-radius: 10px;

    background: #fffaf3;

    box-sizing: border-box;
}

.product-note-icon {
    display: flex;
    align-items: center;
    justify-content: center;

    flex: 0 0 22px;

    width: 22px;
    height: 22px;

    border-radius: 50%;

    background: #ead6bc;
    color: #805b35;

    font-size: 11px;
    font-weight: 700;
}

.product-note-title {
    color: #57483b;

    font-size: 11px;
    line-height: 1.3;
    font-weight: 700;
}

.product-note-text {
    margin-top: 2px;

    color: #75685b;

    font-size: 11px;
    line-height: 1.45;
}


/* =========================================================
   ACTIONS
========================================================= */

.product-form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;

    gap: 9px;

    margin-top: 17px;

    padding-bottom: 4px;
}

.product-cancel-button,
.product-save-button {
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


/* =========================================================
   CANCEL
========================================================= */

.product-cancel-button {
    border: 1px solid #ddd5cd;

    background: #ffffff;
    color: #71665d;

    transition:
        background .18s ease,
        border-color .18s ease,
        color .18s ease;
}

.product-cancel-button:hover {
    border-color: #d4c6ba;
    background: #faf7f4;
    color: #4d4036;
}


/* =========================================================
   SAVE
========================================================= */

.product-save-button {
    border: 1px solid #9d7047;

    background: #aa8250;
    color: #ffffff;

    box-shadow:
        0 3px 8px rgba(111, 78, 45, .12);

    transition:
        background .18s ease,
        transform .18s ease,
        box-shadow .18s ease;
}

.product-save-button:hover {
    background: #966f43;

    transform: translateY(-1px);

    box-shadow:
        0 5px 12px rgba(111, 78, 45, .16);
}

.product-save-button span {
    margin-right: 6px;

    font-size: 14px;
    line-height: 1;
}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 1100px) {

    .product-form-page {
        max-width: 100%;
    }

    .product-form-content {
        grid-template-columns:
            minmax(0, 1fr)
            245px;

        gap: 20px;
    }

    .product-image-section {
        padding-left: 20px;
    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 760px) {

    .product-form-page .topbar {
        flex-direction: column;
        gap: 12px;
    }

    .product-form-page .date-box {
        min-width: 145px;
    }

    .product-form-content {
        grid-template-columns: 1fr;
    }

    .product-image-section {
        padding-top: 20px;
        padding-left: 0;

        border-top: 1px solid #eee8e2;
        border-left: 0;
    }

}


/* =========================================================
   SMALL MOBILE
========================================================= */

@media (max-width: 600px) {

    .product-form-page .page-title h1 {
        font-size: 25px;
    }

    .product-form-card-header {
        align-items: flex-start;
        flex-direction: column;
        gap: 7px;
    }

    .product-form-content {
        padding: 15px;
    }

    .product-two-column {
        grid-template-columns: 1fr;
        gap: 14px;
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


/* =========================================================
   VERY SMALL MOBILE
========================================================= */

@media (max-width: 480px) {

    .product-form-card {
        border-radius: 13px;
    }

    .product-form-card-header {
        padding: 13px 15px;
    }

    .product-form-content {
        padding: 14px;
    }

    .product-form-page .date-box {
        width: 100%;
        justify-content: flex-start;
    }

}

</style>

@endpush


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       ELEMENTS
    ========================================================== */

    const imageInput =
        document.getElementById('image');

    const imagePreview =
        document.getElementById('productImagePreview');

    const imageFileName =
        document.getElementById('productImageFileName');


    if (!imageInput || !imagePreview) {
        return;
    }


    /* =========================================================
       IMAGE CHANGE
    ========================================================== */

    imageInput.addEventListener(
        'change',
        function () {

            const file =
                this.files &&
                this.files[0];


            /* ================================================
               NO FILE
            ================================================= */

            if (!file) {

                imagePreview.innerHTML = `

                    <div class="product-image-placeholder">

                        <div class="product-placeholder-icon">
                            ▧
                        </div>

                        <strong>
                            No image
                        </strong>

                        <span>
                            Preview
                        </span>

                    </div>

                `;


                if (imageFileName) {

                    imageFileName.textContent =
                        'No file selected';

                }

                return;

            }


            /* ================================================
               FILE NAME
            ================================================= */

            if (imageFileName) {

                imageFileName.textContent =
                    file.name;

            }


            /* ================================================
               PREVIEW
            ================================================= */

            const reader =
                new FileReader();


            reader.onload =
                function (event) {

                    imagePreview.innerHTML = `

                        <img
                            src="${event.target.result}"
                            alt="Product preview"
                        >

                    `;

                };


            reader.readAsDataURL(file);

        }
    );

});

</script>

@endpush