@extends('layouts.admin')
@section('title', 'Work Order #' . $order->order_id)
@section('page-title', 'Work Order Detail')
@section('breadcrumb', 'NavalForge / Work Orders / #' . $order->order_id)

@push('styles')
<style>
:root {
    --amber-bg: #fef3c7; --amber-fg: #92400e;
    --green-bg: #dcfce7; --green-fg: #15803d;
    --blue-bg:  #dbeafe; --blue-fg:  #1e40af;
    --gray-bg:  #f1f5f9; --gray-fg:  #475569;
    --divider:  #edf0f7;
}

.sh-back {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 600; color: #94a3b8;
    text-decoration: none; margin-bottom: 20px;
}
.sh-back:hover { color: #475569; }

/* Top bar */
.sh-topbar {
    display: flex; align-items: flex-start;
    justify-content: space-between; gap: 16px;
    margin-bottom: 22px; flex-wrap: wrap;
}
.sh-order-id { font-size: 11px; font-weight: 700; color: #94a3b8; letter-spacing: .06em; margin-bottom: 4px; }
.sh-title { font-size: 20px; font-weight: 700; color: #0f172a; letter-spacing: -.01em; }
.sh-meta { font-size: 13px; color: #64748b; margin-top: 5px; }

.sh-actions { display: flex; gap: 8px; align-items: center; flex-shrink: 0; flex-wrap: wrap; }
.sh-btn {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 13px; font-weight: 600; padding: 9px 16px;
    border-radius: 8px; border: 1.5px solid #e2e8f0;
    background: #fff; color: #475569;
    cursor: pointer; text-decoration: none; transition: background .12s;
}
.sh-btn:hover { background: #f8fafc; color: #475569; }

/* Status inline form */
.sh-status-form { display: inline-flex; align-items: center; gap: 6px; }
.sh-status-select {
    font-size: 13px; font-weight: 500; padding: 8px 10px;
    border: 1.5px solid #e2e8f0; border-radius: 8px;
    background: #fff; color: #0f172a; outline: none; cursor: pointer;
}
.sh-status-select:focus { border-color: #2563eb; }
.sh-status-btn {
    font-size: 13px; font-weight: 600; padding: 9px 16px;
    background: #2563eb; color: #fff; border: none;
    border-radius: 8px; cursor: pointer; transition: opacity .15s;
}
.sh-status-btn:hover { opacity: .88; }

/* Badge */
.wo-badge {
    display: inline-flex; align-items: center;
    font-size: 11.5px; font-weight: 600;
    padding: 4px 11px; border-radius: 999px; white-space: nowrap;
}
.badge-amber { background: var(--amber-bg); color: var(--amber-fg); }
.badge-green { background: var(--green-bg); color: var(--green-fg); }
.badge-blue  { background: var(--blue-bg);  color: var(--blue-fg);  }
.badge-gray  { background: var(--gray-bg);  color: var(--gray-fg);  }

/* Cards */
.sh-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 16px;
    margin-bottom: 16px;
}
@media(max-width: 820px) { .sh-grid { grid-template-columns: 1fr; } }

.sh-card {
    background: #fff; border-radius: 12px;
    border: 1px solid #e2e8f0; overflow: hidden;
}
.sh-card-full {
    background: #fff; border-radius: 12px;
    border: 1px solid #e2e8f0; overflow: hidden;
    margin-bottom: 16px;
}
.sh-card-hd {
    padding: 12px 18px;
    border-bottom: 1.5px solid var(--divider);
    font-size: 10px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .09em; color: #94a3b8;
}
.sh-card-body { padding: 16px 18px; }

/* Info fields */
.sh-fields { display: grid; grid-template-columns: 1fr 1fr; gap: 14px 24px; }
.sh-field-lbl {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .06em; color: #94a3b8; margin-bottom: 3px;
}
.sh-field-val { font-size: 13px; font-weight: 600; color: #1e293b; }

/* Description */
.sh-desc { font-size: 13px; color: #475569; line-height: 1.65; white-space: pre-wrap; }
.sh-none { font-size: 13px; color: #94a3b8; font-style: italic; }

/* Tables */
.sh-table { width: 100%; border-collapse: collapse; }
.sh-table thead th {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .08em; color: #94a3b8;
    padding: 10px 18px 8px; text-align: left;
    border-bottom: 1.5px solid var(--divider);
}
.sh-table tbody td {
    padding: 12px 18px; font-size: 13px; color: #475569;
    border-bottom: 1px solid var(--divider); vertical-align: middle;
}
.sh-table tbody tr:last-child td { border-bottom: none; }
.sh-table tbody td.td-name { font-weight: 600; color: #1e293b; }
.sh-role-pill {
    display: inline-block; font-size: 10px; font-weight: 600;
    padding: 2px 8px; background: #e0e7ff; color: #4338ca;
    border-radius: 999px; letter-spacing: .02em;
}

.sh-flash {
    margin-bottom: 16px; padding: 11px 16px;
    background: #dcfce7; border: 1px solid #bbf7d0;
    border-radius: 8px; font-size: 13px; color: #15803d; font-weight: 500;
}
</style>
@endpush

@section('content')

@if(session('success'))
<div class="sh-flash">{{ session('success') }}</div>
@endif

<a href="{{ route('work-orders.index') }}" class="sh-back">
    <svg width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
        <path d="M8 2L3 6.5 8 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    Back to work orders
</a>

{{-- Top bar --}}
<div class="sh-topbar">
    <div>
        <div class="sh-order-id">WORK ORDER #{{ $order->order_id }}</div>
        <div class="sh-title">{{ $order->title }}</div>
        <div class="sh-meta">
            {{ $order->ship_name }}
            @if($order->berth_name !== '—') · {{ $order->berth_name }}@endif
            · {{ $order->start_date }} → {{ $order->end_date }}
        </div>
    </div>
    <div class="sh-actions">
        <a href="{{ route('work-orders.edit', $order->order_id) }}" class="sh-btn">
            <svg width="13" height="13" viewBox="0 0 15 15" fill="none" aria-hidden="true">
                <path d="M10.5 2.5l2 2L5 12H3v-2l7.5-7.5z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Edit
        </a>
        <form method="POST" action="{{ route('work-orders.status', $order->order_id) }}" class="sh-status-form">
            @csrf @method('PATCH')
            <select name="status" class="sh-status-select">
                <option value="pending"     {{ $order->status === 'pending'     ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ $order->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="done"        {{ $order->status === 'done'        ? 'selected' : '' }}>Done</option>
            </select>
            <button type="submit" class="sh-status-btn">Update status</button>
        </form>
    </div>
</div>

{{-- Info + Description --}}
<div class="sh-grid">
    <div class="sh-card">
        <div class="sh-card-hd">Order information</div>
        <div class="sh-card-body">
            <div class="sh-fields">
                <div>
                    <div class="sh-field-lbl">Status</div>
                    <div style="margin-top:4px;">
                        @php
                            $badgeClass = match($order->status) {
                                'in_progress' => 'badge-amber',
                                'done'        => 'badge-green',
                                default       => 'badge-blue',
                            };
                            $badgeLabel = match($order->status) {
                                'in_progress' => 'In progress',
                                'done'        => 'Completed',
                                default       => 'Pending',
                            };
                        @endphp
                        <span class="wo-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                    </div>
                </div>
                <div>
                    <div class="sh-field-lbl">Ship</div>
                    <div class="sh-field-val">{{ $order->ship_name }}</div>
                </div>
                <div>
                    <div class="sh-field-lbl">Berth</div>
                    <div class="sh-field-val">{{ $order->berth_name }}</div>
                </div>
                <div>
                    <div class="sh-field-lbl">Ship type</div>
                    <div class="sh-field-val">{{ $order->ship_type ?? '—' }}</div>
                </div>
                <div>
                    <div class="sh-field-lbl">Start date</div>
                    <div class="sh-field-val">{{ $order->start_date }}</div>
                </div>
                <div>
                    <div class="sh-field-lbl">End date</div>
                    <div class="sh-field-val">{{ $order->end_date }}</div>
                </div>
                <div>
                    <div class="sh-field-lbl">Flag</div>
                    <div class="sh-field-val">{{ $order->flag ?? '—' }}</div>
                </div>
                <div>
                    <div class="sh-field-lbl">Created</div>
                    <div class="sh-field-val">{{ $order->created_at }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="sh-card">
        <div class="sh-card-hd">Job description</div>
        <div class="sh-card-body">
            @if($order->description)
                <div class="sh-desc">{{ $order->description }}</div>
            @else
                <div class="sh-none">No description provided.</div>
            @endif
        </div>
    </div>
</div>

{{-- Workers --}}
<div class="sh-card-full">
    <div class="sh-card-hd">Assigned workers ({{ count($workers) }})</div>
    @if(count($workers))
    <table class="sh-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody>
            @foreach($workers as $w)
            <tr>
                <td class="td-name">{{ $w->name }}</td>
                <td>@if($w->role)<span class="sh-role-pill">{{ $w->role }}</span>@else —@endif</td>
                <td>{{ $w->phone ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <div class="sh-card-body"><div class="sh-none">No workers assigned to this order.</div></div>
    @endif
</div>

{{-- Materials --}}
<div class="sh-card-full">
    <div class="sh-card-hd">Materials used ({{ count($materials) }})</div>
    @if(count($materials))
    <table class="sh-table">
        <thead>
            <tr>
                <th>Material</th>
                <th>Unit</th>
                <th style="text-align:right;padding-right:18px;">Qty used</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materials as $m)
            <tr>
                <td class="td-name">{{ $m->name }}</td>
                <td>{{ $m->unit ?? '—' }}</td>
                <td style="text-align:right;padding-right:18px;font-variant-numeric:tabular-nums;">{{ $m->qty_used }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <div class="sh-card-body"><div class="sh-none">No materials recorded for this order.</div></div>
    @endif
</div>

@endsection
