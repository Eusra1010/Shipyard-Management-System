@extends('app')
@section('title', 'Our Team')

@section('content')

@php
/*
 * ── Image instructions ────────────────────────────────────────────────
 * Put each member's photo in:  public/images/team/{image}.jpg
 * e.g.  public/images/team/chairman.jpg
 *       public/images/team/managing-director.jpg
 * Until the file exists the gradient + initials placeholder is shown.
 * ─────────────────────────────────────────────────────────────────────
 */
$board = [
    [
        'initials'    => 'MA',
        'name'        => 'Rear Adm. (Retd.) M. Akhtar',
        'designation' => 'Chairman',
        'expertise'   => 'Naval Strategy & Corporate Governance',
        'bio'         => 'Over 35 years of distinguished naval service, providing strategic direction and institutional oversight across NavalForge.',
        'image'       => 'chairman',
        'gradient'    => 'linear-gradient(160deg,#060d1a 0%,#0f2952 60%,#1a3d70 100%)',
    ],
    [
        'initials'    => 'SA',
        'name'        => 'Engr. Salahuddin Ahmed',
        'designation' => 'Managing Director',
        'expertise'   => 'Ship Engineering & Operations Management',
        'bio'         => 'Leads day-to-day operations, business development, and strategic partnerships across the entire facility.',
        'image'       => 'managing-director',
        'gradient'    => 'linear-gradient(160deg,#0c1a3a 0%,#1d4ed8 100%)',
    ],
    [
        'initials'    => 'NI',
        'name'        => 'Capt. (Retd.) Nurul Islam',
        'designation' => 'Director, Operations',
        'expertise'   => 'Marine Operations & Port Logistics',
        'bio'         => 'Brings 28 years of maritime expertise in vessel operations, berth management, and inter-port coordination.',
        'image'       => 'director-operations',
        'gradient'    => 'linear-gradient(160deg,#031a14 0%,#065f46 100%)',
    ],
    [
        'initials'    => 'FH',
        'name'        => 'Mr. Farhad Hossain',
        'designation' => 'Director, Finance',
        'expertise'   => 'Corporate Finance & Risk Management',
        'bio'         => 'Oversees financial planning, audit compliance, and institutional investment strategy for the organisation.',
        'image'       => 'director-finance',
        'gradient'    => 'linear-gradient(160deg,#130020 0%,#4c1d95 100%)',
    ],
    [
        'initials'    => 'RK',
        'name'        => 'Engr. Rashidul Karim',
        'designation' => 'Technical Director',
        'expertise'   => 'Mechanical & Structural Engineering',
        'bio'         => 'Leads all technical operations, quality assurance, and engineering standards across the repair facility.',
        'image'       => 'director-technical',
        'gradient'    => 'linear-gradient(160deg,#1a0800 0%,#7c2d12 100%)',
    ],
    [
        'initials'    => 'NR',
        'name'        => 'Ms. Nazia Rahman',
        'designation' => 'Director, HR & Administration',
        'expertise'   => 'Human Resource Development',
        'bio'         => 'Responsible for workforce development, welfare programmes, and administrative systems across 200+ staff.',
        'image'       => 'director-hr',
        'gradient'    => 'linear-gradient(160deg,#0a0a18 0%,#1e1b4b 100%)',
    ],
];
@endphp

{{-- ── Hero ── --}}
<section style="background:#0f172a;padding:80px 2.5rem 64px;">
    <div style="max-width:960px;margin:0 auto;">
        <div style="font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#1d4ed8;margin-bottom:16px;">
            Leadership
        </div>
        <h1 style="font-size:clamp(32px,5vw,50px);font-weight:800;color:#f1f5f9;line-height:1.1;letter-spacing:-.025em;margin-bottom:16px;">
            Board of Directors
        </h1>
        <p style="font-size:15px;color:#94a3b8;max-width:520px;line-height:1.7;margin:0;">
            NavalForge is guided by a board of seasoned maritime professionals, naval officers, and industry leaders with a combined experience of over 150 years.
        </p>
    </div>
</section>

{{-- ── Thin accent bar ── --}}
<div style="height:4px;background:linear-gradient(90deg,#1d4ed8 0%,#0ea5e9 50%,#1d4ed8 100%);"></div>

{{-- ── Board of Directors ── --}}
<section style="background:#f8fafc;padding:64px 2.5rem 80px;border-bottom:1px solid #e2e8f0;">
    <div style="max-width:960px;margin:0 auto;">

        <div style="margin-bottom:40px;">
            <div style="font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#1d4ed8;margin-bottom:8px;">
                Our leadership
            </div>
            <h2 style="font-size:22px;font-weight:800;color:#0f172a;">Meet the board</h2>
        </div>

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;">
            @foreach($board as $member)
            <div class="tm-card">

                {{-- Photo / placeholder ── --}}
                <div class="tm-photo" style="background:{{ $member['gradient'] }};">
                    <div class="tm-initials">{{ $member['initials'] }}</div>
                    <img src="/images/team/{{ $member['image'] }}.jpg"
                         alt="{{ $member['name'] }}"
                         class="tm-img"
                         onerror="this.style.display='none'">
                </div>

                {{-- Card body ── --}}
                <div style="padding:20px 22px 24px;">
                    <div style="font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#1d4ed8;margin-bottom:6px;">
                        {{ $member['designation'] }}
                    </div>
                    <div style="font-size:16px;font-weight:800;color:#0f172a;line-height:1.3;margin-bottom:12px;">
                        {{ $member['name'] }}
                    </div>
                    <div style="height:1px;background:#f1f5f9;margin-bottom:12px;"></div>
                    <div style="display:flex;align-items:flex-start;gap:8px;margin-bottom:12px;">
                        <i class="fas fa-medal" style="font-size:11px;color:#1d4ed8;margin-top:2px;flex-shrink:0;"></i>
                        <span style="font-size:12px;font-weight:600;color:#475569;">{{ $member['expertise'] }}</span>
                    </div>
                    <p style="font-size:12px;color:#64748b;line-height:1.75;margin:0;">
                        {{ $member['bio'] }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Org values strip ── --}}
<section style="background:#fff;padding:56px 2.5rem;border-bottom:1px solid #e2e8f0;">
    <div style="max-width:960px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:36px;">
            <div style="font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#1d4ed8;margin-bottom:8px;">Our principles</div>
            <h2 style="font-size:20px;font-weight:800;color:#0f172a;">What drives our leadership</h2>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;">
            <div style="padding:1.4rem;border:1px solid #e2e8f0;border-radius:10px;text-align:center;">
                <i class="fas fa-shield-alt" style="font-size:22px;color:#1d4ed8;margin-bottom:10px;display:block;"></i>
                <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">Integrity</div>
                <div style="font-size:12px;color:#64748b;line-height:1.6;">Transparent governance at every level of the organisation.</div>
            </div>
            <div style="padding:1.4rem;border:1px solid #e2e8f0;border-radius:10px;text-align:center;">
                <i class="fas fa-star" style="font-size:22px;color:#1d4ed8;margin-bottom:10px;display:block;"></i>
                <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">Excellence</div>
                <div style="font-size:12px;color:#64748b;line-height:1.6;">ISO-certified processes and internationally recognised standards.</div>
            </div>
            <div style="padding:1.4rem;border:1px solid #e2e8f0;border-radius:10px;text-align:center;">
                <i class="fas fa-handshake" style="font-size:22px;color:#1d4ed8;margin-bottom:10px;display:block;"></i>
                <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">Partnership</div>
                <div style="font-size:12px;color:#64748b;line-height:1.6;">Long-term relationships with maritime operators and port authorities.</div>
            </div>
            <div style="padding:1.4rem;border:1px solid #e2e8f0;border-radius:10px;text-align:center;">
                <i class="fas fa-hard-hat" style="font-size:22px;color:#1d4ed8;margin-bottom:10px;display:block;"></i>
                <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">Safety</div>
                <div style="font-size:12px;color:#64748b;line-height:1.6;">Zero-compromise safety culture across all berths and workshops.</div>
            </div>
        </div>
    </div>
</section>

<style>
/* ── Team cards ── */
.tm-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    transition: box-shadow .2s, transform .2s;
}
.tm-card:hover {
    box-shadow: 0 8px 28px rgba(15,23,42,.10);
    transform: translateY(-3px);
}

/* ── Photo placeholder ── */
.tm-photo {
    height: 230px;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}
.tm-initials {
    font-size: 52px;
    font-weight: 800;
    color: rgba(255,255,255,.18);
    letter-spacing: -.02em;
    user-select: none;
    pointer-events: none;
}
.tm-img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top center;
    transition: transform .4s ease;
}
.tm-card:hover .tm-img { transform: scale(1.04); }

@media (max-width: 768px) {
    div[style*="repeat(3,1fr)"] { grid-template-columns: 1fr 1fr !important; }
    div[style*="repeat(4,1fr)"] { grid-template-columns: 1fr 1fr !important; }
}
@media (max-width: 480px) {
    div[style*="repeat(3,1fr)"] { grid-template-columns: 1fr !important; }
    div[style*="repeat(4,1fr)"] { grid-template-columns: 1fr 1fr !important; }
}
</style>

@endsection
