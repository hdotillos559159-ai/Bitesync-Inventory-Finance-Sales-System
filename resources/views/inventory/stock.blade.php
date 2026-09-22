@extends('layouts.app')

@section('title', 'BiteSync | Stock Transactions')

@section('content')

<style>

/* ================================================================
   PAGE
================================================================ */

.stock-page {
    width: 100%;
    max-width: 1120px;
    margin: 0 auto;
    padding-bottom: 30px;
}


/* ================================================================
   TOP BAR
================================================================ */

.stock-page .topbar {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 25px;
    margin-bottom: 14px;
}

.stock-page .page-title {
    min-width: 0;
}

.stock-page .page-title small {
    display: block;
    margin-bottom: 5px;
    color: #a9825b;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
}

.stock-page .page-title h1 {
    margin: 0;
    color: var(--dark);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.15;
    letter-spacing: -0.045rem;
}

.stock-page .page-title p {
    margin: 6px 0 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.5;
    font-weight: 400;
}


/* ================================================================
   DATE
================================================================ */

.stock-page .date-box {
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
    box-shadow: 0 3px 12px rgba(43, 31, 23, .025);
}

.stock-page .date-icon {
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

.stock-page .date-content {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.stock-page .date-label {
    color: #9a8d82;
    font-size: 8px;
    line-height: 1;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
}

.stock-page .date-value {
    color: var(--muted);
    font-size: 10px;
    line-height: 1.2;
    font-weight: 600;
}


/* ================================================================
   BREADCRUMB
================================================================ */

.stock-breadcrumb {
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

.stock-breadcrumb a {
    color: #a16e42;
    font-weight: 600;
    text-decoration: none;
    transition: color .18s ease;
}

.stock-breadcrumb a:hover {
    color: #7d4e29;
}

.stock-breadcrumb span {
    color: #c2b5aa;
}

.stock-breadcrumb .current {
    color: #6f6259;
    font-weight: 600;
}


/* ================================================================
   ALERT
================================================================ */

.stock-page .form-alert {
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

.stock-page .form-alert-success {
    border: 1px solid #cfe6d7;
    background: #f3faf5;
    color: #35634a;
}

.stock-page .form-alert-error {
    border: 1px solid #efd4cf;
    background: #fff7f5;
    color: #7d433b;
}

.stock-page .alert-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    flex-shrink: 0;
    border-radius: 7px;
    font-size: 11px;
    font-weight: 700;
}

.stock-page .form-alert-success .alert-icon {
    background: #dcefe2;
    color: #39704e;
}

.stock-page .form-alert-error .alert-icon {
    background: #f3d4cf;
    color: #8a4339;
}

.stock-page .alert-title {
    margin-bottom: 4px;
    font-size: 11px;
    font-weight: 700;
}

.stock-page .alert-list {
    margin: 4px 0 0;
    padding-left: 16px;
    line-height: 1.45;
}

.stock-page .alert-list li {
    margin-bottom: 2px;
    font-size: 11px;
    font-weight: 400;
}


/* ================================================================
   ITEM SUMMARY
================================================================ */

.stock-page .item-summary {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 17px;
    padding: 15px 17px;
    border: 1px solid var(--border);
    border-radius: 15px;
    background: white;
    box-shadow: 0 4px 16px rgba(43, 31, 23, .035);
}

.stock-page .item-main {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.stock-page .item-image {
    width: 50px;
    height: 50px;
    flex: 0 0 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border: 1px solid #e5dbd0;
    border-radius: 10px;
    background: #f8f3ee;
    color: #79583c;
    font-size: 21px;
}

.stock-page .item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.stock-page .item-info {
    min-width: 0;
}

.stock-page .item-name {
    margin: 0 0 4px;
    color: var(--dark);
    font-size: 17px;
    line-height: 1.3;
    font-weight: 700;
}

.stock-page .item-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 5px 12px;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.4;
}

.stock-page .item-meta strong {
    color: #5d5047;
    font-weight: 600;
}

.stock-page .stock-summary {
    display: flex;
    align-items: center;
    gap: 22px;
    flex: 0 0 auto;
}

.stock-page .stock-stat {
    text-align: right;
}

.stock-page .stock-stat-label {
    display: block;
    margin-bottom: 4px;
    color: #9a8c81;
    font-size: 9px;
    line-height: 1.3;
    font-weight: 700;
    letter-spacing: .055em;
    text-transform: uppercase;
}

.stock-page .stock-stat-value {
    color: #594536;
    font-size: 17px;
    line-height: 1;
    font-weight: 700;
}

.stock-page .stock-stat-value.warning {
    color: var(--orange);
}


/* ================================================================
   SECTION / PANEL
================================================================ */

.stock-page .section {
    overflow: hidden;
    margin-bottom: 17px;
    border: 1px solid var(--border);
    border-radius: 15px;
    background: white;
    box-shadow: 0 4px 16px rgba(43, 31, 23, .035);
}

.stock-page .section-header {
    min-height: 65px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 14px 19px;
    border-bottom: 1px solid var(--border);
    background: white;
}

.stock-page .section-title {
    margin: 0;
    color: var(--dark);
    font-size: 17px;
    line-height: 1.3;
    font-weight: 700;
}

.stock-page .section-subtitle {
    margin: 3px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.4;
    font-weight: 400;
}

.stock-page .section-header-icon {
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

.stock-page .section-content {
    padding: 18px;
}


/* ================================================================
   OPERATION NOTICE
================================================================ */

.stock-page .operation-notice {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    margin-bottom: 14px;
    padding: 10px;
    border: 1px solid #e9dfd5;
    border-radius: 8px;
    background: #fbf8f4;
    color: #75675c;
    font-size: 10px;
    line-height: 1.5;
}

.stock-page .operation-notice-icon {
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


/* ================================================================
   OPERATIONS GRID
================================================================ */

.stock-page .operations-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 13px;
}

.stock-page .operation-card {
    padding: 15px;
    border: 1px solid #e8e0d9;
    border-radius: 10px;
    background: #fff;
}


/* ================================================================
   STOCK IN / STOCK OUT VISUAL DISTINCTION
================================================================ */

.stock-page .operation-card.stock-in-card {
    border: 1px solid #d6e7da;
    border-top: 3px solid #5f9b70;
    background: #fcfefc;
    box-shadow: 0 4px 13px rgba(62, 112, 77, .055);
}

.stock-page .operation-card.stock-out-card {
    border: 1px solid #ecd8d4;
    border-top: 3px solid #a55b50;
    background: #fffdfc;
    box-shadow: 0 4px 13px rgba(153, 74, 64, .055);
}

.stock-page .stock-operation-label {
    display: inline-flex;
    align-items: center;
    width: max-content;
    margin-bottom: 5px;
    padding: 3px 6px;
    border-radius: 5px;
    font-size: 8px;
    line-height: 1;
    font-weight: 800;
    letter-spacing: .06em;
    text-transform: uppercase;
}

.stock-page .stock-in-card .stock-operation-label {
    background: #e7f3ea;
    color: #3e704d;
}

.stock-page .stock-out-card .stock-operation-label {
    background: #f8e6e2;
    color: #994a40;
}

.stock-page .stock-in-card .operation-icon {
    background: #e5f2e8;
    color: #3e704d;
}

.stock-page .stock-out-card .operation-icon {
    background: #f7e3df;
    color: #994a40;
}

.stock-page .stock-in-card .operation-title {
    color: #3f5f49;
}

.stock-page .stock-out-card .operation-title {
    color: #74453e;
}


/* ================================================================
   OPERATION HEADER
================================================================ */

.stock-page .operation-card-header {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 14px;
}

.stock-page .operation-icon {
    width: 30px;
    height: 30px;
    flex: 0 0 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: var(--orange-light);
    color: var(--orange);
    font-size: 14px;
    line-height: 1;
    font-weight: 700;
}

.stock-page .operation-title {
    margin: 0;
    color: var(--dark);
    font-size: 13px;
    line-height: 1.3;
    font-weight: 700;
}

.stock-page .operation-description {
    margin: 3px 0 0;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.45;
    font-weight: 400;
}


/* ================================================================
   FORM GRID
================================================================ */

.stock-page .form-grid {
    display: grid;
    grid-template-columns:
        minmax(0, 1fr)
        minmax(0, 1fr);
    gap: 12px;
}

.stock-page .form-grid.single {
    grid-template-columns: 1fr;
}

.stock-page .form-group {
    min-width: 0;
    margin-bottom: 0;
}

.stock-page .form-group.full {
    grid-column: 1 / -1;
}

.stock-page .form-label {
    display: block;
    margin-bottom: 6px;
    color: #4c3c31;
    font-size: 11px;
    line-height: 1.35;
    font-weight: 600;
}

.stock-page .required {
    color: #b65f45;
    font-weight: 600;
}


/* ================================================================
   FORM CONTROLS
================================================================ */

.stock-page .form-control {
    width: 100%;
    box-sizing: border-box;
    height: 39px;
    padding: 0 11px;
    border: 1px solid #ded4cb;
    border-radius: 8px;
    outline: none;
    background: white;
    color: var(--text);
    font-family: inherit;
    font-size: 12px;
    line-height: 1.4;
    font-weight: 400;
    transition:
        border-color .18s ease,
        box-shadow .18s ease;
}

.stock-page textarea.form-control {
    min-height: 100px;
    height: auto;
    padding: 10px 11px;
    resize: vertical;
    line-height: 1.5;
}

.stock-page .form-control::placeholder {
    color: #b8aaa0;
    font-weight: 400;
}

.stock-page .form-control:focus {
    border-color: #d2a47b;
    box-shadow: 0 0 0 3px rgba(196, 122, 58, .075);
}

.stock-page select.form-control {
    cursor: pointer;
}


/* ================================================================
   STOCK RECEIPT
================================================================ */

.stock-page .stock-receipt-card {
    grid-column: 1 / -1;
}

.stock-page .receipt-header-grid {
    display: grid;
    grid-template-columns:
        minmax(0, 1fr)
        minmax(0, 1fr);
    gap: 12px;
    margin-bottom: 17px;
}

.stock-page .receipt-items {
    width: 100%;
    overflow-x: auto;
}

.stock-page .receipt-items-table {
    width: 100%;
    min-width: 780px;
    border-collapse: collapse;
}

.stock-page .receipt-items-table th {
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
    white-space: nowrap;
}

.stock-page .receipt-items-table td {
    padding: 8px;
    border-bottom: 1px solid #eee8e2;
    vertical-align: middle;
}

.stock-page .receipt-items-table tbody tr:last-child td {
    border-bottom: 0;
}

.stock-page .receipt-items-table .form-control {
    min-width: 0;
}

.stock-page .receipt-item {
    transition: background-color .15s ease;
}

.stock-page .receipt-item:hover {
    background: #fcfaf8;
}

.stock-page .receipt-unit {
    min-width: 75px;
    padding: 0 9px;
    height: 39px;
    display: flex;
    align-items: center;
    border: 1px solid #e8e0d9;
    border-radius: 8px;
    background: #fcfaf8;
    color: #65564b;
    font-size: 11px;
    font-weight: 600;
}

.stock-page .receipt-total {
    min-width: 95px;
    text-align: right;
    color: #594536;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}

.stock-page .receipt-grand-total {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 16px;
    margin-top: 13px;
    padding-top: 13px;
    border-top: 1px solid #e8e0d9;
}

.stock-page .receipt-grand-total-label {
    color: #796b61;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
}

.stock-page .receipt-grand-total-value {
    color: var(--dark);
    font-size: 18px;
    font-weight: 700;
}

.stock-page .add-item-btn {
    min-height: 35px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    margin-top: 11px;
    padding: 0 12px;
    border: 1px solid #ddcfc2;
    border-radius: 8px;
    background: white;
    color: #765338;
    font-family: inherit;
    font-size: 10px;
    font-weight: 700;
    cursor: pointer;
    transition: .18s ease;
}

.stock-page .add-item-btn:hover {
    border-color: #c9ae95;
    background: #fbf7f3;
    color: #5f412b;
}

.stock-page .remove-item-btn {
    width: 30px;
    height: 30px;
    padding: 0;
    border: 0;
    background: transparent;
    color: #a55b50;
    font-family: inherit;
    font-size: 16px;
    line-height: 1;
    cursor: pointer;
}

.stock-page .remove-item-btn:hover {
    color: #8f4b41;
}

.stock-page .hidden {
    display: none !important;
}


/* ================================================================
   SYSTEM QUANTITY / VARIANCE
================================================================ */

.stock-page .system-quantity-box,
.stock-page .variance-box {
    min-height: 39px;
    box-sizing: border-box;
    display: flex;
    align-items: center;
    padding: 0 11px;
    border: 1px solid #e8e0d9;
    border-radius: 8px;
    background: #fcfaf8;
    color: #594536;
    font-size: 12px;
    line-height: 1;
    font-weight: 700;
}

.stock-page .variance-box {
    color: #75695f;
}

.stock-page .variance-box.increase {
    border-color: #cfe5d5;
    background: #f1f8f3;
    color: #3e704d;
}

.stock-page .variance-box.decrease {
    border-color: #edd1cb;
    background: #fff3f1;
    color: #994a40;
}


/* ================================================================
   BUTTONS
================================================================ */

.stock-page .button-row {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    margin-top: 14px;
}

.stock-page .btn {
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
        transform .18s ease,
        box-shadow .18s ease;
}

.stock-page .btn:hover {
    transform: translateY(-1px);
}

.stock-page .btn-primary {
    border: none;
    background:
        linear-gradient(
            135deg,
            var(--orange),
            var(--orange-dark)
        );
    color: white;
    box-shadow: 0 4px 11px rgba(145, 97, 55, .14);
}

.stock-page .btn-primary:hover {
    background:
        linear-gradient(
            135deg,
            var(--orange-dark),
            var(--orange-dark)
        );
    box-shadow: 0 6px 14px rgba(145, 97, 55, .18);
}

.stock-page .btn-danger {
    border: none;
    background: #a55b50;
    color: white;
    box-shadow: 0 4px 11px rgba(140, 72, 63, .10);
}

.stock-page .btn-danger:hover {
    background: #8f4b41;
    box-shadow: 0 6px 14px rgba(140, 72, 63, .15);
}

.stock-page .btn-adjust {
    border: none;
    background: #765c43;
    color: white;
    box-shadow: 0 4px 11px rgba(91, 69, 48, .10);
}

.stock-page .btn-adjust:hover {
    background: #604a36;
    box-shadow: 0 6px 14px rgba(91, 69, 48, .15);
}

.stock-page .btn-secondary {
    border: 1px solid var(--border);
    background: white;
    color: var(--muted);
}

.stock-page .btn-secondary:hover {
    border-color: #d4c6ba;
    background: #faf7f4;
    color: var(--dark);
}


/* ================================================================
   LEDGER
================================================================ */

.stock-page .ledger-wrapper {
    width: 100%;
    overflow-x: auto;
}

.stock-page .ledger-table {
    width: 100%;
    min-width: 900px;
    border-collapse: collapse;
}

.stock-page .ledger-table th {
    padding: 9px 10px;
    border-bottom: 1px solid #e8e0d9;
    background: #fcfaf8;
    color: #796b61;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: .055em;
    line-height: 1.3;
    text-align: left;
    text-transform: uppercase;
    white-space: nowrap;
}

.stock-page .ledger-table td {
    padding: 9px 10px;
    border-bottom: 1px solid #eee8e2;
    color: #51473f;
    font-size: 10.5px;
    line-height: 1.4;
    vertical-align: top;
}

.stock-page .ledger-table tbody tr:last-child td {
    border-bottom: 0;
}

.stock-page .ledger-table tbody tr:hover {
    background: #fcfaf8;
}


/* ================================================================
   TRANSACTION TYPE
================================================================ */

.stock-page .transaction-type {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 7px;
    border-radius: 6px;
    font-size: 9px;
    line-height: 1;
    font-weight: 700;
    white-space: nowrap;
}

.stock-page .transaction-type.stock-in {
    background: #eef7f1;
    color: #3e704d;
}

.stock-page .transaction-type.stock-out {
    background: #fff1ef;
    color: #994a40;
}

.stock-page .transaction-type.adjustment {
    background: #f4f0ea;
    color: #6d5a45;
}


/* ================================================================
   QUANTITY COLORS
================================================================ */

.stock-page .quantity-positive {
    color: #3e704d;
    font-weight: 700;
}

.stock-page .quantity-negative {
    color: #994a40;
    font-weight: 700;
}

.stock-page .quantity-adjustment {
    color: #6d5a45;
    font-weight: 700;
}


/* ================================================================
   REFERENCE
================================================================ */

.stock-page .reference-label {
    color: #62574e;
    font-weight: 700;
}

.stock-page .reference-type {
    display: block;
    margin-top: 2px;
    color: #a0968e;
    font-size: 9px;
    line-height: 1.3;
}

.stock-page .reason-text {
    max-width: 270px;
    color: #70665e;
    font-size: 10px;
    line-height: 1.45;
}

.stock-page .recorded-by {
    color: #554a42;
    font-weight: 700;
}


/* ================================================================
   EMPTY STATE
================================================================ */

.stock-page .empty-state {
    padding: 30px 18px;
    color: #897e75;
    font-size: 11px;
    line-height: 1.5;
    text-align: center;
}


/* ================================================================
   PAGINATION
================================================================ */

.stock-page .pagination-wrap {
    display: flex;
    justify-content: flex-end;
    margin-top: 13px;
}


/* ================================================================
   FOOTER ACTIONS
================================================================ */

.stock-page .page-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding-bottom: 4px;
}

.stock-page .audit-note {
    color: #95867b;
    font-size: 9px;
    line-height: 1.45;
}


/* ================================================================
   TABLET
================================================================ */

@media (max-width: 1100px) {

    .stock-page .operations-grid {
        grid-template-columns: 1fr;
    }

}


/* ================================================================
   MOBILE
================================================================ */

@media (max-width: 700px) {

    .stock-page {
        max-width: 100%;
    }

    .stock-page .topbar {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .stock-page .date-box {
        align-self: flex-start;
    }

    .stock-page .item-summary {
        align-items: flex-start;
        flex-direction: column;
    }

    .stock-page .stock-summary {
        width: 100%;
        justify-content: flex-start;
    }

    .stock-page .section-header {
        align-items: flex-start;
    }

    .stock-page .section-content {
        padding: 15px;
    }

    .stock-page .section-header {
        padding: 14px 15px;
    }

    .stock-page .form-grid,
    .stock-page .receipt-header-grid {
        grid-template-columns: 1fr;
    }

    .stock-page .form-group.full {
        grid-column: auto;
    }

    .stock-page .page-actions {
        align-items: stretch;
        flex-direction: column;
    }

    .stock-page .page-actions .btn {
        width: 100%;
    }

    .stock-page .receipt-grand-total {
        justify-content: space-between;
    }

}


/* ================================================================
   SMALL MOBILE
================================================================ */

@media (max-width: 480px) {

    .stock-page .page-title h1 {
        font-size: 23px;
    }

    .stock-page .section-title {
        font-size: 17px;
    }

    .stock-page .section-subtitle {
        max-width: 220px;
        font-size: 11px;
    }

    .stock-page .item-name {
        font-size: 16px;
    }

    .stock-page .stock-summary {
        align-items: flex-start;
        flex-direction: column;
        gap: 9px;
    }

    .stock-page .stock-stat {
        text-align: left;
    }

    .stock-page .button-row {
        flex-direction: column;
        align-items: stretch;
    }

    .stock-page .button-row .btn {
        width: 100%;
    }

}

</style>

<div class="stock-page">

{{-- =========================================================
TOPBAR
========================================================== --}}

<div class="topbar">

<div class="page-title">

    <small>
        Inventory Management
    </small>

    <h1>
        Stock Transactions
    </h1>

    <p>
        Review inventory movements and record controlled stock events.
    </p>

</div>

<div class="date-box">

    <span class="date-icon">
        ◷
    </span>

    <div class="date-content">

        <span class="date-label">
            Today
        </span>

        <span class="date-value">
            {{ now()->format('F d, Y') }}
        </span>

    </div>

</div>

</div>

{{-- =========================================================
BREADCRUMB
========================================================== --}}

<div class="stock-breadcrumb">

<a href="{{ route('inventory.index') }}">
    Inventory
</a>

<span>/</span>

<span>
    Stock Transactions
</span>

<span>/</span>

<strong class="current">
    {{ $inventoryItem->name }}
</strong>

</div>

{{-- =========================================================
SUCCESS MESSAGE
========================================================== --}}

@if(session('success'))

<div class="form-alert form-alert-success">

    <div class="alert-icon">
        ✓
    </div>

    <div>

        <div class="alert-title">
            Success
        </div>

        <div>
            {{ session('success') }}
        </div>

    </div>

</div>

@endif

{{-- =========================================================
ERROR MESSAGE
========================================================== --}}

@if(session('error'))

<div class="form-alert form-alert-error">

    <div class="alert-icon">
        !
    </div>

    <div>

        <div class="alert-title">
            Action could not be completed.
        </div>

        <div>
            {{ session('error') }}
        </div>

    </div>

</div>

@endif

{{-- =========================================================
VALIDATION ERRORS
========================================================== --}}

@if($errors->any())

<div class="form-alert form-alert-error">

    <div class="alert-icon">
        !
    </div>

    <div>

        <div class="alert-title">
            Please check the form.
        </div>

        <ul class="alert-list">

            @foreach($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

</div>

@endif

{{-- =========================================================
ITEM SUMMARY
========================================================== --}}

<div class="item-summary">

<div class="item-main">

    <div class="item-image">

        @if(!empty($inventoryItem->image))

            <img
                src="{{ asset('storage/' . $inventoryItem->image) }}"
                alt="{{ $inventoryItem->name }}"
            >

        @else

            @php
                $categoryName =
                    strtolower(
                        $inventoryItem->category->name ?? ''
                    );
            @endphp

            @if(
                str_contains($categoryName, 'drink') ||
                str_contains($categoryName, 'beverage')
            )

                🥤

            @elseif(str_contains($categoryName, 'food'))

                🍔

            @elseif(str_contains($categoryName, 'meat'))

                🥩

            @elseif(str_contains($categoryName, 'vegetable'))

                🥬

            @elseif(str_contains($categoryName, 'fruit'))

                🍎

            @else

                📦

            @endif

        @endif

    </div>

    <div class="item-info">

        <h2 class="item-name">
            {{ $inventoryItem->name }}
        </h2>

        <div class="item-meta">

            <span>
                SKU:
                <strong>
                    {{ $inventoryItem->sku ?: '—' }}
                </strong>
            </span>

            <span>
                Category:
                <strong>
                    {{ $inventoryItem->category->name ?? '—' }}
                </strong>
            </span>

            <span>
                Unit:
                <strong>
                    {{ $inventoryItem->unit->name ?? '—' }}
                </strong>
            </span>

        </div>

    </div>

</div>

<div class="stock-summary">

    <div class="stock-stat">

        <span class="stock-stat-label">
            Current Stock
        </span>

        <span class="stock-stat-value">
            {{ number_format($inventoryItem->quantity, 2) }}
        </span>

    </div>

    <div class="stock-stat">

        <span class="stock-stat-label">
            Minimum
        </span>

        <span class="stock-stat-value warning">
            {{ number_format($inventoryItem->minimum_stock, 2) }}
        </span>

    </div>

</div>

</div>

{{-- =========================================================
CONTROLLED STOCK OPERATIONS
========================================================== --}}

@if(in_array($user->role, ['CEO/Admin', 'Procurement'], true))

<section class="section">

<div class="section-header">

    <div>

        <h2 class="section-title">
            Controlled Stock Operations
        </h2>

        <p class="section-subtitle">
            Use controlled transactions so every inventory quantity change is recorded in the stock ledger.
        </p>

    </div>

    <div class="section-header-icon">
        ⇄
    </div>

</div>

<div class="section-content">

    {{-- =====================================================
         OPERATION NOTICE
    ====================================================== --}}

    <div class="operation-notice">

        <div class="operation-notice-icon">
            i
        </div>

        <div>

            Use <strong>Manual Stock In</strong> for legitimate off-system receipts,
            <strong>Manual Stock Out</strong> for justified inventory losses,
            and <strong>Adjustment</strong> only when the physical count differs from the system quantity.

        </div>

    </div>


    <div class="operations-grid">


        {{-- =================================================
             MANUAL STOCK IN
        ================================================== --}}

        <div class="operation-card stock-receipt-card stock-in-card">

            <div class="operation-card-header">

                <div class="operation-icon">
                    +
                </div>

                <div>

                    <div class="stock-operation-label">
                        Stock In
                    </div>

                    <h3 class="operation-title">
                        Manual Stock In
                    </h3>

                    <p class="operation-description">
                        Record legitimate stock received outside the purchase workflow. Multiple groceries can be recorded under one receipt.
                    </p>

                </div>

            </div>


            <form
                method="POST"
                action="{{ route('inventory.stock-receipt.store') }}"
                id="stockReceiptForm"
            >

                @csrf


                {{-- =================================================
                     RECEIPT INFORMATION
                ================================================== --}}

                <div class="receipt-header-grid">


                    <div class="form-group">

                        <label class="form-label">

                            Source Type

                            <span class="required">
                                *
                            </span>

                        </label>

                        <select
                            name="source_type"
                            id="sourceType"
                            class="form-control"
                            required
                        >

                            <option value="">
                                Select source
                            </option>

                            <option
                                value="supplier"
                                @selected(old('source_type') === 'supplier')
                            >
                                Supplier
                            </option>

                            <option
                                value="store"
                                @selected(old('source_type') === 'store')
                            >
                                Store
                            </option>

                            <option
                                value="other"
                                @selected(old('source_type') === 'other')
                            >
                                Other
                            </option>

                        </select>

                    </div>


                    <div
                        class="form-group"
                        id="supplierGroup"
                    >

                        <label class="form-label">

                            Supplier

                            <span class="required">
                                *
                            </span>

                        </label>

                        <select
                            name="supplier_id"
                            id="supplierId"
                            class="form-control"
                        >

                            <option value="">
                                Select supplier
                            </option>

                            @foreach($suppliers ?? [] as $supplier)

                                <option
                                    value="{{ $supplier->id }}"
                                    @selected(
                                        old('supplier_id') == $supplier->id
                                    )
                                >
                                    {{ $supplier->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div
                        class="form-group hidden"
                        id="sourceNameGroup"
                    >

                        <label class="form-label">

                            Store / Source Name

                            <span class="required">
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            name="source_name"
                            id="sourceName"
                            class="form-control"
                            maxlength="255"
                            value="{{ old('source_name') }}"
                            placeholder="e.g. Local Grocery Store"
                        >

                    </div>


                    <div class="form-group">

                        <label class="form-label">

                            Receipt / Reference No.

                            <span class="required">
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            name="reference_number"
                            class="form-control"
                            maxlength="100"
                            value="{{ old('reference_number') }}"
                            placeholder="e.g. OR-2026-001"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label class="form-label">

                            Receipt Date

                            <span class="required">
                                *
                            </span>

                        </label>

                        <input
                            type="date"
                            name="receipt_date"
                            class="form-control"
                            value="{{ old('receipt_date', now()->format('Y-m-d')) }}"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label class="form-label">
                            Details
                        </label>

                        <input
                            type="text"
                            name="notes"
                            class="form-control"
                            maxlength="1000"
                            value="{{ old('notes') }}"
                            placeholder="Optional receipt notes"
                        >

                    </div>

                </div>


                {{-- =================================================
                     ITEMS
                ================================================== --}}

                <div class="form-group">

                    <label class="form-label">

                        Purchased Inventory Items

                        <span class="required">
                            *
                        </span>

                    </label>

                </div>


                <div class="receipt-items">

                    <table class="receipt-items-table">

                        <thead>

                            <tr>

                                <th style="width: 31%;">
                                    Inventory Item
                                </th>

                                <th style="width: 10%;">
                                    Quantity
                                </th>

                                <th style="width: 11%;">
                                    Unit
                                </th>

                                <th style="width: 17%;">
                                    Unit Price
                                </th>

                                <th style="width: 17%; text-align:right;">
                                    Total
                                </th>

                                <th style="width: 7%; text-align:center;">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody id="receiptItemsBody">

                            @php
                                $oldItems = old('items', [
                                    [
                                        'inventory_item_id' => $inventoryItem->id,
                                        'quantity' => '',
                                        'unit_price' => '',
                                    ]
                                ]);
                            @endphp


                            @foreach($oldItems as $index => $oldItem)

                                <tr class="receipt-item">

                                    <td>

                                        <select
                                            name="items[{{ $index }}][inventory_item_id]"
                                            class="form-control inventory-select"
                                            required
                                        >

                                            <option value="">
                                                Select inventory item
                                            </option>

                                            @foreach($inventoryItems ?? [] as $item)

                                                <option
                                                    value="{{ $item->id }}"
                                                    data-unit="{{ $item->unit->name ?? '—' }}"
                                                    @selected(
                                                        ($oldItem['inventory_item_id'] ?? '') == $item->id
                                                    )
                                                >
                                                    {{ $item->name }}
                                                    @if($item->sku)
                                                        — {{ $item->sku }}
                                                    @endif
                                                </option>

                                            @endforeach

                                        </select>

                                    </td>


                                    <td>

                                        <input
                                            type="number"
                                            name="items[{{ $index }}][quantity]"
                                            class="form-control item-quantity"
                                            min="0.01"
                                            step="0.01"
                                            value="{{ $oldItem['quantity'] ?? '' }}"
                                            placeholder="0.00"
                                            required
                                        >

                                    </td>


                                    <td>

                                        <div class="receipt-unit item-unit">
                                            —
                                        </div>

                                    </td>


                                    <td>

                                        <input
                                            type="number"
                                            name="items[{{ $index }}][unit_price]"
                                            class="form-control item-price"
                                            min="0"
                                            step="0.01"
                                            value="{{ $oldItem['unit_price'] ?? '' }}"
                                            placeholder="0.00"
                                            required
                                        >

                                    </td>


                                    <td>

                                        <div class="receipt-total item-total">
                                            ₱0.00
                                        </div>

                                    </td>


                                    <td style="text-align:center;">

                                        <button
                                            type="button"
                                            class="remove-item-btn"
                                            title="Remove item"
                                            aria-label="Remove item"
                                        >
                                            ×
                                        </button>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                <button
                    type="button"
                    class="add-item-btn"
                    id="addReceiptItem"
                >
                    + Add Item
                </button>


                <div class="receipt-grand-total">

                    <span class="receipt-grand-total-label">
                        Receipt Total
                    </span>

                    <span
                        class="receipt-grand-total-value"
                        id="receiptGrandTotal"
                    >
                        ₱0.00
                    </span>

                </div>


                <div class="button-row">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Record Stock Receipt
                    </button>

                </div>

            </form>

        </div>


        {{-- =================================================
             MANUAL STOCK OUT
        ================================================== --}}

        <div class="operation-card stock-out-card">

            <div class="operation-card-header">

                <div class="operation-icon">
                    −
                </div>

                <div>

                    <div class="stock-operation-label">
                        Stock Out
                    </div>

                    <h3 class="operation-title">
                        Manual Stock Out
                    </h3>

                    <p class="operation-description">
                        Record damaged, expired, spoiled, lost, contaminated, or otherwise justified losses.
                    </p>

                </div>

            </div>


            <form
                method="POST"
                action="{{ route('inventory.stock-out', $inventoryItem) }}"
            >

                @csrf


                <div class="form-grid">

                    <div class="form-group">

                        <label class="form-label">

                            Quantity

                            <span class="required">
                                *
                            </span>

                        </label>

                        <input
                            type="number"
                            name="quantity"
                            class="form-control"
                            min="0.01"
                            step="0.01"
                            value="{{ old('quantity') }}"
                            placeholder="0.00"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label class="form-label">

                            Reason Category

                            <span class="required">
                                *
                            </span>

                        </label>

                        <select
                            name="reason_category"
                            class="form-control"
                            required
                        >

                            <option value="">
                                Select reason
                            </option>

                            <option
                                value="damaged"
                                @selected(old('reason_category') === 'damaged')
                            >
                                Damaged
                            </option>

                            <option
                                value="expired"
                                @selected(old('reason_category') === 'expired')
                            >
                                Expired
                            </option>

                            <option
                                value="spoiled"
                                @selected(old('reason_category') === 'spoiled')
                            >
                                Spoiled
                            </option>

                            <option
                                value="lost"
                                @selected(old('reason_category') === 'lost')
                            >
                                Lost
                            </option>

                            <option
                                value="contaminated"
                                @selected(old('reason_category') === 'contaminated')
                            >
                                Contaminated
                            </option>

                            <option
                                value="other"
                                @selected(old('reason_category') === 'other')
                            >
                                Other
                            </option>

                        </select>

                    </div>


                    <div class="form-group full">

                        <label class="form-label">

                            Details

                            <span class="required">
                                *
                            </span>

                        </label>

                        <textarea
                            name="reason"
                            class="form-control"
                            maxlength="1000"
                            placeholder="Describe what happened to the stock."
                            required
                        >{{ old('reason') }}</textarea>

                    </div>

                </div>


                <div class="button-row">

                    <button
                        type="submit"
                        class="btn btn-danger"
                    >
                        Record Stock Loss
                    </button>

                </div>

            </form>

        </div>


        {{-- =================================================
             PHYSICAL COUNT ADJUSTMENT
             CEO/ADMIN ONLY
        ================================================== --}}

        @if($user->role === 'CEO/Admin')

            <div class="operation-card adjustment-card">

                <div class="operation-card-header">

                    <div class="operation-icon">
                        ±
                    </div>

                    <div>

                        <h3 class="operation-title">
                            Physical Count Adjustment
                        </h3>

                        <p class="operation-description">
                            Use only when a physical count does not match the quantity recorded in the system.
                        </p>

                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route('inventory.adjust', $inventoryItem) }}"
                >

                    @csrf


                    <div class="form-grid">

                        <div class="form-group">

                            <label class="form-label">
                                System Quantity
                            </label>

                            <div
                                class="system-quantity-box"
                                id="systemQuantity"
                                data-value="{{ $inventoryItem->quantity }}"
                            >
                                {{ number_format($inventoryItem->quantity, 2) }}
                            </div>

                        </div>


                        <div class="form-group">

                            <label class="form-label">

                                Actual Physical Count

                                <span class="required">
                                    *
                                </span>

                            </label>

                            <input
                                type="number"
                                name="quantity"
                                id="physicalQuantity"
                                class="form-control"
                                min="0"
                                step="0.01"
                                value="{{ old('quantity') }}"
                                placeholder="Enter actual count"
                                required
                            >

                        </div>


                        <div class="form-group">

                            <label class="form-label">
                                Variance
                            </label>

                            <div
                                class="variance-box"
                                id="variancePreview"
                            >
                                No variance
                            </div>

                        </div>


                        <div class="form-group full">

                            <label class="form-label">

                                Count Explanation

                                <span class="required">
                                    *
                                </span>

                            </label>

                            <textarea
                                name="reason"
                                class="form-control"
                                maxlength="1000"
                                placeholder="Explain the physical count difference."
                                required
                            >{{ old('reason') }}</textarea>

                        </div>

                    </div>


                    <div class="button-row">

                        <button
                            type="submit"
                            class="btn btn-adjust"
                        >
                            Record Count Adjustment
                        </button>

                    </div>

                </form>

            </div>

        @endif

    </div>

</div>

</section>

@endif

{{-- =========================================================
STOCK LEDGER
========================================================== --}}

<section class="section">

<div class="section-header">

    <div>

        <h2 class="section-title">
            Stock Movement Ledger
        </h2>

        <p class="section-subtitle">
            Every quantity change is recorded here for inventory traceability and review.
        </p>

    </div>

    <div class="section-header-icon">
        ≡
    </div>

</div>


<div class="section-content">

    <div class="ledger-wrapper">

        <table class="ledger-table">

            <thead>

                <tr>

                    <th>
                        Date & Time
                    </th>

                    <th>
                        Transaction
                    </th>

                    <th>
                        Reference
                    </th>

                    <th>
                        Quantity Change
                    </th>

                    <th>
                        Stock Before
                    </th>

                    <th>
                        Stock After
                    </th>

                    <th>
                        Recorded By
                    </th>

                    <th>
                        Reason / Details
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($movements as $movement)

                    @php

                        $movementType =
                            strtolower(
                                $movement->type ?? ''
                            );

                        if ($movementType === 'stock_in') {

                            $transactionLabel =
                                'Stock In';

                            $transactionClass =
                                'stock-in';

                            $quantityChange =
                                '+' .
                                number_format(
                                    abs($movement->quantity),
                                    2
                                );

                            $quantityClass =
                                'quantity-positive';

                        } elseif ($movementType === 'stock_out') {

                            $transactionLabel =
                                'Stock Out';

                            $transactionClass =
                                'stock-out';

                            $quantityChange =
                                '-' .
                                number_format(
                                    abs($movement->quantity),
                                    2
                                );

                            $quantityClass =
                                'quantity-negative';

                        } else {

                            $transactionLabel =
                                'Adjustment';

                            $transactionClass =
                                'adjustment';

                            $signedQuantity =
                                (float)
                                $movement->quantity;

                            if ($signedQuantity > 0) {

                                $quantityChange =
                                    '+' .
                                    number_format(
                                        $signedQuantity,
                                        2
                                    );

                            } elseif ($signedQuantity < 0) {

                                $quantityChange =
                                    number_format(
                                        $signedQuantity,
                                        2
                                    );

                            } else {

                                $quantityChange =
                                    '0.00';

                            }

                            $quantityClass =
                                'quantity-adjustment';

                        }

                        $referenceLabel =
                            $movement->reference_label
                            ?? '—';

                        $referenceType =
                            $movement->reference_type
                            ?? '';

                        $recordedBy =
                            optional(
                                $movement->user
                            )->name
                            ??
                            optional(
                                $movement->user
                            )->full_name
                            ??
                            'System';

                    @endphp


                    <tr>

                        <td>

                            <div
                                style="
                                    font-weight:700;
                                    color:#51473f;
                                "
                            >
                                {{ $movement->created_at?->format('M d, Y') }}
                            </div>

                            <div
                                style="
                                    margin-top:2px;
                                    color:#9a9088;
                                    font-size:9px;
                                "
                            >
                                {{ $movement->created_at?->format('h:i A') }}
                            </div>

                        </td>


                        <td>

                            <span
                                class="transaction-type {{ $transactionClass }}"
                            >

                                @if($movementType === 'stock_in')
                                    +
                                @elseif($movementType === 'stock_out')
                                    −
                                @else
                                    ±
                                @endif

                                {{ $transactionLabel }}

                            </span>

                        </td>


                        <td>

                            <span class="reference-label">
                                {{ $referenceLabel }}
                            </span>

                            @if($referenceType)

                                <span class="reference-type">
                                    {{ class_basename($referenceType) }}
                                </span>

                            @endif

                        </td>


                        <td>

                            <span class="{{ $quantityClass }}">
                                {{ $quantityChange }}
                            </span>

                        </td>


                        <td>
                            {{ number_format($movement->quantity_before, 2) }}
                        </td>


                        <td>
                            {{ number_format($movement->quantity_after, 2) }}
                        </td>


                        <td>

                            <span class="recorded-by">
                                {{ $recordedBy }}
                            </span>

                        </td>


                        <td>

                            <div class="reason-text">
                                {{ $movement->reason ?: '—' }}
                            </div>

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="empty-state"
                        >
                            No stock movements have been recorded for this inventory item yet.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    @if($movements->hasPages())

        <div class="pagination-wrap">
            {{ $movements->links() }}
        </div>

    @endif

</div>

</section>

{{-- =========================================================
FOOTER ACTION
========================================================== --}}

<div class="page-actions">

<div class="audit-note">
    Inventory quantities are controlled through purchases, sales, stock events, and authorized adjustments.
</div>

<a
    href="{{ route('inventory.index') }}"
    class="btn btn-secondary"
>
    ← Back to Inventory
</a>

</div>

</div>


{{-- =========================================================
STOCK RECEIPT JAVASCRIPT
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       SOURCE TYPE
    ========================================================= */

    const sourceType =
        document.getElementById('sourceType');

    const supplierGroup =
        document.getElementById('supplierGroup');

    const supplierId =
        document.getElementById('supplierId');

    const sourceNameGroup =
        document.getElementById('sourceNameGroup');

    const sourceName =
        document.getElementById('sourceName');


    function updateSourceFields() {

        if (!sourceType) {
            return;
        }

        const value =
            sourceType.value;


        if (value === 'supplier') {

            supplierGroup.classList.remove('hidden');

            sourceNameGroup.classList.add('hidden');

            supplierId.required = true;

            sourceName.required = false;

            sourceName.value = '';

            return;
        }


        if (value === 'store' || value === 'other') {

            supplierGroup.classList.add('hidden');

            sourceNameGroup.classList.remove('hidden');

            supplierId.required = false;

            sourceName.required = true;

            supplierId.value = '';

            /*
             * If the source is no longer a supplier,
             * purchase-history prices should not be used.
             */
            clearPurchasePrices();

            return;
        }


        supplierGroup.classList.remove('hidden');

        sourceNameGroup.classList.add('hidden');

        supplierId.required = false;

        sourceName.required = false;

    }


    if (sourceType) {

        sourceType.addEventListener(
            'change',
            updateSourceFields
        );

        updateSourceFields();

    }


    /* =========================================================
       RECEIPT ITEMS
    ========================================================= */

    const receiptItemsBody =
        document.getElementById('receiptItemsBody');

    const addReceiptItemButton =
        document.getElementById('addReceiptItem');

    const grandTotalElement =
        document.getElementById('receiptGrandTotal');


    let itemIndex =
        receiptItemsBody
            ? receiptItemsBody.querySelectorAll('.receipt-item').length
            : 0;


    function formatCurrency(value) {

        return '₱' +
            Number(value || 0)
                .toLocaleString(
                    'en-PH',
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                );

    }


    /* =========================================================
       PURCHASE PRICE LOOKUP
    ========================================================= */

    const supplierItemCostUrl =
        @json(route('inventory.supplier-item-cost'));


    /*
     * Prevent an older request from overwriting a newer
     * supplier/item selection.
     */
    const priceRequestVersions =
        new WeakMap();


    function clearPurchasePrices() {

        if (!receiptItemsBody) {
            return;
        }

        receiptItemsBody
            .querySelectorAll('.receipt-item')
            .forEach(function (row) {

                const priceInput =
                    row.querySelector('.item-price');

                if (priceInput) {
                    priceInput.value = '';
                }

            });

        updateGrandTotal();

    }


    async function loadPurchasePrice(row, force = true) {

        if (!row) {
            return;
        }


        const select =
            row.querySelector('.inventory-select');

        const priceInput =
            row.querySelector('.item-price');


        if (!select || !priceInput) {
            return;
        }


        /*
         * Only supplier-based receipts use purchase history.
         */
        if (
            !sourceType ||
            sourceType.value !== 'supplier'
        ) {

            return;

        }


        const supplier =
            supplierId
                ? supplierId.value
                : '';


        const inventoryItemId =
            select.value;


        /*
         * A supplier and inventory item are both required
         * before looking up the previous purchase price.
         */
        if (
            !supplier ||
            !inventoryItemId
        ) {

            return;

        }


        const currentVersion =
            (priceRequestVersions.get(row) || 0) + 1;

        priceRequestVersions.set(
            row,
            currentVersion
        );


        /*
         * When the user selects a supplier/item,
         * the previous automatically populated price
         * should be replaced with the latest purchase price.
         */
        if (force) {

            priceInput.value = '';

            updateItemRow(row);

        }


        try {

            const url =
                supplierItemCostUrl +
                '?supplier_id=' +
                encodeURIComponent(supplier) +
                '&inventory_item_id=' +
                encodeURIComponent(inventoryItemId);


            const response =
                await fetch(
                    url,
                    {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                );


            /*
             * Ignore failed HTTP responses.
             * The user can still enter the price manually.
             */
            if (!response.ok) {

                return;

            }


            const data =
                await response.json();


            /*
             * Ignore this response if another lookup
             * was started for the same row afterward.
             */
            if (
                priceRequestVersions.get(row)
                !== currentVersion
            ) {

                return;

            }


            /*
             * The controller should return:
             *
             * {
             *     unit_cost: 125.50
             * }
             *
             * when a previous purchase exists.
             */
            if (
                data &&
                data.unit_cost !== null &&
                data.unit_cost !== undefined &&
                data.unit_cost !== ''
            ) {

                priceInput.value =
                    Number(data.unit_cost)
                        .toFixed(2);

            }


            updateItemRow(row);

        } catch (error) {

            /*
             * Network/API failure should not prevent
             * manual price entry.
             */
            console.warn(
                'Unable to load previous purchase price.',
                error
            );

        }

    }


    /* =========================================================
       ITEM ROW UPDATE
    ========================================================= */

    function updateItemRow(row) {

        if (!row) {
            return;
        }


        const select =
            row.querySelector('.inventory-select');

        const quantityInput =
            row.querySelector('.item-quantity');

        const priceInput =
            row.querySelector('.item-price');

        const unitElement =
            row.querySelector('.item-unit');

        const totalElement =
            row.querySelector('.item-total');


        if (select && unitElement) {

            const selectedOption =
                select.options[
                    select.selectedIndex
                ];


            if (
                selectedOption &&
                selectedOption.dataset.unit
            ) {

                unitElement.textContent =
                    selectedOption.dataset.unit;

            } else {

                unitElement.textContent =
                    '—';

            }

        }


        const quantity =
            parseFloat(
                quantityInput?.value || 0
            );


        const price =
            parseFloat(
                priceInput?.value || 0
            );


        const total =
            quantity * price;


        if (totalElement) {

            totalElement.textContent =
                formatCurrency(total);

        }


        updateGrandTotal();

    }


    function updateGrandTotal() {

        if (
            !receiptItemsBody ||
            !grandTotalElement
        ) {

            return;

        }


        let total =
            0;


        receiptItemsBody
            .querySelectorAll('.receipt-item')
            .forEach(function (row) {

                const quantity =
                    parseFloat(
                        row.querySelector(
                            '.item-quantity'
                        )?.value || 0
                    );


                const price =
                    parseFloat(
                        row.querySelector(
                            '.item-price'
                        )?.value || 0
                    );


                total +=
                    quantity *
                    price;

            });


        grandTotalElement.textContent =
            formatCurrency(total);

    }


    /* =========================================================
       ROW EVENTS
    ========================================================= */

    function attachRowEvents(row) {

        const select =
            row.querySelector('.inventory-select');

        const quantityInput =
            row.querySelector('.item-quantity');

        const priceInput =
            row.querySelector('.item-price');

        const removeButton =
            row.querySelector('.remove-item-btn');


        if (select) {

            select.addEventListener(
                'change',
                function () {

                    /*
                     * Update unit immediately.
                     */
                    updateItemRow(row);

                    /*
                     * Then retrieve the latest purchase
                     * price for the selected supplier + item.
                     */
                    loadPurchasePrice(
                        row,
                        true
                    );

                }
            );

        }


        if (quantityInput) {

            quantityInput.addEventListener(
                'input',
                function () {

                    updateItemRow(row);

                }
            );

        }


        if (priceInput) {

            priceInput.addEventListener(
                'input',
                function () {

                    updateItemRow(row);

                }
            );

        }


        if (removeButton) {

            removeButton.addEventListener(
                'click',
                function () {

                    const rows =
                        receiptItemsBody
                            .querySelectorAll(
                                '.receipt-item'
                            );


                    if (rows.length <= 1) {

                        return;

                    }


                    row.remove();

                    updateGrandTotal();

                }
            );

        }


        /*
         * Set the unit and calculate the existing
         * quantity × price first.
         */
        updateItemRow(row);


        /*
         * If this is a supplier receipt and the old
         * validation data did not contain a price,
         * retrieve the saved purchase price.
         */
        if (
            sourceType &&
            sourceType.value === 'supplier' &&
            supplierId &&
            supplierId.value &&
            select &&
            select.value &&
            priceInput &&
            !priceInput.value
        ) {

            loadPurchasePrice(
                row,
                false
            );

        }

    }


    /* =========================================================
       INITIAL ROW EVENTS
    ========================================================= */

    if (receiptItemsBody) {

        receiptItemsBody
            .querySelectorAll('.receipt-item')
            .forEach(function (row) {

                attachRowEvents(row);

            });

    }


    /* =========================================================
       SUPPLIER CHANGE
    ========================================================= */

    if (supplierId) {

        supplierId.addEventListener(
            'change',
            function () {

                /*
                 * Only retrieve purchase history when
                 * Supplier is the selected source.
                 */
                if (
                    sourceType &&
                    sourceType.value !== 'supplier'
                ) {

                    return;

                }


                if (!receiptItemsBody) {
                    return;
                }


                receiptItemsBody
                    .querySelectorAll('.receipt-item')
                    .forEach(function (row) {

                        const select =
                            row.querySelector(
                                '.inventory-select'
                            );

                        /*
                         * Only look up prices for rows
                         * that already have an item selected.
                         */
                        if (
                            select &&
                            select.value
                        ) {

                            loadPurchasePrice(
                                row,
                                true
                            );

                        }

                    });

            }
        );

    }


    /* =========================================================
       ADD RECEIPT ITEM
    ========================================================= */

    if (addReceiptItemButton) {

        addReceiptItemButton.addEventListener(
            'click',
            function () {

                const row =
                    document.createElement('tr');

                row.className =
                    'receipt-item';


                row.innerHTML = `

                    <td>

                        <select
                            name="items[${itemIndex}][inventory_item_id]"
                            class="form-control inventory-select"
                            required
                        >

                            <option value="">
                                Select inventory item
                            </option>

                            @foreach($inventoryItems ?? [] as $item)

                                <option
                                    value="{{ $item->id }}"
                                    data-unit="{{ $item->unit->name ?? '—' }}"
                                >
                                    {{ $item->name }}
                                    @if($item->sku)
                                        — {{ $item->sku }}
                                    @endif
                                </option>

                            @endforeach

                        </select>

                    </td>


                    <td>

                        <input
                            type="number"
                            name="items[${itemIndex}][quantity]"
                            class="form-control item-quantity"
                            min="0.01"
                            step="0.01"
                            placeholder="0.00"
                            required
                        >

                    </td>


                    <td>

                        <div class="receipt-unit item-unit">
                            —
                        </div>

                    </td>


                    <td>

                        <input
                            type="number"
                            name="items[${itemIndex}][unit_price]"
                            class="form-control item-price"
                            min="0"
                            step="0.01"
                            placeholder="0.00"
                            required
                        >

                    </td>


                    <td>

                        <div class="receipt-total item-total">
                            ₱0.00
                        </div>

                    </td>


                    <td style="text-align:center;">

                        <button
                            type="button"
                            class="remove-item-btn"
                            title="Remove item"
                            aria-label="Remove item"
                        >
                            ×
                        </button>

                    </td>

                `;


                receiptItemsBody.appendChild(row);

                attachRowEvents(row);

                itemIndex++;

            }
        );

    }


    /* =========================================================
       PHYSICAL COUNT VARIANCE
    ========================================================= */

    const systemQuantityElement =
        document.getElementById('systemQuantity');

    const physicalQuantityElement =
        document.getElementById('physicalQuantity');

    const varianceElement =
        document.getElementById('variancePreview');


    if (
        systemQuantityElement &&
        physicalQuantityElement &&
        varianceElement
    ) {

        const systemQuantity =
            parseFloat(
                systemQuantityElement.dataset.value || '0'
            );


        function updateVariance() {

            const physicalQuantity =
                parseFloat(
                    physicalQuantityElement.value
                );


            if (
                Number.isNaN(physicalQuantity)
            ) {

                varianceElement.textContent =
                    'No variance';

                varianceElement.className =
                    'variance-box';

                return;

            }


            const variance =
                physicalQuantity -
                systemQuantity;


            if (
                Math.abs(variance) < 0.000001
            ) {

                varianceElement.textContent =
                    'No variance';

                varianceElement.className =
                    'variance-box';

                return;

            }


            if (variance > 0) {

                varianceElement.textContent =
                    '+' +
                    variance.toFixed(2) +
                    ' increase';

                varianceElement.className =
                    'variance-box increase';

                return;

            }


            varianceElement.textContent =
                variance.toFixed(2) +
                ' decrease';

            varianceElement.className =
                'variance-box decrease';

        }


        physicalQuantityElement.addEventListener(
            'input',
            updateVariance
        );


        updateVariance();

    }

});

</script>

@endsection