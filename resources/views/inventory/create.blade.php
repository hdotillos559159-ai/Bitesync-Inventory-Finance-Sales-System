@extends('layouts.app')

@section('title', 'BiteSync | Add Inventory')

@section('content')

    <div class="inventory-create-page">

        <div class="topbar">
            <div class="page-title">
                <small>Inventory Management</small>

                <h1>
                    Add Inventory
                </h1>

                <p>
                    Add a new item to your BiteSync inventory records.
                </p>
            </div>

            <div class="date-box">
                <span class="date-icon">◷</span>

                {{ now()->format('F d, Y') }}
            </div>
        </div>


        <!-- =========================================================
             BREADCRUMB
        ========================================================== -->

        <div class="inventory-breadcrumb">

            <a href="{{ route('inventory.index') }}">
                Inventory
            </a>

            <span>
                /
            </span>

            <strong>
                Add Inventory
            </strong>

        </div>


        <!-- =========================================================
             VALIDATION ERRORS
        ========================================================== -->

        @if ($errors->any())

            <div class="form-alert form-alert-error">

                <div class="alert-icon">
                    !
                </div>

                <div>

                    <div class="alert-title">
                        Please check the form.
                    </div>

                    <ul class="alert-list">

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
            action="{{ route('inventory.store') }}"
            class="inventory-form"
        >

            @csrf


            <!-- =====================================================
                 BASIC + STOCK INFORMATION
            ====================================================== -->

            <div class="information-grid">


                <!-- =================================================
                     BASIC INFORMATION
                ================================================== -->

                <div class="form-panel">

                    <div class="form-panel-header">

                        <div>

                            <div class="form-panel-title">
                                Basic Information
                            </div>

                            <div class="form-panel-subtitle">
                                Enter the main details of the inventory item.
                            </div>

                        </div>

                        <div class="form-panel-icon">
                            ▦
                        </div>

                    </div>


                    <div class="form-content">

                        <div class="basic-fields-grid">


                            <!-- ITEM NAME -->

                            <div class="form-group">

                                <label for="name">
                                    Item Name
                                    <span>*</span>
                                </label>

                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="Example: Chicken Breast"
                                    maxlength="255"
                                    required
                                >

                            </div>


                            <!-- SKU -->

                            <div class="form-group">

                                <label for="sku">
                                    SKU
                                    <span>*</span>
                                </label>

                                <input
                                    type="text"
                                    id="sku"
                                    name="sku"
                                    value="{{ old('sku') }}"
                                    placeholder="Example: ING-CHICK-001"
                                    maxlength="100"
                                    required
                                >

                            </div>


                            <!-- CATEGORY -->

                            <div class="form-group">

                                <label for="category_id">
                                    Category
                                    <span>*</span>
                                </label>

                                <select
                                    id="category_id"
                                    name="category_id"
                                    required
                                >

                                    <option value="">
                                        Select category
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


                            <!-- UNIT -->

                            <div class="form-group">

                                <label for="unit_id">
                                    Unit
                                    <span>*</span>
                                </label>

                                <select
                                    id="unit_id"
                                    name="unit_id"
                                    required
                                >

                                    <option value="">
                                        Select unit
                                    </option>

                                    @foreach ($units as $unit)

                                        <option
                                            value="{{ $unit->id }}"
                                            {{ old('unit_id') == $unit->id ? 'selected' : '' }}
                                        >
                                            {{ $unit->name }}
                                            ({{ $unit->abbreviation }})
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            <!-- STORAGE LOCATION -->

                            <div class="form-group basic-location">

                                <label for="location">
                                    Storage Location
                                </label>

                                <input
                                    type="text"
                                    id="location"
                                    name="location"
                                    value="{{ old('location') }}"
                                    placeholder="Example: Freezer 1"
                                    maxlength="255"
                                >

                            </div>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     STOCK INFORMATION
                ================================================== -->

                <div class="form-panel">

                    <div class="form-panel-header">

                        <div>

                            <div class="form-panel-title">
                                Stock Information
                            </div>

                            <div class="form-panel-subtitle">
                                Set the starting quantity and stock thresholds.
                            </div>

                        </div>

                        <div class="form-panel-icon">
                            #
                        </div>

                    </div>


                    <div class="form-content">

                        <div class="stock-fields-grid">


                            <!-- INITIAL QUANTITY -->

                            <div class="form-group">

                                <label for="quantity">
                                    Initial Quantity
                                    <span>*</span>
                                </label>

                                <div class="input-with-unit">

                                    <input
                                        type="number"
                                        id="quantity"
                                        name="quantity"
                                        value="{{ old('quantity', 0) }}"
                                        min="0"
                                        step="0.01"
                                        required
                                    >

                                    <span
                                        id="quantity-unit"
                                        class="input-unit"
                                    >
                                        unit
                                    </span>

                                </div>

                            </div>


                            <!-- MINIMUM STOCK -->

                            <div class="form-group">

                                <label for="minimum_stock">
                                    Minimum Stock
                                    <span>*</span>
                                </label>

                                <div class="input-with-unit">

                                    <input
                                        type="number"
                                        id="minimum_stock"
                                        name="minimum_stock"
                                        value="{{ old('minimum_stock', 0) }}"
                                        min="0"
                                        step="0.01"
                                        required
                                    >

                                    <span
                                        id="minimum-unit"
                                        class="input-unit"
                                    >
                                        unit
                                    </span>

                                </div>

                            </div>


                            <!-- MAXIMUM STOCK -->

                            <div class="form-group">

                                <label for="maximum_stock">
                                    Maximum Stock
                                </label>

                                <div class="input-with-unit">

                                    <input
                                        type="number"
                                        id="maximum_stock"
                                        name="maximum_stock"
                                        value="{{ old('maximum_stock') }}"
                                        min="0"
                                        step="0.01"
                                    >

                                    <span
                                        id="maximum-unit"
                                        class="input-unit"
                                    >
                                        unit
                                    </span>

                                </div>

                            </div>


                            <!-- UNIT COST -->

                            <div class="form-group">

                                <label for="unit_cost">
                                    Unit Cost
                                    <span>*</span>
                                </label>

                                <div class="currency-input">

                                    <span>
                                        ₱
                                    </span>

                                    <input
                                        type="number"
                                        id="unit_cost"
                                        name="unit_cost"
                                        value="{{ old('unit_cost', 0) }}"
                                        min="0"
                                        step="0.01"
                                        required
                                    >

                                </div>

                            </div>

                        </div>


                        <!-- INITIAL STOCK NOTE -->

                        <div class="stock-note">

                            <div class="stock-note-icon">
                                i
                            </div>

                            <div>

                                <strong>
                                    Initial stock will be recorded automatically.
                                </strong>

                                <p>
                                    If the initial quantity is greater than zero,
                                    BiteSync will create an initial Stock In
                                    record in inventory history.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- =========================================================
                 ADDITIONAL INFORMATION
            ========================================================== -->

            <div class="form-panel additional-panel">

                <div class="form-panel-header">

                    <div>

                        <div class="form-panel-title">
                            Additional Information
                        </div>

                        <div class="form-panel-subtitle">
                            Add optional notes about this inventory item.
                        </div>

                    </div>

                    <div class="form-panel-icon">
                        ≡
                    </div>

                </div>


                <div class="form-content">

                    <div class="form-group">

                        <label for="description">
                            Description
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            maxlength="2000"
                            placeholder="Enter additional information about this inventory item..."
                        >{{ old('description') }}</textarea>

                    </div>

                </div>

            </div>


            <!-- =========================================================
                 ACTION BUTTONS
            ========================================================== -->

            <div class="form-actions">

                <a
                    href="{{ route('inventory.index') }}"
                    class="cancel-button"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="save-button"
                >

                    <span>
                        +
                    </span>

                    Add Inventory Item

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

.inventory-create-page {
    width: 100%;
    max-width: 1120px;
    margin: 0 auto;
    padding-bottom: 30px;
}


/* ================================================================
   TOP BAR
================================================================ */

.inventory-create-page .topbar {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 25px;
    margin-bottom: 14px;
}

.inventory-create-page .page-title small {
    display: block;
    margin-bottom: 5px;
    color: #a9825b;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
}

.inventory-create-page .page-title h1 {
    margin: 0;
    color: var(--dark);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.15;
    letter-spacing: -0.045rem;
}

.inventory-create-page .page-title p {
    margin: 6px 0 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.5;
    font-weight: 400;
}


/* ================================================================
   DATE
================================================================ */

.inventory-create-page .date-box {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    min-width: 145px;
    padding: 9px 12px;
    border: 1px solid var(--border);
    border-radius: 10px;
    background: white;
    color: var(--muted);
    font-size: 10px;
    font-weight: 600;
    white-space: nowrap;
    box-shadow: 0 3px 12px rgba(43, 31, 23, .025);
}

.inventory-create-page .date-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    flex-shrink: 0;
    border-radius: 8px;
    background: var(--orange-light);
    color: var(--orange);
    font-size: 17px;
}


/* ================================================================
   BREADCRUMB
================================================================ */

.inventory-breadcrumb {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 17px;
    color: #96877b;
    font-size: 10px;
    font-weight: 600;
    line-height: 1.3;
}

.inventory-breadcrumb a {
    color: #a16e42;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.18s ease;
}

.inventory-breadcrumb a:hover {
    color: #7d4e29;
}

.inventory-breadcrumb span {
    color: #c2b5aa;
}

.inventory-breadcrumb strong {
    color: #6f6259;
    font-weight: 600;
}


/* ================================================================
   ALERT
================================================================ */

.form-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 16px;
    padding: 11px 13px;
    border-radius: 10px;
    font-size: 11px;
    line-height: 1.45;
    font-weight: 400;
}

.form-alert-error {
    border: 1px solid #efd4cf;
    background: #fff7f5;
    color: #7d433b;
}

.alert-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    flex-shrink: 0;
    border-radius: 7px;
    background: #f3d4cf;
    color: #8a4339;
    font-size: 11px;
    font-weight: 700;
}

.alert-title {
    margin-bottom: 4px;
    font-size: 11px;
    font-weight: 700;
}

.alert-list {
    margin: 4px 0 0;
    padding-left: 16px;
    line-height: 1.45;
}

.alert-list li {
    margin-bottom: 2px;
    font-size: 11px;
    font-weight: 400;
}


/* ================================================================
   SIDE-BY-SIDE PANELS
================================================================ */

.information-grid {
    display: grid;
    grid-template-columns:
        minmax(0, 1fr)
        minmax(0, 1fr);
    gap: 17px;
    align-items: stretch;
    margin-bottom: 17px;
}


/* ================================================================
   FORM PANEL
================================================================ */

.form-panel {
    overflow: hidden;
    border: 1px solid var(--border);
    border-radius: 15px;
    background: white;
    box-shadow:
        0 4px 16px
        rgba(43, 31, 23, 0.035);
}

.additional-panel {
    margin-bottom: 17px;
}


/* ================================================================
   PANEL HEADER
================================================================ */

.form-panel-header {
    min-height: 65px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 14px 19px;
    border-bottom: 1px solid var(--border);
    background: white;
}

.form-panel-title {
    color: var(--dark);
    font-size: 17px;
    line-height: 1.3;
    font-weight: 700;
}

.form-panel-subtitle {
    margin-top: 3px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.4;
    font-weight: 400;
}

.form-panel-icon {
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
   CONTENT
================================================================ */

.form-content {
    padding: 18px;
}


/* ================================================================
   BASIC FIELDS
================================================================ */

.basic-fields-grid {
    display: grid;
    grid-template-columns:
        repeat(2, minmax(0, 1fr));
    gap: 17px 15px;
}

.basic-location {
    grid-column: 1 / -1;
}


/* ================================================================
   STOCK FIELDS
================================================================ */

.stock-fields-grid {
    display: grid;
    grid-template-columns:
        repeat(2, minmax(0, 1fr));
    gap: 17px 15px;
}


/* ================================================================
   FORM GROUP
================================================================ */

.form-group {
    min-width: 0;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    color: #4c3c31;
    font-size: 11px;
    line-height: 1.35;
    font-weight: 600;
}

.form-group label span {
    color: #b65f45;
    font-weight: 600;
}


/* ================================================================
   INPUTS
================================================================ */

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    box-sizing: border-box;
    border: 1px solid #ded4cb;
    border-radius: 8px;
    background: white;
    color: var(--text);
    font-family: inherit;
    font-size: 12px;
    line-height: 1.4;
    font-weight: 400;
    outline: none;
    transition:
        border-color 0.18s ease,
        box-shadow 0.18s ease;
}

.form-group input,
.form-group select {
    height: 39px;
    padding: 0 11px;
}

.form-group textarea {
    min-height: 100px;
    padding: 10px 11px;
    resize: vertical;
    line-height: 1.5;
}

.form-group input::placeholder,
.form-group textarea::placeholder {
    color: #b8aaa0;
    font-weight: 400;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #d2a47b;
    box-shadow:
        0 0 0 3px
        rgba(196, 122, 58, 0.075);
}

.form-group select {
    cursor: pointer;
}


/* ================================================================
   NUMBER INPUT
================================================================ */

.form-group input[type="number"] {
    appearance: textfield;
}

.form-group input[type="number"]::-webkit-inner-spin-button,
.form-group input[type="number"]::-webkit-outer-spin-button {
    opacity: 0.6;
}


/* ================================================================
   UNIT INPUT
================================================================ */

.input-with-unit {
    position: relative;
}

.input-with-unit input {
    padding-right: 48px;
}

.input-unit {
    position: absolute;
    top: 50%;
    right: 11px;
    transform: translateY(-50%);
    color: #9b8d82;
    font-size: 10px;
    line-height: 1;
    font-weight: 600;
    pointer-events: none;
}


/* ================================================================
   CURRENCY
================================================================ */

.currency-input {
    position: relative;
}

.currency-input > span {
    position: absolute;
    top: 50%;
    left: 11px;
    transform: translateY(-50%);
    color: #8c684b;
    font-size: 12px;
    line-height: 1;
    font-weight: 600;
    pointer-events: none;
}

.currency-input input {
    padding-left: 27px;
}


/* ================================================================
   STOCK NOTE
================================================================ */

.stock-note {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    margin-top: 15px;
    padding: 10px;
    border: 1px solid #e9dfd5;
    border-radius: 8px;
    background: #fbf8f4;
}

.stock-note-icon {
    width: 22px;
    height: 22px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 7px;
    background: #eadbc9;
    color: #855b38;
    font-size: 9px;
    font-weight: 700;
}

.stock-note strong {
    display: block;
    margin-bottom: 3px;
    color: #594536;
    font-size: 10px;
    line-height: 1.35;
    font-weight: 700;
}

.stock-note p {
    margin: 0;
    color: #95867b;
    font-size: 9px;
    line-height: 1.45;
    font-weight: 400;
}


/* ================================================================
   ACTION BUTTONS
================================================================ */

.form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    padding-top: 0;
    padding-bottom: 4px;
}

.cancel-button,
.save-button {
    min-height: 37px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 0 14px;
    border-radius: 8px;
    font-family: inherit;
    font-size: 11px;
    line-height: 1;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
}


/* ================================================================
   CANCEL
================================================================ */

.cancel-button {
    border: 1px solid var(--border);
    background: white;
    color: var(--muted);
    transition:
        background-color 0.18s ease,
        border-color 0.18s ease,
        color 0.18s ease;
}

.cancel-button:hover {
    border-color: #d4c6ba;
    background: #faf7f4;
    color: var(--dark);
}


/* ================================================================
   SAVE
================================================================ */

.save-button {
    min-width: 120px;
    border: none;
    background:
        linear-gradient(
            135deg,
            var(--orange),
            var(--orange-dark)
        );
    color: white;
    box-shadow:
        0 4px 11px
        rgba(145, 97, 55, 0.14);
    transition:
        transform 0.18s ease,
        box-shadow 0.18s ease;
}

.save-button:hover {
    background:
        linear-gradient(
            135deg,
            var(--orange-dark),
            var(--orange-dark)
        );

    transform: translateY(-1px);

    box-shadow:
        0 6px 14px
        rgba(145, 97, 55, 0.18);
}

.save-button span {
    font-size: 13px;
    line-height: 1;
    font-weight: 400;
}


/* ================================================================
   TABLET
================================================================ */

@media (max-width: 1100px) {

    .information-grid {
        grid-template-columns: 1fr;
    }

}


/* ================================================================
   MOBILE
================================================================ */

@media (max-width: 700px) {

    .inventory-create-page {
        max-width: 100%;
    }

    .inventory-create-page .topbar {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .inventory-create-page .date-box {
        align-self: flex-start;
    }

    .basic-fields-grid,
    .stock-fields-grid {
        grid-template-columns: 1fr;
    }

    .basic-location {
        grid-column: auto;
    }

    .form-content {
        padding: 15px;
    }

    .form-panel-header {
        padding: 14px 15px;
    }

    .form-actions {
        justify-content: stretch;
    }

    .cancel-button,
    .save-button {
        flex: 1;
    }

}


/* ================================================================
   SMALL MOBILE
================================================================ */

@media (max-width: 480px) {

    .inventory-create-page .page-title h1 {
        font-size: 23px;
    }

    .form-panel-title {
        font-size: 17px;
    }

    .form-panel-subtitle {
        max-width: 220px;
        font-size: 11px;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .cancel-button,
    .save-button {
        width: 100%;
    }

}

</style>

@endpush


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const unitSelect =
        document.getElementById('unit_id');

    const quantityUnit =
        document.getElementById('quantity-unit');

    const minimumUnit =
        document.getElementById('minimum-unit');

    const maximumUnit =
        document.getElementById('maximum-unit');


    function updateUnitLabels() {

        if (!unitSelect) {
            return;
        }


        const selectedOption =
            unitSelect.options[
                unitSelect.selectedIndex
            ];


        if (
            !selectedOption ||
            !selectedOption.value
        ) {

            quantityUnit.textContent =
                'unit';

            minimumUnit.textContent =
                'unit';

            maximumUnit.textContent =
                'unit';

            return;
        }


        const text =
            selectedOption.textContent.trim();


        const match =
            text.match(/\(([^)]+)\)$/);


        const abbreviation =
            match
                ? match[1]
                : 'unit';


        quantityUnit.textContent =
            abbreviation;

        minimumUnit.textContent =
            abbreviation;

        maximumUnit.textContent =
            abbreviation;

    }


    if (unitSelect) {

        unitSelect.addEventListener(
            'change',
            updateUnitLabels
        );

        updateUnitLabels();

    }

});

</script>

@endpush