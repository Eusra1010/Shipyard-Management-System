<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NavalForge — @yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',Arial,sans-serif; background:#dde3ed; color:#1e293b; display:flex; min-height:100vh; }
        a { text-decoration:none; color:inherit; }

        /* ── Sidebar ── */
        .adm-sidebar {
            position:fixed; top:0; left:0; width:180px; height:100vh;
            background:#0f172a; display:flex; flex-direction:column;
            z-index:50; overflow-y:auto;
        }
        .adm-logo {
            padding:16px 14px; display:flex; align-items:center; gap:10px;
            border-bottom:1px solid #1e293b; flex-shrink:0;
        }
        .adm-logo-icon {
            width:32px; height:32px; background:#1d4ed8; border-radius:8px;
            display:flex; align-items:center; justify-content:center; flex-shrink:0;
        }
        .adm-nav { padding:10px 8px; flex:1; display:flex; flex-direction:column; gap:2px; }
        .adm-nav a {
            display:flex; align-items:center; gap:9px;
            padding:8px 10px; border-radius:8px;
            font-size:13px; font-weight:500; color:#94a3b8;
        }
        .adm-nav a:hover { background:#1e293b; color:#e2e8f0; }
        .adm-nav a.active { background:#2563eb; color:#fff; }
        .adm-nav a i { width:14px; font-size:12px; flex-shrink:0; text-align:center; }
        .nav-section {
            font-size:9px; font-weight:700; color:#334155; letter-spacing:.1em;
            text-transform:uppercase; padding:10px 10px 3px; display:block;
        }
        .adm-user {
            padding:12px 12px 14px; border-top:1px solid #1e293b; flex-shrink:0;
        }

        /* ── Main wrapper ── */
        .adm-main { margin-left:180px; flex:1; display:flex; flex-direction:column; min-height:100vh; }
        .adm-topbar {
            height:50px; background:#fff; border-bottom:1px solid #e2e8f0;
            display:flex; align-items:center; justify-content:space-between;
            padding:0 24px; flex-shrink:0;
        }
        .adm-content { padding:22px 24px; flex:1; }
    </style>
    @stack('styles')
</head>
<body>

{{-- ── Sidebar ── --}}
<aside class="adm-sidebar">

    <div class="adm-logo">
        <div class="adm-logo-icon">
            <i class="fas fa-ship" style="font-size:14px;color:#fff;"></i>
        </div>
        <div>
            <div style="font-size:13px;font-weight:700;color:#f1f5f9;line-height:1.1;">NavalForge</div>
            <div style="font-size:9px;color:#475569;letter-spacing:.04em;margin-top:1px;">Shipyard System</div>
        </div>
    </div>

    <nav class="adm-nav">
        <span class="nav-section">Main</span>

        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="{{ route('ships.index') }}" class="{{ request()->routeIs('ships.*') ? 'active' : '' }}">
            <i class="fas fa-ship"></i> Ships
        </a>

        <span class="nav-section">Operations</span>

        <a href="{{ route('berths.index') }}" class="{{ request()->routeIs('berths.*') ? 'active' : '' }}">
            <i class="fas fa-water"></i> Berths
        </a>
        <a href="{{ route('work-orders.index') }}" class="{{ request()->routeIs('work-orders.*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-list"></i> Work Orders
        </a>
        <a href="{{ route('workers.index') }}" class="{{ request()->routeIs('workers.*') ? 'active' : '' }}">
            <i class="fas fa-hard-hat"></i> Workers
        </a>
        <a href="{{ route('materials.index') }}" class="{{ request()->routeIs('materials.*') ? 'active' : '' }}">
            <i class="fas fa-boxes"></i> Materials
        </a>

        <span class="nav-section">Admin</span>
        <a href="{{ route('admin.news.index') }}" class="{{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
            <i class="fas fa-newspaper"></i> News
        </a>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fas fa-users-cog"></i> Manage Users
        </a>
    </nav>

    <div class="adm-user">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
            <div style="width:28px;height:28px;border-radius:50%;background:#1e3a5f;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-user" style="font-size:11px;color:#93c5fd;"></i>
            </div>
            <div style="min-width:0;">
                <div style="font-size:12px;font-weight:600;color:#e2e8f0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ Auth::user()->name }}</div>
                <div style="font-size:10px;color:#475569;text-transform:capitalize;">{{ Auth::user()->role }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="width:100%;font-size:11px;font-weight:500;padding:6px 8px;border:1px solid #1e293b;border-radius:6px;background:transparent;color:#64748b;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;">
                <i class="fas fa-sign-out-alt"></i> Sign out
            </button>
        </form>
    </div>

</aside>

{{-- ── Main area ── --}}
<div class="adm-main">

    <div class="adm-topbar">
        <div>
            <div style="font-size:14px;font-weight:600;color:#0f172a;">@yield('page-title', 'Dashboard')</div>
            <div style="font-size:11px;color:#94a3b8;margin-top:1px;">@yield('breadcrumb', 'NavalForge / Dashboard')</div>
        </div>
        <a href="{{ route('home') }}" style="font-size:12px;color:#64748b;display:flex;align-items:center;gap:5px;">
            <i class="fas fa-external-link-alt" style="font-size:10px;"></i> Public site
        </a>
    </div>

    <main class="adm-content">
        @yield('content')
    </main>

</div>

@stack('scripts')
</body>
</html>
