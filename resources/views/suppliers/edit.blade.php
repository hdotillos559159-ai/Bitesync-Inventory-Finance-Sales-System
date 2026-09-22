@extends('layouts.app')

@section('title', 'BiteSync | Edit Supplier')

@section('content')

<div class="supplier-edit-page">

    {{-- =========================================================
         TOPBAR
    ========================================================== --}}

    <div class="topbar">

        <div class="page-title">

            <small>
                Supplier Management
            </small>

            <h1>
                Edit Supplier
            </h1>

            <p>
                Update supplier contact, business, address, and status information.
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

    <div class="supplier-breadcrumb">

        <a href="{{ route('suppliers.index') }}">
            Suppliers
        </a>

        <span>/</span>

        <a href="{{ route('suppliers.show', $supplier) }}">
            {{ $supplier->name }}
        </a>

        <span>/</span>

        <span>
            Edit
        </span>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

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


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if ($errors->any())

        <div class="supplier-alert supplier-alert-error">

            <span class="supplier-alert-icon">
                !
            </span>

            <div>

                <div class="supplier-error-title">
                    Please correct the following:
                </div>

                <ul>

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
         FORM
    ========================================================== --}}

    <form
        method="POST"
        action="{{ route('suppliers.update', $supplier) }}"
        id="supplier-edit-form"
    >

        @csrf

        @method('PUT')


        {{-- =====================================================
             TOP TWO PANELS
        ====================================================== --}}

        <div class="supplier-top-panels">


            {{-- =================================================
                 SUPPLIER INFORMATION
            ================================================== --}}

            <div class="supplier-edit-panel">


                {{-- PANEL HEADER --}}

                <div class="supplier-edit-panel-header">

                    <div>

                        <div class="supplier-edit-panel-title">
                            Supplier Information
                        </div>

                        <div class="supplier-edit-panel-subtitle">
                            Update the supplier's basic business information.
                        </div>

                    </div>

                </div>


                {{-- PANEL BODY --}}

                <div class="supplier-edit-panel-body">

                    <div class="supplier-form-grid">


                        {{-- Supplier Name --}}

                        <div class="supplier-form-group supplier-full-width">

                            <label
                                for="name"
                                class="supplier-form-label"
                            >
                                Supplier Name
                                <span class="supplier-required">*</span>
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $supplier->name) }}"
                                class="supplier-form-input"
                                placeholder="Enter supplier name"
                                maxlength="255"
                                required
                            >

                            @error('name')

                                <div class="supplier-field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Contact Person --}}

                        <div class="supplier-form-group supplier-full-width">

                            <label
                                for="contact_person"
                                class="supplier-form-label"
                            >
                                Contact Person
                            </label>

                            <input
                                type="text"
                                id="contact_person"
                                name="contact_person"
                                value="{{ old('contact_person', $supplier->contact_person) }}"
                                class="supplier-form-input"
                                placeholder="Enter contact person's name"
                                maxlength="255"
                            >

                            @error('contact_person')

                                <div class="supplier-field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Email --}}

                        <div class="supplier-form-group">

                            <label
                                for="email"
                                class="supplier-form-label"
                            >
                                Email Address
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $supplier->email) }}"
                                class="supplier-form-input"
                                placeholder="supplier@example.com"
                                maxlength="255"
                            >

                            @error('email')

                                <div class="supplier-field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Phone --}}

                        <div class="supplier-form-group">

                            <label
                                for="phone"
                                class="supplier-form-label"
                            >
                                Phone Number
                            </label>

                            <div class="phone-input">

                                <span class="phone-prefix">
                                    +63
                                </span>

                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone', $supplier->phone) }}"
                                    class="supplier-form-input"
                                    placeholder="912 345 6789"
                                    maxlength="13"
                                    inputmode="numeric"
                                    autocomplete="tel"
                                >

                            </div>

                            @error('phone')

                                <div class="supplier-field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 SUPPLIER STATUS
            ================================================== --}}

            <div class="supplier-edit-panel">


                {{-- PANEL HEADER --}}

                <div class="supplier-edit-panel-header">

                    <div>

                        <div class="supplier-edit-panel-title">
                            Supplier Status
                        </div>

                        <div class="supplier-edit-panel-subtitle">
                            Manage supplier availability and record details.
                        </div>

                    </div>


                    <div class="supplier-current-status">

                        @if ($supplier->status === 'Active')

                            <span class="supplier-status supplier-status-active">
                                ACTIVE
                            </span>

                        @else

                            <span class="supplier-status supplier-status-inactive">
                                INACTIVE
                            </span>

                        @endif

                    </div>

                </div>


                {{-- PANEL BODY --}}

                <div class="supplier-edit-panel-body">

                    <div class="supplier-form-grid">


                        {{-- Status --}}

                        <div class="supplier-form-group">

                            <label
                                for="status"
                                class="supplier-form-label"
                            >
                                Status
                                <span class="supplier-required">*</span>
                            </label>

                            <select
                                id="status"
                                name="status"
                                class="supplier-form-select"
                                required
                            >

                                <option
                                    value="Active"
                                    {{ old('status', $supplier->status) === 'Active' ? 'selected' : '' }}
                                >
                                    Active
                                </option>

                                <option
                                    value="Inactive"
                                    {{ old('status', $supplier->status) === 'Inactive' ? 'selected' : '' }}
                                >
                                    Inactive
                                </option>

                            </select>

                            @error('status')

                                <div class="supplier-field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Created --}}

                        <div class="supplier-form-group">

                            <label class="supplier-form-label">
                                Created
                            </label>

                            <div class="supplier-readonly">

                                {{ $supplier->created_at?->format('M d, Y h:i A') ?? '—' }}

                            </div>

                        </div>


                        {{-- Last Updated --}}

                        <div class="supplier-form-group supplier-full-width">

                            <label class="supplier-form-label">
                                Last Updated
                            </label>

                            <div class="supplier-readonly">

                                {{ $supplier->updated_at?->format('M d, Y h:i A') ?? '—' }}

                            </div>

                        </div>

                    </div>


                    {{-- STATUS INFORMATION --}}

                    <div class="supplier-status-note">

                        <div class="supplier-status-note-icon">
                            i
                        </div>

                        <div>

                            <div class="supplier-status-note-title">
                                Supplier availability
                            </div>

                            <div class="supplier-status-note-text">
                                Inactive suppliers remain available in historical
                                purchasing and reporting records.
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             CONTACT & ADDRESS PANEL
        ====================================================== --}}

        <div class="supplier-edit-panel supplier-contact-panel">


            {{-- PANEL HEADER --}}

            <div class="supplier-edit-panel-header">

                <div>

                    <div class="supplier-edit-panel-title">
                        Contact & Address
                    </div>

                    <div class="supplier-edit-panel-subtitle">
                        Update supplier communication and complete location details.
                    </div>

                </div>

            </div>


            {{-- PANEL BODY --}}

            <div class="supplier-edit-panel-body">

                <div class="supplier-form-grid">


                    {{-- Street / Building --}}

                    <div class="supplier-form-group supplier-full-width">

                        <label
                            for="street"
                            class="supplier-form-label"
                        >
                            Street / Building / House No.
                        </label>

                        <input
                            type="text"
                            id="street"
                            name="street"
                            value="{{ old('street') }}"
                            class="supplier-form-input"
                            placeholder="Example: 123 Main Street, Building A"
                            maxlength="255"
                        >

                    </div>


                    {{-- Province --}}

                    <div class="supplier-form-group">

                        <label
                            for="province"
                            class="supplier-form-label"
                        >
                            Province
                            <span class="supplier-required">*</span>
                        </label>

                        <select
                            id="province"
                            name="province"
                            class="supplier-form-select"
                            required
                        >

                            <option value="">
                                Loading provinces...
                            </option>

                        </select>

                        @error('province')

                            <div class="supplier-field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- City / Municipality --}}

                    <div class="supplier-form-group">

                        <label
                            for="city_municipality"
                            class="supplier-form-label"
                        >
                            City / Municipality
                            <span class="supplier-required">*</span>
                        </label>

                        <select
                            id="city_municipality"
                            name="city_municipality"
                            class="supplier-form-select"
                            required
                            disabled
                        >

                            <option value="">
                                Select province first
                            </option>

                        </select>

                        @error('city_municipality')

                            <div class="supplier-field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Barangay --}}

                    <div class="supplier-form-group supplier-full-width">

                        <label
                            for="barangay"
                            class="supplier-form-label"
                        >
                            Barangay
                            <span class="supplier-required">*</span>
                        </label>

                        <select
                            id="barangay"
                            name="barangay"
                            class="supplier-form-select"
                            required
                            disabled
                        >

                            <option value="">
                                Select city / municipality first
                            </option>

                        </select>

                        @error('barangay')

                            <div class="supplier-field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Complete Address Preview --}}

                    <div class="supplier-form-group supplier-full-width">

                        <div class="address-preview">

                            <div class="address-preview-icon">
                                ⌖
                            </div>

                            <div class="address-preview-content">

                                <div class="address-preview-title">
                                    Complete Address
                                </div>

                                <div
                                    class="address-preview-text"
                                    id="address-preview-text"
                                >
                                    {{ $supplier->address ?: 'Select the address information above.' }}
                                </div>

                            </div>

                        </div>


                        <input
                            type="hidden"
                            id="address"
                            name="address"
                            value="{{ old('address', $supplier->address) }}"
                        >

                        @error('address')

                            <div class="supplier-field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             NOTES PANEL
        ====================================================== --}}

        <div class="supplier-edit-panel supplier-notes-panel">


            {{-- PANEL HEADER --}}

            <div class="supplier-edit-panel-header">

                <div>

                    <div class="supplier-edit-panel-title">
                        Additional Notes
                    </div>

                    <div class="supplier-edit-panel-subtitle">
                        Add useful supplier information, payment terms, or delivery details.
                    </div>

                </div>

            </div>


            {{-- PANEL BODY --}}

            <div class="supplier-edit-panel-body">

                <div class="supplier-form-group">

                    <label
                        for="notes"
                        class="supplier-form-label"
                    >
                        Notes
                    </label>

                    <textarea
                        id="notes"
                        name="notes"
                        class="supplier-form-textarea"
                        placeholder="Optional notes about this supplier..."
                        maxlength="5000"
                    >{{ old('notes', $supplier->notes) }}</textarea>

                    @error('notes')

                        <div class="supplier-field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>

        </div>


        {{-- =====================================================
             INFORMATION BOX
        ====================================================== --}}

        <div class="supplier-information-box">

            <div class="supplier-information-icon">
                i
            </div>

            <div>

                <div class="supplier-information-title">
                    Supplier records are retained
                </div>

                <div class="supplier-information-text">
                    Setting a supplier to inactive does not delete the supplier
                    or its information. Historical purchasing and reporting
                    records can still reference this supplier.
                </div>

            </div>

        </div>


        {{-- =====================================================
             FOOTER
        ====================================================== --}}

        <div class="supplier-edit-footer">

            <div class="supplier-last-updated">

                Last updated:

                <strong>
                    {{ $supplier->updated_at?->format('M d, Y h:i A') ?? '—' }}
                </strong>

            </div>


            <div class="supplier-edit-actions">

                <a
                    href="{{ route('suppliers.show', $supplier) }}"
                    class="supplier-cancel-button"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="supplier-save-button"
                >

                    <span>
                        ✓
                    </span>

                    Save Changes

                </button>

            </div>

        </div>

    </form>

</div>

@endsection


@push('styles')

<style>

/* =========================================================
   PAGE
========================================================= */

.supplier-edit-page {

    width: 100%;

    max-width: 1120px;

    margin: 0 auto;

    padding-bottom: 30px;

}


/* =========================================================
   TOPBAR
========================================================= */

.supplier-edit-page .topbar {

    margin-bottom: 14px;

}


.supplier-edit-page .page-title small {

    display: block;

    margin-bottom: 4px;

    color: var(--orange-dark);

    font-size: 10px;

    line-height: 1.2;

    font-weight: 700;

    letter-spacing: 0.08em;

    text-transform: uppercase;

}


.supplier-edit-page .page-title h1 {

    margin: 0;

    color: var(--dark);

    font-size: 29px;

    line-height: 1.15;

    letter-spacing: -0.045rem;

    font-weight: 700;

}


.supplier-edit-page .page-title p {

    margin: 5px 0 0;

    color: var(--muted);

    font-size: 12px;

    line-height: 1.45;

    font-weight: 400;

}


.supplier-edit-page .date-box {

    min-width: 145px;

    padding: 9px 12px;

}


.supplier-edit-page .date-icon {

    width: 30px;

    height: 30px;

}


/* =========================================================
   BREADCRUMB
========================================================= */

.supplier-breadcrumb {

    display: flex;

    align-items: center;

    flex-wrap: wrap;

    gap: 7px;

    margin-bottom: 17px;

    color: var(--muted);

    font-size: 11px;

    font-weight: 600;

}


.supplier-breadcrumb a {

    color: var(--orange-dark);

    text-decoration: none;

}


.supplier-breadcrumb a:hover {

    color: var(--orange);

}


.supplier-breadcrumb span {

    color: #b8afa8;

}


/* =========================================================
   ALERTS
========================================================= */

.supplier-alert {

    display: flex;

    align-items: flex-start;

    gap: 9px;

    margin-bottom: 16px;

    padding: 11px 13px;

    border: 1px solid var(--border);

    border-radius: 9px;

    font-size: 11px;

    line-height: 1.5;

    font-weight: 400;

}


.supplier-alert-success {

    align-items: center;

    background: var(--green-light);

    border-color: #d5e7d8;

    color: #397548;

    font-weight: 600;

}


.supplier-alert-error {

    background: var(--red-light);

    border-color: #ecd0cc;

    color: #9e493f;

}


.supplier-alert-icon {

    width: 21px;

    height: 21px;

    flex: 0 0 21px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 6px;

    background: rgba(255, 255, 255, 0.7);

    font-size: 10px;

    font-weight: 700;

}


.supplier-error-title {

    margin-bottom: 4px;

    font-size: 11px;

    font-weight: 700;

}


.supplier-alert ul {

    margin: 0;

    padding-left: 16px;

}


.supplier-alert li {

    margin: 2px 0;

}


/* =========================================================
   TOP PANELS
========================================================= */

.supplier-top-panels {

    display: grid;

    grid-template-columns:
        minmax(0, 1fr)
        minmax(0, 1fr);

    gap: 15px;

    margin-bottom: 15px;

}


/* =========================================================
   MAIN PANELS
========================================================= */

.supplier-edit-panel {

    overflow: hidden;

    background: white;

    border: 1px solid var(--border);

    border-radius: 15px;

    box-shadow:
        0 5px 18px
        rgba(43, 31, 23, 0.035);

}


.supplier-contact-panel {

    margin-bottom: 15px;

}


.supplier-notes-panel {

    margin-bottom: 15px;

}


/* =========================================================
   PANEL HEADER
========================================================= */

.supplier-edit-panel-header {

    min-height: 65px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 18px;

    padding: 14px 19px;

    border-bottom: 1px solid var(--border);

}


.supplier-edit-panel-title {

    color: var(--dark);

    font-size: 17px;

    line-height: 1.3;

    font-weight: 700;

}


.supplier-edit-panel-subtitle {

    margin-top: 3px;

    color: var(--muted);

    font-size: 11px;

    line-height: 1.4;

    font-weight: 400;

}


.supplier-current-status {

    flex-shrink: 0;

}


/* =========================================================
   PANEL BODY
========================================================= */

.supplier-edit-panel-body {

    padding: 18px;

}


/* =========================================================
   STATUS BADGES
========================================================= */

.supplier-status {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    padding: 5px 8px;

    border-radius: 7px;

    font-size: 8px;

    line-height: 1;

    font-weight: 700;

    letter-spacing: 0.3px;

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
   FORM GRID
========================================================= */

.supplier-form-grid {

    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 15px;

}


.supplier-form-group {

    min-width: 0;

}


.supplier-full-width {

    grid-column: 1 / -1;

}


/* =========================================================
   LABELS
========================================================= */

.supplier-form-label {

    display: block;

    margin-bottom: 6px;

    color: #55483f;

    font-size: 11px;

    line-height: 1.35;

    font-weight: 600;

}


.supplier-required {

    color: #c45e3f;

    font-weight: 600;

}


/* =========================================================
   INPUTS
========================================================= */

.supplier-form-input,
.supplier-form-select,
.supplier-form-textarea {

    width: 100%;

    box-sizing: border-box;

    border: 1px solid #ddd3ca;

    border-radius: 8px;

    background: white;

    color: var(--text);

    font-family: inherit;

    font-size: 12px;

    line-height: 1.4;

    font-weight: 400;

    outline: none;

    transition:
        border-color 0.18s ease,
        box-shadow 0.18s ease;

}


.supplier-form-input,
.supplier-form-select {

    height: 39px;

    padding: 0 11px;

}


.supplier-form-textarea {

    min-height: 105px;

    padding: 10px 11px;

    resize: vertical;

    line-height: 1.5;

}


.supplier-form-input:focus,
.supplier-form-select:focus,
.supplier-form-textarea:focus {

    border-color: #d5a77d;

    box-shadow:
        0 0 0 3px
        rgba(196, 122, 58, 0.08);

}


.supplier-form-input::placeholder,
.supplier-form-textarea::placeholder {

    color: #aaa19a;

    font-weight: 400;

}


.supplier-form-select:disabled {

    background: #f7f4f1;

    color: #aaa19a;

    cursor: not-allowed;

}


/* =========================================================
   PHONE
========================================================= */

.phone-input {

    position: relative;

}


.phone-prefix {

    position: absolute;

    top: 50%;

    left: 11px;

    z-index: 2;

    transform: translateY(-50%);

    color: #8c684b;

    font-size: 12px;

    font-weight: 600;

    pointer-events: none;

}


.phone-input .supplier-form-input {

    padding-left: 41px;

}


/* =========================================================
   FIELD ERRORS
========================================================= */

.supplier-field-error {

    margin-top: 5px;

    color: #a64b3f;

    font-size: 10px;

    line-height: 1.4;

    font-weight: 600;

}


/* =========================================================
   READONLY
========================================================= */

.supplier-readonly {

    width: 100%;

    height: 39px;

    box-sizing: border-box;

    display: flex;

    align-items: center;

    padding: 0 11px;

    border: 1px solid #e3dbd4;

    border-radius: 8px;

    background: #f8f5f1;

    color: #81776f;

    font-size: 12px;

    line-height: 1.4;

    font-weight: 400;

}


/* =========================================================
   STATUS NOTE
========================================================= */

.supplier-status-note {

    display: flex;

    align-items: flex-start;

    gap: 9px;

    margin-top: 15px;

    padding: 10px 11px;

    border: 1px solid #eadfd6;

    border-radius: 9px;

    background: var(--card-soft);

}


.supplier-status-note-icon {

    width: 28px;

    height: 28px;

    flex: 0 0 28px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 7px;

    background: var(--orange-light);

    color: var(--orange-dark);

    font-size: 10px;

    font-weight: 700;

}


.supplier-status-note-title {

    margin-bottom: 2px;

    color: #4a3c33;

    font-size: 10px;

    line-height: 1.35;

    font-weight: 700;

}


.supplier-status-note-text {

    color: var(--muted);

    font-size: 10px;

    line-height: 1.45;

    font-weight: 400;

}


/* =========================================================
   ADDRESS PREVIEW
========================================================= */

.address-preview {

    display: flex;

    align-items: flex-start;

    gap: 10px;

    padding: 11px 12px;

    border: 1px solid #eadfd6;

    border-radius: 10px;

    background: var(--card-soft);

}


.address-preview-icon {

    width: 30px;

    height: 30px;

    flex: 0 0 30px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 8px;

    background: var(--orange-light);

    color: var(--orange-dark);

    font-size: 11px;

    font-weight: 700;

}


.address-preview-content {

    min-width: 0;

}


.address-preview-title {

    margin-bottom: 3px;

    color: #4a3c33;

    font-size: 11px;

    line-height: 1.35;

    font-weight: 700;

}


.address-preview-text {

    color: var(--muted);

    font-size: 11px;

    line-height: 1.5;

    font-weight: 400;

    word-break: break-word;

}


/* =========================================================
   INFORMATION BOX
========================================================= */

.supplier-information-box {

    display: flex;

    align-items: flex-start;

    gap: 10px;

    margin-bottom: 15px;

    padding: 11px 12px;

    border: 1px solid #eadfd6;

    border-radius: 10px;

    background: var(--card-soft);

}


.supplier-information-icon {

    width: 30px;

    height: 30px;

    flex: 0 0 30px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 8px;

    background: var(--orange-light);

    color: var(--orange-dark);

    font-size: 11px;

    font-weight: 700;

}


.supplier-information-title {

    margin-bottom: 3px;

    color: #4a3c33;

    font-size: 11px;

    line-height: 1.35;

    font-weight: 700;

}


.supplier-information-text {

    color: var(--muted);

    font-size: 11px;

    line-height: 1.5;

    font-weight: 400;

}


/* =========================================================
   FOOTER
========================================================= */

.supplier-edit-footer {

    min-height: 62px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 18px;

    padding: 12px 18px;

    border: 1px solid var(--border);

    border-radius: 15px;

    background: var(--card-soft);

}


.supplier-last-updated {

    color: var(--muted);

    font-size: 11px;

    line-height: 1.4;

    font-weight: 400;

}


.supplier-last-updated strong {

    color: #665b53;

    font-weight: 600;

}


.supplier-edit-actions {

    display: flex;

    align-items: center;

    gap: 8px;

}


/* =========================================================
   BUTTONS
========================================================= */

.supplier-cancel-button,
.supplier-save-button {

    min-height: 37px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 6px;

    padding: 0 14px;

    border-radius: 8px;

    font-family: inherit;

    font-size: 11px;

    line-height: 1.2;

    font-weight: 700;

    text-decoration: none;

    cursor: pointer;

    transition:
        background-color 0.18s ease,
        border-color 0.18s ease,
        color 0.18s ease,
        box-shadow 0.18s ease;

}


/* =========================================================
   CANCEL
========================================================= */

.supplier-cancel-button {

    border: 1px solid var(--border);

    background: white;

    color: var(--muted);

}


.supplier-cancel-button:hover {

    background: #faf7f3;

    border-color: #cdbfb3;

    color: var(--dark);

}


/* =========================================================
   SAVE
========================================================= */

.supplier-save-button {

    border: 1px solid var(--orange-dark);

    background:
        linear-gradient(
            135deg,
            var(--orange),
            var(--orange-dark)
        );

    color: white;

    box-shadow:
        0 5px 13px
        rgba(168, 95, 40, 0.14);

}


.supplier-save-button:hover {

    background:
        linear-gradient(
            135deg,
            var(--orange-dark),
            var(--orange-dark)
        );

    box-shadow:
        0 6px 14px
        rgba(168, 95, 40, 0.18);

}


.supplier-save-button span {

    font-size: 11px;

}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 850px) {

    .supplier-top-panels {

        grid-template-columns: 1fr;

    }


    .supplier-form-grid {

        grid-template-columns: 1fr;

    }


    .supplier-full-width {

        grid-column: auto;

    }

}


@media (max-width: 700px) {

    .supplier-edit-panel-header {

        align-items: flex-start;

        flex-direction: column;

    }


    .supplier-edit-footer {

        align-items: stretch;

        flex-direction: column;

    }


    .supplier-edit-actions {

        width: 100%;

    }


    .supplier-cancel-button,
    .supplier-save-button {

        flex: 1;

    }

}


@media (max-width: 480px) {

    .supplier-edit-page {

        padding-bottom: 18px;

    }


    .supplier-edit-page .page-title h1 {

        font-size: 26px;

    }


    .supplier-edit-panel-header {

        padding: 14px;

    }


    .supplier-edit-panel-body {

        padding: 14px;

    }


    .supplier-edit-footer {

        padding: 12px 14px;

    }

}

</style>

@endpush


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* ============================================================
       PHILIPPINE ADDRESS API
    ============================================================ */

    const API_BASE =
        'https://psgc.cloud/api/v2';


    /* ============================================================
       ELEMENTS
    ============================================================ */

    const provinceSelect =
        document.getElementById('province');

    const citySelect =
        document.getElementById('city_municipality');

    const barangaySelect =
        document.getElementById('barangay');

    const streetInput =
        document.getElementById('street');

    const addressInput =
        document.getElementById('address');

    const addressPreview =
        document.getElementById('address-preview-text');

    const phoneInput =
        document.getElementById('phone');


    /* ============================================================
       OLD VALUES
    ============================================================ */

    const oldProvince =
        @json(old('province', ''));

    const oldCity =
        @json(old('city_municipality', ''));

    const oldBarangay =
        @json(old('barangay', ''));

    const oldAddress =
        @json(old('address', $supplier->address ?? ''));

    const oldStreet =
        @json(old('street', ''));


    /* ============================================================
       FETCH JSON
    ============================================================ */

    async function fetchJson(url) {

        const response =
            await fetch(url, {
                headers: {
                    'Accept': 'application/json'
                }
            });


        if (!response.ok) {

            throw new Error(
                'Unable to load Philippine address data.'
            );

        }


        return await response.json();

    }


    /* ============================================================
       RESET SELECT
    ============================================================ */

    function resetSelect(
        select,
        placeholder
    ) {

        select.innerHTML = '';

        const option =
            document.createElement('option');

        option.value = '';

        option.textContent =
            placeholder;

        select.appendChild(option);

        select.disabled = true;

    }


    /* ============================================================
       POPULATE SELECT
    ============================================================ */

    function populateSelect(
        select,
        items,
        placeholder
    ) {

        select.innerHTML = '';

        const firstOption =
            document.createElement('option');

        firstOption.value = '';

        firstOption.textContent =
            placeholder;

        select.appendChild(firstOption);


        items.forEach(function (item) {

            const option =
                document.createElement('option');

            option.value =
                item.code;

            option.textContent =
                item.name;

            option.dataset.name =
                item.name;

            select.appendChild(option);

        });


        select.disabled = false;

    }


    /* ============================================================
       FIND OPTION BY CODE OR NAME
    ============================================================ */

    function findOption(
        select,
        value
    ) {

        if (!value) {

            return null;

        }


        const normalizedValue =
            String(value)
                .trim()
                .toLowerCase();


        return Array.from(
            select.options
        ).find(function (option) {

            return (

                option.value
                    .toLowerCase() === normalizedValue

                ||

                option.textContent
                    .trim()
                    .toLowerCase() === normalizedValue

            );

        }) || null;

    }


    /* ============================================================
       FIND ADDRESS PART FROM OLD ADDRESS
    ============================================================ */

    function addressContains(
        address,
        name
    ) {

        if (!address || !name) {

            return false;

        }


        return address
            .toLowerCase()
            .includes(
                name.toLowerCase()
            );

    }


    /* ============================================================
       LOAD PROVINCES
    ============================================================ */

    async function loadProvinces() {

        try {

            provinceSelect.innerHTML =
                '<option value="">Loading provinces...</option>';

            provinceSelect.disabled =
                true;


            const provinces =
                await fetchJson(
                    `${API_BASE}/provinces`
                );


            populateSelect(
                provinceSelect,
                provinces,
                'Select province'
            );


            let provinceOption =
                findOption(
                    provinceSelect,
                    oldProvince
                );


            if (
                !provinceOption &&
                oldAddress
            ) {

                provinceOption =
                    Array.from(
                        provinceSelect.options
                    ).find(function (option) {

                        return (
                            option.value &&
                            addressContains(
                                oldAddress,
                                option.textContent
                            )
                        );

                    }) || null;

            }


            if (provinceOption) {

                provinceOption.selected =
                    true;

                await loadCities(
                    provinceOption.value
                );

            }


        } catch (error) {

            console.error(error);

            provinceSelect.innerHTML =
                '<option value="">Unable to load provinces</option>';

            provinceSelect.disabled =
                true;

        }

    }


    /* ============================================================
       LOAD CITIES
    ============================================================ */

    async function loadCities(
        provinceCode
    ) {

        resetSelect(
            citySelect,
            'Loading cities / municipalities...'
        );


        resetSelect(
            barangaySelect,
            'Select city / municipality first'
        );


        if (!provinceCode) {

            resetSelect(
                citySelect,
                'Select province first'
            );

            updateAddressPreview();

            return;

        }


        try {

            const cities =
                await fetchJson(
                    `${API_BASE}/provinces/${encodeURIComponent(provinceCode)}/cities-municipalities`
                );


            populateSelect(
                citySelect,
                cities,
                'Select city / municipality'
            );


            let cityOption =
                findOption(
                    citySelect,
                    oldCity
                );


            if (
                !cityOption &&
                oldAddress
            ) {

                cityOption =
                    Array.from(
                        citySelect.options
                    ).find(function (option) {

                        return (
                            option.value &&
                            addressContains(
                                oldAddress,
                                option.textContent
                            )
                        );

                    }) || null;

            }


            if (cityOption) {

                cityOption.selected =
                    true;

                await loadBarangays(
                    cityOption.value
                );

            }


        } catch (error) {

            console.error(error);

            resetSelect(
                citySelect,
                'Unable to load cities'
            );

        }

    }


    /* ============================================================
       LOAD BARANGAYS
    ============================================================ */

    async function loadBarangays(
        cityCode
    ) {

        resetSelect(
            barangaySelect,
            'Loading barangays...'
        );


        if (!cityCode) {

            resetSelect(
                barangaySelect,
                'Select city / municipality first'
            );

            updateAddressPreview();

            return;

        }


        try {

            const barangays =
                await fetchJson(
                    `${API_BASE}/cities-municipalities/${encodeURIComponent(cityCode)}/barangays`
                );


            populateSelect(
                barangaySelect,
                barangays,
                'Select barangay'
            );


            let barangayOption =
                findOption(
                    barangaySelect,
                    oldBarangay
                );


            if (
                !barangayOption &&
                oldAddress
            ) {

                barangayOption =
                    Array.from(
                        barangaySelect.options
                    ).find(function (option) {

                        return (
                            option.value &&
                            addressContains(
                                oldAddress,
                                option.textContent
                            )
                        );

                    }) || null;

            }


            if (barangayOption) {

                barangayOption.selected =
                    true;

            }


            updateAddressPreview();


        } catch (error) {

            console.error(error);

            resetSelect(
                barangaySelect,
                'Unable to load barangays'
            );

        }

    }


    /* ============================================================
       PROVINCE CHANGE
    ============================================================ */

    provinceSelect.addEventListener(
        'change',
        function () {

            loadCities(
                this.value
            );

            updateAddressPreview();

        }
    );


    /* ============================================================
       CITY CHANGE
    ============================================================ */

    citySelect.addEventListener(
        'change',
        function () {

            loadBarangays(
                this.value
            );

            updateAddressPreview();

        }
    );


    /* ============================================================
       BARANGAY CHANGE
    ============================================================ */

    barangaySelect.addEventListener(
        'change',
        function () {

            updateAddressPreview();

        }
    );


    /* ============================================================
       STREET CHANGE
    ============================================================ */

    streetInput.addEventListener(
        'input',
        function () {

            updateAddressPreview();

        }
    );


    /* ============================================================
       COMPLETE ADDRESS
    ============================================================ */

    function updateAddressPreview() {

        const street =
            streetInput.value.trim();


        const provinceOption =
            provinceSelect.options[
                provinceSelect.selectedIndex
            ];


        const cityOption =
            citySelect.options[
                citySelect.selectedIndex
            ];


        const barangayOption =
            barangaySelect.options[
                barangaySelect.selectedIndex
            ];


        const province =
            provinceOption &&
            provinceOption.value
                ? provinceOption.textContent.trim()
                : '';


        const city =
            cityOption &&
            cityOption.value
                ? cityOption.textContent.trim()
                : '';


        const barangay =
            barangayOption &&
            barangayOption.value
                ? barangayOption.textContent.trim()
                : '';


        const parts = [];


        if (street) {

            parts.push(
                street
            );

        }


        if (barangay) {

            parts.push(
                'Barangay ' + barangay
            );

        }


        if (city) {

            parts.push(
                city
            );

        }


        if (province) {

            parts.push(
                province
            );

        }


        const completeAddress =
            parts.join(', ');


        if (completeAddress) {

            addressInput.value =
                completeAddress;

            addressPreview.textContent =
                completeAddress;

        } else if (oldAddress) {

            addressInput.value =
                oldAddress;

            addressPreview.textContent =
                oldAddress;

        } else {

            addressInput.value =
                '';

            addressPreview.textContent =
                'Select the address information above.';

        }

    }


    /* ============================================================
       PHONE NUMBER
    ============================================================ */

    function normalizePhone(
        value
    ) {

        let phone =
            String(value || '')
                .replace(
                    /\D/g,
                    ''
                );


        if (
            phone.startsWith('63') &&
            phone.length >= 12
        ) {

            phone =
                phone.substring(2);

        }


        if (
            phone.startsWith('0')
        ) {

            phone =
                phone.substring(1);

        }


        return phone.substring(
            0,
            10
        );

    }


    phoneInput.value =
        normalizePhone(
            phoneInput.value
        );


    /* ============================================================
       PHONE INPUT
    ============================================================ */

    phoneInput.addEventListener(
        'input',
        function () {

            let value =
                this.value.replace(
                    /\D/g,
                    ''
                );


            if (
                value.startsWith('63') &&
                value.length > 10
            ) {

                value =
                    value.substring(2);

            }


            if (
                value.startsWith('0')
            ) {

                value =
                    value.substring(1);

            }


            value =
                value.substring(
                    0,
                    10
                );


            this.value =
                value;

        }
    );


    /* ============================================================
       FORM SUBMIT
    ============================================================ */

    document
        .getElementById(
            'supplier-edit-form'
        )
        .addEventListener(
            'submit',
            function () {

                updateAddressPreview();


                let phone =
                    phoneInput.value.replace(
                        /\D/g,
                        ''
                    );


                if (phone) {

                    if (
                        phone.startsWith('63')
                    ) {

                        phone =
                            phone.substring(2);

                    }


                    if (
                        phone.startsWith('0')
                    ) {

                        phone =
                            phone.substring(1);

                    }


                    phone =
                        phone.substring(
                            0,
                            10
                        );


                    phoneInput.value =
                        '+63' + phone;

                }

            }
        );


    /* ============================================================
       INITIALIZE ADDRESS
    ============================================================ */

    loadProvinces();


    /*
     * Preserve street value.
     */

    if (oldStreet) {

        streetInput.value =
            oldStreet;

    }


    /*
     * Preserve existing address while
     * dropdowns are loading.
     */

    if (oldAddress) {

        addressInput.value =
            oldAddress;

        addressPreview.textContent =
            oldAddress;

    }

});

</script>

@endpush