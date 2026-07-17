@extends('layouts.admin')

@section('title', 'Workers')
@section('page-title', 'Workers')
@section('breadcrumb', 'NavalForge / Workers')

@push('styles')
<style>
/* ── Trade badge colors ── */
:root {
    --c-welder-bg:       #fef3c7; --c-welder-fg:       #92400e;
    --c-electrician-bg:  #dbeafe; --c-electrician-fg:  #1e40af;
    --c-painter-bg:      #f3e8ff; --c-painter-fg:      #6b21a8;
    --c-fitter-bg:       #dcfce7; --c-fitter-fg:       #166534;
    --c-other-bg:        #f1f5f9; --c-other-fg:        #475569;
}

/* ── Header ── */
.wk-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}
.wk-title {
    display: flex;
    align-items: baseline;
    gap: 10px;
}
.wk-title h1 {
    font-size: 22px;
    font-weight: 700;
    color: #0f172a;
}
.wk-count-pill {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    background: #f1f5f9;
    padding: 3px 10px;
    border-radius: 999px;
}
.wk-add-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 13px;
    font-weight: 600;
    padding: 9px 20px;
    background: #2563eb;
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-family: inherit;
    transition: background .15s;
}
.wk-add-btn:hover { background: #1d4ed8; }

/* ── Filters ── */
.wk-filters {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.wk-search {
    flex: 1;
    min-width: 200px;
    font-size: 13px;
    padding: 8px 12px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    outline: none;
    color: #0f172a;
    font-family: inherit;
    background: #fff;
}
.wk-search:focus { border-color: #2563eb; }
.wk-select {
    font-size: 13px;
    padding: 8px 32px 8px 12px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    outline: none;
    color: #0f172a;
    font-family: inherit;
    background: #fff;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%2394a3b8'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
}
.wk-select:focus { border-color: #2563eb; }

/* ── Table wrapper ── */
.wk-table-wrap {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}
.wk-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
.wk-table thead tr {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}
.wk-table th {
    padding: 11px 16px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .05em;
    white-space: nowrap;
}

/* ── Section header rows ── */
.wk-section-row td {
    padding: 10px 16px 4px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: #94a3b8;
    background: #f8fafc;
    border-bottom: 1px solid #f1f5f9;
    border-top: 1px solid #e2e8f0;
}
.wk-section-row:first-child td { border-top: none; }

/* ── Data rows ── */
.wk-row {
    border-bottom: 1px solid #f1f5f9;
    transition: background .1s;
}
.wk-row:last-child { border-bottom: none; }
.wk-row:hover { background: #f8fafc; }
.wk-row.dim { opacity: .45; }
.wk-row td { padding: 13px 16px; vertical-align: middle; }

/* ── Avatar ── */
.wk-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
    flex-shrink: 0;
}
.wk-name-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}
.wk-name-link {
    font-weight: 600;
    color: #0f172a;
    cursor: pointer;
    border-bottom: 1px solid transparent;
    transition: border-color .15s, color .15s;
}
.wk-name-link:hover {
    color: #2563eb;
    border-bottom-color: #2563eb;
}

/* ── Trade badge ── */
.wk-badge {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 4px;
    letter-spacing: .02em;
}
.badge-welder      { background: var(--c-welder-bg);      color: var(--c-welder-fg); }
.badge-electrician { background: var(--c-electrician-bg); color: var(--c-electrician-fg); }
.badge-painter     { background: var(--c-painter-bg);     color: var(--c-painter-fg); }
.badge-fitter      { background: var(--c-fitter-bg);      color: var(--c-fitter-fg); }
.badge-other       { background: var(--c-other-bg);       color: var(--c-other-fg); }

/* ── Status dot ── */
.wk-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 500;
    color: #374151;
    white-space: nowrap;
}
.wk-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    flex-shrink: 0;
}
.dot-available { background: #22c55e; }
.dot-busy      { background: #f59e0b; }
.dot-on_leave  { background: #94a3b8; }

/* ── Actions ── */
.wk-actions {
    display: flex;
    align-items: center;
    gap: 14px;
    white-space: nowrap;
}
.wk-action-link {
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    background: none;
    border: none;
    padding: 0;
    font-family: inherit;
}
.wk-action-link.edit   { color: #2563eb; }
.wk-action-link.assign { color: #059669; }
.wk-action-link.del    { color: #dc2626; }
.wk-action-link:hover  { text-decoration: underline; }

/* ── Empty state ── */
.wk-empty {
    padding: 52px;
    text-align: center;
    color: #94a3b8;
    font-size: 13px;
}

/* ── Backdrop ── */
.wk-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,.3);
    backdrop-filter: blur(1.5px);
    z-index: 199;
    opacity: 0;
    pointer-events: none;
    transition: opacity .22s ease;
}
.wk-backdrop.open {
    opacity: 1;
    pointer-events: all;
}

/* ── Slide-over panel ── */
.wk-panel {
    position: fixed;
    top: 0;
    right: -430px;
    width: 400px;
    height: 100vh;
    background: #fff;
    border-left: 1px solid #e2e8f0;
    z-index: 200;
    display: flex;
    flex-direction: column;
    transition: right .24s ease;
    overflow: hidden;
}
.wk-panel.open { right: 0; }

.wk-panel-head {
    padding: 24px 24px 20px;
    border-bottom: 1px solid #f1f5f9;
    flex-shrink: 0;
}
.wk-panel-close {
    position: absolute;
    top: 16px;
    right: 16px;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    border: 1.5px solid #e2e8f0;
    background: none;
    font-size: 16px;
    color: #64748b;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: border-color .15s, color .15s;
    font-family: inherit;
    line-height: 1;
}
.wk-panel-close:hover { border-color: #94a3b8; color: #0f172a; }

.wk-panel-avatar {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    font-size: 18px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
}
.wk-panel-name {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 4px;
    padding-right: 30px;
}
.wk-panel-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px 24px;
}

/* ── Panel stat boxes ── */
.wk-stat-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin: 20px 0;
}
.wk-stat-box {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 14px 16px;
    text-align: center;
}
.wk-stat-num {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
    margin-bottom: 4px;
}
.wk-stat-lbl {
    font-size: 11px;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: .06em;
}

/* ── Panel info rows ── */
.wk-info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 9px 0;
    border-bottom: 1px solid #f8fafc;
    font-size: 13px;
}
.wk-info-label { color: #94a3b8; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: .06em; }
.wk-info-val   { color: #0f172a; font-weight: 500; }

/* ── Job history timeline ── */
.wk-timeline { margin-top: 20px; }
.wk-timeline-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #94a3b8;
    margin-bottom: 12px;
}
.wk-timeline-item {
    display: flex;
    gap: 12px;
    margin-bottom: 10px;
}
.wk-timeline-bar {
    width: 3px;
    border-radius: 2px;
    flex-shrink: 0;
    min-height: 44px;
}
.bar-in_progress { background: #f59e0b; }
.bar-done        { background: #22c55e; }
.bar-pending     { background: #2563eb; }
.wk-timeline-content { flex: 1; }
.wk-timeline-job  { font-size: 13px; font-weight: 600; color: #0f172a; }
.wk-timeline-ship { font-size: 11px; color: #64748b; margin-top: 1px; }
.wk-timeline-date { font-size: 11px; color: #94a3b8; margin-top: 2px; }

/* ── Panel footer ── */
.wk-panel-footer {
    padding: 16px 24px;
    border-top: 1px solid #f1f5f9;
    flex-shrink: 0;
}
.wk-panel-edit-btn {
    width: 100%;
    padding: 10px;
    font-size: 13px;
    font-weight: 600;
    background: #0f172a;
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-family: inherit;
    transition: background .15s;
}
.wk-panel-edit-btn:hover { background: #1e293b; }

/* ── Add / Edit modal ── */
.wk-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.5);
    z-index: 300;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    pointer-events: none;
    transition: opacity .18s ease;
}
.wk-modal-overlay.open {
    opacity: 1;
    pointer-events: all;
}
.wk-modal {
    background: #fff;
    border-radius: 12px;
    width: 100%;
    max-width: 420px;
    padding: 28px;
    position: relative;
    box-shadow: 0 20px 50px rgba(0,0,0,.2);
    transform: translateY(10px);
    transition: transform .18s ease;
}
.wk-modal-overlay.open .wk-modal { transform: none; }
.wk-modal-title { font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 20px; }
.wk-modal-close {
    position: absolute;
    top: 14px;
    right: 14px;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    border: 1.5px solid #e2e8f0;
    background: none;
    font-size: 16px;
    color: #64748b;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: inherit;
}
.wk-field { margin-bottom: 14px; }
.wk-field label { display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 5px; }
.wk-field input,
.wk-field select {
    width: 100%;
    font-size: 13px;
    padding: 9px 12px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    outline: none;
    color: #0f172a;
    font-family: inherit;
    background: #fff;
}
.wk-field input:focus,
.wk-field select:focus { border-color: #2563eb; }
.wk-modal-submit {
    width: 100%;
    margin-top: 6px;
    padding: 10px;
    font-size: 13px;
    font-weight: 600;
    background: #2563eb;
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-family: inherit;
    transition: background .15s;
}
.wk-modal-submit:hover { background: #1d4ed8; }

.wk-hidden { display: none !important; }
</style>
@endpush

@section('content')

@php
$tradeKey = fn($role) => strtolower(str_replace(' ', '', $role ?? 'other'));
$tradeCls = function($role) {
    return match(strtolower(trim($role ?? ''))) {
        'welder'      => 'welder',
        'electrician' => 'electrician',
        'painter'     => 'painter',
        'fitter'      => 'fitter',
        default       => 'other',
    };
};
$avatarStyle = function($role) {
    return match(strtolower(trim($role ?? ''))) {
        'welder'      => 'background:#fef3c7;color:#92400e;',
        'electrician' => 'background:#dbeafe;color:#1e40af;',
        'painter'     => 'background:#f3e8ff;color:#6b21a8;',
        'fitter'      => 'background:#dcfce7;color:#166534;',
        default       => 'background:#f1f5f9;color:#475569;',
    };
};
$initials = fn($name) => substr(strtoupper(implode('', array_map(fn($w) => $w[0] ?? '', preg_split('/\s+/', trim($name))))), 0, 2);

// Group workers by role for section headers
$byRole = [];
foreach ($workers as $w) {
    $byRole[$w->role ?? 'Other'][] = $w;
}
ksort($byRole);
@endphp

{{-- ── Header ── --}}
<div class="wk-header">
    <div class="wk-title">
        <h1>Workers</h1>
        <span class="wk-count-pill">{{ count($workers) }} total</span>
    </div>
    <button class="wk-add-btn" onclick="openAddModal()">
        <i class="fas fa-plus" style="font-size:11px;"></i> Add worker
    </button>
</div>

{{-- ── Flash ── --}}
@if(session('success'))
<div style="margin-bottom:16px;padding:11px 16px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;font-size:13px;color:#166534;display:flex;align-items:center;gap:8px;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

{{-- ── Filters ── --}}
<div class="wk-filters">
    <input id="wk-search" class="wk-search" type="search" placeholder="Search by name…" oninput="applyFilters()">
    <select id="wk-trade" class="wk-select" onchange="applyFilters()">
        <option value="">All trades</option>
        <option>Welder</option>
        <option>Electrician</option>
        <option>Painter</option>
        <option>Fitter</option>
    </select>
    <select id="wk-avail" class="wk-select" onchange="applyFilters()">
        <option value="">All availability</option>
        <option value="available">Available</option>
        <option value="busy">Assigned</option>
    </select>
</div>

{{-- ── Table ── --}}
<div class="wk-table-wrap">
    <table class="wk-table">
        <thead>
            <tr>
                <th style="width:220px;">Name</th>
                <th>Trade</th>
                <th>Availability</th>
                <th>Currently assigned to</th>
                <th>Phone</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="wk-tbody">
        @forelse($byRole as $role => $roleWorkers)
            <tr class="wk-section-row" data-section="{{ strtolower($role) }}">
                <td colspan="6">{{ $role }}</td>
            </tr>
            @foreach($roleWorkers as $w)
            @php
                $cls   = $tradeCls($w->role);
                $style = $avatarStyle($w->role);
                $ini   = $initials($w->name);
                $data  = $jsData[$w->worker_id] ?? [];
                $isDim = $w->status === 'on_leave';
            @endphp
            <tr class="wk-row {{ $isDim ? 'dim' : '' }}"
                data-name="{{ strtolower($w->name) }}"
                data-trade="{{ strtolower($w->role ?? '') }}"
                data-status="{{ $w->status }}"
                data-section="{{ strtolower($role) }}">

                {{-- Name --}}
                <td>
                    <div class="wk-name-cell">
                        <div class="wk-avatar" style="{{ $style }}">{{ $ini }}</div>
                        <span class="wk-name-link" onclick="openPanel({{ $w->worker_id }})">{{ $w->name }}</span>
                    </div>
                </td>

                {{-- Trade badge --}}
                <td>
                    <span class="wk-badge badge-{{ $cls }}">{{ $w->role ?? 'Unknown' }}</span>
                </td>

                {{-- Status --}}
                <td>
                    <span class="wk-status">
                        <span class="wk-dot dot-{{ $w->status }}"></span>
                        @if($w->status === 'available') Available
                        @elseif($w->status === 'busy') Assigned
                        @else On leave
                        @endif
                    </span>
                </td>

                {{-- Assigned to --}}
                <td style="color:#374151;">
                    @if($w->status === 'busy' && isset($data['ship']))
                        <div style="font-weight:600;">{{ $data['ship'] }}</div>
                        <div style="font-size:11px;color:#64748b;">{{ $data['job'] }}</div>
                    @else
                        <span style="color:#94a3b8;">—</span>
                    @endif
                </td>

                {{-- Phone --}}
                <td style="color:#64748b;">{{ $w->phone ?? '—' }}</td>

                {{-- Actions --}}
                <td>
                    <div class="wk-actions">
                        @if($w->status === 'available')
                        <a href="{{ route('work-orders.create') }}" class="wk-action-link assign">Assign</a>
                        @endif
                        <button class="wk-action-link edit" onclick="openEditModal({{ $w->worker_id }})">Edit</button>
                        <form method="POST" action="{{ route('workers.destroy', $w->worker_id) }}" style="display:inline;"
                              onsubmit="return confirm('Remove {{ addslashes($w->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="wk-action-link del">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        @empty
            <tr><td colspan="6" class="wk-empty">No workers registered yet.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div id="wk-no-results" class="wk-empty wk-hidden">No workers match the current filters.</div>
</div>

{{-- ── Slide-over backdrop ── --}}
<div id="wk-backdrop" class="wk-backdrop" onclick="closePanel()"></div>

{{-- ── Slide-over panel ── --}}
<div id="wk-panel" class="wk-panel">
    <button class="wk-panel-close" onclick="closePanel()">&times;</button>
    <div class="wk-panel-head" id="wk-panel-head"></div>
    <div class="wk-panel-body" id="wk-panel-body"></div>
    <div class="wk-panel-footer">
        <button class="wk-panel-edit-btn" id="wk-panel-edit-btn">Edit worker</button>
    </div>
</div>

{{-- ── Add worker modal ── --}}
<div id="add-modal" class="wk-modal-overlay">
    <div class="wk-modal">
        <button class="wk-modal-close" onclick="closeAddModal()">&times;</button>
        <div class="wk-modal-title">Add worker</div>
        <form method="POST" action="{{ route('workers.store') }}">
            @csrf
            <div class="wk-field">
                <label>Full name <span style="color:#ef4444;">*</span></label>
                <input type="text" name="name" placeholder="e.g. Karim Hossain" required>
            </div>
            <div class="wk-field">
                <label>Trade / Role</label>
                <select name="role">
                    <option value="">— Select trade —</option>
                    <option>Welder</option>
                    <option>Electrician</option>
                    <option>Painter</option>
                    <option>Fitter</option>
                </select>
            </div>
            <div class="wk-field">
                <label>Phone</label>
                <input type="text" name="phone" placeholder="e.g. 01711000000">
            </div>
            <button type="submit" class="wk-modal-submit">Add worker</button>
        </form>
    </div>
</div>

{{-- ── Edit worker modal ── --}}
<div id="edit-modal" class="wk-modal-overlay">
    <div class="wk-modal">
        <button class="wk-modal-close" onclick="closeEditModal()">&times;</button>
        <div class="wk-modal-title">Edit worker</div>
        <form method="POST" id="edit-form">
            @csrf @method('PATCH')
            <div class="wk-field">
                <label>Full name <span style="color:#ef4444;">*</span></label>
                <input type="text" name="name" id="edit-name" required>
            </div>
            <div class="wk-field">
                <label>Trade / Role</label>
                <select name="role" id="edit-role">
                    <option value="">— Select trade —</option>
                    <option>Welder</option>
                    <option>Electrician</option>
                    <option>Painter</option>
                    <option>Fitter</option>
                </select>
            </div>
            <div class="wk-field">
                <label>Phone</label>
                <input type="text" name="phone" id="edit-phone">
            </div>
            <div class="wk-field">
                <label>Status</label>
                <select name="status" id="edit-status">
                    <option value="available">Available</option>
                    <option value="busy">Assigned</option>
                </select>
            </div>
            <button type="submit" class="wk-modal-submit">Save changes</button>
        </form>
    </div>
</div>

{{-- ── Worker data for JS ── --}}
<script>
const WK = @json($jsData);

/* ─── Trade badge HTML helper ─── */
function tradeBadge(role) {
    const map = {
        'Welder':      ['background:#fef3c7;color:#92400e;',      role],
        'Electrician': ['background:#dbeafe;color:#1e40af;',      role],
        'Painter':     ['background:#f3e8ff;color:#6b21a8;',      role],
        'Fitter':      ['background:#dcfce7;color:#166534;',      role],
    };
    const [style, lbl] = map[role] || ['background:#f1f5f9;color:#475569;', role || 'Unknown'];
    return `<span class="wk-badge" style="${style}">${lbl}</span>`;
}

function statusLabel(s) {
    return s === 'available' ? 'Available' : s === 'busy' ? 'Assigned' : 'On leave';
}
function statusDotColor(s) {
    return s === 'available' ? '#22c55e' : s === 'busy' ? '#f59e0b' : '#94a3b8';
}

/* ─── Avatar style per role ─── */
function avatarStyle(role) {
    const map = {
        'Welder':      'background:#fef3c7;color:#92400e;',
        'Electrician': 'background:#dbeafe;color:#1e40af;',
        'Painter':     'background:#f3e8ff;color:#6b21a8;',
        'Fitter':      'background:#dcfce7;color:#166534;',
    };
    return map[role] || 'background:#f1f5f9;color:#475569;';
}

/* ─── Slide-over panel ─── */
function openPanel(id) {
    const w = WK[id];
    if (!w) return;

    /* Head */
    document.getElementById('wk-panel-head').innerHTML = `
        <div class="wk-panel-avatar" style="${avatarStyle(w.role)}">${w.initials}</div>
        <div class="wk-panel-name">${w.name}</div>
        <div style="margin-bottom:8px;">${tradeBadge(w.role)}</div>
        <div style="display:inline-flex;align-items:center;gap:6px;font-size:12px;font-weight:500;color:#374151;">
            <span style="width:7px;height:7px;border-radius:50%;background:${statusDotColor(w.status)};display:inline-block;"></span>
            ${statusLabel(w.status)}
        </div>
    `;

    /* Body */
    const historyHtml = w.history.length
        ? w.history.map(h => {
            const barCls = h.status === 'done' ? 'bar-done' : h.status === 'in_progress' ? 'bar-in_progress' : 'bar-pending';
            const dates  = h.start ? (h.end ? `${h.start} — ${h.end}` : `From ${h.start}`) : '—';
            return `<div class="wk-timeline-item">
                <div class="wk-timeline-bar ${barCls}"></div>
                <div class="wk-timeline-content">
                    <div class="wk-timeline-job">${h.title}</div>
                    <div class="wk-timeline-ship">${h.ship}</div>
                    <div class="wk-timeline-date">${dates}</div>
                </div>
            </div>`;
          }).join('')
        : `<p style="font-size:13px;color:#94a3b8;margin:0;">No job history yet.</p>`;

    const assignedHtml = (w.status === 'busy' && w.ship)
        ? `<div style="font-weight:600;">${w.ship}</div><div style="font-size:11px;color:#64748b;">${w.job || ''}</div>`
        : `<span style="color:#94a3b8;">—</span>`;

    document.getElementById('wk-panel-body').innerHTML = `
        <div class="wk-stat-row">
            <div class="wk-stat-box">
                <div class="wk-stat-num">${w.completed}</div>
                <div class="wk-stat-lbl">Jobs completed</div>
            </div>
            <div class="wk-stat-box">
                <div class="wk-stat-num">${w.active}</div>
                <div class="wk-stat-lbl">Active jobs</div>
            </div>
        </div>
        <div class="wk-info-row"><span class="wk-info-label">Phone</span><span class="wk-info-val">${w.phone}</span></div>
        <div class="wk-info-row"><span class="wk-info-label">Assigned to</span><span class="wk-info-val" style="text-align:right;">${assignedHtml}</span></div>
        <div class="wk-timeline" style="margin-top:24px;">
            <div class="wk-timeline-title">Job history</div>
            ${historyHtml}
        </div>
    `;

    /* Footer edit button */
    document.getElementById('wk-panel-edit-btn').onclick = () => { closePanel(); openEditModal(id); };

    document.getElementById('wk-panel').classList.add('open');
    document.getElementById('wk-backdrop').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closePanel() {
    document.getElementById('wk-panel').classList.remove('open');
    document.getElementById('wk-backdrop').classList.remove('open');
    document.body.style.overflow = '';
}

/* ─── Add modal ─── */
function openAddModal()  { document.getElementById('add-modal').classList.add('open'); }
function closeAddModal() { document.getElementById('add-modal').classList.remove('open'); }

/* ─── Edit modal ─── */
function openEditModal(id) {
    const w = WK[id];
    if (!w) return;
    document.getElementById('edit-name').value   = w.name;
    document.getElementById('edit-role').value   = w.role || '';
    document.getElementById('edit-phone').value  = w.phone === '—' ? '' : w.phone;
    document.getElementById('edit-status').value = w.status;
    document.getElementById('edit-form').action  = `/workers/${id}`;
    document.getElementById('edit-modal').classList.add('open');
}
function closeEditModal() { document.getElementById('edit-modal').classList.remove('open'); }

/* ─── Filters ─── */
function applyFilters() {
    const search = document.getElementById('wk-search').value.toLowerCase().trim();
    const trade  = document.getElementById('wk-trade').value.toLowerCase();
    const avail  = document.getElementById('wk-avail').value;

    const rows     = document.querySelectorAll('.wk-row');
    const sections = document.querySelectorAll('.wk-section-row');
    let   anyVisible = false;

    rows.forEach(row => {
        const nameMatch  = !search || row.dataset.name.includes(search);
        const tradeMatch = !trade  || row.dataset.trade === trade;
        const availMatch = !avail  || row.dataset.status === avail;
        const show = nameMatch && tradeMatch && availMatch;
        row.classList.toggle('wk-hidden', !show);
        if (show) anyVisible = true;
    });

    /* Hide section labels when all their rows are hidden */
    sections.forEach(sec => {
        const sectionTrade = sec.dataset.section;
        const visibleInSection = [...rows].some(r =>
            r.dataset.section === sectionTrade && !r.classList.contains('wk-hidden')
        );
        sec.classList.toggle('wk-hidden', !visibleInSection);
    });

    document.getElementById('wk-no-results').classList.toggle('wk-hidden', anyVisible);
}

/* Close modals on overlay click */
document.getElementById('add-modal').addEventListener('click', e => { if (e.target === document.getElementById('add-modal')) closeAddModal(); });
document.getElementById('edit-modal').addEventListener('click', e => { if (e.target === document.getElementById('edit-modal')) closeEditModal(); });
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closePanel(); closeAddModal(); closeEditModal(); }
});
</script>

@endsection
