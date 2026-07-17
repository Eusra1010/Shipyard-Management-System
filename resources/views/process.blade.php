@extends('app')
@section('title', 'Our Process')

@section('content')
<div style="padding: 64px 2rem 72px; background: #fff;">

    {{-- ── Section header ── --}}
    <div style="text-align:center; margin-bottom: 52px;">
        <div style="
            display: inline-block;
            font-size: 10px; font-weight: 800;
            text-transform: uppercase; letter-spacing: .14em;
            color: #2563eb; margin-bottom: 12px;">
            Our Process
        </div>
        <h1 style="
            font-size: 28px; font-weight: 800;
            color: #0f172a; letter-spacing: -.02em;
            line-height: 1.2; margin: 0 0 14px;">
            How we handle every vessel
        </h1>
        <p style="
            font-size: 14px; color: #64748b; line-height: 1.6;
            max-width: 420px; margin: 0 auto;">
            A structured five-stage workflow — from first inspection to final departure — applied consistently to every ship we service.
        </p>
    </div>

    {{-- ── Process diagram ── --}}
    <div class="proc-outer">

        @php
        $steps = [
            ['n' => 1, 'label' => 'Inspection',               'desc' => 'Vessel docked and assessed for required repairs'],
            ['n' => 2, 'label' => 'Work order created',        'desc' => 'Job scope logged and linked to the vessel\'s berth'],
            ['n' => 3, 'label' => 'Team assigned',             'desc' => 'Specialist workers assigned based on trade and availability'],
            ['n' => 4, 'label' => 'Work in progress',          'desc' => 'Materials logged and consumed as repairs proceed'],
            ['n' => 5, 'label' => 'Quality check &amp; release', 'desc' => 'Job closed, berth freed, vessel cleared for departure'],
        ];
        @endphp

        <div class="proc-track">
            <div class="proc-line" aria-hidden="true"></div>
            @foreach($steps as $step)
            <div class="proc-step">
                <div class="proc-circle">{{ $step['n'] }}</div>
                <div class="proc-label">{!! $step['label'] !!}</div>
                <div class="proc-desc">{{ $step['desc'] }}</div>
            </div>
            @endforeach
        </div>

    </div>

</div>

<style>
.proc-outer {
    max-width: 860px;
    margin: 0 auto;
}

/* ── Horizontal layout ── */
.proc-track {
    position: relative;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0;
}

/* The connecting line behind all circles */
.proc-line {
    position: absolute;
    top: 18px; /* half of 36px circle */
    left: calc(50% / 5);
    right: calc(50% / 5);
    height: 1.5px;
    background: #cbd5e1;
    z-index: 0;
}

/* Individual step */
.proc-step {
    position: relative;
    z-index: 1;
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 0 8px;
}

/* Circle */
.proc-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #0f172a;
    color: #fff;
    font-size: 13px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-bottom: 14px;
    position: relative;
    /* white ring so the connecting line is cleanly interrupted */
    box-shadow: 0 0 0 4px #fff;
}

.proc-label {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.3;
    margin-bottom: 6px;
}

.proc-desc {
    font-size: 11px;
    color: #64748b;
    line-height: 1.5;
    max-width: 130px;
}

/* ── Responsive: vertical stack ── */
@media (max-width: 680px) {
    .proc-track {
        flex-direction: column;
        align-items: flex-start;
        gap: 0;
    }

    .proc-line {
        /* vertical line running down the left side */
        top: calc(50% / 5);
        bottom: calc(50% / 5);
        left: 17px;     /* center of 36px circle */
        right: auto;
        width: 1.5px;
        height: auto;
    }

    .proc-step {
        flex-direction: row;
        align-items: flex-start;
        text-align: left;
        padding: 0 0 32px 0;
        gap: 16px;
        flex: none;
        width: 100%;
    }

    .proc-step:last-child {
        padding-bottom: 0;
    }

    .proc-circle {
        margin-bottom: 0;
        flex-shrink: 0;
    }

    .proc-desc {
        max-width: none;
    }
}
</style>

@endsection
