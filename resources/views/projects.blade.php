@extends('app')
@section('title', 'Projects')

@section('content')

<section style="padding:2.5rem 2.5rem 1.5rem;background:#f8fafc;border-bottom:1px solid #e2e8f0;">
    <div style="font-size:11px;font-weight:600;color:#2563eb;text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px;">Work Orders</div>
    <h1 style="font-size:24px;font-weight:700;color:#0f172a;margin-bottom:.3rem;">Projects</h1>
    <p style="font-size:13px;color:#64748b;">All ship repair and maintenance jobs at NavalForge.</p>
</section>

{{-- Ongoing --}}
<section style="padding:2.5rem;background:#fff;">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:1.2rem;">
        <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#f59e0b;flex-shrink:0;"></span>
        <h2 style="font-size:16px;font-weight:700;color:#0f172a;">Ongoing jobs ({{ count($ongoing) }})</h2>
    </div>

    @if(count($ongoing) > 0)
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(290px,1fr));gap:14px;">
        @foreach($ongoing as $job)
        @php
            $badge = match($job->status) {
                'in_progress' => ['#fef3c7','#92400e'],
                'pending'     => ['#eff6ff','#1e40af'],
                default       => ['#f1f5f9','#475569'],
            };
        @endphp
        <div style="border:1px solid #e2e8f0;border-radius:10px;padding:1.2rem;background:#fff;display:flex;flex-direction:column;gap:8px;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
                <div style="font-size:14px;font-weight:600;color:#0f172a;line-height:1.4;">{{ $job->title }}</div>
                <span style="font-size:10px;font-weight:700;padding:3px 9px;border-radius:999px;white-space:nowrap;flex-shrink:0;
                             background:{{ $badge[0] }};color:{{ $badge[1] }};">
                    {{ strtoupper(str_replace('_',' ',$job->status)) }}
                </span>
            </div>

            <div style="display:flex;flex-direction:column;gap:5px;">
                <div style="font-size:12px;color:#374151;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-ship" style="color:#3b82f6;width:13px;"></i>
                    <span>{{ $job->ship_name }}</span>
                    @if($job->ship_type)
                        <span style="color:#94a3b8;">· {{ $job->ship_type }}</span>
                    @endif
                </div>
                @if($job->flag_country)
                <div style="font-size:12px;color:#64748b;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-flag" style="color:#94a3b8;width:13px;"></i> {{ $job->flag_country }}
                </div>
                @endif
                @if($job->berth_name)
                <div style="font-size:12px;color:#64748b;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-warehouse" style="color:#94a3b8;width:13px;"></i> {{ $job->berth_name }}
                </div>
                @endif
                @if($job->assigned_workers > 0)
                <div style="font-size:12px;color:#64748b;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-hard-hat" style="color:#94a3b8;width:13px;"></i> {{ $job->assigned_workers }} worker{{ $job->assigned_workers > 1 ? 's' : '' }} assigned
                </div>
                @endif
            </div>

            @if($job->start_date)
            <div style="margin-top:4px;padding-top:8px;border-top:1px solid #f1f5f9;font-size:11px;color:#94a3b8;">
                <i class="fas fa-calendar-alt" style="margin-right:4px;"></i>
                Started {{ \Carbon\Carbon::parse($job->start_date)->format('d M Y') }}
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <p style="font-size:13px;color:#94a3b8;">No ongoing projects at this time.</p>
    @endif
</section>

{{-- Completed --}}
<section style="padding:2.5rem;background:#f8fafc;border-top:1px solid #e2e8f0;">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:1.2rem;">
        <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#22c55e;flex-shrink:0;"></span>
        <h2 style="font-size:16px;font-weight:700;color:#0f172a;">Completed jobs ({{ count($completed) }})</h2>
    </div>

    @if(count($completed) > 0)
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(290px,1fr));gap:14px;">
        @foreach($completed as $job)
        <div style="border:1px solid #e2e8f0;border-radius:10px;padding:1.2rem;background:#fff;display:flex;flex-direction:column;gap:8px;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
                <div style="font-size:14px;font-weight:600;color:#0f172a;line-height:1.4;">{{ $job->title }}</div>
                <span style="font-size:10px;font-weight:700;padding:3px 9px;border-radius:999px;background:#dcfce7;color:#166534;white-space:nowrap;flex-shrink:0;">DONE</span>
            </div>

            <div style="display:flex;flex-direction:column;gap:5px;">
                <div style="font-size:12px;color:#374151;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-ship" style="color:#3b82f6;width:13px;"></i>
                    <span>{{ $job->ship_name }}</span>
                    @if($job->ship_type)
                        <span style="color:#94a3b8;">· {{ $job->ship_type }}</span>
                    @endif
                </div>
                @if($job->flag_country)
                <div style="font-size:12px;color:#64748b;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-flag" style="color:#94a3b8;width:13px;"></i> {{ $job->flag_country }}
                </div>
                @endif
                @if($job->days_taken !== null)
                <div style="font-size:12px;color:#64748b;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-clock" style="color:#94a3b8;width:13px;"></i> {{ round($job->days_taken) }} day(s) to complete
                </div>
                @endif
            </div>

            @if($job->end_date)
            <div style="margin-top:4px;padding-top:8px;border-top:1px solid #f1f5f9;font-size:11px;color:#94a3b8;display:flex;align-items:center;gap:5px;">
                <i class="fas fa-check-circle" style="color:#22c55e;"></i>
                Completed {{ \Carbon\Carbon::parse($job->end_date)->format('d M Y') }}
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <p style="font-size:13px;color:#94a3b8;">No completed projects yet.</p>
    @endif
</section>

@endsection
