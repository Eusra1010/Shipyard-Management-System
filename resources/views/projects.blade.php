@extends('app')

@section('title', 'Projects — NavalForge Shipyard')

@section('content')
@php
$imgs = ['drydock', 'hull', 'engine', 'welding', 'yard'];
@endphp

{{-- ── Hero ── --}}
<section class="proj-hero">
    <div class="proj-inner">
        <div class="proj-eyebrow">Ship Repair &amp; Maintenance</div>
        <h1 class="proj-heading">Our Projects</h1>
        <p class="proj-sub">Active and completed repair jobs across our berths at Chittagong.</p>
    </div>
</section>

{{-- ── Tab bar ── --}}
<div class="proj-tabbar">
    <div class="proj-inner">
        <div class="proj-tabs" role="tablist">
            <button class="proj-tab active" data-tab="ongoing" role="tab">
                Ongoing
                <span class="proj-tab-count">{{ count($ongoing) }}</span>
            </button>
            <button class="proj-tab" data-tab="completed" role="tab">
                Completed
                <span class="proj-tab-count">{{ count($completed) }}</span>
            </button>
        </div>
    </div>
</div>

{{-- ── Grids ── --}}
<div class="proj-body">

    {{-- Ongoing --}}
    <div class="proj-grid" id="grid-ongoing">
        @forelse($ongoing as $i => $job)
        @php
            $fallback = '/images/gallery/' . $imgs[$i % count($imgs)] . '.jpg';
            $shipImg  = '/images/ships/' . $job->ship_id . '.jpg';
            $workers   = $workersByOrder[$job->order_id]   ?? [];
            $materials = $materialsByOrder[$job->order_id] ?? [];
            $wList = array_map(fn($w) => $w->name . ($w->role ? ' — ' . $w->role : ''), $workers);
            $mList = array_map(fn($m) => $m->name . ' × ' . $m->qty_used . ($m->unit ? ' ' . $m->unit : ''), $materials);
            $modal = json_encode([
                'ship'      => $job->ship_name,
                'type'      => $job->ship_type,
                'flag'      => $job->flag_country,
                'job'       => $job->title,
                'status'    => $job->status,
                'berth'     => $job->berth_name,
                'start'     => $job->start_date,
                'end'       => null,
                'days'      => null,
                'workers'   => $wList,
                'materials' => $mList,
            ]);
        @endphp
        <div class="proj-card {{ $i % 3 === 0 ? 'from-top' : 'from-left' }}">
            <div class="proj-card-img">
                <img src="{{ $shipImg }}" alt="{{ e($job->ship_name) }}" loading="lazy"
                     onerror="this.onerror=null;this.src='{{ $fallback }}'">
            </div>
            <div class="proj-card-body">
                <div class="proj-card-ship">{{ $job->ship_name }}</div>
                <div class="proj-card-desc">{{ $job->title }}</div>
                @if($job->berth_name)
                <div class="proj-card-meta">
                    <i class="fas fa-anchor"></i> {{ $job->berth_name }}
                </div>
                @endif
                <button class="proj-card-btn" data-modal="{{ $modal }}">
                    Details <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
        @empty
        <div class="proj-empty">No ongoing projects at this time.</div>
        @endforelse
    </div>

    {{-- Completed --}}
    <div class="proj-grid" id="grid-completed" style="display:none;">
        @forelse($completed as $i => $job)
        @php
            $fallback = '/images/gallery/' . $imgs[$i % count($imgs)] . '.jpg';
            $shipImg  = '/images/ships/' . $job->ship_id . '.jpg';
            $workers   = $workersByOrder[$job->order_id]   ?? [];
            $materials = $materialsByOrder[$job->order_id] ?? [];
            $wList = array_map(fn($w) => $w->name . ($w->role ? ' — ' . $w->role : ''), $workers);
            $mList = array_map(fn($m) => $m->name . ' × ' . $m->qty_used . ($m->unit ? ' ' . $m->unit : ''), $materials);
            $modal = json_encode([
                'ship'      => $job->ship_name,
                'type'      => $job->ship_type,
                'flag'      => $job->flag_country,
                'job'       => $job->title,
                'status'    => 'done',
                'berth'     => $job->berth_name,
                'start'     => $job->start_date,
                'end'       => $job->end_date,
                'days'      => $job->days_taken,
                'workers'   => $wList,
                'materials' => $mList,
            ]);
        @endphp
        <div class="proj-card from-left">
            <div class="proj-card-img">
                <img src="{{ $shipImg }}" alt="{{ e($job->ship_name) }}" loading="lazy"
                     onerror="this.onerror=null;this.src='{{ $fallback }}'">
                <span class="proj-done-badge">Completed</span>
            </div>
            <div class="proj-card-body">
                <div class="proj-card-ship">{{ $job->ship_name }}</div>
                <div class="proj-card-desc">{{ $job->title }}</div>
                @if(!empty($job->days_taken))
                <div class="proj-card-meta">
                    <i class="fas fa-calendar-check"></i> {{ round($job->days_taken) }} days
                </div>
                @endif
                <button class="proj-card-btn" data-modal="{{ $modal }}">
                    Details <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
        @empty
        <div class="proj-empty">No completed projects yet.</div>
        @endforelse
    </div>

</div>

{{-- ── Modal ── --}}
<div id="proj-overlay" class="proj-overlay" aria-hidden="true">
    <div class="proj-modal" role="dialog" aria-modal="true">
        <button class="proj-modal-x" id="proj-modal-x" aria-label="Close">&times;</button>
        <div id="proj-modal-body"></div>
    </div>
</div>

<style>
.proj-inner {
    max-width: 960px;
    margin: 0 auto;
    padding: 0 32px;
}

/* Hero */
.proj-hero {
    background: #0f172a;
    padding: 80px 0 64px;
}
.proj-eyebrow {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: #2563eb;
    margin-bottom: 16px;
}
.proj-heading {
    font-size: clamp(34px, 5vw, 54px);
    font-weight: 800;
    color: #fff;
    line-height: 1.08;
    letter-spacing: -.025em;
    margin: 0 0 16px;
}
.proj-sub {
    font-size: 16px;
    color: #94a3b8;
    line-height: 1.65;
    max-width: 460px;
    margin: 0;
}

/* Tab bar */
.proj-tabbar {
    background: #fff;
    border-bottom: 1px solid #e2e8f0;
    position: sticky;
    top: 50px;
    z-index: 40;
}
.proj-tabs { display: flex; }
.proj-tab {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 16px 2px;
    margin-right: 30px;
    font-size: 14px;
    font-weight: 600;
    color: #94a3b8;
    background: none;
    border: none;
    border-bottom: 2px solid transparent;
    cursor: pointer;
    transition: color .15s, border-color .15s;
    font-family: inherit;
}
.proj-tab:hover  { color: #0f172a; }
.proj-tab.active { color: #0f172a; border-bottom-color: #2563eb; }
.proj-tab-count {
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 999px;
    background: #f1f5f9;
    color: #64748b;
}
.proj-tab.active .proj-tab-count { background: #eff6ff; color: #2563eb; }

/* Body + Grid */
.proj-body {
    background: #f8fafc;
    padding: 48px 32px 90px;
    min-height: 50vh;
}
.proj-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
    max-width: 960px;
    margin: 0 auto;
}

/* Card — animation start state */
.proj-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    opacity: 0;
    transition: opacity .55s ease, transform .55s ease, border-color .2s;
}
.proj-card.from-left  { transform: translateX(-38px); }
.proj-card.from-top   { transform: translateY(-28px); }
.proj-card.is-visible { opacity: 1; transform: translate(0,0) !important; }
.proj-card:hover { border-color: #cbd5e1; }

/* Card image */
.proj-card-img {
    height: 190px;
    overflow: hidden;
    position: relative;
    background: linear-gradient(135deg, #0f172a, #1e3a5f);
    flex-shrink: 0;
}
.proj-card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .45s ease;
}
.proj-card:hover .proj-card-img img { transform: scale(1.08); }

.proj-done-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    padding: 4px 10px;
    border-radius: 999px;
    background: rgba(21,128,61,.88);
    color: #fff;
}

/* Card body */
.proj-card-body {
    padding: 20px;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.proj-card-ship {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 5px;
    line-height: 1.3;
}
.proj-card-desc {
    font-size: 13px;
    color: #64748b;
    line-height: 1.55;
    flex: 1;
    margin-bottom: 14px;
}
.proj-card-meta {
    font-size: 12px;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 14px;
}
.proj-card-meta i { font-size: 10px; }
.proj-card-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    align-self: flex-start;
    font-size: 12px;
    font-weight: 600;
    color: #2563eb;
    border: 1.5px solid #2563eb;
    border-radius: 6px;
    padding: 7px 14px;
    background: transparent;
    cursor: pointer;
    transition: background .15s, color .15s;
    font-family: inherit;
}
.proj-card-btn:hover { background: #2563eb; color: #fff; }
.proj-card-btn i { font-size: 10px; }

/* Empty state */
.proj-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 64px 0;
    font-size: 14px;
    color: #94a3b8;
}

/* Modal overlay */
.proj-overlay {
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
.proj-overlay.open {
    opacity: 1;
    pointer-events: all;
}

/* Modal card */
.proj-modal {
    background: #fff;
    border-radius: 14px;
    width: 100%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    padding: 32px;
    position: relative;
    box-shadow: 0 24px 60px rgba(0,0,0,.22);
    transform: translateY(14px) scale(.97);
    transition: transform .2s ease;
}
.proj-overlay.open .proj-modal { transform: none; }

.proj-modal-x {
    position: absolute;
    top: 16px;
    right: 16px;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 1.5px solid #e2e8f0;
    background: none;
    font-size: 18px;
    color: #64748b;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: border-color .15s, color .15s;
    font-family: inherit;
    line-height: 1;
}
.proj-modal-x:hover { border-color: #94a3b8; color: #0f172a; }

/* Modal content (JS-injected) */
.m-ship  { font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 4px; padding-right: 30px; line-height: 1.2; }
.m-job   { font-size: 14px; color: #64748b; margin-bottom: 16px; line-height: 1.45; }
.m-badge { display: inline-block; font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 999px; }
.m-hr    { border: none; border-top: 1px solid #e2e8f0; margin: 18px 0; }
.m-row   { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; margin-bottom: 11px; font-size: 13px; }
.m-lbl   { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: #94a3b8; white-space: nowrap; padding-top: 2px; }
.m-val   { color: #0f172a; font-weight: 500; text-align: right; }
.m-sec   { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: #94a3b8; margin-bottom: 10px; }
.m-list  { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 6px; }
.m-list li { font-size: 13px; color: #0f172a; font-weight: 500; display: flex; align-items: center; gap: 8px; }
.m-list li::before { content: ''; display: inline-block; width: 5px; height: 5px; border-radius: 50%; background: #2563eb; flex-shrink: 0; }

/* Responsive */
@media (max-width: 640px) {
    .proj-hero  { padding: 56px 0 40px; }
    .proj-inner { padding-left: 20px; padding-right: 20px; }
    .proj-body  { padding: 32px 20px 60px; }
    .proj-grid  { grid-template-columns: 1fr; }
    .proj-tab   { font-size: 13px; margin-right: 20px; }
    .proj-modal { padding: 24px 20px; }
}

@media (prefers-reduced-motion: reduce) {
    .proj-card, .proj-card-img img, .proj-overlay, .proj-modal {
        transition: none !important;
    }
    .proj-card { opacity: 1 !important; transform: none !important; }
}
</style>

<script>
(function () {
    /* Tabs */
    const tabs      = document.querySelectorAll('.proj-tab');
    const gOngoing  = document.getElementById('grid-ongoing');
    const gComplete = document.getElementById('grid-completed');
    let completedDone = false;

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const which = tab.dataset.tab;
            tabs.forEach(t => t.classList.toggle('active', t === tab));
            gOngoing.style.display  = which === 'ongoing'   ? 'grid' : 'none';
            gComplete.style.display = which === 'completed' ? 'grid' : 'none';

            if (which === 'completed' && !completedDone) {
                completedDone = true;
                requestAnimationFrame(() => {
                    gComplete.querySelectorAll('.proj-card').forEach((card, i) => {
                        setTimeout(() => {
                            card.classList.add('is-visible');
                            cardObserver.unobserve(card);
                        }, i * 70);
                    });
                });
            }
        });
    });

    /* Intersection Observer for scroll-in */
    const cardObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                cardObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.08 });

    gOngoing.querySelectorAll('.proj-card').forEach(card => cardObserver.observe(card));

    /* Modal */
    const overlay   = document.getElementById('proj-overlay');
    const modalBody = document.getElementById('proj-modal-body');
    const closeBtn  = document.getElementById('proj-modal-x');

    function badge(status) {
        const map = {
            in_progress: ['In Progress', '#fef3c7', '#92400e'],
            pending:     ['Pending',     '#dbeafe', '#1e40af'],
            done:        ['Completed',   '#dcfce7', '#15803d'],
            cancelled:   ['Cancelled',   '#f1f5f9', '#475569'],
        };
        const [lbl, bg, col] = map[status] || ['Unknown', '#f1f5f9', '#475569'];
        return `<span class="m-badge" style="background:${bg};color:${col};">${lbl}</span>`;
    }

    function row(lbl, val) {
        if (!val && val !== 0) return '';
        return `<div class="m-row"><span class="m-lbl">${lbl}</span><span class="m-val">${val}</span></div>`;
    }

    function listBlock(title, items) {
        if (!items || !items.length) return '';
        return `<div class="m-sec">${title}</div>
                <ul class="m-list">${items.map(i => `<li>${i}</li>`).join('')}</ul>`;
    }

    function openModal(data) {
        const noData = (!data.workers || !data.workers.length) && (!data.materials || !data.materials.length);
        modalBody.innerHTML = `
            <div class="m-ship">${data.ship}</div>
            <div class="m-job">${data.job}</div>
            <div style="margin-bottom:18px;">${badge(data.status)}</div>
            <hr class="m-hr">
            ${row('Ship type', data.type)}
            ${row('Flag country', data.flag)}
            ${row('Berth', data.berth)}
            ${row('Start date', data.start)}
            ${row('Completed', data.end)}
            ${data.days ? row('Duration', Math.round(data.days) + ' days') : ''}
            <hr class="m-hr">
            ${listBlock('Assigned Workers', data.workers)}
            ${data.workers && data.workers.length && data.materials && data.materials.length ? '<div style="margin-top:16px;"></div>' : ''}
            ${listBlock('Materials Used', data.materials)}
            ${noData ? '<p style="font-size:13px;color:#94a3b8;margin:0;">No workers or materials recorded.</p>' : ''}
        `;
        overlay.classList.add('open');
        overlay.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        overlay.classList.remove('open');
        overlay.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    document.addEventListener('click', e => {
        const btn = e.target.closest('.proj-card-btn');
        if (btn) {
            try { openModal(JSON.parse(btn.dataset.modal)); } catch (_) {}
        }
    });

    closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', e => { if (e.target === overlay) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
})();
</script>

@endsection
