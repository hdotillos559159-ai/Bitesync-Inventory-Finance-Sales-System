@extends('layouts.app')

@section('title', 'BiteSync | Recipe')

@section('content')

<div class="recipe-page">

    <!-- =========================================================
         TOPBAR
         MATCH ADD PRODUCT
    ========================================================== -->

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


    <!-- =========================================================
         BREADCRUMB
    ========================================================== -->

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


    <!-- =========================================================
         SUCCESS MESSAGE
    ========================================================== -->

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


    <!-- =========================================================
         VALIDATION ERRORS
    ========================================================== -->

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


    <!-- =========================================================
         PRODUCT INFORMATION
    ========================================================== -->

    <section class="recipe-product-card">

        <div class="recipe-product-main">

            <div class="recipe-product-icon">
                ▦
            </div>

            <div class="recipe-product-information">

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


    <!-- =========================================================
         RECIPE SUMMARY
    ========================================================== -->

    <section class="recipe-summary">

        <!-- INGREDIENTS -->

        <div class="recipe-summary-card">

            <div class="recipe-summary-icon">
                ▦
            </div>

            <div class="recipe-summary-content">

                <div class="recipe-summary-label">
                    INGREDIENTS
                </div>

                <div class="recipe-summary-value">
                    {{ $recipe->items->count() }}
                </div>

            </div>

        </div>


        <!-- RECIPE STATUS -->

        <div class="recipe-summary-card">

            <div class="recipe-summary-icon">
                ✓
            </div>

            <div class="recipe-summary-content">

                <div class="recipe-summary-label">
                    RECIPE STATUS
                </div>

                <div class="recipe-summary-status">
                    {{ $recipe->items->count() > 0 ? 'Configured' : 'Not Configured' }}
                </div>

            </div>

        </div>


        <!-- SELLING PRICE -->

        <div class="recipe-summary-card">

            <div class="recipe-summary-icon">
                ₱
            </div>

            <div class="recipe-summary-content">

                <div class="recipe-summary-label">
                    SELLING PRICE
                </div>

                <div class="recipe-summary-value recipe-summary-money">
                    ₱{{ number_format((float) $product->selling_price, 2) }}
                </div>

            </div>

        </div>

    </section>


    <!-- =========================================================
         MAIN RECIPE GRID
    ========================================================== -->

    <div class="recipe-layout">


        <!-- =====================================================
             INGREDIENTS PANEL
        ====================================================== -->

        <section class="recipe-panel">

            <!-- PANEL HEADER -->

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


            <!-- =================================================
                 ADD INGREDIENT
            ================================================== -->

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


                    <!-- INVENTORY ITEM -->

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


                    <!-- QUANTITY -->

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


                    <!-- ADD BUTTON -->

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


                <!-- ADD INGREDIENT NOTE -->

                <div class="recipe-add-note">

                    <span>
                        ℹ
                    </span>

                    <div>
                        Quantity represents the amount of the inventory
                        item required to make <strong>one unit</strong>
                        of the product.
                    </div>

                </div>

            </div>


            <!-- =================================================
                 INGREDIENT TABLE
            ================================================== -->

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

                                <!-- INGREDIENT -->

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


                                <!-- SKU -->

                                <td>

                                    <span class="recipe-sku">
                                        {{ $recipeItem->inventoryItem->sku }}
                                    </span>

                                </td>


                                <!-- QUANTITY -->

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


                                <!-- UNIT -->

                                <td>

                                    <span class="recipe-unit">

                                        {{ $recipeItem->inventoryItem->unit?->abbreviation
                                            ?? $recipeItem->inventoryItem->unit?->name
                                            ?? '—' }}

                                    </span>

                                </td>


                                <!-- ACTIONS -->

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


        <!-- =====================================================
             INSTRUCTIONS PANEL
        ====================================================== -->

        <section class="recipe-panel recipe-instructions-panel">

            <!-- PANEL HEADER -->

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


            <!-- INSTRUCTIONS FORM -->

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


            <!-- =================================================
                 INFORMATION NOTE
            ================================================== -->

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


    <!-- =========================================================
         FOOTER NOTE
    ========================================================== -->

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

/* =========================================================
   RECIPE PAGE
   MATCHES ADD PRODUCT PAGE
========================================================= */

.recipe-page {
    width: 100%;
    max-width: 1120px;
    margin: 0 auto;
    padding-bottom: 30px;
    box-sizing: border-box;
}


/* =========================================================
   TOPBAR
   SAME AS ADD PRODUCT
========================================================= */

.recipe-page .topbar {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 25px;
    margin-bottom: 14px;
}

.recipe-page .page-title {
    min-width: 0;
}

.recipe-page .page-title small {
    display: block;
    margin-bottom: 5px;

    color: #a9825b;

    font-size: 10px;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
}

.recipe-page .page-title h1 {
    margin: 0;

    color: #2d241d;

    font-size: clamp(1.6rem, 2vw, 1.9rem);
    line-height: 1.15;

    font-weight: 700;
    letter-spacing: -.04rem;
}

.recipe-page .page-title p {
    margin: 6px 0 0;

    color: #8a8179;

    font-size: 12px;
    line-height: 1.5;

    font-weight: 400;
}


/* =========================================================
   DATE BOX
   SAME AS ADD PRODUCT
========================================================= */

.recipe-page .date-box {
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

.recipe-page .date-icon {
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
   SAME SPACING AS PRODUCT CREATE
========================================================= */

.recipe-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    gap: 7px;

    margin-bottom: 17px;

    color: #9b9188;

    font-size: 10px;
    line-height: 1.3;
}

.recipe-breadcrumb a {
    color: #9a7048;

    font-weight: 600;

    text-decoration: none;
}

.recipe-breadcrumb a:hover {
    text-decoration: underline;
}

.recipe-breadcrumb span {
    color: #9b9188;
}

.recipe-breadcrumb strong {
    color: #655a51;

    font-weight: 700;
}


/* =========================================================
   ALERTS
   MATCH PRODUCT CREATE
========================================================= */

.recipe-alert {
    display: flex;
    align-items: flex-start;

    gap: 9px;

    margin-bottom: 17px;

    padding: 11px;

    border-radius: 10px;

    font-size: 11px;
    line-height: 1.45;

    box-sizing: border-box;
}

.recipe-alert-success {
    color: #34704b;

    background: #f4faf5;

    border: 1px solid #d5e8d8;
}

.recipe-alert-error {
    color: #7c3f35;

    background: #fff7f5;

    border: 1px solid #efd0c8;
}

.recipe-alert-icon {
    display: flex;
    align-items: center;
    justify-content: center;

    flex: 0 0 22px;

    width: 22px;
    height: 22px;

    border-radius: 50%;

    font-size: 11px;
    font-weight: 700;
}

.recipe-alert-success .recipe-alert-icon {
    background: #dcefe0;
    color: #34704b;
}

.recipe-alert-error .recipe-alert-icon {
    background: #e9a08e;
    color: #ffffff;
}

.recipe-alert-title {
    margin-bottom: 3px;

    font-weight: 700;
}

.recipe-alert ul {
    margin: 4px 0 0;

    padding-left: 16px;
}

.recipe-alert li {
    margin: 2px 0;
}


/* =========================================================
   PRODUCT INFORMATION CARD
   SAME WHITE-SPACE LANGUAGE AS PRODUCT FORM CARD
========================================================= */

.recipe-product-card {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 20px;

    min-height: 92px;

    margin-bottom: 17px;

    padding: 17px 19px;

    border: 1px solid #e8e0d8;
    border-radius: 15px;

    background: #ffffff;

    box-shadow:
        0 4px 16px
        rgba(67, 52, 38, .045);

    box-sizing: border-box;
}

.recipe-product-main {
    display: flex;
    align-items: center;

    gap: 13px;

    min-width: 0;
}

.recipe-product-icon {
    display: flex;
    align-items: center;
    justify-content: center;

    width: 46px;
    height: 46px;

    flex-shrink: 0;

    border-radius: 11px;

    background:
        linear-gradient(
            135deg,
            #fbf1e7,
            #f4e3d4
        );

    color: #a87542;

    font-size: 19px;
    font-weight: 800;
}

.recipe-product-information {
    min-width: 0;
}

.recipe-product-label {
    margin-bottom: 4px;

    color: #9a8d83;

    font-size: 9px;
    line-height: 1.2;

    font-weight: 700;

    letter-spacing: .08em;
}

.recipe-product-card h2 {
    margin: 0;

    color: #342a22;

    font-size: 17px;
    line-height: 1.2;

    font-weight: 700;

    letter-spacing: -.01rem;
}

.recipe-product-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    gap: 7px;

    margin-top: 5px;

    color: #8a8179;

    font-size: 10px;
    line-height: 1.35;
}

.recipe-product-meta strong {
    color: #51463d;

    font-weight: 700;
}

.recipe-meta-divider {
    color: #c2b5aa;
}

.recipe-back-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 6px;

    min-height: 36px;

    padding: 0 12px;

    flex-shrink: 0;

    border: 1px solid #ddd5cd;
    border-radius: 8px;

    background: #ffffff;

    color: #71665d;

    text-decoration: none;

    font-size: 10px;
    line-height: 1;
    font-weight: 700;

    transition:
        background .18s ease,
        border-color .18s ease,
        color .18s ease;
}

.recipe-back-button:hover {
    border-color: #d4c6ba;

    background: #faf7f4;

    color: #4d4036;
}

.recipe-back-button span {
    font-size: 13px;
}


/* =========================================================
   SUMMARY
   MATCH PRODUCT STAT CARD SPACING
========================================================= */

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

    gap: 12px;

    padding: 16px 17px;

    border: 1px solid #e8e0d8;
    border-radius: 15px;

    background: #ffffff;

    box-shadow:
        0 5px 18px
        rgba(43, 31, 23, .045);

    box-sizing: border-box;

    position: relative;

    overflow: hidden;
}

.recipe-summary-card::before {
    content: "";

    position: absolute;

    top: 0;
    left: 0;
    bottom: 0;

    width: 3px;

    background:
        linear-gradient(
            180deg,
            #a87542,
            #e2a16c
        );
}

.recipe-summary-icon {
    display: flex;
    align-items: center;
    justify-content: center;

    width: 36px;
    height: 36px;

    flex-shrink: 0;

    border-radius: 9px;

    background:
        linear-gradient(
            135deg,
            #fbf1e7,
            #f4e3d4
        );

    color: #a87542;

    font-size: 13px;
    font-weight: 800;
}

.recipe-summary-content {
    min-width: 0;
}

.recipe-summary-label {
    color: #8a8179;

    font-size: 10px;
    line-height: 1.3;

    font-weight: 800;

    letter-spacing: .045rem;
}

.recipe-summary-value {
    margin-top: 8px;

    color: #2d241d;

    font-size: 20px;
    line-height: 1;

    font-weight: 800;
}

.recipe-summary-money {
    font-size: 16px;
}

.recipe-summary-status {
    margin-top: 8px;

    color: #34825a;

    font-size: 12px;
    line-height: 1;

    font-weight: 800;
}


/* =========================================================
   MAIN RECIPE LAYOUT
========================================================= */

.recipe-layout {
    display: grid;

    grid-template-columns:
        minmax(0, 1.55fr)
        minmax(300px, .75fr);

    gap: 17px;

    align-items: start;
}


/* =========================================================
   RECIPE PANEL
   MATCH PRODUCT FORM CARD
========================================================= */

.recipe-panel {
    min-width: 0;

    overflow: hidden;

    border: 1px solid #e8e0d8;
    border-radius: 15px;

    background: #ffffff;

    box-shadow:
        0 4px 16px
        rgba(67, 52, 38, .045);
}


/* =========================================================
   PANEL HEADER
   SAME HEIGHT / TEXT SIZE AS PRODUCT FORM HEADER
========================================================= */

.recipe-panel-header {
    min-height: 65px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 20px;

    padding: 14px 19px;

    border-bottom: 1px solid #eee8e1;

    background: #fffdfa;

    box-sizing: border-box;
}

.recipe-panel-title {
    color: #342a22;

    font-size: 17px;
    line-height: 1.2;

    font-weight: 700;
}

.recipe-panel-subtitle {
    margin-top: 3px;

    color: #938a82;

    font-size: 11px;
    line-height: 1.4;
}


/* =========================================================
   ADD INGREDIENT SECTION
========================================================= */

.recipe-add-section {
    padding: 18px;

    background: #fff;

    border-bottom: 1px solid #eee8e1;

    box-sizing: border-box;
}

.recipe-section-heading {
    margin-bottom: 10px;

    color: #51463d;

    font-size: 11px;
    line-height: 1.2;

    font-weight: 700;
}


/* =========================================================
   ADD FORM
========================================================= */

.recipe-add-form {
    display: grid;

    grid-template-columns:
        minmax(0, 1fr)
        130px
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

    color: #51463d;

    font-size: 11px;
    line-height: 1.2;

    font-weight: 700;
}

.recipe-form-field input,
.recipe-form-field select,
.recipe-form-field textarea {
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

.recipe-form-field input,
.recipe-form-field select {
    height: 39px;

    padding: 0 11px;
}

.recipe-form-field textarea {
    min-height: 170px;

    padding: 10px 11px;

    resize: vertical;

    line-height: 1.45;
}

.recipe-form-field input::placeholder,
.recipe-form-field textarea::placeholder {
    color: #b2aaa3;
}

.recipe-form-field input:focus,
.recipe-form-field select:focus,
.recipe-form-field textarea:focus {
    border-color: #c99b6b;

    box-shadow:
        0 0 0 3px
        rgba(201, 155, 107, .12);
}

.recipe-form-field select {
    cursor: pointer;
}


/* =========================================================
   ADD INGREDIENT BUTTON
========================================================= */

.recipe-add-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 5px;

    min-height: 39px;

    padding: 0 13px;

    border: 1px solid #9d7047;
    border-radius: 8px;

    background:
        linear-gradient(
            135deg,
            #aa8250,
            #966f43
        );

    color: #ffffff;

    font-family: inherit;

    font-size: 11px;
    line-height: 1;

    font-weight: 700;

    white-space: nowrap;

    cursor: pointer;

    box-shadow:
        0 3px 8px
        rgba(111, 78, 45, .12);

    transition:
        background .18s ease,
        transform .18s ease,
        box-shadow .18s ease;
}

.recipe-add-button:hover {
    background: #966f43;

    transform: translateY(-1px);

    box-shadow:
        0 5px 12px
        rgba(111, 78, 45, .16);
}

.recipe-add-button span {
    font-size: 14px;
    line-height: 1;
}


/* =========================================================
   ADD NOTE
========================================================= */

.recipe-add-note {
    display: flex;
    align-items: flex-start;

    gap: 7px;

    margin-top: 10px;

    color: #8e8279;

    font-size: 9px;
    line-height: 1.5;
}

.recipe-add-note > span {
    color: #a87542;

    font-size: 10px;
    font-weight: 800;
}

.recipe-add-note strong {
    color: #51463d;

    font-weight: 700;
}


/* =========================================================
   INGREDIENT TABLE
========================================================= */

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
    padding: 10px 12px;

    background: #fbf9f6;

    color: #8a8179;

    border-bottom: 1px solid #e8e0d8;

    text-align: left;

    font-size: 9px;
    line-height: 1.3;

    font-weight: 800;

    text-transform: uppercase;

    letter-spacing: .04rem;

    white-space: nowrap;
}

.recipe-table td {
    padding: 11px 12px;

    color: #625951;

    border-bottom: 1px solid #f0ebe6;

    font-size: 10.5px;
    line-height: 1.4;

    vertical-align: middle;
}

.recipe-table tbody tr {
    background: #ffffff;
}

.recipe-table tbody tr:hover {
    background: #fdfaf7;
}


/* =========================================================
   INGREDIENT
========================================================= */

.recipe-ingredient-name {
    color: #2d241d;

    font-size: 11px;
    line-height: 1.35;

    font-weight: 700;
}

.recipe-ingredient-category {
    margin-top: 2px;

    color: #9a918a;

    font-size: 9px;
    line-height: 1.35;
}


/* =========================================================
   SKU
========================================================= */

.recipe-sku {
    display: inline-block;

    padding: 3px 5px;

    border-radius: 5px;

    background: #f5f0eb;

    color: #75583f;

    font-family: monospace;

    font-size: 9px;
}


/* =========================================================
   UNIT
========================================================= */

.recipe-unit {
    color: #51463d;

    font-size: 10px;

    font-weight: 700;
}


/* =========================================================
   QUANTITY FORM
========================================================= */

.recipe-quantity-form {
    display: inline-flex;

    align-items: center;

    gap: 5px;
}

.recipe-quantity-form input {
    width: 82px;

    height: 32px;

    box-sizing: border-box;

    padding: 0 8px;

    border: 1px solid #ddd5cd;
    border-radius: 7px;

    background: #ffffff;

    color: #3d342d;

    font-family: inherit;

    font-size: 10px;

    outline: none;
}

.recipe-quantity-form input:focus {
    border-color: #c99b6b;

    box-shadow:
        0 0 0 3px
        rgba(201, 155, 107, .10);
}

.recipe-quantity-form button {
    width: 32px;
    height: 32px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border: 1px solid #d9e6db;
    border-radius: 7px;

    background: #f4faf5;

    color: #34825a;

    font-family: inherit;

    font-size: 11px;
    font-weight: 900;

    cursor: pointer;

    transition:
        background .18s ease,
        border-color .18s ease;
}

.recipe-quantity-form button:hover {
    background: #eaf5ec;

    border-color: #c8ddcb;
}


/* =========================================================
   ACTIONS
========================================================= */

.recipe-actions-header {
    text-align: center !important;
}

.recipe-table-actions {
    display: flex;

    align-items: center;
    justify-content: center;
}


/* =========================================================
   REMOVE BUTTON
========================================================= */

.recipe-remove-button {
    min-height: 30px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 4px;

    padding: 0 8px;

    border: 1px solid #ead7d2;
    border-radius: 7px;

    background: #ffffff;

    color: #9b665d;

    font-family: inherit;

    font-size: 9px;
    line-height: 1;

    font-weight: 700;

    cursor: pointer;

    transition:
        background .18s ease,
        border-color .18s ease,
        color .18s ease;
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


/* =========================================================
   EMPTY STATE
========================================================= */

.recipe-empty-state {
    padding: 50px 20px !important;

    text-align: center !important;
}

.recipe-empty-icon {
    width: 46px;
    height: 46px;

    margin: 0 auto 11px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 12px;

    background: #fbf1e7;

    color: #a87542;

    font-size: 17px;
}

.recipe-empty-title {
    color: #2d241d;

    font-size: 12px;

    font-weight: 700;
}

.recipe-empty-description {
    max-width: 330px;

    margin: 4px auto 0;

    color: #8a8179;

    font-size: 9px;

    line-height: 1.5;
}


/* =========================================================
   INSTRUCTIONS FORM
   MATCH PRODUCT FORM CONTENT SPACING
========================================================= */

.recipe-instructions-form {
    padding: 18px;
}

.recipe-instructions-form .recipe-form-field {
    margin-bottom: 0;
}

.recipe-field-help {
    margin-top: 5px;

    color: #9d958f;

    font-size: 9px;

    line-height: 1.4;
}


/* =========================================================
   SAVE INSTRUCTIONS
========================================================= */

.recipe-save-button {
    width: 100%;

    min-height: 37px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 6px;

    margin-top: 13px;

    padding: 0 14px;

    border: 1px solid #9d7047;
    border-radius: 8px;

    background:
        linear-gradient(
            135deg,
            #aa8250,
            #966f43
        );

    color: #ffffff;

    font-family: inherit;

    font-size: 11px;
    line-height: 1;

    font-weight: 700;

    cursor: pointer;

    box-shadow:
        0 3px 8px
        rgba(111, 78, 45, .12);

    transition:
        background .18s ease,
        transform .18s ease,
        box-shadow .18s ease;
}

.recipe-save-button:hover {
    background: #966f43;

    transform: translateY(-1px);

    box-shadow:
        0 5px 12px
        rgba(111, 78, 45, .16);
}

.recipe-save-button span {
    font-size: 12px;
    line-height: 1;
}


/* =========================================================
   INFORMATION BOX
   SAME NOTE STYLE AS PRODUCT CREATE
========================================================= */

.recipe-info-box {
    display: flex;
    align-items: flex-start;

    gap: 9px;

    margin: 0 18px 18px;

    padding: 10px;

    border: 1px solid #eadfce;
    border-radius: 8px;

    background: #fffaf3;

    box-sizing: border-box;
}

.recipe-info-icon {
    display: flex;
    align-items: center;
    justify-content: center;

    flex: 0 0 20px;

    width: 20px;
    height: 20px;

    border-radius: 6px;

    background: #f5e6d8;

    color: #a87542;

    font-size: 9px;
    font-weight: 900;
}

.recipe-info-title {
    margin-bottom: 3px;

    color: #57483b;

    font-size: 10px;
    line-height: 1.3;

    font-weight: 700;
}

.recipe-info-text {
    color: #75685b;

    font-size: 9px;

    line-height: 1.5;
}


/* =========================================================
   FOOTER NOTE
========================================================= */

.recipe-footer-note {
    display: flex;
    align-items: center;
    justify-content: center;

    gap: 6px;

    margin-top: 15px;

    color: #8a8179;

    font-size: 9px;

    line-height: 1.4;

    text-align: center;
}

.recipe-footer-note span {
    color: #34825a;

    font-weight: 900;
}


/* =========================================================
   TABLET
========================================================= */

@media (max-width: 1100px) {

    .recipe-page {
        max-width: 100%;
    }

    .recipe-layout {
        grid-template-columns:
            minmax(0, 1fr)
            minmax(260px, .75fr);
    }

}


/* =========================================================
   MEDIUM TABLET
========================================================= */

@media (max-width: 950px) {

    .recipe-layout {
        grid-template-columns: 1fr;
    }

    .recipe-instructions-panel {
        width: 100%;
    }

}


/* =========================================================
   SUMMARY TABLET
========================================================= */

@media (max-width: 850px) {

    .recipe-summary {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 760px) {

    .recipe-page .topbar {
        flex-direction: column;

        gap: 12px;
    }

    .recipe-page .date-box {
        min-width: 145px;
    }

    .recipe-product-card {
        align-items: flex-start;

        flex-direction: column;

        padding: 17px;
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


/* =========================================================
   SMALL MOBILE
========================================================= */

@media (max-width: 600px) {

    .recipe-page .page-title h1 {
        font-size: 25px;
    }

    .recipe-panel-header {
        align-items: flex-start;

        min-height: auto;

        padding: 14px 15px;
    }

    .recipe-add-section {
        padding: 15px;
    }

    .recipe-instructions-form {
        padding: 15px;
    }

    .recipe-info-box {
        margin:
            0
            15px
            15px;
    }

    .recipe-table th {
        padding:
            10px;
            11px;
    }

    .recipe-table td {
        padding:
            11px;
            11px;
    }

}


/* =========================================================
   VERY SMALL MOBILE
========================================================= */

@media (max-width: 480px) {

    .recipe-page .date-box {
        width: 100%;

        justify-content: flex-start;
    }

    .recipe-product-card {
        border-radius: 13px;
    }

    .recipe-panel {
        border-radius: 13px;
    }

    .recipe-summary-card {
        border-radius: 13px;
    }

}

</style>

@endpush