<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign in - BiteSync</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-page">
  <main class="login-shell">
    <section class="login-brand">
      <div class="login-brand-mark"><div class="login-mark">B</div><div><strong>BiteSync</strong><small>INVENTORY MANAGEMENT SYSTEM</small></div></div>
      <div class="eyebrow">CRAZY BITE CO.</div>
      <h1>Manage smarter.<br><em>Serve better.</em></h1>
      <p>A centralized workspace for managing inventory, procurement, sales records, expenses, and business reports for the Crazy Bite Co.</p>
      <ul class="login-features">
        <li>Centralized inventory management</li>
        <li>Procurement and stock monitoring</li>
        <li>Financial and operational records</li>
      </ul>
    </section>

    <section class="login-card">
      <div class="login-heading">
        <span class="eyebrow">WELCOME BACK</span>
        <h2>Sign in to your account</h2>
        <p>Enter your details below to access your workspace.</p>
      </div>
      <form method="POST" action="{{ route('login.store') }}" class="form login-form">
        @csrf
        <div class="form-group">
          <label for="email">Email address</label>
          <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" placeholder="you@example.com" required autofocus>
          @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <div class="field-label-row"><label for="password">Password</label><span>Required</span></div>
          <input id="password" name="password" type="password" autocomplete="current-password" placeholder="Enter your password" required>
          @error('password') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <label class="remember"><input type="checkbox" name="remember" value="1"> <span>Remember me</span></label>
        <button type="submit" class="btn login-button">Sign in</button>
      </form>
      <p class="login-note">BiteSync · The Crazy Bite Co.<br><span>●</span> Secure system access</p>
    </section>
  </main>
</body>
</html>
