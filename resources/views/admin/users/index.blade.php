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
        <span style="font-size:11px;color:#94a3b8;">{{ count($users) }} accounts</span>
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

@endsection
