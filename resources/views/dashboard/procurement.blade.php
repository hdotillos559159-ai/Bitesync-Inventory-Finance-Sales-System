@extends('layouts.app')

@section('title', 'Procurement Dashboard')

@section('content')

@php

    $purchaseActivityJson =
        json_encode($purchaseActivity ?? []);

    $purchaseStatusesJson =
        json_encode($purchaseStatuses ?? []);

    $inventoryHealthJson =
        json_encode($inventoryHealth ?? [
            [
                'status' => 'Normal',
                'count' => $normalStockCount ?? 0,
            ],
            [
                'status' => 'Low Stock',
                'count' => $lowStockCount ?? 0,
            ],
            [
                'status' => 'Out of Stock',
                'count' => $outOfStockCount ?? 0,
            ],
        ]);

@endphp


<style>

/* ============================================================
   PROCUREMENT DASHBOARD
   SAME VISUAL SYSTEM AS ADMIN DASHBOARD
   PROCUREMENT DATA ONLY
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
   PROCUREMENT NOTICE
   ============================================================ */

.procurement-notice {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 17px;
    padding: 11px 13px;
    border: 1px solid #eadfd4;
    border-radius: 11px;
    background: #fcfaf7;
    color: #756b63;
    font-size: .68rem;
    line-height: 1.45;
}


.procurement-notice-icon {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 8px;
    background: #f4e4d4;
    color: #c47a3a;
    font-size: .72rem;
    font-weight: 800;
}


.procurement-notice strong {
    color: #4f443b;
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
    background: linear-gradient(180deg, #c47a3a, #e2a16c);
}


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
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}


.dashboard-kpi-right {
    min-width: 88px;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    justify-content: flex-start;
    padding-top: 3px;
    flex-shrink: 0;
}


.dashboard-kpi-icon {
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 9px;
    background: linear-gradient(135deg, #fbf1e7, #f4e3d4);
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


.dashboard-kpi-value {
    margin-top: 13px;
    color: #241a14;
    font-size: clamp(1.45rem, 1.8vw, 1.75rem);
    line-height: 1;
    font-weight: 800;
    text-align: right;
    white-space: nowrap;
}


.dashboard-kpi-value.money {
    font-size: clamp(1rem, 1.3vw, 1.25rem);
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
   OPERATIONAL GRID
   ============================================================ */

.dashboard-operational-grid {
    display: grid;
    grid-template-columns:
        minmax(0, 1.35fr)
        minmax(0, 1fr);
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
    box-shadow: 0 4px 16px rgba(43,31,23,.035);
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
   INVENTORY HEALTH CIRCLE GRAPH
   ============================================================ */

.inventory-health-chart {
    width: 100%;
    height: 300px;
    position: relative;
}


.inventory-health-chart canvas {
    width: 100% !important;
    height: 100% !important;
}


/* ============================================================
   PROCUREMENT SUMMARY
   ============================================================ */

.procurement-summary {
    display: grid;
    gap: 13px;
}


.procurement-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f0ebe6;
}


.procurement-row:last-child {
    padding-bottom: 0;
    border-bottom: 0;
}


.procurement-label {
    color: #8b8179;
    font-size: .72rem;
}


.procurement-value {
    color: #241a14;
    font-size: .82rem;
    font-weight: 800;
    text-align: right;
}


.procurement-value.orange {
    color: #a85f28;
}


.procurement-value.green {
    color: #5d8b67;
}


.procurement-value.red {
    color: #b95d56;
}


.procurement-value.blue {
    color: #637f9f;
}


.procurement-divider {
    height: 1px;
    background: #e4dcd4;
    margin: 3px 0;
}


.procurement-highlight {
    padding: 14px;
    border-radius: 11px;
    background: #fcfaf7;
    border: 1px solid #eee6de;
}


.procurement-highlight-label {
    color: #8b8179;
    font-size: .65rem;
    font-weight: 800;
    letter-spacing: .04rem;
    text-transform: uppercase;
}


.procurement-highlight-value {
    margin-top: 6px;
    color: #241a14;
    font-size: 1.35rem;
    font-weight: 800;
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
    margin-bottom: 17px;
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
        grid-template-columns: repeat(2, minmax(0, 1fr));
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

    .dashboard-panel-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .dashboard-panel-body {
        padding: 15px;
    }

    .procurement-notice {
        align-items: flex-start;
    }

    .inventory-health-chart {
        height: 270px;
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

    .inventory-health-chart {
        height: 250px;
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
                Procurement Overview
            </small>

            <h1>
                Dashboard
            </h1>

            <p>
                Monitor purchasing activity, supplier orders, receiving, and inventory stock.
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
         PROCUREMENT NOTICE
         ============================================================ --}}

    <div class="procurement-notice">

        <div class="procurement-notice-icon">
            PO
        </div>

        <div>

            <strong>
                Procurement Workspace
            </strong>

            &nbsp; Manage supplier purchases, receiving, suppliers, and
            controlled inventory stock operations from your assigned modules.

        </div>

    </div>



    {{-- ============================================================
         KPI CARDS
         ============================================================ --}}

    <div class="dashboard-kpis">


        {{-- Purchase Orders --}}

        <div class="dashboard-kpi">

            <div class="dashboard-kpi-left">

                <div class="dashboard-kpi-label">
                    Purchase Orders
                </div>

                <div class="dashboard-kpi-note">
                    Active purchase records
                </div>

            </div>


            <div class="dashboard-kpi-right">

                <div class="dashboard-kpi-icon blue">
                    PO
                </div>

                <div class="dashboard-kpi-value">
                    {{ number_format($purchaseCount ?? 0) }}
                </div>

            </div>

        </div>



        {{-- Awaiting Approval --}}

        <div class="dashboard-kpi">

            <div class="dashboard-kpi-left">

                <div class="dashboard-kpi-label">
                    Awaiting Approval
                </div>

                <div class="dashboard-kpi-note">
                    Pending approval
                </div>

            </div>


            <div class="dashboard-kpi-right">

                <div class="dashboard-kpi-icon">
                    !
                </div>

                <div class="dashboard-kpi-value">
                    {{ number_format($pendingApprovalCount ?? 0) }}
                </div>

            </div>

        </div>



        {{-- Orders To Receive --}}

        <div class="dashboard-kpi">

            <div class="dashboard-kpi-left">

                <div class="dashboard-kpi-label">
                    Orders to Receive
                </div>

                <div class="dashboard-kpi-note">
                    Awaiting receiving
                </div>

            </div>


            <div class="dashboard-kpi-right">

                <div class="dashboard-kpi-icon green">
                    ↓
                </div>

                <div class="dashboard-kpi-value">
                    {{ number_format($toReceiveCount ?? 0) }}
                </div>

            </div>

        </div>



        {{-- Stock Alerts --}}

        <div class="dashboard-kpi">

            <div class="dashboard-kpi-left">

                <div class="dashboard-kpi-label">
                    Stock Alerts
                </div>

                <div class="dashboard-kpi-note">
                    Low or out of stock
                </div>

            </div>


            <div class="dashboard-kpi-right">

                <div class="dashboard-kpi-icon red">
                    !
                </div>

                <div class="dashboard-kpi-value">
                    {{ number_format($stockAlertCount ?? 0) }}
                </div>

            </div>

        </div>

    </div>



    {{-- ============================================================
         MAIN ANALYTICS
         ============================================================ --}}

    <div class="dashboard-main-grid">


        {{-- Purchase Activity --}}

        <div class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div class="dashboard-panel-heading">

                    <h2>
                        Purchase Activity
                    </h2>

                    <p>
                        Daily purchase value for the last 30 days.
                    </p>

                </div>

            </div>


            <div class="dashboard-panel-body">

                <div class="dashboard-chart">

                    <canvas id="purchaseActivityChart"></canvas>

                </div>

            </div>

        </div>



        {{-- Procurement Summary --}}

        <div class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div class="dashboard-panel-heading">

                    <h2>
                        Procurement Summary
                    </h2>

                    <p>
                        Current purchasing workload and inventory position.
                    </p>

                </div>

            </div>


            <div class="dashboard-panel-body">

                <div class="procurement-summary">


                    {{-- Received Purchases --}}

                    <div class="procurement-row">

                        <span class="procurement-label">
                            Received Purchases
                        </span>

                        <span class="procurement-value green">
                            {{ number_format($receivedPurchaseCount ?? 0) }}
                        </span>

                    </div>


                    <div class="procurement-divider"></div>


                    {{-- Total Purchase Value --}}

                    <div class="procurement-highlight">

                        <div class="procurement-highlight-label">
                            Total Purchase Value
                        </div>

                        <div class="procurement-highlight-value">
                            ₱{{ number_format(
                                (float) ($totalPurchases ?? 0),
                                2
                            ) }}
                        </div>

                    </div>


                    {{-- This Month --}}

                    <div class="procurement-row">

                        <span class="procurement-label">
                            This Month
                        </span>

                        <span class="procurement-value">
                            ₱{{ number_format(
                                (float) ($monthlyPurchases ?? 0),
                                2
                            ) }}
                        </span>

                    </div>


                    {{-- Suppliers --}}

                    <div class="procurement-row">

                        <span class="procurement-label">
                            Suppliers
                        </span>

                        <span class="procurement-value">
                            {{ number_format($supplierCount ?? 0) }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- ============================================================
         OPERATIONAL GRID
         ============================================================ --}}

    <div class="dashboard-operational-grid">


        {{-- Purchase Status --}}

        <div class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div class="dashboard-panel-heading">

                    <h2>
                        Purchase Status
                    </h2>

                    <p>
                        Current supplier purchasing workflow.
                    </p>

                </div>

            </div>


            <div class="dashboard-panel-body">

                <div class="dashboard-chart dashboard-chart-small">

                    <canvas id="purchaseStatusChart"></canvas>

                </div>

            </div>

        </div>



        {{-- Inventory Health --}}

        <div class="dashboard-panel">

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

                <div class="inventory-health-chart">

                    <canvas id="inventoryHealthChart"></canvas>

                </div>

            </div>

        </div>

    </div>



    {{-- ============================================================
         RECENT ACTIVITY
         ============================================================ --}}

    <div class="dashboard-activity-grid">


        {{-- Recent Purchases --}}

        <div class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div class="dashboard-panel-heading">

                    <h2>
                        Recent Purchases
                    </h2>

                    <p>
                        Latest supplier purchase orders and their status.
                    </p>

                </div>

            </div>


            @if (($recentPurchases ?? collect())->count())

                <div class="dashboard-table-wrapper">

                    <table class="dashboard-table">

                        <thead>

                            <tr>

                                <th>
                                    Purchase
                                </th>

                                <th>
                                    Supplier
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Total
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($recentPurchases as $purchase)

                                @php

                                    $statusClass =
                                        match ($purchase->status) {

                                            'Received'
                                                => 'received',

                                            'Pending Approval'
                                                => 'pending',

                                            'Ordered'
                                                => 'ordered',

                                            'Partially Received'
                                                => 'partially',

                                            'Draft'
                                                => 'draft',

                                            'Approved'
                                                => 'approved',

                                            'Rejected'
                                                => 'rejected',

                                            'Cancelled'
                                                => 'cancelled',

                                            default
                                                => 'draft',

                                        };

                                @endphp


                                <tr>

                                    <td>

                                        <span class="dashboard-number">
                                            {{ $purchase->purchase_number }}
                                        </span>

                                    </td>


                                    <td>
                                        {{ $purchase->supplier->name ?? '—' }}
                                    </td>


                                    <td>
                                        {{ $purchase->purchase_date
                                            ? $purchase->purchase_date->format('M d, Y')
                                            : '—'
                                        }}
                                    </td>


                                    <td>

                                        <span class="dashboard-status {{ $statusClass }}">
                                            {{ $purchase->status }}
                                        </span>

                                    </td>


                                    <td>

                                        <span class="dashboard-amount">
                                            ₱{{ number_format(
                                                (float) $purchase->total,
                                                2
                                            ) }}
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
                        PO
                    </div>

                    <div class="dashboard-empty-title">
                        No purchases recorded yet
                    </div>

                    <div class="dashboard-empty-text">
                        Supplier purchase orders will appear here.
                    </div>

                </div>

            @endif

        </div>



        {{-- Recent Stock Activity --}}

        <div class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div class="dashboard-panel-heading">

                    <h2>
                        Recent Stock Activity
                    </h2>

                    <p>
                        Latest inventory movements recorded by the system.
                    </p>

                </div>

            </div>


            @if (($recentStockMovements ?? collect())->count())

                <div class="dashboard-table-wrapper">

                    <table class="dashboard-table">

                        <thead>

                            <tr>

                                <th>
                                    Item
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Quantity
                                </th>

                                <th>
                                    Date
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($recentStockMovements as $movement)

                                @php

                                    $movementType =
                                        strtolower(
                                            $movement->type ?? ''
                                        );


                                    $movementClass =
                                        match ($movementType) {

                                            'stock_in'
                                                => 'received',

                                            'stock_out'
                                                => 'cancelled',

                                            'adjustment'
                                                => 'approved',

                                            default
                                                => 'draft',

                                        };


                                    $movementLabel =
                                        match ($movementType) {

                                            'stock_in'
                                                => 'Stock In',

                                            'stock_out'
                                                => 'Stock Out',

                                            'adjustment'
                                                => 'Adjustment',

                                            default
                                                => ucfirst(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $movementType
                                                    )
                                                ),

                                        };


                                    $movementQuantity =
                                        (float) (
                                            $movement->quantity ?? 0
                                        );


                                    $movementPrefix =
                                        $movementType === 'stock_out'
                                            ? '-'
                                            : (
                                                $movementQuantity > 0
                                                    ? '+'
                                                    : ''
                                            );

                                @endphp


                                <tr>

                                    <td>

                                        <span class="dashboard-number">

                                            {{ $movement->inventoryItem->name ?? '—' }}

                                        </span>

                                    </td>


                                    <td>

                                        <span class="dashboard-status {{ $movementClass }}">

                                            {{ $movementLabel }}

                                        </span>

                                    </td>


                                    <td>

                                        <span class="dashboard-amount">

                                            {{ $movementPrefix }}{{ number_format(
                                                abs($movementQuantity),
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    <td>

                                        {{ $movement->created_at
                                            ? $movement->created_at->format('M d, Y')
                                            : '—'
                                        }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="dashboard-empty">

                    <div class="dashboard-empty-icon">
                        ▦
                    </div>

                    <div class="dashboard-empty-title">
                        No stock activity yet
                    </div>

                    <div class="dashboard-empty-text">
                        Inventory movements will appear here.
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
           DATABASE DATA
           ========================================================== */

        const purchaseActivity =
            {!! $purchaseActivityJson ?: '[]' !!};


        const purchaseStatuses =
            {!! $purchaseStatusesJson ?: '[]' !!};


        const inventoryHealth =
            {!! $inventoryHealthJson ?: '[]' !!};



        /* ==========================================================
           CHART DEFAULTS
           ========================================================== */

        Chart.defaults.font.family =
            "'Segoe UI', Arial, sans-serif";

        Chart.defaults.font.size = 10;

        Chart.defaults.color =
            '#8b8179';



        /* ==========================================================
           PURCHASE ACTIVITY
           ========================================================== */

        const purchaseActivityCanvas =
            document.getElementById(
                'purchaseActivityChart'
            );


        if (purchaseActivityCanvas) {

            new Chart(
                purchaseActivityCanvas,
                {

                    type: 'line',

                    data: {

                        labels:
                            purchaseActivity.map(
                                item => item.date
                            ),

                        datasets: [

                            {

                                label: 'Purchases',

                                data:
                                    purchaseActivity.map(
                                        item => Number(
                                            item.total ?? 0
                                        )
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
                                                ' Purchases: ₱' +
                                                Number(
                                                    context.parsed.y
                                                ).toLocaleString(
                                                    'en-PH',
                                                    {
                                                        minimumFractionDigits: 2,
                                                        maximumFractionDigits: 2
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
                                        item => Number(
                                            item.count ?? 0
                                        )
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



        /* ==========================================================
           INVENTORY HEALTH — DOUGHNUT / CIRCLE GRAPH
           ========================================================== */

        const inventoryHealthCanvas =
            document.getElementById(
                'inventoryHealthChart'
            );


        if (inventoryHealthCanvas) {

            const inventoryLabels =
                inventoryHealth.map(
                    item => item.status
                );


            const inventoryValues =
                inventoryHealth.map(
                    item => Number(
                        item.count ?? 0
                    )
                );


            const totalInventory =
                inventoryValues.reduce(
                    function(total, value) {
                        return total + value;
                    },
                    0
                );


            new Chart(
                inventoryHealthCanvas,
                {

                    type: 'doughnut',

                    data: {

                        labels:
                            inventoryLabels,

                        datasets: [

                            {

                                data:
                                    inventoryValues,

                                backgroundColor: [

                                    '#5d8b67',

                                    '#b9823e',

                                    '#b95d56'

                                ],

                                borderColor: '#ffffff',

                                borderWidth: 3,

                                hoverOffset: 5

                            }

                        ]

                    },


                    options: {

                        responsive: true,

                        maintainAspectRatio: false,

                        cutout: '68%',


                        plugins: {

                            legend: {

                                position: 'bottom',

                                labels: {

                                    usePointStyle: true,

                                    pointStyle: 'circle',

                                    padding: 16,

                                    color: '#756b63',

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

                                            const value =
                                                Number(
                                                    context.raw ?? 0
                                                );


                                            const percentage =
                                                totalInventory > 0
                                                    ? (
                                                        value /
                                                        totalInventory
                                                    ) * 100
                                                    : 0;


                                            return (
                                                ' ' +
                                                context.label +
                                                ': ' +
                                                value +
                                                ' (' +
                                                percentage.toFixed(1) +
                                                '%)'
                                            );

                                        }

                                }

                            }

                        }

                    },


                    plugins: [

                        {

                            id: 'inventoryHealthCenterText',

                            beforeDraw:
                                function(chart) {

                                    const width =
                                        chart.width;

                                    const height =
                                        chart.height;

                                    const ctx =
                                        chart.ctx;


                                    ctx.save();


                                    const centerX =
                                        width / 2;

                                    const centerY =
                                        height / 2;


                                    ctx.textAlign =
                                        'center';

                                    ctx.textBaseline =
                                        'middle';


                                    ctx.fillStyle =
                                        '#241a14';

                                    ctx.font =
                                        '800 24px Segoe UI, Arial, sans-serif';


                                    ctx.fillText(
                                        totalInventory.toLocaleString(),
                                        centerX,
                                        centerY - 7
                                    );


                                    ctx.fillStyle =
                                        '#8b8179';

                                    ctx.font =
                                        '600 9px Segoe UI, Arial, sans-serif';


                                    ctx.fillText(
                                        'TOTAL ITEMS',
                                        centerX,
                                        centerY + 14
                                    );


                                    ctx.restore();

                                }

                        }

                    ]

                }

            );

        }

    }

);

</script>

@endsection