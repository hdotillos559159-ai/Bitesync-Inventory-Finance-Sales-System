@extends('layouts.app')

@section('title', 'BiteSync | Suppliers')

@section('content')

<div class="suppliers-page">

<!-- =========================================================
     SUPPLIERS TOPBAR
========================================================== -->

<div class="topbar">

    <div class="page-title">

        <small>
            Supplier Management
        </small>

        <h1>
            Suppliers
        </h1>

        <p>
            Manage your BiteSync supplier records and supplier information.
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
     SUMMARY CARDS
========================================================== -->

<section class="suppliers-stats">

    <!-- TOTAL SUPPLIERS -->

    <div class="suppliers-stat">

        <div class="suppliers-stat-left">

            <div class="suppliers-stat-label">
                TOTAL SUPPLIERS
            </div>

            <div class="suppliers-stat-note">
                All supplier records
            </div>

        </div>


        <div class="suppliers-stat-right">

            <div class="suppliers-stat-icon">
                ▦
            </div>

            <div class="suppliers-stat-value">
                {{ $totalSuppliers }}
            </div>

        </div>

    </div>


    <!-- ACTIVE SUPPLIERS -->

    <div class="suppliers-stat">

        <div class="suppliers-stat-left">

            <div class="suppliers-stat-label">
                ACTIVE SUPPLIERS
            </div>

            <div class="suppliers-stat-note">
                Currently available
            </div>

        </div>


        <div class="suppliers-stat-right">

            <div class="suppliers-stat-icon">
                ✓
            </div>

            <div class="suppliers-stat-value">
                {{ $activeSuppliers }}
            </div>

        </div>

    </div>


    <!-- INACTIVE SUPPLIERS -->

    <div class="suppliers-stat">

        <div class="suppliers-stat-left">

            <div class="suppliers-stat-label">
                INACTIVE SUPPLIERS
            </div>

            <div class="suppliers-stat-note">
                Not currently available
            </div>

        </div>


        <div class="suppliers-stat-right">

            <div class="suppliers-stat-icon">
                ×
            </div>

            <div class="suppliers-stat-value">
                {{ $inactiveSuppliers }}
            </div>

        </div>

    </div>


    <!-- ACTIVE SUPPLIER RATE -->

    <div class="suppliers-stat">

        <div class="suppliers-stat-left">

            <div class="suppliers-stat-label">
                ACTIVE SUPPLIER RATE
            </div>

            <div class="suppliers-stat-note">
                Currently active suppliers
            </div>

        </div>


        <div class="suppliers-stat-right">

            <div class="suppliers-stat-icon">
                %
            </div>

            <div class="suppliers-stat-value">

                @if ($totalSuppliers > 0)

                    {{ number_format(($activeSuppliers / $totalSuppliers) * 100, 1) }}%

                @else

                    0.0%

                @endif

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     SUPPLIER RECORDS PANEL
========================================================== -->

<div class="suppliers-panel">

    <!-- =====================================================
         PANEL HEADER
    ====================================================== -->

    <div class="suppliers-panel-header">

        <div>

            <div class="suppliers-panel-title">
                Supplier Records
            </div>

            <div class="suppliers-panel-subtitle">
                Search and manage your café supplier records.
            </div>

        </div>


        @if ($user->role === 'CEO/Admin' || $user->role === 'Procurement')

            <a
                href="{{ route('suppliers.create') }}"
                class="suppliers-add-button"
            >

                <span>
                    +
                </span>

                Add Supplier

            </a>

        @endif

    </div>


    <!-- =====================================================
         SEARCH / FILTER
    ====================================================== -->

    <form
        method="GET"
        action="{{ route('suppliers.index') }}"
        class="suppliers-filters"
    >

        <div class="suppliers-search-wrapper">

            <span class="suppliers-search-icon">
                ⌕
            </span>

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Search suppliers..."
            >

        </div>


        <select name="status">

            <option value="">
                All Status
            </option>

            <option
                value="active"
                {{ $status === 'active' ? 'selected' : '' }}
            >
                Active
            </option>

            <option
                value="on_hold"
                {{ $status === 'on_hold' ? 'selected' : '' }}
            >
                On Hold
            </option>

            <option
                value="inactive"
                {{ $status === 'inactive' ? 'selected' : '' }}
            >
                Inactive
            </option>

            <option
                value="blacklisted"
                {{ $status === 'blacklisted' ? 'selected' : '' }}
            >
                Blacklisted
            </option>

        </select>


        <button
            type="submit"
            class="suppliers-filter-button"
        >
            Filter
        </button>


        @if ($search || $status)

            <a
                href="{{ route('suppliers.index') }}"
                class="suppliers-clear-button"
            >
                Clear
            </a>

        @endif

    </form>


    <!-- =====================================================
         SUCCESS MESSAGE
    ====================================================== -->

    @if (session('success'))

        <div class="supplier-alert supplier-alert-success">

            {{ session('success') }}

        </div>

    @endif


    <!-- =====================================================
         ERROR MESSAGE
    ====================================================== -->

    @if ($errors->any())

        <div class="supplier-alert supplier-alert-error">

            {{ $errors->first() }}

        </div>

    @endif


    <!-- =====================================================
         SUPPLIER TABLE
    ====================================================== -->

    <div class="suppliers-table-wrapper">

        <table class="suppliers-table">

            <thead>

                <tr>

                    <th>
                        Supplier
                    </th>

                    <th>
                        Contact
                    </th>

                    <th>
                        Email
                    </th>

                    <th>
                        Address
                    </th>

                    <th>
                        Status
                    </th>

                    @if ($user->role === 'CEO/Admin' || $user->role === 'Procurement')

                        <th class="suppliers-actions-header">
                            Actions
                        </th>

                    @endif

                </tr>

            </thead>


            <tbody>

                @forelse ($suppliers as $supplier)

                    @php

                        $supplierStatus =
                            $supplier->status ?? 'Active';

                        $statusClass = match ($supplierStatus) {

                            'Active' =>
                                'suppliers-status-active',

                            'On Hold' =>
                                'suppliers-status-hold',

                            'Inactive' =>
                                'suppliers-status-inactive',

                            'Blacklisted' =>
                                'suppliers-status-blacklisted',

                            default =>
                                'suppliers-status-inactive',

                        };

                    @endphp


                    <tr>

                        <!-- =================================================
                             SUPPLIER
                        ================================================== -->

                        <td>

                            <div class="suppliers-item-cell">

                                <div class="suppliers-item-avatar">

                                    {{ strtoupper(substr($supplier->name ?? 'S', 0, 1)) }}

                                </div>


                                <div class="suppliers-item-info">

                                    <div class="suppliers-name">

                                        {{ $supplier->name }}

                                    </div>


                                    <div class="suppliers-code">

                                        SUP-{{ str_pad($supplier->id, 4, '0', STR_PAD_LEFT) }}

                                    </div>

                                </div>

                            </div>

                        </td>


                        <!-- =================================================
                             CONTACT
                        ================================================== -->

                        <td>

                            <div class="suppliers-contact">

                                <div class="suppliers-contact-name">

                                    {{ $supplier->contact_person ?: 'No contact person' }}

                                </div>


                                @if ($supplier->phone)

                                    <div class="suppliers-contact-phone">

                                        {{ $supplier->phone }}

                                    </div>

                                @else

                                    <div class="suppliers-muted-small">
                                        No phone number
                                    </div>

                                @endif

                            </div>

                        </td>


                        <!-- =================================================
                             EMAIL
                        ================================================== -->

                        <td>

                            @if ($supplier->email)

                                <span
                                    class="suppliers-email"
                                    title="{{ $supplier->email }}"
                                >

                                    {{ $supplier->email }}

                                </span>

                            @else

                                <span class="suppliers-muted">
                                    —
                                </span>

                            @endif

                        </td>


                        <!-- =================================================
                             ADDRESS
                        ================================================== -->

                        <td>

                            @if ($supplier->address)

                                <span
                                    class="suppliers-address"
                                    title="{{ $supplier->address }}"
                                >

                                    {{ $supplier->address }}

                                </span>

                            @else

                                <span class="suppliers-muted">
                                    —
                                </span>

                            @endif

                        </td>


                        <!-- =================================================
                             STATUS
                        ================================================== -->

                        <td>

                            <span
                                class="suppliers-status {{ $statusClass }}"
                            >

                                {{ strtoupper($supplierStatus) }}

                            </span>

                        </td>


                        <!-- =================================================
                             ACTIONS
                        ================================================== -->

                        @if ($user->role === 'CEO/Admin' || $user->role === 'Procurement')

                            <td>

                                <div class="suppliers-table-actions">

                                    <!-- VIEW -->

                                    <a
                                        href="{{ route('suppliers.show', $supplier) }}"
                                        class="supplier-icon-action supplier-view-action"
                                        title="View Supplier"
                                        aria-label="View Supplier"
                                    >
                                        ⌕
                                    </a>


                                    <!-- EDIT -->

                                    <a
                                        href="{{ route('suppliers.edit', $supplier) }}"
                                        class="supplier-icon-action supplier-edit-action"
                                        title="Edit Supplier"
                                        aria-label="Edit Supplier"
                                    >
                                        ✎
                                    </a>


                                    <!-- ON HOLD -->

                                    @if ($supplierStatus === 'Active')

                                        <form
                                            method="POST"
                                            action="{{ route('suppliers.hold', $supplier) }}"
                                            onsubmit="return confirm('Place this supplier on hold?');"
                                        >

                                            @csrf

                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="supplier-icon-action supplier-hold-action"
                                                title="Place On Hold"
                                                aria-label="Place On Hold"
                                            >
                                                ⏸
                                            </button>

                                        </form>

                                    @endif


                                    <!-- ACTIVATE -->

                                    @if (
                                        $supplierStatus === 'On Hold' ||
                                        $supplierStatus === 'Inactive'
                                    )

                                        <form
                                            method="POST"
                                            action="{{ route('suppliers.activate', $supplier) }}"
                                            onsubmit="return confirm('Activate this supplier?');"
                                        >

                                            @csrf

                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="supplier-icon-action supplier-activate-action"
                                                title="Activate Supplier"
                                                aria-label="Activate Supplier"
                                            >
                                                ▶
                                            </button>

                                        </form>

                                    @endif


                                    <!-- BLACKLIST -->

                                    @if ($supplierStatus !== 'Blacklisted')

                                        <form
                                            method="POST"
                                            action="{{ route('suppliers.blacklist', $supplier) }}"
                                            onsubmit="return confirm('Blacklist this supplier?');"
                                        >

                                            @csrf

                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="supplier-icon-action supplier-blacklist-action"
                                                title="Blacklist Supplier"
                                                aria-label="Blacklist Supplier"
                                            >
                                                ⊘
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        @endif

                    </tr>


                @empty

                    <tr>

                        <td
                            colspan="{{ ($user->role === 'CEO/Admin' || $user->role === 'Procurement') ? 6 : 5 }}"
                            class="suppliers-empty-state"
                        >

                            <div class="suppliers-empty-icon">
                                ▦
                            </div>


                            <div class="suppliers-empty-title">
                                No supplier records found
                            </div>


                            <div class="suppliers-empty-description">

                                @if ($search || $status)

                                    Try changing your search or filter.

                                @else

                                    Your supplier records will appear here.

                                @endif

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    <!-- =====================================================
         PAGINATION
    ====================================================== -->

    @if ($suppliers->hasPages())

        <div class="suppliers-pagination-wrapper">

            {{ $suppliers->links() }}

        </div>

    @endif

</div>

</div>

@endsection


@push('styles')

<style>

/* =========================================================
   SUPPLIERS PAGE
========================================================= */

.suppliers-page {

    width: 100%;
}


/* =========================================================
   PAGE TITLE
========================================================= */

.suppliers-page .page-title h1 {

    font-size:
        clamp(1.6rem, 2vw, 1.9rem);

    line-height: 1.15;

    letter-spacing: -0.04rem;
}


/* =========================================================
   SUMMARY CARDS
========================================================= */

.suppliers-stats {

    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 17px;

    margin-bottom: 17px;
}


.suppliers-stat {

    min-height: 125px;

    display: flex;

    align-items: flex-start;

    justify-content: space-between;

    gap: 15px;

    padding:
        16px
        17px
        15px;

    border:
        1px solid var(--border);

    border-radius: 15px;

    background: white;

    box-shadow:
        0 5px 18px
        rgba(43, 31, 23, 0.045);

    position: relative;

    overflow: hidden;
}


/* =========================================================
   LEFT ACCENT
========================================================= */

.suppliers-stat::before {

    content: "";

    position: absolute;

    top: 0;
    left: 0;
    bottom: 0;

    width: 3px;

    background:
        linear-gradient(
            180deg,
            var(--orange),
            #e2a16c
        );
}


/* =========================================================
   LEFT SIDE
========================================================= */

.suppliers-stat-left {

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


/* =========================================================
   LABEL
========================================================= */

.suppliers-stat-label {

    color: var(--muted);

    font-size: 0.6875rem;

    line-height: 1.3;

    font-weight: 800;

    letter-spacing: 0.045rem;

    white-space: nowrap;
}


/* =========================================================
   NOTE
========================================================= */

.suppliers-stat-note {

    margin-top: 58px;

    color: #9d958f;

    font-size: 0.6875rem;

    line-height: 1.4;

    max-width: 155px;
}


/* =========================================================
   RIGHT SIDE
========================================================= */

.suppliers-stat-right {

    min-width: 82px;

    display: flex;

    flex-direction: column;

    align-items: flex-end;

    justify-content: flex-start;

    padding-top: 3px;

    flex-shrink: 0;
}


/* =========================================================
   ICON
========================================================= */

.suppliers-stat-icon {

    width: 34px;

    height: 34px;

    display: flex;

    align-items: center;

    justify-content: center;

    flex-shrink: 0;

    border-radius: 9px;

    background:
        linear-gradient(
            135deg,
            #fbf1e7,
            #f4e3d4
        );

    color: var(--orange);

    font-size: 0.8125rem;

    font-weight: 800;
}


/* =========================================================
   VALUE
========================================================= */

.suppliers-stat-value {

    margin-top: 13px;

    color: var(--dark);

    font-size:
        clamp(1.45rem, 1.8vw, 1.75rem);

    line-height: 1;

    font-weight: 800;

    text-align: right;

    white-space: nowrap;
}


/* =========================================================
   SUPPLIER PANEL
========================================================= */

.suppliers-panel {

    background: white;

    border:
        1px solid var(--border);

    border-radius: 15px;

    overflow: hidden;

    box-shadow:
        0 4px 16px
        rgba(43, 31, 23, 0.035);
}


/* =========================================================
   PANEL HEADER
========================================================= */

.suppliers-panel-header {

    min-height: 65px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 17px;

    padding:
        14px
        18px;

    border-bottom:
        1px solid var(--border);
}


.suppliers-panel-title {

    color: var(--dark);

    font-size: 0.875rem;

    line-height: 1.3;

    font-weight: 700;
}


.suppliers-panel-subtitle {

    margin-top: 3px;

    color: var(--muted);

    font-size: 0.6875rem;

    line-height: 1.4;
}


/* =========================================================
   ADD SUPPLIER
========================================================= */

.suppliers-add-button {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 5px;

    min-height: 32px;

    padding:
        0
        10px;

    border-radius: 8px;

    background:
        linear-gradient(
            135deg,
            var(--orange),
            var(--orange-dark)
        );

    color: white;

    text-decoration: none;

    font-size: 0.6875rem;

    line-height: 1.2;

    font-weight: 700;

    box-shadow:
        0 3px 9px
        rgba(168, 95, 40, 0.12);

    transition:
        background 0.18s ease,
        box-shadow 0.18s ease;
}


.suppliers-add-button:hover {

    color: white;

    background:
        linear-gradient(
            135deg,
            var(--orange-dark),
            var(--orange-dark)
        );

    box-shadow:
        0 4px 10px
        rgba(168, 95, 40, 0.15);
}


.suppliers-add-button span {

    font-size: 0.8125rem;

    line-height: 1;
}


/* =========================================================
   FILTERS
========================================================= */

.suppliers-filters {

    display: flex;

    align-items: center;

    gap: 8px;

    padding:
        12px
        18px;

    background: var(--card-soft);

    border-bottom:
        1px solid var(--border);
}


.suppliers-search-wrapper {

    flex: 1;

    position: relative;

    min-width: 180px;
}


.suppliers-search-wrapper input {

    width: 100%;

    height: 35px;

    padding:
        0
        11px
        0
        34px;

    border:
        1px solid var(--border);

    border-radius: 8px;

    background: white;

    color: var(--text);

    font-family: inherit;

    font-size: 0.75rem;

    outline: none;

    transition:
        border-color 0.18s ease,
        box-shadow 0.18s ease;
}


.suppliers-search-wrapper input:focus {

    border-color: #d5a77d;

    box-shadow:
        0 0 0 3px
        rgba(196, 122, 58, 0.07);
}


.suppliers-search-wrapper input::placeholder {

    color: #aaa19a;
}


.suppliers-search-icon {

    position: absolute;

    left: 11px;

    top: 50%;

    transform:
        translateY(-50%);

    color: var(--muted);

    font-size: 0.875rem;

    pointer-events: none;
}


/* =========================================================
   FILTER SELECT
========================================================= */

.suppliers-filters select {

    height: 35px;

    min-width: 125px;

    padding:
        0
        10px;

    border:
        1px solid var(--border);

    border-radius: 8px;

    background: white;

    color: var(--text);

    font-family: inherit;

    font-size: 0.75rem;

    outline: none;

    cursor: pointer;
}


/* =========================================================
   FILTER BUTTON
========================================================= */

.suppliers-filter-button,
.suppliers-clear-button {

    height: 35px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    padding:
        0
        11px;

    border-radius: 8px;

    font-family: inherit;

    font-size: 0.75rem;

    font-weight: 700;

    line-height: 1.2;

    text-decoration: none;

    cursor: pointer;
}


.suppliers-filter-button {

    border: none;

    background: var(--dark);

    color: white;

    transition:
        background-color 0.18s ease;
}


.suppliers-filter-button:hover {

    background: var(--dark-soft);
}


.suppliers-clear-button {

    border:
        1px solid var(--border);

    background: white;

    color: var(--muted);

    transition:
        background-color 0.18s ease,
        color 0.18s ease;
}


.suppliers-clear-button:hover {

    background: #faf7f3;

    color: var(--dark);
}


/* =========================================================
   ALERTS
========================================================= */

.supplier-alert {

    margin:
        12px
        18px
        0;

    padding:
        9px
        12px;

    border-radius: 8px;

    font-size: 0.6875rem;

    font-weight: 650;
}


.supplier-alert-success {

    background: #edf8f0;

    border:
        1px solid #cfe5d5;

    color: #39704d;
}


.supplier-alert-error {

    background: #fff0ee;

    border:
        1px solid #eccfcb;

    color: #9e4942;
}


/* =========================================================
   TABLE
========================================================= */

.suppliers-table-wrapper {

    width: 100%;

    overflow-x: auto;
}


.suppliers-table {

    width: 100%;

    min-width: 950px;

    border-collapse: collapse;
}


.suppliers-table th {

    padding:
        10px
        12px;

    background: #fbf9f6;

    color: var(--muted);

    border-bottom:
        1px solid var(--border);

    text-align: left;

    font-size: 0.625rem;

    line-height: 1.3;

    font-weight: 800;

    text-transform: uppercase;

    letter-spacing: 0.04rem;

    white-space: nowrap;
}


.suppliers-table td {

    padding:
        11px
        12px;

    color: #625951;

    border-bottom:
        1px solid #f0ebe6;

    font-size: 0.75rem;

    line-height: 1.4;

    vertical-align: middle;
}


.suppliers-table tbody tr {

    background: white;

    transition:
        background-color 0.15s ease;
}


.suppliers-table tbody tr:hover {

    background: #fdfaf7;
}


/* =========================================================
   SUPPLIER ITEM
========================================================= */

.suppliers-item-cell {

    display: flex;

    align-items: center;

    gap: 10px;

    min-width: 190px;
}


.suppliers-item-avatar {

    width: 38px;

    height: 38px;

    flex-shrink: 0;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 50%;

    background:
        linear-gradient(
            135deg,
            #fbf1e7,
            #f1dfce
        );

    border:
        1px solid #f0e6db;

    color: var(--orange-dark);

    font-size: 0.8125rem;

    line-height: 1;

    font-weight: 800;
}


.suppliers-item-info {

    min-width: 0;
}


.suppliers-name {

    color: var(--dark);

    font-size: 0.75rem;

    line-height: 1.4;

    font-weight: 700;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;

    max-width: 220px;
}


.suppliers-code {

    margin-top: 2px;

    color: var(--muted);

    font-family: monospace;

    font-size: 0.625rem;

    line-height: 1.3;
}


/* =========================================================
   CONTACT
========================================================= */

.suppliers-contact {

    min-width: 145px;
}


.suppliers-contact-name {

    color: var(--dark);

    font-size: 0.6875rem;

    line-height: 1.35;

    font-weight: 700;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;

    max-width: 175px;
}


.suppliers-contact-phone {

    margin-top: 3px;

    color: var(--muted);

    font-size: 0.625rem;

    line-height: 1.35;
}


.suppliers-muted-small {

    margin-top: 3px;

    color: #aaa19a;

    font-size: 0.625rem;
}


/* =========================================================
   EMAIL
========================================================= */

.suppliers-email {

    display: inline-block;

    max-width: 210px;

    color: #625951;

    font-size: 0.6875rem;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;
}


/* =========================================================
   ADDRESS
========================================================= */

.suppliers-address {

    display: inline-block;

    max-width: 220px;

    color: #756b64;

    font-size: 0.6875rem;

    line-height: 1.4;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;
}


.suppliers-muted {

    color: #a39b95;

    font-size: 0.75rem;
}


/* =========================================================
   STATUS
========================================================= */

.suppliers-status {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 4px;

    padding:
        4px
        7px;

    border-radius: 7px;

    font-size: 0.625rem;

    line-height: 1.2;

    font-weight: 800;

    white-space: nowrap;

    letter-spacing: 0.015rem;
}


.suppliers-status::before {

    content: "";

    width: 5px;

    height: 5px;

    flex-shrink: 0;

    border-radius: 50%;

    background: currentColor;
}


.suppliers-status-active {

    color: var(--green);

    background: var(--green-light);
}


.suppliers-status-hold {

    color: #a36a12;

    background: #fff5df;
}


.suppliers-status-inactive {

    color: var(--muted);

    background: #f1eeeb;
}


.suppliers-status-blacklisted {

    color: #9b3d3d;

    background: #fbeeee;
}


/* =========================================================
   ACTIONS
========================================================= */

.suppliers-actions-header {

    text-align: center !important;
}


.suppliers-table-actions {

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 2px;

    white-space: nowrap;
}


.suppliers-table-actions form {

    display: inline-flex;

    margin: 0;

    padding: 0;
}


/* =========================================================
   ICON-ONLY ACTIONS
========================================================= */

.supplier-icon-action {

    width: 27px;

    height: 27px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    padding: 0;

    margin: 0;

    border: none;

    background: transparent;

    border-radius: 6px;

    font-family: inherit;

    font-size: 0.75rem;

    line-height: 1;

    text-decoration: none;

    cursor: pointer;

    transition:
        background-color 0.18s ease,
        color 0.18s ease,
        transform 0.18s ease;
}


.supplier-icon-action:hover {

    transform:
        translateY(-1px);
}


/* =========================================================
   VIEW
========================================================= */

.supplier-view-action {

    color: #756b63;
}


.supplier-view-action:hover {

    background: #f3f0ed;

    color: var(--dark);
}


/* =========================================================
   EDIT
========================================================= */

.supplier-edit-action {

    color: #806247;
}


.supplier-edit-action:hover {

    background: #faf2e9;

    color: #68482f;
}


/* =========================================================
   HOLD
========================================================= */

.supplier-hold-action {

    color: #a36a12;
}


.supplier-hold-action:hover {

    background: #fff5df;

    color: #85530a;
}


/* =========================================================
   ACTIVATE
========================================================= */

.supplier-activate-action {

    color: var(--green);
}


.supplier-activate-action:hover {

    background: var(--green-light);

    color: #2e6c46;
}


/* =========================================================
   BLACKLIST
========================================================= */

.supplier-blacklist-action {

    color: #a34b4b;
}


.supplier-blacklist-action:hover {

    background: #fbeeee;

    color: #843535;
}


/* =========================================================
   EMPTY STATE
========================================================= */

.suppliers-empty-state {

    padding:
        55px
        20px !important;

    text-align: center !important;
}


.suppliers-empty-icon {

    width: 46px;

    height: 46px;

    margin:
        0
        auto
        11px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 12px;

    background: var(--orange-light);

    color: var(--orange);

    font-size: 1.05rem;
}


.suppliers-empty-title {

    color: var(--dark);

    font-size: 0.8125rem;

    font-weight: 700;
}


.suppliers-empty-description {

    margin-top: 4px;

    color: var(--muted);

    font-size: 0.6875rem;

    line-height: 1.4;
}


/* =========================================================
   PAGINATION
   MATCHES INVENTORY / PRODUCTS / SALES
========================================================= */

.suppliers-pagination-wrapper {

    padding:
        13px
        18px;

    border-top:
        1px solid var(--border);
}


.suppliers-pagination-wrapper nav {

    display: flex;

    align-items: center;

    justify-content: center;

    width: 100%;
}


.suppliers-pagination-wrapper nav > div {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 12px;

    width: 100%;
}


.suppliers-pagination-wrapper nav > div > div {

    display: flex;

    align-items: center;

    gap: 4px;
}


.suppliers-pagination-wrapper nav a,
.suppliers-pagination-wrapper nav button,
.suppliers-pagination-wrapper nav span[aria-current="page"],
.suppliers-pagination-wrapper nav span[aria-disabled="true"] {

    min-width: 30px;

    height: 30px;

    padding:
        0
        9px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    border:
        1px solid var(--border);

    border-radius: 7px;

    background: white;

    color: var(--muted);

    font-family: inherit;

    font-size: 0.6875rem;

    font-weight: 700;

    line-height: 1;

    text-decoration: none;
}


.suppliers-pagination-wrapper nav a:hover {

    border-color: #d5a77d;

    background: #faf7f3;

    color: var(--orange);
}


.suppliers-pagination-wrapper nav span[aria-current="page"] {

    border-color: var(--orange);

    background: var(--orange);

    color: white;
}


.suppliers-pagination-wrapper nav span[aria-current="page"] > span {

    color: white;
}


.suppliers-pagination-wrapper nav span[aria-disabled="true"] {

    color: #b8b0aa;

    background: #faf9f7;

    cursor: default;
}


.suppliers-pagination-wrapper nav svg {

    width: 13px;

    height: 13px;
}


.suppliers-pagination-wrapper nav p {

    margin: 0;

    color: var(--muted);

    font-size: 0.6875rem;

    line-height: 1.4;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1200px) {

    .suppliers-stats {

        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

}


@media (max-width: 700px) {

    .suppliers-stats {

        grid-template-columns: 1fr;
    }


    .suppliers-stat {

        min-height: 115px;
    }


    .suppliers-stat-left {

        padding-top: 1px;
    }


    .suppliers-stat-right {

        min-width: 72px;

        padding-top: 3px;
    }


    .suppliers-stat-value {

        font-size: 1.45rem;
    }


    .suppliers-stat-note {

        margin-top: 55px;
    }


    .suppliers-panel-header {

        align-items: flex-start;

        flex-direction: column;
    }


    .suppliers-add-button {

        width: 100%;
    }


    .suppliers-filters {

        align-items: stretch;

        flex-direction: column;
    }


    .suppliers-search-wrapper {

        width: 100%;
    }


    .suppliers-filters select,
    .suppliers-filter-button,
    .suppliers-clear-button {

        width: 100%;
    }


    .suppliers-table-actions {

        justify-content: flex-start;
    }


    .suppliers-pagination-wrapper {

        padding:
            12px;
    }


    .suppliers-pagination-wrapper nav > div {

        flex-direction: column;

        align-items: center;
    }

}

</style>

@endpush