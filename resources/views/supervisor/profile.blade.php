@extends('layouts.supervisor')

@section('title', 'My Profile')
@section('page-title', 'My Profile')
@section('breadcrumb', 'NavalForge / ' . ($team ?: 'Supervisor') . ' Team / Profile')

@push('styles')
<style>
.panel { background:#fff; border-radius:10px; border:1px solid #d4dae8; }
.panel-head {
    padding:16px 20px; border-bottom:1px solid #edf0f7;
    display:flex; align-items:center; gap:8px;
}
.panel-head-title { font-size:13px; font-weight:700; color:#0f172a; display:flex; align-items:center; gap:8px; flex:1; }

.prof-field { display:flex; justify-content:space-between; align-items:center; padding:10px 20px; border-bottom:1px solid #edf0f7; }
.prof-field:last-child { border-bottom:none; }
.prof-key { font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.07em; }
.prof-val { font-size:13px; color:#0f172a; font-weight:500; }

.hist-table { width:100%; border-collapse:collapse; }
.hist-table th {
    font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase;
    letter-spacing:.07em; padding:0 20px 8px; text-align:left;
    border-bottom:2px solid #edf0f7;
}
.hist-table td { font-size:13px; padding:11px 20px; border-bottom:1px solid #edf0f7; vertical-align:middle; }
.hist-table tr:last-child td { border-bottom:none; }
.hist-table tbody tr:hover td { background:#f8fafc; }

.pill {
    display:inline-block; font-size:10px; font-weight:700;
    padding:3px 9px; border-radius:999px;
    text-transform:uppercase; letter-spacing:.05em;
}
</style>
@endpush

@section('content')

@php
$pillMap = [
    'in_progress' => ['#fffbeb','#92400e'],
    'pending'     => ['#eff6ff','#1e40af'],
    'done'        => ['#dcfce7','#166534'],
];
$words    = preg_split('/\s+/', trim($user->name));
$initials = strtoupper(implode('', array_map(fn($w) => $w[0] ?? '', $words)));
$initials = substr($initials, 0, 2);
@endphp

<div style="display:grid;grid-template-columns:280px 1fr;gap:16px;align-items:start;">

    {{-- LEFT: Profile card ── --}}
    <div style="display:flex;flex-direction:column;gap:14px;">

        <div class="panel">
            {{-- Avatar header ── --}}
            <div style="padding:24px 20px 16px;text-align:center;border-bottom:1px solid #edf0f7;">
                <div style="width:60px;height:60px;border-radius:50%;background:#1e3a5f;color:#93c5fd;
                            font-size:22px;font-weight:800;display:flex;align-items:center;
                            justify-content:center;margin:0 auto 12px;">
                    {{ $initials }}
                </div>
                <div style="font-size:16px;font-weight:800;color:#0f172a;">{{ $user->name }}</div>
                <div style="font-size:12px;color:#64748b;margin-top:3px;">
                    Supervisor · {{ $team }} Team
                </div>
            </div>

            <div class="prof-field">
                <span class="prof-key">Email</span>
                <span class="prof-val" style="font-size:12px;">{{ $user->email }}</span>
            </div>
            <div class="prof-field">
                <span class="prof-key">Team</span>
                <span class="prof-val">{{ $team ?: '—' }}</span>
            </div>
            <div class="prof-field">
                <span class="prof-key">Role</span>
                <span class="pill" style="background:#eff6ff;color:#1e40af;">Supervisor</span>
            </div>
            @if($worker)
            <div class="prof-field">
                <span class="prof-key">Trade</span>
                <span class="prof-val">{{ $worker->role }}</span>
            </div>
            <div class="prof-field">
                <span class="prof-key">Phone</span>
                <span class="prof-val">{{ $worker->phone ?? '—' }}</span>
            </div>
            @endif
        </div>

        {{-- Stats ── --}}
        <div class="panel" style="padding:16px 20px;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <div style="text-align:center;padding:14px 8px;background:#f8fafc;border-radius:8px;">
                    <div style="font-size:28px;font-weight:800;color:#0f172a;line-height:1;">
                        {{ (int)($stats->completed ?? 0) }}
                    </div>
                    <div style="font-size:10px;color:#94a3b8;margin-top:4px;font-weight:700;
                                text-transform:uppercase;letter-spacing:.06em;">Done</div>
                </div>
                <div style="text-align:center;padding:14px 8px;background:#eff6ff;border-radius:8px;">
                    <div style="font-size:28px;font-weight:800;color:#1d4ed8;line-height:1;">
                        {{ (int)($stats->active ?? 0) }}
                    </div>
                    <div style="font-size:10px;color:#93c5fd;margin-top:4px;font-weight:700;
                                text-transform:uppercase;letter-spacing:.06em;">Active</div>
                </div>
            </div>
        </div>

    </div>

    {{-- RIGHT: Work history ── --}}
    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-title">
                <i class="fas fa-history" style="color:#6366f1;font-size:12px;"></i>
                My Work History
            </div>
            <span style="font-size:11px;color:#94a3b8;">{{ count($history) }} orders</span>
        </div>

        @if(!$user->worker_id)
        <div style="padding:40px;text-align:center;">
            <i class="fas fa-link-slash" style="font-size:26px;color:#94a3b8;display:block;margin-bottom:10px;"></i>
            <div style="font-size:13px;color:#94a3b8;">No worker profile linked. Contact an admin.</div>
        </div>
        @elseif(count($history) === 0)
        <div style="padding:40px;text-align:center;">
            <div style="font-size:13px;color:#94a3b8;">No work history yet.</div>
        </div>
        @else
        <div style="overflow-x:auto;">
            <table class="hist-table">
                <thead>
                    <tr>
                        <th>Ship</th>
                        <th>Job</th>
                        <th>Started</th>
                        <th>Due</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $h)
                    @php $c = $pillMap[$h->status] ?? ['#f1f5f9','#475569']; @endphp
                    <tr>
                        <td style="font-weight:600;color:#0f172a;">{{ $h->ship_name }}</td>
                        <td style="color:#475569;">{{ $h->title }}</td>
                        <td style="color:#64748b;font-size:12px;">{{ $h->start_date }}</td>
                        <td style="color:#64748b;font-size:12px;">{{ $h->end_date ?? '—' }}</td>
                        <td>
                            <span class="pill" style="background:{{ $c[0] }};color:{{ $c[1] }};">
                                {{ str_replace('_',' ',$h->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>

@endsection
