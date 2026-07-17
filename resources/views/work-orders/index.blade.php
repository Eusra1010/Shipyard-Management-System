@extends('layouts.admin')

@section('title', 'Work Orders')
@section('page-title', 'Work Orders')
@section('breadcrumb', 'NavalForge / Work Orders')

@push('styles')
<style>
/* ── Tokens ── */
:root {
    --amber-bg: #fef3c7; --amber-fg: #92400e;
    --green-bg: #dcfce7; --green-fg: #15803d;
    --blue-bg:  #dbeafe; --blue-fg:  #1e40af;
    --gray-bg:  #f1f5f9; --gray-fg:  #475569;
    --track:    #e4e9f2;
    --divider:  #edf0f7;
    --expand:   #f8fafc;
}

/* ── Header ── */
.wo-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 20px; gap: 12px;
}
.wo-header-left { display: flex; align-items: center; gap: 10px; }
.wo-title { font-size: 20px; font-weight: 700; color: #0f172a; letter-spacing: -.01em; }
.wo-count-pill {
    font-size: 12px; font-weight: 500; color: #475569;
    background: #e2e8f0; padding: 4px 11px; border-radius: 999px;
}
.wo-btn-create {
    display: inline-flex; align-items: center; gap: 7px;
    font-size: 13px; font-weight: 600;
    padding: 9px 18px; background: #2563eb; color: #fff;
    border: none; border-radius: 8px; cursor: pointer;
    text-decoration: none; white-space: nowrap; letter-spacing: .01em;
    transition: opacity .15s;
}
.wo-btn-create:hover { opacity: .88; color: #fff; }

/* ── Flash message ── */
.wo-flash {
    margin-bottom: 16px; padding: 11px 16px;
    background: #dcfce7; border: 1px solid #bbf7d0;
    border-radius: 8px; font-size: 13px; color: #15803d; font-weight: 500;
}

/* ── Controls row ── */
.wo-controls {
    display: flex; align-items: center;
    justify-content: space-between; gap: 12px;
    margin-bottom: 14px;
}
.wo-tab-track {
    display: inline-flex; gap: 3px;
    background: var(--track); border-radius: 10px; padding: 4px;
}
.wo-tab {
    font-size: 13px; font-weight: 500; padding: 7px 16px;
    border-radius: 7px; border: none; background: transparent;
    color: #94a3b8; cursor: pointer;
    transition: background .15s, color .15s; white-space: nowrap;
}
.wo-tab:hover:not(.active) { color: #64748b; }
.wo-tab.active { background: #2563eb; color: #fff; }
.wo-search {
    font-size: 13px; padding: 8px 14px; width: 196px;
    border: 1.5px solid #e2e8f0; border-radius: 8px;
    background: #fff; color: #0f172a; outline: none;
    transition: border-color .15s;
}
.wo-search:focus { border-color: #2563eb; }
.wo-search::placeholder { color: #94a3b8; }

/* ── Table card ── */
.wo-card {
    background: #fff; border-radius: 12px;
    border: 1px solid #e2e8f0; overflow: hidden;
}

.wo-table { width: 100%; border-collapse: collapse; }

.wo-table thead th {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .08em; color: #94a3b8;
    padding: 13px 18px 11px; text-align: left;
    border-bottom: 1.5px solid var(--divider);
}
.wo-table thead th.th-center { text-align: center; }

/* Data rows */
.wo-table tr.data-row {
    border-bottom: 1px solid var(--divider); cursor: pointer;
    transition: background .1s;
}
.wo-table tr.data-row:hover { background: #f8fafc; }

.wo-table tr.data-row td {
    padding: 15px 18px; vertical-align: middle;
    font-size: 13px; color: #475569;
}

/* Ship cell */
.ship-cell { display: flex; align-items: center; gap: 8px; }
.ship-name-text { font-size: 14px; font-weight: 600; color: #0f172a; }
.wo-chevron { flex-shrink: 0; color: #cbd5e1; transition: transform .2s; }
tr.data-row.row-expanded .wo-chevron { transform: rotate(180deg); }

/* Workers cell */
.worker-stack { display: flex; flex-direction: column; gap: 2px; }
.worker-name  { font-size: 12.5px; color: #475569; }
.worker-none  { font-size: 12.5px; color: #94a3b8; font-style: italic; }

/* Status badge */
.wo-badge {
    display: inline-flex; align-items: center;
    font-size: 11.5px; font-weight: 600;
    padding: 4px 11px; border-radius: 999px; white-space: nowrap;
}
.badge-amber { background: var(--amber-bg); color: var(--amber-fg); }
.badge-green { background: var(--green-bg); color: var(--green-fg); }
.badge-blue  { background: var(--blue-bg);  color: var(--blue-fg);  }
.badge-gray  { background: var(--gray-bg);  color: var(--gray-fg);  }

/* Date */
.date-cell { font-size: 12px; color: #94a3b8; white-space: nowrap; }

/* Actions */
.actions-cell { text-align: center; }
.action-wrap  { display: inline-flex; align-items: center; gap: 5px; }
.action-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 30px; height: 30px; border: none; background: transparent;
    border-radius: 6px; cursor: pointer; color: #94a3b8;
    transition: background .12s, color .12s; text-decoration: none;
}
.action-btn:hover { background: #f1f5f9; color: #475569; }

/* Detail rows */
.wo-table tr.detail-row { display: none; }
.wo-table tr.detail-row td {
    padding: 0; border-bottom: 1px solid var(--divider);
}
.detail-inner {
    background: var(--expand);
    border-top: 1px solid var(--divider);
    padding: 14px 20px 18px 50px;
    display: flex; gap: 48px;
}
.detail-section { min-width: 160px; }
.detail-label {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .09em; color: #94a3b8; margin-bottom: 8px;
}
.detail-item { font-size: 13px; color: #475569; padding: 2px 0; }
.detail-empty { font-size: 12px; color: #94a3b8; font-style: italic; padding: 2px 0; }

/* Empty state */
.wo-empty-cell {
    text-align: center; padding: 52px 20px;
    font-size: 13px; color: #94a3b8; font-style: italic;
}
.wo-no-match { display: none; }
.wo-no-match td { padding: 40px 20px; text-align: center;
    font-size: 13px; color: #94a3b8; font-style: italic; }
</style>
@endpush

@section('content')

@if(session('success'))
    <div class="wo-flash">{{ session('success') }}</div>
@endif

{{-- Header --}}
<div class="wo-header">
    <div class="wo-header-left">
        <span class="wo-title">Work Orders</span>
        @php $activeCount = ($stats->pending_count ?? 0) + ($stats->in_progress_count ?? 0); @endphp
        <span class="wo-count-pill" id="wo-count">{{ $activeCount }} active</span>
    </div>
    <button type="button" class="wo-btn-create" onclick="openCreateModal()">
        <svg width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
            <path d="M6.5 1v11M1 6.5h11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        Create work order
    </button>
</div>

{{-- Controls --}}
<div class="wo-controls">
    <div class="wo-tab-track">
        <button class="wo-tab active" data-filter="all">All</button>
        <button class="wo-tab" data-filter="pending">Pending</button>
        <button class="wo-tab" data-filter="in_progress">In Progress</button>
        <button class="wo-tab" data-filter="completed">Completed</button>
    </div>
    <input type="text" id="wo-search" class="wo-search" placeholder="Search by ship">
</div>

{{-- Table --}}
<div class="wo-card">
    <table class="wo-table">
        <thead>
            <tr>
                <th style="width:22%">Ship</th>
                <th style="width:22%">Job type</th>
                <th style="width:20%">Assigned workers</th>
                <th style="width:14%">Status</th>
                <th style="width:13%">Created</th>
                <th class="th-center" style="width:9%">Actions</th>
            </tr>
        </thead>
        <tbody id="wo-tbody">

            @forelse($orders as $order)
                @php
                    $statusKey  = $order->status === 'done' ? 'completed' : $order->status;
                    $badgeClass = match($order->status) {
                        'in_progress' => 'badge-amber',
                        'done'        => 'badge-green',
                        'pending'     => 'badge-blue',
                        default       => 'badge-gray',
                    };
                    $badgeLabel = match($order->status) {
                        'in_progress' => 'In progress',
                        'done'        => 'Completed',
                        'pending'     => 'Pending',
                        'cancelled'   => 'Cancelled',
                        default       => ucfirst(str_replace('_', ' ', $order->status)),
                    };
                    $workers   = $workersByOrder[$order->order_id]   ?? [];
                    $materials = $materialsByOrder[$order->order_id] ?? [];
                @endphp

                <tr class="data-row"
                    data-status="{{ $statusKey }}"
                    data-ship="{{ strtolower($order->ship_name) }}"
                    data-expand="detail-{{ $order->order_id }}">

                    <td>
                        <div class="ship-cell">
                            <svg class="wo-chevron" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                                <path d="M3 5l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span class="ship-name-text">{{ $order->ship_name }}</span>
                        </div>
                    </td>

                    <td>{{ $order->title }}</td>

                    <td>
                        @if(count($workers) > 0)
                            <div class="worker-stack">
                                @foreach($workers as $w)
                                    <span class="worker-name">{{ $w->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <span class="worker-none">Unassigned</span>
                        @endif
                    </td>

                    <td>
                        <span class="wo-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                    </td>

                    <td class="date-cell">{{ $order->created_date ?? '—' }}</td>

                    <td class="actions-cell">
                        <div class="action-wrap">
                            <a href="{{ route('work-orders.show', $order->order_id) }}"
                               class="action-btn" title="View" onclick="event.stopPropagation()">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" aria-hidden="true">
                                    <ellipse cx="7.5" cy="7.5" rx="6" ry="4" stroke="currentColor" stroke-width="1.4"/>
                                    <circle cx="7.5" cy="7.5" r="1.5" fill="currentColor"/>
                                </svg>
                            </a>
                            <a href="{{ route('work-orders.edit', $order->order_id) }}"
                               class="action-btn" title="Edit" onclick="event.stopPropagation()">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" aria-hidden="true">
                                    <path d="M10.5 2.5l2 2L5 12H3v-2l7.5-7.5z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>

                <tr class="detail-row" id="detail-{{ $order->order_id }}">
                    <td colspan="6">
                        <div class="detail-inner">
                            <div class="detail-section">
                                <div class="detail-label">Assigned workers</div>
                                @forelse($workers as $w)
                                    <div class="detail-item">
                                        {{ $w->name }}@if($w->role) — {{ $w->role }}@endif
                                    </div>
                                @empty
                                    <div class="detail-empty">No workers assigned</div>
                                @endforelse
                            </div>
                            <div class="detail-section">
                                <div class="detail-label">Materials used</div>
                                @forelse($materials as $m)
                                    <div class="detail-item">
                                        {{ $m->name }} &times; {{ $m->qty_used }}{{ $m->unit ? ' '.$m->unit : '' }}
                                    </div>
                                @empty
                                    <div class="detail-empty">No materials recorded</div>
                                @endforelse
                            </div>
                        </div>
                    </td>
                </tr>

            @empty
                <tr>
                    <td class="wo-empty-cell" colspan="6">No work orders found.</td>
                </tr>
            @endforelse

            <tr class="wo-no-match" id="wo-empty">
                <td colspan="6">No orders match your filter.</td>
            </tr>

        </tbody>
    </table>
</div>

<script>
(function () {
    var tabs      = document.querySelectorAll('.wo-tab');
    var dataRows  = Array.from(document.querySelectorAll('#wo-tbody tr.data-row'));
    var emptyRow  = document.getElementById('wo-empty');
    var search    = document.getElementById('wo-search');
    var countEl   = document.getElementById('wo-count');
    var active    = 'all';
    var term      = '';

    function apply() {
        var visible = 0;
        dataRows.forEach(function (row) {
            var status = row.dataset.status || '';
            var ship   = row.dataset.ship   || '';
            var matchF = active === 'all' || status === active;
            var matchS = !term || ship.indexOf(term) !== -1;
            var show   = matchF && matchS;
            row.style.display = show ? '' : 'none';
            if (!show) {
                var det = document.getElementById(row.dataset.expand);
                if (det) { det.style.display = 'none'; row.classList.remove('row-expanded'); }
            }
            if (show) visible++;
        });
        emptyRow.style.display = visible === 0 ? 'table-row' : 'none';

        var activeCount = dataRows.filter(function (r) {
            return r.style.display !== 'none' &&
                   (r.dataset.status === 'in_progress' || r.dataset.status === 'pending');
        }).length;
        if (countEl) countEl.textContent = activeCount + ' active';
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            tabs.forEach(function (t) { t.classList.remove('active'); });
            tab.classList.add('active');
            active = tab.dataset.filter;
            apply();
        });
    });

    search.addEventListener('input', function (e) {
        term = e.target.value.toLowerCase().trim();
        apply();
    });

    dataRows.forEach(function (row) {
        row.addEventListener('click', function (e) {
            if (e.target.closest('.action-btn')) return;
            var detailId = row.dataset.expand;
            var det = detailId ? document.getElementById(detailId) : null;
            if (!det) return;
            var open = det.style.display === 'table-row';
            det.style.display = open ? 'none' : 'table-row';
            row.classList.toggle('row-expanded', !open);
        });
    });
})();
</script>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{--   CREATE WORK ORDER MODAL                                   --}}
{{-- ═══════════════════════════════════════════════════════════ --}}

@push('styles')
<style>
/* ── Overlay ── */
.cwo-overlay {
    display: none;
    position: fixed; inset: 0; z-index: 400;
    background: rgba(15,23,42,.55);
    align-items: center; justify-content: center;
    padding: 20px;
}
.cwo-overlay.open { display: flex; }

/* ── Modal box ── */
.cwo-modal {
    width: 100%; max-width: 400px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 24px 64px rgba(0,0,0,.22);
    display: flex; flex-direction: column;
    max-height: calc(100vh - 40px);
    overflow: hidden;
}

/* ── Header ── */
.cwo-hd {
    padding: 18px 20px 16px;
    border-bottom: 1px solid #edf0f7;
    display: flex; align-items: center; justify-content: space-between;
    flex-shrink: 0;
}
.cwo-title {
    font-size: 15px; font-weight: 700; color: #0f172a; letter-spacing: -.01em;
}
.cwo-close {
    background: none; border: none; cursor: pointer;
    font-size: 20px; color: #94a3b8; line-height: 1;
    padding: 2px 6px; border-radius: 5px; transition: color .12s;
}
.cwo-close:hover { color: #475569; }

/* ── Body ── */
.cwo-body {
    padding: 20px;
    overflow-y: auto;
    flex: 1;
}

/* ── Field ── */
.cwo-field { margin-bottom: 18px; }
.cwo-field:last-child { margin-bottom: 0; }

.cwo-label {
    display: block;
    font-size: 10px; font-weight: 800;
    text-transform: uppercase; letter-spacing: .09em;
    color: #94a3b8; margin-bottom: 6px;
}
.cwo-label-sub {
    font-size: 10px; font-weight: 500;
    text-transform: none; letter-spacing: 0;
    color: #cbd5e1; margin-left: 5px;
}

.cwo-select, .cwo-input {
    width: 100%; font-size: 13px; padding: 10px 12px;
    border: 1.5px solid #e2e8f0; border-radius: 8px;
    background: #fff; color: #0f172a; outline: none;
    font-family: inherit; transition: border-color .15s;
}
.cwo-select:focus, .cwo-input:focus { border-color: #0f172a; }
.cwo-select.err, .cwo-input.err { border-color: #ef4444; }

/* Berth auto-fill box */
.cwo-berth-box {
    font-size: 13px; font-weight: 600; color: #1e293b;
    padding: 10px 12px; border-radius: 8px;
    background: #f1f5f9; border: 1.5px solid #e2e8f0;
    min-height: 42px; display: flex; align-items: center;
}
.cwo-berth-warn {
    font-size: 12px; font-weight: 600;
    color: #92400e; background: #fef3c7;
    border: 1.5px solid #fde68a;
    padding: 9px 12px; border-radius: 8px;
    min-height: 42px; display: none; align-items: center;
}
.cwo-berth-none {
    font-size: 12px; color: #94a3b8; font-weight: 400; font-style: italic;
}

/* Inline error */
.cwo-err {
    font-size: 11.5px; color: #dc2626; font-weight: 500;
    margin-top: 5px; display: none;
}
.cwo-err.show { display: block; }

/* Worker chips */
.cwo-chips {
    display: flex; flex-wrap: wrap; gap: 6px;
}
.cwo-chip {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 12px; font-weight: 600;
    padding: 6px 12px; border-radius: 6px; cursor: pointer;
    border: 1.5px solid #e2e8f0;
    background: #fff; color: #94a3b8;
    transition: background .1s, color .1s, border-color .1s;
    user-select: none;
}
.cwo-chip:hover {
    border-color: #cbd5e1; color: #475569;
}
.cwo-chip.selected {
    background: #0f172a; color: #fff;
    border-color: #0f172a;
}
.cwo-chip .chip-check {
    font-size: 10px; display: none;
}
.cwo-chip.selected .chip-check { display: inline; }

/* Side-by-side row */
.cwo-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

/* ── Footer ── */
.cwo-ft {
    padding: 14px 20px;
    border-top: 1px solid #edf0f7;
    display: grid; grid-template-columns: 1fr 1fr; gap: 10px;
    flex-shrink: 0;
}
.cwo-btn-cancel {
    font-size: 13px; font-weight: 600; padding: 10px;
    border: 1.5px solid #e2e8f0; border-radius: 8px;
    background: #fff; color: #475569; cursor: pointer;
    transition: background .12s;
}
.cwo-btn-cancel:hover { background: #f8fafc; }
.cwo-btn-submit {
    font-size: 13px; font-weight: 600; padding: 10px;
    border: none; border-radius: 8px;
    background: #0f172a; color: #fff; cursor: pointer;
    transition: opacity .15s;
}
.cwo-btn-submit:hover { opacity: .85; }
</style>
@endpush

{{-- Overlay + modal --}}
<div class="cwo-overlay" id="cwo-overlay">
    <div class="cwo-modal" role="dialog" aria-modal="true" aria-labelledby="cwo-title">

        <div class="cwo-hd">
            <span class="cwo-title" id="cwo-title">Create work order</span>
            <button class="cwo-close" onclick="closeCreateModal()" aria-label="Close">&times;</button>
        </div>

        <form method="POST" action="{{ route('work-orders.store') }}" id="cwo-form" novalidate>
            @csrf

            <div class="cwo-body">

                {{-- Ship --}}
                <div class="cwo-field">
                    <label class="cwo-label" for="cwo-ship">Ship</label>
                    <select name="ship_id" id="cwo-ship"
                            class="cwo-select{{ $errors->has('ship_id') ? ' err' : '' }}"
                            onchange="onShipChange(this)">
                        <option value="">— select ship —</option>
                        @foreach($modalShips as $s)
                            <option value="{{ $s->ship_id }}"
                                    data-berth="{{ $s->berth_name }}"
                                    {{ old('ship_id') == $s->ship_id ? 'selected' : '' }}>
                                {{ $s->ship_name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="cwo-err{{ $errors->has('ship_id') ? ' show' : '' }}" id="err-ship">
                        {{ $errors->first('ship_id') ?: 'Please select a ship.' }}
                    </div>
                </div>

                {{-- Berth (read-only auto-fill) --}}
                <div class="cwo-field">
                    <label class="cwo-label">Berth</label>
                    <div class="cwo-berth-box" id="cwo-berth-box">
                        <span class="cwo-berth-none" id="cwo-berth-text">Select a ship first</span>
                    </div>
                    <div class="cwo-berth-warn" id="cwo-berth-warn">
                        No berth assigned — dock the ship first
                    </div>
                </div>

                {{-- Job description --}}
                <div class="cwo-field">
                    <label class="cwo-label" for="cwo-title-inp">Job description</label>
                    <input type="text" name="title" id="cwo-title-inp"
                           class="cwo-input{{ $errors->has('title') ? ' err' : '' }}"
                           placeholder="e.g. Hull repair — port side plating"
                           value="{{ old('title') }}">
                    <div class="cwo-err{{ $errors->has('title') ? ' show' : '' }}" id="err-title">
                        {{ $errors->first('title') ?: 'Please enter a job description.' }}
                    </div>
                </div>

                {{-- Workers --}}
                <div class="cwo-field">
                    <label class="cwo-label">
                        Assign workers
                        <span class="cwo-label-sub">(available only)</span>
                    </label>
                    <div class="cwo-chips" id="cwo-chips">
                        @forelse($modalWorkers as $w)
                            <button type="button"
                                    class="cwo-chip"
                                    data-id="{{ $w->worker_id }}"
                                    onclick="toggleChip(this)">
                                <span class="chip-check">✓</span>
                                {{ $w->name }}
                            </button>
                        @empty
                            <span style="font-size:12px;color:#94a3b8;font-style:italic;">No workers available.</span>
                        @endforelse
                    </div>
                    <div id="cwo-worker-inputs"></div>
                </div>

                {{-- Priority + Expected completion --}}
                <div class="cwo-row2">
                    <div class="cwo-field" style="margin-bottom:0;">
                        <label class="cwo-label" for="cwo-priority">Priority</label>
                        <select name="priority" id="cwo-priority" class="cwo-select">
                            <option value="normal" {{ old('priority','normal')==='normal' ? 'selected':'' }}>Normal</option>
                            <option value="urgent" {{ old('priority')==='urgent' ? 'selected':'' }}>Urgent</option>
                        </select>
                    </div>
                    <div class="cwo-field" style="margin-bottom:0;">
                        <label class="cwo-label" for="cwo-end">Expected completion</label>
                        <input type="date" name="end_date" id="cwo-end"
                               class="cwo-input{{ $errors->has('end_date') ? ' err' : '' }}"
                               value="{{ old('end_date') }}">
                    </div>
                </div>

            </div>{{-- .cwo-body --}}

            <div class="cwo-ft">
                <button type="button" class="cwo-btn-cancel" onclick="closeCreateModal()">Cancel</button>
                <button type="submit" class="cwo-btn-submit">Create work order</button>
            </div>
        </form>

    </div>
</div>

<script>
(function () {
    /* ── Open / close ── */
    window.openCreateModal  = function () { document.getElementById('cwo-overlay').classList.add('open'); };
    window.closeCreateModal = function () { document.getElementById('cwo-overlay').classList.remove('open'); };

    /* Close on overlay click */
    document.getElementById('cwo-overlay').addEventListener('click', function (e) {
        if (e.target === this) closeCreateModal();
    });

    /* Close on Escape */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeCreateModal();
    });

    /* Re-open if validation errors came back */
    @if($errors->any())
    document.addEventListener('DOMContentLoaded', function () { openCreateModal(); });
    @endif

    /* ── Ship → Berth auto-fill ── */
    window.onShipChange = function (sel) {
        var box  = document.getElementById('cwo-berth-box');
        var warn = document.getElementById('cwo-berth-warn');
        var txt  = document.getElementById('cwo-berth-text');
        var opt  = sel.options[sel.selectedIndex];

        if (!sel.value) {
            box.style.display  = 'flex';
            warn.style.display = 'none';
            txt.textContent    = 'Select a ship first';
            txt.className      = 'cwo-berth-none';
            return;
        }

        var berth = opt.dataset.berth || '';
        if (berth) {
            box.style.display  = 'flex';
            warn.style.display = 'none';
            txt.textContent    = berth;
            txt.className      = '';
        } else {
            box.style.display  = 'none';
            warn.style.display = 'flex';
        }
    };

    /* Restore berth on page reload with old value */
    var shipSel = document.getElementById('cwo-ship');
    if (shipSel && shipSel.value) onShipChange(shipSel);

    /* ── Worker chips ── */
    var workerInputs = document.getElementById('cwo-worker-inputs');

    window.toggleChip = function (btn) {
        btn.classList.toggle('selected');
        var id = btn.dataset.id;
        /* sync hidden inputs */
        var existing = workerInputs.querySelector('input[value="' + id + '"]');
        if (btn.classList.contains('selected')) {
            if (!existing) {
                var inp = document.createElement('input');
                inp.type  = 'hidden';
                inp.name  = 'worker_ids[]';
                inp.value = id;
                workerInputs.appendChild(inp);
            }
        } else {
            if (existing) existing.remove();
        }
    };

    /* Restore chip state on reload with old worker_ids */
    @if(old('worker_ids'))
    var oldWorkers = @json(old('worker_ids', []));
    document.querySelectorAll('.cwo-chip').forEach(function (chip) {
        if (oldWorkers.indexOf(chip.dataset.id) !== -1) toggleChip(chip);
    });
    @endif

    /* ── Client-side validation ── */
    document.getElementById('cwo-form').addEventListener('submit', function (e) {
        var valid = true;

        var ship  = document.getElementById('cwo-ship');
        var title = document.getElementById('cwo-title-inp');
        var errS  = document.getElementById('err-ship');
        var errT  = document.getElementById('err-title');

        if (!ship.value) {
            ship.classList.add('err');
            errS.classList.add('show');
            valid = false;
        } else {
            ship.classList.remove('err');
            errS.classList.remove('show');
        }

        if (!title.value.trim()) {
            title.classList.add('err');
            errT.classList.add('show');
            valid = false;
        } else {
            title.classList.remove('err');
            errT.classList.remove('show');
        }

        if (!valid) e.preventDefault();
    });
}());
</script>

@endsection
