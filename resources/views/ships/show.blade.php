@extends('layouts.admin')
@section('title', $ship->ship_name)
@section('page-title', $ship->ship_name)
@section('breadcrumb', 'NavalForge / Ships / ' . $ship->ship_name)

@section('content')

<div style="margin-bottom:1.2rem;">
    <a href="{{ route('ships.index') }}" style="font-size:13px;color:#64748b;display:inline-flex;align-items:center;gap:5px;">
        <i class="fas fa-arrow-left" style="font-size:10px;"></i> Back to Ships
    </a>
</div>

{{-- Ship info card --}}
<div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:24px;margin-bottom:20px;display:grid;grid-template-columns:repeat(4,1fr);gap:20px;">
    <div>
        <div style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;margin-bottom:4px;">Ship name</div>
        <div style="font-size:15px;font-weight:700;color:#0f172a;">{{ $ship->ship_name }}</div>
    </div>
    <div>
        <div style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;margin-bottom:4px;">Type</div>
        <div style="font-size:14px;color:#374151;">{{ $ship->ship_type ?? '—' }}</div>
    </div>
    <div>
        <div style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;margin-bottom:4px;">Owner</div>
        <div style="font-size:14px;color:#374151;">{{ $ship->owner_name ?? '—' }}</div>
    </div>
    <div>
        <div style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;margin-bottom:4px;">Flag</div>
        <div style="font-size:14px;color:#374151;">{{ $ship->flag_country ?? '—' }}</div>
    </div>
    <div>
        <div style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;margin-bottom:4px;">Tonnage</div>
        <div style="font-size:14px;color:#374151;">{{ $ship->tonnage ? number_format($ship->tonnage) . ' t' : '—' }}</div>
    </div>
    <div>
        <div style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;margin-bottom:4px;">Berth</div>
        <div style="font-size:14px;color:#374151;">{{ $ship->berth_name }}</div>
    </div>
    <div>
        <div style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;margin-bottom:4px;">Status</div>
        @php
            $sc = match($ship->status) {
                'docked'    => ['#eff6ff','#1e40af'],
                'in_repair' => ['#fef3c7','#92400e'],
                'departed'  => ['#f1f5f9','#475569'],
                default     => ['#f1f5f9','#475569'],
            };
        @endphp
        <span style="display:inline-block;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:600;background:{{ $sc[0] }};color:{{ $sc[1] }};">
            {{ str_replace('_', ' ', $ship->status) }}
        </span>
    </div>
    <div>
        <div style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;margin-bottom:4px;">Arrived</div>
        <div style="font-size:14px;color:#374151;">{{ $ship->arrival_date ?? '—' }}</div>
    </div>
</div>

{{-- Work order history --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
    <div style="font-size:15px;font-weight:700;color:#0f172a;">Work Order History</div>
    <span style="font-size:12px;color:#64748b;">{{ count($workOrders) }} total</span>
</div>

<div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
    @if(count($workOrders) > 0)
    <table style="width:100%;border-collapse:collapse;font-size:13px;">
        <thead>
            <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                <th style="padding:11px 16px;text-align:left;font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">Job title</th>
                <th style="padding:11px 16px;text-align:left;font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">Status</th>
                <th style="padding:11px 16px;text-align:left;font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">Priority</th>
                <th style="padding:11px 16px;text-align:left;font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">Start</th>
                <th style="padding:11px 16px;text-align:left;font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">End</th>
                <th style="padding:11px 16px;"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($workOrders as $wo)
            @php
                $badge = match($wo->status) {
                    'done'        => ['#dcfce7','#15803d','Completed'],
                    'in_progress' => ['#fef3c7','#92400e','In progress'],
                    'pending'     => ['#dbeafe','#1e40af','Pending'],
                    default       => ['#f1f5f9','#475569', ucfirst($wo->status)],
                };
            @endphp
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:12px 16px;font-weight:500;color:#0f172a;">{{ $wo->title }}</td>
                <td style="padding:12px 16px;">
                    <span style="display:inline-block;padding:2px 9px;border-radius:999px;font-size:11px;font-weight:600;background:{{ $badge[0] }};color:{{ $badge[1] }};">
                        {{ $badge[2] }}
                    </span>
                </td>
                <td style="padding:12px 16px;">
                    @if($wo->priority === 'urgent')
                        <span style="font-size:11px;font-weight:700;color:#dc2626;text-transform:uppercase;">Urgent</span>
                    @else
                        <span style="font-size:11px;color:#94a3b8;">Normal</span>
                    @endif
                </td>
                <td style="padding:12px 16px;color:#64748b;font-size:12px;">{{ $wo->start_date ?? '—' }}</td>
                <td style="padding:12px 16px;color:#64748b;font-size:12px;">{{ $wo->end_date ?? '—' }}</td>
                <td style="padding:12px 16px;text-align:right;">
                    <a href="{{ route('work-orders.show', $wo->order_id) }}"
                       style="font-size:12px;font-weight:500;color:#2563eb;">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="padding:3rem;text-align:center;color:#94a3b8;">
        <i class="fas fa-clipboard-list" style="font-size:28px;margin-bottom:10px;display:block;opacity:.3;"></i>
        No work orders for this ship yet.
    </div>
    @endif
</div>

@endsection
