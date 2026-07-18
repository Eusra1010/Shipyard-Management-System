<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NavalForge — @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

   <nav style="display:flex;align-items:center;justify-content:space-between;padding:0 2.5rem;height:64px;background:#0f172a;border-bottom:1px solid #1e293b;">
    <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
        <i class="fas fa-ship" style="font-size:22px;color:#3b82f6;"></i>
        <span style="font-size:17px;font-weight:700;color:#f8fafc;">NavalForge</span>
    </a>
    <div style="display:flex;align-items:center;gap:2px;">
        <a href="{{ route('home') }}" style="font-size:14px;color:#94a3b8;padding:7px 14px;border-radius:6px;text-decoration:none;transition:all .15s;">Home</a>
        <a href="#about" style="font-size:14px;color:#94a3b8;padding:7px 14px;border-radius:6px;text-decoration:none;">About</a>
        <a href="{{ route('projects') }}" style="font-size:14px;color:#94a3b8;padding:7px 14px;border-radius:6px;text-decoration:none;">Projects</a>
        <a href="#contact" style="font-size:14px;color:#94a3b8;padding:7px 14px;border-radius:6px;text-decoration:none;">Contact</a>
    </div>
    <div style="display:flex;gap:8px;align-items:center;">
        @guest
            <a href="{{ route('login') }}" style="font-size:13px;font-weight:600;padding:8px 20px;border:1.5px solid #334155;border-radius:8px;color:#e2e8f0;text-decoration:none;background:transparent;">Sign in</a>
        @endguest
        @auth
            <a href="{{ route('dashboard') }}" style="font-size:13px;font-weight:600;padding:8px 20px;background:#2563eb;color:#fff;border-radius:8px;text-decoration:none;border:1.5px solid #1d4ed8;">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" style="font-size:13px;font-weight:600;padding:8px 20px;border:1.5px solid #334155;border-radius:8px;background:transparent;cursor:pointer;color:#e2e8f0;">Logout</button>
            </form>
        @endauth
    </div>
</nav>

    @yield('content')

</body>
</html>