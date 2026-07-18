@extends('layouts.admin')
@section('title', 'Message')
@section('page-title', 'Message')
@section('breadcrumb', 'NavalForge / Inbox / Message')

@section('content')

<div style="margin-bottom:1.2rem;">
    <a href="{{ route('admin.messages.index') }}" style="font-size:13px;color:#64748b;display:inline-flex;align-items:center;gap:5px;">
        <i class="fas fa-arrow-left" style="font-size:10px;"></i> Back to Inbox
    </a>
</div>

<div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;max-width:720px;">
    <div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:18px 24px;">
        <div style="font-size:17px;font-weight:700;color:#0f172a;margin-bottom:10px;">{{ $msg->subject }}</div>
        <div style="display:flex;align-items:center;gap:20px;font-size:12px;color:#64748b;">
            <span><strong style="color:#374151;">From:</strong> {{ $msg->name }}</span>
            <span><strong style="color:#374151;">Email:</strong>
                <a href="mailto:{{ $msg->email }}" style="color:#2563eb;">{{ $msg->email }}</a>
            </span>
            <span><strong style="color:#374151;">Received:</strong> {{ $msg->received_at }}</span>
        </div>
    </div>
    <div style="padding:24px;font-size:14px;color:#374151;line-height:1.8;white-space:pre-wrap;">{{ $msg->message }}</div>
    <div style="padding:16px 24px;border-top:1px solid #f1f5f9;display:flex;align-items:center;gap:12px;">
        <a href="mailto:{{ $msg->email }}?subject=Re: {{ urlencode($msg->subject) }}"
           style="display:inline-flex;align-items:center;gap:7px;font-size:13px;font-weight:600;padding:8px 18px;background:#2563eb;color:#fff;border-radius:8px;text-decoration:none;">
            <i class="fas fa-reply"></i> Reply via email
        </a>
        <form method="POST" action="{{ route('admin.messages.destroy', $msg->id) }}"
              onsubmit="return confirm('Delete this message?')" style="display:inline;">
            @csrf @method('DELETE')
            <button type="submit"
                    style="display:inline-flex;align-items:center;gap:7px;font-size:13px;font-weight:600;padding:8px 18px;background:#fee2e2;color:#b91c1c;border:none;border-radius:8px;cursor:pointer;">
                <i class="fas fa-trash"></i> Delete
            </button>
        </form>
    </div>
</div>

@endsection
