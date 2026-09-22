@extends('layouts.app')

@section('title', 'BiteSync | Expense Details')

@section('content')

@php
$status = $expense->status ?? 'Recorded';

$statusConfig = [
    'Recorded' => [
        'class' => 'status-recorded',
        'icon' => '✓',
        'label' => 'Recorded',
    ],
    'Draft' => [
        'class' => 'status-draft',
        'icon' => '📝',
        'label' => 'Draft',
    ],
    'Cancelled' => [
        'class' => 'status-cancelled',
        'icon' => '⊘',
        'label' => 'Cancelled',
    ],
];

$currentStatus = $statusConfig[$status] ?? [
    'class' => 'status-default',
    'icon' => '•',
    'label' => $status,
];

$recordedBy = $expense->user->name
    ?? $expense->user->full_name
    ?? '—';

$expenseDate = $expense->expense_date
    ? $expense->expense_date->format('F d, Y')
    : '—';

$createdDate = $expense->created_at
    ? $expense->created_at->format('F d, Y h:i A')
    : '—';

$updatedDate = $expense->updated_at
    ? $expense->updated_at->format('F d, Y h:i A')
    : '—';

@endphp

<style>
    /* ============================================================
       EXPENSE SHOW PAGE
       Matches the BiteSync master create-page layout
       ============================================================ */

    .expense-show-page {
        width: 100%;
        max-width: 1120px;
        margin: 0 auto;
        padding-bottom: 30px;
    }


    /* ============================================================
       TOPBAR
       ============================================================ */

    .expense-show-page .topbar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 25px;
        margin-bottom: 14px;
    }

    .expense-show-page .page-title small {
        display: block;
        margin-bottom: 5px;
        color: #a9825b;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .expense-show-page .page-title h1 {
        margin: 0;
        color: var(--dark);
        font-size: 29px;
        font-weight: 700;
        line-height: 1.15;
        letter-spacing: -.045rem;
    }

    .expense-show-page .page-title p {
        margin: 6px 0 0;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.5;
        font-weight: 400;
    }

    .expense-show-page .date-box {
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

    .expense-show-page .date-icon {
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
       MAIN INFORMATION GRID
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
       DETAIL GRID
       ============================================================ */

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 17px 15px;
    }

    .detail-item {
        min-width: 0;
    }

    .detail-item.full-width {
        grid-column: 1 / -1;
    }

    .detail-label {
        display: block;
        margin-bottom: 6px;
        color: #4c3c31;
        font-size: 11px;
        line-height: 1.35;
        font-weight: 600;
    }

    .detail-value {
        min-height: 39px;
        display: flex;
        align-items: center;
        box-sizing: border-box;
        padding: 9px 11px;
        border: 1px solid #ded4cb;
        border-radius: 8px;
        background: #fcfaf8;
        color: var(--text);
        font-size: 12px;
        line-height: 1.4;
        font-weight: 400;
        word-break: break-word;
    }

    .detail-value.empty {
        color: #aaa098;
    }


    /* ============================================================
       AMOUNT DISPLAY
       ============================================================ */

    .amount-display {
        min-height: 56px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 10px 13px;
        border: 1px solid #eadfd5;
        border-radius: 9px;
        background: #fcfaf8;
    }

    .amount-display-label {
        color: #8a7c72;
        font-size: 10px;
        line-height: 1.4;
        font-weight: 600;
    }

    .amount-display-value {
        color: var(--dark);
        font-size: 21px;
        line-height: 1;
        font-weight: 800;
        white-space: nowrap;
    }


    /* ============================================================
       STATUS BADGES
       ============================================================ */

    .expense-status {
        min-height: 39px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 0 11px;
        border-radius: 8px;
        font-size: 10px;
        line-height: 1;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-recorded {
        border: 1px solid #d4e5d7;
        background: #edf6ef;
        color: #557c5d;
    }

    .status-draft {
        border: 1px solid #eadcc8;
        background: #fbf1e3;
        color: #9a713f;
    }

    .status-cancelled {
        border: 1px solid #edd5d2;
        background: #fbeeed;
        color: #a45751;
    }

    .status-default {
        border: 1px solid #ded4cb;
        background: #f8f5f2;
        color: #75685f;
    }


    /* ============================================================
       DESCRIPTION
       ============================================================ */

    .description-box {
        min-height: 108px;
        padding: 10px 11px;
        box-sizing: border-box;
        border: 1px solid #ded4cb;
        border-radius: 8px;
        background: #fcfaf8;
        color: #62574f;
        font-size: 12px;
        line-height: 1.5;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .description-box.empty {
        color: #aaa098;
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
       RECORD METADATA
       ============================================================ */

    .metadata-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 15px;
    }

    .metadata-item {
        padding: 11px 12px;
        border: 1px solid #eadfd5;
        border-radius: 9px;
        background: #fcfaf8;
    }

    .metadata-label {
        color: #968a81;
        font-size: 9px;
        line-height: 1.4;
        font-weight: 600;
    }

    .metadata-value {
        margin-top: 3px;
        color: #5f5148;
        font-size: 10px;
        line-height: 1.45;
        font-weight: 700;
        word-break: break-word;
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

    .action-button {
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

    .back-button {
        border: 1px solid #ded4cb;
        background: white;
        color: #6f6259;
    }

    .back-button:hover {
        background: #faf7f4;
    }

    .edit-button {
        border: 1px solid transparent;
        background: linear-gradient(
            135deg,
            var(--orange),
            var(--orange-dark)
        );
        color: white;
        box-shadow: 0 3px 9px rgba(168, 95, 40, .12);
    }

    .edit-button:hover {
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

        .expense-show-page {
            max-width: 100%;
        }

        .expense-show-page .topbar {
            flex-direction: column;
            gap: 12px;
        }

        .expense-show-page .date-box {
            align-self: flex-start;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }

        .detail-item.full-width {
            grid-column: auto;
        }

        .metadata-grid {
            grid-template-columns: 1fr;
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

        .action-button {
            flex: 1;
        }

    }


    @media (max-width: 480px) {

        .expense-show-page .page-title h1 {
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

        .action-button {
            width: 100%;
            flex: none;
        }

        .amount-display {
            align-items: flex-start;
            flex-direction: column;
            gap: 7px;
        }

        .amount-display-value {
            font-size: 19px;
        }

    }
</style>

<div class="expense-show-page">

{{-- ============================================================
     TOPBAR
     ============================================================ --}}

<div class="topbar">

    <div class="page-title">

        <small>
            Expense Management
        </small>

        <h1>
            Expense Details
        </h1>

        <p>
            View the complete information for this BiteSync expense record.
        </p>

    </div>


    {{-- EXACT Bitesync DATE FORMAT --}}

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

    <strong>
        Expense Details
    </strong>

</div>


{{-- ============================================================
     TWO-COLUMN INFORMATION AREA
     ============================================================ --}}

<div class="information-grid">


    {{-- ========================================================
         EXPENSE INFORMATION
         ======================================================== --}}

    <div class="form-panel">

        <div class="form-panel-header">

            <div>

                <div class="form-panel-title">
                    Expense Information
                </div>

                <div class="form-panel-subtitle">
                    Basic information about this expense.
                </div>

            </div>

            <div class="form-panel-icon">
                ₱
            </div>

        </div>


        <div class="form-content">

            <div class="detail-grid">


                {{-- CATEGORY --}}

                <div class="detail-item">

                    <span class="detail-label">
                        Category
                    </span>

                    <div class="detail-value">
                        {{ $expense->category ?: '—' }}
                    </div>

                </div>


                {{-- EXPENSE DATE --}}

                <div class="detail-item">

                    <span class="detail-label">
                        Expense Date
                    </span>

                    <div class="detail-value">
                        {{ $expenseDate }}
                    </div>

                </div>


                {{-- STATUS --}}

                <div class="detail-item">

                    <span class="detail-label">
                        Status
                    </span>

                    <div>

                        <span class="expense-status {{ $currentStatus['class'] }}">

                            <span>
                                {{ $currentStatus['icon'] }}
                            </span>

                            {{ $currentStatus['label'] }}

                        </span>

                    </div>

                </div>


                {{-- REFERENCE NUMBER --}}

                <div class="detail-item">

                    <span class="detail-label">
                        Reference No.
                    </span>

                    <div class="detail-value {{ !$expense->reference_no ? 'empty' : '' }}">
                        {{ $expense->reference_no ?: 'No reference number provided.' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================
         EXPENSE DETAILS
         ======================================================== --}}

    <div class="form-panel">

        <div class="form-panel-header">

            <div>

                <div class="form-panel-title">
                    Expense Details
                </div>

                <div class="form-panel-subtitle">
                    Amount and supporting information.
                </div>

            </div>

            <div class="form-panel-icon">
                ₱
            </div>

        </div>


        <div class="form-content">

            <div class="detail-grid">


                {{-- AMOUNT --}}

                <div class="detail-item full-width">

                    <span class="detail-label">
                        Amount
                    </span>

                    <div class="amount-display">

                        <div class="amount-display-label">
                            Total expense amount
                        </div>

                        <div class="amount-display-value">
                            ₱{{ number_format((float) $expense->amount, 2) }}
                        </div>

                    </div>

                </div>


                {{-- DESCRIPTION --}}

                <div class="detail-item full-width">

                    <span class="detail-label">
                        Description
                    </span>

                    <div class="description-box {{ !$expense->description ? 'empty' : '' }}">
                        {{ $expense->description ?: 'No description was provided for this expense.' }}
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ============================================================
     RECORDING INFORMATION
     ============================================================ --}}

<div class="form-panel additional-panel">

    <div class="form-panel-header">

        <div>

            <div class="form-panel-title">
                Recording Information
            </div>

            <div class="form-panel-subtitle">
                Ownership and record information.
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
                    Recorded by {{ $recordedBy }}
                </div>

                <div class="recording-description">
                    This expense is linked to the BiteSync user who created the record.
                </div>

            </div>

        </div>

    </div>

</div>


{{-- ============================================================
     RECORD METADATA
     ============================================================ --}}

<div class="form-panel additional-panel">

    <div class="form-panel-header">

        <div>

            <div class="form-panel-title">
                Record Information
            </div>

            <div class="form-panel-subtitle">
                System timestamps for this expense record.
            </div>

        </div>

        <div class="form-panel-icon">
            ◷
        </div>

    </div>


    <div class="form-content">

        <div class="metadata-grid">

            <div class="metadata-item">

                <div class="metadata-label">
                    Created
                </div>

                <div class="metadata-value">
                    {{ $createdDate }}
                </div>

            </div>


            <div class="metadata-item">

                <div class="metadata-label">
                    Last Updated
                </div>

                <div class="metadata-value">
                    {{ $updatedDate }}
                </div>

            </div>

        </div>

    </div>

</div>


{{-- ============================================================
     INFORMATION BOX
     ============================================================ --}}

<div class="information-box">

    <div class="information-box-icon">
        i
    </div>

    <div class="information-box-content">

        <strong>
            Expense record
        </strong>

        @if ($status === 'Recorded')

            This expense is currently recorded and is included in the Expense totals.

        @elseif ($status === 'Draft')

            This expense is currently saved as a draft and can be updated before being recorded.

        @elseif ($status === 'Cancelled')

            This expense has been cancelled and is excluded from the Expense totals.

        @else

            Review the expense status before making any changes to this record.

        @endif

    </div>

</div>


{{-- ============================================================
     ACTIONS
     ============================================================ --}}

<div class="form-actions">

    <a
        href="{{ route('expenses.index') }}"
        class="action-button back-button"
    >
        <span>←</span>
        Back to Expenses
    </a>


    @if (in_array(auth()->user()->role ?? '', ['CEO/Admin', 'Finance'], true))

        <a
            href="{{ route('expenses.edit', $expense) }}"
            class="action-button edit-button"
        >
            <span>✎</span>
            Edit Expense
        </a>

    @endif

</div>

</div>

@endsection
