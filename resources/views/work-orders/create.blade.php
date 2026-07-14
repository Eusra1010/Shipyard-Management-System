@extends('layouts.admin')

@section('title', 'New Work Order')
@section('page-title', 'New Work Order')
@section('breadcrumb', 'NavalForge / Work Orders / New')

@section('content')

<div style="margin-bottom:1.2rem;">
    <div style="font-size:11px;font-weight:600;color:#2563eb;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px;">Operations</div>
    <h1 style="font-size:20px;font-weight:700;color:#0f172a;">Create work order</h1>
    <p style="font-size:13px;color:#64748b;margin-top:4px;">Fill in the details below to assign a repair or maintenance job to a ship.</p>
</div>

<div style="max-width:640px;">
    <div style="background:#fff;border:1px solid #d4dae8;border-radius:12px;padding:2rem;">

        @if($errors->any())
        <div style="margin-bottom:1.2rem;padding:12px 16px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;font-size:13px;color:#991b1b;">
            <div style="font-weight:600;margin-bottom:4px;">Please fix the following:</div>
            <ul style="margin-left:1rem;list-style:disc;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('work-orders.store') }}">
            @csrf

            {{-- Ship --}}
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px;">
                    Ship <span style="color:#ef4444;">*</span>
                </label>
                <select name="ship_id" required
                        style="width:100%;font-size:13px;padding:9px 12px;border:1.5px solid {{ $errors->has('ship_id') ? '#ef4444' : '#e2e8f0' }};border-radius:8px;outline:none;color:#0f172a;background:#fff;">
                    <option value="">— select a ship —</option>
                    @foreach($ships as $ship)
                        <option value="{{ $ship->ship_id }}" {{ old('ship_id') == $ship->ship_id ? 'selected' : '' }}>
                            {{ $ship->ship_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Title --}}
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px;">
                    Job title <span style="color:#ef4444;">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}"
                       placeholder="e.g. Hull plate replacement — port side"
                       style="width:100%;font-size:13px;padding:9px 12px;border:1.5px solid {{ $errors->has('title') ? '#ef4444' : '#e2e8f0' }};border-radius:8px;outline:none;color:#0f172a;">
            </div>

            {{-- Description --}}
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px;">Description</label>
                <textarea name="description" rows="3" placeholder="Describe the work to be done..."
                          style="width:100%;font-size:13px;padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;outline:none;color:#0f172a;resize:vertical;">{{ old('description') }}</textarea>
            </div>

            {{-- Dates --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px;">
                        Start date <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}"
                           style="width:100%;font-size:13px;padding:9px 12px;border:1.5px solid {{ $errors->has('start_date') ? '#ef4444' : '#e2e8f0' }};border-radius:8px;outline:none;color:#0f172a;">
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px;">
                        End date <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}"
                           style="width:100%;font-size:13px;padding:9px 12px;border:1.5px solid {{ $errors->has('end_date') ? '#ef4444' : '#e2e8f0' }};border-radius:8px;outline:none;color:#0f172a;">
                </div>
            </div>

            <div style="display:flex;gap:10px;">
                <button type="submit"
                        style="display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:600;padding:10px 24px;background:#2563eb;color:#fff;border:none;border-radius:8px;cursor:pointer;">
                    <i class="fas fa-plus-circle"></i> Create order
                </button>
                <a href="{{ route('dashboard') }}"
                   style="display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:600;padding:10px 24px;border:1.5px solid #e2e8f0;color:#64748b;border-radius:8px;">
                    Cancel
                </a>
            </div>
        </form>
    </div>

</div>

@endsection
