@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

@php
    $userName = $user->name ?? 'Administrator';

    $salesPurchaseTrendJson = json_encode($salesPurchaseTrend ?? []);
    $expenseBreakdownJson = json_encode($expenseBreakdown ?? []);
    $purchaseStatusesJson = json_encode($purchaseStatuses ?? []);
@endphp


<style>

/* ============================================================
   BITE SYNC DASHBOARD
   ============================================================ */

.dashboard-page {
    width: 100%;
    max-width: 1500px;
    margin: 0 auto;
    padding-bottom: 30px;
}


/* ============================================================
   HEADER
   ============================================================ */

.dashboard-page .topbar {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 25px;
    margin-bottom: 17px;
}

.dashboard-page .page-title small {
    display: block;
    margin-bottom: 5px;
    color: #a9825b;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
}

.dashboard-page .page-title h1 {
    margin: 0;
    color: #241a14;
    font-size: 29px;
    font-weight: 700;
    line-height: 1.15;
    letter-spacing: -.045rem;
}

.dashboard-page .page-title p {
    margin: 6px 0 0;
    color: #8b8179;
    font-size: 12px;
    line-height: 1.5;
    font-weight: 400;
}

.dashboard-page .date-box {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    min-width: 145px;
    padding: 9px 12px;
    border: 1px solid #e4dcd4;
    border-radius: 10px;
    background: white;
    color: #8b8179;
    font-size: 10px;
    font-weight: 600;
    white-space: nowrap;
    box-shadow: 0 3px 12px rgba(43,31,23,.025);
}

.dashboard-page .date-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    flex-shrink: 0;
    border-radius: 8px;
    background: #f4e4d4;
    color: #c47a3a;
    font-size: 17px;
}


/* ============================================================
   KPI CARDS
   ============================================================ */

.dashboard-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 17px;
    margin-bottom: 17px;
}

.dashboard-kpi {
    min-height: 125px;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 15px;

    padding: 16px 17px 15px;

    border: 1px solid #e4dcd4;
    border-radius: 15px;
    background: white;

    box-shadow: 0 5px 18px rgba(43,31,23,.045);

    position: relative;
    overflow: hidden;
}

.dashboard-kpi::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 3px;

    background: linear-gradient(
        180deg,
        #c47a3a,
        #e2a16c
    );
}


/* ============================================================
   KPI LEFT SIDE
   ============================================================ */

.dashboard-kpi-left {
    min-width: 0;
    flex: 1;

    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: flex-start;
    align-self: stretch;

    padding-left: 1px;
    padding-top: 2px;
}

.dashboard-kpi-label {
    color: #8b8179;
    font-size: .6875rem;
    line-height: 1.3;
    font-weight: 800;
    letter-spacing: .045rem;
    white-space: nowrap;
    text-transform: uppercase;
}

.dashboard-kpi-note {
    margin-top: auto;
    padding-top: 10px;

    color: #9d958f;
    font-size: .6875rem;
    line-height: 1.4;

    max-width: 155px;
}


/* ============================================================
   KPI RIGHT SIDE
   ============================================================ */

.dashboard-kpi-right {
    min-width: 88px;

    display: flex;
    flex-direction: column;
    align-items: flex-end;
    justify-content: flex-start;

    padding-top: 3px;

    flex-shrink: 0;
}


/* ============================================================
   KPI ICON
   ============================================================ */

.dashboard-kpi-icon {
    width: 34px;
    height: 34px;

    display: flex;
    align-items: center;
    justify-content: center;

    flex-shrink: 0;

    border-radius: 9px;

    background: linear-gradient(
        135deg,
        #fbf1e7,
        #f4e3d4
    );

    color: #c47a3a;

    font-size: .72rem;
    font-weight: 800;
}

.dashboard-kpi-icon.green {
    background: #edf6ef;
    color: #5d8b67;
}

.dashboard-kpi-icon.blue {
    background: #edf2f7;
    color: #637f9f;
}

.dashboard-kpi-icon.red {
    background: #fbeeed;
    color: #b95d56;
}


/* ============================================================
   KPI VALUE
   ============================================================ */

.dashboard-kpi-value {
    margin-top: 13px;

    color: #241a14;

    font-size: clamp(
        1.45rem,
        1.8vw,
        1.75rem
    );

    line-height: 1;

    font-weight: 800;

    text-align: right;

    white-space: nowrap;
}

.dashboard-kpi-value.money {
    font-size: clamp(
        1rem,
        1.3vw,
        1.25rem
    );

    letter-spacing: -.02rem;
}


/* ============================================================
   MAIN ANALYTICS
   ============================================================ */

.dashboard-main-grid {
    display: grid;

    grid-template-columns:
        minmax(0, 1.7fr)
        minmax(310px, .85fr);

    gap: 17px;

    margin-bottom: 17px;
}


/* ============================================================
   PANELS
   ============================================================ */

.dashboard-panel {
    background: white;

    border: 1px solid #e4dcd4;
    border-radius: 15px;

    overflow: hidden;

    box-shadow:
        0 4px 16px rgba(43,31,23,.035);
}

.dashboard-panel-header {
    min-height: 65px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;

    padding: 14px 18px;

    border-bottom: 1px solid #e4dcd4;
}

.dashboard-panel-heading h2 {
    margin: 0;

    color: #241a14;

    font-size: .875rem;
    line-height: 1.3;

    font-weight: 700;
}

.dashboard-panel-heading p {
    margin: 3px 0 0;

    color: #8b8179;

    font-size: .6875rem;
    line-height: 1.4;

    font-weight: 400;
}

.dashboard-panel-body {
    padding: 18px;
}


/* ============================================================
   CHARTS
   ============================================================ */

.dashboard-chart {
    width: 100%;
    min-height: 310px;
    position: relative;
}

.dashboard-chart-small {
    min-height: 300px;
}


/* ============================================================
   FINANCIAL SUMMARY
   ============================================================ */

.financial-summary {
    display: grid;
    gap: 13px;
}

.financial-row {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;

    padding-bottom: 12px;

    border-bottom: 1px solid #f0ebe6;
}

.financial-row:last-child {
    padding-bottom: 0;
    border-bottom: 0;
}

.financial-label {
    color: #8b8179;
    font-size: .72rem;
}

.financial-value {
    color: #241a14;
    font-size: .82rem;
    font-weight: 800;

    text-align: right;
}

.financial-value.positive {
    color: #5d8b67;
}

.financial-value.negative {
    color: #b95d56;
}

.financial-divider {
    height: 1px;
    background: #e4dcd4;

    margin: 3px 0;
}

.financial-highlight {
    padding: 14px;

    border-radius: 11px;

    background: #fcfaf7;

    border: 1px solid #eee6de;
}

.financial-highlight-label {
    color: #8b8179;

    font-size: .65rem;

    font-weight: 800;

    letter-spacing: .04rem;

    text-transform: uppercase;
}

.financial-highlight-value {
    margin-top: 6px;

    color: #241a14;

    font-size: 1.35rem;

    font-weight: 800;
}


/* ============================================================
   OPERATIONAL GRID
   ============================================================ */

.dashboard-operational-grid {
    display: grid;

    grid-template-columns:
        minmax(0, 1fr)
        minmax(0, 1fr);

    gap: 17px;

    margin-bottom: 17px;
}


/* ============================================================
   INVENTORY HEALTH
   ============================================================ */

.stock-summary {
    display: grid;

    grid-template-columns:
        repeat(3, minmax(0, 1fr));

    gap: 10px;
}

.stock-card {
    min-height: 90px;

    padding: 13px;

    border: 1px solid #e8e0d8;

    border-radius: 11px;

    background: #fcfaf7;

    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.stock-card-info {
    min-width: 0;
    flex: 1;
}

.stock-card-label {
    color: #8b8179;

    font-size: .625rem;

    font-weight: 800;

    text-transform: uppercase;

    letter-spacing: .035rem;
}

.stock-card-value {
    color: #241a14;

    font-size: 1.3rem;

    font-weight: 800;

    white-space: nowrap;

    text-align: right;

    flex-shrink: 0;
}

.stock-card-note {
    margin-top: 3px;

    color: #9d958f;

    font-size: .625rem;
}

.stock-card.normal {
    border-left: 3px solid #5d8b67;
}

.stock-card.low {
    border-left: 3px solid #b9823e;
}

.stock-card.out {
    border-left: 3px solid #b95d56;
}


/* ============================================================
   ACTIVITY
   ============================================================ */

.dashboard-activity-grid {
    display: grid;

    grid-template-columns:
        minmax(0, 1.35fr)
        minmax(0, 1fr);

    gap: 17px;
}


/* ============================================================
   TABLE
   ============================================================ */

.dashboard-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.dashboard-table {
    width: 100%;

    min-width: 560px;

    border-collapse: collapse;
}

.dashboard-table th {
    padding: 10px 12px;

    background: #fbf9f6;

    color: #8b8179;

    border-bottom: 1px solid #e4dcd4;

    text-align: left;

    font-size: .625rem;
    line-height: 1.3;

    font-weight: 800;

    letter-spacing: .04rem;

    text-transform: uppercase;

    white-space: nowrap;
}

.dashboard-table td {
    padding: 11px 12px;

    color: #625951;

    border-bottom: 1px solid #f0ebe6;

    font-size: .72rem;

    line-height: 1.4;

    vertical-align: middle;
}

.dashboard-table tbody tr:last-child td {
    border-bottom: 0;
}

.dashboard-number {
    color: #a85f28;
    font-weight: 700;
}

.dashboard-amount {
    color: #241a14;

    font-weight: 800;

    white-space: nowrap;
}


/* ============================================================
   STATUS
   ============================================================ */

.dashboard-status {
    display: inline-flex;

    align-items: center;

    min-height: 23px;

    padding: 0 7px;

    border-radius: 7px;

    font-size: .6rem;

    line-height: 1.2;

    font-weight: 800;

    white-space: nowrap;
}

.dashboard-status.completed,
.dashboard-status.received {
    background: #edf6ef;
    color: #5d8b67;
}

.dashboard-status.pending,
.dashboard-status.ordered,
.dashboard-status.partially {
    background: #fbf1e3;
    color: #b9823e;
}

.dashboard-status.draft {
    background: #edf2f7;
    color: #637f9f;
}

.dashboard-status.cancelled,
.dashboard-status.rejected {
    background: #fbeeed;
    color: #b95d56;
}

.dashboard-status.approved {
    background: #f4e4d4;
    color: #a85f28;
}


/* ============================================================
   EMPTY STATE
   ============================================================ */

.dashboard-empty {
    padding: 42px 18px;

    text-align: center;
}

.dashboard-empty-icon {
    width: 42px;
    height: 42px;

    display: flex;

    align-items: center;
    justify-content: center;

    margin: 0 auto 10px;

    border-radius: 11px;

    background: #f4e4d4;

    color: #c47a3a;

    font-size: 15px;
}

.dashboard-empty-title {
    color: #625951;

    font-size: .8rem;

    font-weight: 700;
}

.dashboard-empty-text {
    margin-top: 4px;

    color: #9d958f;

    font-size: .68rem;
}


/* ============================================================
   RESPONSIVE
   ============================================================ */

@media (max-width: 1200px) {

    .dashboard-kpis {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

    .dashboard-main-grid {
        grid-template-columns: 1fr;
    }

}


@media (max-width: 850px) {

    .dashboard-operational-grid,
    .dashboard-activity-grid {
        grid-template-columns: 1fr;
    }

}


@media (max-width: 700px) {

    .dashboard-page .topbar {
        flex-direction: column;
        gap: 12px;
    }

    .dashboard-page .date-box {
        align-self: flex-start;
    }

    .dashboard-kpis {
        grid-template-columns: 1fr;
    }

    .dashboard-kpi {
        min-height: 115px;
    }

    .dashboard-kpi-right {
        min-width: 80px;
    }

    .dashboard-kpi-value {
        font-size: 1.45rem;
    }

    .dashboard-kpi-value.money {
        font-size: 1.05rem;
    }

    .stock-summary {
        grid-template-columns: 1fr;
    }

    .dashboard-panel-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .dashboard-panel-body {
        padding: 15px;
    }

}


@media (max-width: 480px) {

    .dashboard-page .page-title h1 {
        font-size: 23px;
    }

    .dashboard-kpi {
        min-height: 115px;
    }

    .dashboard-kpi-note {
        max-width: 145px;
    }

    .stock-card {
        min-height: 82px;
    }

}

</style>


<div class="dashboard-page">


    {{-- ============================================================
         HEADER
         ============================================================ --}}

    <div class="topbar">

        <div class="page-title">

            <small>
                Business Overview
            </small>

            <h1>
                Dashboard
            </h1>

            <p>
                Monitor BiteSync sales, purchasing, expenses, and inventory performance.
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
         KPI CARDS
         ============================================================ --}}

    <div class="dashboard-kpis">


        {{-- ========================================================
             TOTAL SALES
             ======================================================== --}}

        <div class="dashboard-kpi">

            <div class="dashboard-kpi-left">

                <div class="dashboard-kpi-label">
                    Total Sales
                </div>

                <div class="dashboard-kpi-note">
                    {{ number_format($salesCount) }}
                    completed transactions
                </div>

            </div>


            <div class="dashboard-kpi-right">

                <div class="dashboard-kpi-icon">
                    ₱
                </div>

                <div class="dashboard-kpi-value money">
                    ₱{{ number_format($totalSales, 2) }}
                </div>

            </div>

        </div>



        {{-- ========================================================
             TOTAL PURCHASES
             ======================================================== --}}

        <div class="dashboard-kpi">

            <div class="dashboard-kpi-left">

                <div class="dashboard-kpi-label">
                    Purchases
                </div>

                <div class="dashboard-kpi-note">
                    {{ number_format($purchaseCount) }}
                    non-cancelled purchases
                </div>

            </div>


            <div class="dashboard-kpi-right">

                <div class="dashboard-kpi-icon blue">
                    PO
                </div>

                <div class="dashboard-kpi-value money">
                    ₱{{ number_format($totalPurchases, 2) }}
                </div>

            </div>

        </div>



        {{-- ========================================================
             EXPENSES
             ======================================================== --}}

        <div class="dashboard-kpi">

            <div class="dashboard-kpi-left">

                <div class="dashboard-kpi-label">
                    Expenses
                </div>

                <div class="dashboard-kpi-note">
                    {{ number_format($expenseCount) }}
                    recorded expenses
                </div>

            </div>


            <div class="dashboard-kpi-right">

                <div class="dashboard-kpi-icon red">
                    −
                </div>

                <div class="dashboard-kpi-value money">
                    ₱{{ number_format($totalExpenses, 2) }}
                </div>

            </div>

        </div>



        {{-- ========================================================
             INVENTORY VALUE
             ======================================================== --}}

        <div class="dashboard-kpi">

            <div class="dashboard-kpi-left">

                <div class="dashboard-kpi-label">
                    Inventory Value
                </div>

                <div class="dashboard-kpi-note">
                    {{ number_format($inventoryCount) }}
                    inventory items
                </div>

            </div>


            <div class="dashboard-kpi-right">

                <div class="dashboard-kpi-icon green">
                    BOX
                </div>

                <div class="dashboard-kpi-value money">
                    ₱{{ number_format($inventoryValue, 2) }}
                </div>

            </div>

        </div>

    </div>



    {{-- ============================================================
         MAIN ANALYTICS
         ============================================================ --}}

    <div class="dashboard-main-grid">


        {{-- ========================================================
             SALES VS PURCHASES
             ======================================================== --}}

        <div class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div class="dashboard-panel-heading">

                    <h2>
                        Sales vs Purchases
                    </h2>

                    <p>
                        Daily activity for the last 30 days.
                    </p>

                </div>

            </div>


            <div class="dashboard-panel-body">

                <div class="dashboard-chart">

                    <canvas id="salesPurchaseChart"></canvas>

                </div>

            </div>

        </div>



        {{-- ========================================================
             FINANCIAL SUMMARY
             ======================================================== --}}

        <div class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div class="dashboard-panel-heading">

                    <h2>
                        Financial Summary
                    </h2>

                    <p>
                        Current overall business position.
                    </p>

                </div>

            </div>


            <div class="dashboard-panel-body">

                <div class="financial-summary">


                    <div class="financial-row">

                        <span class="financial-label">
                            Total Sales
                        </span>

                        <span class="financial-value positive">
                            ₱{{ number_format($totalSales, 2) }}
                        </span>

                    </div>


                    <div class="financial-row">

                        <span class="financial-label">
                            Total Purchases
                        </span>

                        <span class="financial-value">
                            ₱{{ number_format($totalPurchases, 2) }}
                        </span>

                    </div>


                    <div class="financial-row">

                        <span class="financial-label">
                            Total Expenses
                        </span>

                        <span class="financial-value">
                            ₱{{ number_format($totalExpenses, 2) }}
                        </span>

                    </div>


                    <div class="financial-divider"></div>


                    <div class="financial-highlight">

                        <div class="financial-highlight-label">
                            Net Position
                        </div>

                        <div class="financial-highlight-value
                            {{ $netPosition >= 0 ? 'positive' : 'negative' }}">

                            ₱{{ number_format($netPosition, 2) }}

                        </div>

                    </div>


                    <div class="financial-row">

                        <span class="financial-label">
                            This Month Sales
                        </span>

                        <span class="financial-value">
                            ₱{{ number_format($monthlySales, 2) }}
                        </span>

                    </div>


                    <div class="financial-row">

                        <span class="financial-label">
                            This Month Purchases
                        </span>

                        <span class="financial-value">
                            ₱{{ number_format($monthlyPurchases, 2) }}
                        </span>

                    </div>


                    <div class="financial-row">

                        <span class="financial-label">
                            This Month Expenses
                        </span>

                        <span class="financial-value">
                            ₱{{ number_format($monthlyExpenses, 2) }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- ============================================================
         GRAPH ROW
         ============================================================ --}}

    <div class="dashboard-operational-grid">


        {{-- ========================================================
             EXPENSE BREAKDOWN
             ======================================================== --}}

        <div class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div class="dashboard-panel-heading">

                    <h2>
                        Expense Breakdown
                    </h2>

                    <p>
                        Recorded operating expenses by category.
                    </p>

                </div>

            </div>


            <div class="dashboard-panel-body">

                <div class="dashboard-chart dashboard-chart-small">

                    <canvas id="expenseChart"></canvas>

                </div>

            </div>

        </div>



        {{-- ========================================================
             PURCHASE STATUS
             ======================================================== --}}

        <div class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div class="dashboard-panel-heading">

                    <h2>
                        Purchase Status
                    </h2>

                    <p>
                        Current purchasing workflow.
                    </p>

                </div>

            </div>


            <div class="dashboard-panel-body">

                <div class="dashboard-chart dashboard-chart-small">

                    <canvas id="purchaseStatusChart"></canvas>

                </div>

            </div>

        </div>

    </div>



    {{-- ============================================================
         INVENTORY HEALTH
         ============================================================ --}}

    <div
        class="dashboard-panel"
        style="margin-bottom:17px;"
    >

        <div class="dashboard-panel-header">

            <div class="dashboard-panel-heading">

                <h2>
                    Inventory Health
                </h2>

                <p>
                    Current stock condition across inventory items.
                </p>

            </div>

        </div>


        <div class="dashboard-panel-body">

            <div class="stock-summary">


                <div class="stock-card normal">

                    <div class="stock-card-info">

                        <div class="stock-card-label">
                            Normal Stock
                        </div>

                        <div class="stock-card-note">
                            Items above minimum level
                        </div>

                    </div>


                    <div class="stock-card-value">
                        {{ number_format($normalStockCount) }}
                    </div>

                </div>


                <div class="stock-card low">

                    <div class="stock-card-info">

                        <div class="stock-card-label">
                            Low Stock
                        </div>

                        <div class="stock-card-note">
                            Items needing attention
                        </div>

                    </div>


                    <div class="stock-card-value">
                        {{ number_format($lowStockCount) }}
                    </div>

                </div>


                <div class="stock-card out">

                    <div class="stock-card-info">

                        <div class="stock-card-label">
                            Out of Stock
                        </div>

                        <div class="stock-card-note">
                            Items with zero quantity
                        </div>

                    </div>


                    <div class="stock-card-value">
                        {{ number_format($outOfStockCount) }}
                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- ============================================================
         RECENT ACTIVITY
         ============================================================ --}}

    <div class="dashboard-activity-grid">


        {{-- ========================================================
             RECENT SALES
             ======================================================== --}}

        <div class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div class="dashboard-panel-heading">

                    <h2>
                        Recent Sales
                    </h2>

                    <p>
                        Latest completed customer transactions.
                    </p>

                </div>

            </div>


            @if ($recentSales->count())

                <div class="dashboard-table-wrapper">

                    <table class="dashboard-table">

                        <thead>

                            <tr>

                                <th>
                                    Sale
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Payment
                                </th>

                                <th>
                                    Total
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($recentSales as $sale)

                                <tr>

                                    <td>

                                        <span class="dashboard-number">
                                            {{ $sale->sale_number }}
                                        </span>

                                    </td>


                                    <td>

                                        {{ $sale->sale_date
                                            ? $sale->sale_date->format('M d, Y')
                                            : '—'
                                        }}

                                    </td>


                                    <td>

                                        {{ $sale->payment_method ?? '—' }}

                                    </td>


                                    <td>

                                        <span class="dashboard-amount">
                                            ₱{{ number_format((float) $sale->total, 2) }}
                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="dashboard-empty">

                    <div class="dashboard-empty-icon">
                        ₱
                    </div>

                    <div class="dashboard-empty-title">
                        No sales recorded yet
                    </div>

                    <div class="dashboard-empty-text">
                        Completed sales will appear here.
                    </div>

                </div>

            @endif

        </div>



        {{-- ========================================================
             RECENT EXPENSES
             ======================================================== --}}

        <div class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div class="dashboard-panel-heading">

                    <h2>
                        Recent Expenses
                    </h2>

                    <p>
                        Latest recorded operating expenses.
                    </p>

                </div>

            </div>


            @if ($recentExpenses->count())

                <div class="dashboard-table-wrapper">

                    <table class="dashboard-table">

                        <thead>

                            <tr>

                                <th>
                                    Category
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Amount
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($recentExpenses as $expense)

                                <tr>

                                    <td>
                                        {{ $expense->category }}
                                    </td>


                                    <td>

                                        {{ $expense->expense_date
                                            ? $expense->expense_date->format('M d, Y')
                                            : '—'
                                        }}

                                    </td>


                                    <td>

                                        <span class="dashboard-amount">
                                            ₱{{ number_format((float) $expense->amount, 2) }}
                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="dashboard-empty">

                    <div class="dashboard-empty-icon">
                        −
                    </div>

                    <div class="dashboard-empty-title">
                        No expenses recorded yet
                    </div>

                    <div class="dashboard-empty-text">
                        Recorded expenses will appear here.
                    </div>

                </div>

            @endif

        </div>

    </div>

</div>



{{-- ================================================================
     CHART.JS
     ================================================================ --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /* ==========================================================
           REAL DATABASE DATA
           ========================================================== */

        const salesPurchaseTrend =
            {!! $salesPurchaseTrendJson !!};

        const expenseBreakdown =
            {!! $expenseBreakdownJson !!};

        const purchaseStatuses =
            {!! $purchaseStatusesJson !!};



        /* ==========================================================
           CHART DEFAULTS
           ========================================================== */

        Chart.defaults.font.family =
            "'Segoe UI', Arial, sans-serif";

        Chart.defaults.font.size = 10;

        Chart.defaults.color =
            '#8b8179';



        /* ==========================================================
           SALES VS PURCHASES
           ========================================================== */

        const salesPurchaseCanvas =
            document.getElementById(
                'salesPurchaseChart'
            );


        if (salesPurchaseCanvas) {

            new Chart(
                salesPurchaseCanvas,
                {

                    type: 'line',

                    data: {

                        labels:
                            salesPurchaseTrend.map(
                                item => item.date
                            ),

                        datasets: [

                            {
                                label: 'Sales',

                                data:
                                    salesPurchaseTrend.map(
                                        item => item.sales
                                    ),

                                borderColor:
                                    '#c47a3a',

                                backgroundColor:
                                    'rgba(196,122,58,.10)',

                                borderWidth: 2,

                                fill: true,

                                tension: .35,

                                pointRadius: 2,

                                pointHoverRadius: 4
                            },


                            {
                                label: 'Purchases',

                                data:
                                    salesPurchaseTrend.map(
                                        item => item.purchases
                                    ),

                                borderColor:
                                    '#637f9f',

                                backgroundColor:
                                    'rgba(99,127,159,.06)',

                                borderWidth: 2,

                                fill: true,

                                tension: .35,

                                pointRadius: 2,

                                pointHoverRadius: 4
                            }

                        ]

                    },


                    options: {

                        responsive: true,

                        maintainAspectRatio: false,

                        interaction: {
                            mode: 'index',
                            intersect: false
                        },

                        plugins: {

                            legend: {

                                position: 'top',

                                align: 'end',

                                labels: {

                                    usePointStyle: true,

                                    boxWidth: 7,

                                    padding: 15,

                                    font: {

                                        size: 10,

                                        weight: '600'
                                    }

                                }

                            },

                            tooltip: {

                                callbacks: {

                                    label:
                                        function(context) {

                                            return (
                                                ' ' +
                                                context.dataset.label +
                                                ': ₱' +
                                                Number(
                                                    context.parsed.y
                                                ).toLocaleString(
                                                    'en-PH',
                                                    {
                                                        minimumFractionDigits: 2
                                                    }
                                                )
                                            );

                                        }

                                }

                            }

                        },


                        scales: {

                            x: {

                                grid: {
                                    display: false
                                },

                                ticks: {

                                    maxTicksLimit: 8,

                                    font: {
                                        size: 9
                                    }

                                }

                            },


                            y: {

                                beginAtZero: true,

                                grid: {

                                    color:
                                        '#eee8e2'
                                },

                                ticks: {

                                    font: {
                                        size: 9
                                    },

                                    callback:
                                        function(value) {

                                            return '₱' +
                                                Number(value)
                                                    .toLocaleString(
                                                        'en-PH',
                                                        {
                                                            notation:
                                                                'compact'
                                                        }
                                                    );

                                        }

                                }

                            }

                        }

                    }

                }
            );

        }



        /* ==========================================================
           EXPENSE BREAKDOWN
           ========================================================== */

        const expenseCanvas =
            document.getElementById(
                'expenseChart'
            );


        if (expenseCanvas) {

            new Chart(
                expenseCanvas,
                {

                    type: 'doughnut',

                    data: {

                        labels:
                            expenseBreakdown.map(
                                item => item.category
                            ),

                        datasets: [

                            {

                                data:
                                    expenseBreakdown.map(
                                        item => item.total
                                    ),

                                backgroundColor: [

                                    '#c47a3a',
                                    '#76563d',
                                    '#637f9f',
                                    '#5d8b67',
                                    '#b9823e',
                                    '#a85f28',
                                    '#b95d56',
                                    '#8b8179',
                                    '#d6a15c',
                                    '#9c7b5b'

                                ],

                                borderWidth: 2,

                                borderColor: '#ffffff'

                            }

                        ]

                    },


                    options: {

                        responsive: true,

                        maintainAspectRatio: false,

                        cutout: '62%',

                        plugins: {

                            legend: {

                                position: 'right',

                                labels: {

                                    usePointStyle: true,

                                    pointStyle: 'circle',

                                    padding: 10,

                                    boxWidth: 7,

                                    font: {
                                        size: 9
                                    }

                                }

                            },

                            tooltip: {

                                callbacks: {

                                    label:
                                        function(context) {

                                            return (
                                                ' ₱' +
                                                Number(
                                                    context.parsed
                                                ).toLocaleString(
                                                    'en-PH',
                                                    {
                                                        minimumFractionDigits: 2
                                                    }
                                                )
                                            );

                                        }

                                }

                            }

                        }

                    }

                }
            );

        }



        /* ==========================================================
           PURCHASE STATUS
           ========================================================== */

        const purchaseStatusCanvas =
            document.getElementById(
                'purchaseStatusChart'
            );


        if (purchaseStatusCanvas) {

            new Chart(
                purchaseStatusCanvas,
                {

                    type: 'bar',

                    data: {

                        labels:
                            purchaseStatuses.map(
                                item => item.status
                            ),

                        datasets: [

                            {

                                label: 'Purchases',

                                data:
                                    purchaseStatuses.map(
                                        item => item.count
                                    ),

                                backgroundColor:
                                    '#c47a3a',

                                borderRadius: 6,

                                borderSkipped: false,

                                maxBarThickness: 28

                            }

                        ]

                    },


                    options: {

                        indexAxis: 'y',

                        responsive: true,

                        maintainAspectRatio: false,

                        plugins: {

                            legend: {
                                display: false
                            }

                        },


                        scales: {

                            x: {

                                beginAtZero: true,

                                ticks: {

                                    precision: 0,

                                    font: {
                                        size: 9
                                    }

                                },

                                grid: {

                                    color:
                                        '#eee8e2'
                                }

                            },


                            y: {

                                grid: {
                                    display: false
                                },

                                ticks: {

                                    font: {
                                        size: 9
                                    }

                                }

                            }

                        }

                    }

                }
            );

        }

    }

);

</script>

@endsection