@extends('layouts.admin')
@section('title', 'Register Ship')
@section('page-title', 'Register Ship')
@section('breadcrumb', 'NavalForge / Ships / Register')
@section('content')

<div style="margin-bottom:1.5rem;">
    <a href="{{ route('ships.index') }}"
       style="font-size:13px;color:#64748b;display:inline-flex;align-items:center;gap:6px;margin-bottom:12px;">
        <i class="fas fa-arrow-left"></i> Back to ships
    </a>
    <div style="font-size:11px;font-weight:600;color:#2563eb;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px;">Registry</div>
    <h1 style="font-size:22px;font-weight:700;color:#0f172a;">Register new ship</h1>
</div>

<div style="max-width:680px;">
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:2rem;">

        @if($errors->any())
        <div style="margin-bottom:1.2rem;padding:12px 16px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;font-size:13px;color:#991b1b;">
            <div style="font-weight:600;margin-bottom:6px;display:flex;align-items:center;gap:7px;">
                <i class="fas fa-exclamation-circle"></i> Please fix the following:
            </div>
            <ul style="margin-left:1rem;list-style:disc;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('ships.store') }}">
            @csrf

            {{-- Row 1: Name + Type --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px;">
                        Ship name <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="text" name="ship_name" value="{{ old('ship_name') }}"
                           placeholder="e.g. MV Ocean Star"
                           style="width:100%;font-size:13px;padding:9px 12px;border:1.5px solid {{ $errors->has('ship_name') ? '#ef4444' : '#e2e8f0' }};border-radius:8px;outline:none;color:#0f172a;">
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px;">Ship type</label>
                    <input type="text" name="ship_type" value="{{ old('ship_type') }}"
                           placeholder="e.g. Cargo, Tanker, Bulk carrier"
                           style="width:100%;font-size:13px;padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;outline:none;color:#0f172a;">
                </div>
            </div>

            {{-- Row 2: Owner + Flag --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px;">Owner / Company</label>
                    <input type="text" name="owner_name" value="{{ old('owner_name') }}"
                           placeholder="e.g. Pacific Shipping Ltd."
                           style="width:100%;font-size:13px;padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;outline:none;color:#0f172a;">
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px;">Flag country</label>
                    <input type="text" name="flag_country" value="{{ old('flag_country') }}"
                           placeholder="e.g. Panama, Liberia"
                           style="width:100%;font-size:13px;padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;outline:none;color:#0f172a;">
                </div>
            </div>

            {{-- Row 3: Tonnage + Arrival date --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px;">Gross tonnage (t)</label>
                    <input type="number" name="tonnage" value="{{ old('tonnage') }}"
                           placeholder="e.g. 5000" min="1"
                           style="width:100%;font-size:13px;padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;outline:none;color:#0f172a;">
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px;">Arrival date</label>
                    <input type="date" name="arrival_date" value="{{ old('arrival_date', date('Y-m-d')) }}"
                           style="width:100%;font-size:13px;padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:8px;outline:none;color:#0f172a;">
                </div>
            </div>

            {{-- Status --}}
            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px;">
                    Status <span style="color:#ef4444;">*</span>
                </label>
                <div style="display:flex;gap:10px;">
                    @foreach(['docked' => ['#eff6ff','#1e40af','fa-anchor'], 'in_repair' => ['#fef3c7','#92400e','fa-tools'], 'departed' => ['#f1f5f9','#475569','fa-sign-out-alt']] as $val => $cfg)
                    <label style="flex:1;cursor:pointer;">
                        <input type="radio" name="status" value="{{ $val }}"
                               {{ old('status', 'docked') === $val ? 'checked' : '' }}
                               style="display:none;" class="status-radio">
                        <div class="status-card" data-val="{{ $val }}"
                             style="padding:12px;border:2px solid {{ old('status', 'docked') === $val ? '#2563eb' : '#e2e8f0' }};border-radius:8px;text-align:center;background:{{ old('status', 'docked') === $val ? '#eff6ff' : '#fff' }};transition:all .15s;">
                            <i class="fas {{ $cfg[2] }}" style="font-size:16px;color:{{ $cfg[1] }};margin-bottom:6px;display:block;"></i>
                            <div style="font-size:12px;font-weight:600;color:{{ $cfg[1] }};">{{ ucfirst(str_replace('_',' ',$val)) }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div style="display:flex;gap:10px;">
                <button type="submit"
                        style="display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:600;padding:10px 24px;background:#2563eb;color:#fff;border:none;border-radius:8px;cursor:pointer;">
                    <i class="fas fa-plus-circle"></i> Register ship
                </button>
                <a href="{{ route('ships.index') }}"
                   style="display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:600;padding:10px 24px;border:1.5px solid #e2e8f0;color:#64748b;border-radius:8px;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.status-radio').forEach(radio => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('.status-card').forEach(card => {
            card.style.border = '2px solid #e2e8f0';
            card.style.background = '#fff';
        });
        const selected = document.querySelector(`.status-card[data-val="${radio.value}"]`);
        selected.style.border = '2px solid #2563eb';
        selected.style.background = '#eff6ff';
    });
});
</script>

@endsection
