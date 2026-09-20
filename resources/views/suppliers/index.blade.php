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
                Manage your BiteSync supplier and purchasing records.
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
         SUCCESS MESSAGE
    ========================================================== -->

    @if (session('success'))

        <div class="supplier-alert supplier-alert-success">

            <span class="supplier-alert-icon">
                ✓
            </span>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    <!-- =========================================================
         ERROR MESSAGE
    ========================================================== -->

    @if ($errors->any())

        <div class="supplier-alert supplier-alert-error">

            <span class="supplier-alert-icon">
                !
            </span>

            <span>
                Please check the form and correct the highlighted information.
            </span>

        </div>

    @endif


    <!-- =========================================================
         SUMMARY CARDS
    ========================================================== -->

    <section class="supplier-stats">

        <!-- TOTAL SUPPLIERS -->

        <div class="supplier-stat">

            <div>

                <div class="supplier-stat-label">
                    TOTAL SUPPLIERS
                </div>

                <div class="supplier-stat-value">
                    {{ $totalSuppliers }}
                </div>

                <div class="supplier-stat-note">
                    All supplier records
                </div>

            </div>

            <div class="supplier-stat-icon">
                ♧
            </div>

        </div>


        <!-- ACTIVE SUPPLIERS -->

        <div class="supplier-stat">

            <div>

                <div class="supplier-stat-label">
                    ACTIVE SUPPLIERS
                </div>

                <div class="supplier-stat-value">
                    {{ $activeSuppliers }}
                </div>

                <div class="supplier-stat-note">
                    Currently available
                </div>

            </div>

            <div class="supplier-stat-icon">
                ✓
            </div>

        </div>


        <!-- INACTIVE SUPPLIERS -->

        <div class="supplier-stat">

            <div>

                <div class="supplier-stat-label">
                    INACTIVE SUPPLIERS
                </div>

                <div class="supplier-stat-value">
                    {{ $inactiveSuppliers }}
                </div>

                <div class="supplier-stat-note">
                    Not currently available
                </div>

            </div>

            <div class="supplier-stat-icon">
                —
            </div>

        </div>


        <!-- ACTIVE SUPPLIER RATE -->

        <div class="supplier-stat">

            <div>

                <div class="supplier-stat-label">
                    ACTIVE SUPPLIER RATE
                </div>

                <div class="supplier-stat-value supplier-rate">

                    @if ($totalSuppliers > 0)

                        {{ number_format(
                            ($activeSuppliers / $totalSuppliers) * 100,
                            1
                        ) }}%

                    @else

                        0%

                    @endif

                </div>

                <div class="supplier-stat-note">
                    Suppliers currently active
                </div>

            </div>

            <div class="supplier-stat-icon">
                %
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


            <!-- ADD SUPPLIER -->

            @if (
                in_array(
                    $user->role,
                    ['CEO/Admin', 'Procurement'],
                    true
                )
            )

                <a
                    href="{{ route('suppliers.create') }}"
                    class="supplier-add-button"
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
            class="supplier-filters"
        >

            <!-- SEARCH -->

            <div class="supplier-search-wrapper">

                <span class="supplier-search-icon">
                    ⌕
                </span>

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search suppliers..."
                >

            </div>


            <!-- STATUS -->

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
                    value="inactive"
                    {{ $status === 'inactive' ? 'selected' : '' }}
                >
                    Inactive
                </option>

            </select>


            <!-- FILTER -->

            <button
                type="submit"
                class="supplier-filter-button"
            >
                Filter
            </button>


            <!-- CLEAR -->

            @if ($search || $status)

                <a
                    href="{{ route('suppliers.index') }}"
                    class="supplier-clear-button"
                >
                    Clear
                </a>

            @endif

        </form>


        <!-- =====================================================
             SUPPLIER TABLE
        ====================================================== -->

        <div class="supplier-table-wrapper">

            <table class="suppliers-table">

                <thead>

                    <tr>

                        <th>
                            Supplier
                        </th>

                        <th>
                            Contact Person
                        </th>

                        <th>
                            Phone
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

                        <th class="supplier-actions-header">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($suppliers as $supplier)

                        <tr>

                            <!-- SUPPLIER -->

                            <td>

                                <div class="supplier-name">
                                    {{ $supplier->name }}
                                </div>

                            </td>


                            <!-- CONTACT PERSON -->

                            <td>

                                @if ($supplier->contact_person)

                                    <div class="supplier-contact">
                                        {{ $supplier->contact_person }}
                                    </div>

                                @else

                                    <span class="supplier-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            <!-- PHONE -->

                            <td>

                                @if ($supplier->phone)

                                    <span class="supplier-phone">
                                        {{ $supplier->phone }}
                                    </span>

                                @else

                                    <span class="supplier-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            <!-- EMAIL -->

                            <td>

                                @if ($supplier->email)

                                    <span class="supplier-email">
                                        {{ $supplier->email }}
                                    </span>

                                @else

                                    <span class="supplier-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            <!-- ADDRESS -->

                            <td>

                                @if ($supplier->address)

                                    <div class="supplier-address">
                                        {{ $supplier->address }}
                                    </div>

                                @else

                                    <span class="supplier-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            <!-- STATUS -->

                            <td>

                                @if ($supplier->status === 'Active')

                                    <span class="supplier-status supplier-status-active">
                                        ACTIVE
                                    </span>

                                @else

                                    <span class="supplier-status supplier-status-inactive">
                                        INACTIVE
                                    </span>

                                @endif

                            </td>


                            <!-- ACTIONS -->

                            <td>

                                <div class="supplier-table-actions">

                                    <!-- VIEW -->

                                    <a
                                        href="{{ route('suppliers.show', $supplier) }}"
                                        class="supplier-view-button"
                                    >

                                        <span>
                                            ◉
                                        </span>

                                        View

                                    </a>


                                    <!-- CEO / PROCUREMENT -->

                                    @if (
                                        in_array(
                                            $user->role,
                                            ['CEO/Admin', 'Procurement'],
                                            true
                                        )
                                    )

                                        <!-- EDIT -->

                                        <a
                                            href="{{ route('suppliers.edit', $supplier) }}"
                                            class="supplier-edit-button"
                                        >

                                            <span>
                                                ✎
                                            </span>

                                            Edit

                                        </a>


                                        <!-- DEACTIVATE -->

                                        @if ($supplier->status === 'Active')

                                            <form
                                                method="POST"
                                                action="{{ route('suppliers.destroy', $supplier) }}"
                                                class="supplier-inline-form"
                                                onsubmit="return confirm('Are you sure you want to deactivate this supplier?');"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="supplier-deactivate-button"
                                                >

                                                    <span>
                                                        −
                                                    </span>

                                                    Deactivate

                                                </button>

                                            </form>


                                        <!-- ACTIVATE -->

                                        @else

                                            <form
                                                method="POST"
                                                action="{{ route('suppliers.activate', $supplier) }}"
                                                class="supplier-inline-form"
                                                onsubmit="return confirm('Do you want to reactivate this supplier?');"
                                            >

                                                @csrf

                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="supplier-activate-button"
                                                >

                                                    <span>
                                                        ✓
                                                    </span>

                                                    Activate

                                                </button>

                                            </form>

                                        @endif

                                    @endif

                                </div>

                            </td>

                        </tr>


                    @empty

                        <!-- EMPTY STATE -->

                        <tr>

                            <td
                                colspan="7"
                                class="supplier-empty-state"
                            >

                                <div class="supplier-empty-icon">
                                    ♧
                                </div>

                                <div class="supplier-empty-title">
                                    No supplier records found
                                </div>

                                <div class="supplier-empty-description">

                                    @if ($search || $status)

                                        Try changing your search
                                        or filter.

                                    @else

                                        Your supplier records
                                        will appear here.

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

            <div class="supplier-pagination-wrapper">

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
   SUPPLIER PAGE TITLE
   Matches Products and Inventory
========================================================= */

.suppliers-page .page-title h1 {

    font-size: clamp(
        1.75rem,
        2.2vw,
        2rem
    );

    line-height: 1.15;

    letter-spacing: -0.045rem;

}


/* =========================================================
   ALERTS
========================================================= */

.supplier-alert {

    display: flex;

    align-items: center;

    gap: 10px;

    margin-bottom: 18px;

    padding: 12px 15px;

    border: 1px solid var(--border);

    border-radius: 10px;

    font-size: 0.875rem;

    line-height: 1.4;

    font-weight: 600;

}


.supplier-alert-success {

    background: var(--green-light);

    border-color: #d5e7d8;

    color: #397548;

}


.supplier-alert-error {

    background: var(--red-light);

    border-color: #ecd0cc;

    color: #a45348;

}


.supplier-alert-icon {

    width: 24px;

    height: 24px;

    display: flex;

    align-items: center;

    justify-content: center;

    flex-shrink: 0;

    border-radius: 7px;

    background: rgba(255,255,255,0.65);

    font-size: 0.8125rem;

    font-weight: 800;

}


/* =========================================================
   SUMMARY CARDS
========================================================= */

.supplier-stats {

    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 17px;

    margin-bottom: 22px;

}


.supplier-stat {

    min-height: 135px;

    display: flex;

    align-items: flex-start;

    justify-content: space-between;

    gap: 15px;

    padding: 20px;

    border: 1px solid var(--border);

    border-radius: 17px;

    background: white;

    box-shadow:
        0 7px 22px
        rgba(43, 31, 23, 0.055);

    position: relative;

    overflow: hidden;

}


.supplier-stat::before {

    content: "";

    position: absolute;

    top: 0;

    left: 0;

    bottom: 0;

    width: 4px;

    background:
        linear-gradient(
            180deg,
            var(--orange),
            #e2a16c
        );

}


.supplier-stat-label {

    color: var(--muted);

    font-size: 0.8125rem;

    line-height: 1.3;

    font-weight: 800;

    letter-spacing: 0.05rem;

}


/* =========================================================
   KPI VALUE
   Matches Products / Inventory = 30px
========================================================= */

.supplier-stat-value {

    margin-top: 14px;

    color: var(--dark);

    font-size: 1.875rem;

    line-height: 1;

    font-weight: 800;

}


/* =========================================================
   ACTIVE SUPPLIER RATE
========================================================= */

.supplier-rate {

    font-size: 1.875rem;

}


.supplier-stat-note {

    margin-top: 8px;

    color: #9d958f;

    font-size: 0.8125rem;

    line-height: 1.4;

}


.supplier-stat-icon {

    width: 40px;

    height: 40px;

    display: flex;

    align-items: center;

    justify-content: center;

    flex-shrink: 0;

    border-radius: 11px;

    background:
        linear-gradient(
            135deg,
            #fbf1e7,
            #f4e3d4
        );

    color: var(--orange);

    font-size: 0.875rem;

    font-weight: 800;

}


/* =========================================================
   SUPPLIER PANEL
========================================================= */

.suppliers-panel {

    background: white;

    border: 1px solid var(--border);

    border-radius: 17px;

    overflow: hidden;

    box-shadow:
        0 5px 18px
        rgba(43, 31, 23, 0.035);

}


/* =========================================================
   PANEL HEADER
========================================================= */

.suppliers-panel-header {

    min-height: 75px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    padding: 17px 21px;

    border-bottom: 1px solid var(--border);

}


.suppliers-panel-title {

    color: var(--dark);

    font-size: 1rem;

    line-height: 1.3;

    font-weight: 700;

}


.suppliers-panel-subtitle {

    margin-top: 4px;

    color: var(--muted);

    font-size: 0.8125rem;

    line-height: 1.4;

}


/* =========================================================
   ADD SUPPLIER
========================================================= */

.supplier-add-button {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 6px;

    padding: 8px 12px;

    border-radius: 8px;

    background:
        linear-gradient(
            135deg,
            var(--orange),
            var(--orange-dark)
        );

    color: white;

    text-decoration: none;

    font-size: 0.8125rem;

    line-height: 1.2;

    font-weight: 700;

    box-shadow:
        0 4px 11px
        rgba(168, 95, 40, 0.14);

    transition:
        background 0.18s ease,
        box-shadow 0.18s ease;

}


.supplier-add-button:hover {

    color: white;

    background:
        linear-gradient(
            135deg,
            var(--orange-dark),
            var(--orange-dark)
        );

    box-shadow:
        0 5px 12px
        rgba(168, 95, 40, 0.16);

}


.supplier-add-button span {

    font-size: 0.9375rem;

    line-height: 1;

}


/* =========================================================
   FILTERS
========================================================= */

.supplier-filters {

    display: flex;

    align-items: center;

    gap: 10px;

    padding: 15px 21px;

    background: var(--card-soft);

    border-bottom: 1px solid var(--border);

}


.supplier-search-wrapper {

    flex: 1;

    position: relative;

    min-width: 180px;

}


.supplier-search-wrapper input {

    width: 100%;

    height: 37px;

    padding: 0 13px 0 37px;

    border: 1px solid var(--border);

    border-radius: 8px;

    background: white;

    color: var(--text);

    font-family: inherit;

    font-size: 0.8125rem;

    outline: none;

    transition:
        border-color 0.18s ease,
        box-shadow 0.18s ease;

}


.supplier-search-wrapper input:focus {

    border-color: #d5a77d;

    box-shadow:
        0 0 0 3px
        rgba(196, 122, 58, 0.08);

}


.supplier-search-wrapper input::placeholder {

    color: #aaa19a;

}


.supplier-search-icon {

    position: absolute;

    left: 13px;

    top: 50%;

    transform: translateY(-50%);

    color: var(--muted);

    font-size: 0.9375rem;

    pointer-events: none;

}


.supplier-filters select {

    height: 37px;

    min-width: 135px;

    padding: 0 11px;

    border: 1px solid var(--border);

    border-radius: 8px;

    background: white;

    color: var(--text);

    font-family: inherit;

    font-size: 0.8125rem;

    outline: none;

    cursor: pointer;

}


.supplier-filter-button,
.supplier-clear-button {

    height: 37px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    padding: 0 13px;

    border-radius: 8px;

    font-family: inherit;

    font-size: 0.8125rem;

    font-weight: 700;

    line-height: 1.2;

    text-decoration: none;

    cursor: pointer;

}


.supplier-filter-button {

    border: none;

    background: var(--dark);

    color: white;

    transition: background-color 0.18s ease;

}


.supplier-filter-button:hover {

    background: var(--dark-soft);

}


.supplier-clear-button {

    border: 1px solid var(--border);

    background: white;

    color: var(--muted);

    transition:
        background-color 0.18s ease,
        color 0.18s ease;

}


.supplier-clear-button:hover {

    background: #faf7f3;

    color: var(--dark);

}


/* =========================================================
   TABLE
========================================================= */

.supplier-table-wrapper {

    width: 100%;

    overflow-x: auto;

}


.suppliers-table {

    width: 100%;

    min-width: 1050px;

    border-collapse: collapse;

}


.suppliers-table th {

    padding: 12px 15px;

    background: #fbf9f6;

    color: var(--muted);

    border-bottom: 1px solid var(--border);

    text-align: left;

    font-size: 0.6875rem;

    line-height: 1.3;

    font-weight: 800;

    text-transform: uppercase;

    letter-spacing: 0.045rem;

    white-space: nowrap;

}


.suppliers-table td {

    padding: 13px 15px;

    color: #625951;

    border-bottom: 1px solid #f0ebe6;

    font-size: 0.8125rem;

    line-height: 1.4;

    vertical-align: middle;

}


.suppliers-table tbody tr {

    background: white;

}


.suppliers-table tbody tr:hover {

    background: #fdfaf7;

}


/* =========================================================
   SUPPLIER INFORMATION
========================================================= */

.supplier-name {

    color: var(--dark);

    font-size: 0.8125rem;

    line-height: 1.4;

    font-weight: 700;

}


.supplier-contact {

    color: #625951;

    font-size: 0.8125rem;

}


.supplier-phone {

    color: #625951;

    font-size: 0.8125rem;

    white-space: nowrap;

}


.supplier-email {

    color: #625951;

    font-size: 0.8125rem;

}


.supplier-address {

    max-width: 220px;

    color: #625951;

    font-size: 0.8125rem;

    line-height: 1.4;

}


.supplier-muted {

    color: #a39b95;

    font-size: 0.8125rem;

}


/* =========================================================
   STATUS
========================================================= */

.supplier-status {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    padding: 5px 8px;

    border-radius: 7px;

    font-size: 0.6875rem;

    line-height: 1.2;

    font-weight: 800;

    white-space: nowrap;

    letter-spacing: 0.02rem;

}


.supplier-status-active {

    color: var(--green);

    background: var(--green-light);

}


.supplier-status-inactive {

    color: var(--muted);

    background: #f1eeeb;

}


/* =========================================================
   ACTIONS
========================================================= */

.supplier-actions-header {

    text-align: center !important;

}


.supplier-table-actions {

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 5px;

    white-space: nowrap;

}


.supplier-inline-form {

    display: inline;

    margin: 0;

}


/* =========================================================
   ACTION BUTTONS
========================================================= */

.supplier-view-button,
.supplier-edit-button,
.supplier-deactivate-button,
.supplier-activate-button {

    min-width: 52px;

    height: 28px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 4px;

    padding: 0 8px;

    border-radius: 7px;

    font-family: inherit;

    font-size: 0.6875rem;

    line-height: 1.2;

    font-weight: 700;

    text-decoration: none;

    cursor: pointer;

    transition:
        background-color 0.18s ease,
        border-color 0.18s ease,
        color 0.18s ease;

}


.supplier-view-button span,
.supplier-edit-button span,
.supplier-deactivate-button span,
.supplier-activate-button span {

    font-size: 0.6875rem;

    line-height: 1;

}


/* =========================================================
   VIEW
========================================================= */

.supplier-view-button {

    border: 1px solid #dfd0c3;

    background: #faf7f3;

    color: #79583f;

}


.supplier-view-button:hover {

    background: #f5eee7;

    border-color: #cdb49f;

    color: #68482f;

}


/* =========================================================
   EDIT
========================================================= */

.supplier-edit-button {

    border: 1px solid #e1d4c8;

    background: white;

    color: #7d5b42;

}


.supplier-edit-button:hover {

    background: #faf5ef;

    border-color: #cdb49f;

    color: #68482f;

}


/* =========================================================
   DEACTIVATE
========================================================= */

.supplier-deactivate-button {

    border: 1px solid #e4c9c4;

    background: white;

    color: #a45348;

}


.supplier-deactivate-button:hover {

    background: #fff3f1;

    border-color: #dcb4ae;

    color: #8d3e35;

}


/* =========================================================
   ACTIVATE
========================================================= */

.supplier-activate-button {

    border: 1px solid #c9dfce;

    background: white;

    color: #397548;

}


.supplier-activate-button:hover {

    background: #edf7ef;

    border-color: #afd0b7;

    color: #2f653c;

}


/* =========================================================
   EMPTY STATE
========================================================= */

.supplier-empty-state {

    padding: 65px 20px !important;

    text-align: center !important;

}


.supplier-empty-icon {

    width: 52px;

    height: 52px;

    margin: 0 auto 13px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 14px;

    background: var(--orange-light);

    color: var(--orange);

    font-size: 1.1875rem;

}


.supplier-empty-title {

    color: var(--dark);

    font-size: 0.875rem;

    font-weight: 700;

}


.supplier-empty-description {

    margin-top: 5px;

    color: var(--muted);

    font-size: 0.8125rem;

    line-height: 1.4;

}


/* =========================================================
   PAGINATION
========================================================= */

.supplier-pagination-wrapper {

    padding: 15px 21px;

    border-top: 1px solid var(--border);

}


.supplier-pagination-wrapper nav {

    display: flex;

    justify-content: center;

}


.supplier-pagination-wrapper svg {

    width: 15px;

    height: 15px;

}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1200px) {

    .supplier-stats {

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

    }

}


@media (max-width: 700px) {

    .supplier-stats {

        grid-template-columns: 1fr;

    }


    .suppliers-panel-header {

        align-items: flex-start;

        flex-direction: column;

    }


    .supplier-add-button {

        width: 100%;

    }


    .supplier-filters {

        align-items: stretch;

        flex-direction: column;

    }


    .supplier-search-wrapper {

        width: 100%;

    }


    .supplier-filters select,
    .supplier-filter-button,
    .supplier-clear-button {

        width: 100%;

    }


    .supplier-table-actions {

        justify-content: flex-start;

    }

}

</style>

@endpush