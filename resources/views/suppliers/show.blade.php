@extends('layouts.app')

@section('title', 'BiteSync | Supplier Details')

@section('content')

<style>
/* =========================================================
   SUPPLIER DETAILS PAGE
========================================================= */

.supplier-show-page {
    width: 100%;
    max-width: 1120px;
    margin: 0 auto;
    padding: 0 0 30px;
}

/* =========================================================
   TOPBAR
========================================================= */

.supplier-show-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin: 14px 0 0;
}

.supplier-show-heading {
    min-width: 0;
}

.supplier-show-category {
    margin-bottom: 5px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #9b8064;
}

.supplier-show-heading h1 {
    margin: 0;
    font-size: 29px;
    line-height: 1.1;
    font-weight: 800;
    color: #241a13;
}

.supplier-show-heading p {
    margin: 6px 0 0;
    font-size: 12px;
    line-height: 1.5;
    color: #7c6c5f;
}

/* =========================================================
   DATE BOX
========================================================= */

.supplier-show-date {
    display: flex;
    align-items: center;
    gap: 9px;
    min-width: 145px;
    padding: 9px 12px;
    border: 1px solid #eadfd3;
    border-radius: 12px;
    background: #fffdfb;
    flex-shrink: 0;
}

.supplier-show-date-icon {
    width: 30px;
    height: 30px;
    border-radius: 9px;
    background: #f3eadf;
    color: #8c633e;
    display: flex;
    align-items: center;
    justify-content: center;
}

.supplier-show-date-icon svg {
    width: 15px;
    height: 15px;
}

.supplier-show-date-label {
    font-size: 9px;
    font-weight: 700;
    color: #9a897b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.supplier-show-date-value {
    margin-top: 1px;
    font-size: 11px;
    font-weight: 700;
    color: #3a2b20;
}

/* =========================================================
   BREADCRUMB
========================================================= */

.supplier-show-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin: 17px 0 16px;
    font-size: 11px;
    color: #8b7b6e;
}

.supplier-show-breadcrumb a {
    color: #8b633f;
    text-decoration: none;
    font-weight: 600;
}

.supplier-show-breadcrumb a:hover {
    text-decoration: underline;
}

.supplier-show-breadcrumb-current {
    color: #4d3c30;
    font-weight: 700;
}

.supplier-show-breadcrumb-separator {
    color: #b5a79b;
}

/* =========================================================
   ALERTS
========================================================= */

.supplier-show-alert {
    margin-bottom: 16px;
    padding: 11px 13px;
    border-radius: 11px;
    font-size: 11px;
    line-height: 1.5;
}

.supplier-show-alert-success {
    background: #edf8f0;
    border: 1px solid #cfe8d5;
    color: #2d6940;
}

.supplier-show-alert-error {
    background: #fff1f0;
    border: 1px solid #f0d0cd;
    color: #8a3d37;
}

.supplier-show-alert ul {
    margin: 5px 0 0 17px;
}

/* =========================================================
   MAIN TWO-COLUMN LAYOUT
========================================================= */

.supplier-show-layout {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(0, 0.9fr);
    gap: 15px;
    align-items: start;
}

/* =========================================================
   PANELS
========================================================= */

.supplier-show-panel {
    overflow: hidden;
    border: 1px solid #e9ded2;
    border-radius: 15px;
    background: #ffffff;
    box-shadow: 0 4px 16px rgba(62, 43, 29, 0.045);
}

.supplier-show-panel-header {
    min-height: 65px;
    padding: 14px 19px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    border-bottom: 1px solid #eee5dc;
    background: #fffdfb;
}

.supplier-show-panel-title {
    min-width: 0;
}

.supplier-show-panel-title h2 {
    margin: 0;
    font-size: 17px;
    line-height: 1.2;
    font-weight: 800;
    color: #2a1e16;
}

.supplier-show-panel-title p {
    margin: 4px 0 0;
    font-size: 11px;
    line-height: 1.4;
    color: #8a796b;
}

.supplier-show-panel-body {
    padding: 18px;
}

/* =========================================================
   SUPPLIER INFORMATION
========================================================= */

.supplier-info-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 15px;
}

.supplier-info-item {
    min-width: 0;
}

.supplier-info-item.full-width {
    grid-column: 1 / -1;
}

.supplier-info-label {
    margin-bottom: 5px;
    font-size: 10px;
    line-height: 1.3;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #958477;
}

.supplier-info-value {
    min-height: 39px;
    display: flex;
    align-items: center;
    padding: 9px 11px;
    border: 1px solid #e8ded4;
    border-radius: 9px;
    background: #fffdfa;
    color: #34271e;
    font-size: 12px;
    line-height: 1.4;
    word-break: break-word;
}

.supplier-info-value.empty {
    color: #a69a91;
    font-style: italic;
}

.supplier-info-address {
    min-height: 68px;
    align-items: flex-start;
    line-height: 1.55;
}

/* =========================================================
   STATUS BADGE
========================================================= */

.supplier-status-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 25px;
    padding: 4px 9px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.supplier-status-active {
    background: #eaf7ee;
    color: #287342;
}

.supplier-status-inactive {
    background: #f4ece8;
    color: #76584a;
}

.supplier-status-pending {
    background: #fff5dc;
    color: #8a641d;
}

.supplier-status-default {
    background: #f1efed;
    color: #655b54;
}

/* =========================================================
   STATUS INFORMATION
========================================================= */

.supplier-status-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 15px;
}

.supplier-status-item {
    min-width: 0;
}

.supplier-status-label {
    margin-bottom: 5px;
    font-size: 10px;
    line-height: 1.3;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #958477;
}

.supplier-status-value {
    min-height: 39px;
    display: flex;
    align-items: center;
    color: #34271e;
    font-size: 12px;
    line-height: 1.4;
}

.supplier-status-note-box {
    min-height: 105px;
    padding: 10px 11px;
    border: 1px solid #e8ded4;
    border-radius: 9px;
    background: #fffdfa;
    color: #514239;
    font-size: 12px;
    line-height: 1.55;
    white-space: pre-wrap;
    word-break: break-word;
}

.supplier-status-note-box.empty {
    color: #a69a91;
    font-style: italic;
}

/* =========================================================
   FOOTER ACTIONS
========================================================= */

.supplier-show-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-top: 15px;
    padding: 14px 0 0;
    border-top: 1px solid #eee5dc;
}

.supplier-show-last-updated {
    font-size: 10px;
    color: #978a80;
}

.supplier-show-last-updated strong {
    color: #65574d;
}

.supplier-show-action-buttons {
    display: flex;
    align-items: center;
    gap: 8px;
}

.supplier-show-btn {
    height: 37px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 0 13px;
    border-radius: 8px;
    border: 1px solid transparent;
    text-decoration: none;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.18s ease;
}

.supplier-show-btn svg {
    width: 14px;
    height: 14px;
}

.supplier-show-btn-secondary {
    background: #ffffff;
    border-color: #ded3c9;
    color: #5d4d41;
}

.supplier-show-btn-secondary:hover {
    background: #f8f3ee;
}

.supplier-show-btn-primary {
    background: #8a603d;
    border-color: #8a603d;
    color: #ffffff;
}

.supplier-show-btn-primary:hover {
    background: #714c30;
    border-color: #714c30;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 900px) {

    .supplier-show-layout {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 700px) {

    .supplier-show-page {
        padding-left: 14px;
        padding-right: 14px;
    }

    .supplier-show-topbar {
        align-items: flex-start;
        flex-direction: column;
    }

    .supplier-show-date {
        width: 100%;
    }

    .supplier-info-grid {
        grid-template-columns: 1fr;
    }

    .supplier-info-item.full-width {
        grid-column: auto;
    }

    .supplier-show-actions {
        align-items: stretch;
        flex-direction: column;
    }

    .supplier-show-action-buttons {
        width: 100%;
    }

    .supplier-show-btn {
        flex: 1;
    }
}

@media (max-width: 480px) {

    .supplier-show-page {
        padding-left: 10px;
        padding-right: 10px;
    }

    .supplier-show-heading h1 {
        font-size: 25px;
    }

    .supplier-show-panel-header {
        padding: 13px 15px;
    }

    .supplier-show-panel-body {
        padding: 15px;
    }

    .supplier-show-panel-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .supplier-show-action-buttons {
        flex-direction: column;
    }

    .supplier-show-btn {
        width: 100%;
    }
}
</style>

@php

    $status = strtolower(trim((string) ($supplier->status ?? '')));

    $statusClass = match ($status) {
        'active' => 'supplier-status-active',
        'inactive' => 'supplier-status-inactive',
        'pending' => 'supplier-status-pending',
        default => 'supplier-status-default',
    };

    $statusLabel = $supplier->status
        ? ucfirst($supplier->status)
        : 'Not Set';

    $phone = trim((string) ($supplier->phone ?? ''));

    $email = trim((string) ($supplier->email ?? ''));

    $address = trim((string) ($supplier->address ?? ''));

    $notes = trim((string) ($supplier->notes ?? ''));

    $createdAt = $supplier->created_at
        ? $supplier->created_at->format('M d, Y h:i A')
        : '—';

    $updatedAt = $supplier->updated_at
        ? $supplier->updated_at->format('M d, Y h:i A')
        : '—';

@endphp


<div class="supplier-show-page">

    {{-- =====================================================
         TOPBAR
    ====================================================== --}}

    <div class="supplier-show-topbar">

        <div class="supplier-show-heading">

            <div class="supplier-show-category">
                Supplier Management
            </div>

            <h1>
                Supplier Details
            </h1>

            <p>
                View supplier information, contact details, address, and current status.
            </p>

        </div>


        <div class="supplier-show-date">

            <div class="supplier-show-date-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <rect
                        x="3"
                        y="4"
                        width="18"
                        height="17"
                        rx="2"
                    ></rect>

                    <path d="M16 2v4"></path>
                    <path d="M8 2v4"></path>
                    <path d="M3 10h18"></path>
                </svg>

            </div>


            <div>

                <div class="supplier-show-date-label">
                    Today
                </div>

                <div class="supplier-show-date-value">
                    {{ now()->format('M d, Y') }}
                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         BREADCRUMB
    ====================================================== --}}

    <div class="supplier-show-breadcrumb">

        <a href="{{ route('suppliers.index') }}">
            Suppliers
        </a>

        <span class="supplier-show-breadcrumb-separator">
            /
        </span>

        <span>
            {{ $supplier->name }}
        </span>

        <span class="supplier-show-breadcrumb-separator">
            /
        </span>

        <span class="supplier-show-breadcrumb-current">
            Details
        </span>

    </div>


    {{-- =====================================================
         ALERTS
    ====================================================== --}}

    @if(session('success'))

        <div class="supplier-show-alert supplier-show-alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if($errors->any())

        <div class="supplier-show-alert supplier-show-alert-error">

            <strong>
                Please check the following:
            </strong>

            <ul>

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =====================================================
         MAIN LAYOUT
         SUPPLIER INFORMATION + SUPPLIER STATUS
    ====================================================== --}}

    <div class="supplier-show-layout">


        {{-- =================================================
             SUPPLIER INFORMATION
             CONTACT + ADDRESS INCLUDED HERE
        ================================================== --}}

        <section class="supplier-show-panel">

            <div class="supplier-show-panel-header">

                <div class="supplier-show-panel-title">

                    <h2>
                        Supplier Information
                    </h2>

                    <p>
                        Supplier, contact, and registered address details.
                    </p>

                </div>

            </div>


            <div class="supplier-show-panel-body">

                <div class="supplier-info-grid">


                    {{-- SUPPLIER NAME --}}

                    <div class="supplier-info-item">

                        <div class="supplier-info-label">
                            Supplier Name
                        </div>

                        <div class="supplier-info-value">
                            {{ $supplier->name ?: '—' }}
                        </div>

                    </div>


                    {{-- CONTACT PERSON --}}

                    <div class="supplier-info-item">

                        <div class="supplier-info-label">
                            Contact Person
                        </div>

                        <div class="supplier-info-value {{ empty($supplier->contact_person) ? 'empty' : '' }}">
                            {{ $supplier->contact_person ?: 'Not provided' }}
                        </div>

                    </div>


                    {{-- PHONE --}}

                    <div class="supplier-info-item">

                        <div class="supplier-info-label">
                            Phone Number
                        </div>

                        <div class="supplier-info-value {{ empty($phone) ? 'empty' : '' }}">
                            {{ $phone ?: 'Not provided' }}
                        </div>

                    </div>


                    {{-- EMAIL --}}

                    <div class="supplier-info-item">

                        <div class="supplier-info-label">
                            Email Address
                        </div>

                        <div class="supplier-info-value {{ empty($email) ? 'empty' : '' }}">
                            {{ $email ?: 'Not provided' }}
                        </div>

                    </div>


                    {{-- ADDRESS --}}

                    <div class="supplier-info-item full-width">

                        <div class="supplier-info-label">
                            Address
                        </div>

                        <div class="supplier-info-value supplier-info-address {{ empty($address) ? 'empty' : '' }}">
                            {{ $address ?: 'No address has been provided.' }}
                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- =================================================
             SUPPLIER STATUS
        ================================================== --}}

        <section class="supplier-show-panel">

            <div class="supplier-show-panel-header">

                <div class="supplier-show-panel-title">

                    <h2>
                        Supplier Status
                    </h2>

                    <p>
                        Current availability and record status.
                    </p>

                </div>


                <span class="supplier-status-badge {{ $statusClass }}">
                    {{ $statusLabel }}
                </span>

            </div>


            <div class="supplier-show-panel-body">

                <div class="supplier-status-grid">


                    {{-- STATUS --}}

                    <div class="supplier-status-item">

                        <div class="supplier-status-label">
                            Status
                        </div>

                        <div class="supplier-status-value">

                            <span class="supplier-status-badge {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>

                        </div>

                    </div>


                    {{-- CREATED --}}

                    <div class="supplier-status-item">

                        <div class="supplier-status-label">
                            Created
                        </div>

                        <div class="supplier-status-value">
                            {{ $createdAt }}
                        </div>

                    </div>


                    {{-- NOTES --}}

                    <div class="supplier-status-item">

                        <div class="supplier-status-label">
                            Notes
                        </div>

                        <div class="supplier-status-note-box {{ empty($notes) ? 'empty' : '' }}">
                            {{ $notes ?: 'No notes have been added for this supplier.' }}
                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>


    {{-- =====================================================
         ACTIONS
    ====================================================== --}}

    <div class="supplier-show-actions">

        <div class="supplier-show-last-updated">

            Last updated:

            <strong>
                {{ $updatedAt }}
            </strong>

        </div>


        <div class="supplier-show-action-buttons">


            {{-- BACK --}}

            <a
                href="{{ route('suppliers.index') }}"
                class="supplier-show-btn supplier-show-btn-secondary"
            >

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="M19 12H5"></path>
                    <path d="M12 19l-7-7 7-7"></path>
                </svg>

                Back to Suppliers

            </a>


            {{-- EDIT --}}

            <a
                href="{{ route('suppliers.edit', $supplier) }}"
                class="supplier-show-btn supplier-show-btn-primary"
            >

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="M12 20h9"></path>
                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                </svg>

                Edit Supplier

            </a>

        </div>

    </div>

</div>

@endsection