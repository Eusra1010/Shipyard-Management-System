@extends('layouts.admin')

@section('title', 'Manage Users')
@section('page-title', 'Manage Users')
@section('breadcrumb', 'NavalForge / Admin / Users')

@push('styles')
<style>
.panel { background:#fff; border-radius:10px; border:1px solid #d4dae8; }
.panel-header {
    padding:16px 20px; border-bottom:1px solid #edf0f7;
    display:flex; align-items:center; justify-content:space-between;
}
.panel-header-title { font-size:13px; font-weight:700; color:#0f172a; display:flex; align-items:center; gap:8px; }

.usr-table { width:100%; border-collapse:collapse; }
.usr-table th {
    font-size:10px; font-weight:700; color:#94a3b8;
    text-transform:uppercase; letter-spacing:.07em;
    padding:10px 18px; text-align:left;
    border-bottom:2px solid #edf0f7;
}
.usr-table td { font-size:13px; padding:13px 18px; border-bottom:1px solid #edf0f7; vertical-align:middle; }
.usr-table tr:last-child td { border-bottom:none; }
.usr-table tbody tr:hover td { background:#f8fafc; }

.pill {
    display:inline-block; font-size:10px; font-weight:700;
    padding:3px 9px; border-radius:999px;
    text-transform:uppercase; letter-spacing:.05em; white-space:nowrap;
}

.usr-select {
    font-size:12px; padding:5px 10px; border:1.5px solid #e2e8f0;
    border-radius:7px; background:#fff; color:#334155;
    cursor:pointer; outline:none;
}
.usr-select:focus { border-color:#2563eb; }

.usr-btn {
    font-size:12px; font-weight:600; padding:5px 14px;
    border:1.5px solid #2563eb; border-radius:7px;
    background:#2563eb; color:#fff; cursor:pointer;
    transition:background .12s;
}
.usr-btn:hover { background:#1d4ed8; }

.usr-avatar {
    width:30px; height:30px; border-radius:50%;
    background:#1e3a5f; color:#93c5fd;
    font-size:11px; font-weight:700;
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0;
}
.modal-overlay {
    display:none; position:fixed; inset:0;
    background:rgba(0,0,0,.45); z-index:999;
    align-items:center; justify-content:center;
}
.modal-overlay.active { display:flex; }
.modal-box {
    background:#fff; border-radius:12px;
    padding:28px 30px; width:100%; max-width:440px;
    box-shadow:0 20px 60px rgba(0,0,0,.25);
}
.modal-title { font-size:16px; font-weight:700; color:#0f172a; margin-bottom:20px;
               display:flex; align-items:center; gap:8px; }
.modal-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px; }
.modal-group { display:flex; flex-direction:column; gap:5px; }
.modal-label { font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.06em; }
.modal-input {
    font-size:13px; padding:8px 11px; border:1.5px solid #e2e8f0;
    border-radius:7px; color:#0f172a; outline:none; font-family:inherit;
}
.modal-input:focus { border-color:#2563eb; }
.modal-footer { display:flex; gap:8px; justify-content:flex-end; margin-top:20px; }
.modal-cancel {
    font-size:13px; font-weight:600; padding:8px 18px;
    border:1.5px solid #e2e8f0; border-radius:7px;
    background:#fff; color:#64748b; cursor:pointer; font-family:inherit;
}
.modal-save {
    font-size:13px; font-weight:600; padding:8px 20px;
    background:#2563eb; color:#fff; border:none; border-radius:7px;
    cursor:pointer; font-family:inherit;
}
</style>
@endpush

@section('content')

@if(session('status'))
<div style="margin-bottom:14px;padding:11px 16px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;font-size:13px;color:#166534;display:flex;align-items:center;gap:8px;">
    <i class="fas fa-check-circle"></i> {{ session('status') }}
</div>
@endif

<div class="panel">
    <div class="panel-header">
        <div class="panel-header-title">
            <i class="fas fa-users-cog" style="color:#6366f1;font-size:12px;"></i>
            All Users
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
            <span style="font-size:11px;color:#94a3b8;">{{ count($users) }} accounts</span>
            <button onclick="document.getElementById('createUserModal').classList.add('active')"
                    class="usr-btn" style="display:flex;align-items:center;gap:6px;">
                <i class="fas fa-plus" style="font-size:10px;"></i> New user
            </button>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table class="usr-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Team</th>
                    <th>Linked Worker</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                @php
                    $words    = preg_split('/\s+/', trim($u->name));
                    $initials = strtoupper(implode('', array_map(fn($w) => $w[0] ?? '', $words)));
                    $initials = substr($initials, 0, 2);
                    $linked   = collect($workers)->firstWhere('worker_id', $u->worker_id);
                @endphp
                <tr>
                    {{-- Avatar + name ── --}}
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="usr-avatar">{{ $initials }}</div>
                            <div>
                                <div style="font-weight:600;color:#0f172a;">{{ $u->name }}</div>
                                <div style="font-size:11px;color:#94a3b8;">ID #{{ $u->id }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Email ── --}}
                    <td style="color:#475569;">{{ $u->email }}</td>

                    {{-- Role + change form ── --}}
                    <td>
                        <form method="POST"
                              action="{{ route('admin.users.update-role', $u->id) }}"
                              style="display:flex;align-items:center;gap:6px;">
                            @csrf @method('PATCH')
                            <select name="role" class="usr-select">
                                <option value="supervisor" @selected($u->role === 'supervisor')>Supervisor</option>
                                <option value="admin"      @selected($u->role === 'admin')>Admin</option>
                            </select>
                            <button type="submit" class="usr-btn">Save</button>
                        </form>
                    </td>

                    {{-- Team field ── --}}
                    <td>
                        <form method="POST"
                              action="{{ route('admin.users.link-team', $u->id) }}"
                              style="display:flex;align-items:center;gap:6px;">
                            @csrf @method('PATCH')
                            <input type="text" name="team"
                                   value="{{ $u->team ?? '' }}"
                                   placeholder="e.g. Welding"
                                   class="usr-select" style="width:110px;">
                            <button type="submit" class="usr-btn"
                                    style="background:#6366f1;border-color:#6366f1;">Set</button>
                        </form>
                    </td>

                    {{-- Linked worker + link form ── --}}
                    <td>
                        <form method="POST"
                              action="{{ route('admin.users.link-worker', $u->id) }}"
                              style="display:flex;align-items:center;gap:6px;">
                            @csrf @method('PATCH')
                            <select name="worker_id" class="usr-select" style="min-width:140px;">
                                <option value="">— None —</option>
                                @foreach($workers as $w)
                                <option value="{{ $w->worker_id }}" @selected($u->worker_id == $w->worker_id)>
                                    {{ $w->name }} ({{ $w->role }})
                                </option>
                                @endforeach
                            </select>
                            <button type="submit" class="usr-btn" style="background:#0d9488;border-color:#0d9488;">Link</button>
                        </form>
                        @if($linked)
                        <div style="margin-top:5px;font-size:11px;color:#22c55e;display:flex;align-items:center;gap:4px;">
                            <i class="fas fa-link" style="font-size:9px;"></i>
                            {{ $linked->name }} · {{ $linked->role }}
                        </div>
                        @endif
                    </td>

                    {{-- Role badge ── --}}
                    <td>
                        @php
                            $rBg = $u->role === 'admin' ? '#eff6ff' : '#f5f3ff';
                            $rFg = $u->role === 'admin' ? '#1e40af' : '#5b21b6';
                        @endphp
                        <span class="pill" style="background:{{ $rBg }};color:{{ $rFg }};">
                            {{ $u->role }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Create user modal --}}
<div class="modal-overlay" id="createUserModal">
    <div class="modal-box">
        <div class="modal-title">
            <i class="fas fa-user-plus" style="color:#2563eb;font-size:14px;"></i>
            Create new account
        </div>

        @if($errors->any())
        <div style="padding:10px 13px;background:#fef2f2;border:1px solid #fecaca;border-radius:7px;
                    font-size:12px;color:#991b1b;margin-bottom:14px;">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="modal-row">
                <div class="modal-group" style="grid-column:1/-1;">
                    <label class="modal-label">Full name</label>
                    <input type="text" name="name" class="modal-input"
                           value="{{ old('name') }}" placeholder="e.g. Ahmad Karim" required>
                </div>
            </div>
            <div class="modal-group" style="margin-bottom:12px;">
                <label class="modal-label">Email address</label>
                <input type="email" name="email" class="modal-input"
                       value="{{ old('email') }}" placeholder="ahmad@navalforge.com" required>
            </div>
            <div class="modal-row">
                <div class="modal-group">
                    <label class="modal-label">Password</label>
                    <input type="password" name="password" class="modal-input"
                           placeholder="Min. 8 characters" required>
                </div>
                <div class="modal-group">
                    <label class="modal-label">Role</label>
                    <select name="role" class="modal-input">
                        <option value="supervisor" @selected(old('role') === 'supervisor')>Supervisor</option>
                        <option value="admin"      @selected(old('role') === 'admin')>Admin</option>
                    </select>
                </div>
            </div>
            <div class="modal-group">
                <label class="modal-label">Team <span style="font-weight:400;text-transform:none;color:#94a3b8;">(optional)</span></label>
                <input type="text" name="team" class="modal-input"
                       value="{{ old('team') }}" placeholder="e.g. Welding, Painting">
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-cancel"
                        onclick="document.getElementById('createUserModal').classList.remove('active')">
                    Cancel
                </button>
                <button type="submit" class="modal-save">Create account</button>
            </div>
        </form>
    </div>
</div>

@if($errors->any())
<script>
    document.getElementById('createUserModal').classList.add('active');
</script>
@endif

@endsection
