@extends('layouts.admin')
@section('title', 'Inbox')
@section('page-title', 'Inbox')
@section('breadcrumb', 'NavalForge / Inbox')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.4rem;">
    <div>
        <div style="font-size:11px;font-weight:700;color:#2563eb;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px;">Contact messages</div>
        <h1 style="font-size:20px;font-weight:700;color:#0f172a;">Inbox
            @if($unread > 0)
            <span style="display:inline-block;font-size:11px;font-weight:700;background:#dc2626;color:#fff;padding:1px 8px;border-radius:999px;vertical-align:middle;margin-left:6px;">
                {{ $unread }} new
            </span>
            @endif
        </h1>
    </div>
</div>

@if(session('success'))
<div style="margin-bottom:1rem;padding:11px 16px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;font-size:13px;color:#166534;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
    @forelse($messages as $msg)
    <div style="display:flex;align-items:flex-start;gap:14px;padding:16px 20px;border-bottom:1px solid #f1f5f9;{{ !$msg->is_read ? 'background:#f8faff;' : '' }}">
        <div style="width:36px;height:36px;border-radius:50%;background:{{ !$msg->is_read ? '#2563eb' : '#e2e8f0' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
            <i class="fas fa-user" style="font-size:13px;color:{{ !$msg->is_read ? '#fff' : '#94a3b8' }};"></i>
        </div>
        <div style="flex:1;min-width:0;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:3px;">
                <span style="font-size:13px;font-weight:{{ !$msg->is_read ? '700' : '500' }};color:#0f172a;">{{ $msg->name }}</span>
                <span style="font-size:11px;color:#94a3b8;">{{ $msg->email }}</span>
                @if(!$msg->is_read)
                <span style="font-size:10px;font-weight:700;background:#2563eb;color:#fff;padding:1px 7px;border-radius:999px;">New</span>
                @endif
            </div>
            <div style="font-size:13px;font-weight:600;color:#374151;margin-bottom:4px;">{{ $msg->subject }}</div>
            <div style="font-size:12px;color:#64748b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:600px;">{{ $msg->preview }}</div>
        </div>
        <div style="display:flex;align-items:center;gap:12px;flex-shrink:0;">
            <span style="font-size:11px;color:#94a3b8;white-space:nowrap;">{{ $msg->received_at }}</span>
            <a href="{{ route('admin.messages.show', $msg->id) }}"
               style="font-size:12px;font-weight:600;color:#2563eb;white-space:nowrap;">Read</a>
            <form method="POST" action="{{ route('admin.messages.destroy', $msg->id) }}"
                  onsubmit="return confirm('Delete this message?')" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" style="font-size:12px;font-weight:500;color:#dc2626;background:none;border:none;cursor:pointer;">Delete</button>
            </form>
        </div>
    </div>
    @empty
    <div style="padding:3.5rem;text-align:center;color:#94a3b8;">
        <i class="fas fa-inbox" style="font-size:32px;display:block;margin-bottom:12px;opacity:.25;"></i>
        No messages yet.
    </div>
    @endforelse
</div>

@endsection
