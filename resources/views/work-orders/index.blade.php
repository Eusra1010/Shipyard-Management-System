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
    transition: background .12s, color .12s;
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

{{-- Flash success --}}
@if(session('success'))
    <div class="wo-flash">{{ session('success') }}</div>
@endif

{{-- Header --}}
<div class="wo-header">
    <div class="wo-header-left">
        <span class="wo-title">Work Orders</span>
        <span class="wo-count-pill" id="wo-count">{{ $activeCount }} active</span>
    </div>
    <a href="{{ route('work-orders.create') }}" class="wo-btn-create">
        <svg width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
            <path d="M6.5 1v11M1 6.5h11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        Create work order
    </a>
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

                {{-- Main row --}}
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
                            {{-- Mark complete --}}
                            <button class="action-btn" title="Mark complete">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                                    <rect x="1.5" y="1.5" width="13" height="13" rx="3" stroke="{{ $order->status === 'done' ? '#16a34a' : 'currentColor' }}" stroke-width="1.5"/>
                                    @if($order->status === 'done')
                                        <path d="M4.5 8.5l2.5 2 4.5-5" stroke="#16a34a" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    @else
                                        <path d="M4.5 8.5l2.5 2 4.5-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" opacity=".3"/>
                                    @endif
                                </svg>
                            </button>
                            {{-- Edit --}}
                            <button class="action-btn" title="Edit">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" aria-hidden="true">
                                    <path d="M10.5 2.5l2 2L5 12H3v-2l7.5-7.5z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>

                {{-- Expandable detail row --}}
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

            {{-- JS empty state --}}
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
            // keep linked detail row hidden if main row is hidden
            if (!show) {
                var det = document.getElementById(row.dataset.expand);
                if (det) { det.style.display = 'none'; row.classList.remove('row-expanded'); }
            }
            if (show) visible++;
        });
        emptyRow.style.display = visible === 0 ? 'table-row' : 'none';

        // live-update active count pill
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

    // Expand / collapse detail rows on row click
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

@endsection
