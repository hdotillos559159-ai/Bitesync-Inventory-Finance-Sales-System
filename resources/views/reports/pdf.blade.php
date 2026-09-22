<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
    BiteSync Business Report
</title>

<style>

    * {
        box-sizing: border-box;
    }


    body {
        margin: 0;
        padding: 30px;
        background: #f5f1eb;
        color: #2c241f;
        font-family:
            Arial,
            Helvetica,
            sans-serif;
        font-size: 12px;
    }


    .report-page {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
    }


    /*
    |--------------------------------------------------------------------------
    | PRINT ACTIONS
    |--------------------------------------------------------------------------
    */

    .print-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        margin-bottom: 15px;
    }


    .print-button {
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 13px;
        border: 1px solid #e4dcd4;
        border-radius: 8px;
        background: white;
        color: #625951;
        font-family: inherit;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
    }


    .print-button.primary {
        border-color: #c47a3a;
        background:
            linear-gradient(
                135deg,
                #c47a3a,
                #a85f28
            );
        color: white;
    }


    /*
    |--------------------------------------------------------------------------
    | REPORT HEADER
    |--------------------------------------------------------------------------
    */

    .report-header {
        display: block;
        width: 100%;
        margin-bottom: 17px;
    }


    .brand-area {
        width: 100%;
        min-width: 0;
    }


    .header-top-row {
        width: 100%;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 25px;
        margin: 0;
    }


    .brand-area small {
        display: block;
        margin: 0;
        color: #a9825b;
        font-size: 10px;
        line-height: 1.4;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
    }


    .current-date {
        flex-shrink: 0;
        margin: 0;
        padding: 0;
        color: #625951;
        font-size: 10px;
        line-height: 1.4;
        font-weight: 600;
        white-space: nowrap;
        text-align: right;
    }


    .brand-area h1 {
        margin: 8px 0 0;
        color: #241a14;
        font-size: 24px;
        line-height: 1.2;
        font-weight: 700;
        letter-spacing: -.035rem;
    }


    .brand-area p {
        max-width: 600px;
        margin: 6px 0 0;
        color: #8b8179;
        font-size: 12px;
        line-height: 1.5;
    }


    /*
    |--------------------------------------------------------------------------
    | REPORTING PERIOD
    |--------------------------------------------------------------------------
    */

    .period-line {
        display: flex;
        align-items: center;
        margin-top: 12px;
        color: #625951;
        font-size: 10px;
        line-height: 1.4;
    }


    .period-line strong {
        color: #625951;
        font-weight: 700;
    }


    .period-value-line {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 4px;
        color: #241a14;
        font-size: 10px;
        line-height: 1.4;
    }


    .period-value {
        color: #241a14;
        font-weight: 700;
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATED
    |--------------------------------------------------------------------------
    */

    .generated-line {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 9px;
        color: #8b8179;
        font-size: 9px;
        line-height: 1.4;
    }


    .generated-line strong {
        color: #625951;
        font-weight: 700;
    }


    /*
    |--------------------------------------------------------------------------
    | SECTIONS
    |--------------------------------------------------------------------------
    */

    .section {
        margin-bottom: 17px;
        border: 1px solid #e4dcd4;
        border-radius: 15px;
        background: white;
        overflow: hidden;
        box-shadow:
            0 4px 16px rgba(43, 31, 23, .035);
    }


    .section-header {
        min-height: 65px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 14px 19px;
        border-bottom: 1px solid #e4dcd4;
        background: white;
    }


    .section-title {
        color: #241a14;
        font-size: 15px;
        line-height: 1.3;
        font-weight: 700;
    }


    .section-subtitle {
        margin-top: 3px;
        color: #8b8179;
        font-size: 10px;
        line-height: 1.4;
        font-weight: 400;
    }


    .section-icon {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 8px;
        background: #f4e4d4;
        color: #c47a3a;
        font-size: 13px;
        font-weight: 700;
    }


    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    .table-wrapper {
        width: 100%;
        overflow: hidden;
    }


    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }


    th {
        padding: 10px 12px;
        background: #f8f5f1;
        color: #756b63;
        border-bottom: 1px solid #ddd4cc;
        text-align: left;
        font-size: 9px;
        line-height: 1.3;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .045rem;
        white-space: nowrap;
    }


    td {
        padding: 11px 12px;
        color: #625951;
        border-bottom: 1px solid #f0ebe6;
        font-size: 11px;
        line-height: 1.4;
        vertical-align: middle;
        background: white;
    }


    tbody tr:nth-child(even) td {
        background: #fcfaf8;
    }


    tbody tr:hover td {
        background: #faf6f1;
    }


    tbody tr:last-child td {
        border-bottom: none;
    }


    .amount {
        text-align: right;
        white-space: nowrap;
        font-weight: 700;
        color: #241a14;
    }


    .number {
        text-align: right;
        white-space: nowrap;
    }


    .muted {
        color: #9d958f;
    }


    .date-cell {
        white-space: nowrap;
        color: #625951;
    }


    .description-cell {
        color: #6e655e;
    }


    /*
    |--------------------------------------------------------------------------
    | SUMMARY TABLE
    |--------------------------------------------------------------------------
    */

    .summary-table th:first-child {
        width: 55%;
    }


    .summary-table th:nth-child(2) {
        width: 30%;
        text-align: right;
    }


    .summary-table th:nth-child(3) {
        width: 15%;
        text-align: right;
    }


    .summary-table td:first-child {
        color: #4f4741;
        font-weight: 600;
    }


    .summary-table td:last-child {
        font-weight: 700;
        color: #241a14;
    }


    .summary-table .total-row td {
        background: #f7f1eb !important;
        border-top: 1px solid #ddd0c5;
        color: #241a14;
        font-weight: 800;
    }


    .summary-table .total-row td:first-child {
        color: #241a14;
    }


    .positive {
        color: #5d8b67 !important;
    }


    .negative {
        color: #b95d56 !important;
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL TABLES
    |--------------------------------------------------------------------------
    */

    .daily-sales-table th:first-child {
        width: 30%;
    }


    .daily-sales-table th:nth-child(2) {
        width: 40%;
        text-align: right;
    }


    .daily-sales-table th:nth-child(3) {
        width: 30%;
        text-align: right;
    }


    .expense-table th:nth-child(1) {
        width: 16%;
    }


    .expense-table th:nth-child(2) {
        width: 19%;
    }


    .expense-table th:nth-child(3) {
        width: 28%;
    }


    .expense-table th:nth-child(4) {
        width: 17%;
        text-align: right;
    }


    .expense-table th:nth-child(5) {
        width: 20%;
    }


    .expense-table td:nth-child(4) {
        text-align: right;
    }


    .remittance-table th:nth-child(1) {
        width: 23%;
    }


    .remittance-table th:nth-child(2) {
        width: 25%;
        text-align: right;
    }


    .remittance-table th:nth-child(3) {
        width: 25%;
        text-align: right;
    }


    .remittance-table th:nth-child(4) {
        width: 27%;
        text-align: right;
    }


    /*
    |--------------------------------------------------------------------------
    | EMPTY STATE
    |--------------------------------------------------------------------------
    */

    .empty-row td {
        padding: 20px 12px;
        text-align: center;
        background: white !important;
    }


    /*
    |--------------------------------------------------------------------------
    | FOOTER
    |--------------------------------------------------------------------------
    */

    .report-footer {
        margin-top: 20px;
        padding-top: 13px;
        border-top: 1px solid #e4dcd4;
        color: #9d958f;
        font-size: 9px;
        line-height: 1.5;
        text-align: center;
    }


    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 700px) {

        body {
            padding: 15px;
        }


        .header-top-row {
            gap: 15px;
        }


        .current-date {
            font-size: 9px;
        }


        .table-wrapper {
            overflow-x: auto;
        }


        table {
            min-width: 700px;
        }

    }


    /*
    |--------------------------------------------------------------------------
    | PRINT
    |--------------------------------------------------------------------------
    */

    @media print {

        body {
            padding: 0;
            background: white;
        }


        .report-page {
            max-width: none;
        }


        .print-actions {
            display: none !important;
        }


        .section {
            box-shadow: none;
            break-inside: avoid;
        }


        .report-header {
            break-inside: avoid;
        }


        .report-footer {
            margin-top: 15px;
        }


        tbody tr:hover td {
            background: inherit;
        }

    }

</style>

</head>


<body>

@php

    /*
    |--------------------------------------------------------------------------
    | ROLE CHECK
    |--------------------------------------------------------------------------
    |
    | Procurement receives a restricted report containing only:
    |
    | 1. Purchasing
    | 2. Inventory
    |
    | CEO/Admin and Finance continue receiving the complete report.
    |
    */

    $isProcurement =
        auth()->user()?->role === 'Procurement';

@endphp


<div class="report-page">


{{-- ============================================================
SCREEN-ONLY ACTIONS
============================================================ --}}

<div class="print-actions">

    <button
        type="button"
        class="print-button"
        onclick="window.history.back()"
    >

        ← Back

    </button>


    <button
        type="button"
        class="print-button primary"
        onclick="window.print()"
    >

        Print / Save as PDF

    </button>

</div>


{{-- ============================================================
REPORT HEADER
============================================================ --}}

<div class="report-header">

    <div class="brand-area">

        <div class="header-top-row">

            <small>
                Reports Management
            </small>


            <div class="current-date">

                {{ now()->format('n/j/y, g:i A') }}

            </div>

        </div>


        @if($isProcurement)

            <h1>
                Procurement & Inventory Report
            </h1>


            <p>
                Purchasing activity and current inventory position for the selected period.
            </p>

        @else

            <h1>
                Business Summary
            </h1>


            <p>
                Financial, purchasing, expense, inventory, and cash remittance activity for the selected period.
            </p>

        @endif


        <div class="period-line">

            <strong>
                Reporting Period
            </strong>

        </div>


        <div class="period-value-line">

            <span class="period-value">

                {{ \Carbon\Carbon::parse($startDate)->format('F d, Y') }}

            </span>


            <span>
                →
            </span>


            <span class="period-value">

                {{ \Carbon\Carbon::parse($endDate)->format('F d, Y') }}

            </span>

        </div>


        <div class="generated-line">

            <strong>
                Generated:
            </strong>


            <span>
                {{ now()->format('F d, Y h:i A') }}
            </span>

        </div>

    </div>

</div>



@if($isProcurement)

{{-- ============================================================
PROCUREMENT REPORT
ONLY PROCUREMENT-RELATED INFORMATION
============================================================ --}}


{{-- ============================================================
PURCHASING SUMMARY
============================================================ --}}

<div class="section">

    <div class="section-header">

        <div>

            <div class="section-title">
                Purchasing Summary
            </div>


            <div class="section-subtitle">
                Purchase activity recorded during the selected reporting period.
            </div>

        </div>


        <div class="section-icon">
            ₱
        </div>

    </div>


    <div class="table-wrapper">

        <table class="summary-table">

            <thead>

                <tr>

                    <th>
                        Purchasing Metric
                    </th>

                    <th>
                        Amount
                    </th>

                    <th>
                        Records
                    </th>

                </tr>

            </thead>


            <tbody>

                <tr>

                    <td>
                        Total Purchases
                    </td>

                    <td class="amount">

                        ₱{{ number_format(
                            (float) $totalPurchases,
                            2
                        ) }}

                    </td>

                    <td class="number">

                        {{ $purchaseCount }}

                    </td>

                </tr>


                <tr class="total-row">

                    <td>
                        Purchase Records
                    </td>

                    <td class="amount">
                        —
                    </td>

                    <td class="number">

                        {{ $purchaseCount }}

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>



{{-- ============================================================
INVENTORY SUMMARY
============================================================ --}}

<div class="section">

    <div class="section-header">

        <div>

            <div class="section-title">
                Inventory Summary
            </div>


            <div class="section-subtitle">
                Current stock quantities, inventory value, and stock-level alerts.
            </div>

        </div>


        <div class="section-icon">
            ▦
        </div>

    </div>


    <div class="table-wrapper">

        <table class="summary-table">

            <thead>

                <tr>

                    <th>
                        Inventory Metric
                    </th>

                    <th>
                        Value
                    </th>

                    <th>
                        Unit
                    </th>

                </tr>

            </thead>


            <tbody>

                <tr>

                    <td>
                        Inventory Items
                    </td>

                    <td class="number">

                        {{ $inventoryCount }}

                    </td>

                    <td class="number">
                        Items
                    </td>

                </tr>


                <tr>

                    <td>
                        Inventory Value
                    </td>

                    <td class="amount">

                        ₱{{ number_format(
                            (float) $inventoryValue,
                            2
                        ) }}

                    </td>

                    <td class="number">
                        PHP
                    </td>

                </tr>


                <tr>

                    <td>
                        Low Stock Items
                    </td>

                    <td class="number">

                        {{ $lowStockCount }}

                    </td>

                    <td class="number">
                        Items
                    </td>

                </tr>


                <tr>

                    <td>
                        Out of Stock Items
                    </td>

                    <td class="number">

                        {{ $outOfStockCount }}

                    </td>

                    <td class="number">
                        Items
                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>



{{-- ============================================================
PROCUREMENT REPORT FOOTNOTE
============================================================ --}}

<div class="section">

    <div class="section-header">

        <div>

            <div class="section-title">
                Procurement Scope
            </div>


            <div class="section-subtitle">
                Information included in this report according to the Procurement role.
            </div>

        </div>


        <div class="section-icon">
            ✓
        </div>

    </div>


    <div class="table-wrapper">

        <table class="summary-table">

            <thead>

                <tr>

                    <th>
                        Report Area
                    </th>

                    <th>
                        Included
                    </th>

                    <th>
                        Scope
                    </th>

                </tr>

            </thead>


            <tbody>

                <tr>

                    <td>
                        Purchasing
                    </td>

                    <td class="number">
                        Yes
                    </td>

                    <td class="number">
                        Procurement
                    </td>

                </tr>


                <tr>

                    <td>
                        Inventory
                    </td>

                    <td class="number">
                        Yes
                    </td>

                    <td class="number">
                        Procurement
                    </td>

                </tr>


                <tr>

                    <td>
                        Sales
                    </td>

                    <td class="number">
                        No
                    </td>

                    <td class="number">
                        Restricted
                    </td>

                </tr>


                <tr>

                    <td>
                        Expenses
                    </td>

                    <td class="number">
                        No
                    </td>

                    <td class="number">
                        Restricted
                    </td>

                </tr>


                <tr>

                    <td>
                        Cash Remittance
                    </td>

                    <td class="number">
                        No
                    </td>

                    <td class="number">
                        Restricted
                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>



@else

{{-- ============================================================
FULL BUSINESS REPORT
CEO/ADMIN + FINANCE
============================================================ --}}


{{-- ============================================================
FINANCIAL SUMMARY
============================================================ --}}

<div class="section">

    <div class="section-header">

        <div>

            <div class="section-title">
                Financial Summary
            </div>


            <div class="section-subtitle">
                Financial activity recorded during the selected reporting period.
            </div>

        </div>


        <div class="section-icon">
            ₱
        </div>

    </div>


    <div class="table-wrapper">

        <table class="summary-table">

            <thead>

                <tr>

                    <th>
                        Financial Metric
                    </th>

                    <th>
                        Amount
                    </th>

                    <th>
                        Records
                    </th>

                </tr>

            </thead>


            <tbody>

                <tr>

                    <td>
                        Total Sales
                    </td>

                    <td class="amount">

                        ₱{{ number_format(
                            (float) $totalSales,
                            2
                        ) }}

                    </td>

                    <td class="number">
                        {{ $salesCount }}
                    </td>

                </tr>


                <tr>

                    <td>
                        Total Purchases
                    </td>

                    <td class="amount">

                        ₱{{ number_format(
                            (float) $totalPurchases,
                            2
                        ) }}

                    </td>

                    <td class="number">
                        {{ $purchaseCount }}
                    </td>

                </tr>


                <tr>

                    <td>
                        Total Expenses
                    </td>

                    <td class="amount">

                        ₱{{ number_format(
                            (float) $totalExpenses,
                            2
                        ) }}

                    </td>

                    <td class="number">
                        {{ $expenseCount }}
                    </td>

                </tr>


                <tr class="total-row">

                    <td>
                        Net Amount
                    </td>

                    <td
                        class="amount
                        {{ $netAmount >= 0
                            ? 'positive'
                            : 'negative' }}"
                    >

                        ₱{{ number_format(
                            (float) $netAmount,
                            2
                        ) }}

                    </td>

                    <td class="number">
                        —
                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>



{{-- ============================================================
INVENTORY SUMMARY
============================================================ --}}

<div class="section">

    <div class="section-header">

        <div>

            <div class="section-title">
                Inventory Summary
            </div>


            <div class="section-subtitle">
                Current stock quantities, inventory value, and stock-level alerts.
            </div>

        </div>


        <div class="section-icon">
            ▦
        </div>

    </div>


    <div class="table-wrapper">

        <table class="summary-table">

            <thead>

                <tr>

                    <th>
                        Inventory Metric
                    </th>

                    <th>
                        Value
                    </th>

                    <th>
                        Unit
                    </th>

                </tr>

            </thead>


            <tbody>

                <tr>

                    <td>
                        Inventory Items
                    </td>

                    <td class="number">
                        {{ $inventoryCount }}
                    </td>

                    <td class="number">
                        Items
                    </td>

                </tr>


                <tr>

                    <td>
                        Inventory Value
                    </td>

                    <td class="amount">

                        ₱{{ number_format(
                            (float) $inventoryValue,
                            2
                        ) }}

                    </td>

                    <td class="number">
                        PHP
                    </td>

                </tr>


                <tr>

                    <td>
                        Low Stock Items
                    </td>

                    <td class="number">
                        {{ $lowStockCount }}
                    </td>

                    <td class="number">
                        Items
                    </td>

                </tr>


                <tr>

                    <td>
                        Out of Stock Items
                    </td>

                    <td class="number">
                        {{ $outOfStockCount }}
                    </td>

                    <td class="number">
                        Items
                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>



{{-- ============================================================
CASH REMITTANCE
============================================================ --}}

<div class="section">

    <div class="section-header">

        <div>

            <div class="section-title">
                Cash Remittance Summary
            </div>


            <div class="section-subtitle">
                Expected and actual remittance activity recorded for the period.
            </div>

        </div>


        <div class="section-icon">
            ₱
        </div>

    </div>


    <div class="table-wrapper">

        <table class="summary-table">

            <thead>

                <tr>

                    <th>
                        Remittance Metric
                    </th>

                    <th>
                        Amount
                    </th>

                    <th>
                        Records
                    </th>

                </tr>

            </thead>


            <tbody>

                <tr>

                    <td>
                        Expected Remittance
                    </td>

                    <td class="amount">

                        ₱{{ number_format(
                            (float) $expectedRemittance,
                            2
                        ) }}

                    </td>

                    <td class="number">
                        —
                    </td>

                </tr>


                <tr>

                    <td>
                        Actual Remittance
                    </td>

                    <td class="amount">

                        ₱{{ number_format(
                            (float) $actualRemittance,
                            2
                        ) }}

                    </td>

                    <td class="number">
                        —
                    </td>

                </tr>


                <tr class="total-row">

                    <td>
                        Variance
                    </td>

                    <td
                        class="amount
                        {{ $remittanceVariance >= 0
                            ? 'positive'
                            : 'negative' }}"
                    >

                        ₱{{ number_format(
                            (float) $remittanceVariance,
                            2
                        ) }}

                    </td>

                    <td class="number">
                        —
                    </td>

                </tr>


                <tr>

                    <td>
                        Remittance Records
                    </td>

                    <td class="amount">
                        —
                    </td>

                    <td class="number">
                        {{ $remittanceCount }}
                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>



{{-- ============================================================
DAILY SALES
============================================================ --}}

<div class="section">

    <div class="section-header">

        <div>

            <div class="section-title">
                Daily Sales
            </div>


            <div class="section-subtitle">
                Sales transactions grouped by transaction date.
            </div>

        </div>


        <div class="section-icon">
            ◷
        </div>

    </div>


    <div class="table-wrapper">

        <table class="daily-sales-table">

            <thead>

                <tr>

                    <th>
                        Transaction Date
                    </th>

                    <th>
                        Sales Amount
                    </th>

                    <th>
                        Transactions
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($dailySales as $daily)

                    <tr>

                        <td class="date-cell">

                            @if($daily->report_date)

                                {{ \Carbon\Carbon::parse(
                                    $daily->report_date
                                )->format('M d, Y') }}

                            @else

                                —

                            @endif

                        </td>


                        <td class="amount">

                            ₱{{ number_format(
                                (float) $daily->total_sales,
                                2
                            ) }}

                        </td>


                        <td class="number">

                            {{ (int) $daily->transaction_count }}

                        </td>

                    </tr>

                @empty

                    <tr class="empty-row">

                        <td colspan="3">

                            <span class="muted">
                                No sales records found for this period.
                            </span>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>



{{-- ============================================================
RECENT EXPENSES
============================================================ --}}

<div class="section">

    <div class="section-header">

        <div>

            <div class="section-title">
                Recent Expenses
            </div>


            <div class="section-subtitle">
                Latest expense transactions recorded during the selected period.
            </div>

        </div>


        <div class="section-icon">
            ₱
        </div>

    </div>


    <div class="table-wrapper">

        <table class="expense-table">

            <thead>

                <tr>

                    <th>
                        Date
                    </th>

                    <th>
                        Category
                    </th>

                    <th>
                        Description
                    </th>

                    <th>
                        Amount
                    </th>

                    <th>
                        Recorded By
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($recentExpenses as $expense)

                    <tr>

                        <td class="date-cell">

                            @if($expense->expense_date)

                                {{ $expense->expense_date->format(
                                    'M d, Y'
                                ) }}

                            @else

                                —

                            @endif

                        </td>


                        <td>

                            {{ $expense->category ?? '—' }}

                        </td>


                        <td class="description-cell">

                            {{ $expense->description ?? '—' }}

                        </td>


                        <td class="amount">

                            ₱{{ number_format(
                                (float) $expense->amount,
                                2
                            ) }}

                        </td>


                        <td>

                            @if($expense->user)

                                {{ $expense->user->name
                                    ?? $expense->user->full_name
                                    ?? '—' }}

                            @else

                                —

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr class="empty-row">

                        <td colspan="5">

                            <span class="muted">
                                No expense records found for this period.
                            </span>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>



{{-- ============================================================
RECENT CASH REMITTANCES
============================================================ --}}

<div class="section">

    <div class="section-header">

        <div>

            <div class="section-title">
                Recent Cash Remittances
            </div>


            <div class="section-subtitle">
                Latest cash remittance transactions recorded during the period.
            </div>

        </div>


        <div class="section-icon">
            ₱
        </div>

    </div>


    <div class="table-wrapper">

        <table class="remittance-table">

            <thead>

                <tr>

                    <th>
                        Remittance Date
                    </th>

                    <th>
                        Expected Amount
                    </th>

                    <th>
                        Actual Amount
                    </th>

                    <th>
                        Variance
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($recentRemittances as $remittance)

                    @php

                        $expected =
                            (float) (
                                $remittance->expected_amount
                                ?? 0
                            );

                        $actual =
                            (float) (
                                $remittance->actual_amount
                                ?? 0
                            );

                        $variance =
                            $actual - $expected;

                    @endphp


                    <tr>

                        <td class="date-cell">

                            @if($remittance->remittance_date)

                                {{ $remittance->remittance_date->format(
                                    'M d, Y'
                                ) }}

                            @else

                                —

                            @endif

                        </td>


                        <td class="amount">

                            ₱{{ number_format(
                                $expected,
                                2
                            ) }}

                        </td>


                        <td class="amount">

                            ₱{{ number_format(
                                $actual,
                                2
                            ) }}

                        </td>


                        <td
                            class="amount
                            {{ $variance >= 0
                                ? 'positive'
                                : 'negative' }}"
                        >

                            ₱{{ number_format(
                                $variance,
                                2
                            ) }}

                        </td>

                    </tr>

                @empty

                    <tr class="empty-row">

                        <td colspan="4">

                            <span class="muted">
                                No cash remittance records found for the period.
                            </span>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endif



{{-- ============================================================
FOOTER
============================================================ --}}

<div class="report-footer">

    BiteSync Inventory Management System

    <br>

    @if($isProcurement)

        Procurement & Inventory Report

    @else

        Business Report

    @endif

    <br>

    Generated {{ now()->format('F d, Y h:i A') }}

</div>


</div>

</body>

</html>