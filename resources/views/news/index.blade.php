@extends('app')
@section('title', 'News & Events')

@section('content')
<div style="background:#fff;min-height:calc(100vh - 50px);">
    <div style="max-width:1000px;margin:0 auto;padding:44px 2.5rem 80px;">

        {{-- ── Heading ── --}}
        <div style="margin-bottom:32px;">
            <div style="font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#1d4ed8;margin-bottom:10px;">
                NavalForge
            </div>
            <h1 style="font-size:clamp(26px,4vw,38px);font-weight:900;color:#0f172a;letter-spacing:-.025em;line-height:1.1;margin-bottom:10px;">
                News &amp; Events
            </h1>
            <p style="font-size:13px;color:#64748b;margin:0;">
                Company announcements, project milestones, and industry updates.
            </p>
        </div>

        {{-- ── Table ── --}}
        @if(count($items) === 0)
        <div style="text-align:center;padding:80px 0;color:#94a3b8;">
            <i class="fas fa-newspaper" style="font-size:32px;margin-bottom:12px;display:block;"></i>
            No announcements published yet.
        </div>
        @else

        <div class="ne-wrap">
            <table class="ne-table">
                <thead>
                    <tr>
                        <th class="th-date">Publish Date</th>
                        <th class="th-desc">Description</th>
                        <th class="th-link">Link</th>
                        <th class="th-pdf">PDF</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr class="ne-row {{ $loop->even ? 'ne-row-alt' : '' }}">

                        <td class="td-date">{{ $item->pub_date }}</td>

                        <td class="td-desc">{{ $item->title }}</td>

                        <td class="td-link">
                            @if($item->link)
                                <a href="{{ $item->link }}" target="_blank" rel="noopener" class="ne-link">
                                    Details
                                </a>
                            @else
                                <span class="ne-dash">—</span>
                            @endif
                        </td>

                        <td class="td-pdf">
                            @if($item->pdf_path)
                                <a href="{{ $item->pdf_path }}" target="_blank" rel="noopener" class="ne-pdf" title="Download PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            @else
                                <span class="ne-dash">—</span>
                            @endif
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @endif

    </div>
</div>

<style>
/* ── Table wrapper — provides rounded outer corners ── */
.ne-wrap {
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    overflow: hidden;
}

/* ── Table base ── */
.ne-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

/* ── Header ── */
.ne-table thead tr {
    background: #1d4ed8;
}
.ne-table thead th {
    padding: 15px 18px;
    text-align: left;
    font-size: 12px;
    font-weight: 700;
    color: #fff;
    letter-spacing: .06em;
    text-transform: uppercase;
    white-space: nowrap;
}
.th-date { width: 140px; }
.th-desc { /* takes up remaining width automatically */ }
.th-link { width: 88px; text-align: center; }
.th-pdf  { width: 68px; text-align: center; }

/* ── Rows ── */
.ne-row {
    background: #fff;
    border-bottom: 1px solid #e2e8f0;
    transition: background .1s;
}
.ne-row-alt { background: #f8fafc; }
.ne-row:last-child { border-bottom: none; }
.ne-row:hover { background: #eff6ff !important; }

/* ── Cells ── */
.ne-table td {
    padding: 15px 18px;
    vertical-align: middle;
    font-size: 13px;
}

.td-date {
    font-size: 13px;
    font-weight: 700;
    color: #374151;
    white-space: nowrap;
}
.td-desc {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.45;
}
.td-link { text-align: center; }
.td-pdf  { text-align: center; }

/* ── Link ── */
.ne-link {
    color: #1d4ed8;
    font-size: 13px;
    font-weight: 600;
    text-decoration: underline;
    text-underline-offset: 2px;
    white-space: nowrap;
}
.ne-link:hover { color: #1e40af; }

/* ── PDF icon ── */
.ne-pdf {
    color: #dc2626;
    font-size: 20px;
    display: inline-block;
    line-height: 1;
    transition: opacity .15s;
}
.ne-pdf:hover { opacity: .7; }

/* ── Dash placeholder ── */
.ne-dash {
    color: #cbd5e1;
    font-size: 14px;
}

@media (max-width: 640px) {
    .th-link, .th-pdf, .td-link, .td-pdf { display: none; }
    .td-date { font-size: 11px; }
    .td-desc { font-size: 13px; }
    .ne-table td, .ne-table th { padding: 12px 12px; }
}
</style>

@endsection
