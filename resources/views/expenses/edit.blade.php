@extends('layouts.app')

@section('title', 'BiteSync | Edit Expense')

@section('content')

@php
$expenseCategories = $categories ?? [
'Utilities',
'Rent',
'Transportation',
'Supplies',
'Maintenance',
'Salaries',
'Marketing',
'Food & Beverages',
'Equipment',
'Other',
];

$expenseStatus = old('status', $expense->status ?? 'Recorded');

@endphp

<style>
    /* ============================================================
       EXPENSE EDIT PAGE
       Matches the BiteSync master create-page layout
       ============================================================ */

    .expense-edit-page {
        width: 100%;
        max-width: 1120px;
        margin: 0 auto;
        padding-bottom: 30px;
    }


    /* ============================================================
       TOPBAR
       ============================================================ */

    .expense-edit-page .topbar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 25px;
        margin-bottom: 14px;
    }

    .expense-edit-page .page-title small {
        display: block;
        margin-bottom: 5px;
        color: #a9825b;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .expense-edit-page .page-title h1 {
        margin: 0;
        color: var(--dark);
        font-size: 29px;
        font-weight: 700;
        line-height: 1.15;
        letter-spacing: -.045rem;
    }

    .expense-edit-page .page-title p {
        margin: 6px 0 0;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.5;
        font-weight: 400;
    }

    .expense-edit-page .date-box {
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

    .expense-edit-page .date-icon {
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


    /* ============================================================
       BREADCRUMB
       ============================================================ */

    .expense-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-bottom: 17px;
        color: #6f6259;
        font-size: 10px;
        line-height: 1.4;
    }

    .expense-breadcrumb a {
        color: #a16e42;
        text-decoration: none;
        font-weight: 600;
    }

    .expense-breadcrumb a:hover {
        text-decoration: underline;
    }

    .expense-breadcrumb span {
        color: #c2b5aa;
    }

    .expense-breadcrumb strong {
        color: #6f6259;
        font-weight: 600;
    }


    /* ============================================================
       VALIDATION ALERT
       ============================================================ */

    .form-alert {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        margin-bottom: 17px;
        padding: 13px 15px;
        border: 1px solid #ead1c7;
        border-radius: 10px;
        background: #fff8f5;
    }

    .form-alert-error {
        color: #8d4e3b;
    }

    .alert-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        flex-shrink: 0;
        border-radius: 8px;
        background: #f5dfd6;
        color: #a95b42;
        font-size: 12px;
        font-weight: 800;
    }

    .alert-title {
        margin: 0;
        color: #754637;
        font-size: 11px;
        line-height: 1.4;
        font-weight: 700;
    }

    .alert-list {
        margin: 5px 0 0;
        padding-left: 17px;
        color: #8d6558;
        font-size: 10px;
        line-height: 1.5;
    }


    /* ============================================================
       INFORMATION GRID
       ============================================================ */

    .information-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 17px;
        margin-bottom: 17px;
    }


    /* ============================================================
       FORM PANEL
       ============================================================ */

    .form-panel {
        overflow: hidden;
        border: 1px solid var(--border);
        border-radius: 15px;
        background: white;
        box-shadow: 0 4px 16px rgba(43, 31, 23, .035);
    }

    .additional-panel {
        margin-bottom: 17px;
    }

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

    .form-content {
        padding: 18px;
    }


    /* ============================================================
       FORM FIELDS
       ============================================================ */

    .basic-fields-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 17px 15px;
    }

    .expense-full-width {
        grid-column: 1 / -1;
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
            border-color .18s ease,
            box-shadow .18s ease;
    }

    .form-group input,
    .form-group select {
        height: 39px;
        padding: 0 11px;
    }

    .form-group textarea {
        min-height: 108px;
        padding: 10px 11px;
        resize: vertical;
        line-height: 1.5;
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder {
        color: #b4aaa2;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #c58a57;
        box-shadow: 0 0 0 3px rgba(197, 138, 87, .10);
    }

    .form-group select {
        cursor: pointer;
    }


    /* ============================================================
       AMOUNT FIELD
       ============================================================ */

    .amount-wrapper {
        position: relative;
    }

    .amount-prefix {
        position: absolute;
        top: 0;
        left: 0;
        width: 39px;
        height: 39px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-right: 1px solid #ded4cb;
        border-radius: 8px 0 0 8px;
        background: #fbf8f5;
        color: #8c7564;
        font-size: 11px;
        font-weight: 700;
        pointer-events: none;
    }

    .amount-wrapper input {
        padding-left: 50px;
        font-weight: 600;
    }


    /* ============================================================
       FIELD NOTES
       ============================================================ */

    .field-note {
        margin-top: 5px;
        color: #9d9289;
        font-size: 9px;
        line-height: 1.4;
    }


    /* ============================================================
       STATUS NOTE
       ============================================================ */

    .status-note {
        display: flex;
        align-items: flex-start;
        gap: 9px;
        margin-top: 15px;
        padding: 10px 11px;
        border: 1px solid #eadfd5;
        border-radius: 8px;
        background: #fcfaf8;
    }

    .status-note-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 25px;
        height: 25px;
        flex-shrink: 0;
        border-radius: 7px;
        background: #f5e8dc;
        color: #a6754e;
        font-size: 11px;
        font-weight: 700;
    }

    .status-note-text {
        color: #81746b;
        font-size: 9px;
        line-height: 1.5;
    }

    .status-note-text strong {
        display: block;
        margin-bottom: 2px;
        color: #67584e;
        font-size: 10px;
        font-weight: 700;
    }


    /* ============================================================
       RECORDING INFORMATION
       ============================================================ */

    .recording-card {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 12px 13px;
        border: 1px solid #eadfd5;
        border-radius: 9px;
        background: #fcfaf8;
    }

    .recording-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        flex-shrink: 0;
        border-radius: 8px;
        background: #f5e8dc;
        color: #a6754e;
        font-size: 13px;
        font-weight: 700;
    }

    .recording-text {
        min-width: 0;
    }

    .recording-title {
        color: #5f5148;
        font-size: 10px;
        line-height: 1.4;
        font-weight: 700;
    }

    .recording-description {
        margin-top: 2px;
        color: #968a81;
        font-size: 9px;
        line-height: 1.45;
    }


    /* ============================================================
       INFORMATION BOX
       ============================================================ */

    .information-box {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 17px;
        padding: 12px 13px;
        border: 1px solid #eadfd5;
        border-radius: 9px;
        background: #fcfaf8;
    }

    .information-box-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        flex-shrink: 0;
        border-radius: 8px;
        background: #f5e8dc;
        color: #a6754e;
        font-size: 12px;
        font-weight: 700;
    }

    .information-box-content {
        color: #8a7c72;
        font-size: 9px;
        line-height: 1.5;
    }

    .information-box-content strong {
        display: block;
        margin-bottom: 2px;
        color: #66574d;
        font-size: 10px;
        font-weight: 700;
    }


    /* ============================================================
       FORM ACTIONS
       ============================================================ */

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
        transition:
            transform .16s ease,
            box-shadow .16s ease,
            background .16s ease;
    }

    .cancel-button {
        border: 1px solid #ded4cb;
        background: white;
        color: #6f6259;
    }

    .cancel-button:hover {
        background: #faf7f4;
    }

    .save-button {
        border: 1px solid transparent;
        background: linear-gradient(
            135deg,
            var(--orange),
            var(--orange-dark)
        );
        color: white;
        box-shadow: 0 3px 9px rgba(168, 95, 40, .12);
    }

    .save-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 13px rgba(168, 95, 40, .18);
    }


    /* ============================================================
       RESPONSIVE
       ============================================================ */

    @media (max-width: 1100px) {

        .information-grid {
            grid-template-columns: 1fr;
        }

    }


    @media (max-width: 700px) {

        .expense-edit-page {
            max-width: 100%;
        }

        .expense-edit-page .topbar {
            flex-direction: column;
            gap: 12px;
        }

        .expense-edit-page .date-box {
            align-self: flex-start;
        }

        .basic-fields-grid {
            grid-template-columns: 1fr;
        }

        .expense-full-width {
            grid-column: auto;
        }

        .form-content {
            padding: 15px;
        }

        .form-panel-header {
            padding: 14px 15px;
        }

        .form-actions {
            align-items: stretch;
        }

        .cancel-button,
        .save-button {
            flex: 1;
        }

    }


    @media (max-width: 480px) {

        .expense-edit-page .page-title h1 {
            font-size: 23px;
        }

        .form-panel-title {
            font-size: 17px;
        }

        .form-panel-subtitle {
            max-width: 220px;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .cancel-button,
        .save-button {
            width: 100%;
            flex: none;
        }

    }
</style>

<div class="expense-edit-page">

{{-- ============================================================
     TOPBAR
     ============================================================ --}}

<div class="topbar">

    <div class="page-title">

        <small>
            Expense Management
        </small>

        <h1>
            Edit Expense
        </h1>

        <p>
            Update the expense information and keep the BiteSync record accurate.
        </p>

    </div>


    <div class="date-box">

        <span class="date-icon">
            ◷
        </span>

        {{ now()->format('F d, Y') }}

    </div>

</div>


{{-- ============================================================
     BREADCRUMB
     ============================================================ --}}

<div class="expense-breadcrumb">

    <a href="{{ route('expenses.index') }}">
        Expenses
    </a>

    <span>
        /
    </span>

    <a href="{{ route('expenses.show', $expense) }}">
        Expense #{{ $expense->id }}
    </a>

    <span>
        /
    </span>

    <strong>
        Edit Expense
    </strong>

</div>


{{-- ============================================================
     VALIDATION ERRORS
     ============================================================ --}}

@if ($errors->any())

    <div class="form-alert form-alert-error">

        <div class="alert-icon">
            !
        </div>

        <div>

            <p class="alert-title">
                Please correct the following information.
            </p>

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


{{-- ============================================================
     FORM
     ============================================================ --}}

<form
    action="{{ route('expenses.update', $expense) }}"
    method="POST"
>

    @csrf
    @method('PUT')


    {{-- ========================================================
         TWO-COLUMN INFORMATION AREA
         ======================================================== --}}

    <div class="information-grid">


        {{-- ====================================================
             EXPENSE INFORMATION
             ==================================================== --}}

        <div class="form-panel">

            <div class="form-panel-header">

                <div>

                    <div class="form-panel-title">
                        Expense Information
                    </div>

                    <div class="form-panel-subtitle">
                        Update the basic expense information.
                    </div>

                </div>

                <div class="form-panel-icon">
                    ₱
                </div>

            </div>


            <div class="form-content">

                <div class="basic-fields-grid">


                    {{-- CATEGORY --}}

                    <div class="form-group">

                        <label for="category">
                            Category <span>*</span>
                        </label>

                        <select
                            id="category"
                            name="category"
                            required
                        >

                            <option value="">
                                Select category
                            </option>

                            @foreach ($expenseCategories as $category)

                                <option
                                    value="{{ $category }}"
                                    @selected(old('category', $expense->category) === $category)
                                >
                                    {{ $category }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- EXPENSE DATE --}}

                    <div class="form-group">

                        <label for="expense_date">
                            Expense Date <span>*</span>
                        </label>

                        <input
                            type="date"
                            id="expense_date"
                            name="expense_date"
                            value="{{ old('expense_date', optional($expense->expense_date)->format('Y-m-d')) }}"
                            required
                        >

                    </div>


                    {{-- STATUS --}}

                    <div class="form-group">

                        <label for="status">
                            Status <span>*</span>
                        </label>

                        <select
                            id="status"
                            name="status"
                            required
                        >

                            <option
                                value="Recorded"
                                @selected($expenseStatus === 'Recorded')
                            >
                                Recorded
                            </option>

                            <option
                                value="Draft"
                                @selected($expenseStatus === 'Draft')
                            >
                                Draft
                            </option>

                            <option
                                value="Cancelled"
                                @selected($expenseStatus === 'Cancelled')
                            >
                                Cancelled
                            </option>

                        </select>

                    </div>


                    {{-- REFERENCE NUMBER --}}

                    <div class="form-group">

                        <label for="reference_no">
                            Reference No.
                        </label>

                        <input
                            type="text"
                            id="reference_no"
                            name="reference_no"
                            value="{{ old('reference_no', $expense->reference_no) }}"
                            maxlength="100"
                            placeholder="Optional receipt or reference no."
                        >

                    </div>

                </div>


                <div class="status-note">

                    <div class="status-note-icon">
                        i
                    </div>

                    <div class="status-note-text">

                        <strong>
                            Expense status
                        </strong>

                        Draft expenses can be completed later. Recorded expenses are included in expense totals. Cancelled expenses are excluded from totals.

                    </div>

                </div>

            </div>

        </div>


        {{-- ====================================================
             EXPENSE DETAILS
             ==================================================== --}}

        <div class="form-panel">

            <div class="form-panel-header">

                <div>

                    <div class="form-panel-title">
                        Expense Details
                    </div>

                    <div class="form-panel-subtitle">
                        Update the amount and supporting details.
                    </div>

                </div>

                <div class="form-panel-icon">
                    ₱
                </div>

            </div>


            <div class="form-content">

                <div class="basic-fields-grid">


                    {{-- AMOUNT --}}

                    <div class="form-group expense-full-width">

                        <label for="amount">
                            Amount <span>*</span>
                        </label>

                        <div class="amount-wrapper">

                            <span class="amount-prefix">
                                ₱
                            </span>

                            <input
                                type="number"
                                id="amount"
                                name="amount"
                                value="{{ old('amount', $expense->amount) }}"
                                min="0.01"
                                max="9999999999.99"
                                step="0.01"
                                placeholder="0.00"
                                required
                            >

                        </div>

                        <div class="field-note">
                            Enter the total expense amount in Philippine pesos.
                        </div>

                    </div>


                    {{-- DESCRIPTION --}}

                    <div class="form-group expense-full-width">

                        <label for="description">
                            Description
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            maxlength="500"
                            placeholder="Describe what the expense was for..."
                        >{{ old('description', $expense->description) }}</textarea>

                        <div class="field-note">
                            Add useful details such as the purpose, item purchased, or payment reason.
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================
         RECORDING INFORMATION
         ======================================================== --}}

    <div class="form-panel additional-panel">

        <div class="form-panel-header">

            <div>

                <div class="form-panel-title">
                    Recording Information
                </div>

                <div class="form-panel-subtitle">
                    Review the ownership and record information.
                </div>

            </div>

            <div class="form-panel-icon">
                ✓
            </div>

        </div>


        <div class="form-content">

            <div class="recording-card">

                <div class="recording-icon">
                    ✓
                </div>

                <div class="recording-text">

                    <div class="recording-title">
                        Expense recorded by the current BiteSync user
                    </div>

                    <div class="recording-description">
                        The expense remains connected to its original authenticated user record. Editing the expense does not require manual user selection.
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================
         INFORMATION BOX
         ======================================================== --}}

    <div class="information-box">

        <div class="information-box-icon">
            i
        </div>

        <div class="information-box-content">

            <strong>
                Before updating
            </strong>

            Review the category, amount, expense date, reference number, description, and status before saving your changes.

        </div>

    </div>


    {{-- ========================================================
         ACTIONS
         ======================================================== --}}

    <div class="form-actions">

        <a
            href="{{ route('expenses.show', $expense) }}"
            class="cancel-button"
        >
            <span>←</span>
            Cancel
        </a>

        <button
            type="submit"
            class="save-button"
        >
            <span>✓</span>
            Update Expense
        </button>

    </div>

</form>

</div>

@endsection
