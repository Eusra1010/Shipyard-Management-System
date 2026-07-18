<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In — NavalForge</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
            min-height: 100vh;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 14px;
            width: 100%;
            max-width: 400px;
            padding: 40px 36px;
        }
        .logo-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
        }
        .logo-icon {
            width: 40px; height: 40px;
            background: #2563eb;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .logo-name { font-size: 18px; font-weight: 800; color: #f8fafc; letter-spacing: -.3px; }
        .logo-sub  { font-size: 11px; color: #64748b; margin-top: 1px; }
        h1 { font-size: 20px; font-weight: 700; color: #f1f5f9; margin-bottom: 4px; }
        .subtitle { font-size: 13px; color: #64748b; margin-bottom: 28px; }
        .alert {
            padding: 11px 14px; border-radius: 8px; font-size: 13px;
            margin-bottom: 18px; display: flex; align-items: center; gap: 8px;
        }
        .alert-error { background: #450a0a; border: 1px solid #7f1d1d; color: #fca5a5; }
        .alert-info  { background: #0c1a3a; border: 1px solid #1e3a6e; color: #93c5fd; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: 11px; font-weight: 700; color: #94a3b8;
                text-transform: uppercase; letter-spacing: .07em; margin-bottom: 7px; }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            color: #475569; font-size: 13px; pointer-events: none;
        }
        input[type="email"], input[type="password"] {
            width: 100%; padding: 10px 12px 10px 36px;
            background: #0f172a; border: 1.5px solid #334155;
            border-radius: 8px; color: #f1f5f9; font-size: 14px;
            font-family: inherit; outline: none; transition: border-color .15s;
        }
        input:focus { border-color: #2563eb; }
        .field-error { font-size: 11px; color: #f87171; margin-top: 5px; }
        .remember-row {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 22px;
        }
        .remember-label {
            display: flex; align-items: center; gap: 7px;
            font-size: 13px; color: #94a3b8; cursor: pointer;
            text-transform: none; letter-spacing: 0; font-weight: 400;
        }
        .remember-label input[type="checkbox"] {
            width: 15px; height: 15px; accent-color: #2563eb;
            padding: 0; border: none; background: none;
        }
        .forgot { font-size: 12px; color: #3b82f6; text-decoration: none; }
        .forgot:hover { color: #60a5fa; }
        .submit-btn {
            width: 100%; padding: 11px;
            background: #2563eb; color: #fff;
            border: none; border-radius: 8px;
            font-size: 14px; font-weight: 700;
            font-family: inherit; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: background .12s;
        }
        .submit-btn:hover { background: #1d4ed8; }
        .back-link {
            display: block; text-align: center; margin-top: 20px;
            font-size: 12px; color: #475569; text-decoration: none;
        }
        .back-link:hover { color: #94a3b8; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo-row">
            <div class="logo-icon">
                <i class="fas fa-anchor" style="font-size:16px;color:#fff;"></i>
            </div>
            <div>
                <div class="logo-name">NavalForge</div>
                <div class="logo-sub">Shipyard Management System</div>
            </div>
        </div>

        <h1>Welcome back</h1>
        <p class="subtitle">Sign in to your account to continue.</p>

        @if(session('status'))
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> {{ session('status') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email address</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}" required autofocus autocomplete="username">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password"
                           required autocomplete="current-password">
                </div>
            </div>

            <div class="remember-row">
                <label class="remember-label">
                    <input type="checkbox" name="remember" id="remember_me">
                    Remember me
                </label>
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot">Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="submit-btn">
                <i class="fas fa-sign-in-alt"></i> Sign in
            </button>
        </form>

        <a href="{{ route('home') }}" class="back-link">
            <i class="fas fa-arrow-left" style="font-size:10px;margin-right:4px;"></i> Back to website
        </a>
    </div>
</body>
</html>
