@extends('layouts.app')

@section('title', 'BiteSync | Reports')

@section('content')

<div class="reports-page">

    {{-- =========================================================
         REPORT HEADER
    ========================================================== --}}

    <div class="report-header">

        <div class="report-header-main">

            <div class="report-eyebrow">
                Reports & Analytics
            </div>

            <h1>
                Business Report
            </h1>

            <p>
                @if ($userRole === 'Procurement')
                    Purchasing and inventory activity for the selected reporting period.
                @elseif ($userRole === 'Finance')
                    Financial, purchasing, inventory, and cash activity for the selected reporting period.
                @else
                    Consolidated sales, purchasing, expense, inventory, and cash activity.
                @endif
            </p>

        </div>


        <div class="report-header-date">

            <span class="report-date-icon">
                ◷
            </span>

            <div>

                <small>
                    Report Generated
                </small>

                <strong>
                    {{ now()->format('F d, Y') }}
                </strong>

            </div>

        </div>

    </div>


    {{-- =========================================================
         REPORT NAVIGATION / BREADCRUMB
    ========================================================== --}}

    <div class="reports-breadcrumb">

        <span>
            Dashboard
        </span>

        <span class="breadcrumb-separator">
            /
        </span>

        <strong>
            Reports
        </strong>

    </div>


    {{-- =========================================================
         REPORT CONTROL BAR
    ========================================================== --}}

    <div class="report-control-panel">

        <div class="report-control-heading">

            <div class="report-control-icon">
                ◷
            </div>

            <div>

                <div class="report-control-title">
                    Reporting Period
                </div>

                <div class="report-control-subtitle">
                    Select the period to include in this business report.
                </div>

            </div>

        </div>


        <form
            method="GET"
            action="{{ route('reports.index') }}"
            class="report-control-form"
        >

            <div class="report-date-field">

                <label for="start_date">
                    Start Date
                </label>

                <input
                    type="date"
                    id="start_date"
                    name="start_date"
                    value="{{ $startDate }}"
                >

            </div>


            <div class="report-date-field">

                <label for="end_date">
                    End Date
                </label>

                <input
                    type="date"
                    id="end_date"
                    name="end_date"
                    value="{{ $endDate }}"
                >

            </div>


            <button
                type="submit"
                class="report-control-button report-control-primary"
            >
                Apply Period
            </button>


            <a
                href="{{ route('reports.index') }}"
                class="report-control-button report-control-reset"
            >
                Reset
            </a>

        </form>

    </div>


    {{-- =========================================================
         REPORT ACTIONS
    ========================================================== --}}

    <div class="report-actions">

        <div class="report-actions-information">

            <div class="report-actions-title">
                Report Actions
            </div>

            <div class="report-actions-subtitle">

                @if ($userRole === 'Procurement')
                    Export or print the purchasing and inventory report.
                @elseif ($userRole === 'Finance')
                    Export or print the financial and operational report.
                @else
                    Export or print the complete business report.
                @endif

            </div>

        </div>


        <div class="report-actions-buttons">

            <button
                type="button"
                class="report-action report-action-print"
                onclick="window.print()"
            >
                <span>🖨</span>
                Print Report
            </button>


            <a
                href="{{ route('reports.pdf', request()->query()) }}"
                class="report-action"
            >
                <span>↓</span>
                Download PDF
            </a>


            <a
                href="{{ route('reports.excel', request()->query()) }}"
                class="report-action"
            >
                <span>▦</span>
                Download Excel
            </a>

        </div>

    </div>


    {{-- =========================================================
         REPORT DOCUMENT
    ========================================================== --}}

    <div class="report-document">


        {{-- =====================================================
             REPORT DOCUMENT HEADER
        ====================================================== --}}

        <div class="report-document-header">

            <div>

                <div class="report-document-label">
                    BITESYNC
                </div>

                <h2>

                    @if ($userRole === 'Procurement')
                        Purchasing & Inventory Report
                    @elseif ($userRole === 'Finance')
                        Financial Performance Report
                    @else
                        Business Performance Report
                    @endif

                </h2>

                <p>

                    @if ($userRole === 'Procurement')
                        Purchasing activity and current inventory position.
                    @elseif ($userRole === 'Finance')
                        Financial, purchasing, expense, inventory, and cash activity summary.
                    @else
                        Sales, purchasing, expenses, inventory, and cash activity summary.
                    @endif

                </p>

            </div>


            <div class="report-period-box">

                <span>
                    REPORTING PERIOD
                </span>

                <strong>
                    {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }}
                    –
                    {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                </strong>

            </div>

        </div>


        {{-- =====================================================
             EXECUTIVE SUMMARY
        ====================================================== --}}

        <section class="report-section">

            <div class="report-section-heading">

                <div>

                    <span class="report-section-number">
                        01
                    </span>

                    <div class="report-section-title">

                        @if ($userRole === 'Procurement')
                            Procurement Summary
                        @else
                            Executive Summary
                        @endif

                    </div>

                    <div class="report-section-description">

                        @if ($userRole === 'Procurement')
                            Key purchasing and inventory figures for the selected reporting period.
                        @else
                            Key financial and operational results for the selected reporting period.
                        @endif

                    </div>

                </div>

            </div>


            <div class="report-summary-grid">


                {{-- =================================================
                     SALES
                ================================================== --}}

                @if ($showSales)

                    <div class="report-summary-card">

                        <div class="report-summary-card-top">

                            <span>
                                Total Sales
                            </span>

                            <div class="report-summary-icon">
                                ₱
                            </div>

                        </div>

                        <strong>
                            ₱{{ number_format((float) $totalSales, 2) }}
                        </strong>

                        <small>
                            {{ number_format((int) $salesCount) }} completed transactions
                        </small>

                    </div>

                @endif


                {{-- =================================================
                     PURCHASES
                ================================================== --}}

                @if ($showPurchases)

                    <div class="report-summary-card">

                        <div class="report-summary-card-top">

                            <span>
                                Total Purchases
                            </span>

                            <div class="report-summary-icon">
                                ◈
                            </div>

                        </div>

                        <strong>
                            ₱{{ number_format((float) $totalPurchases, 2) }}
                        </strong>

                        <small>
                            {{ number_format((int) $purchaseCount) }} purchase records
                        </small>

                    </div>

                @endif


                {{-- =================================================
                     EXPENSES
                ================================================== --}}

                @if ($showExpenses)

                    <div class="report-summary-card">

                        <div class="report-summary-card-top">

                            <span>
                                Total Expenses
                            </span>

                            <div class="report-summary-icon">
                                ₱
                            </div>

                        </div>

                        <strong>
                            ₱{{ number_format((float) $totalExpenses, 2) }}
                        </strong>

                        <small>
                            {{ number_format((int) $expenseCount) }} recorded expenses
                        </small>

                    </div>

                @endif


                {{-- =================================================
                     NET
                ================================================== --}}

                @if (
                    $showSales &&
                    $showPurchases &&
                    $showExpenses
                )

                    <div class="report-summary-card report-summary-card-net">

                        <div class="report-summary-card-top">

                            <span>
                                Net Amount
                            </span>

                            <div class="report-summary-icon">
                                ✓
                            </div>

                        </div>

                        <strong>
                            ₱{{ number_format((float) $netAmount, 2) }}
                        </strong>

                        <small>
                            Sales less purchases and expenses
                        </small>

                    </div>

                @endif


                {{-- =================================================
                     PROCUREMENT INVENTORY SUMMARY
                ================================================== --}}

                @if (
                    $userRole === 'Procurement' &&
                    $showInventory
                )

                    <div class="report-summary-card">

                        <div class="report-summary-card-top">

                            <span>
                                Inventory Items
                            </span>

                            <div class="report-summary-icon">
                                ◫
                            </div>

                        </div>

                        <strong>
                            {{ number_format((int) $inventoryCount) }}
                        </strong>

                        <small>
                            Items currently recorded
                        </small>

                    </div>

                @endif


                @if (
                    $userRole === 'Procurement' &&
                    $showInventory
                )

                    <div class="report-summary-card report-summary-card-net">

                        <div class="report-summary-card-top">

                            <span>
                                Inventory Value
                            </span>

                            <div class="report-summary-icon">
                                ₱
                            </div>

                        </div>

                        <strong>
                            ₱{{ number_format((float) $inventoryValue, 2) }}
                        </strong>

                        <small>
                            Current recorded inventory value
                        </small>

                    </div>

                @endif

            </div>

        </section>


        {{-- =====================================================
             FINANCIAL PERFORMANCE
             CEO/ADMIN + FINANCE ONLY
        ====================================================== --}}

        @if (
            $showSales &&
            $showPurchases &&
            $showExpenses
        )

            <section class="report-section">

                <div class="report-section-heading">

                    <div>

                        <span class="report-section-number">
                            02
                        </span>

                        <div class="report-section-title">
                            Financial Performance
                        </div>

                        <div class="report-section-description">
                            Summary of revenue, purchasing costs, operating expenses, and resulting net amount.
                        </div>

                    </div>

                </div>


                <div class="financial-report-table">

                    <div class="financial-row financial-header-row">

                        <span>
                            Financial Measure
                        </span>

                        <span>
                            Amount
                        </span>

                    </div>


                    <div class="financial-row">

                        <span>
                            Total Sales Revenue
                        </span>

                        <strong>
                            ₱{{ number_format((float) $totalSales, 2) }}
                        </strong>

                    </div>


                    <div class="financial-row">

                        <span>
                            Less: Purchases
                        </span>

                        <strong>
                            ₱{{ number_format((float) $totalPurchases, 2) }}
                        </strong>

                    </div>


                    <div class="financial-row">

                        <span>
                            Less: Operating Expenses
                        </span>

                        <strong>
                            ₱{{ number_format((float) $totalExpenses, 2) }}
                        </strong>

                    </div>


                    <div class="financial-row financial-total-row">

                        <span>
                            Net Amount
                        </span>

                        <strong>
                            ₱{{ number_format((float) $netAmount, 2) }}
                        </strong>

                    </div>

                </div>


                <div class="financial-two-column">

                    <div class="financial-mini-card">

                        <span>
                            Sales Transactions
                        </span>

                        <strong>
                            {{ number_format((int) $salesCount) }}
                        </strong>

                        <small>
                            Completed sales within the selected period
                        </small>

                    </div>


                    <div class="financial-mini-card">

                        <span>
                            Average Sale
                        </span>

                        <strong>
                            ₱{{ number_format(
                                $salesCount > 0
                                    ? $totalSales / $salesCount
                                    : 0,
                                2
                            ) }}
                        </strong>

                        <small>
                            Average value per sales transaction
                        </small>

                    </div>


                    <div class="financial-mini-card">

                        <span>
                            Expense Records
                        </span>

                        <strong>
                            {{ number_format((int) $expenseCount) }}
                        </strong>

                        <small>
                            Recorded operating expenses
                        </small>

                    </div>


                    <div class="financial-mini-card">

                        <span>
                            Average Expense
                        </span>

                        <strong>
                            ₱{{ number_format(
                                $expenseCount > 0
                                    ? $totalExpenses / $expenseCount
                                    : 0,
                                2
                            ) }}
                        </strong>

                        <small>
                            Average expense record value
                        </small>

                    </div>

                </div>

            </section>

        @endif


        {{-- =====================================================
             PROCUREMENT PURCHASE OVERVIEW
             PROCUREMENT ONLY
        ====================================================== --}}

        @if (
            $userRole === 'Procurement' &&
            $showPurchases
        )

            <section class="report-section">

                <div class="report-section-heading">

                    <div>

                        <span class="report-section-number">
                            02
                        </span>

                        <div class="report-section-title">
                            Purchasing Overview
                        </div>

                        <div class="report-section-description">
                            Summary of purchasing activity recorded during the selected reporting period.
                        </div>

                    </div>

                </div>


                <div class="financial-report-table">

                    <div class="financial-row financial-header-row">

                        <span>
                            Purchasing Measure
                        </span>

                        <span>
                            Value
                        </span>

                    </div>


                    <div class="financial-row">

                        <span>
                            Purchase Records
                        </span>

                        <strong>
                            {{ number_format((int) $purchaseCount) }}
                        </strong>

                    </div>


                    <div class="financial-row">

                        <span>
                            Total Purchase Cost
                        </span>

                        <strong>
                            ₱{{ number_format((float) $totalPurchases, 2) }}
                        </strong>

                    </div>


                    <div class="financial-row financial-total-row">

                        <span>
                            Reporting Period
                        </span>

                        <strong>
                            {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }}
                            –
                            {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                        </strong>

                    </div>

                </div>


                <div class="financial-two-column">

                    <div class="financial-mini-card">

                        <span>
                            Purchase Records
                        </span>

                        <strong>
                            {{ number_format((int) $purchaseCount) }}
                        </strong>

                        <small>
                            Purchases recorded during the period
                        </small>

                    </div>


                    <div class="financial-mini-card">

                        <span>
                            Average Purchase
                        </span>

                        <strong>
                            ₱{{ number_format(
                                $purchaseCount > 0
                                    ? $totalPurchases / $purchaseCount
                                    : 0,
                                2
                            ) }}
                        </strong>

                        <small>
                            Average value per purchase record
                        </small>

                    </div>

                </div>

            </section>

        @endif


        {{-- =====================================================
             SALES REPORT
             CEO/ADMIN + FINANCE ONLY
        ====================================================== --}}

        @if ($showSales)

            <section class="report-section">

                <div class="report-section-heading">

                    <div>

                        <span class="report-section-number">

                            @if ($showSales && $showPurchases && $showExpenses)
                                03
                            @else
                                02
                            @endif

                        </span>

                        <div class="report-section-title">
                            Sales Report
                        </div>

                        <div class="report-section-description">
                            Daily completed sales transactions and revenue generated during the reporting period.
                        </div>

                    </div>

                </div>


                <div class="report-table-wrapper">

                    <table class="formal-report-table">

                        <thead>

                            <tr>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Transactions
                                </th>

                                <th class="text-right">
                                    Total Sales
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @if ($dailySales->count())

                                @foreach ($dailySales as $daily)

                                    <tr>

                                        <td>
                                            {{ \Carbon\Carbon::parse($daily->report_date)->format('M d, Y') }}
                                        </td>

                                        <td>
                                            {{ number_format((int) $daily->transaction_count) }}
                                        </td>

                                        <td class="text-right amount-cell">
                                            ₱{{ number_format((float) $daily->total_sales, 2) }}
                                        </td>

                                    </tr>

                                @endforeach


                                <tr class="table-total-row">

                                    <td>
                                        Total
                                    </td>

                                    <td>
                                        {{ number_format((int) $salesCount) }}
                                    </td>

                                    <td class="text-right">
                                        ₱{{ number_format((float) $totalSales, 2) }}
                                    </td>

                                </tr>

                            @else

                                <tr>

                                    <td
                                        colspan="3"
                                        class="table-empty"
                                    >
                                        No completed sales records were found for the selected period.
                                    </td>

                                </tr>

                            @endif

                        </tbody>

                    </table>

                </div>

            </section>

        @endif


        {{-- =====================================================
             INVENTORY REPORT
             CEO/ADMIN + FINANCE + PROCUREMENT
        ====================================================== --}}

        @if ($showInventory)

            <section class="report-section">

                <div class="report-section-heading">

                    <div>

                        <span class="report-section-number">

                            @if ($userRole === 'Procurement')
                                03
                            @elseif ($showSales && $showPurchases && $showExpenses)
                                04
                            @else
                                03
                            @endif

                        </span>

                        <div class="report-section-title">
                            Inventory Report
                        </div>

                        <div class="report-section-description">

                            @if ($userRole === 'Procurement')
                                Current inventory position, stock levels, and availability.
                            @else
                                Current inventory position and stock availability.
                            @endif

                        </div>

                    </div>

                </div>


                <div class="inventory-report-grid">

                    <div class="inventory-report-card">

                        <span>
                            Inventory Items
                        </span>

                        <strong>
                            {{ number_format((int) $inventoryCount) }}
                        </strong>

                        <small>
                            Items currently recorded
                        </small>

                    </div>


                    <div class="inventory-report-card">

                        <span>
                            Inventory Value
                        </span>

                        <strong>
                            ₱{{ number_format((float) $inventoryValue, 2) }}
                        </strong>

                        <small>
                            Current recorded inventory value
                        </small>

                    </div>


                    <div class="inventory-report-card inventory-warning">

                        <span>
                            Low Stock
                        </span>

                        <strong>
                            {{ number_format((int) $lowStockCount) }}
                        </strong>

                        <small>
                            Items requiring attention
                        </small>

                    </div>


                    <div class="inventory-report-card inventory-danger">

                        <span>
                            Out of Stock
                        </span>

                        <strong>
                            {{ number_format((int) $outOfStockCount) }}
                        </strong>

                        <small>
                            Items currently unavailable
                        </small>

                    </div>

                </div>

            </section>

        @endif


        {{-- =====================================================
             EXPENSE REPORT
             CEO/ADMIN + FINANCE ONLY
        ====================================================== --}}

        @if ($showExpenses)

            <section class="report-section">

                <div class="report-section-heading">

                    <div>

                        <span class="report-section-number">
                            05
                        </span>

                        <div class="report-section-title">
                            Expense Report
                        </div>

                        <div class="report-section-description">
                            Recorded operating expenses and recent expense activity.
                        </div>

                    </div>

                </div>


                <div class="report-table-wrapper">

                    <table class="formal-report-table">

                        <thead>

                            <tr>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Category
                                </th>

                                <th>
                                    Recorded By
                                </th>

                                <th class="text-right">
                                    Amount
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @if ($recentExpenses->count())

                                @foreach ($recentExpenses as $expense)

                                    <tr>

                                        <td>

                                            {{ $expense->expense_date
                                                ? $expense->expense_date->format('M d, Y')
                                                : '—'
                                            }}

                                        </td>


                                        <td>
                                            {{ $expense->category }}
                                        </td>


                                        <td>

                                            @if ($expense->user)

                                                {{ $expense->user->name
                                                    ?? $expense->user->full_name
                                                    ?? '—'
                                                }}

                                            @else

                                                —

                                            @endif

                                        </td>


                                        <td class="text-right amount-cell">

                                            ₱{{ number_format(
                                                (float) $expense->amount,
                                                2
                                            ) }}

                                        </td>

                                    </tr>

                                @endforeach


                                <tr class="table-total-row">

                                    <td colspan="3">
                                        Total Recorded Expenses
                                    </td>

                                    <td class="text-right">
                                        ₱{{ number_format((float) $totalExpenses, 2) }}
                                    </td>

                                </tr>

                            @else

                                <tr>

                                    <td
                                        colspan="4"
                                        class="table-empty"
                                    >
                                        No expense records were found for the selected period.
                                    </td>

                                </tr>

                            @endif

                        </tbody>

                    </table>

                </div>

            </section>

        @endif


        {{-- =====================================================
             CASH REMITTANCE
             CEO/ADMIN + FINANCE ONLY
        ====================================================== --}}

        @if ($showRemittance)

            <section class="report-section">

                <div class="report-section-heading">

                    <div>

                        <span class="report-section-number">
                            06
                        </span>

                        <div class="report-section-title">
                            Cash Remittance Report
                        </div>

                        <div class="report-section-description">
                            Expected and actual cash remittance activity for the reporting period.
                        </div>

                    </div>

                </div>


                <div class="remittance-summary-grid">

                    <div class="remittance-card">

                        <span>
                            Remittance Records
                        </span>

                        <strong>
                            {{ number_format((int) $remittanceCount) }}
                        </strong>

                    </div>


                    <div class="remittance-card">

                        <span>
                            Expected Amount
                        </span>

                        <strong>
                            ₱{{ number_format((float) $expectedRemittance, 2) }}
                        </strong>

                    </div>


                    <div class="remittance-card">

                        <span>
                            Actual Amount
                        </span>

                        <strong>
                            ₱{{ number_format((float) $actualRemittance, 2) }}
                        </strong>

                    </div>


                    <div class="remittance-card">

                        <span>
                            Variance
                        </span>

                        <strong
                            class="
                                @if ($remittanceVariance > 0)
                                    variance-positive
                                @elseif ($remittanceVariance < 0)
                                    variance-negative
                                @else
                                    variance-zero
                                @endif
                            "
                        >
                            ₱{{ number_format((float) $remittanceVariance, 2) }}
                        </strong>

                    </div>

                </div>


                <div class="report-table-wrapper">

                    <table class="formal-report-table">

                        <thead>

                            <tr>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Reference
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-right">
                                    Actual Amount
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @if ($recentRemittances->count())

                                @foreach ($recentRemittances as $remittance)

                                    <tr>

                                        <td>

                                            {{ $remittance->remittance_date
                                                ? $remittance->remittance_date->format('M d, Y')
                                                : '—'
                                            }}

                                        </td>


                                        <td>
                                            {{ $remittance->reference_no ?: '—' }}
                                        </td>


                                        <td>

                                            <span class="report-status">
                                                {{ $remittance->status }}
                                            </span>

                                        </td>


                                        <td class="text-right amount-cell">

                                            ₱{{ number_format(
                                                (float) $remittance->actual_amount,
                                                2
                                            ) }}

                                        </td>

                                    </tr>

                                @endforeach

                            @else

                                <tr>

                                    <td
                                        colspan="4"
                                        class="table-empty"
                                    >
                                        No cash remittance records were found for the selected period.
                                    </td>

                                </tr>

                            @endif

                        </tbody>

                    </table>

                </div>

            </section>

        @endif


        {{-- =====================================================
             REPORT FOOTER
        ====================================================== --}}

        <div class="report-document-footer">

            <div>

                <strong>
                    BiteSync
                </strong>

                <span>
                    Inventory Management System
                </span>

            </div>

            <div>

                Generated on
                {{ now()->format('F d, Y h:i A') }}

            </div>

        </div>


    </div>

</div>


@push('styles')

<style>

/* =========================================================
   REPORTS PAGE
========================================================= */

.reports-page {
    width: 100%;
    padding-bottom: 30px;
}


/* =========================================================
   REPORT HEADER
========================================================= */

.report-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 25px;
    margin-bottom: 14px;
}

.report-header-main {
    min-width: 0;
}

.report-eyebrow {
    display: block;
    margin-bottom: 5px;
    color: #a9825b;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
}

.report-header h1 {
    margin: 0;
    color: var(--dark);
    font-size: clamp(1.6rem, 2vw, 1.9rem);
    line-height: 1.15;
    font-weight: 700;
    letter-spacing: -.04rem;
}

.report-header p {
    margin: 6px 0 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.5;
}


/* =========================================================
   HEADER DATE
========================================================= */

.report-header-date {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    min-width: 150px;
    padding: 9px 12px;
    border: 1px solid var(--border);
    border-radius: 10px;
    background: white;
    box-shadow: 0 3px 12px rgba(43,31,23,.025);
}

.report-date-icon {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 8px;
    background: var(--orange-light);
    color: var(--orange);
    font-size: 17px;
}

.report-header-date small {
    display: block;
    margin-bottom: 2px;
    color: var(--muted);
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .04rem;
}

.report-header-date strong {
    display: block;
    color: var(--dark);
    font-size: 12px;
    font-weight: 700;
}


/* =========================================================
   BREADCRUMB
========================================================= */

.reports-breadcrumb {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 17px;
    color: #9a8e84;
    font-size: 11px;
    line-height: 1.4;
}

.reports-breadcrumb strong {
    color: #6f6259;
    font-weight: 600;
}

.breadcrumb-separator {
    color: #c2b5aa;
}


/* =========================================================
   REPORT CONTROL PANEL
========================================================= */

.report-control-panel {
    min-height: 65px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 17px;
    padding: 14px 18px;
    border: 1px solid var(--border);
    border-radius: 15px;
    background: white;
    box-shadow: 0 4px 16px rgba(43,31,23,.035);
}

.report-control-heading {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 210px;
}

.report-control-icon {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 8px;
    background: var(--orange-light);
    color: var(--orange);
    font-size: 13px;
}

.report-control-title {
    color: var(--dark);
    font-size: 14px;
    font-weight: 700;
}

.report-control-subtitle {
    margin-top: 3px;
    color: var(--muted);
    font-size: 11px;
    font-weight: 400;
    line-height: 1.45;
}

.report-control-form {
    display: flex;
    align-items: flex-end;
    gap: 8px;
    flex: 1;
    justify-content: flex-end;
}

.report-date-field {
    min-width: 150px;
}

.report-date-field label {
    display: block;
    margin-bottom: 5px;
    color: #4c3c31;
    font-size: 10px;
    font-weight: 600;
}

.report-date-field input {
    width: 100%;
    height: 35px;
    box-sizing: border-box;
    padding: 0 10px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: white;
    color: var(--text);
    font-family: inherit;
    font-size: 11px;
    outline: none;
}

.report-date-field input:focus {
    border-color: #d5a77d;
    box-shadow: 0 0 0 3px rgba(196,122,58,.07);
}


/* =========================================================
   CONTROL BUTTONS
========================================================= */

.report-control-button {
    height: 35px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 11px;
    border-radius: 8px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 700;
    line-height: 1.2;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}

.report-control-primary {
    border: 0;
    background: var(--dark);
    color: white;
}

.report-control-primary:hover {
    background: var(--dark-soft);
}

.report-control-reset {
    border: 1px solid var(--border);
    background: white;
    color: var(--muted);
}

.report-control-reset:hover {
    background: #faf7f3;
    color: var(--dark);
}


/* =========================================================
   REPORT ACTIONS
========================================================= */

.report-actions {
    min-height: 65px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 17px;
    margin-bottom: 17px;
    padding: 14px 18px;
    border: 1px solid var(--border);
    border-radius: 15px;
    background: white;
    box-shadow: 0 4px 16px rgba(43,31,23,.035);
}

.report-actions-information {
    min-width: 0;
}

.report-actions-title {
    color: var(--dark);
    font-size: 15px;
    font-weight: 600;
}

.report-actions-subtitle {
    margin-top: 3px;
    color: var(--muted);
    font-size: 11px;
    font-weight: 400;
    line-height: 1.45;
}

.report-actions-buttons {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 5px;
    flex-shrink: 0;
}

.report-action {
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 0 10px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: white;
    color: #6f6259;
    font-family: inherit;
    font-size: 11px;
    font-weight: 600;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}

.report-action:hover {
    background: #faf7f3;
    border-color: #d5c5b7;
    color: var(--dark);
}

.report-action-print {
    border-color: transparent;
    background: linear-gradient(
        135deg,
        var(--orange),
        var(--orange-dark)
    );
    color: white;
}

.report-action-print:hover {
    border-color: transparent;
    background: var(--orange-dark);
    color: white;
}


/* =========================================================
   REPORT DOCUMENT
========================================================= */

.report-document {
    overflow: hidden;
    border: 1px solid var(--border);
    border-radius: 15px;
    background: white;
    box-shadow: 0 5px 20px rgba(43,31,23,.045);
}


/* =========================================================
   DOCUMENT HEADER
========================================================= */

.report-document-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 25px;
    padding: 25px 26px 22px;
    border-bottom: 2px solid var(--dark);
}

.report-document-label {
    margin-bottom: 6px;
    color: var(--orange-dark);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .16em;
}

.report-document-header h2 {
    margin: 0;
    color: var(--dark);
    font-size: 23px;
    line-height: 1.2;
    font-weight: 700;
    letter-spacing: -.03rem;
}

.report-document-header p {
    margin: 6px 0 0;
    color: var(--muted);
    font-size: 12px;
    font-weight: 400;
    line-height: 1.5;
}

.report-period-box {
    min-width: 210px;
    padding: 10px 12px;
    border: 1px solid var(--border);
    border-radius: 9px;
    background: #fcfaf7;
    text-align: right;
}

.report-period-box span {
    display: block;
    margin-bottom: 5px;
    color: var(--muted);
    font-size: 9px;
    font-weight: 700;
    letter-spacing: .07em;
}

.report-period-box strong {
    display: block;
    color: var(--dark);
    font-size: 11px;
    line-height: 1.4;
    font-weight: 600;
}


/* =========================================================
   REPORT SECTION
========================================================= */

.report-section {
    padding: 22px 26px;
    border-bottom: 1px solid #eee7e1;
}

.report-section:last-of-type {
    border-bottom: 0;
}

.report-section-heading {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 15px;
}

.report-section-number {
    display: inline-block;
    margin-bottom: 4px;
    color: var(--orange);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .08em;
}

.report-section-title {
    color: var(--dark);
    font-size: 16px;
    line-height: 1.3;
    font-weight: 700;
}

.report-section-description {
    margin-top: 4px;
    color: #756a62;
    font-size: 12px;
    font-weight: 400;
    line-height: 1.5;
}


/* =========================================================
   EXECUTIVE SUMMARY CARDS
========================================================= */

.report-summary-grid {
    display: grid;
    grid-template-columns: repeat(4,minmax(0,1fr));
    gap: 12px;
}

.report-summary-card {
    min-height: 115px;
    padding: 13px 14px;
    border: 1px solid var(--border);
    border-radius: 11px;
    background: #fcfaf7;
}

.report-summary-card-net {
    background: #fbf7f1;
}

.report-summary-card-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}

.report-summary-card-top > span {
    color: #756a62;
    font-size: 11px;
    font-weight: 600;
    line-height: 1.35;
}

.report-summary-icon {
    width: 27px;
    height: 27px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 7px;
    background: var(--orange-light);
    color: var(--orange);
    font-size: 11px;
    font-weight: 700;
}


/* =========================================================
   IMPORTANT CARD VALUE ALIGNMENT
   Title = top
   Value = next line, right
   Subtext = below, left
========================================================= */

.report-summary-card > strong {
    display: block;
    width: 100%;
    margin-top: 16px;
    color: var(--dark);
    font-size: 17px;
    line-height: 1.15;
    font-weight: 700;
    text-align: right;
    white-space: nowrap;
}

.report-summary-card > small {
    display: block;
    width: 100%;
    margin-top: 9px;
    color: #80766e;
    font-size: 10px;
    font-weight: 400;
    line-height: 1.4;
    text-align: left;
}


/* =========================================================
   FINANCIAL STATEMENT
========================================================= */

.financial-report-table {
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
}

.financial-row {
    min-height: 44px;
    display: grid;
    grid-template-columns: 1fr 180px;
    align-items: center;
    gap: 15px;
    padding: 0 15px;
    border-bottom: 1px solid #eee7e1;
}

.financial-row:last-child {
    border-bottom: 0;
}

.financial-row > span {
    color: #625951;
    font-size: 13px;
    font-weight: 400;
    line-height: 1.4;
}

.financial-row > strong {
    color: var(--dark);
    font-size: 13px;
    font-weight: 700;
    text-align: right;
    line-height: 1.4;
}

.financial-header-row {
    min-height: 40px;
    background: #fbf9f6;
}

.financial-header-row > span {
    color: #625951;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0;
    text-transform: none;
}

.financial-header-row > span:last-child {
    text-align: right;
}

.financial-total-row {
    background: #f7efe7;
    border-top: 1px solid #dfcdbd;
}

.financial-total-row > span,
.financial-total-row > strong {
    color: var(--dark);
    font-weight: 700;
}

.financial-two-column {
    display: grid;
    grid-template-columns: repeat(4,minmax(0,1fr));
    gap: 10px;
    margin-top: 12px;
}

.financial-mini-card {
    min-height: 86px;
    padding: 11px 12px;
    border: 1px solid var(--border);
    border-radius: 9px;
    background: white;
}

.financial-mini-card > span {
    display: block;
    color: #756a62;
    font-size: 11px;
    font-weight: 600;
    line-height: 1.35;
}

.financial-mini-card > strong {
    display: block;
    width: 100%;
    margin-top: 8px;
    color: var(--dark);
    font-size: 14px;
    line-height: 1.15;
    font-weight: 700;
    text-align: right;
}

.financial-mini-card > small {
    display: block;
    width: 100%;
    margin-top: 6px;
    color: #80766e;
    font-size: 10px;
    font-weight: 400;
    line-height: 1.45;
    text-align: left;
}


/* =========================================================
   FORMAL TABLE
========================================================= */

.report-table-wrapper {
    width: 100%;
    overflow-x: auto;
    border: 1px solid var(--border);
    border-radius: 10px;
}

.formal-report-table {
    width: 100%;
    min-width: 620px;
    border-collapse: collapse;
}

.formal-report-table th {
    padding: 12px 12px;
    background: #fbf9f6;
    color: #625951;
    border-bottom: 1px solid var(--border);
    text-align: left;
    font-size: 12px;
    line-height: 1.3;
    font-weight: 600;
    letter-spacing: 0;
    text-transform: none;
    white-space: nowrap;
}

.formal-report-table td {
    padding: 12px 12px;
    color: #625951;
    border-bottom: 1px solid #f0ebe6;
    font-size: 13px;
    font-weight: 400;
    line-height: 1.4;
    vertical-align: middle;
}

.formal-report-table tbody tr:last-child td {
    border-bottom: 0;
}

.formal-report-table tbody tr:hover {
    background: #fdfaf7;
}

.text-right {
    text-align: right !important;
}

.amount-cell {
    color: var(--dark) !important;
    font-weight: 700 !important;
    white-space: nowrap;
}

.table-total-row td {
    background: #f8f3ee;
    color: var(--dark);
    font-size: 13px;
    font-weight: 700;
    border-top: 1px solid #ded1c6;
}

.table-empty {
    padding: 28px 15px !important;
    color: #80766e !important;
    text-align: center;
    font-size: 12px !important;
    font-weight: 400 !important;
}


/* =========================================================
   INVENTORY REPORT CARDS
========================================================= */

.inventory-report-grid {
    display: grid;
    grid-template-columns: repeat(4,minmax(0,1fr));
    gap: 10px;
}

.inventory-report-card {
    min-height: 86px;
    padding: 12px;
    border: 1px solid var(--border);
    border-radius: 9px;
    background: white;
}

.inventory-report-card > span {
    display: block;
    color: #756a62;
    font-size: 11px;
    font-weight: 600;
    line-height: 1.35;
}

.inventory-report-card > strong {
    display: block;
    width: 100%;
    margin-top: 10px;
    color: var(--dark);
    font-size: 17px;
    line-height: 1.15;
    font-weight: 700;
    text-align: right;
}

.inventory-report-card > small {
    display: block;
    width: 100%;
    margin-top: 7px;
    color: #80766e;
    font-size: 10px;
    font-weight: 400;
    line-height: 1.4;
    text-align: left;
}

.inventory-warning {
    background: #fffaf2;
}

.inventory-danger {
    background: #fff7f6;
}


/* =========================================================
   REMITTANCE
========================================================= */

.remittance-summary-grid {
    display: grid;
    grid-template-columns: repeat(4,minmax(0,1fr));
    gap: 10px;
    margin-bottom: 12px;
}

.remittance-card {
    min-height: 70px;
    padding: 12px;
    border: 1px solid var(--border);
    border-radius: 9px;
    background: #fcfaf7;
}

.remittance-card > span {
    display: block;
    color: #756a62;
    font-size: 11px;
    font-weight: 600;
    line-height: 1.35;
}

.remittance-card > strong {
    display: block;
    width: 100%;
    margin-top: 9px;
    color: var(--dark);
    font-size: 14px;
    line-height: 1.15;
    font-weight: 700;
    text-align: right;
    white-space: nowrap;
}

.report-status {
    display: inline-flex;
    align-items: center;
    min-height: 23px;
    padding: 0 8px;
    border-radius: 6px;
    background: var(--green-light);
    color: var(--green);
    font-size: 10px;
    font-weight: 600;
}

.variance-positive {
    color: var(--green) !important;
}

.variance-negative {
    color: var(--red) !important;
}

.variance-zero {
    color: #75685f !important;
}


/* =========================================================
   DOCUMENT FOOTER
========================================================= */

.report-document-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 13px 26px;
    border-top: 1px solid var(--border);
    background: #fbf9f6;
    color: #80766e;
    font-size: 10px;
    font-weight: 400;
}

.report-document-footer strong {
    margin-right: 6px;
    color: var(--dark);
    font-weight: 700;
}

.report-document-footer span {
    color: #80766e;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1200px) {

    .report-summary-grid,
    .financial-two-column,
    .inventory-report-grid,
    .remittance-summary-grid {
        grid-template-columns: repeat(2,minmax(0,1fr));
    }

}


@media (max-width: 950px) {

    .report-control-panel {
        align-items: flex-start;
        flex-direction: column;
    }

    .report-control-heading {
        width: 100%;
    }

    .report-control-form {
        width: 100%;
        justify-content: flex-start;
    }

    .report-actions {
        align-items: flex-start;
        flex-direction: column;
    }

    .report-actions-buttons {
        width: 100%;
        justify-content: flex-start;
        flex-wrap: wrap;
    }

    .report-document-header {
        flex-direction: column;
    }

    .report-period-box {
        width: 100%;
        box-sizing: border-box;
        text-align: left;
    }

}


@media (max-width: 700px) {

    .report-header {
        flex-direction: column;
    }

    .report-header-date {
        align-self: flex-start;
    }

    .report-control-form {
        flex-direction: column;
        align-items: stretch;
    }

    .report-date-field {
        width: 100%;
    }

    .report-control-button {
        width: 100%;
    }

    .report-actions-buttons {
        flex-direction: column;
        align-items: stretch;
    }

    .report-action {
        width: 100%;
    }

    .report-summary-grid,
    .financial-two-column,
    .inventory-report-grid,
    .remittance-summary-grid {
        grid-template-columns: 1fr;
    }

    .report-section {
        padding: 18px 15px;
    }

    .report-document-header {
        padding: 20px 15px;
    }

    .report-document-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 15px;
    }

    .financial-row {
        grid-template-columns: 1fr 120px;
    }

    .financial-row > span,
    .financial-row > strong {
        font-size: 12px;
    }

    .formal-report-table th {
        font-size: 11px;
    }

    .formal-report-table td {
        font-size: 12px;
    }

}


@media (max-width: 480px) {

    .report-header h1 {
        font-size: 23px;
    }

    .report-document-header h2 {
        font-size: 20px;
    }

}


/* =========================================================
   PRINT REPORT
   CENTER REPORT ON A4 PAPER
========================================================= */

@media print {

    @page {
        size: A4 portrait;
        margin: 10mm;
    }

    html,
    body {
        width: 100%;
        min-height: 100%;
        margin: 0 !important;
        padding: 0 !important;
        background: white !important;
    }

    body {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .sidebar,
    .report-header,
    .reports-breadcrumb,
    .report-control-panel,
    .report-actions {
        display: none !important;
    }

    .main-content {
        margin-left: 0 !important;
        width: 100% !important;
        max-width: none !important;
        padding: 0 !important;
    }

    .reports-page {
        width: 100% !important;
        max-width: 190mm !important;
        margin: 0 auto !important;
        padding: 0 !important;
    }

    .report-document {
        width: 100% !important;
        max-width: 190mm !important;
        margin: 0 auto !important;
        border: 0;
        border-radius: 0;
        box-shadow: none;
        overflow: visible;
    }

    .report-document-header {
        width: 100%;
        box-sizing: border-box;
        padding: 0 0 14px;
        border-bottom: 2px solid #241a14;
    }

    .report-section {
        width: 100%;
        box-sizing: border-box;
        padding: 14px 0;
        break-inside: avoid;
    }

    .report-summary-grid {
        grid-template-columns: repeat(4,minmax(0,1fr));
        gap: 7px;
    }

    .report-summary-card {
        min-height: 75px;
        padding: 8px;
        border-radius: 5px;
        box-shadow: none;
    }

    /* Title top-left */
    .report-summary-card-top {
        align-items: flex-start;
    }

    /* Value next line, right */
    .report-summary-card > strong {
        display: block;
        width: 100%;
        margin-top: 9px;
        font-size: 10px;
        line-height: 1.15;
        text-align: right;
    }

    /* Subtext below, left */
    .report-summary-card > small {
        display: block;
        width: 100%;
        margin-top: 5px;
        font-size: 7px;
        line-height: 1.35;
        text-align: left;
    }

    .report-summary-icon {
        width: 20px;
        height: 20px;
        font-size: 8px;
    }

    .financial-two-column,
    .inventory-report-grid,
    .remittance-summary-grid {
        gap: 7px;
    }

    .financial-mini-card,
    .inventory-report-card,
    .remittance-card {
        padding: 8px;
    }

    .financial-mini-card > strong,
    .inventory-report-card > strong,
    .remittance-card > strong {
        width: 100%;
        font-size: 10px;
        line-height: 1.15;
        text-align: right;
    }

    .financial-mini-card > small,
    .inventory-report-card > small {
        width: 100%;
        font-size: 7px;
        line-height: 1.35;
        text-align: left;
    }

    .report-table-wrapper {
        width: 100%;
        border-radius: 5px;
        overflow: visible;
    }

    .formal-report-table {
        width: 100%;
        min-width: 0;
    }

    .formal-report-table th {
        padding: 6px 7px;
        font-size: 8px;
    }

    .formal-report-table td {
        padding: 6px 7px;
        font-size: 9px;
    }

    .financial-row {
        min-height: 31px;
        grid-template-columns: 1fr 120px;
        padding: 0 9px;
    }

    .financial-row > span,
    .financial-row > strong {
        font-size: 9px;
    }

    .financial-header-row > span {
        font-size: 8px;
    }

    .report-document-footer {
        padding: 8px 0 0;
        background: white;
    }

}

</style>

@endpush

@endsection