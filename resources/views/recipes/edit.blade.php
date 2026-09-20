@extends('layouts.app')

@section('title', 'BiteSync | Recipe')

@section('content')

<div class="recipe-page">

    {{-- =========================================================
         TOP BAR
    ========================================================== --}}

    <div class="topbar">

        <div class="page-title">

            <small>
                Product Management
            </small>

            <h1>
                Recipe
            </h1>

            <p>
                Manage the ingredients and preparation instructions
                for this product.
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

    <div class="recipe-breadcrumb">

        <a href="{{ route('products.index') }}">
            Products
        </a>

        <span>
            /
        </span>

        <span>
            {{ $product->name }}
        </span>

        <span>
            /
        </span>

        <strong>
            Recipe
        </strong>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

    @if (session('success'))

        <div class="recipe-alert recipe-alert-success">

            <span class="recipe-alert-icon">
                ✓
            </span>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if ($errors->any())

        <div class="recipe-alert recipe-alert-error">

            <div class="recipe-alert-icon">
                !
            </div>

            <div>

                <div class="recipe-alert-title">
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

        </div>

    @endif


    {{-- =========================================================
         PRODUCT HEADER
    ========================================================== --}}

    <section class="recipe-product-card">

        <div class="recipe-product-main">

            <div class="recipe-product-icon">
                ▦
            </div>

            <div>

                <div class="recipe-product-label">
                    PRODUCT RECIPE
                </div>

                <h2>
                    {{ $product->name }}
                </h2>

                <div class="recipe-product-meta">

                    <span>
                        SKU:
                        <strong>
                            {{ $product->sku }}
                        </strong>
                    </span>

                    <span class="recipe-meta-divider">
                        •
                    </span>

                    <span>
                        Selling Price:
                        <strong>
                            ₱{{ number_format((float) $product->selling_price, 2) }}
                        </strong>
                    </span>

                    @if ($product->category)

                        <span class="recipe-meta-divider">
                            •
                        </span>

                        <span>
                            Category:
                            <strong>
                                {{ $product->category->name }}
                            </strong>
                        </span>

                    @endif

                </div>

            </div>

        </div>


        <a
            href="{{ route('products.index') }}"
            class="recipe-back-button"
        >
            <span>
                ←
            </span>

            Back to Products
        </a>

    </section>


    {{-- =========================================================
         RECIPE SUMMARY
    ========================================================== --}}

    <section class="recipe-summary">

        <div class="recipe-summary-card">

            <div class="recipe-summary-icon">
                ▦
            </div>

            <div>

                <div class="recipe-summary-label">
                    INGREDIENTS
                </div>

                <div class="recipe-summary-value">
                    {{ $recipe->items->count() }}
                </div>

            </div>

        </div>


        <div class="recipe-summary-card">

            <div class="recipe-summary-icon">
                ✓
            </div>

            <div>

                <div class="recipe-summary-label">
                    RECIPE STATUS
                </div>

                <div class="recipe-summary-status">
                    {{ $recipe->items->count() > 0 ? 'Configured' : 'Not Configured' }}
                </div>

            </div>

        </div>


        <div class="recipe-summary-card">

            <div class="recipe-summary-icon">
                ₱
            </div>

            <div>

                <div class="recipe-summary-label">
                    SELLING PRICE
                </div>

                <div class="recipe-summary-value recipe-summary-money">
                    ₱{{ number_format((float) $product->selling_price, 2) }}
                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
         MAIN RECIPE GRID
    ========================================================== --}}

    <div class="recipe-layout">


        {{-- =====================================================
             INGREDIENTS PANEL
        ====================================================== --}}

        <section class="recipe-panel">

            <div class="recipe-panel-header">

                <div>

                    <div class="recipe-panel-title">
                        Recipe Ingredients
                    </div>

                    <div class="recipe-panel-subtitle">
                        Inventory items used to prepare one unit
                        of this product.
                    </div>

                </div>

            </div>


            {{-- =================================================
                 ADD INGREDIENT
            ================================================== --}}

            <div class="recipe-add-section">

                <div class="recipe-section-heading">
                    Add Ingredient
                </div>

                <form
                    method="POST"
                    action="{{ route('recipes.items.store', $product) }}"
                    class="recipe-add-form"
                >

                    @csrf

                    <div class="recipe-form-field recipe-ingredient-field">

                        <label for="inventory_item_id">
                            Inventory Item
                        </label>

                        <select
                            name="inventory_item_id"
                            id="inventory_item_id"
                            required
                        >

                            <option value="">
                                Select an inventory item
                            </option>

                            @foreach ($inventoryItems as $inventoryItem)

                                @php
                                    $alreadyUsed = $recipe->items
                                        ->contains(
                                            'inventory_item_id',
                                            $inventoryItem->id
                                        );
                                @endphp

                                @if (!$alreadyUsed)

                                    <option
                                        value="{{ $inventoryItem->id }}"
                                        {{ old('inventory_item_id') == $inventoryItem->id ? 'selected' : '' }}
                                    >
                                        {{ $inventoryItem->name }}
                                        — {{ $inventoryItem->sku }}
                                        @if ($inventoryItem->unit)
                                            ({{ $inventoryItem->unit->abbreviation ?? $inventoryItem->unit->name }})
                                        @endif
                                    </option>

                                @endif

                            @endforeach

                        </select>

                    </div>


                    <div class="recipe-form-field recipe-quantity-field">

                        <label for="quantity">
                            Quantity
                        </label>

                        <input
                            type="number"
                            name="quantity"
                            id="quantity"
                            value="{{ old('quantity') }}"
                            min="0.0001"
                            step="0.0001"
                            placeholder="0.0000"
                            required
                        >

                    </div>


                    <button
                        type="submit"
                        class="recipe-add-button"
                    >

                        <span>
                            +
                        </span>

                        Add Ingredient

                    </button>

                </form>


                <div class="recipe-add-note">

                    <span>
                        ℹ
                    </span>

                    Quantity represents the amount of the inventory
                    item required to make <strong>one unit</strong>
                    of the product.

                </div>

            </div>


            {{-- =================================================
                 INGREDIENT TABLE
            ================================================== --}}

            <div class="recipe-table-wrapper">

                <table class="recipe-table">

                    <thead>

                        <tr>

                            <th>
                                Ingredient
                            </th>

                            <th>
                                SKU
                            </th>

                            <th>
                                Quantity
                            </th>

                            <th>
                                Unit
                            </th>

                            <th class="recipe-actions-header">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($recipe->items as $recipeItem)

                            <tr>

                                <td>

                                    <div class="recipe-ingredient-name">
                                        {{ $recipeItem->inventoryItem->name }}
                                    </div>

                                    @if ($recipeItem->inventoryItem->category)

                                        <div class="recipe-ingredient-category">
                                            {{ $recipeItem->inventoryItem->category->name }}
                                        </div>

                                    @endif

                                </td>


                                <td>

                                    <span class="recipe-sku">
                                        {{ $recipeItem->inventoryItem->sku }}
                                    </span>

                                </td>


                                <td>

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'recipes.items.update',
                                            [
                                                'product' => $product,
                                                'recipeItem' => $recipeItem
                                            ]
                                        ) }}"
                                        class="recipe-quantity-form"
                                    >

                                        @csrf
                                        @method('PUT')

                                        <input
                                            type="number"
                                            name="quantity"
                                            value="{{ number_format((float) $recipeItem->quantity, 4, '.', '') }}"
                                            min="0.0001"
                                            step="0.0001"
                                            required
                                        >

                                        <button
                                            type="submit"
                                            title="Save quantity"
                                        >
                                            ✓
                                        </button>

                                    </form>

                                </td>


                                <td>

                                    <span class="recipe-unit">

                                        {{ $recipeItem->inventoryItem->unit?->abbreviation
                                            ?? $recipeItem->inventoryItem->unit?->name
                                            ?? '—' }}

                                    </span>

                                </td>


                                <td>

                                    <div class="recipe-table-actions">

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'recipes.items.destroy',
                                                [
                                                    'product' => $product,
                                                    'recipeItem' => $recipeItem
                                                ]
                                            ) }}"
                                            onsubmit="return confirm('Remove this ingredient from the recipe?');"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="recipe-remove-button"
                                                title="Remove ingredient"
                                            >

                                                <span>
                                                    ×
                                                </span>

                                                Remove

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="recipe-empty-state"
                                >

                                    <div class="recipe-empty-icon">
                                        ▦
                                    </div>

                                    <div class="recipe-empty-title">
                                        No ingredients added yet
                                    </div>

                                    <div class="recipe-empty-description">
                                        Add inventory items above to
                                        define the ingredients for this
                                        product.
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>


        {{-- =====================================================
             INSTRUCTIONS PANEL
        ====================================================== --}}

        <section class="recipe-panel recipe-instructions-panel">

            <div class="recipe-panel-header">

                <div>

                    <div class="recipe-panel-title">
                        Preparation Instructions
                    </div>

                    <div class="recipe-panel-subtitle">
                        Optional instructions for preparing the product.
                    </div>

                </div>

            </div>


            <form
                method="POST"
                action="{{ route(
                    'recipes.instructions.update',
                    $product
                ) }}"
                class="recipe-instructions-form"
            >

                @csrf
                @method('PUT')

                <div class="recipe-form-field">

                    <label for="instructions">
                        Instructions
                    </label>

                    <textarea
                        name="instructions"
                        id="instructions"
                        rows="12"
                        maxlength="5000"
                        placeholder="Enter preparation instructions, cooking steps, serving notes, or other useful information..."
                    >{{ old('instructions', $recipe->instructions) }}</textarea>

                    <div class="recipe-field-help">
                        Maximum 5,000 characters.
                    </div>

                </div>


                <button
                    type="submit"
                    class="recipe-save-button"
                >

                    <span>
                        ✓
                    </span>

                    Save Instructions

                </button>

            </form>


            {{-- =================================================
                 INFORMATION NOTE
            ================================================== --}}

            <div class="recipe-info-box">

                <div class="recipe-info-icon">
                    ℹ
                </div>

                <div>

                    <div class="recipe-info-title">
                        How recipe quantities work
                    </div>

                    <div class="recipe-info-text">

                        Each ingredient quantity represents the amount
                        consumed when one unit of this product is sold.
                        These recipe records can later be used to
                        calculate inventory consumption.

                    </div>

                </div>

            </div>

        </section>

    </div>


    {{-- =========================================================
         FOOTER NOTE
    ========================================================== --}}

    <div class="recipe-footer-note">

        <span>
            ✓
        </span>

        Recipe changes are saved separately from inventory quantities.
        Managing a recipe does not directly change your current stock.

    </div>

</div>

@endsection


@push('styles')

<style>

/* =============================================================
   RECIPE PAGE
============================================================= */

.recipe-page {
    width: 100%;
}


/* =============================================================
   BREADCRUMB
============================================================= */

.recipe-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin:
        -8px 0 18px;
    color: var(--muted);
    font-size: 10px;
}

.recipe-breadcrumb a {
    color: var(--orange);
    text-decoration: none;
    font-weight: 700;
}

.recipe-breadcrumb a:hover {
    color: var(--orange-dark);
}

.recipe-breadcrumb strong {
    color: var(--dark);
    font-weight: 800;
}


/* =============================================================
   ALERTS
============================================================= */

.recipe-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 12px 14px;
    margin-bottom: 18px;
    border-radius: 10px;
    font-size: 10px;
}

.recipe-alert-success {
    color: var(--green);
    background: var(--green-light);
    border: 1px solid #d5e8d8;
}

.recipe-alert-error {
    color: var(--red);
    background: var(--red-light);
    border: 1px solid #ecd4d0;
}

.recipe-alert-icon {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 900;
}

.recipe-alert-success .recipe-alert-icon {
    background: #dcefe0;
}

.recipe-alert-error .recipe-alert-icon {
    background: #f0dcd8;
}

.recipe-alert-title {
    margin-bottom: 5px;
    font-weight: 800;
}

.recipe-alert ul {
    margin: 4px 0 0 15px;
    padding: 0;
}

.recipe-alert li {
    margin-bottom: 2px;
}


/* =============================================================
   PRODUCT HEADER
============================================================= */

.recipe-product-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 19px 21px;
    margin-bottom: 17px;
    background: white;
    border: 1px solid var(--border);
    border-radius: 17px;
    box-shadow:
        0 5px 18px
        rgba(43, 31, 23, 0.035);
}

.recipe-product-main {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
}

.recipe-product-icon {
    width: 52px;
    height: 52px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 14px;
    background:
        linear-gradient(
            135deg,
            #fbf1e7,
            #f4e3d4
        );
    color: var(--orange);
    font-size: 21px;
    font-weight: 800;
}

.recipe-product-label {
    color: var(--muted);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 0.8px;
}

.recipe-product-card h2 {
    margin: 4px 0 7px;
    color: var(--dark);
    font-size: 19px;
    font-weight: 800;
}

.recipe-product-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    color: var(--muted);
    font-size: 9px;
}

.recipe-product-meta strong {
    color: var(--text);
    font-weight: 800;
}

.recipe-meta-divider {
    color: #c7beb7;
}

.recipe-back-button {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    height: 36px;
    padding: 0 12px;
    flex-shrink: 0;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: white;
    color: var(--muted);
    text-decoration: none;
    font-size: 9px;
    font-weight: 800;
    transition:
        background-color 0.18s ease,
        color 0.18s ease,
        border-color 0.18s ease;
}

.recipe-back-button:hover {
    background: #faf7f3;
    border-color: #ddcec1;
    color: var(--dark);
}

.recipe-back-button span {
    font-size: 13px;
}


/* =============================================================
   SUMMARY CARDS
============================================================= */

.recipe-summary {
    display: grid;
    grid-template-columns:
        repeat(3, minmax(0, 1fr));
    gap: 17px;
    margin-bottom: 17px;
}

.recipe-summary-card {
    min-height: 94px;
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 17px 19px;
    background: white;
    border: 1px solid var(--border);
    border-radius: 15px;
    box-shadow:
        0 5px 18px
        rgba(43, 31, 23, 0.035);
}

.recipe-summary-icon {
    width: 39px;
    height: 39px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 10px;
    background: #faf0e7;
    color: var(--orange);
    font-size: 13px;
    font-weight: 800;
}

.recipe-summary-label {
    color: var(--muted);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 0.7px;
}

.recipe-summary-value {
    margin-top: 6px;
    color: var(--dark);
    font-size: 20px;
    line-height: 1;
    font-weight: 800;
}

.recipe-summary-money {
    font-size: 17px;
}

.recipe-summary-status {
    margin-top: 6px;
    color: var(--green);
    font-size: 12px;
    font-weight: 800;
}


/* =============================================================
   MAIN GRID
============================================================= */

.recipe-layout {
    display: grid;
    grid-template-columns:
        minmax(0, 1.55fr)
        minmax(300px, 0.75fr);
    gap: 17px;
    align-items: start;
}

.recipe-panel {
    min-width: 0;
    background: white;
    border: 1px solid var(--border);
    border-radius: 17px;
    overflow: hidden;
    box-shadow:
        0 5px 18px
        rgba(43, 31, 23, 0.035);
}

.recipe-panel-header {
    min-height: 73px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
}

.recipe-panel-title {
    color: var(--dark);
    font-size: 14px;
    font-weight: 800;
}

.recipe-panel-subtitle {
    margin-top: 4px;
    color: var(--muted);
    font-size: 9px;
    line-height: 1.5;
}


/* =============================================================
   ADD INGREDIENT
============================================================= */

.recipe-add-section {
    padding: 17px 20px;
    background: var(--card-soft);
    border-bottom: 1px solid var(--border);
}

.recipe-section-heading {
    margin-bottom: 11px;
    color: var(--dark);
    font-size: 10px;
    font-weight: 800;
}

.recipe-add-form {
    display: grid;
    grid-template-columns:
        minmax(0, 1fr)
        125px
        auto;
    align-items: end;
    gap: 10px;
}

.recipe-form-field {
    min-width: 0;
}

.recipe-form-field label {
    display: block;
    margin-bottom: 6px;
    color: var(--dark);
    font-size: 9px;
    font-weight: 800;
}

.recipe-form-field input,
.recipe-form-field select,
.recipe-form-field textarea {
    width: 100%;
    box-sizing: border-box;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: white;
    color: var(--text);
    font-family: inherit;
    font-size: 10px;
    outline: none;
    transition:
        border-color 0.18s ease,
        box-shadow 0.18s ease;
}

.recipe-form-field input,
.recipe-form-field select {
    height: 38px;
    padding: 0 11px;
}

.recipe-form-field textarea {
    min-height: 220px;
    padding: 11px;
    line-height: 1.6;
    resize: vertical;
}

.recipe-form-field input:focus,
.recipe-form-field select:focus,
.recipe-form-field textarea:focus {
    border-color: #d5a77d;
    box-shadow:
        0 0 0 3px
        rgba(196, 122, 58, 0.08);
}

.recipe-add-button {
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 0 13px;
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
    font-size: 9px;
    font-weight: 800;
    cursor: pointer;
    white-space: nowrap;
    transition:
        background-color 0.18s ease,
        box-shadow 0.18s ease;
    box-shadow:
        0 4px 11px
        rgba(168, 95, 40, 0.14);
}

.recipe-add-button:hover {
    background:
        var(--orange-dark);
    box-shadow:
        0 5px 13px
        rgba(168, 95, 40, 0.17);
}

.recipe-add-button span {
    font-size: 14px;
    line-height: 1;
}

.recipe-add-note {
    display: flex;
    align-items: flex-start;
    gap: 7px;
    margin-top: 10px;
    color: var(--muted);
    font-size: 8px;
    line-height: 1.5;
}

.recipe-add-note span {
    color: var(--orange);
    font-weight: 800;
}

.recipe-add-note strong {
    color: var(--text);
}


/* =============================================================
   INGREDIENT TABLE
============================================================= */

.recipe-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.recipe-table {
    width: 100%;
    min-width: 700px;
    border-collapse: collapse;
}

.recipe-table th {
    padding: 12px 14px;
    background: #fbf9f6;
    color: var(--muted);
    border-bottom: 1px solid var(--border);
    text-align: left;
    font-size: 8px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    white-space: nowrap;
}

.recipe-table td {
    padding: 13px 14px;
    color: #625951;
    border-bottom: 1px solid #f0ebe6;
    font-size: 10px;
    vertical-align: middle;
}

.recipe-table tbody tr {
    background: white;
}

.recipe-table tbody tr:hover {
    background: #fdfaf7;
}

.recipe-ingredient-name {
    color: var(--dark);
    font-size: 11px;
    font-weight: 800;
}

.recipe-ingredient-category {
    margin-top: 3px;
    color: var(--muted);
    font-size: 8px;
}

.recipe-sku {
    display: inline-block;
    padding: 4px 6px;
    border-radius: 5px;
    background: #f5f0eb;
    color: var(--brown);
    font-family: monospace;
    font-size: 8px;
}

.recipe-unit {
    color: var(--text);
    font-size: 9px;
    font-weight: 700;
}

.recipe-actions-header {
    text-align: center !important;
}

.recipe-quantity-form {
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.recipe-quantity-form input {
    width: 82px;
    height: 30px;
    box-sizing: border-box;
    padding: 0 7px;
    border: 1px solid var(--border);
    border-radius: 7px;
    background: white;
    color: var(--text);
    font-family: inherit;
    font-size: 9px;
    outline: none;
}

.recipe-quantity-form input:focus {
    border-color: #d5a77d;
    box-shadow:
        0 0 0 3px
        rgba(196, 122, 58, 0.08);
}

.recipe-quantity-form button {
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #d9e6db;
    border-radius: 7px;
    background: #f4faf5;
    color: var(--green);
    font-family: inherit;
    font-size: 11px;
    font-weight: 900;
    cursor: pointer;
    transition:
        background-color 0.18s ease,
        border-color 0.18s ease;
}

.recipe-quantity-form button:hover {
    background: #eaf5ec;
    border-color: #c8ddcb;
}

.recipe-table-actions {
    display: flex;
    align-items: center;
    justify-content: center;
}

.recipe-remove-button {
    min-width: 72px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    padding: 0 8px;
    border: 1px solid #ead7d2;
    border-radius: 7px;
    background: white;
    color: #9b665d;
    font-family: inherit;
    font-size: 8px;
    font-weight: 800;
    cursor: pointer;
    transition:
        background-color 0.18s ease,
        border-color 0.18s ease,
        color 0.18s ease;
}

.recipe-remove-button:hover {
    background: #fcf4f2;
    border-color: #dfc1ba;
    color: #814c43;
}

.recipe-remove-button span {
    font-size: 13px;
    line-height: 1;
}

.recipe-empty-state {
    padding: 55px 20px !important;
    text-align: center !important;
}

.recipe-empty-icon {
    width: 48px;
    height: 48px;
    margin: 0 auto 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 13px;
    background: var(--orange-light);
    color: var(--orange);
    font-size: 17px;
}

.recipe-empty-title {
    color: var(--dark);
    font-size: 12px;
    font-weight: 800;
}

.recipe-empty-description {
    max-width: 330px;
    margin: 5px auto 0;
    color: var(--muted);
    font-size: 9px;
    line-height: 1.5;
}


/* =============================================================
   INSTRUCTIONS
============================================================= */

.recipe-instructions-form {
    padding: 18px 20px;
}

.recipe-field-help {
    margin-top: 5px;
    color: #9d958f;
    font-size: 8px;
}

.recipe-save-button {
    width: 100%;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    margin-top: 13px;
    border: none;
    border-radius: 8px;
    background: var(--dark);
    color: white;
    font-family: inherit;
    font-size: 9px;
    font-weight: 800;
    cursor: pointer;
    transition:
        background-color 0.18s ease;
}

.recipe-save-button:hover {
    background: var(--dark-soft);
}

.recipe-save-button span {
    font-size: 11px;
}


/* =============================================================
   INFORMATION BOX
============================================================= */

.recipe-info-box {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    margin: 0 20px 20px;
    padding: 12px;
    border: 1px solid #eadfd6;
    border-radius: 9px;
    background: #fcf8f4;
}

.recipe-info-icon {
    width: 21px;
    height: 21px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 6px;
    background: #f5e6d8;
    color: var(--orange);
    font-size: 9px;
    font-weight: 900;
}

.recipe-info-title {
    margin-bottom: 3px;
    color: var(--dark);
    font-size: 9px;
    font-weight: 800;
}

.recipe-info-text {
    color: var(--muted);
    font-size: 8px;
    line-height: 1.55;
}


/* =============================================================
   FOOTER NOTE
============================================================= */

.recipe-footer-note {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    margin-top: 15px;
    color: var(--muted);
    font-size: 8px;
    text-align: center;
}

.recipe-footer-note span {
    color: var(--green);
    font-weight: 900;
}


/* =============================================================
   RESPONSIVE
============================================================= */

@media (max-width: 1150px) {

    .recipe-layout {
        grid-template-columns: 1fr;
    }

    .recipe-instructions-panel {
        width: 100%;
    }

}


@media (max-width: 850px) {

    .recipe-summary {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

}


@media (max-width: 700px) {

    .recipe-product-card {
        align-items: flex-start;
        flex-direction: column;
    }

    .recipe-back-button {
        width: 100%;
    }

    .recipe-summary {
        grid-template-columns: 1fr;
    }

    .recipe-add-form {
        grid-template-columns: 1fr;
    }

    .recipe-add-button {
        width: 100%;
    }

    .recipe-product-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 3px;
    }

    .recipe-meta-divider {
        display: none;
    }

}

</style>

@endpush