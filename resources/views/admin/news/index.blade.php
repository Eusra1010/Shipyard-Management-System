@extends('layouts.admin')
@section('title', 'News')
@section('page-title', 'News')
@section('breadcrumb', 'NavalForge / News')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div style="display:flex;align-items:center;gap:10px;">
        <h1 style="font-size:18px;font-weight:800;color:#0f172a;">Announcements</h1>
        <span style="font-size:12px;font-weight:600;color:#64748b;background:#f1f5f9;border:1px solid #e2e8f0;padding:2px 10px;border-radius:20px;">
            {{ count($items) }} items
        </span>
    </div>
    <a href="{{ route('admin.news.create') }}"
       style="display:inline-flex;align-items:center;gap:7px;font-size:13px;font-weight:700;padding:8px 18px;background:#1d4ed8;color:#fff;border-radius:8px;">
        <i class="fas fa-plus" style="font-size:11px;"></i> Add news
    </a>
</div>

@if(session('success'))
<div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:10px 16px;margin-bottom:16px;font-size:13px;color:#15803d;">
    <i class="fas fa-check-circle" style="margin-right:6px;"></i>{{ session('success') }}
</div>
@endif

@if(count($items) === 0)
<div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:60px;text-align:center;">
    <i class="fas fa-newspaper" style="font-size:28px;color:#cbd5e1;margin-bottom:12px;display:block;"></i>
    <div style="font-size:14px;color:#94a3b8;">No news items yet.</div>
    <a href="{{ route('admin.news.create') }}" style="display:inline-block;margin-top:14px;font-size:13px;font-weight:600;color:#1d4ed8;">+ Add the first announcement</a>
</div>
@else
<div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:700;color:#94a3b8;letter-spacing:.06em;text-transform:uppercase;">Title</th>
                <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:700;color:#94a3b8;letter-spacing:.06em;text-transform:uppercase;width:130px;">Published</th>
                <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:700;color:#94a3b8;letter-spacing:.06em;text-transform:uppercase;width:90px;">Links</th>
                <th style="padding:10px 16px;width:80px;"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr style="border-bottom:1px solid #f1f5f9;" class="news-row">
                <td style="padding:13px 16px;">
                    <div style="font-size:14px;font-weight:700;color:#0f172a;line-height:1.3;">{{ $item->title }}</div>
                </td>
                <td style="padding:13px 16px;font-size:12px;color:#64748b;white-space:nowrap;">
                    {{ $item->pub_date }}
                </td>
                <td style="padding:13px 16px;">
                    <div style="display:flex;gap:6px;">
                        @if($item->link)
                        <a href="{{ $item->link }}" target="_blank" title="External link"
                           style="display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:6px;background:#eff6ff;color:#1d4ed8;font-size:11px;">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        @endif
                        @if($item->pdf_path)
                        <a href="{{ $item->pdf_path }}" target="_blank" title="PDF"
                           style="display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:6px;background:#fef2f2;color:#dc2626;font-size:11px;">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                        @endif
                    </div>
                </td>
                <td style="padding:13px 16px;text-align:right;">
                    <div style="display:flex;gap:6px;justify-content:flex-end;">
                        <a href="{{ route('admin.news.edit', $item->id) }}"
                           style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;padding:5px 11px;border-radius:6px;border:1px solid #bfdbfe;background:#eff6ff;color:#1d4ed8;">
                            <i class="fas fa-pen" style="font-size:9px;"></i> Edit
                        </a>
                        <form method="POST" action="{{ route('admin.news.destroy', $item->id) }}"
                              onsubmit="return confirm('Delete this news item?')" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    style="font-size:11px;font-weight:600;padding:5px 10px;border-radius:6px;border:1px solid #fecaca;background:#fff5f5;color:#dc2626;cursor:pointer;">
                                <i class="fas fa-trash" style="font-size:10px;"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<style>
.news-row:hover td { background:#fafafa; }
.news-row:last-child { border-bottom: none; }
</style>

@endsection
