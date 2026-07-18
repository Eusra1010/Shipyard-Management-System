@extends('layouts.admin')

@section('title', 'No Access')
@section('page-title', 'Account Not Set Up')

@section('content')
<div style="max-width:440px;margin:60px auto;text-align:center;">
    <div style="width:64px;height:64px;border-radius:50%;background:#f1f5f9;
                display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
        <i class="fas fa-lock" style="font-size:24px;color:#94a3b8;"></i>
    </div>
    <h2 style="font-size:17px;font-weight:700;color:#0f172a;margin-bottom:8px;">
        No dashboard assigned
    </h2>
    <p style="font-size:13px;color:#64748b;line-height:1.65;margin-bottom:20px;">
        Your account role hasn't been configured yet.<br>
        Contact an administrator to assign your role.
    </p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
                style="font-size:13px;font-weight:600;padding:9px 24px;
                       border:1.5px solid #e2e8f0;border-radius:8px;
                       background:#fff;color:#475569;cursor:pointer;">
            Sign out
        </button>
    </form>
</div>
@endsection
