@extends('layouts.admin')
@section('title', 'Edit Work Order #' . $order->order_id)
@section('page-title', 'Edit Work Order')
@section('breadcrumb', 'NavalForge / Work Orders / #' . $order->order_id . ' / Edit')

@push('styles')
<style>
:root { --divider: #edf0f7; }

.ed-back {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 600; color: #94a3b8;
    text-decoration: none; margin-bottom: 20px;
}
.ed-back:hover { color: #475569; }

.ed-heading { font-size: 20px; font-weight: 700; color: #0f172a; letter-spacing: -.01em; margin-bottom: 4px; }
.ed-sub { font-size: 13px; color: #64748b; margin-bottom: 22px; }

/* Cards */
.ed-card {
    background: #fff; border-radius: 12px;
    border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 16px;
}
.ed-card-hd {
    padding: 12px 18px; border-bottom: 1.5px solid var(--divider);
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .09em; color: #94a3b8;
}
.ed-card-body { padding: 16px 18px; }

/* Form fields */
.ed-field { margin-bottom: 16px; }
.ed-field:last-child { margin-bottom: 0; }
.ed-label {
    display: block; font-size: 11px; font-weight: 700;
    color: #64748b; margin-bottom: 6px; letter-spacing: .03em;
}
.ed-input, .ed-textarea, .ed-select {
    width: 100%; font-size: 13px; padding: 9px 12px;
    border: 1.5px solid #e2e8f0; border-radius: 8px;
    background: #fff; color: #0f172a; outline: none; font-family: inherit;
    transition: border-color .15s;
}
.ed-input:focus, .ed-textarea:focus, .ed-select:focus { border-color: #2563eb; }
.ed-input::placeholder, .ed-textarea::placeholder { color: #94a3b8; }
.ed-textarea { resize: vertical; min-height: 110px; line-height: 1.55; }
.ed-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

/* Worker checkboxes */
.ed-role-label {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .08em; color: #94a3b8;
    padding: 8px 0 6px; margin-top: 12px;
    border-top: 1px solid var(--divider);
}
.ed-role-label:first-child { margin-top: 0; border-top: none; }
.ed-worker-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
    gap: 6px;
}
.ed-worker-item {
    display: flex; align-items: center; gap: 8px;
    padding: 8px 12px; border: 1.5px solid #e2e8f0;
    border-radius: 8px; cursor: pointer;
    transition: border-color .12s, background .12s;
}
.ed-worker-item:hover { background: #f0f7ff; border-color: #bfdbfe; }
.ed-worker-item input[type="checkbox"] {
    width: 14px; height: 14px; accent-color: #2563eb; cursor: pointer; flex-shrink: 0;
}
.ed-worker-name { font-size: 12.5px; font-weight: 600; color: #1e293b; }

/* Material rows */
.mat-hdr {
    display: grid; grid-template-columns: 1fr 110px 32px; gap: 8px; align-items: end;
    margin-bottom: 6px;
}
.mat-hdr-lbl {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .08em; color: #94a3b8;
}
.mat-row {
    display: grid; grid-template-columns: 1fr 110px 32px; gap: 8px;
    align-items: center; margin-bottom: 6px;
}
.mat-select, .mat-qty {
    font-size: 13px; padding: 9px 10px;
    border: 1.5px solid #e2e8f0; border-radius: 8px;
    background: #fff; color: #0f172a; outline: none;
    width: 100%; font-family: inherit; transition: border-color .15s;
}
.mat-select:focus, .mat-qty:focus { border-color: #2563eb; }
.mat-qty { font-variant-numeric: tabular-nums; }
.mat-del {
    width: 32px; height: 38px; display: flex; align-items: center; justify-content: center;
    border: 1.5px solid #fecaca; border-radius: 8px; background: #fff5f5;
    cursor: pointer; color: #dc2626; font-size: 13px; transition: opacity .12s;
}
.mat-del:hover { opacity: .7; }
.mat-add {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 600; padding: 8px 14px;
    border: 1.5px dashed #bfdbfe; border-radius: 8px;
    background: #f0f7ff; color: #2563eb; cursor: pointer; margin-top: 4px;
    transition: opacity .12s;
}
.mat-add:hover { opacity: .75; }

/* Footer */
.ed-footer {
    display: flex; justify-content: flex-end; gap: 10px; padding-top: 4px;
}
.ed-cancel {
    font-size: 13px; font-weight: 600; padding: 9px 20px;
    border: 1.5px solid #e2e8f0; border-radius: 8px;
    background: #fff; color: #475569; cursor: pointer; text-decoration: none;
}
.ed-save {
    font-size: 13px; font-weight: 600; padding: 9px 22px;
    background: #2563eb; color: #fff; border: none;
    border-radius: 8px; cursor: pointer; transition: opacity .15s;
}
.ed-save:hover { opacity: .88; }

.ed-error {
    margin-bottom: 16px; padding: 11px 16px;
    background: #fef2f2; border: 1px solid #fecaca;
    border-radius: 8px; font-size: 13px; color: #dc2626; font-weight: 500;
}
.ed-error-item { margin-top: 3px; }
</style>
@endpush

@section('content')

<a href="{{ route('work-orders.show', $order->order_id) }}" class="ed-back">
    <svg width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
        <path d="M8 2L3 6.5 8 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    Back to order #{{ $order->order_id }}
</a>

<div class="ed-heading">{{ $order->title }}</div>
<div class="ed-sub">Ship: {{ $order->ship_name }}</div>

@if($errors->any())
<div class="ed-error">
    Please fix the following errors:
    @foreach($errors->all() as $err)
        <div class="ed-error-item">· {{ $err }}</div>
    @endforeach
</div>
@endif

<form method="POST" action="{{ route('work-orders.update', $order->order_id) }}">
    @csrf @method('PATCH')

    {{-- Job details --}}
    <div class="ed-card">
        <div class="ed-card-hd">Job details</div>
        <div class="ed-card-body">
            <div class="ed-field">
                <label class="ed-label">Job title *</label>
                <input type="text" name="title" class="ed-input"
                       value="{{ old('title', $order->title) }}" required>
            </div>
            <div class="ed-field">
                <label class="ed-label">Description</label>
                <textarea name="description" class="ed-textarea">{{ old('description', $order->description) }}</textarea>
            </div>
            <div class="ed-row2">
                <div class="ed-field">
                    <label class="ed-label">Start date *</label>
                    <input type="date" name="start_date" class="ed-input"
                           value="{{ old('start_date', $order->start_date) }}" required>
                </div>
                <div class="ed-field">
                    <label class="ed-label">End date *</label>
                    <input type="date" name="end_date" class="ed-input"
                           value="{{ old('end_date', $order->end_date) }}" required>
                </div>
            </div>
            <div class="ed-field">
                <label class="ed-label">Status *</label>
                <select name="status" class="ed-select" required>
                    <option value="pending"     {{ old('status', $order->status) === 'pending'     ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ old('status', $order->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="done"        {{ old('status', $order->status) === 'done'        ? 'selected' : '' }}>Done</option>
                </select>
            </div>
            <div class="ed-field" style="margin-top:4px;">
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                    <input type="checkbox" name="is_outdoor_sensitive" value="1"
                           {{ old('is_outdoor_sensitive', $order->is_outdoor_sensitive) ? 'checked' : '' }}
                           style="width:15px;height:15px;accent-color:#f59e0b;cursor:pointer;flex-shrink:0;">
                    <span>
                        <span style="font-size:12px;font-weight:700;color:#0f172a;">Outdoor-sensitive job</span>
                        <span style="font-size:11px;color:#94a3b8;display:block;margin-top:1px;">Flag if bad weather (rain, high wind) affects this work</span>
                    </span>
                </label>
            </div>
        </div>
    </div>

    {{-- Workers --}}
    <div class="ed-card">
        <div class="ed-card-hd">Assigned workers</div>
        <div class="ed-card-body">
            @php
                $byRole = [];
                foreach ($allWorkers as $w) {
                    $byRole[$w->role ?? 'Other'][] = $w;
                }
                ksort($byRole);
            @endphp
            @forelse($byRole as $role => $group)
                <div class="ed-role-label">{{ $role }}</div>
                <div class="ed-worker-grid">
                    @foreach($group as $w)
                    <label class="ed-worker-item">
                        <input type="checkbox" name="worker_ids[]" value="{{ $w->worker_id }}"
                               {{ in_array($w->worker_id, $assignedIds) ? 'checked' : '' }}>
                        <span class="ed-worker-name">{{ $w->name }}</span>
                    </label>
                    @endforeach
                </div>
            @empty
                <div style="font-size:13px;color:#94a3b8;font-style:italic;">No workers in system.</div>
            @endforelse
        </div>
    </div>

    {{-- Materials --}}
    <div class="ed-card">
        <div class="ed-card-hd">Materials used</div>
        <div class="ed-card-body">
            <div class="mat-hdr">
                <span class="mat-hdr-lbl">Material</span>
                <span class="mat-hdr-lbl">Quantity</span>
                <span></span>
            </div>
            <div id="mat-rows">
            @forelse($usedMaterials as $u)
                <div class="mat-row">
                    <select name="material_id[]" class="mat-select">
                        <option value="">— select —</option>
                        @foreach($allMaterials as $m)
                            <option value="{{ $m->material_id }}"
                                    {{ $m->material_id == $u->material_id ? 'selected' : '' }}>
                                {{ $m->name }}{{ $m->unit ? ' ('.$m->unit.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <input type="number" name="qty_used[]" class="mat-qty"
                           min="0.01" step="0.01" value="{{ $u->qty_used }}">
                    <button type="button" class="mat-del" onclick="this.closest('.mat-row').remove()">
                        &times;
                    </button>
                </div>
            @empty
                <div id="mat-empty-hint" style="font-size:13px;color:#94a3b8;font-style:italic;margin-bottom:8px;">
                    No materials recorded — add one below.
                </div>
            @endforelse
            </div>
            <button type="button" class="mat-add" id="mat-add">
                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" aria-hidden="true">
                    <path d="M5.5 1v9M1 5.5h9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                Add material
            </button>
        </div>
    </div>

    <div class="ed-footer">
        <a href="{{ route('work-orders.show', $order->order_id) }}" class="ed-cancel">Cancel</a>
        <button type="submit" class="ed-save">Save changes</button>
    </div>
</form>

<script>
(function () {
    var allMaterials = @json($allMaterials);

    function buildOptions(selectedId) {
        var html = '<option value="">— select —</option>';
        allMaterials.forEach(function (m) {
            var sel = (selectedId && m.material_id == selectedId) ? ' selected' : '';
            var lbl = m.name + (m.unit ? ' (' + m.unit + ')' : '');
            html += '<option value="' + m.material_id + '"' + sel + '>' + lbl + '</option>';
        });
        return html;
    }

    document.getElementById('mat-add').addEventListener('click', function () {
        var hint = document.getElementById('mat-empty-hint');
        if (hint) hint.remove();

        var row = document.createElement('div');
        row.className = 'mat-row';
        row.innerHTML =
            '<select name="material_id[]" class="mat-select">' + buildOptions(null) + '</select>' +
            '<input type="number" name="qty_used[]" class="mat-qty" min="0.01" step="0.01" placeholder="0">' +
            '<button type="button" class="mat-del" onclick="this.closest(\'.mat-row\').remove()">&times;</button>';
        document.getElementById('mat-rows').appendChild(row);
        row.querySelector('select').focus();
    });
}());
</script>

@endsection
