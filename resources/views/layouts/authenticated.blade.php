<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NavalForge — @isset($title){{ $title }}@else Dashboard @endisset</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f1f5f9; color: #1e293b; display: flex; flex-direction: column; min-height: 100vh; }
        a { text-decoration: none; color: inherit; }
    </style>
</head>
<body>

{{-- Top nav --}}
<nav style="height:60px;background:#0f172a;border-bottom:1px solid #1e293b;display:flex;align-items:center;justify-content:space-between;padding:0 2rem;position:sticky;top:0;z-index:100;flex-shrink:0;">
    <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:10px;">
        <i class="fas fa-ship" style="font-size:20px;color:#3b82f6;"></i>
        <span style="font-size:16px;font-weight:700;color:#f8fafc;">NavalForge</span>
    </a>

    <div style="display:flex;align-items:center;gap:2px;">
        <a href="{{ route('dashboard') }}"
           style="font-size:13px;padding:7px 14px;border-radius:6px;color:{{ request()->routeIs('dashboard') ? '#fff' : '#94a3b8' }};background:{{ request()->routeIs('dashboard') ? '#1d4ed8' : 'transparent' }};">
            <i class="fas fa-tachometer-alt" style="margin-right:6px;"></i>Dashboard
        </a>

        <a href="{{ route('ships.index') }}"
           style="font-size:13px;padding:7px 14px;border-radius:6px;color:{{ request()->routeIs('ships.*') ? '#fff' : '#94a3b8' }};background:{{ request()->routeIs('ships.*') ? '#1d4ed8' : 'transparent' }};">
            <i class="fas fa-ship" style="margin-right:6px;"></i>Ships
        </a>

        @if(Auth::user()->role === 'admin')
        <a href="{{ route('admin.users.index') }}"
           style="font-size:13px;padding:7px 14px;border-radius:6px;color:{{ request()->routeIs('admin.users.*') ? '#fff' : '#94a3b8' }};background:{{ request()->routeIs('admin.users.*') ? '#1d4ed8' : 'transparent' }};">
            <i class="fas fa-users-cog" style="margin-right:6px;"></i>Manage Users
        </a>
        @endif
    </div>

    <div style="display:flex;align-items:center;gap:12px;">
        <div style="display:flex;align-items:center;gap:8px;">
            <div style="width:32px;height:32px;border-radius:50%;background:#1e3a5f;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-user" style="font-size:13px;color:#93c5fd;"></i>
            </div>
            <div>
                <div style="font-size:13px;font-weight:600;color:#f1f5f9;">{{ Auth::user()->name }}</div>
                <div style="font-size:11px;color:#64748b;line-height:1;">
                    <span style="display:inline-block;padding:1px 7px;border-radius:999px;font-size:10px;font-weight:600;background:{{ Auth::user()->role === 'admin' ? '#1e3a5f' : '#1e293b' }};color:{{ Auth::user()->role === 'admin' ? '#93c5fd' : '#94a3b8' }};">
                        {{ Auth::user()->role }}
                    </span>
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" style="font-size:12px;font-weight:600;padding:7px 16px;border:1px solid #334155;border-radius:6px;background:transparent;cursor:pointer;color:#94a3b8;">
                <i class="fas fa-sign-out-alt" style="margin-right:5px;"></i>Logout
            </button>
        </form>
    </div>
</nav>

{{-- Page header (optional) --}}
@isset($header)
<div style="background:#fff;border-bottom:1px solid #e2e8f0;padding:1rem 2rem;">
    {{ $header }}
</div>
@endisset

{{-- Main content --}}
<main style="flex:1;padding:2rem;">
    {{ $slot }}
</main>

</body>
</html>
