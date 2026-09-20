@extends('layouts.app')

@section('title', 'BiteSync | Add Supplier')

@section('content')

    <div class="supplier-create-page">

        <!-- =========================================================
             TOP BAR
        ========================================================== -->

        <div class="topbar">

            <div class="page-title">

                <small>
                    Supplier Management
                </small>

                <h1>
                    Add Supplier
                </h1>

                <p>
                    Add a new supplier and record complete contact and address information.
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
             BREADCRUMB
        ========================================================== -->

        <div class="supplier-breadcrumb">

            <a href="{{ route('suppliers.index') }}">
                Suppliers
            </a>

            <span>
                /
            </span>

            <strong>
                Add Supplier
            </strong>

        </div>


        <!-- =========================================================
             VALIDATION ERRORS
        ========================================================== -->

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


        <!-- =========================================================
             FORM
        ========================================================== -->

        <form
            method="POST"
            action="{{ route('suppliers.store') }}"
            id="supplier-create-form"
            class="supplier-form"
        >

            @csrf


            <!-- =====================================================
                 BASIC INFORMATION + CONTACT INFORMATION
            ====================================================== -->

            <div class="information-grid">


                <!-- =================================================
                     BASIC INFORMATION
                ================================================== -->

                <div class="form-panel">

                    <div class="form-panel-header">

                        <div>

                            <div class="form-panel-title">
                                Basic Information
                            </div>

                            <div class="form-panel-subtitle">
                                Enter the supplier's primary business information.
                            </div>

                        </div>

                        <div class="form-panel-icon">
                            ▦
                        </div>

                    </div>


                    <div class="form-content">

                        <div class="basic-fields-grid">


                            <!-- SUPPLIER NAME -->

                            <div class="form-group supplier-full-width">

                                <label for="name">

                                    Supplier Name
                                    <span>*</span>

                                </label>

                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="Enter supplier or company name"
                                    maxlength="255"
                                    required
                                >

                                @error('name')

                                    <div class="field-error">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <!-- CONTACT PERSON -->

                            <div class="form-group">

                                <label for="contact_person">
                                    Contact Person
                                </label>

                                <input
                                    type="text"
                                    id="contact_person"
                                    name="contact_person"
                                    value="{{ old('contact_person') }}"
                                    placeholder="Enter contact person's name"
                                    maxlength="255"
                                >

                                @error('contact_person')

                                    <div class="field-error">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <!-- EMAIL -->

                            <div class="form-group">

                                <label for="email">
                                    Email Address
                                </label>

                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="supplier@example.com"
                                    maxlength="255"
                                >

                                @error('email')

                                    <div class="field-error">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <!-- PHONE -->

                            <div class="form-group supplier-full-width">

                                <label for="phone">
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
                                        value="{{ old('phone') }}"
                                        placeholder="912 345 6789"
                                        maxlength="10"
                                        inputmode="numeric"
                                        autocomplete="tel"
                                    >

                                </div>

                                @error('phone')

                                    <div class="field-error">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     SUPPLIER STATUS
                ================================================== -->

                <div class="form-panel">

                    <div class="form-panel-header">

                        <div>

                            <div class="form-panel-title">
                                Supplier Status
                            </div>

                            <div class="form-panel-subtitle">
                                Set the current availability of this supplier.
                            </div>

                        </div>

                        <div class="form-panel-icon">
                            ✓
                        </div>

                    </div>


                    <div class="form-content">

                        <div class="status-fields-grid">


                            <!-- STATUS -->

                            <div class="form-group">

                                <label for="status">
                                    Status
                                    <span>*</span>
                                </label>

                                <select
                                    id="status"
                                    name="status"
                                    required
                                >

                                    <option
                                        value="Active"
                                        {{ old('status', 'Active') === 'Active' ? 'selected' : '' }}
                                    >
                                        Active
                                    </option>

                                    <option
                                        value="Inactive"
                                        {{ old('status') === 'Inactive' ? 'selected' : '' }}
                                    >
                                        Inactive
                                    </option>

                                </select>

                                @error('status')

                                    <div class="field-error">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <!-- CONTACT SUMMARY -->

                            <div class="supplier-status-note">

                                <div class="supplier-status-note-icon">
                                    i
                                </div>

                                <div>

                                    <strong>
                                        Supplier availability
                                    </strong>

                                    <p>
                                        Active suppliers can be used for future purchasing records.
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- =========================================================
                 ADDRESS INFORMATION
            ========================================================== -->

            <div class="form-panel additional-panel">

                <div class="form-panel-header">

                    <div>

                        <div class="form-panel-title">
                            Address Information
                        </div>

                        <div class="form-panel-subtitle">
                            Select the supplier's location using Philippine address information.
                        </div>

                    </div>

                    <div class="form-panel-icon">
                        ⌖
                    </div>

                </div>


                <div class="form-content">

                    <div class="address-fields-grid">


                        <!-- STREET -->

                        <div class="form-group address-full-width">

                            <label for="street">
                                Street / Building / House No.
                            </label>

                            <input
                                type="text"
                                id="street"
                                name="street"
                                value="{{ old('street') }}"
                                placeholder="Example: 123 Main Street, Building A"
                                maxlength="255"
                            >

                            @error('street')

                                <div class="field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- PROVINCE -->

                        <div class="form-group">

                            <label for="province">

                                Province
                                <span>*</span>

                            </label>

                            <select
                                id="province"
                                name="province"
                                required
                            >

                                <option value="">
                                    Loading provinces...
                                </option>

                            </select>

                            @error('province')

                                <div class="field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- CITY / MUNICIPALITY -->

                        <div class="form-group">

                            <label for="city_municipality">

                                City / Municipality
                                <span>*</span>

                            </label>

                            <select
                                id="city_municipality"
                                name="city_municipality"
                                required
                                disabled
                            >

                                <option value="">
                                    Select province first
                                </option>

                            </select>

                            @error('city_municipality')

                                <div class="field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- BARANGAY -->

                        <div class="form-group">

                            <label for="barangay">

                                Barangay
                                <span>*</span>

                            </label>

                            <select
                                id="barangay"
                                name="barangay"
                                required
                                disabled
                            >

                                <option value="">
                                    Select city / municipality first
                                </option>

                            </select>

                            @error('barangay')

                                <div class="field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- ADDRESS PREVIEW -->

                        <div class="form-group address-full-width">

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
                                        Select the address information above.
                                    </div>

                                </div>

                            </div>

                            <input
                                type="hidden"
                                id="address"
                                name="address"
                                value="{{ old('address') }}"
                            >

                            @error('address')

                                <div class="field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            <!-- =========================================================
                 ADDITIONAL INFORMATION
            ========================================================== -->

            <div class="form-panel additional-panel">

                <div class="form-panel-header">

                    <div>

                        <div class="form-panel-title">
                            Additional Information
                        </div>

                        <div class="form-panel-subtitle">
                            Add optional notes about this supplier.
                        </div>

                    </div>

                    <div class="form-panel-icon">
                        ≡
                    </div>

                </div>


                <div class="form-content">

                    <div class="form-group">

                        <label for="notes">
                            Notes
                        </label>

                        <textarea
                            id="notes"
                            name="notes"
                            rows="4"
                            maxlength="5000"
                            placeholder="Optional notes about this supplier..."
                        >{{ old('notes') }}</textarea>

                        @error('notes')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>


            <!-- =========================================================
                 INFORMATION NOTE
            ========================================================== -->

            <div class="supplier-information-box">

                <div class="supplier-information-icon">
                    i
                </div>

                <div>

                    <strong>
                        Supplier records are retained.
                    </strong>

                    <p>
                        Suppliers should not be permanently deleted because
                        purchasing records may reference them in the future.
                        You can deactivate a supplier when they are no longer active.
                    </p>

                </div>

            </div>


            <!-- =========================================================
                 ACTION BUTTONS
            ========================================================== -->

            <div class="form-actions">

                <a
                    href="{{ route('suppliers.index') }}"
                    class="cancel-button"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="save-button"
                >

                    <span>
                        +
                    </span>

                    Add Supplier

                </button>

            </div>

        </form>

    </div>

@endsection


@push('styles')

<style>

/* ================================================================
   PAGE
================================================================ */

.supplier-create-page {

    width: 100%;

    max-width: 1120px;

    margin: 0 auto;

    padding-bottom: 30px;

}


/* ================================================================
   TOP BAR
================================================================ */

.supplier-create-page .topbar {

    display: flex;

    align-items: flex-start;

    justify-content: space-between;

    gap: 25px;

    margin-bottom: 14px;

}


.supplier-create-page .page-title small {

    display: block;

    margin-bottom: 5px;

    color: #a9825b;

    font-size: 10px;

    font-weight: 700;

    letter-spacing: 0.12em;

    text-transform: uppercase;

}


.supplier-create-page .page-title h1 {

    margin: 0;

    color: var(--dark);

    font-size: 29px;

    font-weight: 700;

    line-height: 1.15;

    letter-spacing: -0.045rem;

}


.supplier-create-page .page-title p {

    margin: 6px 0 0;

    color: var(--muted);

    font-size: 12px;

    line-height: 1.5;

    font-weight: 400;

}


/* ================================================================
   DATE
================================================================ */

.supplier-create-page .date-box {

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


.supplier-create-page .date-icon {

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

.supplier-breadcrumb {

    display: flex;

    align-items: center;

    gap: 7px;

    margin-bottom: 17px;

    color: #96877b;

    font-size: 10px;

    font-weight: 600;

    line-height: 1.3;

}


.supplier-breadcrumb a {

    color: #a16e42;

    font-weight: 600;

    text-decoration: none;

    transition:
        color 0.18s ease;

}


.supplier-breadcrumb a:hover {

    color: #7d4e29;

}


.supplier-breadcrumb span {

    color: #c2b5aa;

}


.supplier-breadcrumb strong {

    color: #6f6259;

    font-weight: 600;

}


/* ================================================================
   ALERT
================================================================ */

.form-alert {

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


.form-alert-error {

    border: 1px solid #efd4cf;

    background: #fff7f5;

    color: #7d433b;

}


.alert-icon {

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


.alert-title {

    margin-bottom: 4px;

    font-size: 11px;

    font-weight: 700;

}


.alert-list {

    margin: 4px 0 0;

    padding-left: 16px;

    line-height: 1.45;

}


.alert-list li {

    margin-bottom: 2px;

    font-size: 11px;

    font-weight: 400;

}


/* ================================================================
   SIDE-BY-SIDE PANELS
================================================================ */

.information-grid {

    display: grid;

    grid-template-columns:
        minmax(0, 1fr)
        minmax(0, 1fr);

    gap: 17px;

    align-items: stretch;

    margin-bottom: 17px;

}


/* ================================================================
   FORM PANEL
================================================================ */

.form-panel {

    overflow: hidden;

    border: 1px solid var(--border);

    border-radius: 15px;

    background: white;

    box-shadow:
        0 4px 16px
        rgba(43, 31, 23, 0.035);

}


.additional-panel {

    margin-bottom: 17px;

}


/* ================================================================
   PANEL HEADER
================================================================ */

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


/* ================================================================
   CONTENT
================================================================ */

.form-content {

    padding: 18px;

}


/* ================================================================
   BASIC FIELDS
================================================================ */

.basic-fields-grid {

    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 17px 15px;

}


.supplier-full-width {

    grid-column: 1 / -1;

}


/* ================================================================
   STATUS FIELDS
================================================================ */

.status-fields-grid {

    display: grid;

    grid-template-columns:
        minmax(0, 1fr)
        minmax(0, 1fr);

    gap: 15px;

    align-items: start;

}


/* ================================================================
   ADDRESS FIELDS
================================================================ */

.address-fields-grid {

    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 17px 15px;

}


.address-full-width {

    grid-column: 1 / -1;

}


/* ================================================================
   FORM GROUP
================================================================ */

.form-group {

    min-width: 0;

}


.form-group label {

    display: block;

    margin-bottom: 6px;

    color: #4c3c31;

    font-size: 11px;

    line-height: 1.35;

    font-weight: 600;

}


.form-group label span {

    color: #b65f45;

    font-weight: 600;

}


/* ================================================================
   INPUTS
================================================================ */

.form-group input,
.form-group select,
.form-group textarea {

    width: 100%;

    box-sizing: border-box;

    border: 1px solid #ded4cb;

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


.form-group input,
.form-group select {

    height: 39px;

    padding: 0 11px;

}


.form-group textarea {

    min-height: 100px;

    padding: 10px 11px;

    resize: vertical;

    line-height: 1.5;

}


.form-group input::placeholder,
.form-group textarea::placeholder {

    color: #b8aaa0;

    font-weight: 400;

}


.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {

    border-color: #d2a47b;

    box-shadow:
        0 0 0 3px
        rgba(196, 122, 58, 0.075);

}


.form-group select {

    cursor: pointer;

}


.form-group select:disabled {

    background: #f7f4f1;

    color: #aaa19a;

    cursor: not-allowed;

}


/* ================================================================
   FIELD ERROR
================================================================ */

.field-error {

    margin-top: 5px;

    color: #a64b3f;

    font-size: 10px;

    line-height: 1.4;

    font-weight: 600;

}


/* ================================================================
   PHONE
================================================================ */

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

    line-height: 1;

    font-weight: 600;

    pointer-events: none;

}


.phone-input input {

    padding-left: 42px;

}


/* ================================================================
   STATUS NOTE
================================================================ */

.supplier-status-note {

    display: flex;

    align-items: flex-start;

    gap: 9px;

    min-height: 65px;

    padding: 10px;

    border: 1px solid #e9dfd5;

    border-radius: 8px;

    background: #fbf8f4;

}


.supplier-status-note-icon {

    width: 22px;

    height: 22px;

    flex-shrink: 0;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 7px;

    background: #eadbc9;

    color: #855b38;

    font-size: 9px;

    font-weight: 700;

}


.supplier-status-note strong {

    display: block;

    margin-bottom: 3px;

    color: #594536;

    font-size: 10px;

    line-height: 1.35;

    font-weight: 700;

}


.supplier-status-note p {

    margin: 0;

    color: #95867b;

    font-size: 9px;

    line-height: 1.45;

    font-weight: 400;

}


/* ================================================================
   ADDRESS PREVIEW
================================================================ */

.address-preview {

    display: flex;

    align-items: flex-start;

    gap: 9px;

    margin-top: 0;

    padding: 10px;

    border: 1px solid #e9dfd5;

    border-radius: 8px;

    background: #fbf8f4;

}


.address-preview-icon {

    width: 22px;

    height: 22px;

    flex-shrink: 0;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 7px;

    background: #eadbc9;

    color: #855b38;

    font-size: 10px;

    font-weight: 700;

}


.address-preview-content {

    min-width: 0;

}


.address-preview-title {

    margin-bottom: 3px;

    color: #594536;

    font-size: 10px;

    line-height: 1.35;

    font-weight: 700;

}


.address-preview-text {

    color: #95867b;

    font-size: 9px;

    line-height: 1.45;

    font-weight: 400;

    word-break: break-word;

}


/* ================================================================
   INFORMATION BOX
================================================================ */

.supplier-information-box {

    display: flex;

    align-items: flex-start;

    gap: 9px;

    margin-bottom: 17px;

    padding: 10px;

    border: 1px solid #e9dfd5;

    border-radius: 8px;

    background: #fbf8f4;

}


.supplier-information-icon {

    width: 22px;

    height: 22px;

    flex-shrink: 0;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 7px;

    background: #eadbc9;

    color: #855b38;

    font-size: 9px;

    font-weight: 700;

}


.supplier-information-box strong {

    display: block;

    margin-bottom: 3px;

    color: #594536;

    font-size: 10px;

    line-height: 1.35;

    font-weight: 700;

}


.supplier-information-box p {

    margin: 0;

    color: #95867b;

    font-size: 9px;

    line-height: 1.45;

    font-weight: 400;

}


/* ================================================================
   ACTION BUTTONS
================================================================ */

.form-actions {

    display: flex;

    align-items: center;

    justify-content: flex-end;

    gap: 8px;

    padding-top: 0;

    padding-bottom: 4px;

}


.cancel-button,
.save-button {

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

}


/* ================================================================
   CANCEL
================================================================ */

.cancel-button {

    border: 1px solid var(--border);

    background: white;

    color: var(--muted);

    transition:
        background-color 0.18s ease,
        border-color 0.18s ease,
        color 0.18s ease;

}


.cancel-button:hover {

    border-color: #d4c6ba;

    background: #faf7f4;

    color: var(--dark);

}


/* ================================================================
   SAVE
================================================================ */

.save-button {

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

    transition:
        transform 0.18s ease,
        box-shadow 0.18s ease;

}


.save-button:hover {

    background:
        linear-gradient(
            135deg,
            var(--orange-dark),
            var(--orange-dark)
        );

    transform: translateY(-1px);

    box-shadow:
        0 6px 14px
        rgba(145, 97, 55, 0.18);

}


.save-button span {

    font-size: 13px;

    line-height: 1;

    font-weight: 400;

}


/* ================================================================
   TABLET
================================================================ */

@media (max-width: 1100px) {

    .information-grid {

        grid-template-columns: 1fr;

    }

}


/* ================================================================
   MOBILE
================================================================ */

@media (max-width: 700px) {

    .supplier-create-page {

        max-width: 100%;

    }


    .supplier-create-page .topbar {

        flex-direction: column;

        align-items: flex-start;

        gap: 12px;

    }


    .supplier-create-page .date-box {

        align-self: flex-start;

    }


    .basic-fields-grid,
    .address-fields-grid,
    .status-fields-grid {

        grid-template-columns: 1fr;

    }


    .supplier-full-width,
    .address-full-width {

        grid-column: auto;

    }


    .form-content {

        padding: 15px;

    }


    .form-panel-header {

        padding: 14px 15px;

    }


    .form-actions {

        justify-content: stretch;

    }


    .cancel-button,
    .save-button {

        flex: 1;

    }

}


/* ================================================================
   SMALL MOBILE
================================================================ */

@media (max-width: 480px) {

    .supplier-create-page .page-title h1 {

        font-size: 23px;

    }


    .form-panel-title {

        font-size: 17px;

    }


    .form-panel-subtitle {

        max-width: 220px;

        font-size: 11px;

    }


    .form-actions {

        flex-direction: column-reverse;

    }


    .cancel-button,
    .save-button {

        width: 100%;

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
        'https://psgc.gitlab.io/api';


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

    const oldStreet =
        @json(old('street', ''));

    const oldAddress =
        @json(old('address', ''));


    /* ============================================================
       FETCH JSON
    ============================================================ */

    async function fetchJson(url) {

        const response =
            await fetch(
                url,
                {
                    headers: {
                        'Accept': 'application/json'
                    }
                }
            );


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


        option.value =
            '';


        option.textContent =
            placeholder;


        select.appendChild(
            option
        );


        select.disabled =
            true;

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


        firstOption.value =
            '';


        firstOption.textContent =
            placeholder;


        select.appendChild(
            firstOption
        );


        items.forEach(function (item) {

            const option =
                document.createElement('option');


            option.value =
                item.code;


            option.textContent =
                item.name;


            option.dataset.name =
                item.name;


            select.appendChild(
                option
            );

        });


        select.disabled =
            false;

    }


    /* ============================================================
       FIND OPTION
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
                    `${API_BASE}/provinces/`
                );


            populateSelect(
                provinceSelect,
                provinces,
                'Select province'
            );


            const provinceOption =
                findOption(
                    provinceSelect,
                    oldProvince
                );


            if (provinceOption) {

                provinceOption.selected =
                    true;


                await loadCities(
                    provinceOption.value
                );

            }


        } catch (error) {

            console.error(
                'Province loading error:',
                error
            );


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
                    `${API_BASE}/provinces/${encodeURIComponent(provinceCode)}/cities-municipalities/`
                );


            populateSelect(
                citySelect,
                cities,
                'Select city / municipality'
            );


            const cityOption =
                findOption(
                    citySelect,
                    oldCity
                );


            if (cityOption) {

                cityOption.selected =
                    true;


                await loadBarangays(
                    cityOption.value
                );

            }

        } catch (error) {

            console.error(
                'City loading error:',
                error
            );


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
                    `${API_BASE}/cities-municipalities/${encodeURIComponent(cityCode)}/barangays/`
                );


            populateSelect(
                barangaySelect,
                barangays,
                'Select barangay'
            );


            const barangayOption =
                findOption(
                    barangaySelect,
                    oldBarangay
                );


            if (barangayOption) {

                barangayOption.selected =
                    true;

            }


            updateAddressPreview();

        } catch (error) {

            console.error(
                'Barangay loading error:',
                error
            );


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
       PHONE NORMALIZATION
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
            phone.length > 10
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


    /* ============================================================
       INITIAL PHONE VALUE
    ============================================================ */

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
            'supplier-create-form'
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
       INITIALIZE
    ============================================================ */

    if (oldStreet) {

        streetInput.value =
            oldStreet;

    }


    loadProvinces();

});

</script>

@endpush