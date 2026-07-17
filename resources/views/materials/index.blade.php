@extends('layouts.admin')
@section('title', 'Materials')
@section('page-title', 'Materials')
@section('breadcrumb', 'NavalForge / Materials')

@push('styles')
<style>
:root {
    --divider: #edf0f7;
    --track:   #e4e9f2;
}

/* ── Flash ── */
.mt-flash {
    margin-bottom: 16px; padding: 11px 16px;
    background: #dcfce7; border: 1px solid #bbf7d0;
    border-radius: 8px; font-size: 13px; color: #15803d; font-weight: 500;
}

/* ── Header ── */
.mt-header {
    display: flex; align-items: center; justify-content: space-between;
    gap: 12px; margin-bottom: 18px; flex-wrap: wrap;
}
.mt-header-left { display: flex; align-items: center; gap: 10px; }
.mt-title { font-size: 20px; font-weight: 700; color: #0f172a; letter-spacing: -.01em; }
.mt-count {
    font-size: 12px; font-weight: 500; color: #475569;
    background: #e2e8f0; padding: 4px 11px; border-radius: 999px;
}
.mt-btn-add {
    display: inline-flex; align-items: center; gap: 7px;
    font-size: 13px; font-weight: 600;
    padding: 9px 18px; background: #2563eb; color: #fff;
    border: none; border-radius: 8px; cursor: pointer;
    text-decoration: none; white-space: nowrap; transition: opacity .15s;
}
.mt-btn-add:hover { opacity: .88; color: #fff; }

/* ── Filters ── */
.mt-filters {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 14px; flex-wrap: wrap;
}
.mt-search {
    font-size: 13px; padding: 8px 14px; width: 210px;
    border: 1.5px solid #e2e8f0; border-radius: 8px;
    background: #fff; color: #0f172a; outline: none; transition: border-color .15s;
}
.mt-search:focus { border-color: #2563eb; }
.mt-search::placeholder { color: #94a3b8; }
.mt-select {
    font-size: 13px; padding: 8px 12px;
    border: 1.5px solid #e2e8f0; border-radius: 8px;
    background: #fff; color: #0f172a; outline: none; cursor: pointer;
    transition: border-color .15s;
}
.mt-select:focus { border-color: #2563eb; }
.mt-reset {
    font-size: 12px; font-weight: 600; color: #94a3b8;
    background: none; border: none; cursor: pointer; padding: 4px 6px;
    transition: color .12s;
}
.mt-reset:hover { color: #475569; }

/* ── Table card ── */
.mt-card {
    background: #fff; border-radius: 12px;
    border: 1px solid #e2e8f0; overflow: hidden;
}
.mt-table { width: 100%; border-collapse: collapse; }
.mt-table thead th {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .08em; color: #94a3b8;
    padding: 12px 18px 10px; text-align: left;
    border-bottom: 1.5px solid var(--divider);
    white-space: nowrap;
}
.mt-table tbody tr {
    border-bottom: 1px solid var(--divider); transition: background .1s;
}
.mt-table tbody tr:last-child { border-bottom: none; }
.mt-table tbody tr:hover { background: #fafbfc; }
.mt-table tbody td {
    padding: 14px 18px; vertical-align: middle;
    font-size: 13px; color: #475569;
}

/* Name cell */
.mt-name { font-size: 13.5px; font-weight: 600; color: #1e293b; }

/* Category pill */
.mt-cat {
    display: inline-block; font-size: 11px; font-weight: 600;
    padding: 3px 10px; background: #f1f5f9; color: #475569;
    border-radius: 999px; white-space: nowrap; letter-spacing: .02em;
}

/* Quantity cell */
.mt-qty-num { font-size: 13px; font-weight: 600; color: #1e293b; }
.mt-qty-thr { font-size: 11px; color: #94a3b8; margin-left: 4px; }

/* Stock bar */
.mt-bar-wrap { width: 140px; }
.mt-bar-track {
    height: 6px; background: #e2e8f0; border-radius: 999px; overflow: hidden;
}
.mt-bar-fill {
    height: 100%; border-radius: 999px; transition: width .3s;
}
.fill-red    { background: #ef4444; }
.fill-amber  { background: #f59e0b; }
.fill-green  { background: #22c55e; }
.mt-bar-lbl  { font-size: 10px; color: #94a3b8; margin-top: 3px; }

/* Last restocked */
.mt-date { font-size: 12px; color: #94a3b8; white-space: nowrap; }

/* Actions */
.mt-actions-cell { text-align: right; white-space: nowrap; }
.mt-restock-link {
    font-size: 12.5px; font-weight: 600; color: #2563eb;
    background: none; border: none; cursor: pointer; padding: 0;
    text-decoration: none; transition: opacity .12s;
}
.mt-restock-link:hover { opacity: .7; }
.mt-edit-link {
    font-size: 12.5px; font-weight: 600; color: #64748b;
    background: none; border: none; cursor: pointer; padding: 0;
    margin-left: 14px; text-decoration: none; transition: color .12s;
}
.mt-edit-link:hover { color: #1e293b; }

/* Empty state */
.mt-empty {
    text-align: center; padding: 52px;
    font-size: 13px; color: #94a3b8; font-style: italic;
}

/* ── Modal overlay ── */
.modal-overlay {
    display: none; position: fixed; inset: 0; z-index: 500;
    background: rgba(0,0,0,.35); align-items: center; justify-content: center;
}
.modal-overlay.open { display: flex; }
.modal-box {
    background: #fff; border-radius: 12px; border: 1px solid #e2e8f0;
    width: 100%; max-width: 420px; margin: 16px;
    box-shadow: 0 20px 60px rgba(0,0,0,.15);
}
.modal-hd {
    padding: 16px 20px; border-bottom: 1.5px solid #edf0f7;
    display: flex; align-items: center; justify-content: space-between;
}
.modal-title { font-size: 14px; font-weight: 700; color: #0f172a; }
.modal-close {
    font-size: 18px; color: #94a3b8; background: none; border: none;
    cursor: pointer; line-height: 1; padding: 2px 6px;
    border-radius: 4px; transition: color .12s;
}
.modal-close:hover { color: #475569; }
.modal-body { padding: 18px 20px; }
.modal-field { margin-bottom: 14px; }
.modal-field:last-child { margin-bottom: 0; }
.modal-label {
    display: block; font-size: 11px; font-weight: 700;
    color: #64748b; margin-bottom: 5px; letter-spacing: .03em;
}
.modal-input, .modal-select {
    width: 100%; font-size: 13px; padding: 9px 12px;
    border: 1.5px solid #e2e8f0; border-radius: 8px;
    background: #fff; color: #0f172a; outline: none; font-family: inherit;
    transition: border-color .15s;
}
.modal-input:focus, .modal-select:focus { border-color: #2563eb; }
.modal-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.modal-ft {
    padding: 14px 20px; border-top: 1px solid #edf0f7;
    display: flex; justify-content: flex-end; gap: 8px;
}
.modal-cancel {
    font-size: 13px; font-weight: 600; padding: 8px 18px;
    border: 1.5px solid #e2e8f0; border-radius: 8px;
    background: #fff; color: #475569; cursor: pointer;
}
.modal-submit {
    font-size: 13px; font-weight: 600; padding: 8px 20px;
    background: #2563eb; color: #fff; border: none;
    border-radius: 8px; cursor: pointer; transition: opacity .15s;
}
.modal-submit:hover { opacity: .88; }
</style>
@endpush

@section('content')

@if(session('success'))
<div class="mt-flash">{{ session('success') }}</div>
@endif

{{-- ── Header ── --}}
<div class="mt-header">
    <div class="mt-header-left">
        <span class="mt-title">Materials</span>
        <span class="mt-count" id="mt-count">{{ $total }} items</span>
    </div>
    <button type="button" class="mt-btn-add" onclick="openAdd()">
        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
            <path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        Add material
    </button>
</div>

{{-- ── Filters ── --}}
<div class="mt-filters">
    <input type="text" id="mt-search" class="mt-search" placeholder="Search material name…">
    <select id="mt-cat" class="mt-select">
        <option value="">All categories</option>
        @foreach(array_unique(array_column($materials, 'category')) as $cat)
            <option value="{{ strtolower($cat) }}">{{ $cat }}</option>
        @endforeach
    </select>
    <select id="mt-stock" class="mt-select">
        <option value="">All stock levels</option>
        <option value="out">Out of stock</option>
        <option value="low">Low stock</option>
        <option value="ok">Healthy</option>
    </select>
    <button type="button" class="mt-reset" id="mt-reset">Clear filters</button>
</div>

{{-- ── Table ── --}}
<div class="mt-card">
    <table class="mt-table">
        <thead>
            <tr>
                <th style="width:22%">Material name</th>
                <th style="width:12%">Category</th>
                <th style="width:16%">Quantity</th>
                <th style="width:18%">Stock level</th>
                <th style="width:14%">Last restocked</th>
                <th style="width:18%;text-align:right;padding-right:18px;">Actions</th>
            </tr>
        </thead>
        <tbody id="mt-tbody">

        @forelse($materials as $m)
            @php
                $qty = (int) $m->quantity;
                $thr = (int) $m->min_threshold;

                if ($thr > 0) {
                    $pct = min(100, round($qty / $thr * 100));
                } else {
                    $pct = $qty > 0 ? 100 : 0;
                }

                if ($qty === 0) {
                    $fillClass = 'fill-red';
                    $stockKey  = 'out';
                    $barLabel  = 'Out of stock';
                } elseif ($thr > 0 && $qty < $thr) {
                    $fillClass = $qty <= $thr * 0.5 ? 'fill-red' : 'fill-amber';
                    $stockKey  = 'low';
                    $barLabel  = 'Low stock';
                } else {
                    $fillClass = 'fill-green';
                    $stockKey  = 'ok';
                    $barLabel  = 'Healthy';
                }
            @endphp

            <tr data-name="{{ strtolower($m->name) }}"
                data-cat="{{ strtolower($m->category) }}"
                data-stock="{{ $stockKey }}">

                <td><span class="mt-name">{{ $m->name }}</span></td>

                <td><span class="mt-cat">{{ $m->category }}</span></td>

                <td>
                    <span class="mt-qty-num">{{ $qty }} {{ $m->unit }}</span>
                    @if($thr > 0)
                        <span class="mt-qty-thr">/ min {{ $thr }}</span>
                    @endif
                </td>

                <td>
                    <div class="mt-bar-wrap">
                        <div class="mt-bar-track">
                            <div class="mt-bar-fill {{ $fillClass }}"
                                 style="width: {{ $pct }}%"></div>
                        </div>
                        <div class="mt-bar-lbl">{{ $barLabel }}</div>
                    </div>
                </td>

                <td><span class="mt-date">{{ $m->last_restocked ?? '—' }}</span></td>

                <td class="mt-actions-cell">
                    <button type="button" class="mt-restock-link"
                            onclick="openRestock({{ $m->material_id }}, '{{ addslashes($m->name) }}', {{ $qty }}, '{{ addslashes($m->unit) }}')">
                        Restock
                    </button>
                    <button type="button" class="mt-edit-link"
                            onclick="openEdit({{ $m->material_id }}, '{{ addslashes($m->name) }}', '{{ addslashes($m->category) }}', {{ $qty }}, '{{ addslashes($m->unit) }}', {{ $thr }})">
                        Edit
                    </button>
                </td>
            </tr>
        @empty
            <tr><td class="mt-empty" colspan="6">No materials found.</td></tr>
        @endforelse

        <tr id="mt-empty" style="display:none;">
            <td colspan="6" class="mt-empty">No materials match your filters.</td>
        </tr>

        </tbody>
    </table>
</div>


{{-- ── Add modal ── --}}
<div class="modal-overlay" id="modal-add">
    <div class="modal-box">
        <div class="modal-hd">
            <span class="modal-title">Add material</span>
            <button class="modal-close" onclick="closeModal('modal-add')">&times;</button>
        </div>
        <form method="POST" action="{{ route('materials.store') }}">
            @csrf
            <div class="modal-body">
                <div class="modal-field">
                    <label class="modal-label">Material name *</label>
                    <input type="text" name="name" class="modal-input" required>
                </div>
                <div class="modal-field">
                    <label class="modal-label">Category *</label>
                    <select name="category" class="modal-select" required>
                        <option value="">— select —</option>
                        <option>Steel</option>
                        <option>Paint</option>
                        <option>Electrical</option>
                        <option>Mechanical</option>
                        <option>Welding</option>
                        <option>Fasteners</option>
                        <option>General</option>
                    </select>
                </div>
                <div class="modal-row2">
                    <div class="modal-field">
                        <label class="modal-label">Quantity *</label>
                        <input type="number" name="quantity" class="modal-input" min="0" required>
                    </div>
                    <div class="modal-field">
                        <label class="modal-label">Unit *</label>
                        <input type="text" name="unit" class="modal-input" placeholder="kg / pcs / m…" required>
                    </div>
                </div>
                <div class="modal-field">
                    <label class="modal-label">Minimum threshold *</label>
                    <input type="number" name="min_threshold" class="modal-input" min="0" required>
                </div>
            </div>
            <div class="modal-ft">
                <button type="button" class="modal-cancel" onclick="closeModal('modal-add')">Cancel</button>
                <button type="submit" class="modal-submit">Add material</button>
            </div>
        </form>
    </div>
</div>


{{-- ── Edit modal ── --}}
<div class="modal-overlay" id="modal-edit">
    <div class="modal-box">
        <div class="modal-hd">
            <span class="modal-title">Edit material</span>
            <button class="modal-close" onclick="closeModal('modal-edit')">&times;</button>
        </div>
        <form method="POST" id="edit-form" action="">
            @csrf @method('PATCH')
            <div class="modal-body">
                <div class="modal-field">
                    <label class="modal-label">Material name *</label>
                    <input type="text" name="name" id="edit-name" class="modal-input" required>
                </div>
                <div class="modal-field">
                    <label class="modal-label">Category *</label>
                    <select name="category" id="edit-category" class="modal-select" required>
                        <option value="">— select —</option>
                        <option>Steel</option>
                        <option>Paint</option>
                        <option>Electrical</option>
                        <option>Mechanical</option>
                        <option>Welding</option>
                        <option>Fasteners</option>
                        <option>General</option>
                    </select>
                </div>
                <div class="modal-row2">
                    <div class="modal-field">
                        <label class="modal-label">Quantity *</label>
                        <input type="number" name="quantity" id="edit-quantity" class="modal-input" min="0" required>
                    </div>
                    <div class="modal-field">
                        <label class="modal-label">Unit *</label>
                        <input type="text" name="unit" id="edit-unit" class="modal-input" required>
                    </div>
                </div>
                <div class="modal-field">
                    <label class="modal-label">Minimum threshold *</label>
                    <input type="number" name="min_threshold" id="edit-threshold" class="modal-input" min="0" required>
                </div>
            </div>
            <div class="modal-ft">
                <button type="button" class="modal-cancel" onclick="closeModal('modal-edit')">Cancel</button>
                <button type="submit" class="modal-submit">Save changes</button>
            </div>
        </form>
    </div>
</div>


{{-- ── Restock modal ── --}}
<div class="modal-overlay" id="modal-restock">
    <div class="modal-box" style="max-width:340px;">
        <div class="modal-hd">
            <span class="modal-title" id="restock-title">Restock</span>
            <button class="modal-close" onclick="closeModal('modal-restock')">&times;</button>
        </div>
        <form method="POST" id="restock-form" action="">
            @csrf @method('PATCH')
            <div class="modal-body">
                <div style="font-size:13px;color:#64748b;margin-bottom:14px;" id="restock-current"></div>
                <div class="modal-field">
                    <label class="modal-label">Quantity to add *</label>
                    <input type="number" name="add_qty" id="restock-qty"
                           class="modal-input" min="1" required>
                </div>
            </div>
            <div class="modal-ft">
                <button type="button" class="modal-cancel" onclick="closeModal('modal-restock')">Cancel</button>
                <button type="submit" class="modal-submit">Add stock</button>
            </div>
        </form>
    </div>
</div>


<script>
(function () {
    /* ── Filters ── */
    var rows    = Array.from(document.querySelectorAll('#mt-tbody tr[data-name]'));
    var empty   = document.getElementById('mt-empty');
    var countEl = document.getElementById('mt-count');
    var term    = '';
    var cat     = '';
    var stock   = '';

    function apply() {
        var vis = 0;
        rows.forEach(function (row) {
            var ok = (!term  || row.dataset.name.indexOf(term)   !== -1) &&
                     (!cat   || row.dataset.cat  === cat)  &&
                     (!stock || row.dataset.stock === stock);
            row.style.display = ok ? '' : 'none';
            if (ok) vis++;
        });
        empty.style.display  = vis === 0 ? 'table-row' : 'none';
        countEl.textContent  = vis + ' item' + (vis !== 1 ? 's' : '');
    }

    document.getElementById('mt-search').addEventListener('input', function () {
        term = this.value.toLowerCase().trim();
        apply();
    });
    document.getElementById('mt-cat').addEventListener('change', function () {
        cat = this.value;
        apply();
    });
    document.getElementById('mt-stock').addEventListener('change', function () {
        stock = this.value;
        apply();
    });
    document.getElementById('mt-reset').addEventListener('click', function () {
        term = cat = stock = '';
        document.getElementById('mt-search').value = '';
        document.getElementById('mt-cat').value    = '';
        document.getElementById('mt-stock').value  = '';
        apply();
    });

    /* ── Modals ── */
    window.openAdd = function () {
        document.getElementById('modal-add').classList.add('open');
    };

    window.openEdit = function (id, name, category, qty, unit, thr) {
        document.getElementById('edit-form').action = '/materials/' + id;
        document.getElementById('edit-name').value      = name;
        document.getElementById('edit-quantity').value  = qty;
        document.getElementById('edit-unit').value      = unit;
        document.getElementById('edit-threshold').value = thr;
        var sel = document.getElementById('edit-category');
        for (var i = 0; i < sel.options.length; i++) {
            sel.options[i].selected = sel.options[i].value === category;
        }
        document.getElementById('modal-edit').classList.add('open');
    };

    window.openRestock = function (id, name, qty, unit) {
        document.getElementById('restock-form').action = '/materials/' + id + '/restock';
        document.getElementById('restock-title').textContent   = 'Restock — ' + name;
        document.getElementById('restock-current').textContent = 'Current stock: ' + qty + ' ' + unit;
        document.getElementById('restock-qty').value = '';
        document.getElementById('modal-restock').classList.add('open');
        document.getElementById('restock-qty').focus();
    };

    window.closeModal = function (id) {
        document.getElementById(id).classList.remove('open');
    };

    /* Close on overlay click */
    document.querySelectorAll('.modal-overlay').forEach(function (el) {
        el.addEventListener('click', function (e) {
            if (e.target === el) el.classList.remove('open');
        });
    });

    /* Close on Escape */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.open').forEach(function (el) {
                el.classList.remove('open');
            });
        }
    });
}());
</script>

@endsection
