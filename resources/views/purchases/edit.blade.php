```blade
@extends('layouts.app')

@section('title', 'BiteSync | Edit Purchase')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | FORM ITEMS
    |--------------------------------------------------------------------------
    |
    | If validation failed, keep the submitted values.
    | Otherwise, load the existing purchase items.
    |
    */

    $formItems = old('items');

    if ($formItems === null) {
        $formItems = $purchase->items->map(function ($item) {
            return [
                'inventory_item_id' => $item->inventory_item_id,
                'quantity' => $item->quantity,
                'unit_cost' => $item->unit_cost,
            ];
        })->values()->all();
    }

    /*
    |--------------------------------------------------------------------------
    | ALWAYS KEEP ONE ROW
    |--------------------------------------------------------------------------
    */

    if (empty($formItems)) {
        $formItems = [
            [
                'inventory_item_id' => '',
                'quantity' => '',
                'unit_cost' => '',
            ],
        ];
    }
@endphp

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
                Edit Purchase
            </h1>

            <p>
                Update the details and items for this purchase record.
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
            Edit Purchase
        </span>

    </div>


    {{-- =========================================================
         PURCHASE REFERENCE
    ========================================================== --}}

    <div class="purchase-reference-bar">

        <div class="purchase-reference-item">

            <span class="purchase-reference-label">
                Purchase Number
            </span>

            <strong>
                {{ $purchase->purchase_number }}
            </strong>

        </div>

        <div class="purchase-reference-item">

            <span class="purchase-reference-label">
                Current Status
            </span>

            @if ($purchase->isRejected())

                <span class="purchase-status purchase-status-rejected">
                    Rejected
                </span>

            @else

                <span class="purchase-status purchase-status-draft">
                    Draft
                </span>

            @endif

        </div>

    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if ($errors->any())

        <div class="purchase-form-alert">

            <div class="purchase-alert-title">
                Please correct the following:
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


    {{-- =========================================================
         FORM
    ========================================================== --}}

    <form
        method="POST"
        action="{{ route('purchases.update', $purchase) }}"
        id="purchaseForm"
    >

        @csrf

        @method('PUT')


        {{-- =====================================================
             INFORMATION GRID
        ====================================================== --}}

        <div class="information-grid">


            {{-- =================================================
                 PURCHASE INFORMATION
            ================================================== --}}

            <div class="form-panel">

                <div class="form-panel-header">

                    <div class="form-panel-icon">
                        🛒
                    </div>

                    <div>

                        <div class="form-panel-title">
                            Purchase Information
                        </div>

                        <div class="form-panel-subtitle">
                            Update the supplier and purchase schedule.
                        </div>

                    </div>

                </div>


                <div class="form-panel-body">

                    {{-- SUPPLIER --}}

                    <div class="form-field">

                        <label for="supplier_id">
                            Supplier
                            <span>*</span>
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
                                    {{ (string) old('supplier_id', $purchase->supplier_id) === (string) $supplier->id ? 'selected' : '' }}
                                >
                                    {{ $supplier->name }}
                                </option>

                            @endforeach

                        </select>

                        @error('supplier_id')

                            <small class="field-error">
                                {{ $message }}
                            </small>

                        @enderror

                    </div>


                    {{-- PURCHASE DATE --}}

                    <div class="form-field">

                        <label for="purchase_date">
                            Purchase Date
                            <span>*</span>
                        </label>

                        <input
                            type="date"
                            name="purchase_date"
                            id="purchase_date"
                            value="{{ old('purchase_date', $purchase->purchase_date ? $purchase->purchase_date->format('Y-m-d') : '') }}"
                            required
                        >

                        @error('purchase_date')

                            <small class="field-error">
                                {{ $message }}
                            </small>

                        @enderror

                    </div>


                    {{-- EXPECTED DELIVERY --}}

                    <div class="form-field">

                        <label for="expected_date">
                            Expected Delivery
                        </label>

                        <input
                            type="date"
                            name="expected_date"
                            id="expected_date"
                            value="{{ old('expected_date', $purchase->expected_date ? $purchase->expected_date->format('Y-m-d') : '') }}"
                        >

                        <small class="field-helper">
                            Optional expected delivery date.
                        </small>

                        @error('expected_date')

                            <small class="field-error">
                                {{ $message }}
                            </small>

                        @enderror

                    </div>

                </div>

            </div>


            {{-- =================================================
                 PURCHASE SUMMARY
            ================================================== --}}

            <div class="form-panel">

                <div class="form-panel-header">

                    <div class="form-panel-icon">
                        ₱
                    </div>

                    <div>

                        <div class="form-panel-title">
                            Purchase Summary
                        </div>

                        <div class="form-panel-subtitle">
                            Review the updated purchase amount.
                        </div>

                    </div>

                </div>


                <div class="summary-body">

                    <div class="summary-row">

                        <span>
                            Subtotal
                        </span>

                        <strong id="subtotalDisplay">
                            ₱0.00
                        </strong>

                    </div>


                    <div class="summary-tax-row">

                        <label for="tax">
                            Tax
                        </label>

                        <div class="summary-tax-input">

                            <span>
                                ₱
                            </span>

                            <input
                                type="number"
                                name="tax"
                                id="tax"
                                value="{{ old('tax', $purchase->tax ?? 0) }}"
                                min="0"
                                step="0.01"
                                placeholder="0.00"
                            >

                        </div>

                    </div>


                    <div class="summary-divider"></div>


                    <div class="summary-total-row">

                        <span>
                            Total
                        </span>

                        <strong id="totalDisplay">
                            ₱0.00
                        </strong>

                    </div>


                    <div class="summary-note">

                        <span>
                            ●
                        </span>

                        Saving changes will return this purchase to Draft status.

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             PURCHASE ITEMS
        ====================================================== --}}

        <div class="form-panel full-width-panel">

            <div class="form-panel-header form-panel-header-action">

                <div class="form-panel-heading">

                    <div class="form-panel-icon">
                        📦
                    </div>

                    <div>

                        <div class="form-panel-title">
                            Purchase Items
                        </div>

                        <div class="form-panel-subtitle">
                            Update the inventory items, quantities, and unit costs.
                        </div>

                    </div>

                </div>


                <button
                    type="button"
                    class="add-item-button"
                    id="addItemButton"
                >

                    <span>
                        +
                    </span>

                    Add Item

                </button>

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
                                    Unit Cost
                                </th>

                                <th>
                                    Subtotal
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody id="purchaseItemsBody">

                            @foreach ($formItems as $index => $item)

                                <tr class="purchase-item-row">

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
                                                    {{ (string) ($item['inventory_item_id'] ?? '') === (string) $inventoryItem->id ? 'selected' : '' }}
                                                >
                                                    {{ $inventoryItem->name }}
                                                </option>

                                            @endforeach

                                        </select>

                                    </td>


                                    {{-- QUANTITY --}}

                                    <td>

                                        <input
                                            type="number"
                                            name="items[{{ $index }}][quantity]"
                                            class="item-quantity"
                                            value="{{ $item['quantity'] ?? '' }}"
                                            min="0.01"
                                            step="0.01"
                                            placeholder="0.00"
                                            required
                                        >

                                    </td>


                                    {{-- UNIT COST --}}

                                    <td>

                                        <div class="cost-input">

                                            <span>
                                                ₱
                                            </span>

                                            <input
                                                type="number"
                                                name="items[{{ $index }}][unit_cost]"
                                                class="item-unit-cost"
                                                value="{{ $item['unit_cost'] ?? '' }}"
                                                min="0"
                                                step="0.01"
                                                placeholder="0.00"
                                                required
                                            >

                                        </div>

                                    </td>


                                    {{-- SUBTOTAL --}}

                                    <td>

                                        <span class="item-subtotal">
                                            ₱0.00
                                        </span>

                                    </td>


                                    {{-- REMOVE --}}

                                    <td>

                                        <button
                                            type="button"
                                            class="remove-item-button"
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

            </div>

        </div>


        {{-- =====================================================
             ADDITIONAL INFORMATION
        ====================================================== --}}

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
                            Update any notes or additional details about this purchase.
                        </div>

                    </div>

                </div>

            </div>


            <div class="additional-information-body">

                <div class="form-field">

                    <label for="notes">
                        Notes
                    </label>

                    <textarea
                        name="notes"
                        id="notes"
                        rows="5"
                        maxlength="5000"
                        placeholder="Enter purchase notes..."
                    >{{ old('notes', $purchase->notes) }}</textarea>

                    <small class="field-helper">
                        Add any delivery instructions, supplier notes, or other relevant information.
                    </small>

                    @error('notes')

                        <small class="field-error">
                            {{ $message }}
                        </small>

                    @enderror

                </div>

            </div>

        </div>


        {{-- =====================================================
             ACTIONS
        ====================================================== --}}

        <div class="form-actions">

            <a
                href="{{ route('purchases.show', $purchase) }}"
                class="cancel-button"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="save-button"
            >

                <span>
                    ✓
                </span>

                Save Changes

            </button>

        </div>

    </form>

</div>


@push('styles')

<style>

/* =========================================================
   PAGE
========================================================= */

.purchase-create-page {
    width: 100%;
}


/* =========================================================
   TOPBAR
========================================================= */

.purchase-create-page .topbar {
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
   PURCHASE REFERENCE
========================================================= */

.purchase-reference-bar {
    min-height: 58px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 18px;
    padding: 12px 18px;
    background: white;
    border: 1px solid var(--border);
    border-radius: 14px;
    box-shadow:
        0 5px 18px rgba(43, 31, 23, 0.035);
}

.purchase-reference-item {
    display: flex;
    align-items: center;
    gap: 9px;
}

.purchase-reference-label {
    color: var(--muted);
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.04rem;
}

.purchase-reference-item strong {
    color: var(--dark);
    font-size: 0.8125rem;
    font-weight: 800;
}

.purchase-status {
    display: inline-flex;
    align-items: center;
    padding: 5px 9px;
    border-radius: 7px;
    font-size: 0.6875rem;
    font-weight: 800;
}

.purchase-status-draft {
    color: var(--muted);
    background: #f1eeeb;
}

.purchase-status-rejected {
    color: #a94f48;
    background: #fff0ee;
}


/* =========================================================
   VALIDATION ALERT
========================================================= */

.purchase-form-alert {
    margin-bottom: 18px;
    padding: 13px 16px;
    color: #91453e;
    background: #fff0ee;
    border: 1px solid #eccfcb;
    border-radius: 10px;
    font-size: 0.8125rem;
}

.purchase-alert-title {
    margin-bottom: 6px;
    font-weight: 800;
}

.purchase-form-alert ul {
    margin: 0;
    padding-left: 18px;
}

.purchase-form-alert li {
    margin-bottom: 3px;
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
    gap: 11px;
    padding: 15px 18px;
    border-bottom: 1px solid var(--border);
}

.form-panel-header-action {
    justify-content: space-between;
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
   FORM BODY
========================================================= */

.form-panel-body {
    display: grid;
    grid-template-columns:
        repeat(3, minmax(0, 1fr));
    gap: 16px;
    padding: 18px;
}

.form-field {
    min-width: 0;
}

.form-field label {
    display: block;
    margin-bottom: 7px;
    color: var(--dark);
    font-size: 0.75rem;
    font-weight: 800;
}

.form-field label span {
    color: #c16e38;
}

.form-field input,
.form-field select,
.form-field textarea {
    width: 100%;
    border: 1px solid #ded4cb;
    border-radius: 9px;
    background: white;
    color: var(--text);
    font-family: inherit;
    font-size: 0.8125rem;
    outline: none;
    transition:
        border-color 0.18s ease,
        box-shadow 0.18s ease;
    box-sizing: border-box;
}

.form-field input,
.form-field select {
    height: 41px;
    padding: 0 11px;
}

.form-field textarea {
    min-height: 125px;
    resize: vertical;
    padding: 10px 11px;
    line-height: 1.5;
}

.form-field input:focus,
.form-field select:focus,
.form-field textarea:focus {
    border-color: #d2a47b;
    box-shadow:
        0 0 0 3px rgba(196, 122, 58, 0.08);
}

.field-helper {
    display: block;
    margin-top: 6px;
    color: var(--muted);
    font-size: 0.6875rem;
    line-height: 1.4;
}

.field-error {
    display: block;
    margin-top: 5px;
    color: #a94f48;
    font-size: 0.6875rem;
}


/* =========================================================
   SUMMARY
========================================================= */

.summary-body {
    padding: 18px;
}

.summary-row,
.summary-tax-row,
.summary-total-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.summary-row span,
.summary-tax-row label {
    color: var(--muted);
    font-size: 0.8125rem;
    font-weight: 650;
}

.summary-row strong {
    color: var(--dark);
    font-size: 0.875rem;
}

.summary-tax-row {
    margin-top: 15px;
}

.summary-tax-input {
    width: 125px;
    position: relative;
}

.summary-tax-input span {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--muted);
    font-size: 0.75rem;
    pointer-events: none;
}

.summary-tax-input input {
    width: 100%;
    height: 38px;
    padding: 0 9px 0 25px;
    border: 1px solid #ded4cb;
    border-radius: 9px;
    background: white;
    color: var(--text);
    font-family: inherit;
    font-size: 0.8125rem;
    outline: none;
    box-sizing: border-box;
}

.summary-tax-input input:focus {
    border-color: #d2a47b;
    box-shadow:
        0 0 0 3px rgba(196, 122, 58, 0.08);
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

.summary-note {
    display: flex;
    align-items: flex-start;
    gap: 7px;
    margin-top: 15px;
    padding: 9px 10px;
    border-radius: 8px;
    background: #faf7f2;
    color: var(--muted);
    font-size: 0.6875rem;
    line-height: 1.4;
}

.summary-note span {
    color: var(--orange);
    font-size: 0.55rem;
    margin-top: 3px;
}


/* =========================================================
   ADD ITEM BUTTON
========================================================= */

.add-item-button {
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 0 11px;
    border: none;
    border-radius: 8px;
    background:
        linear-gradient(
            135deg,
            var(--orange),
            var(--orange-dark)
        );
    color: white;
    font-family: inherit;
    font-size: 0.75rem;
    font-weight: 700;
    cursor: pointer;
    transition:
        transform 0.15s ease,
        box-shadow 0.15s ease;
}

.add-item-button:hover {
    box-shadow:
        0 4px 10px rgba(168, 95, 40, 0.14);
    transform: translateY(-1px);
}

.add-item-button span {
    font-size: 1rem;
    line-height: 1;
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
    min-width: 760px;
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
    padding: 12px 14px;
    border-bottom: 1px solid #f0ebe6;
    vertical-align: middle;
}

.items-table tr:last-child td {
    border-bottom: none;
}

.items-table select,
.items-table input {
    height: 37px;
    width: 100%;
    border: 1px solid #ded4cb;
    border-radius: 9px;
    background: white;
    color: var(--text);
    font-family: inherit;
    font-size: 0.8125rem;
    outline: none;
    box-sizing: border-box;
}

.items-table select {
    min-width: 240px;
    padding: 0 10px;
}

.items-table input {
    padding: 0 10px;
}

.items-table select:focus,
.items-table input:focus {
    border-color: #d2a47b;
    box-shadow:
        0 0 0 3px rgba(196, 122, 58, 0.08);
}


/* =========================================================
   COST
========================================================= */

.cost-input {
    position: relative;
}

.cost-input > span {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--muted);
    font-size: 0.75rem;
    pointer-events: none;
}

.cost-input input {
    padding-left: 25px;
}


/* =========================================================
   ITEM SUBTOTAL
========================================================= */

.item-subtotal {
    color: var(--dark);
    font-size: 0.8125rem;
    font-weight: 800;
    white-space: nowrap;
}


/* =========================================================
   REMOVE BUTTON
========================================================= */

.remove-item-button {
    width: 29px;
    height: 29px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e5d7ce;
    border-radius: 7px;
    background: white;
    color: #9d7560;
    font-size: 1rem;
    line-height: 1;
    cursor: pointer;
    transition:
        background 0.15s ease,
        border-color 0.15s ease,
        color 0.15s ease;
}

.remove-item-button:hover {
    background: #fff3ef;
    border-color: #e2c3ba;
    color: #a94f48;
}


/* =========================================================
   ADDITIONAL INFORMATION
========================================================= */

.additional-information-body {
    padding: 18px;
}


/* =========================================================
   ACTIONS
========================================================= */

.form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 9px;
    margin-top: 18px;
    padding-bottom: 5px;
}

.cancel-button,
.save-button {
    min-height: 41px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 0 15px;
    border-radius: 9px;
    font-family: inherit;
    font-size: 0.8125rem;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
}

.cancel-button {
    border: 1px solid var(--border);
    background: white;
    color: var(--muted);
}

.cancel-button:hover {
    background: #faf7f3;
    color: var(--dark);
}

.save-button {
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

.save-button:hover {
    color: white;
    box-shadow:
        0 5px 13px rgba(168, 95, 40, 0.18);
}

.save-button span {
    font-size: 0.75rem;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1100px) {

    .information-grid {
        grid-template-columns: 1fr;
    }

    .form-panel-body {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

}


@media (max-width: 700px) {

    .purchase-reference-bar {
        align-items: flex-start;
        flex-direction: column;
    }

    .purchase-reference-item {
        width: 100%;
        justify-content: space-between;
    }

    .form-panel-body {
        grid-template-columns: 1fr;
    }

    .form-panel-header-action {
        align-items: flex-start;
        flex-direction: column;
    }

    .add-item-button {
        width: 100%;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .cancel-button,
    .save-button {
        width: 100%;
    }

}


@media (max-width: 480px) {

    .purchase-reference-bar {
        padding: 11px 14px;
    }

    .form-panel-header,
    .form-panel-body,
    .summary-body,
    .additional-information-body {
        padding-left: 14px;
        padding-right: 14px;
    }

}

</style>

@endpush


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const itemsBody =
        document.getElementById('purchaseItemsBody');

    const addItemButton =
        document.getElementById('addItemButton');

    const taxInput =
        document.getElementById('tax');

    const subtotalDisplay =
        document.getElementById('subtotalDisplay');

    const totalDisplay =
        document.getElementById('totalDisplay');


    /*
    |--------------------------------------------------------------------------
    | INVENTORY OPTIONS
    |--------------------------------------------------------------------------
    |
    | Instead of @json(), create the option HTML directly from Blade.
    | This avoids the Blade/PHP parser problem encountered previously.
    |
    */

    const inventoryOptions = `
        <option value="">
            Select inventory item
        </option>

        @foreach ($inventoryItems as $inventoryItem)

            <option value="{{ $inventoryItem->id }}">
                {{ $inventoryItem->name }}
            </option>

        @endforeach
    `;


    /*
    |--------------------------------------------------------------------------
    | FORMAT MONEY
    |--------------------------------------------------------------------------
    */

    function formatMoney(value) {

        return '₱' +
            Number(value || 0).toLocaleString(
                'en-PH',
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );

    }


    /*
    |--------------------------------------------------------------------------
    | CALCULATE ONE ROW
    |--------------------------------------------------------------------------
    */

    function calculateRow(row) {

        const quantityInput =
            row.querySelector('.item-quantity');

        const costInput =
            row.querySelector('.item-unit-cost');

        const subtotalElement =
            row.querySelector('.item-subtotal');

        const quantity =
            parseFloat(quantityInput.value) || 0;

        const unitCost =
            parseFloat(costInput.value) || 0;

        const subtotal =
            quantity * unitCost;

        subtotalElement.textContent =
            formatMoney(subtotal);

        calculateTotals();

    }


    /*
    |--------------------------------------------------------------------------
    | CALCULATE TOTALS
    |--------------------------------------------------------------------------
    */

    function calculateTotals() {

        let subtotal = 0;

        itemsBody
            .querySelectorAll('.purchase-item-row')
            .forEach(function (row) {

                const quantityInput =
                    row.querySelector('.item-quantity');

                const costInput =
                    row.querySelector('.item-unit-cost');

                const quantity =
                    parseFloat(quantityInput.value) || 0;

                const unitCost =
                    parseFloat(costInput.value) || 0;

                subtotal +=
                    quantity * unitCost;

            });


        const tax =
            parseFloat(taxInput.value) || 0;

        const total =
            subtotal + tax;


        subtotalDisplay.textContent =
            formatMoney(subtotal);

        totalDisplay.textContent =
            formatMoney(total);

    }


    /*
    |--------------------------------------------------------------------------
    | REINDEX ROWS
    |--------------------------------------------------------------------------
    */

    function reindexRows() {

        itemsBody
            .querySelectorAll('.purchase-item-row')
            .forEach(function (row, index) {

                row.querySelector('.item-select').name =
                    `items[${index}][inventory_item_id]`;

                row.querySelector('.item-quantity').name =
                    `items[${index}][quantity]`;

                row.querySelector('.item-unit-cost').name =
                    `items[${index}][unit_cost]`;

            });

    }


    /*
    |--------------------------------------------------------------------------
    | BIND ROW
    |--------------------------------------------------------------------------
    */

    function bindRow(row) {

        const quantityInput =
            row.querySelector('.item-quantity');

        const costInput =
            row.querySelector('.item-unit-cost');

        const itemSelect =
            row.querySelector('.item-select');

        const removeButton =
            row.querySelector('.remove-item-button');


        quantityInput.addEventListener(
            'input',
            function () {

                calculateRow(row);

            }
        );


        costInput.addEventListener(
            'input',
            function () {

                calculateRow(row);

            }
        );


        itemSelect.addEventListener(
            'change',
            function () {

                calculateRow(row);

            }
        );


        removeButton.addEventListener(
            'click',
            function () {

                const rows =
                    itemsBody.querySelectorAll(
                        '.purchase-item-row'
                    );


                /*
                |--------------------------------------------------------------------------
                | KEEP AT LEAST ONE ROW
                |--------------------------------------------------------------------------
                */

                if (rows.length <= 1) {

                    itemSelect.value = '';
                    quantityInput.value = '';
                    costInput.value = '';

                    calculateRow(row);

                    return;

                }


                row.remove();

                reindexRows();

                calculateTotals();

            }
        );


        calculateRow(row);

    }


    /*
    |--------------------------------------------------------------------------
    | ADD NEW ITEM ROW
    |--------------------------------------------------------------------------
    */

    function addItemRow() {

        const row =
            document.createElement('tr');

        row.className =
            'purchase-item-row';


        row.innerHTML = `

            <td>

                <select
                    class="item-select"
                    required
                >

                    ${inventoryOptions}

                </select>

            </td>


            <td>

                <input
                    type="number"
                    class="item-quantity"
                    min="0.01"
                    step="0.01"
                    placeholder="0.00"
                    required
                >

            </td>


            <td>

                <div class="cost-input">

                    <span>
                        ₱
                    </span>

                    <input
                        type="number"
                        class="item-unit-cost"
                        min="0"
                        step="0.01"
                        placeholder="0.00"
                        required
                    >

                </div>

            </td>


            <td>

                <span class="item-subtotal">
                    ₱0.00
                </span>

            </td>


            <td>

                <button
                    type="button"
                    class="remove-item-button"
                    title="Remove item"
                >
                    ×
                </button>

            </td>

        `;


        itemsBody.appendChild(row);


        reindexRows();


        bindRow(row);

    }


    /*
    |--------------------------------------------------------------------------
    | ADD ITEM BUTTON
    |--------------------------------------------------------------------------
    */

    addItemButton.addEventListener(
        'click',
        function () {

            addItemRow();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | TAX
    |--------------------------------------------------------------------------
    */

    taxInput.addEventListener(
        'input',
        function () {

            calculateTotals();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIALIZE EXISTING ROWS
    |--------------------------------------------------------------------------
    */

    itemsBody
        .querySelectorAll('.purchase-item-row')
        .forEach(function (row) {

            bindRow(row);

        });


    /*
    |--------------------------------------------------------------------------
    | INITIAL TOTAL
    |--------------------------------------------------------------------------
    */

    calculateTotals();

});

</script>

@endpush

@endsection