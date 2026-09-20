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
   LOGO
========================================================= */

.logo {

    display: flex;

    align-items: center;

    gap: 11px;

    padding:
        3px
        9px;

    margin-bottom:
        clamp(15px, 2.8vh, 30px);

    flex-shrink: 0;
}


.logo-icon {

    width:
        clamp(38px, 5vh, 45px);

    height:
        clamp(38px, 5vh, 45px);

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 13px;

    background:
        linear-gradient(
            135deg,
            #d79555,
            #a85f28
        );

    color: white;

    font-size: 1.1875rem;

    font-weight: 800;

    box-shadow:
        0 8px 20px
        rgba(0, 0, 0, 0.22);

    flex-shrink: 0;
}


.logo-name {

    font-size: 1.25rem;

    font-weight: 800;

    letter-spacing: -0.025rem;
}


.logo-subtitle {

    margin-top: 2px;

    font-size: 0.75rem;

    color:
        rgba(255, 255, 255, 0.55);

    text-transform: uppercase;

    letter-spacing: 0.055rem;
}


/* =========================================================
   NAVIGATION TITLE
========================================================= */

.nav-title {

    padding: 0 11px;

    margin-bottom:
        clamp(5px, 1vh, 9px);

    font-size: 0.75rem;

    color:
        rgba(255, 255, 255, 0.48);

    text-transform: uppercase;

    letter-spacing: 0.075rem;

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
        clamp(2px, 0.5vh, 5px);

    flex-shrink: 0;
}


.nav-item {

    width: 100%;

    min-height:
        clamp(36px, 5vh, 45px);

    display: flex;

    align-items: center;

    gap:
        clamp(8px, 1.3vh, 12px);

    padding:
        clamp(6px, 0.9vh, 9px)
        11px;

    border-radius: 10px;

    border: none;

    background: transparent;

    color:
        rgba(255, 255, 255, 0.72);

    text-decoration: none;

    font-size: 0.875rem;

    line-height: 1.3;

    cursor: pointer;

    white-space: nowrap;

    transition:
        background 0.2s ease,
        color 0.2s ease;
}


.nav-item:hover {

    background:
        rgba(255, 255, 255, 0.07);

    color: white;
}


.nav-item.active {

    background:
        linear-gradient(
            135deg,
            rgba(196, 122, 58, 0.98),
            rgba(168, 95, 40, 0.98)
        );

    color: white;

    box-shadow:
        0 6px 16px
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
        clamp(26px, 3.4vh, 30px);

    height:
        clamp(26px, 3.4vh, 30px);

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 8px;

    background:
        rgba(255, 255, 255, 0.055);

    color:
        rgba(255, 255, 255, 0.82);

    font-size: 0.8125rem;

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
        clamp(12px, 2vh, 23px);
}


/* =========================================================
   SIDEBAR BOTTOM
========================================================= */

.sidebar-bottom {

    margin-top: auto;

    padding-top:
        clamp(8px, 1.4vh, 15px);

    flex-shrink: 0;
}


/* =========================================================
   USER MINI
========================================================= */

.user-mini {

    display: flex;

    align-items: center;

    gap: 9px;

    padding:
        clamp(8px, 1.2vh, 11px);

    margin-bottom: 6px;

    border-radius: 11px;

    background:
        rgba(255, 255, 255, 0.055);

    border:
        1px solid
        rgba(255, 255, 255, 0.045);
}


.avatar {

    width:
        clamp(34px, 4.5vh, 39px);

    height:
        clamp(34px, 4.5vh, 39px);

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 10px;

    background:
        linear-gradient(
            135deg,
            #e0a164,
            #c47a3a
        );

    color: #382519;

    font-size: 0.8125rem;

    font-weight: 800;

    flex-shrink: 0;
}


.user-info {

    min-width: 0;
}


.user-name {

    font-size: 0.8125rem;

    font-weight: 700;

    line-height: 1.3;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;

    color: white;
}


.user-role {

    margin-top: 2px;

    font-size: 0.75rem;

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
        clamp(36px, 4.5vh, 42px);

    display: flex;

    align-items: center;

    gap: 10px;

    padding:
        6px 11px;

    border:
        1px solid
        rgba(255, 255, 255, 0.06);

    border-radius: 9px;

    background:
        rgba(255, 255, 255, 0.025);

    color:
        rgba(255, 255, 255, 0.68);

    font-family: inherit;

    font-size: 0.875rem;

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
   MAIN CONTENT
   MASTER DASHBOARD POSITION
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
   SAME AS ADMIN DASHBOARD
========================================================= */

.topbar {

    width: 100%;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    margin: 0 0 clamp(20px, 3vh, 28px);

    padding: 0;
}


/* =========================================================
   PAGE TITLE
   SAME AS ADMIN DASHBOARD
========================================================= */

.page-title {

    margin: 0;

    padding: 0;
}


.page-title small {

    display: block;

    margin-bottom: 5px;

    color: var(--orange);

    font-size: 0.8125rem;

    font-weight: 800;

    text-transform: uppercase;

    letter-spacing: 0.075rem;
}


.page-title h1 {

    margin: 0;

    padding: 0;

    font-size:
        clamp(1.75rem, 2.4vw, 2.125rem);

    line-height: 1.15;

    letter-spacing: -0.055rem;

    color: var(--dark);
}


.page-title p {

    margin:
        7px 0 0;

    padding: 0;

    color: var(--muted);

    font-size: 0.9375rem;

    line-height: 1.5;
}


/* =========================================================
   DATE BOX
   SAME AS ADMIN DASHBOARD
========================================================= */

.date-box {

    display: flex;

    align-items: center;

    gap: 8px;

    padding:
        11px 15px;

    border:
        1px solid var(--border);

    border-radius: 12px;

    background: white;

    color: #756b63;

    font-size: 0.875rem;

    box-shadow:
        0 3px 12px
        rgba(43, 31, 23, 0.03);

    flex-shrink: 0;
}


.date-icon {

    color: var(--orange);

    font-size: 1rem;
}


/* =========================================================
   GENERAL FORM ELEMENTS
========================================================= */

button,
input,
select,
textarea {

    font-family: inherit;

    font-size: 1rem;
}


label {

    font-size: 0.875rem;

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
            15px 8px;
    }


    .logo {

        justify-content: center;

        padding: 0;

        margin-bottom: 20px;
    }


    .logo > div:not(.logo-icon) {

        display: none;
    }


    .nav-title {

        display: none;
    }


    .nav-item {

        justify-content: center;

        padding:
            6px 3px;
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
            6px 3px;
    }


    .user-info {

        display: none;
    }


    .logout-button {

        justify-content: center;

        padding:
            5px 3px;
    }


    .logout-button span:not(.nav-icon) {

        display: none;
    }


    .main {

        width:
            calc(100% - 72px);

        margin-left: 72px;

        padding:
            24px 18px 35px;
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
            20px 14px 30px;
    }


    .page-title h1 {

        font-size: 1.75rem;
    }


    .page-title p {

        font-size: 0.875rem;
    }
}

</style>


@stack('styles')

</head>


<body>

<div class="app">


    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <aside class="sidebar">


        <!-- LOGO -->

        <div class="logo">

            <div class="logo-icon">
                B
            </div>

            <div>

                <div class="logo-name">
                    BiteSync
                </div>

                <div class="logo-subtitle">
                    Management System
                </div>

            </div>

        </div>


        <!-- MAIN MENU -->

        <div class="nav-title">
            Main Menu
        </div>


        <nav class="nav">


            <!-- DASHBOARD -->

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


            <!-- INVENTORY -->

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


            <!-- PRODUCTS -->

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


            <!-- SUPPLIERS -->

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


            <!-- PURCHASES -->

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


            <!-- SALES -->

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


            <!-- EXPENSES -->

            <a
                href="#"
                class="nav-item"
            >

                <span class="nav-icon">
                    ▣
                </span>

                <span>
                    Expenses
                </span>

            </a>


            <!-- REPORTS -->

            <a
                href="#"
                class="nav-item"
            >

                <span class="nav-icon">
                    ▥
                </span>

                <span>
                    Reports
                </span>

            </a>

        </nav>


        <!-- ADMINISTRATION -->

        <div class="admin-section">

            <div class="nav-title">
                Administration
            </div>


            <nav class="nav">

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


        <!-- USER + LOGOUT -->

        <div class="sidebar-bottom">

            <div class="user-mini">

                <div class="avatar">

                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                </div>


                <div class="user-info">

                    <div class="user-name">

                        {{ auth()->user()->name }}

                    </div>


                    <div class="user-role">

                        {{ auth()->user()->role }}

                    </div>

                </div>

            </div>


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


    <!-- =====================================================
         MAIN CONTENT
    ====================================================== -->

    <main class="main">

        <div class="page-content">

            @yield('content')

        </div>

    </main>

</div>


@stack('scripts')

</body>

</html>
