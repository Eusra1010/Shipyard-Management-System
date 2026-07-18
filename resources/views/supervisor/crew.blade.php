@extends('layouts.supervisor')

@section('title', 'My Crew')
@section('page-title', 'My Crew')
@section('breadcrumb', 'NavalForge / ' . ($team ?: 'Supervisor') . ' Team / My Crew')

@push('styles')
<style>
.panel { background:#fff; border-radius:10px; border:1px solid #d4dae8; }
.panel-head {
    padding:16px 20px; border-bottom:1px solid #edf0f7;
    display:flex; align-items:center; gap:8px;
}
.panel-head-title { font-size:13px; font-weight:700; color:#0f172a; display:flex; align-items:center; gap:8px; flex:1; }

.crew-card {
    display:flex; align-items:center; gap:14px;
    padding:16px 20px; border-bottom:1px solid #edf0f7;
    transition:background .1s;
}
.crew-card:last-child { border-bottom:none; }
.crew-card:hover { background:#f8fafc; }

.crew-avatar {
    width:42px; height:42px; border-radius:50%;
    font-size:15px; font-weight:800;
    display:flex; align-items:center; justify-content:center; flex-shrink:0;
}
.pill {
    display:inline-flex; align-items:center; gap:5px;
    font-size:10px; font-weight:700;
    padding:3px 9px; border-radius:999px;
    text-transform:uppercase; letter-spacing:.05em; white-space:nowrap;
}
.pill-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
</style>
@endpush

@section('content')

<div class="panel">
    <div class="panel-head">
        <div class="panel-head-title">
            <i class="fas fa-hard-hat" style="color:#6366f1;font-size:12px;"></i>
            {{ $team }} Team
        </div>
        <span style="font-size:11px;color:#94a3b8;">{{ count($crew) }} members</span>
    </div>

    @if(count($crew) === 0)
    <div style="padding:40px;text-align:center;">
        <i class="fas fa-users" style="font-size:28px;color:#94a3b8;display:block;margin-bottom:10px;"></i>
        <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:4px;">No crew members</div>
        <div style="font-size:12px;color:#94a3b8;">Ask an admin to assign users to your team.</div>
    </div>
    @else
    @foreach($crew as $c)
    @php
        $words = preg_split('/\s+/', trim($c->name));
        $ini   = strtoupper(implode('', array_map(fn($w) => $w[0] ?? '', $words)));
        $ini   = substr($ini, 0, 2);
        $cs    = $c->status ?? 'available';

        if ($cs === 'busy') {
            $avBg='#1e3a5f'; $avFg='#93c5fd';
            $pillBg='#fffbeb'; $pillFg='#92400e'; $dotClr='#f59e0b';
        } elseif ($cs === 'on_leave') {
            $avBg='#334155'; $avFg='#94a3b8';
            $pillBg='#f8fafc'; $pillFg='#475569'; $dotClr='#94a3b8';
        } else {
            $avBg='#14532d'; $avFg='#86efac';
            $pillBg='#f0fdf4'; $pillFg='#166534'; $dotClr='#22c55e';
        }
    @endphp
    <div class="crew-card">
        <div class="crew-avatar" style="background:{{ $avBg }};color:{{ $avFg }};">
            {{ $ini }}
        </div>

        <div style="flex:1;min-width:0;">
            <div style="font-size:14px;font-weight:700;color:#0f172a;line-height:1.2;">
                {{ $c->name }}
            </div>
            <div style="font-size:12px;color:#64748b;margin-top:2px;">{{ $c->email }}</div>
            @if($c->active_job)
            <div style="margin-top:6px;font-size:11.5px;color:#475569;display:flex;align-items:center;gap:5px;">
                <i class="fas fa-ship" style="font-size:10px;color:#94a3b8;"></i>
                <span>{{ $c->active_job->ship_name }} — {{ $c->active_job->title }}</span>
            </div>
            @endif
        </div>

        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;">
            <span class="pill" style="background:{{ $pillBg }};color:{{ $pillFg }};">
                <span class="pill-dot" style="background:{{ $dotClr }};"></span>
                {{ ucfirst(str_replace('_', ' ', $cs)) }}
            </span>
            @if($c->active_job)
            <span style="font-size:10px;color:#94a3b8;">
                {{ ucwords(str_replace('_',' ',$c->active_job->status)) }}
            </span>
            @else
            <span style="font-size:10px;color:#94a3b8;">Not assigned</span>
            @endif
        </div>
    </div>
    @endforeach
    @endif
</div>

@endsection
