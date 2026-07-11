@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'NavalForge / Dashboard')

@push('styles')
<style>
/* ── Stat cards ── */
.stat-card {
    border-radius:10px; padding:20px 20px 16px; position:relative; overflow:hidden;
}
.stat-label {
    font-size:10px; font-weight:700; text-transform:uppercase;
    letter-spacing:.08em; margin-bottom:8px;
}
.stat-number { font-size:34px; font-weight:800; line-height:1; margin-bottom:5px; }
.stat-sub { font-size:12px; }
.stat-icon {
    position:absolute; right:14px; bottom:12px;
    font-size:40px; opacity:.18; pointer-events:none;
}

/* ── Panels ── */
.panel { background:#fff; border-radius:10px; padding:20px; border:1px solid #d4dae8; }
.panel-title {
    font-size:13px; font-weight:700; color:#0f172a;
    margin-bottom:16px; display:flex; align-items:center; gap:8px;
}

/* ── Berth grid boxes ── */
.berth-box {
    border-radius:6px; padding:7px 10px;
    font-size:11px; font-weight:600; text-align:center;
    display:flex; align-items:center; justify-content:center;
    min-height:36px; min-width:78px; word-break:break-word; line-height:1.2;
}

/* ── Work orders table ── */
.wo-table { width:100%; border-collapse:collapse; }
.wo-table th {
    font-size:10px; font-weight:700; color:#94a3b8;
    text-transform:uppercase; letter-spacing:.07em;
    padding:0 12px 8px; text-align:left;
    border-bottom:2px solid #edf0f7;
}
.wo-table td {
    font-size:13px; padding:10px 12px;
    border-bottom:1px solid #edf0f7; vertical-align:middle;
}
.wo-table tr:last-child td { border-bottom:none; }
.wo-table tbody tr:hover td { background:#f4f6fb; }
.status-pill {
    display:inline-block; font-size:10px; font-weight:700;
    padding:3px 9px; border-radius:999px;
    text-transform:uppercase; letter-spacing:.05em; white-space:nowrap;
}

/* ── Right sidebar ── */
.stock-item {
    display:flex; justify-content:space-between; align-items:center;
    padding:8px 0; border-bottom:1px solid #edf0f7; gap:8px;
}
.stock-item:last-child { border-bottom:none; }
</style>
@endpush

@section('content')

{{-- ════════════════════════════════
     4 STAT CARDS
════════════════════════════════ --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:16px;">

    {{-- Active Ships · solid blue --}}
    <div class="stat-card" style="background:#1d4ed8;">
        <div class="stat-label" style="color:rgba(255,255,255,.65);">Active Ships</div>
        <div class="stat-number" style="color:#fff;">{{ $activeShips }}</div>
        <div class="stat-sub" style="color:rgba(255,255,255,.55);">Currently in repair</div>
        <i class="fas fa-ship stat-icon" style="color:#fff;"></i>
    </div>

    {{-- Berths · solid teal --}}
    <div class="stat-card" style="background:#0d9488;">
        <div class="stat-label" style="color:rgba(255,255,255,.65);">Berths Free</div>
        <div class="stat-number" style="color:#fff;">
            {{ $freeBerths }}<span style="font-size:16px;font-weight:500;color:rgba(255,255,255,.5);"> / {{ $totalBerths }}</span>
        </div>
        <div class="stat-sub" style="color:rgba(255,255,255,.55);">{{ $occupiedBerths }} occupied</div>
        <i class="fas fa-anchor stat-icon" style="color:#fff;"></i>
    </div>

    {{-- Work Orders · solid amber --}}
    <div class="stat-card" style="background:#b45309;">
        <div class="stat-label" style="color:rgba(255,255,255,.65);">Work Orders</div>
        <div class="stat-number" style="color:#fff;">{{ $activeOrders }}</div>
        <div class="stat-sub" style="color:rgba(255,255,255,.55);">Open &amp; in progress</div>
        <i class="fas fa-clipboard-list stat-icon" style="color:#fff;"></i>
    </div>

    {{-- Low Stock · solid red (urgent) or solid green (all clear) --}}
    @php $stockUrgent = $lowStockCount > 0; @endphp
    <div class="stat-card" style="background:{{ $stockUrgent ? '#be123c' : '#166534' }};">
        <div class="stat-label" style="color:rgba(255,255,255,.65);">Low Stock</div>
        <div class="stat-number" style="color:#fff;">{{ $lowStockCount }}</div>
        <div class="stat-sub" style="color:rgba(255,255,255,.55);">
            {{ $stockUrgent ? 'Items need restocking' : 'All levels OK' }}
        </div>
        <i class="fas fa-{{ $stockUrgent ? 'exclamation-triangle' : 'check-circle' }} stat-icon" style="color:#fff;"></i>
    </div>

</div>

{{-- ════════════════════════════════
     TWO-COLUMN BODY
════════════════════════════════ --}}
<div style="display:grid;grid-template-columns:1fr 256px;gap:14px;align-items:start;">

    {{-- ── LEFT: Berth grid + Work orders table ── --}}
    <div style="display:flex;flex-direction:column;gap:14px;">

        {{-- Berth Status Grid --}}
        <div class="panel">
            <div class="panel-title">
                <i class="fas fa-water" style="color:#0ea5e9;font-size:12px;"></i>
                Berth Status
                <span style="margin-left:auto;font-size:11px;font-weight:500;color:#94a3b8;">{{ $totalBerths }} berths total</span>
            </div>

            @if(count($berthGrid) > 0)
                <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:14px;">
                    @foreach($berthGrid as $b)
                        @if($b->status === 'occupied')
                            <div class="berth-box" style="background:#1e3a5f;color:#93c5fd;">{{ $b->berth_name }}</div>
                        @else
                            <div class="berth-box" style="background:#e0f2fe;color:#0369a1;">{{ $b->berth_name }}</div>
                        @endif
                    @endforeach
                </div>
                <div style="display:flex;gap:18px;">
                    <div style="display:flex;align-items:center;gap:6px;">
                        <div style="width:10px;height:10px;border-radius:3px;background:#1e3a5f;flex-shrink:0;"></div>
                        <span style="font-size:11px;color:#64748b;">Occupied</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;">
                        <div style="width:10px;height:10px;border-radius:3px;background:#e0f2fe;flex-shrink:0;"></div>
                        <span style="font-size:11px;color:#64748b;">Free</span>
                    </div>
                </div>
            @else
                <p style="font-size:13px;color:#94a3b8;">No berths configured yet.</p>
            @endif
        </div>

        {{-- Active Work Orders Table --}}
        <div class="panel">
            <div class="panel-title">
                <i class="fas fa-clipboard-list" style="color:#f59e0b;font-size:12px;"></i>
                Active Work Orders
                <a href="{{ route('ships.index') }}"
                   style="margin-left:auto;font-size:11px;font-weight:500;color:#2563eb;display:flex;align-items:center;gap:4px;">
                    All ships <i class="fas fa-arrow-right" style="font-size:9px;"></i>
                </a>
            </div>

            @if(count($recentOrders) > 0)
                <div style="overflow-x:auto;">
                    <table class="wo-table">
                        <thead>
                            <tr>
                                <th>Ship</th>
                                <th>Job</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            @php
                                $pill = match($order->status) {
                                    'in_progress' => ['#fffbeb', '#92400e'],
                                    'pending'     => ['#eff6ff', '#1e40af'],
                                    'done'        => ['#dcfce7', '#166534'],
                                    default       => ['#f1f5f9', '#475569'],
                                };
                            @endphp
                            <tr>
                                <td style="font-weight:600;color:#0f172a;">{{ $order->ship_name }}</td>
                                <td style="color:#475569;">{{ $order->title }}</td>
                                <td>
                                    <span class="status-pill" style="background:{{ $pill[0] }};color:{{ $pill[1] }};">
                                        {{ str_replace('_', ' ', $order->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p style="font-size:13px;color:#94a3b8;padding:8px 0;">No active work orders.</p>
            @endif
        </div>

    </div>

    {{-- ── RIGHT: Widgets ── --}}
    <div style="display:flex;flex-direction:column;gap:14px;">

        {{-- Low Stock list --}}
        <div class="panel">
            <div class="panel-title">
                <i class="fas fa-exclamation-triangle" style="color:#f59e0b;font-size:12px;"></i>
                Low Stock
                @if($lowStockCount > 0)
                    <span style="margin-left:auto;font-size:10px;font-weight:700;padding:2px 7px;border-radius:999px;background:#fff1f2;color:#be123c;">{{ $lowStockCount }}</span>
                @endif
            </div>
            @if(count($lowStockMaterials) > 0)
                @foreach($lowStockMaterials as $mat)
                    @php
                        $qtyColor = $mat->quantity <= 0 ? '#be123c' : ($mat->quantity <= 3 ? '#dc2626' : '#d97706');
                    @endphp
                    <div class="stock-item">
                        <span style="font-size:13px;color:#374151;font-weight:500;">{{ $mat->name }}</span>
                        <span style="font-size:12px;font-weight:700;color:{{ $qtyColor }};white-space:nowrap;">
                            {{ $mat->quantity }}{{ $mat->unit ? ' '.$mat->unit : '' }}
                        </span>
                    </div>
                @endforeach
            @else
                <p style="font-size:13px;color:#94a3b8;">All materials in stock.</p>
            @endif
        </div>

        {{-- Workers summary --}}
        <div class="panel">
            <div class="panel-title">
                <i class="fas fa-hard-hat" style="color:#6366f1;font-size:12px;"></i>
                Workers
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px;">
                <div style="text-align:center;padding:14px 8px;background:#f8fafc;border-radius:8px;">
                    <div style="font-size:28px;font-weight:800;color:#0f172a;line-height:1;">{{ $totalWorkers }}</div>
                    <div style="font-size:10px;color:#94a3b8;margin-top:4px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;">Total</div>
                </div>
                <div style="text-align:center;padding:14px 8px;background:#eff6ff;border-radius:8px;">
                    <div style="font-size:28px;font-weight:800;color:#1d4ed8;line-height:1;">{{ $assignedWorkers }}</div>
                    <div style="font-size:10px;color:#93c5fd;margin-top:4px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;">Assigned</div>
                </div>
            </div>
            @php $available = max(0, $totalWorkers - $assignedWorkers); @endphp
            <div style="font-size:12px;color:#64748b;text-align:center;">
                <i class="fas fa-check-circle" style="color:#22c55e;margin-right:4px;"></i>
                {{ $available }} worker{{ $available !== 1 ? 's' : '' }} available
            </div>
        </div>

        {{-- Primary action --}}
        <a href="{{ route('ships.create') }}"
           style="display:flex;align-items:center;justify-content:center;gap:8px;padding:13px;background:#2563eb;color:#fff;border-radius:10px;font-size:13px;font-weight:600;text-align:center;">
            <i class="fas fa-plus"></i> Add New Ship
        </a>

        {{-- Secondary action --}}
        <a href="{{ route('ships.index') }}"
           style="display:flex;align-items:center;justify-content:center;gap:8px;padding:11px;background:#f8fafc;color:#475569;border:1.5px solid #e2e8f0;border-radius:10px;font-size:13px;font-weight:500;text-align:center;">
            <i class="fas fa-list" style="color:#3b82f6;"></i> View All Ships
        </a>

    </div>

</div>

@endsection
