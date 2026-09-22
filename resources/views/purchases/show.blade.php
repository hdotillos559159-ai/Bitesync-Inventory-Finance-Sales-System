@extends('layouts.app')

@section('title', 'BiteSync | Purchase Details')

@php

    $currentUser = auth()->user();
    $role = $currentUser?->role;

    $isAdmin = $role === 'CEO/Admin';

    $canManagePurchases =
        in_array($role, ['CEO/Admin', 'Procurement'], true);

    $canReceive =
        $canManagePurchases &&
        in_array(
            $purchase->status,
            ['Ordered', 'Partially Received'],
            true
        );

    $hasReceivedStock = $purchase->items->contains(
        function ($item) {

            return (float) ($item->received_quantity ?? 0) > 0;

        }
    );

    $canCancelPurchase =
        $canManagePurchases &&
        !in_array(
            $purchase->status,
            ['Received', 'Cancelled', 'Partially Received'],
            true
        ) &&
        !$hasReceivedStock;


    $statusClass = match ($purchase->status) {

        'Draft' =>
            'purchase-status-draft',

        'Pending Approval' =>
            'purchase-status-pending',

        'Approved' =>
            'purchase-status-approved',

        'Rejected' =>
            'purchase-status-rejected',

        'Ordered' =>
            'purchase-status-ordered',

        'Partially Received' =>
            'purchase-status-partial',

        'Received' =>
            'purchase-status-received',

        'Cancelled' =>
            'purchase-status-cancelled',

        default =>
            'purchase-status-default',

    };


    $statusIcon = match ($purchase->status) {

        'Draft' =>
            '📝',

        'Pending Approval' =>
            '⏳',

        'Approved' =>
            '✓',

        'Rejected' =>
            '✕',

        'Ordered' =>
            '📦',

        'Partially Received' =>
            '◐',

        'Received' =>
            '✓',

        'Cancelled' =>
            '⊘',

        default =>
            '•',

    };


    $totalOrderedQuantity =
        $purchase->items->sum(
            function ($item) {

                return (float) $item->quantity;

            }
        );


    $totalReceivedQuantity =
        $purchase->items->sum(
            function ($item) {

                return (float) ($item->received_quantity ?? 0);

            }
        );


    $totalRemainingQuantity = max(
        0,
        $totalOrderedQuantity - $totalReceivedQuantity
    );

@endphp


@section('content')

<div class="purchase-show-page">


    {{-- =========================================================
         TOPBAR
    ========================================================== --}}

    <div class="topbar">

        <div class="page-title">

            <small>
                Purchasing
            </small>

            <h1>
                Purchase Details
            </h1>

            <p>
                Review the complete details, supplier information, items, and receiving status for this purchase.
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

    <div class="purchase-breadcrumb">

        <a href="{{ route('purchases.index') }}">
            Purchases
        </a>

        <span>
            /
        </span>

        <strong>
            Purchase Details
        </strong>

        <span>
            /
        </span>

        <strong>
            {{ $purchase->purchase_number }}
        </strong>

    </div>


    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="purchase-page-header">

        <div class="purchase-page-title">

            <small>
                PURCHASE RECORD
            </small>

            <h2>
                {{ $purchase->purchase_number }}
            </h2>

            <p>
                Supplier order and receiving record
            </p>

        </div>


        <div class="purchase-header-actions">

            <a
                href="{{ route('purchases.index') }}"
                class="secondary-button"
            >

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path d="M19 12H5"/>
                    <path d="M12 19l-7-7 7-7"/>
                </svg>

                Back to Purchases

            </a>


            @if (
                $canManagePurchases &&
                in_array(
                    $purchase->status,
                    ['Draft', 'Rejected'],
                    true
                )
            )

                <a
                    href="{{ route('purchases.edit', $purchase) }}"
                    class="save-button"
                >

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M12 20h9"/>
                        <path d="M16.5 3.5a2.1 2.1 0 013 3L8 18l-4 1 1-4L16.5 3.5z"/>
                    </svg>

                    Edit Purchase

                </a>

            @endif

        </div>

    </div>


    {{-- =========================================================
         ALERTS
    ========================================================== --}}

    @if (session('success'))

        <div class="form-alert form-alert-success">

            <div class="alert-icon">
                ✓
            </div>

            <div>
                {{ session('success') }}
            </div>

        </div>

    @endif


    @if (session('error'))

        <div class="form-alert form-alert-error">

            <div class="alert-icon">
                !
            </div>

            <div>
                {{ session('error') }}
            </div>

        </div>

    @endif


    @if ($errors->any())

        <div class="form-alert form-alert-error">

            <div class="alert-icon">
                !
            </div>

            <div>

                <div class="alert-title">
                    Please check the form.
                </div>

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


    {{-- =========================================================
         STATUS NOTICE
    ========================================================== --}}

    @if ($purchase->status === 'Pending Approval')

        <div class="status-notice status-warning">

            <div class="status-notice-icon">
                ⏳
            </div>

            <div>

                <strong>
                    Awaiting approval
                </strong>

                <p>
                    This purchase has been submitted and is waiting for CEO/Admin approval before it can be ordered.
                </p>

            </div>

        </div>

    @elseif ($purchase->status === 'Approved')

        <div class="status-notice status-info">

            <div class="status-notice-icon">
                ✓
            </div>

            <div>

                <strong>
                    Purchase approved
                </strong>

                <p>
                    This purchase is approved and can now be marked as ordered.
                </p>

            </div>

        </div>

    @elseif ($purchase->status === 'Ordered')

        <div class="status-notice status-info">

            <div class="status-notice-icon">
                📦
            </div>

            <div>

                <strong>
                    Purchase ordered
                </strong>

                <p>
                    Inventory can now be received against this purchase.
                </p>

            </div>

        </div>

    @elseif ($purchase->status === 'Partially Received')

        <div class="status-notice status-warning">

            <div class="status-notice-icon">
                ◐
            </div>

            <div>

                <strong>
                    Partially received
                </strong>

                <p>
                    Some items have already been received. The remaining quantities can still be received.
                </p>

            </div>

        </div>

    @elseif ($purchase->status === 'Received')

        <div class="status-notice status-success">

            <div class="status-notice-icon">
                ✓
            </div>

            <div>

                <strong>
                    Purchase fully received
                </strong>

                <p>
                    All ordered quantities have been received and added to inventory.
                </p>

            </div>

        </div>

    @elseif ($purchase->status === 'Rejected')

        <div class="status-notice status-error">

            <div class="status-notice-icon">
                ✕
            </div>

            <div>

                <strong>
                    Purchase rejected
                </strong>

                <p>
                    This purchase was rejected and can be edited and resubmitted.
                </p>

            </div>

        </div>

    @elseif ($purchase->status === 'Cancelled')

        <div class="status-notice status-error">

            <div class="status-notice-icon">
                ⊘
            </div>

            <div>

                <strong>
                    Purchase cancelled
                </strong>

                <p>
                    This purchase is no longer active and cannot be received.
                </p>

            </div>

        </div>

    @endif


    {{-- =========================================================
         MAIN GRID
    ========================================================== --}}

    <div class="purchase-main-grid">


        {{-- =====================================================
             LEFT COLUMN
        ====================================================== --}}

        <div class="purchase-left-column">


            {{-- =================================================
                 PURCHASE INFORMATION
            ================================================== --}}

            <div class="form-panel purchase-information-panel">

                <div class="form-panel-header">

                    <div>

                        <div class="form-panel-title">
                            Purchase Information
                        </div>

                        <div class="form-panel-subtitle">
                            Basic information and current status of this purchase.
                        </div>

                    </div>

                    <div class="form-panel-icon">
                        🧾
                    </div>

                </div>


                {{-- Intentionally no individual cards/borders --}}

                <div class="purchase-information-grid">


                    <div class="purchase-information-item">

                        <span>
                            Purchase Number
                        </span>

                        <strong>
                            {{ $purchase->purchase_number }}
                        </strong>

                    </div>


                    <div class="purchase-information-item">

                        <span>
                            Supplier
                        </span>

                        <strong>
                            {{ $purchase->supplier?->name ?? '—' }}
                        </strong>

                    </div>


                    <div class="purchase-information-item">

                        <span>
                            Status
                        </span>

                        <span class="purchase-status-badge {{ $statusClass }}">

                            <span>
                                {{ $statusIcon }}
                            </span>

                            {{ $purchase->status }}

                        </span>

                    </div>


                    <div class="purchase-information-item">

                        <span>
                            Purchase Date
                        </span>

                        <strong>
                            {{ $purchase->purchase_date?->format('M d, Y') ?? '—' }}
                        </strong>

                    </div>


                    <div class="purchase-information-item">

                        <span>
                            Expected Delivery
                        </span>

                        <strong>
                            {{ $purchase->expected_date?->format('M d, Y') ?? '—' }}
                        </strong>

                    </div>


                    <div class="purchase-information-item">

                        <span>
                            Line Items
                        </span>

                        <strong>
                            {{ $purchase->items->count() }}
                            {{ $purchase->items->count() === 1 ? 'item' : 'items' }}
                        </strong>

                    </div>


                </div>

            </div>


            {{-- =================================================
                 PURCHASE ITEMS
            ================================================== --}}

            <div class="form-panel purchase-items-panel">

                <div class="form-panel-header">

                    <div>

                        <div class="form-panel-title">
                            Purchase Items
                        </div>

                        <div class="form-panel-subtitle">
                            Inventory items included in this supplier purchase.
                        </div>

                    </div>

                    <div class="form-panel-icon">
                        📦
                    </div>

                </div>


                <div class="items-content">

                    <div class="table-wrapper">

                        <table class="items-table">

                            <thead>

                                <tr>

                                    <th style="width: 25%;">
                                        Inventory Item
                                    </th>

                                    <th style="width: 13%;">
                                        Category
                                    </th>

                                    <th style="width: 10%;">
                                        Ordered
                                    </th>

                                    <th style="width: 10%;">
                                        Unit
                                    </th>

                                    <th style="width: 13%;">
                                        Unit Cost
                                    </th>

                                    <th style="width: 13%;">
                                        Subtotal
                                    </th>

                                    <th style="width: 16%;">
                                        Receiving
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse ($purchase->items as $item)

                                    @php

                                        $ordered =
                                            (float) $item->quantity;

                                        $received =
                                            (float) ($item->received_quantity ?? 0);

                                        $remaining =
                                            max(
                                                0,
                                                $ordered - $received
                                            );

                                    @endphp


                                    <tr>


                                        {{-- ITEM --}}

                                        <td data-label="Inventory Item">

                                            <div class="item-name">
                                                {{ $item->inventoryItem?->name ?? 'Unknown Item' }}
                                            </div>

                                            @if ($item->inventoryItem?->sku)

                                                <div class="item-sku">
                                                    SKU: {{ $item->inventoryItem->sku }}
                                                </div>

                                            @endif

                                        </td>


                                        {{-- CATEGORY --}}

                                        <td data-label="Category">

                                            {{ $item->inventoryItem?->category?->name ?? '—' }}

                                        </td>


                                        {{-- ORDERED --}}

                                        <td data-label="Ordered">

                                            <strong>
                                                {{ number_format($ordered, 2) }}
                                            </strong>

                                        </td>


                                        {{-- UNIT --}}

                                        <td data-label="Unit">

                                            {{ $item->inventoryItem?->unit?->name ?? '—' }}

                                        </td>


                                        {{-- UNIT COST --}}

                                        <td data-label="Unit Cost">

                                            ₱{{ number_format((float) $item->unit_cost, 2) }}

                                        </td>


                                        {{-- SUBTOTAL --}}

                                        <td data-label="Subtotal">

                                            <strong>
                                                ₱{{ number_format((float) $item->subtotal, 2) }}
                                            </strong>

                                        </td>


                                        {{-- RECEIVING --}}

                                        <td data-label="Receiving">

                                            <div class="item-receiving">


                                                <div class="item-receiving-line">

                                                    <span>
                                                        Received
                                                    </span>

                                                    <strong>
                                                        {{ number_format($received, 2) }}
                                                    </strong>

                                                </div>


                                                @if ($remaining > 0)

                                                    <div class="item-receiving-line remaining">

                                                        <span>
                                                            Remaining
                                                        </span>

                                                        <strong>
                                                            {{ number_format($remaining, 2) }}
                                                        </strong>

                                                    </div>

                                                @else

                                                    <div class="item-complete">
                                                        Complete
                                                    </div>

                                                @endif


                                            </div>

                                        </td>


                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="7"
                                            class="empty-cell"
                                        >
                                            No purchase items found.
                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 PURCHASE RECORD
            ================================================== --}}

            <div class="form-panel purchase-record-panel">

                <div class="form-panel-header">

                    <div>

                        <div class="form-panel-title">
                            Purchase Record
                        </div>

                        <div class="form-panel-subtitle">
                            Users responsible for creating and approving this purchase.
                        </div>

                    </div>

                    <div class="form-panel-icon">
                        ≡
                    </div>

                </div>


                <div class="record-content">

                    <div class="table-wrapper">

                        <table class="record-table">

                            <thead>

                                <tr>

                                    <th style="width: 20%;">
                                        Record
                                    </th>

                                    <th style="width: 28%;">
                                        User
                                    </th>

                                    <th style="width: 27%;">
                                        Email
                                    </th>

                                    <th style="width: 25%;">
                                        Date &amp; Time
                                    </th>

                                </tr>

                            </thead>


                            <tbody>


                                {{-- CREATED BY --}}

                                <tr>

                                    <td data-label="Record">

                                        <strong>
                                            Created By
                                        </strong>

                                    </td>


                                    <td data-label="User">

                                        <div class="record-user">

                                            <div class="record-avatar">

                                                {{ strtoupper(
                                                    substr(
                                                        $purchase->creator?->name
                                                        ?? $purchase->creator?->full_name
                                                        ?? 'U',
                                                        0,
                                                        1
                                                    )
                                                ) }}

                                            </div>

                                            <span>

                                                {{
                                                    $purchase->creator?->name
                                                    ?? $purchase->creator?->full_name
                                                    ?? '—'
                                                }}

                                            </span>

                                        </div>

                                    </td>


                                    <td data-label="Email">

                                        {{ $purchase->creator?->email ?? '—' }}

                                    </td>


                                    <td data-label="Date & Time">

                                        {{
                                            $purchase->created_at
                                                ?->format('M d, Y · h:i A')
                                            ?? '—'
                                        }}

                                    </td>

                                </tr>


                                {{-- APPROVED BY --}}

                                <tr>

                                    <td data-label="Record">

                                        <strong>
                                            Approved By
                                        </strong>

                                    </td>


                                    <td data-label="User">

                                        @if ($purchase->approver)

                                            <div class="record-user">

                                                <div class="record-avatar">

                                                    {{ strtoupper(
                                                        substr(
                                                            $purchase->approver?->name
                                                            ?? $purchase->approver?->full_name
                                                            ?? 'U',
                                                            0,
                                                            1
                                                        )
                                                    ) }}

                                                </div>

                                                <span>

                                                    {{
                                                        $purchase->approver?->name
                                                        ?? $purchase->approver?->full_name
                                                        ?? '—'
                                                    }}

                                                </span>

                                            </div>

                                        @else

                                            <span class="record-muted">
                                                Pending
                                            </span>

                                        @endif

                                    </td>


                                    <td data-label="Email">

                                        {{ $purchase->approver?->email ?? '—' }}

                                    </td>


                                    <td data-label="Date & Time">

                                        {{
                                            $purchase->approved_at
                                                ?->format('M d, Y · h:i A')
                                            ?? '—'
                                        }}

                                    </td>

                                </tr>


                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 ADDITIONAL INFORMATION
            ================================================== --}}

            @if ($purchase->notes)

                <div class="form-panel">

                    <div class="form-panel-header">

                        <div>

                            <div class="form-panel-title">
                                Additional Information
                            </div>

                            <div class="form-panel-subtitle">
                                Notes and additional details recorded for this purchase.
                            </div>

                        </div>

                        <div class="form-panel-icon">
                            ≡
                        </div>

                    </div>


                    <div class="form-content">

                        <div class="purchase-notes">
                            {{ $purchase->notes }}
                        </div>

                    </div>

                </div>

            @endif


        </div>


        {{-- =====================================================
             RIGHT COLUMN
        ====================================================== --}}

        <div class="purchase-right-column">


            {{-- =================================================
                 PURCHASE SUMMARY
            ================================================== --}}

            <div class="form-panel purchase-summary-panel">

                <div class="form-panel-header">

                    <div>

                        <div class="form-panel-title">
                            Purchase Summary
                        </div>

                        <div class="form-panel-subtitle">
                            Review the calculated purchase amount.
                        </div>

                    </div>

                    <div class="form-panel-icon">
                        ₱
                    </div>

                </div>


                <div class="form-content">


                    <div class="summary-row">

                        <div>

                            <span>
                                Subtotal
                            </span>

                            <small>
                                Total before tax
                            </small>

                        </div>

                        <strong>
                            ₱{{ number_format((float) $purchase->subtotal, 2) }}
                        </strong>

                    </div>


                    <div class="summary-row">

                        <div>

                            <span>
                                Tax
                            </span>

                            <small>
                                Applied tax
                            </small>

                        </div>

                        <strong>
                            ₱{{ number_format((float) $purchase->tax, 2) }}
                        </strong>

                    </div>


                    <div class="summary-total">

                        <div>

                            <span>
                                Total
                            </span>

                            <small>
                                Subtotal + tax
                            </small>

                        </div>

                        <strong>
                            ₱{{ number_format((float) $purchase->total, 2) }}
                        </strong>

                    </div>


                    {{-- SUPPLIER --}}

                    <div class="supplier-summary">

                        <div class="supplier-summary-label">
                            SUPPLIER
                        </div>

                        <strong>
                            {{ $purchase->supplier?->name ?? '—' }}
                        </strong>


                        @if ($purchase->supplier?->contact_person)

                            <span>
                                Contact: {{ $purchase->supplier->contact_person }}
                            </span>

                        @endif


                        @if ($purchase->supplier?->phone)

                            <span>
                                {{ $purchase->supplier->phone }}
                            </span>

                        @endif


                        @if ($purchase->supplier?->email)

                            <span>
                                {{ $purchase->supplier->email }}
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- =================================================
                 RECEIVE STOCK
            ================================================== --}}

            @if ($canReceive)

                <div class="form-panel receive-panel">

                    <div class="form-panel-header">

                        <div>

                            <div class="form-panel-title">
                                Receive Stock
                            </div>

                            <div class="form-panel-subtitle">
                                Record the quantities physically received from this purchase.
                            </div>

                        </div>

                        <div class="form-panel-icon">
                            ↓
                        </div>

                    </div>


                    <form
                        method="POST"
                        action="{{ route('purchases.receive', $purchase) }}"
                    >

                        @csrf


                        <div class="receive-content">


                            @foreach ($purchase->items as $item)

                                @php

                                    $ordered =
                                        (float) $item->quantity;

                                    $received =
                                        (float) ($item->received_quantity ?? 0);

                                    $remaining =
                                        max(
                                            0,
                                            $ordered - $received
                                        );

                                @endphp


                                @if ($remaining > 0)

                                    <div class="receiving-item">


                                        <div class="receiving-item-top">

                                            <div>

                                                <div class="receiving-item-name">
                                                    {{ $item->inventoryItem?->name ?? 'Unknown Item' }}
                                                </div>

                                                <div class="receiving-item-unit">
                                                    {{ $item->inventoryItem?->unit?->name ?? 'Unit' }}
                                                </div>

                                            </div>


                                            <div class="receiving-remaining">

                                                {{ number_format($remaining, 2) }}

                                                remaining

                                            </div>

                                        </div>


                                        <div class="form-group receive-quantity-group">

                                            <label
                                                for="received_{{ $item->id }}"
                                            >
                                                Quantity to Receive
                                            </label>

                                            <input
                                                type="number"
                                                name="received[{{ $item->id }}]"
                                                id="received_{{ $item->id }}"
                                                value="{{ old('received.' . $item->id, $remaining) }}"
                                                min="0"
                                                max="{{ $remaining }}"
                                                step="0.01"
                                                required
                                            >

                                        </div>


                                        <div class="receiving-breakdown">


                                            <div class="receiving-stat">

                                                <span>
                                                    Ordered
                                                </span>

                                                <strong>
                                                    {{ number_format($ordered, 2) }}
                                                </strong>

                                            </div>


                                            <div class="receiving-stat">

                                                <span>
                                                    Received
                                                </span>

                                                <strong>
                                                    {{ number_format($received, 2) }}
                                                </strong>

                                            </div>


                                            <div class="receiving-stat receiving-stat-remaining">

                                                <span>
                                                    Remaining
                                                </span>

                                                <strong>
                                                    {{ number_format($remaining, 2) }}
                                                </strong>

                                            </div>


                                        </div>

                                    </div>

                                @endif

                            @endforeach


                            @if ($totalRemainingQuantity > 0)

                                <button
                                    type="submit"
                                    class="save-button receive-button"
                                >

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path d="M12 3v12"/>
                                        <path d="M7 10l5 5 5-5"/>
                                        <path d="M5 21h14"/>
                                    </svg>

                                    Receive Stock

                                </button>

                            @endif

                        </div>

                    </form>

                </div>

            @elseif ($purchase->status === 'Received')

                <div class="form-panel completion-panel">

                    <div class="completion-icon">
                        ✓
                    </div>

                    <div>

                        <strong>
                            All stock received
                        </strong>

                        <p>
                            Every ordered item has been fully received into inventory.
                        </p>

                    </div>

                </div>

            @elseif ($purchase->status === 'Cancelled')

                <div class="form-panel completion-panel cancelled-panel">

                    <div class="completion-icon">
                        ⊘
                    </div>

                    <div>

                        <strong>
                            Purchase cancelled
                        </strong>

                        <p>
                            This purchase is no longer available for receiving.
                        </p>

                    </div>

                </div>

            @endif


            {{-- =================================================
                 WORKFLOW ACTIONS
            ================================================== --}}

            @if ($canManagePurchases)

                <div class="form-panel workflow-panel">

                    <div class="form-panel-header">

                        <div>

                            <div class="form-panel-title">
                                Workflow Actions
                            </div>

                            <div class="form-panel-subtitle">
                                Available actions based on the current purchase status.
                            </div>

                        </div>

                        <div class="form-panel-icon">
                            ⋯
                        </div>

                    </div>


                    <div class="workflow-actions">


                        {{-- SUBMIT --}}

                        @if (
                            in_array(
                                $purchase->status,
                                ['Draft', 'Rejected'],
                                true
                            )
                        )

                            <form
                                method="POST"
                                action="{{ route('purchases.submit', $purchase) }}"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="workflow-button workflow-primary"
                                >

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path d="M22 2L11 13"/>
                                        <path d="M22 2l-7 20-4-9-9-4 20-7z"/>
                                    </svg>

                                    Submit for Approval

                                </button>

                            </form>

                        @endif


                        {{-- APPROVE --}}

                        @if (
                            $isAdmin &&
                            $purchase->status === 'Pending Approval'
                        )

                            <form
                                method="POST"
                                action="{{ route('purchases.approve', $purchase) }}"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="workflow-button workflow-approve"
                                >

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path d="M20 6L9 17l-5-5"/>
                                    </svg>

                                    Approve Purchase

                                </button>

                            </form>

                        @endif


                        {{-- REJECT --}}

                        @if (
                            $isAdmin &&
                            $purchase->status === 'Pending Approval'
                        )

                            <form
                                method="POST"
                                action="{{ route('purchases.reject', $purchase) }}"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="workflow-button workflow-reject"
                                >

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path d="M18 6L6 18"/>
                                        <path d="M6 6l12 12"/>
                                    </svg>

                                    Reject Purchase

                                </button>

                            </form>

                        @endif


                        {{-- ORDER --}}

                        @if ($purchase->status === 'Approved')

                            <form
                                method="POST"
                                action="{{ route('purchases.order', $purchase) }}"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="workflow-button workflow-primary"
                                >

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path d="M3 6h18"/>
                                        <path d="M5 6v13h14V6"/>
                                        <path d="M9 10h6"/>
                                        <path d="M8 3h8"/>
                                    </svg>

                                    Mark as Ordered

                                </button>

                            </form>

                        @endif


                        {{-- CANCEL --}}

                        @if ($canCancelPurchase)

                            <form
                                method="POST"
                                action="{{ route('purchases.cancel', $purchase) }}"
                                onsubmit="return confirm('Are you sure you want to cancel this purchase?');"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="workflow-button workflow-cancel"
                                >

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="M8 8l8 8"/>
                                    </svg>

                                    Cancel Purchase

                                </button>

                            </form>

                        @endif


                    </div>

                </div>

            @endif


        </div>

    </div>

</div>

@endsection


@push('styles')

<style>


/* ================================================================
   PAGE
================================================================ */

.purchase-show-page {

    width: 100%;
    max-width: 1120px;

    margin: 0 auto;

    padding-bottom: 30px;

    overflow-x: hidden;

}


/* ================================================================
   TOPBAR
================================================================ */

.purchase-show-page .topbar {

    display: flex;

    align-items: flex-start;

    justify-content: space-between;

    gap: 25px;

    margin-bottom: 14px;

}


.purchase-show-page .page-title small {

    display: block;

    margin-bottom: 5px;

    color: #a9825b;

    font-size: 10px;

    font-weight: 700;

    letter-spacing: 0.12em;

    text-transform: uppercase;

}


.purchase-show-page .page-title h1 {

    margin: 0;

    color: var(--dark);

    font-size: 29px;

    font-weight: 700;

    line-height: 1.15;

    letter-spacing: -0.045rem;

}


.purchase-show-page .page-title p {

    margin: 6px 0 0;

    color: var(--muted);

    font-size: 12px;

    line-height: 1.5;

    font-weight: 400;

}


/* ================================================================
   DATE BOX
================================================================ */

.purchase-show-page .date-box {

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

    box-shadow:
        0 3px 12px
        rgba(43, 31, 23, .025);

}


.purchase-show-page .date-icon {

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


/* ================================================================
   BREADCRUMB
================================================================ */

.purchase-show-page .purchase-breadcrumb {

    display: flex;

    align-items: center;

    flex-wrap: wrap;

    gap: 7px;

    margin-bottom: 17px;

    color: #96877b;

    font-size: 10px;

    font-weight: 600;

    line-height: 1.3;

}


.purchase-show-page .purchase-breadcrumb a {

    color: #a16e42;

    font-weight: 600;

    text-decoration: none;

    transition: color .18s ease;

}


.purchase-show-page .purchase-breadcrumb a:hover {

    color: #7d4e29;

}


.purchase-show-page .purchase-breadcrumb span {

    color: #c2b5aa;

}


.purchase-show-page .purchase-breadcrumb strong {

    color: #6f6259;

    font-weight: 600;

    overflow-wrap: anywhere;

}


/* ================================================================
   PAGE HEADER
================================================================ */

.purchase-page-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    margin-bottom: 16px;

}


.purchase-page-title {

    min-width: 0;

}


.purchase-page-title small {

    display: block;

    margin-bottom: 4px;

    color: #a9825b;

    font-size: 9px;

    font-weight: 700;

    letter-spacing: .10em;

}


.purchase-page-title h2 {

    margin: 0;

    color: var(--dark);

    font-size: 20px;

    line-height: 1.2;

    font-weight: 700;

}


.purchase-page-title p {

    margin: 5px 0 0;

    color: var(--muted);

    font-size: 10px;

    line-height: 1.4;

}


.purchase-header-actions {

    display: flex;

    align-items: center;

    justify-content: flex-end;

    flex-wrap: wrap;

    gap: 8px;

}


/* ================================================================
   BUTTONS
================================================================ */

.purchase-show-page .secondary-button,
.purchase-show-page .save-button {

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
        background-color .18s ease,
        border-color .18s ease,
        color .18s ease,
        transform .18s ease,
        box-shadow .18s ease;

}


.purchase-show-page .secondary-button {

    border: 1px solid var(--border);

    background: white;

    color: var(--muted);

}


.purchase-show-page .secondary-button:hover {

    border-color: #d4c6ba;

    background: #faf7f4;

    color: var(--dark);

}


.purchase-show-page .save-button {

    min-width: 120px;

    border: none;

    background:
        linear-gradient(
            135deg,
            var(--orange),
            var(--orange-dark)
        );

    color: white;

    box-shadow:
        0 4px 11px
        rgba(145, 97, 55, 0.14);

}


.purchase-show-page .save-button:hover {

    background:
        linear-gradient(
            135deg,
            var(--orange-dark),
            var(--orange-dark)
        );

    color: white;

    transform: translateY(-1px);

    box-shadow:
        0 6px 14px
        rgba(145, 97, 55, 0.18);

}


.purchase-show-page .save-button svg,
.purchase-show-page .secondary-button svg {

    width: 14px;

    height: 14px;

    flex-shrink: 0;

}


/* ================================================================
   ALERT
================================================================ */

.purchase-show-page .form-alert {

    display: flex;

    align-items: flex-start;

    gap: 10px;

    margin-bottom: 16px;

    padding: 11px 13px;

    border-radius: 10px;

    font-size: 11px;

    line-height: 1.45;

    font-weight: 400;

}


.purchase-show-page .form-alert-success {

    border: 1px solid #d8e8dc;

    background: #f6fbf7;

    color: #4d7557;

}


.purchase-show-page .form-alert-error {

    border: 1px solid #efd4cf;

    background: #fff7f5;

    color: #7d433b;

}


.purchase-show-page .alert-icon {

    display: flex;

    align-items: center;

    justify-content: center;

    width: 22px;

    height: 22px;

    flex-shrink: 0;

    border-radius: 7px;

    background: #f3d4cf;

    color: #8a4339;

    font-size: 11px;

    font-weight: 700;

}


.purchase-show-page .form-alert-success .alert-icon {

    background: #dcecdf;

    color: #4c7657;

}


.purchase-show-page .alert-title {

    margin-bottom: 4px;

    font-size: 11px;

    font-weight: 700;

}


.purchase-show-page .alert-list {

    margin: 4px 0 0;

    padding-left: 16px;

    line-height: 1.45;

}


.purchase-show-page .alert-list li {

    margin-bottom: 2px;

    font-size: 11px;

}


/* ================================================================
   STATUS NOTICE
================================================================ */

.status-notice {

    display: flex;

    align-items: flex-start;

    gap: 10px;

    margin-bottom: 16px;

    padding: 11px 13px;

    border-radius: 10px;

}


.status-notice-icon {

    width: 27px;

    height: 27px;

    flex-shrink: 0;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 8px;

    font-size: 12px;

}


.status-notice strong {

    display: block;

    margin-bottom: 2px;

    font-size: 11px;

    line-height: 1.35;

    font-weight: 700;

}


.status-notice p {

    margin: 0;

    font-size: 9px;

    line-height: 1.45;

}


.status-warning {

    border: 1px solid #eadbc8;

    background: #fffaf4;

    color: #816343;

}


.status-warning .status-notice-icon {

    background: #f1e3d0;

}


.status-info {

    border: 1px solid #e0d9d2;

    background: #fbfaf8;

    color: #655b53;

}


.status-info .status-notice-icon {

    background: #eee9e4;

}


.status-success {

    border: 1px solid #d8e8dc;

    background: #f6fbf7;

    color: #4f7658;

}


.status-success .status-notice-icon {

    background: #e1efe4;

}


.status-error {

    border: 1px solid #ecd7d3;

    background: #fff8f6;

    color: #96564d;

}


.status-error .status-notice-icon {

    background: #f4e3df;

}


/* ================================================================
   MAIN GRID
================================================================ */

.purchase-main-grid {

    display: grid;

    grid-template-columns:
        minmax(0, 1.9fr)
        minmax(285px, .9fr);

    align-items: start;

    gap: 17px;

    min-width: 0;

}


.purchase-left-column,
.purchase-right-column {

    display: flex;

    flex-direction: column;

    gap: 17px;

    min-width: 0;

}


/* ================================================================
   FORM PANEL
================================================================ */

.purchase-show-page .form-panel {

    min-width: 0;

    overflow: hidden;

    border: 1px solid var(--border);

    border-radius: 15px;

    background: white;

    box-shadow:
        0 4px 16px
        rgba(43, 31, 23, 0.035);

}


/* ================================================================
   PANEL HEADER
================================================================ */

.purchase-show-page .form-panel-header {

    min-height: 65px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    padding: 14px 19px;

    border-bottom: 1px solid var(--border);

    background: white;

}


.purchase-show-page .form-panel-title {

    color: var(--dark);

    font-size: 17px;

    line-height: 1.3;

    font-weight: 700;

}


.purchase-show-page .form-panel-subtitle {

    margin-top: 3px;

    color: var(--muted);

    font-size: 11px;

    line-height: 1.4;

    font-weight: 400;

}


.purchase-show-page .form-panel-icon {

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


/* ================================================================
   PURCHASE INFORMATION
   NO INDIVIDUAL BORDERS
================================================================ */

.purchase-information-grid {

    display: grid;

    grid-template-columns:
        repeat(3, minmax(0, 1fr));

    column-gap: 28px;

    row-gap: 19px;

    padding: 18px 19px 20px;

}


.purchase-information-item {

    min-width: 0;

}


.purchase-information-item > span:first-child {

    display: block;

    margin-bottom: 6px;

    color: #96877b;

    font-size: 9px;

    line-height: 1.3;

    font-weight: 600;

}


.purchase-information-item > strong {

    display: block;

    min-width: 0;

    color: #4c3c31;

    font-size: 12px;

    line-height: 1.35;

    font-weight: 700;

    overflow-wrap: anywhere;

}


.purchase-status-badge {

    display: inline-flex;

    align-items: center;

    gap: 5px;

    width: fit-content;

    max-width: 100%;

    padding: 5px 8px;

    border-radius: 7px;

    font-size: 9px;

    line-height: 1;

    font-weight: 700;

    white-space: nowrap;

}


.purchase-status-draft {

    background: #f1ede9;

    color: #756a61;

}


.purchase-status-pending {

    background: #fff1d9;

    color: #9a6d32;

}


.purchase-status-approved {

    background: #e8f3eb;

    color: #4f7c5b;

}


.purchase-status-rejected {

    background: #f9e8e5;

    color: #a3544c;

}


.purchase-status-ordered {

    background: #eee9e3;

    color: #705e4e;

}


.purchase-status-partial {

    background: #fff1da;

    color: #9a6c32;

}


.purchase-status-received {

    background: #e6f3e9;

    color: #4c7958;

}


.purchase-status-cancelled {

    background: #f1e8e6;

    color: #89645d;

}


.purchase-status-default {

    background: #efebe7;

    color: #6f655d;

}


/* ================================================================
   ITEMS
================================================================ */

.purchase-show-page .items-content {

    padding: 16px 18px 18px;

}


.purchase-show-page .table-wrapper {

    width: 100%;

    overflow: hidden;

}


.purchase-show-page .items-table {

    width: 100%;

    min-width: 0;

    table-layout: fixed;

    border-collapse: collapse;

}


.purchase-show-page .items-table th {

    padding: 9px 8px;

    border-bottom: 1px solid #e8e0d9;

    background: #fcfaf8;

    color: #796b61;

    font-size: 9px;

    font-weight: 700;

    letter-spacing: .055em;

    line-height: 1.3;

    text-align: left;

    text-transform: uppercase;

}


.purchase-show-page .items-table td {

    padding: 10px 8px;

    border-bottom: 1px solid #eee8e2;

    color: #655b53;

    font-size: 10px;

    line-height: 1.4;

    vertical-align: middle;

    overflow-wrap: anywhere;

}


.purchase-show-page .items-table tbody tr:last-child td {

    border-bottom: none;

}


.purchase-show-page .item-name {

    color: #4c3c31;

    font-size: 12px;

    line-height: 1.35;

    font-weight: 700;

}


.purchase-show-page .item-sku {

    margin-top: 3px;

    color: #a4978d;

    font-size: 8px;

    line-height: 1.3;

}


.purchase-show-page .items-table td strong {

    color: #594b40;

    font-weight: 700;

}


.purchase-show-page .item-receiving {

    display: flex;

    flex-direction: column;

    gap: 4px;

    min-width: 0;

}


.purchase-show-page .item-receiving-line {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 6px;

    min-width: 0;

}


.purchase-show-page .item-receiving-line span {

    color: #9a8e84;

    font-size: 8px;

    line-height: 1.2;

    font-weight: 600;

    text-transform: uppercase;

}


.purchase-show-page .item-receiving-line strong {

    color: #594b40;

    font-size: 10px;

    font-weight: 700;

}


.purchase-show-page .item-receiving-line.remaining {

    padding-top: 4px;

    border-top: 1px solid #eee7df;

}


.purchase-show-page .item-receiving-line.remaining strong {

    color: #a66f38;

    font-size: 11px;

}


.purchase-show-page .item-complete {

    width: fit-content;

    padding: 5px 7px;

    border-radius: 7px;

    background: #e8f3eb;

    color: #4e7958;

    font-size: 8px;

    line-height: 1;

    font-weight: 700;

}


.purchase-show-page .empty-cell {

    padding: 30px 10px !important;

    color: #a4978d !important;

    text-align: center;

}


/* ================================================================
   RECORD
================================================================ */

.purchase-show-page .record-content {

    padding: 16px 18px 18px;

}


.purchase-show-page .record-table {

    width: 100%;

    min-width: 0;

    table-layout: fixed;

    border-collapse: collapse;

}


.purchase-show-page .record-table th {

    padding: 9px 8px;

    border-bottom: 1px solid #e8e0d9;

    background: #fcfaf8;

    color: #796b61;

    font-size: 9px;

    font-weight: 700;

    letter-spacing: .055em;

    text-align: left;

    text-transform: uppercase;

}


.purchase-show-page .record-table td {

    padding: 10px 8px;

    border-bottom: 1px solid #eee8e2;

    color: #6b6057;

    font-size: 10px;

    line-height: 1.4;

    vertical-align: middle;

    overflow-wrap: anywhere;

}


.purchase-show-page .record-table tbody tr:last-child td {

    border-bottom: none;

}


.purchase-show-page .record-table td strong {

    color: #594b40;

    font-weight: 700;

}


.record-user {

    display: flex;

    align-items: center;

    gap: 8px;

    min-width: 0;

}


.record-avatar {

    width: 27px;

    height: 27px;

    flex: 0 0 27px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background: var(--orange-light);

    color: var(--orange);

    font-size: 9px;

    font-weight: 700;

}


.record-user span {

    min-width: 0;

    overflow: hidden;

    color: #594b40;

    font-size: 10px;

    font-weight: 600;

    text-overflow: ellipsis;

    white-space: nowrap;

}


.record-muted {

    color: #a4978d;

    font-size: 10px;

    font-weight: 600;

}


/* ================================================================
   NOTES
================================================================ */

.purchase-show-page .form-content {

    padding: 18px;

}


.purchase-notes {

    padding: 11px 12px;

    border: 1px solid #e9dfd5;

    border-radius: 8px;

    background: #fbf8f4;

    color: #655b53;

    font-size: 10px;

    line-height: 1.55;

    white-space: pre-wrap;

}


/* ================================================================
   SUMMARY
================================================================ */

.purchase-summary-panel {

    position: sticky;

    top: 18px;

}


.purchase-show-page .summary-row,
.purchase-show-page .summary-total {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

}


.purchase-show-page .summary-row {

    padding-bottom: 14px;

    margin-bottom: 15px;

    border-bottom: 1px solid var(--border);

}


.purchase-show-page .summary-row span,
.purchase-show-page .summary-total span {

    display: block;

    color: #4c3c31;

    font-size: 12px;

    line-height: 1.35;

    font-weight: 600;

}


.purchase-show-page .summary-row small,
.purchase-show-page .summary-total small {

    display: block;

    margin-top: 3px;

    color: var(--muted);

    font-size: 9px;

    line-height: 1.35;

}


.purchase-show-page .summary-row strong {

    color: #594536;

    font-size: 16px;

    line-height: 1;

    font-weight: 700;

}


.purchase-show-page .summary-total strong {

    color: var(--orange);

    font-size: 21px;

    line-height: 1;

    font-weight: 800;

}


.supplier-summary {

    margin-top: 17px;

    padding: 10px;

    border: 1px solid #e9dfd5;

    border-radius: 8px;

    background: #fbf8f4;

}


.supplier-summary-label {

    margin-bottom: 5px;

    color: #95867b;

    font-size: 8px;

    line-height: 1.2;

    font-weight: 700;

    letter-spacing: .07em;

}


.supplier-summary > strong {

    display: block;

    margin-bottom: 5px;

    color: #594536;

    font-size: 11px;

    line-height: 1.35;

    font-weight: 700;

}


.supplier-summary > span {

    display: block;

    color: #95867b;

    font-size: 9px;

    line-height: 1.45;

}


/* ================================================================
   RECEIVE STOCK
================================================================ */

.receive-content {

    padding: 16px 18px 18px;

}


.receiving-item {

    min-width: 0;

    padding: 11px;

    border: 1px solid #e9dfd5;

    border-radius: 8px;

    background: white;

}


.receiving-item-top {

    display: flex;

    align-items: flex-start;

    justify-content: space-between;

    gap: 10px;

    margin-bottom: 12px;

}


.receiving-item-name {

    color: #4c3c31;

    font-size: 12px;

    line-height: 1.35;

    font-weight: 700;

}


.receiving-item-unit {

    margin-top: 3px;

    color: #9a8e84;

    font-size: 9px;

}


.receiving-remaining {

    flex-shrink: 0;

    max-width: 110px;

    padding: 5px 7px;

    border-radius: 7px;

    background: #fff3df;

    color: #a66f38;

    font-size: 8px;

    line-height: 1.25;

    font-weight: 700;

    text-align: right;

}


.purchase-show-page .form-group {

    margin-bottom: 15px;

}


.purchase-show-page .form-group label {

    display: block;

    margin-bottom: 6px;

    color: #4c3c31;

    font-size: 11px;

    line-height: 1.35;

    font-weight: 600;

}


.purchase-show-page .receive-quantity-group input {

    width: 100%;

    height: 39px;

    padding: 0 11px;

    border: 1px solid #ded4cb;

    border-radius: 8px;

    background: white;

    color: var(--text);

    font-family: inherit;

    font-size: 12px;

    outline: none;

    transition:
        border-color .18s ease,
        box-shadow .18s ease;

}


.purchase-show-page .receive-quantity-group input:focus {

    border-color: #d2a47b;

    box-shadow:
        0 0 0 3px
        rgba(196, 122, 58, 0.075);

}


.receiving-breakdown {

    display: grid;

    grid-template-columns:
        repeat(3, minmax(0, 1fr));

    gap: 7px;

    margin-top: 8px;

}


.receiving-stat {

    min-width: 0;

    min-height: 49px;

    display: flex;

    flex-direction: column;

    align-items: flex-start;

    justify-content: center;

    gap: 3px;

    padding: 7px 8px;

    border: 1px solid #e9dfd5;

    border-radius: 8px;

    background: white;

}


.receiving-stat span {

    color: #95867b;

    font-size: 8px;

    line-height: 1.2;

    font-weight: 700;

    letter-spacing: .04em;

    text-transform: uppercase;

}


.receiving-stat strong {

    color: #594536;

    font-size: 12px;

    line-height: 1.2;

    font-weight: 800;

}


.receiving-stat-remaining {

    border-color: #dfc8aa;

    background: #fff8ef;

}


.receiving-stat-remaining span {

    color: #8d6844;

}


.receiving-stat-remaining strong {

    color: var(--orange);

    font-size: 13px;

}


.receive-button {

    width: 100%;

    margin-top: 11px;

}


/* ================================================================
   COMPLETION
================================================================ */

.completion-panel {

    display: flex;

    align-items: flex-start;

    gap: 10px;

    padding: 15px 18px;

}


.completion-icon {

    width: 30px;

    height: 30px;

    flex: 0 0 30px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 8px;

    background: #e2efe5;

    color: #4d7658;

    font-size: 13px;

    font-weight: 700;

}


.completion-panel strong {

    display: block;

    margin-bottom: 3px;

    color: #4d7057;

    font-size: 11px;

    line-height: 1.3;

}


.completion-panel p {

    margin: 0;

    color: #829188;

    font-size: 9px;

    line-height: 1.45;

}


.cancelled-panel {

    border-color: #ead9d5 !important;

    background: #fff9f7 !important;

}


.cancelled-panel .completion-icon {

    background: #f4e5e1;

    color: #956157;

}


.cancelled-panel strong {

    color: #87574f;

}


/* ================================================================
   WORKFLOW
================================================================ */

.workflow-actions {

    display: flex;

    flex-direction: column;

    gap: 7px;

    padding: 16px 18px 18px;

}


.workflow-actions form {

    width: 100%;

    margin: 0;

}


.workflow-button {

    width: 100%;

    min-height: 37px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 6px;

    padding: 0 12px;

    border-radius: 8px;

    font-family: inherit;

    font-size: 11px;

    line-height: 1;

    font-weight: 700;

    cursor: pointer;

    transition:
        background-color .18s ease,
        border-color .18s ease,
        transform .18s ease,
        box-shadow .18s ease;

}


.workflow-button svg {

    width: 14px;

    height: 14px;

    flex-shrink: 0;

}


/* SAME ORANGE STYLE AS CREATE PURCHASE */

.workflow-primary {

    border: none;

    background:
        linear-gradient(
            135deg,
            var(--orange),
            var(--orange-dark)
        );

    color: white;

    box-shadow:
        0 4px 11px
        rgba(145, 97, 55, 0.14);

}


.workflow-primary:hover {

    background:
        linear-gradient(
            135deg,
            var(--orange-dark),
            var(--orange-dark)
        );

    color: white;

    transform: translateY(-1px);

    box-shadow:
        0 6px 14px
        rgba(145, 97, 55, 0.18);

}


/* APPROVE */

.workflow-approve {

    border: 1px solid #75a17f;

    background: #75a17f;

    color: white;

}


.workflow-approve:hover {

    border-color: #648f6e;

    background: #648f6e;

}


/* REJECT */

.workflow-reject {

    border: 1px solid #c7786f;

    background: #c7786f;

    color: white;

}


.workflow-reject:hover {

    border-color: #b86a61;

    background: #b86a61;

}


/* CANCEL */

.workflow-cancel {

    border: 1px solid #ead7d3;

    background: white;

    color: #a25d53;

}


.workflow-cancel:hover {

    border-color: #dcbfb8;

    background: #fff8f6;

}


/* ================================================================
   TABLET
================================================================ */

@media (max-width: 1100px) {

    .purchase-main-grid {

        grid-template-columns: 1fr;

    }


    .purchase-summary-panel {

        position: static;

    }

}


/* ================================================================
   MOBILE
================================================================ */

@media (max-width: 760px) {

    .purchase-show-page {

        max-width: 100%;

    }


    .purchase-show-page .topbar {

        flex-direction: column;

        align-items: flex-start;

        gap: 12px;

    }


    .purchase-show-page .date-box {

        align-self: flex-start;

    }


    .purchase-page-header {

        align-items: flex-start;

        flex-direction: column;

    }


    .purchase-header-actions {

        width: 100%;

        justify-content: flex-start;

    }


    .purchase-information-grid {

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        column-gap: 20px;

        row-gap: 18px;

    }


    /* ------------------------------------------------------------
       ITEMS MOBILE
    ------------------------------------------------------------- */

    .purchase-show-page .items-table,
    .purchase-show-page .items-table tbody,
    .purchase-show-page .items-table tr,
    .purchase-show-page .items-table td {

        display: block;

        width: 100%;

    }


    .purchase-show-page .items-table thead {

        display: none;

    }


    .purchase-show-page .items-table tr {

        padding: 10px 0;

        border-bottom: 1px solid #eee8e2;

    }


    .purchase-show-page .items-table tbody tr:last-child {

        border-bottom: none;

    }


    .purchase-show-page .items-table td {

        display: grid;

        grid-template-columns:
            105px minmax(0, 1fr);

        gap: 8px;

        padding: 5px 0;

        border: none;

    }


    .purchase-show-page .items-table td::before {

        content: attr(data-label);

        color: #96877b;

        font-size: 8px;

        line-height: 1.3;

        font-weight: 700;

        letter-spacing: .035em;

        text-transform: uppercase;

    }


    .purchase-show-page .items-table td:first-child {

        display: block;

        padding: 2px 0 8px;

    }


    .purchase-show-page .items-table td:first-child::before {

        display: none;

    }


    /* ------------------------------------------------------------
       RECORD MOBILE
    ------------------------------------------------------------- */

    .purchase-show-page .record-table,
    .purchase-show-page .record-table tbody,
    .purchase-show-page .record-table tr,
    .purchase-show-page .record-table td {

        display: block;

        width: 100%;

    }


    .purchase-show-page .record-table thead {

        display: none;

    }


    .purchase-show-page .record-table tr {

        padding: 10px 0;

        border-bottom: 1px solid #eee8e2;

    }


    .purchase-show-page .record-table tbody tr:last-child {

        border-bottom: none;

    }


    .purchase-show-page .record-table td {

        display: grid;

        grid-template-columns:
            105px minmax(0, 1fr);

        gap: 8px;

        padding: 5px 0;

        border: none;

    }


    .purchase-show-page .record-table td::before {

        content: attr(data-label);

        color: #96877b;

        font-size: 8px;

        line-height: 1.3;

        font-weight: 700;

        letter-spacing: .035em;

        text-transform: uppercase;

    }


    .purchase-show-page .record-table td[data-label="Record"] {

        padding-top: 2px;

    }


    .purchase-show-page .record-table td[data-label="Record"] strong {

        font-size: 10px;

    }


    .purchase-show-page .record-table .record-user span {

        white-space: normal;

        overflow-wrap: anywhere;

    }


    .receiving-breakdown {

        grid-template-columns:
            repeat(3, minmax(0, 1fr));

    }

}


/* ================================================================
   SMALL MOBILE
================================================================ */

@media (max-width: 480px) {

    .purchase-show-page .page-title h1 {

        font-size: 23px;

    }


    .purchase-show-page .form-panel-title {

        font-size: 17px;

    }


    .purchase-show-page .form-panel-subtitle {

        max-width: 235px;

        font-size: 11px;

    }


    .purchase-information-grid {

        grid-template-columns: 1fr;

    }


    .purchase-header-actions {

        flex-direction: column;

        align-items: stretch;

    }


    .purchase-header-actions a {

        width: 100%;

    }


    .receiving-breakdown {

        gap: 5px;

    }


    .receiving-stat {

        min-height: 47px;

        padding: 6px;

    }


    .receiving-stat span {

        font-size: 7px;

    }


    .receiving-stat strong {

        font-size: 11px;

    }


    .receiving-stat-remaining strong {

        font-size: 12px;

    }

}

</style>

@endpush