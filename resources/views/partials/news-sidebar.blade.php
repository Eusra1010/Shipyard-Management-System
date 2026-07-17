{{-- Left news panel — data from App\View\Composers\NewsComposer --}}

{{-- ── Panel header ── --}}
<div class="nsp-head">
    <div class="nsp-head-inner">
        <span class="nsp-live-dot"></span>
        <span class="nsp-label">Latest News</span>
    </div>
    <a href="{{ route('news.index') }}" class="nsp-all-link" title="All news">
        <i class="fas fa-arrow-right"></i>
    </a>
</div>

{{-- ── Scrolling items ── --}}
<div class="nsp-wrap" id="nsp-wrap">
    @if(count($latestNews) > 0)
    <div class="nsp-track" id="nsp-track">
        @foreach($latestNews as $item)
        <a href="{{ route('news.index') }}#news-{{ $item->id }}" class="nsp-item">
            <div class="nsp-date">{{ $item->published_at }}</div>
            <div class="nsp-title">{{ $item->title }}</div>
        </a>
        @endforeach
    </div>
    @else
    <div class="nsp-empty">No announcements yet.</div>
    @endif
</div>

{{-- ── Panel footer ── --}}
<a href="{{ route('news.index') }}" class="nsp-footer">
    <span>View all news</span>
    <i class="fas fa-angle-double-right" style="font-size:10px;"></i>
</a>

<style>
/* ── Panel structure ── */
.nsp-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 14px;
    height: 44px;
    background: #0f172a;
    flex-shrink: 0;
    border-bottom: 2px solid #1d4ed8;
}
.nsp-head-inner {
    display: flex;
    align-items: center;
    gap: 8px;
}
.nsp-live-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: #1d4ed8;
    box-shadow: 0 0 0 3px rgba(29,78,216,.22);
    flex-shrink: 0;
}
.nsp-label {
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: #f1f5f9;
}
.nsp-all-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 24px; height: 24px;
    border-radius: 5px;
    background: rgba(29,78,216,.25);
    color: #93c5fd;
    font-size: 10px;
    transition: background .15s;
}
.nsp-all-link:hover { background: rgba(29,78,216,.55); }

/* ── Scroll area ── */
.nsp-wrap {
    flex: 1;
    overflow: hidden;
    position: relative;
}
.nsp-track {
    display: flex;
    flex-direction: column;
    animation: nsp-scroll 20s linear infinite;
}
.nsp-wrap:hover .nsp-track {
    animation-play-state: paused;
}
@keyframes nsp-scroll {
    0%   { transform: translateY(0); }
    100% { transform: translateY(-50%); }
}

/* ── Each news item ── */
.nsp-item {
    display: block;
    padding: 12px 14px;
    border-bottom: 1px solid #e8edf4;
    min-height: 70px;
    cursor: pointer;
    transition: background .12s;
    text-decoration: none;
    position: relative;
}
.nsp-item:hover {
    background: #eff3fb;
}
.nsp-item::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: transparent;
    transition: background .12s;
}
.nsp-item:hover::before {
    background: #1d4ed8;
}

.nsp-date {
    font-size: 10px;
    font-weight: 700;
    color: #1d4ed8;
    text-transform: uppercase;
    letter-spacing: .05em;
    margin-bottom: 5px;
}
.nsp-title {
    font-size: 12px;
    font-weight: 600;
    color: #1e293b;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* ── Empty state ── */
.nsp-empty {
    padding: 24px 14px;
    font-size: 12px;
    color: #94a3b8;
    text-align: center;
}

/* ── Footer link ── */
.nsp-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 14px;
    font-size: 11px;
    font-weight: 700;
    color: #1d4ed8;
    background: #fff;
    border-top: 1px solid #e2e8f0;
    letter-spacing: .04em;
    text-transform: uppercase;
    flex-shrink: 0;
    transition: background .12s, color .12s;
}
.nsp-footer:hover {
    background: #1d4ed8;
    color: #fff;
}
</style>

<script>
(function () {
    var track = document.getElementById('nsp-track');
    if (!track) return;
    track.innerHTML += track.innerHTML;
})();
</script>
