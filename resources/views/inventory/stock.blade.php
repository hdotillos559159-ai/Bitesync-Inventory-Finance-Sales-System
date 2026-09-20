@extends('layouts.app')

@section('title', 'BiteSync | Stock Operations')

@section('content')

<div class="stock-page">

    <!-- =========================================================
         TOP BAR
    ========================================================== -->
    <div class="stock-topbar">

        <div>
            <div class="stock-breadcrumb">
                Inventory Management
                <span>/</span>
                Stock Operations
            </div>

            <h1>Stock Operations</h1>

            <p>
                Manage stock movements for this inventory item.
            </p>
        </div>

        <a
            href="{{ route('inventory.index') }}"
            class="stock-back-button"
        >
            <span>←</span>
            Back to Inventory
        </a>

    </div>


    <!-- =========================================================
         SUCCESS MESSAGE
    ========================================================== -->
    @if (session('success'))
        <div class="stock-alert stock-alert-success">
            <span class="stock-alert-icon">✓</span>

            <div>
                <strong>Success</strong>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif


    <!-- =========================================================
         VALIDATION ERRORS
    ========================================================== -->
    @if ($errors->any())
        <div class="stock-alert stock-alert-error">
            <span class="stock-alert-icon">!</span>

            <div>
                <strong>Please check the following:</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif


    <!-- =========================================================
         ITEM SUMMARY
    ========================================================== -->
    <div class="stock-item-card">

        <div class="stock-item-left">

            <div class="stock-item-image">

                @if ($inventoryItem->image)
                    <img
                        src="{{ asset('storage/' . $inventoryItem->image) }}"
                        alt="{{ $inventoryItem->name }}"
                    >
                @else
                    <span>📦</span>
                @endif

            </div>

            <div class="stock-item-information">

                <div class="stock-item-label">
                    INVENTORY ITEM
                </div>

                <h2>
                    {{ $inventoryItem->name }}
                </h2>

                <div class="stock-item-meta">

                    <span>
                        SKU:
                        <strong>{{ $inventoryItem->sku }}</strong>
                    </span>

                    @if ($inventoryItem->category)
                        <span>
                            Category:
                            <strong>
                                {{ $inventoryItem->category->name }}
                            </strong>
                        </span>
                    @endif

                    @if ($inventoryItem->unit)
                        <span>
                            Unit:
                            <strong>
                                {{ $inventoryItem->unit->name }}
                            </strong>
                        </span>
                    @endif

                </div>

            </div>

        </div>


        <div class="stock-current">

            <div class="stock-current-label">
                CURRENT STOCK
            </div>

            <div class="stock-current-value">
                {{ number_format((float) $inventoryItem->quantity, 2) }}
            </div>

            @if ($inventoryItem->unit)
                <div class="stock-current-unit">
                    {{ $inventoryItem->unit->name }}
                </div>
            @endif

            <div class="stock-minimum">
                Minimum:
                <strong>
                    {{ number_format((float) $inventoryItem->minimum_stock, 2) }}
                </strong>
            </div>

        </div>

    </div>


    <!-- =========================================================
         OPERATIONS
    ========================================================== -->
    <div class="stock-operations-grid">


        <!-- =====================================================
             STOCK IN
        ====================================================== -->
        <div class="stock-operation-card">

            <div class="stock-operation-header">

                <div class="stock-operation-icon stock-in-icon">
                    ↓
                </div>

                <div>
                    <h3>Stock In</h3>
                    <p>
                        Add incoming stock to the inventory.
                    </p>
                </div>

            </div>


            <form
                method="POST"
                action="{{ route('inventory.stock-in', $inventoryItem) }}"
                class="stock-operation-form"
            >

                @csrf

                <div class="stock-form-group">

                    <label for="stock_in_quantity">
                        Quantity
                    </label>

                    <input
                        type="number"
                        id="stock_in_quantity"
                        name="quantity"
                        min="0.01"
                        step="0.01"
                        value="{{ old('quantity') }}"
                        placeholder="Enter quantity"
                        required
                    >

                </div>


                <div class="stock-form-group">

                    <label for="stock_in_reason">
                        Reason
                        <span>Optional</span>
                    </label>

                    <textarea
                        id="stock_in_reason"
                        name="reason"
                        rows="3"
                        placeholder="e.g. New supplier delivery"
                    >{{ old('reason') }}</textarea>

                </div>


                <button
                    type="submit"
                    class="stock-operation-button stock-in-button"
                >
                    <span>+</span>
                    Add Stock
                </button>

            </form>

        </div>


        <!-- =====================================================
             STOCK OUT
        ====================================================== -->
        <div class="stock-operation-card">

            <div class="stock-operation-header">

                <div class="stock-operation-icon stock-out-icon">
                    ↑
                </div>

                <div>
                    <h3>Stock Out</h3>
                    <p>
                        Remove stock for usage, sales, waste, or other reasons.
                    </p>
                </div>

            </div>


            <form
                method="POST"
                action="{{ route('inventory.stock-out', $inventoryItem) }}"
                class="stock-operation-form"
            >

                @csrf

                <div class="stock-form-group">

                    <label for="stock_out_quantity">
                        Quantity
                    </label>

                    <input
                        type="number"
                        id="stock_out_quantity"
                        name="quantity"
                        min="0.01"
                        max="{{ (float) $inventoryItem->quantity }}"
                        step="0.01"
                        value="{{ old('quantity') }}"
                        placeholder="Enter quantity"
                        required
                    >

                    <small>
                        Maximum available:
                        <strong>
                            {{ number_format((float) $inventoryItem->quantity, 2) }}
                        </strong>
                    </small>

                </div>


                <div class="stock-form-group">

                    <label for="stock_out_reason">
                        Reason
                        <span class="required-label">Required</span>
                    </label>

                    <textarea
                        id="stock_out_reason"
                        name="reason"
                        rows="3"
                        placeholder="e.g. Used for production"
                        required
                    >{{ old('reason') }}</textarea>

                </div>


                <button
                    type="submit"
                    class="stock-operation-button stock-out-button"
                >
                    <span>−</span>
                    Remove Stock
                </button>

            </form>

        </div>


        <!-- =====================================================
             ADJUSTMENT
        ====================================================== -->
        <div class="stock-operation-card">

            <div class="stock-operation-header">

                <div class="stock-operation-icon stock-adjust-icon">
                    ↔
                </div>

                <div>
                    <h3>Adjustment</h3>
                    <p>
                        Correct the stock quantity based on an actual count.
                    </p>
                </div>

            </div>


            <form
                method="POST"
                action="{{ route('inventory.adjust', $inventoryItem) }}"
                class="stock-operation-form"
            >

                @csrf

                <div class="stock-form-group">

                    <label for="adjust_quantity">
                        New Quantity
                    </label>

                    <input
                        type="number"
                        id="adjust_quantity"
                        name="quantity"
                        min="0"
                        step="0.01"
                        value="{{ old('quantity') }}"
                        placeholder="Enter actual quantity"
                        required
                    >

                    <small>
                        Current quantity:
                        <strong>
                            {{ number_format((float) $inventoryItem->quantity, 2) }}
                        </strong>
                    </small>

                </div>


                <div class="stock-form-group">

                    <label for="adjust_reason">
                        Reason
                        <span class="required-label">Required</span>
                    </label>

                    <textarea
                        id="adjust_reason"
                        name="reason"
                        rows="3"
                        placeholder="e.g. Physical stock count correction"
                        required
                    >{{ old('reason') }}</textarea>

                </div>


                <button
                    type="submit"
                    class="stock-operation-button stock-adjust-button"
                >
                    <span>↔</span>
                    Apply Adjustment
                </button>

            </form>

        </div>

    </div>


    <!-- =========================================================
         INFORMATION NOTE
    ========================================================== -->
    <div class="stock-information-note">

        <div class="stock-information-icon">
            i
        </div>

        <div>

            <strong>Stock quantity is managed separately</strong>

            <p>
                Use Stock In, Stock Out, or Adjustment whenever the quantity
                changes. The normal Edit page is intended for updating item
                information only. Every stock operation is recorded in the
                stock movement history for auditing.
            </p>

        </div>

    </div>

</div>


<style>

/* =============================================================
   PAGE
============================================================= */

.stock-page {
    width: 100%;
    max-width: 1450px;
    margin: 0 auto;
    padding: 28px 32px 50px;
}


/* =============================================================
   TOP BAR
============================================================= */

.stock-topbar {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 26px;
}

.stock-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 7px;
    color: #8a8177;
    font-size: 0.75rem;
    font-weight: 600;
}

.stock-breadcrumb span {
    color: #b8b0a7;
}

.stock-topbar h1 {
    margin: 0;
    color: #29251f;
    font-size: 1.7rem;
    font-weight: 800;
    letter-spacing: -0.025em;
}

.stock-topbar p {
    margin: 6px 0 0;
    color: #81786e;
    font-size: 0.85rem;
}

.stock-back-button {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    height: 38px;
    padding: 0 14px;
    border: 1px solid #ded8d0;
    border-radius: 9px;
    background: #ffffff;
    color: #62594f;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 700;
    transition:
        background-color 0.18s ease,
        border-color 0.18s ease,
        color 0.18s ease;
}

.stock-back-button:hover {
    background: #faf8f5;
    border-color: #cfc7be;
    color: #342e28;
}

.stock-back-button span {
    font-size: 1rem;
}


/* =============================================================
   ALERTS
============================================================= */

.stock-alert {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 13px 16px;
    margin-bottom: 18px;
    border-radius: 10px;
    font-size: 0.8rem;
}

.stock-alert-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    flex: 0 0 24px;
    border-radius: 50%;
    font-size: 0.75rem;
    font-weight: 800;
}

.stock-alert strong {
    display: block;
    margin-bottom: 2px;
    font-size: 0.8rem;
}

.stock-alert p {
    margin: 0;
}

.stock-alert ul {
    margin: 5px 0 0;
    padding-left: 18px;
}

.stock-alert-success {
    border: 1px solid #cfe4d6;
    background: #f3faf5;
    color: #356346;
}

.stock-alert-success .stock-alert-icon {
    background: #dcefe2;
    color: #2e7045;
}

.stock-alert-error {
    border: 1px solid #ead1d1;
    background: #fff7f7;
    color: #8a4545;
}

.stock-alert-error .stock-alert-icon {
    background: #f5dddd;
    color: #9b3d3d;
}


/* =============================================================
   ITEM SUMMARY
============================================================= */

.stock-item-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    padding: 20px 22px;
    margin-bottom: 22px;
    border: 1px solid #e4ded7;
    border-radius: 14px;
    background: #ffffff;
    box-shadow: 0 3px 12px rgba(54, 45, 35, 0.035);
}

.stock-item-left {
    display: flex;
    align-items: center;
    gap: 15px;
    min-width: 0;
}

.stock-item-image {
    width: 68px;
    height: 68px;
    flex: 0 0 68px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e6dfd7;
    border-radius: 11px;
    background: #faf8f5;
}

.stock-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.stock-item-image span {
    font-size: 1.8rem;
}

.stock-item-information {
    min-width: 0;
}

.stock-item-label {
    margin-bottom: 3px;
    color: #9a9085;
    font-size: 0.63rem;
    font-weight: 800;
    letter-spacing: 0.08em;
}

.stock-item-information h2 {
    margin: 0 0 7px;
    overflow: hidden;
    color: #302a24;
    font-size: 1.15rem;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.stock-item-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px 17px;
    color: #81786f;
    font-size: 0.72rem;
}

.stock-item-meta strong {
    color: #514941;
}

.stock-current {
    min-width: 150px;
    padding-left: 24px;
    border-left: 1px solid #ebe5de;
    text-align: right;
}

.stock-current-label {
    margin-bottom: 3px;
    color: #968c81;
    font-size: 0.62rem;
    font-weight: 800;
    letter-spacing: 0.08em;
}

.stock-current-value {
    color: #315e43;
    font-size: 1.55rem;
    font-weight: 850;
    line-height: 1.1;
}

.stock-current-unit {
    margin-top: 2px;
    color: #8a8178;
    font-size: 0.7rem;
}

.stock-minimum {
    margin-top: 7px;
    color: #91877c;
    font-size: 0.7rem;
}

.stock-minimum strong {
    color: #665e55;
}


/* =============================================================
   OPERATIONS GRID
============================================================= */

.stock-operations-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
}

.stock-operation-card {
    display: flex;
    flex-direction: column;
    min-width: 0;
    padding: 20px;
    border: 1px solid #e5dfd8;
    border-radius: 13px;
    background: #ffffff;
    box-shadow: 0 3px 12px rgba(54, 45, 35, 0.03);
}

.stock-operation-header {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    min-height: 61px;
    margin-bottom: 17px;
}

.stock-operation-icon {
    width: 37px;
    height: 37px;
    flex: 0 0 37px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    font-size: 1rem;
    font-weight: 850;
}

.stock-in-icon {
    background: #eaf6ee;
    color: #36744d;
}

.stock-out-icon {
    background: #fff0ed;
    color: #a94e3e;
}

.stock-adjust-icon {
    background: #f2effb;
    color: #675a92;
}

.stock-operation-header h3 {
    margin: 1px 0 4px;
    color: #302a24;
    font-size: 0.95rem;
    font-weight: 800;
}

.stock-operation-header p {
    margin: 0;
    color: #8b8278;
    font-size: 0.72rem;
    line-height: 1.5;
}


/* =============================================================
   FORMS
============================================================= */

.stock-operation-form {
    display: flex;
    flex: 1;
    flex-direction: column;
}

.stock-form-group {
    margin-bottom: 14px;
}

.stock-form-group label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 6px;
    color: #514a43;
    font-size: 0.73rem;
    font-weight: 750;
}

.stock-form-group label span {
    color: #9b9187;
    font-size: 0.65rem;
    font-weight: 600;
}

.stock-form-group label .required-label {
    color: #a85a4c;
}

.stock-form-group input,
.stock-form-group textarea {
    width: 100%;
    box-sizing: border-box;
    border: 1px solid #dcd5cd;
    border-radius: 8px;
    background: #fcfbf9;
    color: #332d27;
    font-family: inherit;
    font-size: 0.78rem;
    outline: none;
    transition:
        border-color 0.18s ease,
        background-color 0.18s ease,
        box-shadow 0.18s ease;
}

.stock-form-group input {
    height: 39px;
    padding: 0 11px;
}

.stock-form-group textarea {
    min-height: 78px;
    padding: 9px 11px;
    resize: vertical;
}

.stock-form-group input:focus,
.stock-form-group textarea:focus {
    border-color: #b7aa9b;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(183, 170, 155, 0.12);
}

.stock-form-group input::placeholder,
.stock-form-group textarea::placeholder {
    color: #b2aaa1;
}

.stock-form-group small {
    display: block;
    margin-top: 5px;
    color: #9a9188;
    font-size: 0.65rem;
}

.stock-form-group small strong {
    color: #6d645b;
}


/* =============================================================
   OPERATION BUTTONS
============================================================= */

.stock-operation-button {
    width: 100%;
    height: 39px;
    margin-top: auto;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    border: 0;
    border-radius: 8px;
    font-family: inherit;
    font-size: 0.76rem;
    font-weight: 800;
    cursor: pointer;
    transition:
        transform 0.15s ease,
        filter 0.15s ease;
}

.stock-operation-button:hover {
    filter: brightness(0.97);
    transform: translateY(-1px);
}

.stock-operation-button:active {
    transform: translateY(0);
}

.stock-operation-button span {
    font-size: 1rem;
    line-height: 1;
}

.stock-in-button {
    background: #e5f3e9;
    color: #316a45;
}

.stock-out-button {
    background: #fae9e5;
    color: #994b3d;
}

.stock-adjust-button {
    background: #eeeaf8;
    color: #62558c;
}


/* =============================================================
   INFORMATION NOTE
============================================================= */

.stock-information-note {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    margin-top: 20px;
    padding: 14px 16px;
    border: 1px solid #e2ddd6;
    border-radius: 10px;
    background: #faf9f7;
}

.stock-information-icon {
    width: 22px;
    height: 22px;
    flex: 0 0 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #cfc8bf;
    border-radius: 50%;
    color: #756c62;
    font-size: 0.68rem;
    font-weight: 800;
}

.stock-information-note strong {
    display: block;
    margin-bottom: 3px;
    color: #514941;
    font-size: 0.75rem;
}

.stock-information-note p {
    margin: 0;
    color: #898078;
    font-size: 0.7rem;
    line-height: 1.55;
}


/* =============================================================
   RESPONSIVE
============================================================= */

@media (max-width: 1050px) {

    .stock-operations-grid {
        grid-template-columns: 1fr;
    }

    .stock-operation-header {
        min-height: auto;
    }

}


@media (max-width: 700px) {

    .stock-page {
        padding: 20px 16px 35px;
    }

    .stock-topbar {
        flex-direction: column;
    }

    .stock-back-button {
        align-self: flex-start;
    }

    .stock-item-card {
        align-items: flex-start;
        flex-direction: column;
    }

    .stock-current {
        width: 100%;
        box-sizing: border-box;
        padding-top: 15px;
        padding-left: 0;
        border-top: 1px solid #ebe5de;
        border-left: 0;
        text-align: left;
    }

    .stock-item-meta {
        flex-direction: column;
        gap: 4px;
    }

}

</style>

@endsection