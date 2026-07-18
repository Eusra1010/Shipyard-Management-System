@php $isAdmin = Auth::user()->role === 'admin'; @endphp
@extends($isAdmin ? 'layouts.admin' : 'layouts.supervisor')
@section('title', 'My Profile')
@section('page-title', 'My Profile')
@section('breadcrumb', 'NavalForge / Profile')

@push('styles')
<style>
.panel { background:#fff; border-radius:10px; border:1px solid #d4dae8; margin-bottom:16px; }
.panel-head {
    padding:16px 20px; border-bottom:1px solid #edf0f7;
    font-size:13px; font-weight:700; color:#0f172a;
    display:flex; align-items:center; gap:8px;
}
.form-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.form-group { display:flex; flex-direction:column; gap:5px; }
.form-label { font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.07em; }
.form-input {
    font-size:13px; padding:9px 12px; border:1.5px solid #e2e8f0;
    border-radius:8px; background:#fff; color:#0f172a; outline:none;
    font-family:inherit; transition:border-color .15s;
}
.form-input:focus { border-color:#2563eb; }
.form-error { font-size:11px; color:#dc2626; margin-top:3px; }
.save-btn {
    font-size:13px; font-weight:600; padding:9px 24px;
    background:#2563eb; color:#fff; border:none; border-radius:8px;
    cursor:pointer; transition:background .12s; font-family:inherit;
}
.save-btn:hover { background:#1d4ed8; }
</style>
@endpush

@section('content')

@if(session('status') === 'profile-updated')
<div style="margin-bottom:14px;padding:11px 16px;background:#f0fdf4;border:1px solid #bbf7d0;
            border-radius:8px;font-size:13px;color:#166534;display:flex;align-items:center;gap:8px;">
    <i class="fas fa-check-circle"></i> Profile updated successfully.
</div>
@endif

@if(session('status') === 'password-updated')
<div style="margin-bottom:14px;padding:11px 16px;background:#f0fdf4;border:1px solid #bbf7d0;
            border-radius:8px;font-size:13px;color:#166534;display:flex;align-items:center;gap:8px;">
    <i class="fas fa-check-circle"></i> Password updated successfully.
</div>
@endif

<div style="max-width:640px;">

    {{-- Update profile info --}}
    <div class="panel">
        <div class="panel-head">
            <i class="fas fa-user" style="color:#2563eb;font-size:12px;"></i>
            Profile Information
        </div>
        <div style="padding:20px;">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf @method('PATCH')
                <div class="form-row" style="margin-bottom:14px;">
                    <div class="form-group">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-input"
                               value="{{ old('name', Auth::user()->name) }}" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input"
                               value="{{ old('email', Auth::user()->email) }}" required>
                        @error('email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <button type="submit" class="save-btn">Save changes</button>
            </form>
        </div>
    </div>

    {{-- Update password --}}
    <div class="panel">
        <div class="panel-head">
            <i class="fas fa-lock" style="color:#6366f1;font-size:12px;"></i>
            Update Password
        </div>
        <div style="padding:20px;">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf @method('PUT')
                <div style="display:flex;flex-direction:column;gap:14px;margin-bottom:16px;">
                    <div class="form-group">
                        <label class="form-label">Current password</label>
                        <input type="password" name="current_password" class="form-input" autocomplete="current-password">
                        @error('current_password')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">New password</label>
                            <input type="password" name="password" class="form-input" autocomplete="new-password">
                            @error('password')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm password</label>
                            <input type="password" name="password_confirmation" class="form-input" autocomplete="new-password">
                        </div>
                    </div>
                </div>
                <button type="submit" class="save-btn">Update password</button>
            </form>
        </div>
    </div>

</div>

@endsection
