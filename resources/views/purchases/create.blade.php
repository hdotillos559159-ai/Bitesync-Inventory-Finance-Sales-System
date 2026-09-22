@extends('layouts.app')

@section('title', 'BiteSync | Add Purchase')

@php

$oldItems = old('items');

if ($oldItems === null || empty($oldItems)) {

    $oldItems = [
        [
            'inventory_item_id' => '',
            'quantity' => '',
            'unit_cost' => '',
        ],
    ];

}

@endphp

@section('content')

<div class="purchase-create-page">

    {{-- =========================================================
         TOPBAR
    ========================================================== --}}

    <div class="topbar">

        <div class="page-title">

            <small>
                Purchasing
            </small>

            <h1>
                Add Purchase
            </h1>

            <p>
                Create one purchase and add all inventory items included in the supplier order.
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

        <span>
            /
        </span>

        <strong>
            Add Purchase
        </strong>

    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

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


    {{-- =========================================================
         PURCHASE FORM
    ========================================================== --}}

    <form
        method="POST"
        action="{{ route('purchases.store') }}"
        id="purchaseForm"
    >

        @csrf


        {{-- =====================================================
             PURCHASE INFORMATION + SUMMARY
        ====================================================== --}}

        <div class="information-grid">


            {{-- =================================================
                 PURCHASE INFORMATION
            ================================================== --}}

            <div class="form-panel">

                <div class="form-panel-header">

                    <div>

                        <div class="form-panel-title">
                            Purchase Information
                        </div>

                        <div class="form-panel-subtitle">
                            Enter the supplier and purchase schedule.
                        </div>

                    </div>

                    <div class="form-panel-icon">
                        🧾
                    </div>

                </div>


                <div class="form-content">


                    {{-- SUPPLIER --}}

                    <div class="form-group">

                        <label for="supplier_id">

                            Supplier

                            <span>
                                *
                            </span>

                        </label>

                        <select
                            name="supplier_id"
                            id="supplier_id"
                            required
                        >

                            <option value="">
                                Select supplier
                            </option>

                            @foreach ($suppliers as $supplier)

                                <option
                                    value="{{ $supplier->id }}"
                                    {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}
                                >
                                    {{ $supplier->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- PURCHASE DATE --}}

                    <div class="form-group">

                        <label for="purchase_date">

                            Purchase Date

                            <span>
                                *
                            </span>

                        </label>

                        <input
                            type="date"
                            name="purchase_date"
                            id="purchase_date"
                            value="{{ old('purchase_date', now()->format('Y-m-d')) }}"
                            required
                        >

                    </div>


                    {{-- EXPECTED DELIVERY --}}

                    <div class="form-group">

                        <label for="expected_date">
                            Expected Delivery
                        </label>

                        <input
                            type="date"
                            name="expected_date"
                            id="expected_date"
                            value="{{ old('expected_date') }}"
                        >

                    </div>

                </div>

            </div>


            {{-- =================================================
                 PURCHASE SUMMARY
            ================================================== --}}

            <div class="form-panel">

                <div class="form-panel-header">

                    <div>

                        <div class="form-panel-title">
                            Purchase Summary
                        </div>

                        <div class="form-panel-subtitle">
                            Review the calculated purchase amount.
                        </div>

                    </div>

                    <div class="form-panel-icon">
                        ₱
                    </div>

                </div>


                <div class="form-content">


                    {{-- SUBTOTAL --}}

                    <div class="summary-row">

                        <div>

                            <span>
                                Subtotal
                            </span>

                            <small>
                                Total before tax
                            </small>

                        </div>

                        <strong id="summarySubtotal">
                            ₱0.00
                        </strong>

                    </div>


                    {{-- TAX --}}

                    <div class="form-group tax-group">

                        <label for="tax">
                            Tax
                        </label>

                        <div class="currency-input">

                            <span>
                                ₱
                            </span>

                            <input
                                type="number"
                                name="tax"
                                id="tax"
                                value="{{ old('tax', 0) }}"
                                min="0"
                                step="0.01"
                            >

                        </div>

                    </div>


                    {{-- TOTAL --}}

                    <div class="summary-total">

                        <div>

                            <span>
                                Total
                            </span>

                            <small>
                                Subtotal + tax
                            </small>

                        </div>

                        <strong id="summaryTotal">
                            ₱0.00
                        </strong>

                    </div>


                    {{-- DRAFT NOTE --}}

                    <div class="purchase-note">

                        <div class="purchase-note-icon">
                            i
                        </div>

                        <div>

                            <strong>
                                New purchases are saved as Draft.
                            </strong>

                            <p>
                                Purchases remain in Draft status until submitted for approval.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             PURCHASE ITEMS
        ====================================================== --}}

        <div class="form-panel additional-panel purchase-items-panel">

            <div class="form-panel-header purchase-items-header">

                <div>

                    <div class="form-panel-title">
                        Purchase Items
                    </div>

                    <div class="form-panel-subtitle">
                        Add every inventory item included in this purchase.
                    </div>

                </div>

                <button
                    type="button"
                    class="add-item-button"
                    id="addItemBtn"
                >

                    <span>
                        +
                    </span>

                    Add Item

                </button>

            </div>


            <div class="form-content items-content">

                <div class="table-wrapper">

                    <table class="items-table">

                        <thead>

                            <tr>

                                <th style="width: 42%;">
                                    Inventory Item
                                </th>

                                <th style="width: 15%;">
                                    Quantity
                                </th>

                                <th style="width: 18%;">
                                    Unit Cost
                                </th>

                                <th style="width: 17%;">
                                    Subtotal
                                </th>

                                <th style="width: 8%;">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody id="itemsContainer">

                            @foreach ($oldItems as $index => $oldItem)

                                <tr class="item-row">


                                    {{-- INVENTORY ITEM --}}

                                    <td>

                                        <select
                                            name="items[{{ $index }}][inventory_item_id]"
                                            class="item-select"
                                            required
                                        >

                                            <option value="">
                                                Select inventory item
                                            </option>

                                            @foreach ($inventoryItems as $inventoryItem)

                                                <option
                                                    value="{{ $inventoryItem->id }}"
                                                    {{ isset($oldItem['inventory_item_id']) && $oldItem['inventory_item_id'] == $inventoryItem->id ? 'selected' : '' }}
                                                >

                                                    {{ $inventoryItem->name }}

                                                    @if ($inventoryItem->unit)

                                                        ({{ $inventoryItem->unit->abbreviation }})

                                                    @endif

                                                </option>

                                            @endforeach

                                        </select>

                                    </td>


                                    {{-- QUANTITY --}}

                                    <td>

                                        <input
                                            type="number"
                                            name="items[{{ $index }}][quantity]"
                                            class="quantity-input"
                                            value="{{ $oldItem['quantity'] ?? '' }}"
                                            min="0.01"
                                            step="0.01"
                                            placeholder="0"
                                            required
                                        >

                                    </td>


                                    {{-- UNIT COST --}}

                                    <td>

                                        <div class="currency-input">

                                            <span>
                                                ₱
                                            </span>

                                            <input
                                                type="number"
                                                name="items[{{ $index }}][unit_cost]"
                                                class="unit-cost-input"
                                                value="{{ $oldItem['unit_cost'] ?? '' }}"
                                                min="0"
                                                step="0.01"
                                                placeholder="0.00"
                                                required
                                            >

                                        </div>

                                    </td>


                                    {{-- SUBTOTAL --}}

                                    <td>

                                        <div class="row-subtotal">
                                            ₱0.00
                                        </div>

                                    </td>


                                    {{-- ACTION --}}

                                    <td class="action-cell">

                                        <button
                                            type="button"
                                            class="remove-item-btn"
                                            title="Remove item"
                                        >
                                            ×
                                        </button>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                <div class="items-helper">

                    <div class="items-helper-icon">
                        i
                    </div>

                    <div>

                        <strong>
                            One purchase can contain multiple items
                        </strong>

                        <p>
                            Add all items from the supplier order here. Each row represents a different inventory item, quantity, and unit cost.
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             HIDDEN TEMPLATE
        ====================================================== --}}

        <template id="itemRowTemplate">

            <tr class="item-row">

                <td>

                    <select
                        class="item-select"
                        required
                    >

                        <option value="">
                            Select inventory item
                        </option>

                        @foreach ($inventoryItems as $inventoryItem)

                            <option value="{{ $inventoryItem->id }}">

                                {{ $inventoryItem->name }}

                                @if ($inventoryItem->unit)

                                    ({{ $inventoryItem->unit->abbreviation }})

                                @endif

                            </option>

                        @endforeach

                    </select>

                </td>


                <td>

                    <input
                        type="number"
                        class="quantity-input"
                        min="0.01"
                        step="0.01"
                        placeholder="0"
                        required
                    >

                </td>


                <td>

                    <div class="currency-input">

                        <span>
                            ₱
                        </span>

                        <input
                            type="number"
                            class="unit-cost-input"
                            min="0"
                            step="0.01"
                            placeholder="0.00"
                            required
                        >

                    </div>

                </td>


                <td>

                    <div class="row-subtotal">
                        ₱0.00
                    </div>

                </td>


                <td class="action-cell">

                    <button
                        type="button"
                        class="remove-item-btn"
                        title="Remove item"
                    >
                        ×
                    </button>

                </td>

            </tr>

        </template>


        {{-- =====================================================
             ADDITIONAL INFORMATION
        ====================================================== --}}

        <div class="form-panel additional-panel">

            <div class="form-panel-header">

                <div>

                    <div class="form-panel-title">
                        Additional Information
                    </div>

                    <div class="form-panel-subtitle">
                        Add optional notes about this purchase.
                    </div>

                </div>

                <div class="form-panel-icon">
                    ≡
                </div>

            </div>


            <div class="form-content">

                <div class="form-group">

                    <label for="notes">
                        Notes
                    </label>

                    <textarea
                        name="notes"
                        id="notes"
                        rows="4"
                        maxlength="2000"
                        placeholder="Enter additional notes or instructions..."
                    >{{ old('notes') }}</textarea>

                </div>

            </div>

        </div>


        {{-- =====================================================
             ACTION BUTTONS
        ====================================================== --}}

        <div class="form-actions">

            <a
                href="{{ route('purchases.index') }}"
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

                Create Purchase

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

.purchase-create-page {
    width: 100%;
    max-width: 1120px;
    margin: 0 auto;
    padding-bottom: 30px;
}


/* ================================================================
   TOP BAR
================================================================ */

.purchase-create-page .topbar {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 25px;
    margin-bottom: 14px;
}

.purchase-create-page .page-title small {
    display: block;
    margin-bottom: 5px;
    color: #a9825b;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
}

.purchase-create-page .page-title h1 {
    margin: 0;
    color: var(--dark);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.15;
    letter-spacing: -0.045rem;
}

.purchase-create-page .page-title p {
    margin: 6px 0 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.5;
    font-weight: 400;
}


/* ================================================================
   DATE
================================================================ */

.purchase-create-page .date-box {
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
    box-shadow:
        0 3px 12px
        rgba(43, 31, 23, .025);
}

.purchase-create-page .date-icon {
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

.purchase-breadcrumb {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 17px;
    color: #96877b;
    font-size: 10px;
    font-weight: 600;
    line-height: 1.3;
}

.purchase-breadcrumb a {
    color: #a16e42;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.18s ease;
}

.purchase-breadcrumb a:hover {
    color: #7d4e29;
}

.purchase-breadcrumb span {
    color: #c2b5aa;
}

.purchase-breadcrumb strong {
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

.purchase-create-page .information-grid {
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

.purchase-create-page .form-panel {
    overflow: hidden;
    border: 1px solid var(--border);
    border-radius: 15px;
    background: white;
    box-shadow:
        0 4px 16px
        rgba(43, 31, 23, 0.035);
}

.purchase-create-page .additional-panel {
    margin-bottom: 17px;
}


/* ================================================================
   PANEL HEADER
================================================================ */

.purchase-create-page .form-panel-header {
    min-height: 65px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 14px 19px;
    border-bottom: 1px solid var(--border);
    background: white;
}

.purchase-create-page .form-panel-title {
    color: var(--dark);
    font-size: 17px;
    line-height: 1.3;
    font-weight: 700;
}

.purchase-create-page .form-panel-subtitle {
    margin-top: 3px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.4;
    font-weight: 400;
}

.purchase-create-page .form-panel-icon {
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

.purchase-create-page .form-content {
    padding: 18px;
}


/* ================================================================
   FORM GROUP
================================================================ */

.purchase-create-page .form-group {
    min-width: 0;
    margin-bottom: 17px;
}

.purchase-create-page .form-group:last-child {
    margin-bottom: 0;
}

.purchase-create-page .form-group label {
    display: block;
    margin-bottom: 6px;
    color: #4c3c31;
    font-size: 11px;
    line-height: 1.35;
    font-weight: 600;
}

.purchase-create-page .form-group label span {
    color: #b65f45;
    font-weight: 600;
}


/* ================================================================
   INPUTS
================================================================ */

.purchase-create-page .form-group input,
.purchase-create-page .form-group select,
.purchase-create-page .form-group textarea,
.purchase-create-page .items-table input,
.purchase-create-page .items-table select {
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

.purchase-create-page .form-group input,
.purchase-create-page .form-group select {
    height: 39px;
    padding: 0 11px;
}

.purchase-create-page .form-group textarea {
    min-height: 100px;
    padding: 10px 11px;
    resize: vertical;
    line-height: 1.5;
}

.purchase-create-page .form-group input::placeholder,
.purchase-create-page .form-group textarea::placeholder,
.purchase-create-page .items-table input::placeholder {
    color: #b8aaa0;
    font-weight: 400;
}

.purchase-create-page .form-group input:focus,
.purchase-create-page .form-group select:focus,
.purchase-create-page .form-group textarea:focus,
.purchase-create-page .items-table input:focus,
.purchase-create-page .items-table select:focus {
    border-color: #d2a47b;
    box-shadow:
        0 0 0 3px
        rgba(196, 122, 58, 0.075);
}

.purchase-create-page .form-group select,
.purchase-create-page .items-table select {
    cursor: pointer;
}


/* ================================================================
   NUMBER INPUT
================================================================ */

.purchase-create-page input[type="number"] {
    appearance: textfield;
}

.purchase-create-page input[type="number"]::-webkit-inner-spin-button,
.purchase-create-page input[type="number"]::-webkit-outer-spin-button {
    opacity: 0.6;
}


/* ================================================================
   CURRENCY INPUT
================================================================ */

.purchase-create-page .currency-input {
    position: relative;
}

.purchase-create-page .currency-input > span {
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

.purchase-create-page .currency-input input {
    padding-left: 27px;
}


/* ================================================================
   SUMMARY
================================================================ */

.purchase-create-page .summary-row,
.purchase-create-page .summary-total {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.purchase-create-page .summary-row {
    padding-bottom: 14px;
    margin-bottom: 15px;
    border-bottom: 1px solid var(--border);
}

.purchase-create-page .summary-row span,
.purchase-create-page .summary-total span {
    display: block;
    color: #4c3c31;
    font-size: 12px;
    line-height: 1.35;
    font-weight: 600;
}

.purchase-create-page .summary-row small,
.purchase-create-page .summary-total small {
    display: block;
    margin-top: 3px;
    color: var(--muted);
    font-size: 9px;
    line-height: 1.35;
}

.purchase-create-page .summary-row strong {
    color: #594536;
    font-size: 16px;
    line-height: 1;
    font-weight: 700;
}

.purchase-create-page .tax-group {
    padding-bottom: 15px;
    margin-bottom: 15px;
    border-bottom: 1px solid var(--border);
}

.purchase-create-page .summary-total strong {
    color: var(--orange);
    font-size: 21px;
    line-height: 1;
    font-weight: 800;
}


/* ================================================================
   PURCHASE NOTE
================================================================ */

.purchase-create-page .purchase-note {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    margin-top: 15px;
    padding: 10px;
    border: 1px solid #e9dfd5;
    border-radius: 8px;
    background: #fbf8f4;
}

.purchase-create-page .purchase-note-icon {
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

.purchase-create-page .purchase-note strong {
    display: block;
    margin-bottom: 3px;
    color: #594536;
    font-size: 10px;
    line-height: 1.35;
    font-weight: 700;
}

.purchase-create-page .purchase-note p {
    margin: 0;
    color: #95867b;
    font-size: 9px;
    line-height: 1.45;
    font-weight: 400;
}


/* ================================================================
   PURCHASE ITEMS HEADER
================================================================ */

.purchase-create-page .purchase-items-header {
    align-items: center;
}

.purchase-create-page .add-item-button {
    min-height: 37px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 0 13px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: white;
    color: #76553c;
    font-family: inherit;
    font-size: 11px;
    line-height: 1;
    font-weight: 700;
    cursor: pointer;
    transition:
        background-color 0.18s ease,
        border-color 0.18s ease,
        transform 0.18s ease;
}

.purchase-create-page .add-item-button:hover {
    border-color: #d4c6ba;
    background: #faf7f4;
    transform: translateY(-1px);
}

.purchase-create-page .add-item-button span {
    font-size: 15px;
    line-height: 1;
    font-weight: 400;
}


/* ================================================================
   ITEMS TABLE
================================================================ */

.purchase-create-page .items-content {
    padding-top: 16px;
}

.purchase-create-page .table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.purchase-create-page .items-table {
    width: 100%;
    min-width: 760px;
    border-collapse: collapse;
}

.purchase-create-page .items-table th {
    padding: 9px 10px;
    border-bottom: 1px solid #e8e0d9;
    background: #fcfaf8;
    color: #796b61;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: .055em;
    line-height: 1.3;
    text-align: left;
    text-transform: uppercase;
}

.purchase-create-page .items-table td {
    padding: 9px 10px;
    border-bottom: 1px solid #eee8e2;
    vertical-align: middle;
}

.purchase-create-page .items-table tbody tr:last-child td {
    border-bottom: 0;
}

.purchase-create-page .items-table input,
.purchase-create-page .items-table select {
    height: 39px;
    padding: 0 10px;
}

.purchase-create-page .items-table .currency-input > span {
    height: 39px;
    display: flex;
    align-items: center;
}

.purchase-create-page .items-table .currency-input input {
    padding-left: 27px;
}

.purchase-create-page .row-subtotal {
    min-height: 39px;
    display: flex;
    align-items: center;
    padding: 0 10px;
    border: 1px solid #e8e0d9;
    border-radius: 8px;
    background: #fcfaf8;
    color: #594536;
    font-size: 12px;
    line-height: 1;
    font-weight: 700;
    white-space: nowrap;
}

.purchase-create-page .action-cell {
    text-align: center;
}

.purchase-create-page .remove-item-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border: 1px solid #ead7d3;
    border-radius: 8px;
    background: #fff8f7;
    color: #c45e55;
    font-family: inherit;
    font-size: 18px;
    line-height: 1;
    cursor: pointer;
    transition:
        background-color 0.18s ease,
        border-color 0.18s ease,
        transform 0.18s ease;
}

.purchase-create-page .remove-item-btn:hover {
    border-color: #dca9a3;
    background: #fff1ef;
    transform: translateY(-1px);
}


/* ================================================================
   ITEMS HELPER
================================================================ */

.purchase-create-page .items-helper {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    margin-top: 14px;
    padding: 10px;
    border: 1px solid #e9dfd5;
    border-radius: 8px;
    background: #fbf8f4;
}

.purchase-create-page .items-helper-icon {
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

.purchase-create-page .items-helper strong {
    display: block;
    margin-bottom: 3px;
    color: #594536;
    font-size: 10px;
    line-height: 1.35;
    font-weight: 700;
}

.purchase-create-page .items-helper p {
    margin: 0;
    color: #95867b;
    font-size: 9px;
    line-height: 1.45;
    font-weight: 400;
}


/* ================================================================
   ACTION BUTTONS
================================================================ */

.purchase-create-page .form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    padding-top: 0;
    padding-bottom: 4px;
}

.purchase-create-page .cancel-button,
.purchase-create-page .save-button {
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

.purchase-create-page .cancel-button {
    border: 1px solid var(--border);
    background: white;
    color: var(--muted);
    transition:
        background-color 0.18s ease,
        border-color 0.18s ease,
        color 0.18s ease;
}

.purchase-create-page .cancel-button:hover {
    border-color: #d4c6ba;
    background: #faf7f4;
    color: var(--dark);
}


/* ================================================================
   SAVE
================================================================ */

.purchase-create-page .save-button {
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

.purchase-create-page .save-button:hover {
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

.purchase-create-page .save-button span {
    font-size: 13px;
    line-height: 1;
    font-weight: 400;
}


/* ================================================================
   TABLET
================================================================ */

@media (max-width: 1100px) {

    .purchase-create-page .information-grid {
        grid-template-columns: 1fr;
    }

}


/* ================================================================
   MOBILE
================================================================ */

@media (max-width: 700px) {

    .purchase-create-page {
        max-width: 100%;
    }

    .purchase-create-page .topbar {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .purchase-create-page .date-box {
        align-self: flex-start;
    }

    .purchase-create-page .purchase-items-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .purchase-create-page .add-item-button {
        width: 100%;
    }

    .purchase-create-page .form-content {
        padding: 15px;
    }

    .purchase-create-page .form-panel-header {
        padding: 14px 15px;
    }

    .purchase-create-page .form-actions {
        justify-content: stretch;
    }

    .purchase-create-page .cancel-button,
    .purchase-create-page .save-button {
        flex: 1;
    }

}


/* ================================================================
   SMALL MOBILE
================================================================ */

@media (max-width: 480px) {

    .purchase-create-page .page-title h1 {
        font-size: 23px;
    }

    .purchase-create-page .form-panel-title {
        font-size: 17px;
    }

    .purchase-create-page .form-panel-subtitle {
        max-width: 220px;
        font-size: 11px;
    }

    .purchase-create-page .form-actions {
        flex-direction: column-reverse;
    }

    .purchase-create-page .cancel-button,
    .purchase-create-page .save-button {
        width: 100%;
    }

}

</style>

@endpush


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {


    /* ============================================================
       ELEMENTS
    ============================================================ */

    const itemsContainer =
        document.getElementById('itemsContainer');

    const addItemBtn =
        document.getElementById('addItemBtn');

    const taxInput =
        document.getElementById('tax');

    const summarySubtotal =
        document.getElementById('summarySubtotal');

    const summaryTotal =
        document.getElementById('summaryTotal');

    const itemRowTemplate =
        document.getElementById('itemRowTemplate');


    /* ============================================================
       CURRENCY
    ============================================================ */

    function formatCurrency(value) {

        const number =
            Number(value) || 0;

        return '₱' + number.toLocaleString(
            'en-PH',
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );

    }


    /* ============================================================
       RE-INDEX ROWS
    ============================================================ */

    function reindexRows() {

        const rows =
            itemsContainer.querySelectorAll('.item-row');


        rows.forEach(function (row, index) {

            const select =
                row.querySelector('.item-select');

            const quantity =
                row.querySelector('.quantity-input');

            const unitCost =
                row.querySelector('.unit-cost-input');


            if (select) {

                select.name =
                    'items[' +
                    index +
                    '][inventory_item_id]';

            }


            if (quantity) {

                quantity.name =
                    'items[' +
                    index +
                    '][quantity]';

            }


            if (unitCost) {

                unitCost.name =
                    'items[' +
                    index +
                    '][unit_cost]';

            }

        });

    }


    /* ============================================================
       CALCULATE ROW
    ============================================================ */

    function calculateRow(row) {

        const quantityInput =
            row.querySelector('.quantity-input');

        const unitCostInput =
            row.querySelector('.unit-cost-input');

        const subtotalElement =
            row.querySelector('.row-subtotal');


        if (!quantityInput ||
            !unitCostInput ||
            !subtotalElement) {

            return 0;

        }


        const quantity =
            parseFloat(quantityInput.value) || 0;

        const unitCost =
            parseFloat(unitCostInput.value) || 0;


        const subtotal =
            quantity * unitCost;


        subtotalElement.textContent =
            formatCurrency(subtotal);


        return subtotal;

    }


    /* ============================================================
       CALCULATE TOTALS
    ============================================================ */

    function calculateTotals() {

        const rows =
            itemsContainer.querySelectorAll('.item-row');


        let subtotal = 0;


        rows.forEach(function (row) {

            subtotal +=
                calculateRow(row);

        });


        const tax =
            parseFloat(taxInput.value) || 0;


        const total =
            subtotal + tax;


        summarySubtotal.textContent =
            formatCurrency(subtotal);

        summaryTotal.textContent =
            formatCurrency(total);

    }


    /* ============================================================
       ATTACH ROW EVENTS
    ============================================================ */

    function attachRowEvents(row) {

        const quantityInput =
            row.querySelector('.quantity-input');

        const unitCostInput =
            row.querySelector('.unit-cost-input');

        const removeButton =
            row.querySelector('.remove-item-btn');


        if (quantityInput) {

            quantityInput.addEventListener(
                'input',
                calculateTotals
            );

        }


        if (unitCostInput) {

            unitCostInput.addEventListener(
                'input',
                calculateTotals
            );

        }


        if (removeButton) {

            removeButton.addEventListener(
                'click',
                function () {

                    const rows =
                        itemsContainer.querySelectorAll('.item-row');


                    /*
                     * Keep one row available at all times.
                     */

                    if (rows.length === 1) {

                        const select =
                            row.querySelector('.item-select');

                        const quantity =
                            row.querySelector('.quantity-input');

                        const unitCost =
                            row.querySelector('.unit-cost-input');


                        if (select) {
                            select.value = '';
                        }

                        if (quantity) {
                            quantity.value = '';
                        }

                        if (unitCost) {
                            unitCost.value = '';
                        }


                        calculateTotals();

                        return;

                    }


                    row.remove();

                    reindexRows();

                    calculateTotals();

                }
            );

        }

    }


    /* ============================================================
       ADD NEW ITEM ROW
    ============================================================ */

    function addItemRow() {

        if (!itemRowTemplate) {
            return;
        }


        const templateContent =
            itemRowTemplate.content.cloneNode(true);


        itemsContainer.appendChild(
            templateContent
        );


        const rows =
            itemsContainer.querySelectorAll('.item-row');


        const newRow =
            rows[rows.length - 1];


        attachRowEvents(newRow);

        reindexRows();

        calculateTotals();

    }


    /* ============================================================
       ADD ITEM BUTTON
    ============================================================ */

    if (addItemBtn) {

        addItemBtn.addEventListener(
            'click',
            function () {

                addItemRow();

            }
        );

    }


    /* ============================================================
       TAX
    ============================================================ */

    if (taxInput) {

        taxInput.addEventListener(
            'input',
            function () {

                calculateTotals();

            }
        );

    }


    /* ============================================================
       EXISTING ROWS
    ============================================================ */

    const existingRows =
        itemsContainer.querySelectorAll('.item-row');


    existingRows.forEach(function (row) {

        attachRowEvents(row);

    });


    /* ============================================================
       INITIAL SETUP
    ============================================================ */

    reindexRows();

    calculateTotals();

});

</script>

@endpush