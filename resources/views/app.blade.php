<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NavalForge — @yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; color: #1e293b; background: #fff; }
        a { text-decoration: none; color: inherit; }
    </style>
</head>
<body>

{{-- ── Top identity bar ── --}}
<div style="background:#0a1628;padding:10px 2.5rem;display:flex;align-items:center;gap:16px;border-bottom:1px solid #1e3a5f;">
    {{-- Logo placeholder — replace src with your real logo file in public/images/logo.png --}}
    <div style="width:48px;height:48px;border-radius:10px;background:linear-gradient(135deg,#1d4ed8,#0ea5e9);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <i class="fas fa-ship" style="font-size:22px;color:#fff;"></i>
    </div>
    <div>
        <div style="font-size:18px;font-weight:800;color:#f8fafc;letter-spacing:-.3px;">NavalForge</div>
        <div style="font-size:11px;color:#64748b;letter-spacing:.04em;">Professional Ship Repair & Maintenance · Chittagong, Bangladesh</div>
    </div>
    <div style="margin-left:auto;display:flex;gap:8px;">
        @guest
            <a href="{{ route('login') }}" style="font-size:12px;font-weight:600;padding:7px 18px;border:1.5px solid #334155;border-radius:6px;color:#94a3b8;">Sign in</a>
            <a href="{{ route('register') }}" style="font-size:12px;font-weight:600;padding:7px 18px;background:#1d4ed8;color:#fff;border-radius:6px;">Register</a>
        @endguest
        @auth
            <a href="{{ route('dashboard') }}" style="font-size:12px;font-weight:600;padding:7px 18px;background:#1d4ed8;color:#fff;border-radius:6px;">
                <i class="fas fa-tachometer-alt" style="margin-right:5px;"></i>Dashboard
            </a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" style="font-size:12px;font-weight:600;padding:7px 18px;border:1.5px solid #334155;border-radius:6px;background:transparent;cursor:pointer;color:#94a3b8;">Logout</button>
            </form>
        @endauth
    </div>
</div>

{{-- ── Navbar ── --}}
<nav style="background:#0f172a;border-bottom:2px solid #1d4ed8;position:sticky;top:0;z-index:100;">
    <div style="padding:0 2.5rem;display:flex;align-items:center;height:50px;gap:4px;">
        <a href="{{ route('home') }}"
           style="font-size:13px;font-weight:600;padding:6px 16px;border-radius:4px;color:{{ request()->routeIs('home') ? '#fff' : '#94a3b8' }};background:{{ request()->routeIs('home') ? '#1d4ed8' : 'transparent' }};">
            <i class="fas fa-home" style="margin-right:6px;font-size:11px;"></i>Home
        </a>
        <a href="#about"
           style="font-size:13px;font-weight:600;padding:6px 16px;border-radius:4px;color:#94a3b8;">
            <i class="fas fa-info-circle" style="margin-right:6px;font-size:11px;"></i>About Us
        </a>
        <a href="{{ route('projects') }}"
           style="font-size:13px;font-weight:600;padding:6px 16px;border-radius:4px;color:{{ request()->routeIs('projects') ? '#fff' : '#94a3b8' }};background:{{ request()->routeIs('projects') ? '#1d4ed8' : 'transparent' }};">
            <i class="fas fa-folder-open" style="margin-right:6px;font-size:11px;"></i>Projects
        </a>
        <a href="#news"
           style="font-size:13px;font-weight:600;padding:6px 16px;border-radius:4px;color:#94a3b8;">
            <i class="fas fa-newspaper" style="margin-right:6px;font-size:11px;"></i>News
        </a>
        <a href="#contact"
           style="font-size:13px;font-weight:600;padding:6px 16px;border-radius:4px;color:#94a3b8;">
            <i class="fas fa-envelope" style="margin-right:6px;font-size:11px;"></i>Contact
        </a>
    </div>
</nav>

@yield('content')

</body>
</html>
