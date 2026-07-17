@extends('app')
@section('title', 'Our Facility — NavalForge')

@section('content')

@php
$facilities = [
    [
        'slug' => 'carpentry-shop',
        'name' => 'Carpentry Shop',
        'grad' => 'linear-gradient(145deg,#180f07 0%,#2e1c0d 100%)',
        'desc' => 'Custom joinery and wooden fittings for cabin interiors, deck furnishings, and structural panelling — built and finished on-site. Our carpenters work from technical drawings supplied by our in-house design team, ensuring every fitting meets the vessel\'s original specification.',
    ],
    [
        'slug' => 'fabrication-shed',
        'name' => 'Fabrication Shed',
        'grad' => 'linear-gradient(145deg,#091120 0%,#0f2240 100%)',
        'desc' => 'Heavy plate cutting, bending, and structural steel assembly for hull repairs and new component fabrication. The shed handles both light sheet work and large structural sections, with overhead crane access and full MIG and stick welding capability.',
    ],
    [
        'slug' => 'warehouse',
        'name' => 'Warehouse',
        'grad' => 'linear-gradient(145deg,#0d110d 0%,#162016 100%)',
        'desc' => 'Central storage for all incoming materials — steel stock, paints, electrical components, and consumables. Stock levels are tracked through our inventory system and tied directly to open work orders, so materials are ready at the point of use.',
    ],
    [
        'slug' => 'in-house-design',
        'name' => 'In-house Design',
        'grad' => 'linear-gradient(145deg,#0a0f1c 0%,#102040 100%)',
        'desc' => 'Technical drawings, structural assessments, and repair specifications produced by our resident engineers before work begins. Having design on-site shortens the approval cycle, reduces rework, and allows plans to be revised quickly if conditions change during the job.',
    ],
    [
        'slug' => 'welding-unit',
        'name' => 'Welding Unit',
        'grad' => 'linear-gradient(145deg,#1a0e08 0%,#2c1a0a 100%)',
        'desc' => 'Dedicated bays for arc, MIG, TIG, and certified underwater welding. Our welders hold current qualifications to classification society standards and regularly carry out structural hull repairs, pipe work, and pressure vessel fabrication.',
    ],
    [
        'slug' => 'dry-dock',
        'name' => 'Dry Dock',
        'grad' => 'linear-gradient(145deg,#07101e 0%,#0b1e38 100%)',
        'desc' => 'Our graving dock accommodates vessels up to 12,000 DWT for underwater hull work, propeller servicing, sea valve replacement, and full bottom painting. The dock is equipped for ballast tank inspection, cathodic protection renewal, and classification society surveys.',
    ],
];
@endphp

{{-- ════════════════════════════════ FACILITY SECTION ══════════════════════════════ --}}
<section style="padding:3.5rem 2.5rem 4rem; background:#fff; border-bottom:1px solid #e2e8f0;">
<div style="max-width:960px; margin:0 auto;">

    {{-- ── Header ── --}}
    <div style="text-align:center; margin-bottom:2.6rem;">
        <div style="font-size:11px; font-weight:700; color:#1d4ed8;
                    text-transform:uppercase; letter-spacing:.1em; margin-bottom:10px;">
            Our Facility
        </div>
        <h1 style="font-size:26px; font-weight:800; color:#0f172a;
                   letter-spacing:-.02em; line-height:1.2; margin:0 0 12px;">
            Inside the Yard
        </h1>
        <p style="font-size:13px; color:#64748b; line-height:1.65;
                  max-width:440px; margin:0 auto;">
            Six specialist facilities — each dedicated to a different stage of ship repair —
            operating together from our yard in Chittagong.
        </p>
    </div>

    {{-- ── Card grid ── --}}
    <div class="fac-grid">
        @foreach($facilities as $f)
        <button class="fac-card"
                onclick="openFacModal({{ $loop->index }})"
                aria-label="View {{ $f['name'] }}">
            {{-- Background image with zoom target --}}
            <div class="fac-img"
                 style="background-image:url('/images/facility/{{ $f['slug'] }}.jpg'),{{ $f['grad'] }};"></div>
            {{-- Dark gradient overlay --}}
            <div class="fac-overlay"></div>
            {{-- Name text --}}
            <div class="fac-text">
                <div class="fac-name">{{ $f['name'] }}</div>
            </div>
        </button>
        @endforeach
    </div>

</div>
</section>

{{-- ════════════════════════════════ MODAL ══════════════════════════════ --}}
<div id="fac-overlay" class="fac-overlay-bg" aria-hidden="true">
    <div class="fac-modal" role="dialog" aria-modal="true" id="fac-modal">

        <button class="fac-modal-x" id="fac-modal-x" aria-label="Close">&times;</button>

        {{-- Image band at top --}}
        <div class="fac-modal-img" id="fac-modal-img"></div>

        {{-- Body --}}
        <div class="fac-modal-body">
            <h2 class="fac-modal-title" id="fac-modal-title"></h2>
            <p  class="fac-modal-desc"  id="fac-modal-desc"></p>
        </div>

        {{-- Footer --}}
        <div class="fac-modal-ft">
            <button class="fac-modal-close-btn" id="fac-modal-close-btn">Close</button>
        </div>

    </div>
</div>

{{-- ════════════════════════════════ STYLES ══════════════════════════════ --}}
<style>
/* ── Grid ── */
.fac-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
}
@media (max-width: 680px) { .fac-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 420px) { .fac-grid { grid-template-columns: 1fr; } }

/* ── Card ── */
.fac-card {
    position: relative;
    height: 162px;
    border-radius: 10px;
    overflow: hidden;
    cursor: pointer;
    border: none;
    padding: 0;
    display: block;
    width: 100%;
    text-align: left;
    background: transparent;
}
.fac-card:focus-visible {
    outline: 3px solid #1d4ed8;
    outline-offset: 2px;
}

/* Background image layer — this is what zooms */
.fac-img {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    transition: transform .45s ease;
    border-radius: 10px;
}
.fac-card:hover .fac-img { transform: scale(1.07); }

/* Gradient overlay */
.fac-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,.72) 0%, rgba(0,0,0,.08) 55%, transparent 100%);
    border-radius: 10px;
    pointer-events: none;
}

/* Name text */
.fac-text {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    padding: 14px 15px;
    z-index: 1;
}
.fac-name {
    font-size: 14px;
    font-weight: 800;
    color: #fff;
    line-height: 1.2;
    letter-spacing: -.01em;
}

/* ── Modal overlay ── */
.fac-overlay-bg {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.55);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    pointer-events: none;
    transition: opacity .2s ease;
}
.fac-overlay-bg.open {
    opacity: 1;
    pointer-events: all;
}

/* ── Modal card ── */
.fac-modal {
    background: #fff;
    border-radius: 14px;
    width: 100%;
    max-width: 460px;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    box-shadow: 0 24px 60px rgba(0,0,0,.24);
    transform: translateY(14px) scale(.97);
    transition: transform .2s ease;
    display: flex;
    flex-direction: column;
}
.fac-overlay-bg.open .fac-modal { transform: none; }

/* Close × button top-right */
.fac-modal-x {
    position: absolute;
    top: 14px; right: 14px;
    width: 28px; height: 28px;
    border-radius: 50%;
    border: 1.5px solid #e2e8f0;
    background: rgba(255,255,255,.9);
    font-size: 18px; color: #64748b;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: border-color .15s, color .15s;
    font-family: inherit; line-height: 1;
    z-index: 2;
}
.fac-modal-x:hover { border-color: #94a3b8; color: #0f172a; }

/* Image band */
.fac-modal-img {
    width: 100%;
    height: 200px;
    background-size: cover;
    background-position: center;
    border-radius: 14px 14px 0 0;
    flex-shrink: 0;
}

/* Body */
.fac-modal-body {
    padding: 22px 24px 6px;
    flex: 1;
}
.fac-modal-title {
    font-size: 19px; font-weight: 800; color: #0f172a;
    letter-spacing: -.015em; margin: 0 0 10px;
    padding-right: 24px;
    line-height: 1.2;
}
.fac-modal-desc {
    font-size: 13.5px; color: #475569;
    line-height: 1.68; margin: 0;
}

/* Footer */
.fac-modal-ft {
    padding: 18px 24px 22px;
    display: flex; justify-content: flex-end;
}
.fac-modal-close-btn {
    font-size: 13px; font-weight: 600;
    padding: 9px 24px;
    border: 1.5px solid #e2e8f0; border-radius: 8px;
    background: #fff; color: #475569; cursor: pointer;
    transition: background .12s, border-color .12s;
    font-family: inherit;
}
.fac-modal-close-btn:hover { background: #f8fafc; border-color: #cbd5e1; }

@media (prefers-reduced-motion: reduce) {
    .fac-img, .fac-overlay-bg, .fac-modal { transition: none !important; }
    .fac-card:hover .fac-img { transform: none; }
}
</style>

{{-- ════════════════════════════════ SCRIPT ══════════════════════════════ --}}
<script>
(function () {
    var FACILITIES = @json($facilities);

    var overlay   = document.getElementById('fac-overlay');
    var modalImg  = document.getElementById('fac-modal-img');
    var modalTitle = document.getElementById('fac-modal-title');
    var modalDesc  = document.getElementById('fac-modal-desc');
    var btnX       = document.getElementById('fac-modal-x');
    var btnClose   = document.getElementById('fac-modal-close-btn');

    window.openFacModal = function (idx) {
        var f = FACILITIES[idx];
        if (!f) return;

        // Image: try real file first, fall back to gradient
        modalImg.style.backgroundImage =
            "url('/images/facility/" + f.slug + ".jpg')," + f.grad;

        modalTitle.textContent = f.name;
        modalDesc.textContent  = f.desc;

        overlay.classList.add('open');
        overlay.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    };

    function closeModal() {
        overlay.classList.remove('open');
        overlay.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    btnX.addEventListener('click', closeModal);
    btnClose.addEventListener('click', closeModal);
    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) closeModal();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });
}());
</script>

@endsection
