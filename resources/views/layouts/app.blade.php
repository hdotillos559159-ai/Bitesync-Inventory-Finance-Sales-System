<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
    @yield('title', 'BiteSync')
</title>


<style>

/* =========================================================
   RESET
========================================================= */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


/* =========================================================
   ROOT
========================================================= */

:root {

    --bg: #f5f1eb;

    --card: #ffffff;

    --card-soft: #fcfaf7;


    --dark: #241a14;

    --dark-soft: #34251d;


    --brown: #76563d;


    --orange: #c47a3a;

    --orange-dark: #a85f28;

    --orange-light: #f4e4d4;


    --text: #2c241f;

    --muted: #8b8179;


    --border: #e4dcd4;


    --green: #5d8b67;

    --green-light: #edf6ef;


    --red: #b95d56;

    --red-light: #fbeeed;


    --yellow: #b9823e;

    --yellow-light: #fbf1e3;


    --blue: #637f9f;

    --blue-light: #edf2f7;
}


/* =========================================================
   HTML
========================================================= */

html {
    min-height: 100%;
    font-size: 16px;
}


/* =========================================================
   BODY
========================================================= */

body {

    min-height: 100vh;

    font-family:
        "Segoe UI",
        Arial,
        Helvetica,
        sans-serif;

    font-size: 1rem;

    line-height: 1.5;

    background: var(--bg);

    color: var(--text);

    overflow-x: hidden;
}


/* =========================================================
   APP
========================================================= */

.app {
    min-height: 100vh;
}


/* =========================================================
   SIDEBAR
========================================================= */

.sidebar {

    width: 255px;

    height: 100vh;

    position: fixed;

    top: 0;
    left: 0;

    display: flex;

    flex-direction: column;

    padding:
        clamp(12px, 2.2vh, 24px)
        14px
        clamp(10px, 1.8vh, 18px);

    background:
        linear-gradient(
            160deg,
            #241a14 0%,
            #302118 55%,
            #422b1c 100%
        );

    color: white;

    z-index: 100;

    overflow: hidden;

    box-shadow:
        5px 0 22px
        rgba(28, 19, 13, 0.08);
}


/* =========================================================
   BRAND
========================================================= */

.logo {

    display: flex;

    align-items: center;

    padding:
        3px
        8px;

    margin-bottom:
        clamp(14px, 2.5vh, 27px);

    flex-shrink: 0;
}


.logo-name {

    font-size: 1.125rem;

    font-weight: 800;

    letter-spacing: -0.02rem;

    color: white;
}


.logo-subtitle {

    margin-top: 1px;

    font-size: 0.6875rem;

    color:
        rgba(255, 255, 255, 0.55);

    text-transform: uppercase;

    letter-spacing: 0.05rem;
}


/* =========================================================
   NAVIGATION TITLE
========================================================= */

.nav-title {

    padding: 0 10px;

    margin-bottom:
        clamp(5px, 0.9vh, 8px);

    font-size: 0.6875rem;

    color:
        rgba(255, 255, 255, 0.48);

    text-transform: uppercase;

    letter-spacing: 0.07rem;

    font-weight: 800;

    flex-shrink: 0;
}


/* =========================================================
   NAVIGATION
========================================================= */

.nav {

    display: flex;

    flex-direction: column;

    gap:
        clamp(2px, 0.45vh, 4px);

    flex-shrink: 0;
}


/* =========================================================
   NAV ITEM
========================================================= */

.nav-item {

    width: 100%;

    min-height:
        clamp(35px, 4.7vh, 42px);

    display: flex;

    align-items: center;

    gap:
        clamp(8px, 1.2vh, 11px);

    padding:
        clamp(5px, 0.8vh, 8px)
        10px;

    border-radius: 9px;

    border: none;

    background: transparent;

    color:
        rgba(255, 255, 255, 0.72);

    text-decoration: none;

    font-size: 0.8125rem;

    line-height: 1.3;

    cursor: pointer;

    white-space: nowrap;

    transition:
        background 0.2s ease,
        color 0.2s ease;
}


/* =========================================================
   NAV HOVER
========================================================= */

.nav-item:hover {

    background:
        rgba(255, 255, 255, 0.07);

    color: white;
}


/* =========================================================
   ACTIVE NAVIGATION
========================================================= */

.nav-item.active {

    background:
        linear-gradient(
            135deg,
            rgba(196, 122, 58, 0.98),
            rgba(168, 95, 40, 0.98)
        );

    color: white;

    box-shadow:
        0 5px 13px
        rgba(0, 0, 0, 0.16);
}


.nav-item.active:hover {

    background:
        linear-gradient(
            135deg,
            rgba(196, 122, 58, 0.98),
            rgba(168, 95, 40, 0.98)
        );

    color: white;
}


/* =========================================================
   NAV ICON
========================================================= */

.nav-icon {

    width:
        clamp(25px, 3.2vh, 28px);

    height:
        clamp(25px, 3.2vh, 28px);

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 7px;

    background:
        rgba(255, 255, 255, 0.055);

    color:
        rgba(255, 255, 255, 0.82);

    font-size: 0.75rem;

    flex-shrink: 0;
}


.nav-item.active .nav-icon {

    background:
        rgba(255, 255, 255, 0.15);

    color: white;
}


/* =========================================================
   ADMINISTRATION
========================================================= */

.admin-section {

    margin-top:
        clamp(11px, 1.8vh, 20px);
}


/* =========================================================
   SIDEBAR BOTTOM
========================================================= */

.sidebar-bottom {

    margin-top: auto;

    padding-top:
        clamp(7px, 1.2vh, 13px);

    flex-shrink: 0;
}


/* =========================================================
   MINI USER
========================================================= */

.user-mini {

    display: flex;

    align-items: center;

    gap: 8px;

    padding:
        clamp(7px, 1.1vh, 10px);

    margin-bottom: 5px;

    border-radius: 10px;

    background:
        rgba(255, 255, 255, 0.055);

    border:
        1px solid
        rgba(255, 255, 255, 0.045);
}


/* =========================================================
   USER AVATAR
========================================================= */

.avatar {

    width:
        clamp(32px, 4.2vh, 37px);

    height:
        clamp(32px, 4.2vh, 37px);

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 9px;

    background:
        linear-gradient(
            135deg,
            #e0a164,
            #c47a3a
        );

    color: #382519;

    font-size: 0.75rem;

    font-weight: 800;

    flex-shrink: 0;
}


/* =========================================================
   USER INFORMATION
========================================================= */

.user-info {

    min-width: 0;
}


.user-name {

    font-size: 0.75rem;

    font-weight: 700;

    line-height: 1.3;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;

    color: white;
}


.user-role {

    margin-top: 1px;

    font-size: 0.6875rem;

    line-height: 1.3;

    color:
        rgba(255, 255, 255, 0.58);
}


/* =========================================================
   LOGOUT
========================================================= */

.logout-form {
    width: 100%;
}


.logout-button {

    width: 100%;

    min-height:
        clamp(35px, 4.2vh, 40px);

    display: flex;

    align-items: center;

    gap: 9px;

    padding:
        5px
        10px;

    border:
        1px solid
        rgba(255, 255, 255, 0.06);

    border-radius: 8px;

    background:
        rgba(255, 255, 255, 0.025);

    color:
        rgba(255, 255, 255, 0.68);

    font-family: inherit;

    font-size: 0.8125rem;

    cursor: pointer;

    transition:
        background 0.2s ease,
        color 0.2s ease,
        border-color 0.2s ease;
}


.logout-button:hover {

    background:
        rgba(255, 255, 255, 0.08);

    border-color:
        rgba(255, 255, 255, 0.11);

    color: white;
}


/* =========================================================
   MAIN
========================================================= */

.main {

    width:
        calc(100% - 255px);

    min-height: 100vh;

    margin-left: 255px;

    padding:
        clamp(20px, 3vw, 34px)
        clamp(20px, 3vw, 34px)
        40px;
}


/* =========================================================
   PAGE CONTENT
========================================================= */

.page-content {

    width: 100%;

    margin: 0;

    padding: 0;
}


/* =========================================================
   SHARED TOPBAR
========================================================= */

.topbar {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    margin-bottom:
        clamp(18px, 2.6vh, 25px);
}


/* =========================================================
   PAGE TITLE
========================================================= */

.page-title small {

    display: block;

    margin-bottom: 4px;

    color: var(--orange);

    font-size: 0.6875rem;

    font-weight: 800;

    text-transform: uppercase;

    letter-spacing: 0.07rem;
}


.page-title h1 {

    font-size:
        clamp(1.6rem, 2.2vw, 1.9rem);

    line-height: 1.15;

    letter-spacing: -0.045rem;

    color: var(--dark);
}


.page-title p {

    margin-top: 5px;

    color: var(--muted);

    font-size: 0.75rem;

    line-height: 1.5;
}


/* =========================================================
   DATE BOX
========================================================= */

.date-box {
    display: none;
}


.date-icon {
    display: none;
}


/* =========================================================
   GENERAL FORM ELEMENTS
========================================================= */

button,
input,
select,
textarea {

    font-family: inherit;

    font-size: 0.875rem;
}


label {

    font-size: 0.8125rem;

    line-height: 1.4;

    font-weight: 600;

    color: var(--text);
}


/* =========================================================
   RESPONSIVE — 850px
========================================================= */

@media (max-width: 850px) {

    .sidebar {

        width: 72px;

        padding:
            15px
            8px;
    }


    .logo {

        justify-content: center;

        padding: 0;

        margin-bottom: 20px;
    }


    .logo-name {

        display: none;
    }


    .logo-subtitle {

        display: none;
    }


    .nav-title {

        display: none;
    }


    .nav-item {

        justify-content: center;

        padding:
            6px
            3px;
    }


    .nav-item span:not(.nav-icon) {

        display: none;
    }


    .nav-icon {

        width: 36px;

        height: 36px;
    }


    .admin-section {

        margin-top: 15px;
    }


    .user-mini {

        justify-content: center;

        padding:
            6px
            3px;
    }


    .user-info {

        display: none;
    }


    .logout-button {

        justify-content: center;

        padding:
            5px
            3px;
    }


    .logout-button span:not(.nav-icon) {

        display: none;
    }


    .main {

        width:
            calc(100% - 72px);

        margin-left: 72px;

        padding:
            24px
            18px
            35px;
    }

}


/* =========================================================
   RESPONSIVE — 600px
========================================================= */

@media (max-width: 600px) {

    .topbar {

        align-items: flex-start;

        flex-direction: column;
    }


    .date-box {

        display: none;
    }


    .main {

        padding:
            20px
            14px
            30px;
    }


    .page-title h1 {

        font-size: 1.75rem;
    }


    .page-title p {

        font-size: 0.75rem;
    }

}

</style>

@stack('styles')

</head>


<body>

<div class="app">


<!-- =========================================================
     SIDEBAR
========================================================== -->

<aside class="sidebar">


<!-- =====================================================
     BRAND
====================================================== -->

<div class="logo">

    <div>

        <div class="logo-name">
            BiteSync
        </div>

        <div class="logo-subtitle">
            Management System
        </div>

    </div>

</div>


<!-- =====================================================
     CURRENT USER ROLE
====================================================== -->

@php

    $currentUser = auth()->user();

    $currentRole = $currentUser?->role;

    /*
    |--------------------------------------------------------------------------
    | Role permissions used ONLY for navigation visibility.
    |--------------------------------------------------------------------------
    | Actual route middleware/controller authorization should still protect
    | the pages themselves.
    |
    */

    $isAdmin = $currentRole === 'CEO/Admin';

    $isFinance = $currentRole === 'Finance';

    $isProcurement = $currentRole === 'Procurement';

@endphp


<!-- =====================================================
     MAIN MENU
====================================================== -->

<div class="nav-title">
    Main Menu
</div>


<nav class="nav">


    <!-- =================================================
         DASHBOARD
    ================================================== -->

    @if ($isAdmin)

        <a
            href="{{ route('admin.dashboard') }}"
            class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
        >

            <span class="nav-icon">
                ⌂
            </span>

            <span>
                Dashboard
            </span>

        </a>

    @elseif ($isFinance)

        <a
            href="{{ route('finance.dashboard') }}"
            class="nav-item {{ request()->routeIs('finance.dashboard') ? 'active' : '' }}"
        >

            <span class="nav-icon">
                ⌂
            </span>

            <span>
                Dashboard
            </span>

        </a>

    @elseif ($isProcurement)

        <a
            href="{{ route('procurement.dashboard') }}"
            class="nav-item {{ request()->routeIs('procurement.dashboard') ? 'active' : '' }}"
        >

            <span class="nav-icon">
                ⌂
            </span>

            <span>
                Dashboard
            </span>

        </a>

    @endif


    <!-- =================================================
         INVENTORY
         CEO/Admin = manage
         Finance = view
         Procurement = manage
    ================================================== -->

    @if ($isAdmin || $isFinance || $isProcurement)

        <a
            href="{{ route('inventory.index') }}"
            class="nav-item {{ request()->routeIs('inventory.*') ? 'active' : '' }}"
        >

            <span class="nav-icon">
                ▦
            </span>

            <span>
                Inventory
            </span>

        </a>

    @endif


    <!-- =================================================
         PRODUCTS
         CEO/Admin ONLY
    ================================================== -->

    @if ($isAdmin)

        <a
            href="{{ route('products.index') }}"
            class="nav-item {{ request()->routeIs('products.*') || request()->routeIs('recipes.*') ? 'active' : '' }}"
        >

            <span class="nav-icon">
                ◈
            </span>

            <span>
                Products
            </span>

        </a>

    @endif


    <!-- =================================================
         SUPPLIERS
         CEO/Admin = manage
         Finance = view
         Procurement = manage
    ================================================== -->

    @if ($isAdmin || $isFinance || $isProcurement)

        <a
            href="{{ route('suppliers.index') }}"
            class="nav-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}"
        >

            <span class="nav-icon">
                ♧
            </span>

            <span>
                Suppliers
            </span>

        </a>

    @endif


    <!-- =================================================
         PURCHASES
         CEO/Admin = manage
         Finance = view
         Procurement = manage
    ================================================== -->

    @if ($isAdmin || $isFinance || $isProcurement)

        <a
            href="{{ route('purchases.index') }}"
            class="nav-item {{ request()->routeIs('purchases.*') ? 'active' : '' }}"
        >

            <span class="nav-icon">
                ▤
            </span>

            <span>
                Purchases
            </span>

        </a>

    @endif


    <!-- =================================================
         SALES
         CEO/Admin = manage
         Finance = view
         Procurement = NO ACCESS
    ================================================== -->

    @if ($isAdmin || $isFinance)

        <a
            href="{{ route('sales.index') }}"
            class="nav-item {{ request()->routeIs('sales.*') ? 'active' : '' }}"
        >

            <span class="nav-icon">
                ₱
            </span>

            <span>
                Sales
            </span>

        </a>

    @endif


    <!-- =================================================
         EXPENSES
         CEO/Admin = manage
         Finance = manage
         Procurement = NO ACCESS
    ================================================== -->

    @if ($isAdmin || $isFinance)

        <a
            href="{{ route('expenses.index') }}"
            class="nav-item {{ request()->routeIs('expenses.*') ? 'active' : '' }}"
        >

            <span class="nav-icon">
                ▣
            </span>

            <span>
                Expenses
            </span>

        </a>

    @endif


    <!-- =================================================
         REPORTS
         ALL THREE ROLES
    ================================================== -->

    @if ($isAdmin || $isFinance || $isProcurement)

        <a
            href="{{ route('reports.index') }}"
            class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}"
        >

            <span class="nav-icon">
                ▥
            </span>

            <span>
                Reports
            </span>

        </a>

    @endif

</nav>


<!-- =====================================================
     ADMINISTRATION
     CEO/Admin ONLY
====================================================== -->

@if ($isAdmin)

    <div class="admin-section">

        <div class="nav-title">
            Administration
        </div>


        <nav class="nav">


            <!-- =================================================
                 USER MANAGEMENT
            ================================================== -->

            <a
                href="#"
                class="nav-item"
            >

                <span class="nav-icon">
                    ♙
                </span>

                <span>
                    User Management
                </span>

            </a>


            <!-- =================================================
                 SYSTEM SETTINGS
            ================================================== -->

            <a
                href="#"
                class="nav-item"
            >

                <span class="nav-icon">
                    ⚙
                </span>

                <span>
                    System Settings
                </span>

            </a>


            <!-- =================================================
                 AUDIT LOGS
            ================================================== -->

            <a
                href="#"
                class="nav-item"
            >

                <span class="nav-icon">
                    ◷
                </span>

                <span>
                    Audit Logs
                </span>

            </a>

        </nav>

    </div>

@endif


<!-- =====================================================
     USER + LOGOUT
====================================================== -->

<div class="sidebar-bottom">


    <!-- =================================================
         USER
    ================================================== -->

    <div class="user-mini">

        <div class="avatar">

            @if(auth()->check())

                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

            @else

                U

            @endif

        </div>


        <div class="user-info">

            <div class="user-name">

                @if(auth()->check())

                    {{ auth()->user()->name }}

                @else

                    User

                @endif

            </div>


            <div class="user-role">

                @if(auth()->check())

                    {{ auth()->user()->role }}

                @else

                    Guest

                @endif

            </div>

        </div>

    </div>


    <!-- =================================================
         LOGOUT
    ================================================== -->

    <form
        method="POST"
        action="{{ route('logout') }}"
        class="logout-form"
    >

        @csrf

        <button
            type="submit"
            class="logout-button"
        >

            <span class="nav-icon">
                ↪
            </span>

            <span>
                Sign Out
            </span>

        </button>

    </form>

</div>


</aside>


<!-- =========================================================
     MAIN CONTENT
========================================================== -->

<main class="main">

    <div class="page-content">

        @yield('content')

    </div>

</main>


</div>


@stack('scripts')

</body>

</html>