@extends('layouts.supervisor')

@section('title', 'My Jobs')
@section('page-title', 'My Jobs')
@section('breadcrumb', 'NavalForge / ' . ($team ?: 'Supervisor') . ' Team / My Jobs')

@push('styles')
<style>
/* ── Stat cards ── */
.sc { border-radius:10px; padding:18px 20px 14px; position:relative; overflow:hidden; }
.sc-label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; margin-bottom:6px; }
.sc-number { font-size:34px; font-weight:800; line-height:1; margin-bottom:4px; }
.sc-sub { font-size:11px; }
.sc-icon { position:absolute; right:14px; bottom:10px; font-size:40px; opacity:.14; pointer-events:none; }

/* ── Panels ── */
.panel { background:#fff; border-radius:10px; border:1px solid #d4dae8; }
.panel-head {
    padding:16px 20px; border-bottom:1px solid #edf0f7;
    display:flex; align-items:center; gap:8px;
}
.panel-head-title { font-size:13px; font-weight:700; color:#0f172a; display:flex; align-items:center; gap:8px; flex:1; }

/* ── Job row ── */
.job-row {
    display:flex; align-items:center; gap:14px; padding:15px 20px;
    border-bottom:1px solid #edf0f7;
    transition:background .1s;
}
.job-row:last-child { border-bottom:none; }
.job-row:hover { background:#f8fafc; }
.job-stripe {
    width:3px; border-radius:2px; flex-shrink:0;
    align-self:stretch; min-height:40px;
}
.job-main { flex:1; min-width:0; }
.job-ship { font-size:14px; font-weight:700; color:#0f172a; line-height:1.2; }
.job-title { font-size:12.5px; color:#475569; margin-top:2px; }
.job-meta { display:flex; align-items:center; gap:10px; margin-top:6px; flex-wrap:wrap; }
.job-meta-chip {
    display:inline-flex; align-items:center; gap:4px;
    font-size:11px; color:#64748b; background:#f1f5f9;
    border:1px solid #e2e8f0; border-radius:5px; padding:2px 8px;
}
.job-meta-chip i { font-size:9px; color:#94a3b8; }
.job-right { display:flex; align-items:center; gap:8px; flex-shrink:0; }

/* ── Status pills ── */
.pill {
    display:inline-block; font-size:10px; font-weight:700;
    padding:3px 9px; border-radius:999px;
    text-transform:uppercase; letter-spacing:.05em; white-space:nowrap;
}

/* ── Inline status form ── */
.status-sel {
    font-size:12px; padding:5px 8px; border:1.5px solid #e2e8f0;
    border-radius:7px; background:#fff; color:#334155; cursor:pointer;
    outline:none;
}
.status-sel:focus { border-color:#2563eb; }
.upd-btn {
    font-size:11px; font-weight:600; padding:5px 12px;
    border:1.5px solid #2563eb; border-radius:7px;
    background:#2563eb; color:#fff; cursor:pointer;
    transition:background .12s; white-space:nowrap;
}
.upd-btn:hover { background:#1d4ed8; }
.view-link {
    font-size:11px; font-weight:600; color:#64748b;
    padding:5px 10px; border:1.5px solid #e2e8f0; border-radius:7px;
    background:#f8fafc; white-space:nowrap; transition:border-color .12s;
    display:inline-flex; align-items:center; gap:4px;
}
.view-link:hover { border-color:#94a3b8; color:#0f172a; }

/* ── Crew chips ── */
.crew-chip {
    display:inline-flex; align-items:center; gap:7px;
    border-radius:999px; padding:5px 14px 5px 7px;
    font-size:12.5px; font-weight:500; border:1.5px solid transparent;
}
.crew-avatar {
    width:24px; height:24px; border-radius:50%;
    font-size:9px; font-weight:800;
    display:flex; align-items:center; justify-content:center; flex-shrink:0;
}
</style>
@endpush

@section('content')

@php
$pillMap = [
    'in_progress' => ['bg'=>'#fffbeb','fg'=>'#92400e','stripe'=>'#f59e0b'],
    'pending'     => ['bg'=>'#eff6ff','fg'=>'#1e40af','stripe'=>'#3b82f6'],
    'done'        => ['bg'=>'#dcfce7','fg'=>'#166534','stripe'=>'#22c55e'],
    'overdue'     => ['bg'=>'#fff1f2','fg'=>'#be123c','stripe'=>'#f43f5e'],
];
@endphp

@if(session('success'))
<div style="margin-bottom:16px;padding:11px 16px;background:#f0fdf4;border:1px solid #bbf7d0;
            border-radius:8px;font-size:13px;color:#166534;display:flex;align-items:center;gap:8px;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

{{-- ══ Greeting ══ --}}
<div style="margin-bottom:20px;">
    <div style="font-size:12px;color:#94a3b8;font-weight:500;margin-bottom:3px;">Welcome back</div>
    <div style="display:flex;align-items:baseline;gap:12px;flex-wrap:wrap;">
        <h1 style="font-size:22px;font-weight:800;color:#0f172a;letter-spacing:-.02em;">
            {{ $user->name }}
        </h1>
        <span style="font-size:13px;color:#64748b;font-weight:500;">
            Supervisor · {{ $team }} Team
        </span>
    </div>
</div>

{{-- ══ Stat cards ══ --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:20px;">

    <div class="sc" style="background:#1d4ed8;">
        <div class="sc-label" style="color:rgba(255,255,255,.65);">Assigned Jobs</div>
        <div class="sc-number" style="color:#fff;">{{ $assignedJobs }}</div>
        <div class="sc-sub" style="color:rgba(255,255,255,.55);">Open &amp; in progress</div>
        <i class="fas fa-clipboard-list sc-icon" style="color:#fff;"></i>
    </div>

    <div class="sc" style="background:#0d9488;">
        <div class="sc-label" style="color:rgba(255,255,255,.65);">Crew on Duty</div>
        <div class="sc-number" style="color:#fff;">
            {{ $crewOnDuty }}<span style="font-size:16px;font-weight:500;color:rgba(255,255,255,.5);"> / {{ $totalCrew }}</span>
        </div>
        <div class="sc-sub" style="color:rgba(255,255,255,.55);">Currently busy</div>
        <i class="fas fa-hard-hat sc-icon" style="color:#fff;"></i>
    </div>

    <div class="sc" style="background:{{ $dueToday > 0 ? '#b45309' : '#166534' }};">
        <div class="sc-label" style="color:rgba(255,255,255,.65);">Due Today</div>
        <div class="sc-number" style="color:#fff;">{{ $dueToday }}</div>
        <div class="sc-sub" style="color:rgba(255,255,255,.55);">
            {{ $dueToday > 0 ? 'Needs attention' : 'Nothing urgent' }}
        </div>
        <i class="fas fa-{{ $dueToday > 0 ? 'exclamation-triangle' : 'check-circle' }} sc-icon" style="color:#fff;"></i>
    </div>

</div>

{{-- ══ Body — two column ══ --}}
<div style="display:grid;grid-template-columns:1fr 236px;gap:16px;align-items:start;">

    {{-- LEFT: Job list ── --}}
    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-title">
                <i class="fas fa-wrench" style="color:#2563eb;font-size:12px;"></i>
                My Assigned Jobs
            </div>
            <span style="font-size:11px;color:#94a3b8;">{{ count($jobs) }} total</span>
        </div>

        @if(count($jobs) === 0)
        <div style="padding:40px 20px;text-align:center;">
            <i class="fas fa-check-circle" style="font-size:28px;color:#22c55e;display:block;margin-bottom:10px;"></i>
            <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:4px;">All clear</div>
            <div style="font-size:12px;color:#94a3b8;">No jobs assigned to your team yet.</div>
        </div>
        @else
        @foreach($jobs as $j)
        @php
            $isOverdue = (bool)$j->overdue;
            $key    = $isOverdue && $j->status !== 'done' ? 'overdue' : ($j->status ?? 'pending');
            $colors = $pillMap[$key] ?? $pillMap['pending'];
            $label  = $isOverdue && $j->status !== 'done'
                        ? 'Overdue'
                        : ucwords(str_replace('_', ' ', $j->status));
        @endphp
        <div class="job-row">
            {{-- Status stripe ── --}}
            <div class="job-stripe" style="background:{{ $colors['stripe'] }};"></div>

            {{-- Main info ── --}}
            <div class="job-main">
                <div class="job-ship">{{ $j->ship_name }}</div>
                <div class="job-title">{{ $j->title }}</div>
                <div class="job-meta">
                    <span class="job-meta-chip">
                        <i class="fas fa-water"></i> {{ $j->berth_name }}
                    </span>
                    <span class="job-meta-chip">
                        <i class="fas fa-calendar-alt"></i> {{ $j->start_date }}
                    </span>
                    @if($j->priority && $j->priority !== 'normal')
                    <span class="job-meta-chip" style="color:{{ $j->priority === 'high' ? '#be123c' : '#64748b' }};">
                        <i class="fas fa-flag"></i> {{ ucfirst($j->priority) }}
                    </span>
                    @endif
                </div>
            </div>

            {{-- Right: status + actions ── --}}
            <div class="job-right">
                <span class="pill" style="background:{{ $colors['bg'] }};color:{{ $colors['fg'] }};">
                    {{ $label }}
                </span>

                @if($j->status !== 'done')
                <form method="POST" action="{{ route('work-orders.status', $j->order_id) }}"
                      style="display:flex;align-items:center;gap:5px;">
                    @csrf @method('PATCH')
                    <input type="hidden" name="from" value="supervisor">
                    <select name="status" class="status-sel">
                        <option value="pending"     @selected($j->status === 'pending')>Pending</option>
                        <option value="in_progress" @selected($j->status === 'in_progress')>In Progress</option>
                        <option value="done">Done</option>
                    </select>
                    <button type="submit" class="upd-btn">Update</button>
                </form>
                @endif

                <a href="{{ route('work-orders.show', $j->order_id) }}" class="view-link">
                    <i class="fas fa-eye" style="font-size:10px;"></i> View
                </a>
            </div>
        </div>
        @endforeach
        @endif
    </div>

    {{-- RIGHT: My Crew ── --}}
    <div style="display:flex;flex-direction:column;gap:14px;">
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-title">
                    <i class="fas fa-hard-hat" style="color:#6366f1;font-size:12px;"></i>
                    My Crew
                </div>
                <a href="{{ route('supervisor.crew') }}"
                   style="font-size:11px;color:#2563eb;font-weight:500;white-space:nowrap;">
                    View all <i class="fas fa-arrow-right" style="font-size:9px;"></i>
                </a>
            </div>

            <div style="padding:14px 16px;">
                @if(count($crew) === 0)
                <p style="font-size:13px;color:#94a3b8;text-align:center;padding:12px 0;">
                    No crew assigned to your team.
                </p>
                @else
                <div style="display:flex;flex-direction:column;gap:8px;">
                    @foreach($crew as $c)
                    @php
                        $cWords = preg_split('/\s+/', trim($c->name));
                        $cIni   = strtoupper(implode('', array_map(fn($w) => $w[0] ?? '', $cWords)));
                        $cIni   = substr($cIni, 0, 2);
                        $cs     = $c->status ?? 'available';
                        if ($cs === 'busy') {
                            $chipBg='#fffbeb'; $chipBd='#fde68a'; $chipFg='#92400e';
                            $dotClr='#f59e0b'; $avBg='#1e3a5f'; $avFg='#93c5fd';
                        } elseif ($cs === 'on_leave') {
                            $chipBg='#f8fafc'; $chipBd='#e2e8f0'; $chipFg='#64748b';
                            $dotClr='#94a3b8'; $avBg='#334155'; $avFg='#94a3b8';
                        } else {
                            $chipBg='#f0fdf4'; $chipBd='#bbf7d0'; $chipFg='#166534';
                            $dotClr='#22c55e'; $avBg='#14532d'; $avFg='#86efac';
                        }
                    @endphp
                    <div class="crew-chip"
                         style="background:{{ $chipBg }};border-color:{{ $chipBd }};">
                        <div class="crew-avatar"
                             style="background:{{ $avBg }};color:{{ $avFg }};">
                            {{ $cIni }}
                        </div>
                        <div style="min-width:0;">
                            <div style="font-size:12px;font-weight:600;color:#0f172a;
                                        white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
                                        max-width:130px;">{{ $c->name }}</div>
                            <div style="font-size:10px;color:{{ $chipFg }};display:flex;align-items:center;gap:4px;">
                                <span style="width:6px;height:6px;border-radius:50%;
                                             background:{{ $dotClr }};display:inline-block;flex-shrink:0;"></span>
                                {{ ucfirst(str_replace('_',' ',$cs)) }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Quick action ── --}}
        <a href="{{ route('supervisor.crew') }}"
           style="display:flex;align-items:center;justify-content:center;gap:7px;
                  padding:11px;background:#f8fafc;color:#475569;
                  border:1.5px solid #e2e8f0;border-radius:10px;
                  font-size:13px;font-weight:500;">
            <i class="fas fa-hard-hat" style="color:#6366f1;"></i> Full Crew List
        </a>
        <a href="{{ route('supervisor.profile') }}"
           style="display:flex;align-items:center;justify-content:center;gap:7px;
                  padding:11px;background:#f8fafc;color:#475569;
                  border:1.5px solid #e2e8f0;border-radius:10px;
                  font-size:13px;font-weight:500;">
            <i class="fas fa-id-badge" style="color:#f59e0b;"></i> My Profile
        </a>
    </div>

</div>

@endsection
