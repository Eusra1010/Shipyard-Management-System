@extends('layouts.admin')
@section('title', 'Ships')
@section('page-title', 'Ships')
@section('breadcrumb', 'NavalForge / Ships')
@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
    <div>
        <div style="font-size:11px;font-weight:600;color:#2563eb;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px;">Registry</div>
        <h1 style="font-size:22px;font-weight:700;color:#0f172a;">Ships</h1>
    </div>
    <a href="{{ route('ships.create') }}"
       style="display:inline-flex;align-items:center;gap:7px;font-size:13px;font-weight:600;padding:9px 20px;background:#2563eb;color:#fff;border-radius:8px;border:1.5px solid #1d4ed8;">
        <i class="fas fa-plus"></i> Register ship
    </a>
</div>

@if(session('success'))
<div style="margin-bottom:1.2rem;padding:12px 16px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;font-size:13px;color:#166534;display:flex;align-items:center;gap:8px;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;font-size:13px;">
        <thead>
            <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">Ship</th>
                <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">Owner</th>
                <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">Type / Flag</th>
                <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">Tonnage</th>
                <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">Berth</th>
                <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">Jobs</th>
                <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">Status</th>
                <th style="padding:12px 16px;text-align:left;font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">Arrived</th>
                <th style="padding:12px 16px;"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($ships as $ship)
            @php
                $statusColor = match($ship->status) {
                    'docked'    => ['bg'=>'#eff6ff','text'=>'#1e40af'],
                    'in_repair' => ['bg'=>'#fef3c7','text'=>'#92400e'],
                    'departed'  => ['bg'=>'#f1f5f9','text'=>'#475569'],
                    default     => ['bg'=>'#f1f5f9','text'=>'#475569'],
                };
            @endphp
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:13px 16px;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:34px;height:34px;border-radius:8px;background:#eff6ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-ship" style="font-size:14px;color:#2563eb;"></i>
                        </div>
                        <div>
                            <div style="font-weight:600;color:#0f172a;">{{ $ship->ship_name }}</div>
                            <div style="font-size:11px;color:#94a3b8;">#{{ $ship->ship_id }}</div>
                        </div>
                    </div>
                </td>
                <td style="padding:13px 16px;color:#374151;">{{ $ship->owner_name ?? '—' }}</td>
                <td style="padding:13px 16px;">
                    <div style="color:#374151;">{{ $ship->ship_type ?? '—' }}</div>
                    <div style="font-size:11px;color:#94a3b8;">{{ $ship->flag_country ?? '' }}</div>
                </td>
                <td style="padding:13px 16px;color:#374151;">
                    {{ $ship->tonnage ? number_format($ship->tonnage) . ' t' : '—' }}
                </td>
                <td style="padding:13px 16px;color:#374151;">{{ $ship->berth_name ?? '—' }}</td>
                <td style="padding:13px 16px;">
                    @if($ship->active_jobs > 0)
                        <span style="display:inline-flex;align-items:center;gap:4px;font-size:12px;font-weight:600;color:#b45309;">
                            <i class="fas fa-tools" style="font-size:10px;"></i> {{ $ship->active_jobs }}
                        </span>
                    @else
                        <span style="font-size:12px;color:#94a3b8;">—</span>
                    @endif
                </td>
                <td style="padding:13px 16px;">
                    <span style="display:inline-block;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:600;
                                 background:{{ $statusColor['bg'] }};color:{{ $statusColor['text'] }};">
                        {{ str_replace('_', ' ', $ship->status) }}
                    </span>
                </td>
                <td style="padding:13px 16px;color:#64748b;font-size:12px;">
                    {{ $ship->arrival_date ? \Carbon\Carbon::parse($ship->arrival_date)->format('d M Y') : '—' }}
                </td>
                <td style="padding:13px 16px;text-align:right;white-space:nowrap;">
                    <a href="{{ route('ships.edit', $ship->ship_id) }}"
                       style="font-size:12px;font-weight:500;color:#2563eb;margin-right:14px;">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('ships.destroy', $ship->ship_id) }}"
                          style="display:inline;"
                          onsubmit="return confirm('Delete {{ addslashes($ship->ship_name) }}? This will also remove all its work orders.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                style="font-size:12px;font-weight:500;color:#dc2626;background:none;border:none;cursor:pointer;padding:0;">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="padding:3rem;text-align:center;color:#94a3b8;">
                    <i class="fas fa-ship" style="font-size:28px;margin-bottom:10px;display:block;opacity:.3;"></i>
                    No ships registered yet.
                    <a href="{{ route('ships.create') }}" style="color:#2563eb;font-weight:600;">Register the first one.</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
