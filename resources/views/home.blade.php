@extends('app')
@section('title', 'Home')

@section('content')

{{-- ════════════════════════════════
     CAROUSEL HERO
     To use real photos:
       1. Put images in public/images/gallery/ (e.g. yard.jpg, drydock.jpg …)
       2. On each .cs-slide, add:  style="background-image:url('/images/gallery/yard.jpg')"
          and remove the gradient background style from that slide.
════════════════════════════════ --}}
<style>
/* ── Carousel shell ── */
.cs-wrap{position:relative;width:100%;height:520px;overflow:hidden;background:#09111f;user-select:none;}

/* ── Track & slides ── */
.cs-track{display:flex;height:100%;transition:transform .75s cubic-bezier(.77,0,.18,1);will-change:transform;}
.cs-slide{
    position:relative;min-width:100%;height:100%;overflow:hidden;
    /* real-image fallback: background-size:cover; background-position:center; */
}

/* Decorative ambient glow per slide — sits under the icon */
.cs-slide::before{
    content:'';position:absolute;inset:0;
    background:inherit;  /* pulled from inline style */
    filter:blur(0);
}

/* Large faded icon — visual identity when no photo is loaded */
.cs-icon{
    position:absolute;inset:0;display:flex;align-items:center;justify-content:center;
    font-size:clamp(80px,18vw,160px);color:rgba(255,255,255,.055);
    pointer-events:none;z-index:2;
}

/* ── Dark overlay for real photos (also softens gradient slides) ── */
.cs-overlay{
    position:absolute;inset:0;
    background:linear-gradient(to top,rgba(5,12,25,.82) 0%,rgba(5,12,25,.3) 50%,rgba(5,12,25,.12) 100%);
    z-index:1;pointer-events:none;
}

/* ── Bottom gradient fade (keeps dots legible) ── */
.cs-fade{
    position:absolute;bottom:0;left:0;right:0;height:110px;
    background:linear-gradient(to top,rgba(9,17,31,.9) 0%,transparent 100%);
    z-index:2;pointer-events:none;
}

/* ── Slide title & subtitle ── */
.cs-title{
    position:absolute;bottom:62px;left:40px;right:130px;z-index:3;
    opacity:0;transform:translateY(16px);
    transition:opacity .6s ease .08s,transform .6s ease .08s;
    pointer-events:none;
}
.cs-title.active{opacity:1;transform:translateY(0);}
.cs-title-h{
    font-size:clamp(18px,3.2vw,34px);font-weight:800;color:#f1f5f9;
    line-height:1.22;margin-bottom:7px;
    text-shadow:0 2px 14px rgba(0,0,0,.75);
}
.cs-title-sub{
    font-size:clamp(11px,1.4vw,13px);color:#b0bfd4;
    letter-spacing:.04em;line-height:1.5;
    text-shadow:0 1px 8px rgba(0,0,0,.9);
}

/* ── Badge ── */
.cs-badge{
    position:absolute;top:20px;left:20px;z-index:3;
    font-size:10px;font-weight:700;letter-spacing:.16em;color:#e2e8f0;
    background:rgba(9,17,31,.62);border:1px solid rgba(255,255,255,.14);
    padding:5px 13px;border-radius:4px;
    backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);
    text-transform:uppercase;
}

/* ── Arrows ── */
.cs-arrow{
    position:absolute;top:50%;transform:translateY(-50%);z-index:4;
    width:46px;height:46px;border-radius:50%;
    background:rgba(9,17,31,.58);border:1px solid rgba(255,255,255,.14);
    color:#fff;font-size:15px;cursor:pointer;
    display:flex;align-items:center;justify-content:center;
    backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);
    transition:background .2s,transform .2s;
    outline:none;
}
.cs-arrow:hover{background:rgba(9,17,31,.88);transform:translateY(-50%) scale(1.08);}
.cs-prev{left:20px;}
.cs-next{right:20px;}

/* ── Dots ── */
.cs-dots{
    position:absolute;bottom:20px;left:50%;transform:translateX(-50%);
    z-index:4;display:flex;gap:9px;align-items:center;
}
.cs-dot{
    width:8px;height:8px;border-radius:50%;
    background:rgba(255,255,255,.35);cursor:pointer;
    transition:background .25s,transform .25s,width .25s;
}
.cs-dot.active{
    background:#4a9ee0;transform:scale(1.3);
    box-shadow:0 0 6px rgba(74,158,224,.55);
}
.cs-dot:hover:not(.active){background:rgba(255,255,255,.6);}

/* ── Responsive ── */
@media(max-width:640px){
    .cs-wrap{height:300px;}
    .cs-arrow{width:36px;height:36px;font-size:12px;}
    .cs-prev{left:10px;} .cs-next{right:10px;}
    .cs-badge{font-size:9px;padding:4px 10px;}
    .cs-title{bottom:44px;left:14px;right:14px;}
}
@media(prefers-reduced-motion:reduce){
    .cs-track{transition:none;}
    .cs-arrow,.cs-dot{transition:none;}
}
</style>

<div class="cs-wrap" id="gallery">

    {{-- ── Track ── --}}
    <div class="cs-track" id="csTrack">

        {{-- Slide 1 · YARD VIEW — save your image as public/images/gallery/yard.jpg --}}
        <div class="cs-slide" style="background:radial-gradient(ellipse at 30% 45%,rgba(59,130,246,.22) 0%,transparent 58%),linear-gradient(160deg,#060d1a 0%,#0f2952 42%,#1a3d70 68%,#060d1a 100%);background-image:url('/images/gallery/yard.jpg');background-size:cover;background-position:center;">
            <div class="cs-icon"><i class="fas fa-ship"></i></div>
            <div class="cs-overlay"></div>
            <span class="cs-badge">Yard View</span>
            <div class="cs-title active">
                <div class="cs-title-h">NavalForge Shipyard</div>
                <div class="cs-title-sub">Chittagong Port, Bangladesh &nbsp;·&nbsp; Est. 1998</div>
            </div>
            <div class="cs-fade"></div>
        </div>

        {{-- Slide 2 · DRY DOCK — save your image as public/images/gallery/drydock.jpg --}}
        <div class="cs-slide" style="background:radial-gradient(ellipse at 68% 35%,rgba(20,184,166,.18) 0%,transparent 55%),linear-gradient(155deg,#021918 0%,#0a3d38 44%,#0f5a50 70%,#021918 100%);background-image:url('/images/gallery/drydock.jpg');background-size:cover;background-position:center;">
            <div class="cs-icon"><i class="fas fa-anchor"></i></div>
            <div class="cs-overlay"></div>
            <span class="cs-badge">Dry Dock</span>
            <div class="cs-title">
                <div class="cs-title-h">Main Dry Dock Facility</div>
                <div class="cs-title-sub">Full structural overhaul capability</div>
            </div>
            <div class="cs-fade"></div>
        </div>

        {{-- Slide 3 · ENGINE ROOM — save your image as public/images/gallery/engine.jpg --}}
        <div class="cs-slide" style="background:radial-gradient(ellipse at 50% 62%,rgba(251,146,60,.22) 0%,transparent 52%),linear-gradient(170deg,#130400 0%,#5c1800 42%,#7c2d12 66%,#130400 100%);background-image:url('/images/gallery/engine.jpg');background-size:cover;background-position:center;">
            <div class="cs-icon"><i class="fas fa-cogs"></i></div>
            <div class="cs-overlay"></div>
            <span class="cs-badge">Engine Room</span>
            <div class="cs-title">
                <div class="cs-title-h">Engine Overhaul Workshop</div>
                <div class="cs-title-sub">Precision mechanical engineering since 1998</div>
            </div>
            <div class="cs-fade"></div>
        </div>

        {{-- Slide 4 · WELDING UNIT — save your image as public/images/gallery/welding.jpg --}}
        <div class="cs-slide" style="background:radial-gradient(ellipse at 62% 42%,rgba(167,139,250,.2) 0%,transparent 52%),linear-gradient(155deg,#07000f 0%,#1e0a4a 42%,#3b0e8d 68%,#07000f 100%);background-image:url('/images/gallery/welding.jpg');background-size:cover;background-position:center;">
            <div class="cs-icon"><i class="fas fa-fire"></i></div>
            <div class="cs-overlay"></div>
            <span class="cs-badge">Welding Unit</span>
            <div class="cs-title">
                <div class="cs-title-h">Precision Welding Unit</div>
                <div class="cs-title-sub">Certified structural &amp; arc welding</div>
            </div>
            <div class="cs-fade"></div>
        </div>

        {{-- Slide 5 · HULL WORKS — save your image as public/images/gallery/hull.jpg --}}
        <div class="cs-slide" style="background:radial-gradient(ellipse at 38% 50%,rgba(148,163,184,.12) 0%,transparent 52%),linear-gradient(155deg,#090f18 0%,#1a2744 42%,#273d5a 68%,#090f18 100%);background-image:url('/images/gallery/hull.jpg');background-size:cover;background-position:center;">
            <div class="cs-icon"><i class="fas fa-paint-roller"></i></div>
            <div class="cs-overlay"></div>
            <span class="cs-badge">Hull Works</span>
            <div class="cs-title">
                <div class="cs-title-h">Hull Restoration Bay</div>
                <div class="cs-title-sub">Painting, blasting &amp; anti-corrosion treatment</div>
            </div>
            <div class="cs-fade"></div>
        </div>

    </div>{{-- /track --}}

    {{-- ── Arrows ── --}}
    <button class="cs-arrow cs-prev" id="csPrev" aria-label="Previous slide">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button class="cs-arrow cs-next" id="csNext" aria-label="Next slide">
        <i class="fas fa-chevron-right"></i>
    </button>

    {{-- ── Dots ── --}}
    <div class="cs-dots" id="csDots">
        <span class="cs-dot active" data-idx="0"></span>
        <span class="cs-dot" data-idx="1"></span>
        <span class="cs-dot" data-idx="2"></span>
        <span class="cs-dot" data-idx="3"></span>
        <span class="cs-dot" data-idx="4"></span>
    </div>

</div>

{{-- ── Hero headline (below the carousel, clean separation) ── --}}
<div style="background:#0a1628;padding:2.8rem 2.5rem 2.4rem;text-align:center;border-bottom:1px solid #1e293b;">
    <span style="display:inline-block;font-size:10px;font-weight:700;color:#60a5fa;background:#1e3a5f;border-radius:999px;padding:4px 16px;margin-bottom:1rem;text-transform:uppercase;letter-spacing:.12em;">
        Est. 1998 · Chittagong Port, Bangladesh
    </span>
    <h1 style="font-size:clamp(22px,4vw,34px);font-weight:800;color:#f1f5f9;max-width:540px;margin:0 auto .85rem;line-height:1.22;">
        Where ships are restored to full strength
    </h1>
    <p style="font-size:13px;color:#94a3b8;max-width:440px;margin:0 auto 1.8rem;line-height:1.8;">
        Full-service ship repair and maintenance — engine overhauls, hull restoration, structural welding, and electrical systems, all under one roof.
    </p>
    <div style="display:flex;justify-content:center;gap:10px;flex-wrap:wrap;">
        @guest
            <a href="{{ route('login') }}" style="display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:700;padding:11px 28px;background:#1d4ed8;color:#fff;border-radius:8px;border:1.5px solid #2563eb;">
                <i class="fas fa-sign-in-alt"></i> Access system
            </a>
        @endguest
        @auth
            <a href="{{ route('dashboard') }}" style="display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:700;padding:11px 28px;background:#1d4ed8;color:#fff;border-radius:8px;">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        @endauth
        <a href="{{ route('projects') }}" style="display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:600;padding:11px 28px;border:1.5px solid #334155;border-radius:8px;color:#cbd5e1;">
            <i class="fas fa-folder-open"></i> View projects
        </a>
    </div>
</div>

<script>
(function () {
    var track   = document.getElementById('csTrack');
    var dots    = document.querySelectorAll('#csDots .cs-dot');
    var wrap    = document.querySelector('.cs-wrap');
    var total   = dots.length;
    var current = 0;
    var timer   = null;
    var manualPause = false;
    var hovering    = false;

    function goTo(idx) {
        current = ((idx % total) + total) % total;
        track.style.transform = 'translateX(-' + (current * 100) + '%)';
        dots.forEach(function (d, i) {
            d.classList.toggle('active', i === current);
        });
        var titles = document.querySelectorAll('.cs-title');
        titles.forEach(function (t) { t.classList.remove('active'); });
        if (titles[current]) {
            setTimeout(function () { titles[current].classList.add('active'); }, 120);
        }
    }

    function startAuto() {
        stopAuto();
        if (!manualPause && !hovering) {
            timer = setInterval(function () { goTo(current + 1); }, 5000);
        }
    }

    function stopAuto() { clearInterval(timer); timer = null; }

    function manualNav(dir) {
        goTo(current + dir);
        stopAuto();
        manualPause = true;
        clearTimeout(window._csResume);
        window._csResume = setTimeout(function () {
            manualPause = false;
            if (!hovering) startAuto();
        }, 3000);
    }

    /* Arrows */
    document.getElementById('csPrev').addEventListener('click', function () { manualNav(-1); });
    document.getElementById('csNext').addEventListener('click', function () { manualNav(1); });

    /* Dots */
    dots.forEach(function (dot) {
        dot.addEventListener('click', function () {
            var idx = parseInt(dot.getAttribute('data-idx'), 10);
            goTo(idx);
            stopAuto();
            manualPause = true;
            clearTimeout(window._csResume);
            window._csResume = setTimeout(function () {
                manualPause = false;
                if (!hovering) startAuto();
            }, 3000);
        });
    });

    /* Pause on hover */
    wrap.addEventListener('mouseenter', function () { hovering = true;  stopAuto(); });
    wrap.addEventListener('mouseleave', function () { hovering = false; if (!manualPause) startAuto(); });

    /* Touch swipe */
    var touchX = null;
    wrap.addEventListener('touchstart', function (e) { touchX = e.touches[0].clientX; }, {passive:true});
    wrap.addEventListener('touchend', function (e) {
        if (touchX === null) return;
        var dx = e.changedTouches[0].clientX - touchX;
        if (Math.abs(dx) > 40) manualNav(dx < 0 ? 1 : -1);
        touchX = null;
    });

    startAuto();
}());
</script>

{{-- ════════════════════════════════
     LIVE STATS BAR
════════════════════════════════ --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);background:#fff;border-bottom:2px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,.05);">
    <div style="padding:1.4rem;text-align:center;border-right:1px solid #e5e7eb;">
        <i class="fas fa-ship" style="font-size:20px;color:#1d4ed8;margin-bottom:8px;display:block;"></i>
        <div style="font-size:28px;font-weight:800;color:#1d4ed8;">{{ $totalShips }}</div>
        <div style="font-size:11px;color:#9ca3af;margin-top:2px;text-transform:uppercase;letter-spacing:.05em;">Total ships</div>
    </div>
    <div style="padding:1.4rem;text-align:center;border-right:1px solid #e5e7eb;">
        <i class="fas fa-tools" style="font-size:20px;color:#d97706;margin-bottom:8px;display:block;"></i>
        <div style="font-size:28px;font-weight:800;color:#d97706;">{{ $shipsInRepair }}</div>
        <div style="font-size:11px;color:#9ca3af;margin-top:2px;text-transform:uppercase;letter-spacing:.05em;">Under repair</div>
    </div>
    <div style="padding:1.4rem;text-align:center;border-right:1px solid #e5e7eb;">
        <i class="fas fa-clipboard-list" style="font-size:20px;color:#059669;margin-bottom:8px;display:block;"></i>
        <div style="font-size:28px;font-weight:800;color:#059669;">{{ $activeJobs }}</div>
        <div style="font-size:11px;color:#9ca3af;margin-top:2px;text-transform:uppercase;letter-spacing:.05em;">Active jobs</div>
    </div>
    <div style="padding:1.4rem;text-align:center;">
        <i class="fas fa-warehouse" style="font-size:20px;color:#7c3aed;margin-bottom:8px;display:block;"></i>
        <div style="font-size:28px;font-weight:800;color:#7c3aed;">{{ $freeBerths }}</div>
        <div style="font-size:11px;color:#9ca3af;margin-top:2px;text-transform:uppercase;letter-spacing:.05em;">Free berths</div>
    </div>
</div>

{{-- ════════════════════════════════
     ABOUT US
════════════════════════════════ --}}
<section id="about" style="padding:4rem 2.5rem;background:#fff;">
    <div style="max-width:960px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:start;">

        <div>
            <div style="font-size:11px;font-weight:700;color:#1d4ed8;text-transform:uppercase;letter-spacing:.1em;margin-bottom:8px;">About NavalForge</div>
            <h2 style="font-size:26px;font-weight:800;color:#0f172a;margin-bottom:1rem;line-height:1.3;">
                Over 25 years of maritime excellence
            </h2>
            <p style="font-size:13px;color:#475569;line-height:1.8;margin-bottom:1rem;">
                NavalForge was founded in 1998 at the Port of Chittagong with a single berth and a team of 12 skilled engineers. Today we operate a full-service facility with 8 berths, a modern dry dock, and over 200 specialist workers.
            </p>
            <p style="font-size:13px;color:#475569;line-height:1.8;margin-bottom:1.5rem;">
                We handle cargo vessels, tankers, bulk carriers, and offshore support vessels — providing engine overhauls, hull painting, electrical systems, welding, and structural repairs to international standards.
            </p>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div style="padding:1rem;border:1px solid #e2e8f0;border-radius:10px;">
                    <i class="fas fa-certificate" style="color:#1d4ed8;margin-bottom:6px;display:block;"></i>
                    <div style="font-size:12px;font-weight:700;color:#0f172a;margin-bottom:2px;">ISO 9001:2015</div>
                    <div style="font-size:11px;color:#64748b;">Quality management certified</div>
                </div>
                <div style="padding:1rem;border:1px solid #e2e8f0;border-radius:10px;">
                    <i class="fas fa-shield-alt" style="color:#059669;margin-bottom:6px;display:block;"></i>
                    <div style="font-size:12px;font-weight:700;color:#0f172a;margin-bottom:2px;">IACS Member</div>
                    <div style="font-size:11px;color:#64748b;">International classification</div>
                </div>
                <div style="padding:1rem;border:1px solid #e2e8f0;border-radius:10px;">
                    <i class="fas fa-hard-hat" style="color:#d97706;margin-bottom:6px;display:block;"></i>
                    <div style="font-size:12px;font-weight:700;color:#0f172a;margin-bottom:2px;">200+ Workers</div>
                    <div style="font-size:11px;color:#64748b;">Specialist engineers on site</div>
                </div>
                <div style="padding:1rem;border:1px solid #e2e8f0;border-radius:10px;">
                    <i class="fas fa-anchor" style="color:#7c3aed;margin-bottom:6px;display:block;"></i>
                    <div style="font-size:12px;font-weight:700;color:#0f172a;margin-bottom:2px;">8 Berths</div>
                    <div style="font-size:11px;color:#64748b;">Including a full dry dock</div>
                </div>
            </div>
        </div>

        {{-- Location --}}
        <div>
            <div style="font-size:11px;font-weight:700;color:#1d4ed8;text-transform:uppercase;letter-spacing:.1em;margin-bottom:8px;">Our location</div>
            <h3 style="font-size:18px;font-weight:700;color:#0f172a;margin-bottom:1rem;">Dock 12, Chittagong Port</h3>

            {{-- Map placeholder — replace this div with a real Google Maps embed iframe --}}
            <div style="width:100%;height:200px;background:linear-gradient(135deg,#e0f2fe,#bae6fd);border-radius:12px;border:1px solid #bae6fd;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;margin-bottom:1.2rem;">
                <i class="fas fa-map-marked-alt" style="font-size:32px;color:#0284c7;opacity:.7;"></i>
                <span style="font-size:12px;color:#0369a1;font-weight:600;">Replace with Google Maps embed</span>
                <span style="font-size:11px;color:#64748b;">Dock 12, Port Industrial Zone, Chittagong</span>
            </div>

            <div style="display:flex;flex-direction:column;gap:10px;">
                <div style="display:flex;gap:12px;align-items:center;">
                    <div style="width:34px;height:34px;background:#eff6ff;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-map-marker-alt" style="color:#1d4ed8;font-size:14px;"></i>
                    </div>
                    <span style="font-size:13px;color:#374151;">Dock 12, Port Industrial Zone, Chittagong 4100, Bangladesh</span>
                </div>
                <div style="display:flex;gap:12px;align-items:center;">
                    <div style="width:34px;height:34px;background:#eff6ff;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-clock" style="color:#1d4ed8;font-size:14px;"></i>
                    </div>
                    <span style="font-size:13px;color:#374151;">Saturday – Thursday &nbsp;·&nbsp; 8:00 am – 6:00 pm</span>
                </div>
                <div style="display:flex;gap:12px;align-items:center;">
                    <div style="width:34px;height:34px;background:#eff6ff;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-phone" style="color:#1d4ed8;font-size:14px;"></i>
                    </div>
                    <span style="font-size:13px;color:#374151;">+880 31 000 0000</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════════════════
     RECENT ACTIVE JOBS
════════════════════════════════ --}}
@if(count($recent) > 0)
<section style="padding:3rem 2.5rem;background:#f8fafc;border-top:1px solid #e2e8f0;">
    <div style="max-width:960px;margin:0 auto;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.4rem;">
            <div>
                <div style="font-size:11px;font-weight:700;color:#1d4ed8;text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px;">Live</div>
                <h2 style="font-size:20px;font-weight:800;color:#0f172a;">Recent active jobs</h2>
            </div>
            <a href="{{ route('projects') }}" style="font-size:13px;font-weight:600;color:#1d4ed8;display:inline-flex;align-items:center;gap:6px;">
                View all <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:12px;">
            @foreach($recent as $job)
            @php
                $badge = match($job->status) {
                    'in_progress' => ['#fef3c7','#92400e'],
                    'pending'     => ['#eff6ff','#1e40af'],
                    default       => ['#f1f5f9','#475569'],
                };
            @endphp
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:1.1rem;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;margin-bottom:8px;">
                    <div style="font-size:14px;font-weight:600;color:#0f172a;">{{ $job->title }}</div>
                    <span style="font-size:10px;font-weight:700;padding:3px 9px;border-radius:999px;white-space:nowrap;flex-shrink:0;background:{{ $badge[0] }};color:{{ $badge[1] }};">
                        {{ strtoupper(str_replace('_',' ',$job->status)) }}
                    </span>
                </div>
                <div style="font-size:12px;color:#64748b;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-ship" style="color:#3b82f6;"></i> {{ $job->ship_name }}
                </div>
                @if($job->berth_name)
                <div style="font-size:12px;color:#94a3b8;margin-top:4px;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-warehouse" style="color:#94a3b8;"></i> {{ $job->berth_name }}
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ════════════════════════════════
     TRUSTED BY
════════════════════════════════ --}}
<section style="padding:3.5rem 2.5rem;background:#fff;border-top:1px solid #e2e8f0;">
    <div style="max-width:960px;margin:0 auto;text-align:center;">
        <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.12em;margin-bottom:2rem;">
            Trusted by leading maritime operators
        </div>
        {{-- Replace these with real sponsor logos using <img src="/images/sponsors/logo.png"> --}}
        <div style="display:flex;flex-wrap:wrap;justify-content:center;align-items:center;gap:2rem;">

            <div style="padding:12px 24px;border:1.5px solid #e2e8f0;border-radius:10px;min-width:140px;text-align:center;">
                <i class="fas fa-ship" style="font-size:18px;color:#0369a1;display:block;margin-bottom:5px;"></i>
                <div style="font-size:12px;font-weight:700;color:#1e293b;">Pacific Maritime</div>
                <div style="font-size:10px;color:#94a3b8;">Singapore</div>
            </div>

            <div style="padding:12px 24px;border:1.5px solid #e2e8f0;border-radius:10px;min-width:140px;text-align:center;">
                <i class="fas fa-anchor" style="font-size:18px;color:#0f766e;display:block;margin-bottom:5px;"></i>
                <div style="font-size:12px;font-weight:700;color:#1e293b;">Bengal Vessels Ltd.</div>
                <div style="font-size:10px;color:#94a3b8;">Bangladesh</div>
            </div>

            <div style="padding:12px 24px;border:1.5px solid #e2e8f0;border-radius:10px;min-width:140px;text-align:center;">
                <i class="fas fa-water" style="font-size:18px;color:#7c3aed;display:block;margin-bottom:5px;"></i>
                <div style="font-size:12px;font-weight:700;color:#1e293b;">Delta Shipping Co.</div>
                <div style="font-size:10px;color:#94a3b8;">Myanmar</div>
            </div>

            <div style="padding:12px 24px;border:1.5px solid #e2e8f0;border-radius:10px;min-width:140px;text-align:center;">
                <i class="fas fa-globe-asia" style="font-size:18px;color:#b45309;display:block;margin-bottom:5px;"></i>
                <div style="font-size:12px;font-weight:700;color:#1e293b;">Gulf Star Marine</div>
                <div style="font-size:10px;color:#94a3b8;">UAE</div>
            </div>

            <div style="padding:12px 24px;border:1.5px solid #e2e8f0;border-radius:10px;min-width:140px;text-align:center;">
                <i class="fas fa-industry" style="font-size:18px;color:#dc2626;display:block;margin-bottom:5px;"></i>
                <div style="font-size:12px;font-weight:700;color:#1e293b;">CPA — Chittagong</div>
                <div style="font-size:10px;color:#94a3b8;">Port Authority</div>
            </div>

        </div>
    </div>
</section>

{{-- ════════════════════════════════
     FOOTER WITH CONTACT
════════════════════════════════ --}}
<footer id="contact" style="background:#0a1628;color:#94a3b8;padding:4rem 2.5rem 1.5rem;border-top:3px solid #1d4ed8;">
    <div style="max-width:960px;margin:0 auto;display:grid;grid-template-columns:2fr 1fr 1fr;gap:3rem;margin-bottom:2.5rem;">

        {{-- Contact form --}}
        <div>
            <div style="font-size:11px;font-weight:700;color:#60a5fa;text-transform:uppercase;letter-spacing:.1em;margin-bottom:14px;">Contact us</div>
            <p style="font-size:13px;color:#64748b;margin-bottom:1.2rem;line-height:1.7;">Have a vessel that needs repairs? Send us a message and our team will get back to you within 24 hours.</p>
            <form method="POST" action="#" style="display:flex;flex-direction:column;gap:10px;">
                @csrf
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                    <input type="text" name="name" placeholder="Your name"
                           style="font-size:12px;padding:9px 12px;border:1.5px solid #1e293b;border-radius:7px;background:#0f172a;color:#e2e8f0;outline:none;">
                    <input type="email" name="email" placeholder="Email address"
                           style="font-size:12px;padding:9px 12px;border:1.5px solid #1e293b;border-radius:7px;background:#0f172a;color:#e2e8f0;outline:none;">
                </div>
                <input type="text" name="subject" placeholder="Subject (e.g. Engine repair enquiry)"
                       style="font-size:12px;padding:9px 12px;border:1.5px solid #1e293b;border-radius:7px;background:#0f172a;color:#e2e8f0;outline:none;">
                <textarea name="message" rows="3" placeholder="Tell us about your vessel and the work required..."
                          style="font-size:12px;padding:9px 12px;border:1.5px solid #1e293b;border-radius:7px;background:#0f172a;color:#e2e8f0;outline:none;resize:vertical;font-family:inherit;"></textarea>
                <button type="submit" style="display:inline-flex;align-items:center;justify-content:center;gap:8px;font-size:13px;font-weight:600;padding:10px;border-radius:7px;background:#1d4ed8;color:#fff;border:none;cursor:pointer;">
                    <i class="fas fa-paper-plane"></i> Send message
                </button>
            </form>
        </div>

        {{-- Contact info --}}
        <div>
            <div style="font-size:11px;font-weight:700;color:#60a5fa;text-transform:uppercase;letter-spacing:.1em;margin-bottom:14px;">Get in touch</div>
            <div style="display:flex;flex-direction:column;gap:12px;">
                <div style="display:flex;gap:10px;align-items:flex-start;">
                    <i class="fas fa-map-marker-alt" style="color:#3b82f6;margin-top:2px;width:14px;"></i>
                    <span style="font-size:12px;line-height:1.6;">Dock 12, Port Industrial Zone,<br>Chittagong 4100, Bangladesh</span>
                </div>
                <div style="display:flex;gap:10px;align-items:center;">
                    <i class="fas fa-phone" style="color:#3b82f6;width:14px;"></i>
                    <span style="font-size:12px;">+880 31 000 0000</span>
                </div>
                <div style="display:flex;gap:10px;align-items:center;">
                    <i class="fas fa-fax" style="color:#3b82f6;width:14px;"></i>
                    <span style="font-size:12px;">+880 31 000 0001</span>
                </div>
                <div style="display:flex;gap:10px;align-items:center;">
                    <i class="fas fa-envelope" style="color:#3b82f6;width:14px;"></i>
                    <span style="font-size:12px;">info@navalforge.com</span>
                </div>
                <div style="display:flex;gap:10px;align-items:center;">
                    <i class="fas fa-clock" style="color:#3b82f6;width:14px;"></i>
                    <span style="font-size:12px;">Sat–Thu &nbsp;8:00 am – 6:00 pm</span>
                </div>
            </div>
        </div>

        {{-- Quick links --}}
        <div>
            <div style="font-size:11px;font-weight:700;color:#60a5fa;text-transform:uppercase;letter-spacing:.1em;margin-bottom:14px;">Quick links</div>
            <div style="display:flex;flex-direction:column;gap:9px;">
                <a href="{{ route('home') }}"     style="font-size:13px;color:#64748b;display:flex;align-items:center;gap:7px;"><i class="fas fa-chevron-right" style="font-size:9px;color:#3b82f6;"></i>Home</a>
                <a href="#about"                  style="font-size:13px;color:#64748b;display:flex;align-items:center;gap:7px;"><i class="fas fa-chevron-right" style="font-size:9px;color:#3b82f6;"></i>About us</a>
                <a href="{{ route('projects') }}" style="font-size:13px;color:#64748b;display:flex;align-items:center;gap:7px;"><i class="fas fa-chevron-right" style="font-size:9px;color:#3b82f6;"></i>Projects</a>
                <a href="#gallery"                style="font-size:13px;color:#64748b;display:flex;align-items:center;gap:7px;"><i class="fas fa-chevron-right" style="font-size:9px;color:#3b82f6;"></i>Gallery</a>
                <a href="#contact"                style="font-size:13px;color:#64748b;display:flex;align-items:center;gap:7px;"><i class="fas fa-chevron-right" style="font-size:9px;color:#3b82f6;"></i>Contact</a>
                @guest
                <a href="{{ route('login') }}"    style="font-size:13px;color:#64748b;display:flex;align-items:center;gap:7px;"><i class="fas fa-chevron-right" style="font-size:9px;color:#3b82f6;"></i>Sign in</a>
                @endguest
            </div>
        </div>
    </div>

    <div style="max-width:960px;margin:0 auto;border-top:1px solid #1e293b;padding-top:1.2rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
        <p style="font-size:12px;color:#334155;">NavalForge &copy; {{ date('Y') }} — All rights reserved.</p>
        <p style="font-size:12px;color:#334155;">Powered by Laravel &amp; Oracle Database</p>
    </div>
</footer>

@endsection
