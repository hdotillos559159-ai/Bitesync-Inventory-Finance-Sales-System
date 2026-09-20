<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>BiteSync | Sign In</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --cream: #f7f3ed;
            --cream-light: #fcfaf7;
            --brown-dark: #241a14;
            --brown: #4a3425;
            --brown-soft: #76563d;
            --orange: #c47a3a;
            --orange-dark: #a85f28;
            --text: #2c241f;
            --muted: #81776f;
            --border: #ddd4ca;
            --white: #ffffff;
            --danger: #b94b4b;
        }

        body {
            min-height: 100vh;
            font-family:
                "Segoe UI",
                Arial,
                Helvetica,
                sans-serif;

            background: var(--cream);

            color: var(--text);

            overflow-x: hidden;
        }

        /* =========================================================
           PAGE
        ========================================================= */

        .page {
            min-height: 100vh;

            display: flex;
            align-items: stretch;
        }

        /* =========================================================
           BRAND PANEL
        ========================================================= */

        .brand-panel {
            width: 46%;

            position: relative;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 60px;

            overflow: hidden;

            background:
                radial-gradient(
                    circle at 15% 15%,
                    rgba(196, 122, 58, 0.28),
                    transparent 30%
                ),
                linear-gradient(
                    145deg,
                    #241a14 0%,
                    #332319 52%,
                    #4b3020 100%
                );

            color: white;
        }

        .brand-panel::before {
            content: "";

            position: absolute;

            width: 420px;
            height: 420px;

            right: -180px;
            top: -160px;

            border-radius: 50%;

            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .brand-panel::after {
            content: "";

            position: absolute;

            width: 520px;
            height: 520px;

            left: -320px;
            bottom: -300px;

            border-radius: 50%;

            border: 1px solid rgba(255, 255, 255, 0.07);
        }

        .brand-content {
            width: 100%;
            max-width: 470px;

            position: relative;
            z-index: 2;
        }

        /* =========================================================
           BRAND
        ========================================================= */

        .brand-header {
            display: flex;
            align-items: center;

            gap: 15px;

            margin-bottom: 55px;
        }

        .brand-icon {
            width: 58px;
            height: 58px;

            border-radius: 17px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: linear-gradient(
                135deg,
                #d79555,
                #a85f28
            );

            box-shadow:
                0 10px 30px rgba(0, 0, 0, 0.25);
        }

        .brand-icon span {
            font-size: 26px;
            font-weight: 800;
        }

        .brand-name {
            font-size: 25px;
            font-weight: 800;

            letter-spacing: -0.5px;
        }

        .brand-tagline {
            font-size: 12px;

            color: rgba(255, 255, 255, 0.62);

            margin-top: 2px;

            letter-spacing: 1.2px;

            text-transform: uppercase;
        }

        /* =========================================================
           HERO TEXT
        ========================================================= */

        .brand-content h1 {
            font-size: clamp(42px, 4vw, 66px);

            line-height: 1.02;

            letter-spacing: -2.5px;

            margin-bottom: 25px;
        }

        .brand-content h1 span {
            color: #d99553;
        }

        .brand-description {
            max-width: 410px;

            font-size: 16px;

            line-height: 1.75;

            color: rgba(255, 255, 255, 0.67);
        }

        /* =========================================================
           FEATURE LIST
        ========================================================= */

        .features {
            margin-top: 45px;

            display: grid;

            gap: 17px;
        }

        .feature {
            display: flex;
            align-items: center;

            gap: 13px;

            font-size: 14px;

            color: rgba(255, 255, 255, 0.78);
        }

        .feature-icon {
            width: 31px;
            height: 31px;

            border-radius: 10px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: rgba(255, 255, 255, 0.08);

            color: #e0a164;

            font-size: 13px;

            flex-shrink: 0;
        }

        /* =========================================================
           FORM PANEL
        ========================================================= */

        .form-panel {
            width: 54%;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 50px;

            background: var(--cream-light);
        }

        .login-container {
            width: 100%;
            max-width: 440px;
        }

        /* =========================================================
           FORM HEADER
        ========================================================= */

        .form-header {
            margin-bottom: 35px;
        }

        .welcome {
            font-size: 13px;

            font-weight: 700;

            color: var(--orange);

            text-transform: uppercase;

            letter-spacing: 1.3px;

            margin-bottom: 10px;
        }

        .form-header h2 {
            font-size: 38px;

            line-height: 1.15;

            letter-spacing: -1.2px;

            color: var(--brown-dark);

            margin-bottom: 10px;
        }

        .form-header p {
            color: var(--muted);

            font-size: 14px;

            line-height: 1.6;
        }

        /* =========================================================
           ERROR MESSAGE
        ========================================================= */

        .error-box {
            display: flex;
            align-items: flex-start;

            gap: 11px;

            padding: 13px 15px;

            margin-bottom: 23px;

            border-radius: 12px;

            background: #fff2f1;

            border: 1px solid #f0cccc;

            color: var(--danger);

            font-size: 13px;

            line-height: 1.5;
        }

        .error-icon {
            width: 20px;
            height: 20px;

            flex-shrink: 0;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            background: var(--danger);

            color: white;

            font-size: 11px;

            font-weight: 800;
        }

        /* =========================================================
           FORM GROUP
        ========================================================= */

        .form-group {
            margin-bottom: 22px;
        }

        .form-label {
            display: block;

            font-size: 13px;

            font-weight: 700;

            color: var(--brown-dark);

            margin-bottom: 9px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;

            left: 16px;
            top: 50%;

            transform: translateY(-50%);

            color: #a69a90;

            font-size: 15px;

            pointer-events: none;
        }

        .form-input {
            width: 100%;

            height: 54px;

            padding:
                0
                48px;

            border: 1px solid var(--border);

            border-radius: 13px;

            outline: none;

            background: white;

            color: var(--text);

            font-size: 14px;

            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease;
        }

        .form-input::placeholder {
            color: #aaa19a;
        }

        .form-input:hover {
            border-color: #cbbfb4;
        }

        .form-input:focus {
            border-color: var(--orange);

            background: #fff;

            box-shadow:
                0 0 0 4px rgba(196, 122, 58, 0.10);
        }

        .password-toggle {
            position: absolute;

            right: 15px;
            top: 50%;

            transform: translateY(-50%);

            border: none;

            background: transparent;

            color: #968b82;

            cursor: pointer;

            font-size: 14px;

            padding: 5px;
        }

        .password-toggle:hover {
            color: var(--orange);
        }

        /* =========================================================
           REMEMBER
        ========================================================= */

        .form-options {
            display: flex;

            align-items: center;

            justify-content: space-between;

            margin-top: 5px;

            margin-bottom: 27px;
        }

        .remember {
            display: flex;
            align-items: center;

            gap: 9px;

            font-size: 13px;

            color: #71675f;

            cursor: pointer;
        }

        .remember input {
            width: 16px;
            height: 16px;

            accent-color: var(--orange);

            cursor: pointer;
        }

        /* =========================================================
           LOGIN BUTTON
        ========================================================= */

        .login-button {
            width: 100%;

            height: 55px;

            border: none;

            border-radius: 13px;

            background:
                linear-gradient(
                    135deg,
                    #c47a3a,
                    #a9612b
                );

            color: white;

            font-size: 14px;

            font-weight: 700;

            letter-spacing: 0.2px;

            cursor: pointer;

            box-shadow:
                0 9px 22px rgba(168, 95, 40, 0.22);

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                filter 0.2s ease;
        }

        .login-button:hover {
            transform: translateY(-2px);

            box-shadow:
                0 13px 27px rgba(168, 95, 40, 0.28);

            filter: brightness(1.03);
        }

        .login-button:active {
            transform: translateY(0);

            box-shadow:
                0 6px 14px rgba(168, 95, 40, 0.20);
        }

        /* =========================================================
           FOOTER
        ========================================================= */

        .login-footer {
            margin-top: 30px;

            text-align: center;

            font-size: 12px;

            color: #aaa099;
        }

        .login-footer strong {
            color: #786a60;
        }

        /* =========================================================
           SECURITY BADGE
        ========================================================= */

        .security-badge {
            margin-top: 19px;

            display: flex;

            align-items: center;
            justify-content: center;

            gap: 7px;

            color: #9b9189;

            font-size: 11px;
        }

        .security-dot {
            width: 7px;
            height: 7px;

            border-radius: 50%;

            background: #7da66d;
        }

        /* =========================================================
           ANIMATION
        ========================================================= */

        .brand-content,
        .login-container {
            animation: fadeUp 0.6s ease both;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;

                transform: translateY(12px);
            }

            to {
                opacity: 1;

                transform: translateY(0);
            }
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 900px) {

            .page {
                flex-direction: column;
            }

            .brand-panel {
                width: 100%;

                min-height: 350px;

                padding: 45px 30px;

                align-items: flex-start;
            }

            .brand-header {
                margin-bottom: 35px;
            }

            .brand-content h1 {
                font-size: 42px;
            }

            .brand-description {
                font-size: 14px;
            }

            .features {
                display: none;
            }

            .form-panel {
                width: 100%;

                padding: 45px 25px;

                min-height: 550px;
            }
        }

        @media (max-width: 500px) {

            .brand-panel {
                min-height: 300px;

                padding: 30px 23px;
            }

            .brand-header {
                margin-bottom: 30px;
            }

            .brand-icon {
                width: 48px;
                height: 48px;

                border-radius: 14px;
            }

            .brand-icon span {
                font-size: 21px;
            }

            .brand-name {
                font-size: 21px;
            }

            .brand-content h1 {
                font-size: 34px;

                letter-spacing: -1.5px;
            }

            .brand-description {
                font-size: 13px;

                line-height: 1.6;
            }

            .form-panel {
                padding: 35px 20px;
            }

            .form-header h2 {
                font-size: 31px;
            }

            .form-options {
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <!-- =========================================================
         LEFT BRAND PANEL
    ========================================================== -->

    <section class="brand-panel">

        <div class="brand-content">

            <div class="brand-header">

                <div class="brand-icon">
                    <span>B</span>
                </div>

                <div>
                    <div class="brand-name">
                        BiteSync
                    </div>

                    <div class="brand-tagline">
                        Inventory Management System
                    </div>
                </div>

            </div>

            <h1>
                Manage smarter.<br>
                <span>Serve better.</span>
            </h1>

            <p class="brand-description">
                A centralized workspace for managing inventory,
                procurement, sales records, expenses, and business
                reports for The Crazy Bite Co.
            </p>

            <div class="features">

                <div class="feature">
                    <div class="feature-icon">
                        ✓
                    </div>

                    <span>
                        Centralized inventory management
                    </span>
                </div>

                <div class="feature">
                    <div class="feature-icon">
                        ✓
                    </div>

                    <span>
                        Procurement and stock monitoring
                    </span>
                </div>

                <div class="feature">
                    <div class="feature-icon">
                        ✓
                    </div>

                    <span>
                        Financial and operational records
                    </span>
                </div>

            </div>

        </div>

    </section>


    <!-- =========================================================
         RIGHT LOGIN PANEL
    ========================================================== -->

    <section class="form-panel">

        <div class="login-container">

            <div class="form-header">

                <div class="welcome">
                    Welcome back
                </div>

                <h2>
                    Sign in to BiteSync
                </h2>

                <p>
                    Enter your account details to access your workspace.
                </p>

            </div>


            <!-- =================================================
                 VALIDATION ERRORS
            ================================================== -->

            @if ($errors->any())

                <div class="error-box">

                    <div class="error-icon">
                        !
                    </div>

                    <div>
                        {{ $errors->first() }}
                    </div>

                </div>

            @endif


            <!-- =================================================
                 LOGIN FORM
            ================================================== -->

            <form
                method="POST"
                action="{{ route('login.authenticate') }}"
            >

                @csrf


                <!-- EMAIL -->

                <div class="form-group">

                    <label
                        class="form-label"
                        for="email"
                    >
                        Email Address
                    </label>

                    <div class="input-wrapper">

                        <span class="input-icon">
                            ✉
                        </span>

                        <input
                            class="form-input"
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Enter your email address"
                            autocomplete="email"
                            required
                            autofocus
                        >

                    </div>

                </div>


                <!-- PASSWORD -->

                <div class="form-group">

                    <label
                        class="form-label"
                        for="password"
                    >
                        Password
                    </label>

                    <div class="input-wrapper">

                        <span class="input-icon">
                            ●
                        </span>

                        <input
                            class="form-input"
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            id="passwordToggle"
                            aria-label="Show password"
                        >
                            Show
                        </button>

                    </div>

                </div>


                <!-- OPTIONS -->

                <div class="form-options">

                    <label class="remember">

                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                            {{ old('remember') ? 'checked' : '' }}
                        >

                        <span>
                            Remember me
                        </span>

                    </label>

                </div>


                <!-- LOGIN BUTTON -->

                <button
                    type="submit"
                    class="login-button"
                >
                    Sign In
                </button>

            </form>


            <!-- FOOTER -->

            <div class="login-footer">

                <div>
                    <strong>BiteSync</strong>
                    &nbsp;•&nbsp;
                    The Crazy Bite Co.
                </div>

            </div>


            <div class="security-badge">

                <span class="security-dot"></span>

                Secure system access

            </div>

        </div>

    </section>

</div>


<script>

    const passwordInput =
        document.getElementById("password");

    const passwordToggle =
        document.getElementById("passwordToggle");


    passwordToggle.addEventListener("click", function () {

        if (passwordInput.type === "password") {

            passwordInput.type = "text";

            passwordToggle.textContent = "Hide";

            passwordToggle.setAttribute(
                "aria-label",
                "Hide password"
            );

        } else {

            passwordInput.type = "password";

            passwordToggle.textContent = "Show";

            passwordToggle.setAttribute(
                "aria-label",
                "Show password"
            );

        }

    });

</script>

</body>
</html>