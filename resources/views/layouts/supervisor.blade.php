<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NavalForge — @yield('title', 'Supervisor')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',Arial,sans-serif; background:#dde3ed; color:#1e293b; display:flex; min-height:100vh; }
        a { text-decoration:none; color:inherit; }

        /* ── Sidebar ── */
        .sv-sidebar {
            position:fixed; top:0; left:0; width:200px; height:100vh;
            background:#0f172a; display:flex; flex-direction:column;
            z-index:50; overflow-y:auto;
        }
        .sv-logo {
            padding:18px 16px; display:flex; align-items:center; gap:10px;
            border-bottom:1px solid #1e293b; flex-shrink:0;
        }
        .sv-logo-icon {
            width:34px; height:34px; background:#1d4ed8; border-radius:8px;
            display:flex; align-items:center; justify-content:center; flex-shrink:0;
        }
        .sv-badge {
            display:inline-block; font-size:8px; font-weight:700;
            background:#1e293b; color:#4a9ee0; border-radius:4px;
            padding:2px 6px; letter-spacing:.06em; text-transform:uppercase;
            margin-top:3px;
        }

        /* ── Nav ── */
        .sv-nav { padding:16px 10px; flex:1; display:flex; flex-direction:column; gap:3px; }
        .sv-nav-label {
            font-size:9px; font-weight:700; color:#334155; letter-spacing:.1em;
            text-transform:uppercase; padding:10px 10px 4px; display:block;
        }
        .sv-nav a {
            display:flex; align-items:center; gap:10px;
            padding:9px 12px; border-radius:8px;
            font-size:13px; font-weight:500; color:#94a3b8;
            transition:background .12s, color .12s;
        }
        .sv-nav a:hover { background:#1e293b; color:#e2e8f0; }
        .sv-nav a.active { background:#2563eb; color:#fff; }
        .sv-nav a i { width:15px; font-size:12px; flex-shrink:0; text-align:center; }

        /* ── User strip ── */
        .sv-user { padding:14px 14px 16px; border-top:1px solid #1e293b; flex-shrink:0; }

        /* ── Main ── */
        .sv-main { margin-left:200px; flex:1; display:flex; flex-direction:column; min-height:100vh; }
        .sv-topbar {
            height:52px; background:#fff; border-bottom:1px solid #e2e8f0;
            display:flex; align-items:center; justify-content:space-between;
            padding:0 26px; flex-shrink:0;
        }
        .sv-content { padding:24px 26px; flex:1; }
    </style>
    @stack('styles')
</head>
<body>

{{-- ── Sidebar ── --}}
<aside class="sv-sidebar">

    <div class="sv-logo">
        <div class="sv-logo-icon">
            <i class="fas fa-ship" style="font-size:15px;color:#fff;"></i>
        </div>
        <div>
            <div style="font-size:13px;font-weight:700;color:#f1f5f9;line-height:1.1;">NavalForge</div>
            <div class="sv-badge">Supervisor</div>
        </div>
    </div>

    <nav class="sv-nav">
        <span class="sv-nav-label">My Work</span>

        <a href="{{ route('dashboard') }}"
           class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-clipboard-list"></i> My Jobs
        </a>
        <a href="{{ route('supervisor.crew') }}"
           class="{{ request()->routeIs('supervisor.crew') ? 'active' : '' }}">
            <i class="fas fa-hard-hat"></i> My Crew
        </a>

        <span class="sv-nav-label" style="margin-top:4px;">Account</span>

        <a href="{{ route('supervisor.profile') }}"
           class="{{ request()->routeIs('supervisor.profile') ? 'active' : '' }}">
            <i class="fas fa-id-badge"></i> Profile
        </a>
    </nav>

    <div class="sv-user">
        @php
            $u     = Auth::user();
            $words = preg_split('/\s+/', trim($u->name));
            $ini   = strtoupper(implode('', array_map(fn($w) => $w[0] ?? '', $words)));
            $ini   = substr($ini, 0, 2);
        @endphp
        <div style="display:flex;align-items:center;gap:9px;margin-bottom:10px;">
            <div style="width:30px;height:30px;border-radius:50%;background:#1e3a5f;
                        display:flex;align-items:center;justify-content:center;flex-shrink:0;
                        font-size:11px;font-weight:700;color:#93c5fd;">
                {{ $ini }}
            </div>
            <div style="min-width:0;">
                <div style="font-size:12px;font-weight:600;color:#e2e8f0;
                            white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ $u->name }}
                </div>
                <div style="font-size:10px;color:#475569;">
                    {{ $u->team ?? 'Supervisor' }} Team
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    style="width:100%;font-size:11px;font-weight:500;padding:6px 8px;
                           border:1px solid #1e293b;border-radius:6px;background:transparent;
                           color:#64748b;cursor:pointer;display:flex;align-items:center;
                           justify-content:center;gap:6px;">
                <i class="fas fa-sign-out-alt"></i> Sign out
            </button>
        </form>
    </div>

</aside>

{{-- ── Main area ── --}}
<div class="sv-main">

    <div class="sv-topbar">
        <div>
            <div style="font-size:14px;font-weight:600;color:#0f172a;">@yield('page-title', 'My Jobs')</div>
            <div style="font-size:11px;color:#94a3b8;margin-top:1px;">@yield('breadcrumb')</div>
        </div>
        <a href="{{ route('home') }}"
           style="font-size:12px;color:#64748b;display:flex;align-items:center;gap:5px;">
            <i class="fas fa-external-link-alt" style="font-size:10px;"></i> Public site
        </a>
    </div>

    <main class="sv-content">
        @yield('content')
    </main>

</div>

</body>
</html>
