@extends('layouts.admin')
@section('title', 'Berths')
@section('page-title', 'Berths')
@section('breadcrumb', 'NavalForge / Berths')

@push('styles')
<style>
/* ── Flash ── */
.bth-flash {
    margin-bottom: 18px; padding: 11px 16px;
    background: #dcfce7; border: 1px solid #bbf7d0;
    border-radius: 8px; font-size: 13px; color: #15803d; font-weight: 500;
}

/* ── Page header ── */
.bth-header { margin-bottom: 24px; }
.bth-title  { font-size: 20px; font-weight: 700; color: #0f172a; letter-spacing: -.01em; }
.bth-summary {
    font-size: 13px; color: #64748b; margin-top: 4px;
}
.bth-summary strong { color: #0f172a; }

/* ── Berth grid ── */
.bth-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
    margin-bottom: 20px;
}
@media (max-width: 1100px) { .bth-grid { grid-template-columns: repeat(4, 1fr); } }
@media (max-width:  860px) { .bth-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width:  600px) { .bth-grid { grid-template-columns: repeat(2, 1fr); } }

/* ── Tile base ── */
.bth-tile {
    border-radius: 8px;
    padding: 16px;
    min-height: 110px;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: opacity .15s, box-shadow .15s;
    user-select: none;
    position: relative;
}
.bth-tile:hover { opacity: .88; box-shadow: 0 4px 16px rgba(0,0,0,.18); }
.bth-tile.active-tile { box-shadow: 0 0 0 3px #4a9ee0, 0 4px 16px rgba(0,0,0,.18); }

/* Occupied */
.bth-tile.occupied {
    background: #0f172a;
    color: #f1f5f9;
}
/* Free */
.bth-tile.free {
    background: #4a9ee0;
    color: #0f172a;
}

/* Tile text elements */
.tile-berth-id {
    font-size: 10px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .1em;
    opacity: .55;
    margin-bottom: 6px;
}
.tile-ship-name {
    font-size: 14px; font-weight: 700;
    line-height: 1.25;
    flex: 1;
}
.tile-free-label {
    font-size: 15px; font-weight: 700;
    flex: 1; display: flex; align-items: center;
}
.tile-docked {
    font-size: 10.5px;
    opacity: .5;
    margin-top: 8px;
}

/* ── Legend ── */
.bth-legend {
    display: flex; align-items: center; gap: 20px;
    margin-bottom: 28px;
}
.legend-item { display: flex; align-items: center; gap: 7px; }
.legend-swatch {
    width: 16px; height: 16px; border-radius: 4px; flex-shrink: 0;
}
.legend-lbl { font-size: 12px; color: #64748b; font-weight: 500; }

/* ── Side panel ── */
.bth-panel {
    position: fixed;
    top: 72px;
    right: 24px;
    width: 260px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0,0,0,.12);
    z-index: 200;
    display: none;
}
.bth-panel.open { display: block; }

.panel-hd {
    padding: 14px 16px;
    border-bottom: 1px solid #edf0f7;
    display: flex; align-items: center; justify-content: space-between;
}
.panel-berth-id {
    font-size: 10px; font-weight: 800; text-transform: uppercase;
    letter-spacing: .1em; color: #94a3b8;
}
.panel-close {
    background: none; border: none; cursor: pointer;
    font-size: 18px; color: #94a3b8; line-height: 1;
    padding: 0 4px; border-radius: 4px; transition: color .12s;
}
.panel-close:hover { color: #475569; }

.panel-body { padding: 14px 16px; }

.panel-ship-name {
    font-size: 15px; font-weight: 700; color: #0f172a;
    line-height: 1.3; margin-bottom: 12px;
}

.panel-row {
    margin-bottom: 10px;
}
.panel-lbl {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .07em; color: #94a3b8; margin-bottom: 2px;
}
.panel-val {
    font-size: 13px; color: #1e293b; font-weight: 500;
}
.panel-val.muted { color: #64748b; font-weight: 400; }

/* Job status chip */
.panel-status {
    display: inline-block; font-size: 10.5px; font-weight: 700;
    padding: 2px 9px; border-radius: 999px;
}
.ps-pending    { background: #dbeafe; color: #1e40af; }
.ps-in_progress{ background: #fef3c7; color: #92400e; }
.ps-done       { background: #dcfce7; color: #15803d; }

.panel-divider { border: none; border-top: 1px solid #edf0f7; margin: 12px 0; }

/* Free panel – assign form */
.panel-free-label {
    font-size: 13px; font-weight: 600; color: #4a9ee0;
    margin-bottom: 14px;
}
.panel-select {
    width: 100%; font-size: 13px; padding: 8px 10px;
    border: 1.5px solid #e2e8f0; border-radius: 8px;
    background: #fff; color: #0f172a; outline: none;
    margin-bottom: 10px; font-family: inherit;
    transition: border-color .15s;
}
.panel-select:focus { border-color: #4a9ee0; }
.panel-select-none {
    font-size: 12px; color: #94a3b8; font-style: italic;
}

/* Panel action buttons */
.panel-btn {
    width: 100%; font-size: 13px; font-weight: 600;
    padding: 9px; border-radius: 8px; border: none;
    cursor: pointer; margin-top: 4px; transition: opacity .15s;
}
.panel-btn:hover { opacity: .85; }
.btn-release { background: #fee2e2; color: #b91c1c; }
.btn-assign  { background: #0f172a; color: #fff; }
</style>
@endpush

@section('content')

@if(session('success'))
<div class="bth-flash">{{ session('success') }}</div>
@endif

{{-- ── Header ── --}}
<div class="bth-header">
    <div class="bth-title">Berths</div>
    <div class="bth-summary">
        <strong>{{ $total }} total</strong> &nbsp;·&nbsp;
        {{ $total - $free }} occupied &nbsp;·&nbsp;
        <strong>{{ $free }} free</strong>
    </div>
</div>

{{-- ── Berth grid ── --}}
<div class="bth-grid">
@foreach($berths as $b)
    <div class="bth-tile {{ $b->status }}"
         data-id="{{ $b->berth_id }}"
         onclick="openPanel({{ $b->berth_id }})">

        <div class="tile-berth-id">
            {{ $b->berth_type === 'Dry Dock' ? 'Dry Dock' : $b->berth_name }}
        </div>

        @if($b->status === 'occupied')
            <div class="tile-ship-name">{{ $b->ship_name }}</div>
            <div class="tile-docked">Docked {{ $b->docked_since }}</div>
        @else
            <div class="tile-free-label">Free</div>
            <div class="tile-docked">&nbsp;</div>
        @endif
    </div>
@endforeach
</div>

{{-- ── Legend ── --}}
<div class="bth-legend">
    <div class="legend-item">
        <div class="legend-swatch" style="background:#0f172a;"></div>
        <span class="legend-lbl">Occupied</span>
    </div>
    <div class="legend-item">
        <div class="legend-swatch" style="background:#4a9ee0;"></div>
        <span class="legend-lbl">Free</span>
    </div>
</div>

{{-- ── Side panel ── --}}
<div class="bth-panel" id="bth-panel">
    <div class="panel-hd">
        <span class="panel-berth-id" id="panel-berth-id">—</span>
        <button class="panel-close" onclick="closePanel()">&times;</button>
    </div>
    <div class="panel-body" id="panel-body">
        {{-- filled by JS --}}
    </div>
</div>

{{-- Berth data for JS --}}
<script>
var BERTHS = @json($berths);
var SHIPS  = @json($availableShips);
var ROUTES = {
    release : '{{ url("/berths") }}',
    assign  : '{{ url("/berths") }}',
    csrf    : '{{ csrf_token() }}'
};
</script>

<script>
(function () {
    var panel   = document.getElementById('bth-panel');
    var panelId = document.getElementById('panel-berth-id');
    var panelBd = document.getElementById('panel-body');
    var tiles   = document.querySelectorAll('.bth-tile');
    var current = null;

    function jobStatusClass(s) {
        if (s === 'in_progress') return 'ps-in_progress';
        if (s === 'done')        return 'ps-done';
        return 'ps-pending';
    }
    function jobStatusLabel(s) {
        if (s === 'in_progress') return 'In progress';
        if (s === 'done')        return 'Completed';
        return 'Pending';
    }

    window.openPanel = function (id) {
        var b = BERTHS.find(function (x) { return x.berth_id == id; });
        if (!b) return;

        /* highlight tile */
        tiles.forEach(function (t) { t.classList.remove('active-tile'); });
        var tile = document.querySelector('.bth-tile[data-id="' + id + '"]');
        if (tile) tile.classList.add('active-tile');
        current = id;

        var label = b.berth_type === 'Dry Dock' ? 'Dry Dock' : b.berth_name;
        panelId.textContent = label.toUpperCase();

        var html = '';

        if (b.status === 'occupied') {
            html += '<div class="panel-ship-name">' + esc(b.ship_name) + '</div>';

            html += '<div class="panel-row"><div class="panel-lbl">Docked since</div>';
            html += '<div class="panel-val">' + (b.docked_since || '—') + '</div></div>';

            html += '<div class="panel-row"><div class="panel-lbl">Ship type</div>';
            html += '<div class="panel-val muted">' + (b.ship_type || '—') + '</div></div>';

            if (b.job_title) {
                html += '<hr class="panel-divider">';
                html += '<div class="panel-row"><div class="panel-lbl">Active job</div>';
                html += '<div class="panel-val">' + esc(b.job_title) + '</div></div>';
                html += '<div class="panel-row"><div class="panel-lbl">Status</div>';
                html += '<div class="panel-val"><span class="panel-status ' + jobStatusClass(b.job_status) + '">' + jobStatusLabel(b.job_status) + '</span></div></div>';
            } else {
                html += '<hr class="panel-divider">';
                html += '<div class="panel-row"><div class="panel-lbl">Active job</div>';
                html += '<div class="panel-val muted">No active work order</div></div>';
            }

            html += '<hr class="panel-divider">';
            html += '<form method="POST" action="' + ROUTES.release + '/' + b.berth_id + '/release">';
            html += '<input type="hidden" name="_token" value="' + ROUTES.csrf + '">';
            html += '<input type="hidden" name="_method" value="PATCH">';
            html += '<button type="submit" class="panel-btn btn-release">Release berth</button>';
            html += '</form>';

        } else {
            html += '<div class="panel-free-label">Berth is free</div>';

            if (SHIPS.length > 0) {
                html += '<form method="POST" action="' + ROUTES.assign + '/' + b.berth_id + '/assign">';
                html += '<input type="hidden" name="_token" value="' + ROUTES.csrf + '">';
                html += '<input type="hidden" name="_method" value="PATCH">';
                html += '<div class="panel-row"><div class="panel-lbl">Assign ship</div></div>';
                html += '<select name="ship_id" class="panel-select" required>';
                html += '<option value="">— select ship —</option>';
                SHIPS.forEach(function (s) {
                    html += '<option value="' + s.ship_id + '">' + esc(s.ship_name) + '</option>';
                });
                html += '</select>';
                html += '<button type="submit" class="panel-btn btn-assign">Assign ship</button>';
                html += '</form>';
            } else {
                html += '<div class="panel-select-none">No unassigned ships available.</div>';
            }
        }

        panelBd.innerHTML = html;
        panel.classList.add('open');
    };

    window.closePanel = function () {
        panel.classList.remove('open');
        tiles.forEach(function (t) { t.classList.remove('active-tile'); });
        current = null;
    };

    /* Close on outside click */
    document.addEventListener('click', function (e) {
        if (!panel.contains(e.target) && !e.target.closest('.bth-tile')) {
            closePanel();
        }
    });

    /* Escape key */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closePanel();
    });

    function esc(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
}());
</script>

@endsection
