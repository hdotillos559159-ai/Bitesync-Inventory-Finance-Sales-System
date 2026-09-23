<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>@yield('title', 'BiteSync — Crazy Bite Co.')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="app-shell">
  <aside class="sidebar">
    <a class="sidebar-brand" href="{{ route('finance.index') }}">
      <span class="brand-avatar">B</span>
      <span><strong>BiteSync</strong><small>MANAGEMENT SYSTEM</small></span>
    </a>

    <div class="nav-label">MAIN MENU</div>
    <nav class="side-nav" aria-label="Main navigation">
      <a class="{{ request()->routeIs('finance.index') ? 'active' : '' }}" href="{{ route('finance.index') }}"><span>⌂</span>Dashboard</a>
      <a class="{{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}"><span>▥</span>Sales</a>
      <a class="{{ request()->routeIs('expenses.*') ? 'active' : '' }}" href="{{ route('expenses.index') }}"><span>▧</span>Expenses</a>
      <a class="{{ request()->routeIs('purchases.*') ? 'active' : '' }}" href="{{ route('purchases.index') }}"><span>▤</span>Purchases</a>
    </nav>

    <div class="nav-label account-label">ACCOUNT</div>
    <nav class="side-nav">
      <a class="{{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}"><span>⚙</span>System Settings</a>
    </nav>

    <div class="sidebar-user">
      <span class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
      <span><strong>{{ auth()->user()->name }}</strong><small>{{ auth()->user()->email }}</small></span>
    </div>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button class="sign-out" type="submit"><span>↪</span>Sign Out</button>
    </form>
  </aside>

  <main class="main-content">
    <header class="page-header">
      <div><div class="eyebrow">CRAZY BITE CO.</div><h1>@yield('page-title', 'Finance overview')</h1><p>@yield('tagline', 'Sales, expenses & purchases')</p></div>
      <div class="header-date">
        <span class="header-date-label">TODAY</span>
        <strong>{{ now()->format('D, M j, Y') }}</strong>
      </div>
    </header>

    @if (session('status'))
      <div class="flash">{{ session('status') }}</div>
    @endif

    @yield('content')

    <footer class="foot">BiteSync Management System <span>·</span> Crazy Bite Co.</footer>
  </main>
</div>

@yield('scripts')
</body>
</html>
