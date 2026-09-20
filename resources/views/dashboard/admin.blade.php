<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>BiteSync | Admin Dashboard</title>

    <style>

        /* =========================================================
           RESET
        ========================================================== */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        /* =========================================================
           ROOT
        ========================================================== */

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
        ========================================================== */

        html {
            min-height: 100%;
            font-size: 16px;
        }


        /* =========================================================
           BODY
        ========================================================== */

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
        ========================================================== */

        .app {
            min-height: 100vh;
        }


        /* =========================================================
           SIDEBAR
        ========================================================== */

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
        ========================================================== */

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
        ========================================================== */

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
        ========================================================== */

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


        /* =========================================================
           NAV HOVER
        ========================================================== */

        .nav-item:hover {

            background:
                rgba(255, 255, 255, 0.07);

            color: white;
        }


        /* =========================================================
           ACTIVE NAVIGATION
        ========================================================== */

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
        ========================================================== */

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
        ========================================================== */

        .admin-section {

            margin-top:
                clamp(12px, 2vh, 23px);
        }


        /* =========================================================
           SIDEBAR BOTTOM
        ========================================================== */

        .sidebar-bottom {

            margin-top: auto;

            padding-top:
                clamp(8px, 1.4vh, 15px);

            flex-shrink: 0;
        }


        /* =========================================================
           MINI USER
        ========================================================== */

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
        ========================================================== */

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
           MAIN
        ========================================================== */

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
           TOPBAR
        ========================================================== */

        .topbar {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            margin-bottom:
                clamp(20px, 3vh, 28px);
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

            font-size:
                clamp(1.75rem, 2.4vw, 2.125rem);

            line-height: 1.15;

            letter-spacing: -0.055rem;

            color: var(--dark);
        }


        .page-title p {

            margin-top: 7px;

            color: var(--muted);

            font-size: 0.9375rem;

            line-height: 1.5;
        }


        .date-box {

            display: flex;

            align-items: center;

            gap: 8px;

            padding: 11px 15px;

            border:
                1px solid var(--border);

            border-radius: 12px;

            background: white;

            color: #756b63;

            font-size: 0.875rem;

            box-shadow:
                0 3px 12px
                rgba(43, 31, 23, 0.03);
        }


        .date-icon {

            color: var(--orange);

            font-size: 1rem;
        }


        /* =========================================================
           STATS
        ========================================================== */

        .stats {

            display: grid;

            grid-template-columns:
                repeat(4, minmax(0, 1fr));

            gap: 17px;

            margin-bottom: 22px;
        }


        .stat-card {

            min-height:
                clamp(145px, 18vh, 165px);

            position: relative;

            overflow: hidden;

            padding: 20px 20px 18px;

            border:
                1px solid var(--border);

            border-radius: 17px;

            background: var(--card);

            box-shadow:
                0 7px 22px
                rgba(43, 31, 23, 0.055);

            transition:
                box-shadow 0.2s ease;
        }


        .stat-card:hover {

            box-shadow:
                0 11px 27px
                rgba(43, 31, 23, 0.075);
        }


        .stat-card::before {

            content: "";

            position: absolute;

            left: 0;
            top: 0;
            bottom: 0;

            width: 4px;

            background:
                linear-gradient(
                    180deg,
                    var(--orange),
                    #e2a16c
                );
        }


        .stat-card::after {

            content: "";

            position: absolute;

            width: 115px;
            height: 115px;

            right: -48px;
            bottom: -56px;

            border-radius: 50%;

            background:
                rgba(196, 122, 58, 0.075);
        }


        .stat-top {

            display: flex;

            align-items: center;

            justify-content: space-between;

            position: relative;

            z-index: 2;
        }


        .stat-label {

            color: var(--muted);

            font-size: 0.8125rem;

            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: 0.05rem;
        }


        .stat-icon {

            width: 42px;
            height: 42px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 11px;

            background:
                linear-gradient(
                    135deg,
                    #fbf1e7,
                    #f4e3d4
                );

            color: var(--orange);

            font-size: 1rem;

            box-shadow:
                inset 0 0 0 1px
                rgba(196, 122, 58, 0.08);
        }


        .stat-value {

            position: relative;

            z-index: 2;

            margin-top: 21px;

            font-size:
                clamp(1.75rem, 2.1vw, 2rem);

            line-height: 1;

            font-weight: 800;

            color: var(--dark);

            letter-spacing: -0.05rem;
        }


        .stat-note {

            position: relative;

            z-index: 2;

            margin-top: 10px;

            color: #9d958f;

            font-size: 0.8125rem;

            line-height: 1.45;
        }


        /* =========================================================
           ANALYTICS
        ========================================================== */

        .analytics-grid {

            display: grid;

            grid-template-columns:
                minmax(0, 1.55fr)
                minmax(280px, 0.85fr);

            gap: 20px;

            margin-bottom: 20px;
        }


        .panel {

            border:
                1px solid var(--border);

            border-radius: 17px;

            background: white;

            overflow: hidden;

            box-shadow:
                0 5px 18px
                rgba(43, 31, 23, 0.035);
        }


        .panel-header {

            min-height: 76px;

            padding: 17px 21px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            border-bottom:
                1px solid var(--border);
        }


        .panel-title {

            font-size: 1rem;

            font-weight: 800;

            color: var(--dark);

            line-height: 1.35;
        }


        .panel-subtitle {

            margin-top: 4px;

            color: var(--muted);

            font-size: 0.8125rem;

            line-height: 1.4;
        }


        .panel-badge {

            padding: 6px 10px;

            border-radius: 7px;

            background: var(--orange-light);

            color: var(--orange-dark);

            font-size: 0.75rem;

            font-weight: 800;

            white-space: nowrap;
        }


        /* =========================================================
           GRAPH
        ========================================================== */

        .chart-container {

            min-height: 280px;

            padding:
                15px 20px 18px;
        }


        .chart-area {

            width: 100%;

            height: 220px;

            position: relative;
        }


        .chart-svg {

            width: 100%;

            height: 100%;

            display: block;
        }


        .chart-grid-line {

            stroke: #eee8e2;

            stroke-width: 1;
        }


        .chart-axis {

            stroke: #dcd3ca;

            stroke-width: 1;
        }


        .chart-bar-empty {

            fill: #eee8e2;

            rx: 5;
        }


        .chart-line {

            fill: none;

            stroke: var(--orange);

            stroke-width: 3;

            stroke-linecap: round;

            stroke-linejoin: round;
        }


        .chart-point {

            fill: white;

            stroke: var(--orange);

            stroke-width: 3;
        }


        .chart-label {

            fill: #81776f;

            font-size: 0.75rem;

            font-family:
                "Segoe UI",
                Arial,
                sans-serif;
        }


        .chart-legend {

            display: flex;

            align-items: center;

            gap: 18px;

            margin-top: 2px;

            color: var(--muted);

            font-size: 0.8125rem;

            line-height: 1.4;
        }


        .legend-item {

            display: flex;

            align-items: center;

            gap: 7px;
        }


        .legend-dot {

            width: 9px;
            height: 9px;

            border-radius: 3px;

            background: #e6b58a;

            flex-shrink: 0;
        }


        .legend-dot.orange {

            background: var(--orange);
        }


        /* =========================================================
           QUICK ACTIONS
        ========================================================== */

        .quick-actions {

            padding: 17px;

            display: grid;

            grid-template-columns:
                repeat(4, minmax(0, 1fr));

            gap: 11px;
        }


        .action {

            min-height: 100px;

            position: relative;

            padding: 13px;

            border:
                1px solid var(--border);

            border-radius: 12px;

            background: var(--card-soft);

            text-decoration: none;

            transition:
                border-color 0.2s ease,
                background 0.2s ease,
                box-shadow 0.2s ease;
        }


        .action:hover {

            border-color: #d8b28f;

            background: #fffaf5;

            box-shadow:
                0 5px 13px
                rgba(43, 31, 23, 0.04);
        }


        .action-icon {

            width: 32px;
            height: 32px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 9px;

            background: #f4e7db;

            color: var(--orange);

            font-size: 0.875rem;

            margin-bottom: 9px;
        }


        .action-title {

            color: var(--dark);

            font-size: 0.875rem;

            font-weight: 800;

            line-height: 1.35;
        }


        .action-description {

            margin-top: 4px;

            color: var(--muted);

            font-size: 0.8125rem;

            line-height: 1.45;
        }


        /* =========================================================
           LOWER CONTENT
        ========================================================== */

        .content-grid {

            display: grid;

            grid-template-columns:
                minmax(0, 1.55fr)
                minmax(280px, 0.85fr);

            gap: 20px;
        }


        /* =========================================================
           INVENTORY STATUS
        ========================================================== */

        .inventory-list {

            padding:
                5px 21px 14px;
        }


        .inventory-row {

            min-height: 64px;

            display: flex;

            align-items: center;

            gap: 12px;

            padding: 10px 0;

            border-bottom:
                1px solid #f0ebe6;
        }


        .inventory-row:last-child {

            border-bottom: none;
        }


        .inventory-item-icon {

            width: 37px;
            height: 37px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 10px;

            background: #f7eee7;

            color: var(--orange);

            font-size: 0.875rem;

            flex-shrink: 0;
        }


        .inventory-info {

            flex: 1;

            min-width: 0;
        }


        .inventory-name {

            color: var(--dark);

            font-size: 0.875rem;

            font-weight: 700;

            line-height: 1.35;
        }


        .inventory-detail {

            margin-top: 3px;

            color: var(--muted);

            font-size: 0.8125rem;

            line-height: 1.4;
        }


        .stock-status {

            padding: 6px 9px;

            border-radius: 7px;

            font-size: 0.75rem;

            font-weight: 800;

            letter-spacing: 0.025rem;

            white-space: nowrap;
        }


        .status-out {

            color: var(--red);

            background: var(--red-light);
        }


        .status-low {

            color: var(--yellow);

            background: var(--yellow-light);
        }


        .status-normal {

            color: var(--green);

            background: var(--green-light);
        }


        /* =========================================================
           ACTIVITY
        ========================================================== */

        .activity-list {

            padding:
                7px 21px 15px;
        }


        .activity {

            display: flex;

            gap: 12px;

            padding: 12px 0;

            border-bottom:
                1px solid #f0ebe6;
        }


        .activity:last-child {

            border-bottom: none;
        }


        .activity-dot {

            width: 9px;
            height: 9px;

            margin-top: 7px;

            border-radius: 50%;

            background: var(--orange);

            flex-shrink: 0;

            box-shadow:
                0 0 0 4px
                rgba(196, 122, 58, 0.09);
        }


        .activity-text {

            color: #625951;

            font-size: 0.875rem;

            line-height: 1.55;
        }


        .activity-text strong {

            color: var(--dark);
        }


        .activity-time {

            margin-top: 4px;

            color: #938a83;

            font-size: 0.75rem;

            line-height: 1.4;
        }


        /* =========================================================
           VIEW LINK
        ========================================================== */

        .view-link {

            color: var(--orange);

            font-size: 0.8125rem;

            font-weight: 800;

            text-decoration: none;

            transition:
                color 0.2s ease;
        }


        .view-link:hover {

            color: var(--orange-dark);
        }


        /* =========================================================
           RESPONSIVE — 1250px
        ========================================================== */

        @media (max-width: 1250px) {

            .stats {

                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }


            .analytics-grid,
            .content-grid {

                grid-template-columns: 1fr;
            }


            .quick-actions {

                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

        }


        /* =========================================================
           RESPONSIVE — 850px
        ========================================================== */

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

                padding: 6px 3px;
            }


            .user-info {

                display: none;
            }


            .logout-button {

                justify-content: center;

                padding: 5px 3px;
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
        ========================================================== */

        @media (max-width: 600px) {

            .stats {

                grid-template-columns: 1fr;
            }


            .quick-actions {

                grid-template-columns: 1fr;
            }


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


            .chart-container {

                padding:
                    12px;
            }


            .page-title h1 {

                font-size: 1.75rem;
            }


            .page-title p {

                font-size: 0.875rem;
            }


            .panel-header {

                padding:
                    15px;
            }


            .inventory-list,
            .activity-list {

                padding-left: 15px;

                padding-right: 15px;
            }

        }


        /* =========================================================
           RESPONSIVE — 420px
        ========================================================== */

        @media (max-width: 420px) {

            .main {

                padding:
                    18px 11px 25px;
            }


            .stat-card {

                padding:
                    18px 16px;
            }


            .quick-actions {

                padding:
                    14px;
            }


            .action {

                min-height: 96px;
            }


            .stock-status {

                padding:
                    5px 7px;

                font-size: 0.6875rem;
            }

        }

    </style>

</head>


<body>

<div class="app">


    <!-- =========================================================
         SIDEBAR
    ========================================================== -->

    <aside class="sidebar">


        <!-- =====================================================
             LOGO
        ====================================================== -->

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


            <!-- =================================================
                 INVENTORY
            ================================================== -->

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


            <!-- =================================================
                 PRODUCTS
            ================================================== -->

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


            <!-- =================================================
                 SUPPLIERS
            ================================================== -->

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


            <!-- =================================================
                 PURCHASES
            ================================================== -->

            <a
                href="#"
                class="nav-item"
            >

                <span class="nav-icon">
                    ▤
                </span>

                <span>
                    Purchases
                </span>

            </a>


            <!-- =================================================
                 SALES
            ================================================== -->

            <a
                href="#"
                class="nav-item"
            >

                <span class="nav-icon">
                    ₱
                </span>

                <span>
                    Sales
                </span>

            </a>


            <!-- =================================================
                 EXPENSES
            ================================================== -->

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


            <!-- =================================================
                 REPORTS
            ================================================== -->

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


        <!-- =====================================================
             ADMINISTRATION
        ====================================================== -->

        <div class="admin-section">

            <div class="nav-title">
                Administration
            </div>


            <nav class="nav">


                <!-- USER MANAGEMENT -->

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


                <!-- SYSTEM SETTINGS -->

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


                <!-- AUDIT LOGS -->

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


        <!-- =====================================================
             USER / LOGOUT
        ====================================================== -->

        <div class="sidebar-bottom">


            <!-- USER -->

            <div class="user-mini">

                <div class="avatar">

                    {{ strtoupper(substr($user->name, 0, 1)) }}

                </div>


                <div class="user-info">

                    <div class="user-name">
                        {{ $user->name }}
                    </div>

                    <div class="user-role">
                        {{ $user->role }}
                    </div>

                </div>

            </div>


            <!-- LOGOUT -->

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


        <!-- =====================================================
             TOPBAR
        ====================================================== -->

        <div class="topbar">

            <div class="page-title">

                <small>
                    CEO / Administration
                </small>

                <h1>
                    Dashboard
                </h1>

                <p>
                    Welcome back, {{ $user->name }}.
                    Here's your BiteSync overview.
                </p>

            </div>


            <div class="date-box">

                <span class="date-icon">
                    ◷
                </span>

                {{ now()->format('F d, Y') }}

            </div>

        </div>


        <!-- =====================================================
             SUMMARY CARDS
        ====================================================== -->

        <section class="stats">


            <!-- INVENTORY -->

            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-label">
                        Inventory Items
                    </div>

                    <div class="stat-icon">
                        ▦
                    </div>

                </div>


                <div class="stat-value">
                    0
                </div>


                <div class="stat-note">
                    Total active inventory items
                </div>

            </div>


            <!-- LOW STOCK -->

            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-label">
                        Low Stock
                    </div>

                    <div class="stat-icon">
                        !
                    </div>

                </div>


                <div class="stat-value">
                    0
                </div>


                <div class="stat-note">
                    Items requiring attention
                </div>

            </div>


            <!-- PURCHASES -->

            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-label">
                        Purchases
                    </div>

                    <div class="stat-icon">
                        ▤
                    </div>

                </div>


                <div class="stat-value">
                    ₱0.00
                </div>


                <div class="stat-note">
                    No purchase records yet
                </div>

            </div>


            <!-- EXPENSES -->

            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-label">
                        Expenses
                    </div>

                    <div class="stat-icon">
                        ₱
                    </div>

                </div>


                <div class="stat-value">
                    ₱0.00
                </div>


                <div class="stat-note">
                    No expense records yet
                </div>

            </div>

        </section>


        <!-- =====================================================
             ANALYTICS
        ====================================================== -->

        <section class="analytics-grid">


            <!-- =================================================
                 INVENTORY OVERVIEW
            ================================================== -->

            <div class="panel">


                <div class="panel-header">

                    <div>

                        <div class="panel-title">
                            Inventory Overview
                        </div>

                        <div class="panel-subtitle">
                            Current stock status by category
                        </div>

                    </div>


                    <div class="panel-badge">
                        Inventory
                    </div>

                </div>


                <div class="chart-container">

                    <div class="chart-area">

                        <svg
                            class="chart-svg"
                            viewBox="0 0 700 220"
                            preserveAspectRatio="none"
                        >

                            <line
                                class="chart-grid-line"
                                x1="55"
                                y1="25"
                                x2="680"
                                y2="25"
                            />

                            <line
                                class="chart-grid-line"
                                x1="55"
                                y1="70"
                                x2="680"
                                y2="70"
                            />

                            <line
                                class="chart-grid-line"
                                x1="55"
                                y1="115"
                                x2="680"
                                y2="115"
                            />

                            <line
                                class="chart-grid-line"
                                x1="55"
                                y1="160"
                                x2="680"
                                y2="160"
                            />

                            <line
                                class="chart-axis"
                                x1="55"
                                y1="195"
                                x2="680"
                                y2="195"
                            />


                            <text
                                class="chart-label"
                                x="20"
                                y="29"
                            >
                                20
                            </text>

                            <text
                                class="chart-label"
                                x="25"
                                y="74"
                            >
                                15
                            </text>

                            <text
                                class="chart-label"
                                x="25"
                                y="119"
                            >
                                10
                            </text>

                            <text
                                class="chart-label"
                                x="30"
                                y="164"
                            >
                                5
                            </text>

                            <text
                                class="chart-label"
                                x="30"
                                y="199"
                            >
                                0
                            </text>


                            <rect
                                class="chart-bar-empty"
                                x="90"
                                y="193"
                                width="55"
                                height="2"
                            />

                            <rect
                                class="chart-bar-empty"
                                x="190"
                                y="193"
                                width="55"
                                height="2"
                            />

                            <rect
                                class="chart-bar-empty"
                                x="290"
                                y="193"
                                width="55"
                                height="2"
                            />

                            <rect
                                class="chart-bar-empty"
                                x="390"
                                y="193"
                                width="55"
                                height="2"
                            />

                            <rect
                                class="chart-bar-empty"
                                x="490"
                                y="193"
                                width="55"
                                height="2"
                            />

                            <rect
                                class="chart-bar-empty"
                                x="590"
                                y="193"
                                width="55"
                                height="2"
                            />


                            <text
                                class="chart-label"
                                x="94"
                                y="213"
                            >
                                Meat
                            </text>

                            <text
                                class="chart-label"
                                x="185"
                                y="213"
                            >
                                Vegetables
                            </text>

                            <text
                                class="chart-label"
                                x="300"
                                y="213"
                            >
                                Dairy
                            </text>

                            <text
                                class="chart-label"
                                x="395"
                                y="213"
                            >
                                Beverages
                            </text>

                            <text
                                class="chart-label"
                                x="500"
                                y="213"
                            >
                                Dry Goods
                            </text>

                            <text
                                class="chart-label"
                                x="605"
                                y="213"
                            >
                                Other
                            </text>

                        </svg>

                    </div>


                    <div class="chart-legend">

                        <div class="legend-item">

                            <span class="legend-dot"></span>

                            <span>
                                No inventory records yet
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 BUSINESS ACTIVITY
            ================================================== -->

            <div class="panel">


                <div class="panel-header">

                    <div>

                        <div class="panel-title">
                            Business Activity
                        </div>

                        <div class="panel-subtitle">
                            Recent financial activity
                        </div>

                    </div>


                    <div class="panel-badge">
                        Overview
                    </div>

                </div>


                <div class="chart-container">

                    <div class="chart-area">

                        <svg
                            class="chart-svg"
                            viewBox="0 0 500 220"
                            preserveAspectRatio="none"
                        >

                            <line
                                class="chart-grid-line"
                                x1="45"
                                y1="30"
                                x2="475"
                                y2="30"
                            />

                            <line
                                class="chart-grid-line"
                                x1="45"
                                y1="75"
                                x2="475"
                                y2="75"
                            />

                            <line
                                class="chart-grid-line"
                                x1="45"
                                y1="120"
                                x2="475"
                                y2="120"
                            />

                            <line
                                class="chart-grid-line"
                                x1="45"
                                y1="165"
                                x2="475"
                                y2="165"
                            />

                            <line
                                class="chart-axis"
                                x1="45"
                                y1="195"
                                x2="475"
                                y2="195"
                            />


                            <line
                                class="chart-line"
                                x1="55"
                                y1="194"
                                x2="465"
                                y2="194"
                                style="opacity: 0.25;"
                            />


                            <circle
                                class="chart-point"
                                cx="65"
                                cy="194"
                                r="4"
                                style="opacity: 0.55;"
                            />

                            <circle
                                class="chart-point"
                                cx="135"
                                cy="194"
                                r="4"
                                style="opacity: 0.55;"
                            />

                            <circle
                                class="chart-point"
                                cx="205"
                                cy="194"
                                r="4"
                                style="opacity: 0.55;"
                            />

                            <circle
                                class="chart-point"
                                cx="275"
                                cy="194"
                                r="4"
                                style="opacity: 0.55;"
                            />

                            <circle
                                class="chart-point"
                                cx="345"
                                cy="194"
                                r="4"
                                style="opacity: 0.55;"
                            />

                            <circle
                                class="chart-point"
                                cx="415"
                                cy="194"
                                r="4"
                                style="opacity: 0.55;"
                            />

                            <circle
                                class="chart-point"
                                cx="465"
                                cy="194"
                                r="4"
                                style="opacity: 0.55;"
                            />


                            <text
                                class="chart-label"
                                x="52"
                                y="213"
                            >
                                Jan
                            </text>

                            <text
                                class="chart-label"
                                x="122"
                                y="213"
                            >
                                Feb
                            </text>

                            <text
                                class="chart-label"
                                x="192"
                                y="213"
                            >
                                Mar
                            </text>

                            <text
                                class="chart-label"
                                x="262"
                                y="213"
                            >
                                Apr
                            </text>

                            <text
                                class="chart-label"
                                x="332"
                                y="213"
                            >
                                May
                            </text>

                            <text
                                class="chart-label"
                                x="402"
                                y="213"
                            >
                                Jun
                            </text>

                            <text
                                class="chart-label"
                                x="450"
                                y="213"
                            >
                                Jul
                            </text>

                        </svg>

                    </div>


                    <div class="chart-legend">

                        <div class="legend-item">

                            <span class="legend-dot orange"></span>

                            <span>
                                No financial records yet
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        <!-- =====================================================
             QUICK ACTIONS
        ====================================================== -->

        <div
            class="panel"
            style="margin-bottom: 20px;"
        >

            <div class="panel-header">

                <div>

                    <div class="panel-title">
                        Quick Actions
                    </div>

                    <div class="panel-subtitle">
                        Frequently used management functions
                    </div>

                </div>

            </div>


            <div class="quick-actions">


                <!-- =================================================
                     ADD INVENTORY
                ================================================== -->

                <a
                    href="{{ route('inventory.create') }}"
                    class="action"
                >

                    <div class="action-icon">
                        +
                    </div>

                    <div class="action-title">
                        Add Inventory
                    </div>

                    <div class="action-description">
                        Register a new stock item
                    </div>

                </a>


                <!-- =================================================
                     PRODUCTS
                ================================================== -->

                <a
                    href="{{ route('products.index') }}"
                    class="action"
                >

                    <div class="action-icon">
                        ◈
                    </div>

                    <div class="action-title">
                        Products
                    </div>

                    <div class="action-description">
                        Manage café menu products
                    </div>

                </a>


                <!-- =================================================
                     NEW PURCHASE
                ================================================== -->

                <a
                    href="#"
                    class="action"
                >

                    <div class="action-icon">
                        ▤
                    </div>

                    <div class="action-title">
                        New Purchase
                    </div>

                    <div class="action-description">
                        Create a purchase record
                    </div>

                </a>


                <!-- =================================================
                     REPORTS
                ================================================== -->

                <a
                    href="#"
                    class="action"
                >

                    <div class="action-icon">
                        ▥
                    </div>

                    <div class="action-title">
                        View Reports
                    </div>

                    <div class="action-description">
                        Review system reports
                    </div>

                </a>

            </div>

        </div>


        <!-- =====================================================
             LOWER CONTENT
        ====================================================== -->

        <section class="content-grid">


            <!-- =================================================
                 INVENTORY STATUS
            ================================================== -->

            <div class="panel">

                <div class="panel-header">

                    <div>

                        <div class="panel-title">
                            Inventory Status
                        </div>

                        <div class="panel-subtitle">
                            Items that require monitoring
                        </div>

                    </div>


                    <a
                        href="{{ route('inventory.index') }}"
                        class="view-link"
                    >
                        View Inventory
                    </a>

                </div>


                <div class="inventory-list">


                    <!-- BURGER PATTY -->

                    <div class="inventory-row">

                        <div class="inventory-item-icon">
                            ◈
                        </div>


                        <div class="inventory-info">

                            <div class="inventory-name">
                                Burger Patty
                            </div>

                            <div class="inventory-detail">
                                No stock recorded
                            </div>

                        </div>


                        <span class="stock-status status-out">
                            NO STOCK
                        </span>

                    </div>


                    <!-- CHICKEN -->

                    <div class="inventory-row">

                        <div class="inventory-item-icon">
                            ◈
                        </div>


                        <div class="inventory-info">

                            <div class="inventory-name">
                                Chicken
                            </div>

                            <div class="inventory-detail">
                                No stock recorded
                            </div>

                        </div>


                        <span class="stock-status status-out">
                            NO STOCK
                        </span>

                    </div>


                    <!-- COFFEE BEANS -->

                    <div class="inventory-row">

                        <div class="inventory-item-icon">
                            ◈
                        </div>


                        <div class="inventory-info">

                            <div class="inventory-name">
                                Coffee Beans
                            </div>

                            <div class="inventory-detail">
                                No stock recorded
                            </div>

                        </div>


                        <span class="stock-status status-out">
                            NO STOCK
                        </span>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 RECENT ACTIVITY
            ================================================== -->

            <div class="panel">

                <div class="panel-header">

                    <div>

                        <div class="panel-title">
                            Recent Activity
                        </div>

                        <div class="panel-subtitle">
                            Latest system actions
                        </div>

                    </div>

                </div>


                <div class="activity-list">


                    <!-- LOGIN -->

                    <div class="activity">

                        <div class="activity-dot"></div>

                        <div>

                            <div class="activity-text">

                                <strong>
                                    {{ $user->name }}
                                </strong>

                                signed in to BiteSync.

                            </div>

                            <div class="activity-time">
                                Just now
                            </div>

                        </div>

                    </div>


                    <!-- INVENTORY -->

                    <div class="activity">

                        <div class="activity-dot"></div>

                        <div>

                            <div class="activity-text">
                                Inventory monitoring is ready
                                for use.
                            </div>

                            <div class="activity-time">
                                System
                            </div>

                        </div>

                    </div>


                    <!-- PRODUCTS -->

                    <div class="activity">

                        <div class="activity-dot"></div>

                        <div>

                            <div class="activity-text">
                                Product and recipe management
                                are ready to be configured.
                            </div>

                            <div class="activity-time">
                                System
                            </div>

                        </div>

                    </div>


                    <!-- PROCUREMENT -->

                    <div class="activity">

                        <div class="activity-dot"></div>

                        <div>

                            <div class="activity-text">
                                Procurement and financial
                                records can be managed from
                                BiteSync.
                            </div>

                            <div class="activity-time">
                                System
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>


    </main>

</div>

</body>

</html>