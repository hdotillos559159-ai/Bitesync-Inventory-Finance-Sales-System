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
                Create a new purchase order and add the inventory items you need.
            </p>

        </div>

        <div class="date-box">

            <span class="date-icon">
                ◷
            </span>

            <div>

                <small>
                    Today
                </small>

                <strong>
                    {{ now()->format('F d, Y') }}
                </strong>

            </div>

        </div>

    </div>


    {{-- =========================================================
         BREADCRUMB
    ========================================================== --}}

    <div class="breadcrumb">

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

        <div class="alert alert-danger">

            <div class="alert-icon">
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

                <div class="panel-header">

                    <div class="panel-icon">
                        🧾
                    </div>

                    <div>

                        <h2>
                            Purchase Information
                        </h2>

                        <p>
                            Enter the basic details of this purchase.
                        </p>

                    </div>

                </div>


                <div class="panel-body">


                    {{-- Supplier --}}

                    <div class="form-group">

                        <label for="supplier_id">

                            Supplier

                            <span class="required">
                                *
                            </span>

                        </label>

                        <select
                            name="supplier_id"
                            id="supplier_id"
                            class="form-control @error('supplier_id') is-invalid @enderror"
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

                        <span class="helper-text">
                            Select the supplier for this purchase order.
                        </span>

                    </div>


                    {{-- Purchase Date --}}

                    <div class="form-group">

                        <label for="purchase_date">

                            Purchase Date

                            <span class="required">
                                *
                            </span>

                        </label>

                        <input
                            type="date"
                            name="purchase_date"
                            id="purchase_date"
                            class="form-control @error('purchase_date') is-invalid @enderror"
                            value="{{ old('purchase_date', now()->format('Y-m-d')) }}"
                            required
                        >

                        <span class="helper-text">
                            Date when the purchase order is created.
                        </span>

                    </div>


                    {{-- Expected Delivery --}}

                    <div class="form-group">

                        <label for="expected_date">
                            Expected Delivery
                        </label>

                        <input
                            type="date"
                            name="expected_date"
                            id="expected_date"
                            class="form-control @error('expected_date') is-invalid @enderror"
                            value="{{ old('expected_date') }}"
                        >

                        <span class="helper-text">
                            Optional expected delivery date.
                        </span>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 PURCHASE SUMMARY
            ================================================== --}}

            <div class="form-panel">

                <div class="panel-header">

                    <div class="panel-icon">
                        ₱
                    </div>

                    <div>

                        <h2>
                            Purchase Summary
                        </h2>

                        <p>
                            Review the calculated purchase amount.
                        </p>

                    </div>

                </div>


                <div class="panel-body">


                    {{-- Subtotal --}}

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


                    {{-- Tax --}}

                    <div class="form-group tax-group">

                        <label for="tax">
                            Tax
                        </label>

                        <div class="input-prefix">

                            <span>
                                ₱
                            </span>

                            <input
                                type="number"
                                name="tax"
                                id="tax"
                                class="form-control @error('tax') is-invalid @enderror"
                                value="{{ old('tax', 0) }}"
                                min="0"
                                step="0.01"
                            >

                        </div>

                        <span class="helper-text">
                            Enter the tax amount for this purchase.
                        </span>

                    </div>


                    {{-- Total --}}

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


                    {{-- Draft Notice --}}

                    <div class="draft-note">

                        <span class="draft-note-icon">
                            i
                        </span>

                        <span>
                            New purchases are saved as
                            <strong>Draft</strong>
                            until submitted for approval.
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             PURCHASE ITEMS
        ====================================================== --}}

        <div class="form-panel full-panel">

            <div class="panel-header panel-header-with-action">

                <div class="panel-header-left">

                    <div class="panel-icon">
                        📦
                    </div>

                    <div>

                        <h2>
                            Purchase Items
                        </h2>

                        <p>
                            Add the inventory items included in this purchase.
                        </p>

                    </div>

                </div>


                <button
                    type="button"
                    class="btn btn-secondary"
                    id="addItemBtn"
                >

                    <span>
                        +
                    </span>

                    Add Item

                </button>

            </div>


            <div class="panel-body items-panel-body">

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


                                    {{-- Inventory Item --}}

                                    <td>

                                        <select
                                            name="items[{{ $index }}][inventory_item_id]"
                                            class="form-control item-select"
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


                                    {{-- Quantity --}}

                                    <td>

                                        <input
                                            type="number"
                                            name="items[{{ $index }}][quantity]"
                                            class="form-control quantity-input"
                                            value="{{ $oldItem['quantity'] ?? '' }}"
                                            min="0.01"
                                            step="0.01"
                                            placeholder="0"
                                            required
                                        >

                                    </td>


                                    {{-- Unit Cost --}}

                                    <td>

                                        <div class="input-prefix">

                                            <span>
                                                ₱
                                            </span>

                                            <input
                                                type="number"
                                                name="items[{{ $index }}][unit_cost]"
                                                class="form-control unit-cost-input"
                                                value="{{ $oldItem['unit_cost'] ?? '' }}"
                                                min="0"
                                                step="0.01"
                                                placeholder="0.00"
                                                required
                                            >

                                        </div>

                                    </td>


                                    {{-- Subtotal --}}

                                    <td>

                                        <div class="row-subtotal">
                                            ₱0.00
                                        </div>

                                    </td>


                                    {{-- Remove --}}

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

                    <span class="helper-icon">
                        i
                    </span>

                    <span>
                        Add all inventory items included in this purchase. The subtotal is calculated automatically.
                    </span>

                </div>

            </div>

        </div>


        {{-- =====================================================
             HIDDEN TEMPLATE FOR NEW ITEMS
        ====================================================== --}}

        <template id="itemRowTemplate">

            <tr class="item-row">

                <td>

                    <select
                        class="form-control item-select"
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
                        class="form-control quantity-input"
                        min="0.01"
                        step="0.01"
                        placeholder="0"
                        required
                    >

                </td>


                <td>

                    <div class="input-prefix">

                        <span>
                            ₱
                        </span>

                        <input
                            type="number"
                            class="form-control unit-cost-input"
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

        <div class="form-panel full-panel">

            <div class="panel-header">

                <div class="panel-icon">
                    📝
                </div>

                <div>

                    <h2>
                        Additional Information
                    </h2>

                    <p>
                        Add notes or special instructions for this purchase.
                    </p>

                </div>

            </div>


            <div class="panel-body">

                <div class="form-group">

                    <label for="notes">
                        Notes
                    </label>

                    <textarea
                        name="notes"
                        id="notes"
                        class="form-control textarea-control @error('notes') is-invalid @enderror"
                        rows="5"
                        placeholder="Enter any additional notes or instructions..."
                    >{{ old('notes') }}</textarea>

                    <span class="helper-text">
                        Optional. You can include delivery instructions, special requests, or other purchase details.
                    </span>

                </div>

            </div>

        </div>


        {{-- =====================================================
             FORM ACTIONS
        ====================================================== --}}

        <div class="form-actions">

            <a
                href="{{ route('purchases.index') }}"
                class="btn btn-cancel"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >

                <span>
                    ✓
                </span>

                Create Purchase

            </button>

        </div>

    </form>

</div>

@endsection


@push('styles')

<style>

/* ============================================================
   PAGE
============================================================ */

.purchase-create-page {
    width: 100%;
    max-width: 1500px;
    margin: 0 auto;
}


/* ============================================================
   TOPBAR
============================================================ */

.purchase-create-page .topbar {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 30px;
    margin-bottom: 18px;
}

.purchase-create-page .page-title small {
    display: block;
    margin-bottom: 5px;
    color: var(--orange);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.1px;
    text-transform: uppercase;
}

.purchase-create-page .page-title h1 {
    margin: 0;
    color: #2b1f17;
    font-size: 29px;
    line-height: 1.15;
    font-weight: 700;
}

.purchase-create-page .page-title p {
    margin: 7px 0 0;
    color: #84776d;
    font-size: 13px;
    line-height: 1.5;
}

.purchase-create-page .date-box {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 165px;
    padding: 11px 14px;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: #ffffff;
    box-shadow: 0 4px 15px rgba(43, 31, 23, .035);
}

.purchase-create-page .date-icon {
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

.purchase-create-page .date-box small {
    display: block;
    margin-bottom: 2px;
    color: #978a80;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .7px;
}

.purchase-create-page .date-box strong {
    color: #3a2c23;
    font-size: 12px;
    font-weight: 700;
}


/* ============================================================
   BREADCRUMB
============================================================ */

.purchase-create-page .breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
    color: #95887e;
    font-size: 12px;
}

.purchase-create-page .breadcrumb a {
    color: var(--orange);
    text-decoration: none;
    font-weight: 600;
}

.purchase-create-page .breadcrumb strong {
    color: #5f5148;
    font-weight: 600;
}


/* ============================================================
   ALERT
============================================================ */

.purchase-create-page .alert {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 20px;
    padding: 13px 15px;
    border-radius: 11px;
    font-size: 12px;
}

.purchase-create-page .alert-danger {
    border: 1px solid #efcaca;
    background: #fff6f6;
    color: #8f3636;
}

.purchase-create-page .alert-icon {
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

.purchase-create-page .alert strong {
    display: block;
    margin-bottom: 5px;
}

.purchase-create-page .alert ul {
    margin: 0;
    padding-left: 17px;
}


/* ============================================================
   GRID
============================================================ */

.purchase-create-page .information-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 18px;
}


/* ============================================================
   PANELS
============================================================ */

.purchase-create-page .form-panel {
    overflow: hidden;
    border: 1px solid var(--border);
    border-radius: 17px;
    background: #ffffff;
    box-shadow: 0 5px 18px rgba(43, 31, 23, .035);
}

.purchase-create-page .full-panel {
    margin-bottom: 18px;
}

.purchase-create-page .panel-header {
    display: flex;
    align-items: center;
    gap: 12px;
    min-height: 68px;
    padding: 15px 18px;
    border-bottom: 1px solid #eee7e1;
}

.purchase-create-page .panel-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.purchase-create-page .panel-header-with-action {
    justify-content: space-between;
}

.purchase-create-page .panel-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
    width: 32px;
    height: 32px;
    border-radius: 9px;
    background: var(--orange-light);
    color: var(--orange);
    font-size: 15px;
}

.purchase-create-page .panel-header h2 {
    margin: 0;
    color: #35281f;
    font-size: 19px;
    line-height: 1.2;
    font-weight: 700;
}

.purchase-create-page .panel-header p {
    margin: 3px 0 0;
    color: #93877d;
    font-size: 12px;
    line-height: 1.4;
}

.purchase-create-page .panel-body {
    padding: 18px;
}


/* ============================================================
   FORM CONTROLS
============================================================ */

.purchase-create-page .form-group {
    margin-bottom: 16px;
}

.purchase-create-page .form-group:last-child {
    margin-bottom: 0;
}

.purchase-create-page label {
    display: block;
    margin-bottom: 7px;
    color: #4a3b31;
    font-size: 12px;
    font-weight: 700;
}

.purchase-create-page .required {
    color: #d75f4f;
}

.purchase-create-page .form-control {
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
    transition:
        border-color .18s ease,
        box-shadow .18s ease;
}

.purchase-create-page select.form-control {
    cursor: pointer;
}

.purchase-create-page textarea.form-control {
    height: auto;
    min-height: 115px;
    padding: 11px;
    resize: vertical;
    line-height: 1.5;
}

.purchase-create-page .form-control::placeholder {
    color: #b0a59d;
}

.purchase-create-page .form-control:focus {
    border-color: #d2a47b;
    box-shadow: 0 0 0 3px rgba(210, 164, 123, .12);
}

.purchase-create-page .form-control.is-invalid {
    border-color: #d9534f;
}

.purchase-create-page .helper-text {
    display: block;
    margin-top: 6px;
    color: #988b82;
    font-size: 11px;
    line-height: 1.45;
}


/* ============================================================
   CURRENCY INPUT
============================================================ */

.purchase-create-page .input-prefix {
    position: relative;
}

.purchase-create-page .input-prefix > span {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 2;
    display: flex;
    align-items: center;
    height: 41px;
    padding-left: 11px;
    color: #8a7b70;
    font-size: 13px;
    pointer-events: none;
}

.purchase-create-page .input-prefix .form-control {
    padding-left: 28px;
}


/* ============================================================
   SUMMARY
============================================================ */

.purchase-create-page .summary-row,
.purchase-create-page .summary-total {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.purchase-create-page .summary-row {
    padding-bottom: 15px;
    margin-bottom: 15px;
    border-bottom: 1px solid #eee7e1;
}

.purchase-create-page .summary-row span,
.purchase-create-page .summary-total span {
    display: block;
    color: #4b3c32;
    font-size: 13px;
    font-weight: 700;
}

.purchase-create-page .summary-row small,
.purchase-create-page .summary-total small {
    display: block;
    margin-top: 3px;
    color: #988b82;
    font-size: 10px;
}

.purchase-create-page .summary-row strong {
    color: #4d3a2e;
    font-size: 17px;
}

.purchase-create-page .tax-group {
    padding-bottom: 15px;
    margin-bottom: 15px;
    border-bottom: 1px solid #eee7e1;
}

.purchase-create-page .summary-total strong {
    color: var(--orange);
    font-size: 22px;
    font-weight: 800;
}

.purchase-create-page .draft-note {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    margin-top: 18px;
    padding: 10px 11px;
    border: 1px solid #eee3d8;
    border-radius: 9px;
    background: #fcf9f5;
    color: #88796e;
    font-size: 11px;
    line-height: 1.5;
}

.purchase-create-page .draft-note strong {
    color: #645044;
}

.purchase-create-page .draft-note-icon {
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
   BUTTONS
============================================================ */

.purchase-create-page .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 41px;
    padding: 0 16px;
    border: 0;
    border-radius: 9px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    transition:
        transform .18s ease,
        box-shadow .18s ease,
        background .18s ease;
}

.purchase-create-page .btn:hover {
    transform: translateY(-1px);
}

.purchase-create-page .btn-primary {
    color: #ffffff;
    background: linear-gradient(
        135deg,
        var(--orange),
        #bd8957
    );
    box-shadow: 0 5px 12px rgba(180, 125, 75, .18);
}

.purchase-create-page .btn-primary:hover {
    box-shadow: 0 7px 16px rgba(180, 125, 75, .24);
}

.purchase-create-page .btn-cancel {
    border: 1px solid #ded4cb;
    background: #ffffff;
    color: #66584f;
}

.purchase-create-page .btn-cancel:hover {
    background: #faf7f4;
}

.purchase-create-page .btn-secondary {
    min-height: 36px;
    padding: 0 13px;
    border: 1px solid #e0d4ca;
    background: #ffffff;
    color: #76553c;
}

.purchase-create-page .btn-secondary:hover {
    border-color: #d1b39a;
    background: #fdf9f5;
}

.purchase-create-page .btn-secondary span {
    font-size: 17px;
    line-height: 1;
}


/* ============================================================
   ITEMS TABLE
============================================================ */

.purchase-create-page .items-panel-body {
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
    padding: 10px;
    border-bottom: 1px solid #e8e0d9;
    background: #fcfaf8;
    color: #796b61;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .4px;
    text-align: left;
    text-transform: uppercase;
}

.purchase-create-page .items-table td {
    padding: 10px;
    border-bottom: 1px solid #eee8e2;
    vertical-align: middle;
}

.purchase-create-page .items-table tbody tr:last-child td {
    border-bottom: 0;
}

.purchase-create-page .items-table .form-control {
    height: 39px;
}

.purchase-create-page .items-table .input-prefix > span {
    height: 39px;
}

.purchase-create-page .row-subtotal {
    min-height: 39px;
    display: flex;
    align-items: center;
    padding: 0 10px;
    border: 1px solid #e8e0d9;
    border-radius: 9px;
    background: #fcfaf8;
    color: #4d3b30;
    font-size: 13px;
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
    width: 32px;
    height: 32px;
    border: 1px solid #ead7d3;
    border-radius: 8px;
    background: #fff8f7;
    color: #c45e55;
    font-size: 20px;
    line-height: 1;
    cursor: pointer;
    transition:
        background .18s ease,
        border-color .18s ease,
        transform .18s ease;
}

.purchase-create-page .remove-item-btn:hover {
    border-color: #dca9a3;
    background: #fff1ef;
    transform: translateY(-1px);
}

.purchase-create-page .items-helper {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    margin-top: 12px;
    padding: 10px 11px;
    border-radius: 9px;
    background: #fcf9f5;
    color: #8c7e74;
    font-size: 11px;
    line-height: 1.5;
}

.purchase-create-page .helper-icon {
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
   FORM ACTIONS
============================================================ */

.purchase-create-page .form-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 9px;
    padding-bottom: 20px;
}


/* ============================================================
   RESPONSIVE
============================================================ */

@media (max-width: 1100px) {

    .purchase-create-page .information-grid {
        grid-template-columns: 1fr;
    }

}

@media (max-width: 700px) {

    .purchase-create-page .topbar {
        flex-direction: column;
        gap: 15px;
    }

    .purchase-create-page .date-box {
        width: 100%;
    }

    .purchase-create-page .panel-header-with-action {
        align-items: flex-start;
        flex-direction: column;
    }

    .purchase-create-page .btn-secondary {
        width: 100%;
    }

    .purchase-create-page .form-actions {
        flex-direction: column-reverse;
    }

    .purchase-create-page .form-actions .btn {
        width: 100%;
    }

}

@media (max-width: 480px) {

    .purchase-create-page .page-title h1 {
        font-size: 25px;
    }

    .purchase-create-page .panel-header {
        padding: 14px;
    }

    .purchase-create-page .panel-body {
        padding: 14px;
    }

}

</style>

@endpush


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Elements
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | Re-index item rows
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | Calculate one row
    |--------------------------------------------------------------------------
    */

    function calculateRow(row) {

        const quantityInput =
            row.querySelector('.quantity-input');

        const unitCostInput =
            row.querySelector('.unit-cost-input');

        const subtotalElement =
            row.querySelector('.row-subtotal');


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


    /*
    |--------------------------------------------------------------------------
    | Calculate all totals
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | Attach row events
    |--------------------------------------------------------------------------
    */

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
                    |--------------------------------------------------------------------------
                    | Never remove the final row completely.
                    |--------------------------------------------------------------------------
                    */

                    if (rows.length === 1) {

                        const select =
                            row.querySelector('.item-select');

                        const quantity =
                            row.querySelector('.quantity-input');

                        const unitCost =
                            row.querySelector('.unit-cost-input');


                        select.value = '';

                        quantity.value = '';

                        unitCost.value = '';


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


    /*
    |--------------------------------------------------------------------------
    | Add new item row
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | Add Item button
    |--------------------------------------------------------------------------
    */

    if (addItemBtn) {

        addItemBtn.addEventListener(
            'click',
            function () {

                addItemRow();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Tax
    |--------------------------------------------------------------------------
    */

    if (taxInput) {

        taxInput.addEventListener(
            'input',
            function () {

                calculateTotals();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Existing rows
    |--------------------------------------------------------------------------
    */

    const existingRows =
        itemsContainer.querySelectorAll('.item-row');


    existingRows.forEach(function (row) {

        attachRowEvents(row);

    });


    /*
    |--------------------------------------------------------------------------
    | Initial setup
    |--------------------------------------------------------------------------
    */

    reindexRows();

    calculateTotals();

});

</script>

@endpush