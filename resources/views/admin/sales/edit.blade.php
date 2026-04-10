@extends('layouts.backend')

@section('title', 'Edit Sale — ' . $sale->name)

@section('styles')
<style>
    .fc { background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:34px 38px; box-shadow:0 1px 3px rgba(0,0,0,.05),0 4px 16px rgba(0,0,0,.04); }
    .page-back { display:inline-flex;align-items:center;gap:7px;color:#64748b;font-size:.88rem;text-decoration:none;padding:7px 14px;border-radius:8px;border:1px solid #e5e7eb;background:#fff;transition:all .15s; }
    .page-back:hover { background:#fef2f2;color:#ef4444;border-color:#fca5a5; }
    .form-label { font-weight:600;font-size:.875rem;color:#374151;margin-bottom:6px; }
    .req { color:#ef4444; }
    .form-control,.form-select { border:1.5px solid #e5e7eb;border-radius:9px;padding:10px 14px;font-size:.9rem;background:#fafafa;transition:border-color .15s,box-shadow .15s; }
    .form-control:focus,.form-select:focus { border-color:#ef4444;box-shadow:0 0 0 3px rgba(239,68,68,.1);background:#fff;outline:none; }

    /* Discount type tiles */
    .tile-row { display:flex;gap:10px; }
    .tile { flex:1;padding:14px 10px;border:1.5px solid #e5e7eb;border-radius:10px;text-align:center;cursor:pointer;transition:all .15s;user-select:none; }
    .tile:hover { border-color:#fca5a5;background:#fef2f2; }
    .tile.sel   { border-color:#ef4444;background:#fef2f2;color:#dc2626; }
    .tile-icon  { font-size:1.3rem;display:block;margin-bottom:4px; }
    .tile-lbl   { font-size:.82rem;font-weight:700;display:block; }
    .tile-sub   { font-size:.72rem;color:#94a3b8;display:block;margin-top:2px; }
    .tile.sel .tile-sub { color:#fca5a5; }

    /* Scope tiles — 3 options */
    .scope-tile-row { display:flex;gap:10px; }
    .scope-tile { flex:1;padding:14px 10px;border:1.5px solid #e5e7eb;border-radius:10px;text-align:center;cursor:pointer;transition:all .15s;user-select:none; }
    .scope-tile:hover { border-color:#fca5a5;background:#fef2f2; }
    .scope-tile.sel   { border-color:#ef4444;background:#fef2f2;color:#dc2626; }
    .scope-tile .tile-sub { font-size:.72rem;color:#94a3b8;display:block;margin-top:2px; }
    .scope-tile.sel .tile-sub { color:#fca5a5; }

    .tag-dot { display:inline-block;width:9px;height:9px;border-radius:50%;margin-right:5px;vertical-align:middle; }

    .divider { height:1px;background:#e5e7eb;margin:26px 0; }
    .hint { font-size:.78rem;color:#94a3b8;margin-top:5px; }

    .switch-wrap { display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border:1.5px solid #e5e7eb;border-radius:10px;background:#fafafa; }
    .form-switch .form-check-input { width:44px;height:24px;cursor:pointer; }
    .form-switch .form-check-input:checked { background-color:#ef4444;border-color:#ef4444; }

    .btn-save { background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;border:none;border-radius:10px;padding:12px 36px;font-size:.95rem;font-weight:700;cursor:pointer;transition:all .2s;box-shadow:0 4px 12px rgba(239,68,68,.3); }
    .btn-save:hover { transform:translateY(-1px);box-shadow:0 6px 20px rgba(239,68,68,.4); }
    .btn-cancel { background:#fff;color:#64748b;border:1.5px solid #e5e7eb;border-radius:10px;padding:12px 28px;font-size:.9rem;font-weight:600;text-decoration:none;display:inline-block;transition:all .15s; }
    .btn-cancel:hover { background:#f8fafc;color:#374151; }

    .status-strip { display:flex;align-items:center;gap:10px;padding:14px 18px;border-radius:12px;margin-bottom:24px;font-size:.88rem; }
    .strip-live     { background:#d1fae5;border:1px solid #6ee7b7;color:#065f46; }
    .strip-upcoming { background:#fef3c7;border:1px solid #fcd34d;color:#92400e; }
    .strip-ended    { background:#fee2e2;border:1px solid #fca5a5;color:#991b1b; }
</style>
@endsection

@section('content')
<div class="container-fluid py-4 px-4" style="background:#f8fafc;min-height:100vh;">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.sales.index') }}" class="page-back">← Back</a>
        <div>
            <h2 style="margin:0;font-weight:800;font-size:1.35rem;color:#1e293b;">Edit Sale</h2>
            <small style="color:#94a3b8;">{{ $sale->name }}</small>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-7 col-lg-9">

            {{-- Status strip --}}
            @if($sale->isActive())
                <div class="status-strip strip-live">🔥 <strong>This sale is currently LIVE</strong> — changes take effect immediately.</div>
            @elseif($sale->is_active && now()->lt($sale->starts_at))
                <div class="status-strip strip-upcoming">⏳ <strong>Upcoming</strong> — starts {{ $sale->starts_at->diffForHumans() }}.</div>
            @else
                <div class="status-strip strip-ended">⏰ <strong>This sale has ended.</strong> Update dates to reactivate.</div>
            @endif

            <div class="fc">

                @if($errors->any())
                    <div class="alert mb-4" style="background:#fee2e2;color:#991b1b;border:none;border-radius:10px;font-size:.88rem;">
                        <strong>Fix these errors:</strong>
                        <ul class="mb-0 mt-1 ps-3">
                            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.sales.update', $sale) }}">
                    @csrf @method('PUT')
                    <input type="hidden" name="type"  id="f-type"  value="{{ old('type',  $sale->type) }}">
                    <input type="hidden" name="scope" id="f-scope" value="{{ old('scope', $sale->scope) }}">

                    {{-- Sale Name --}}
                    <div class="mb-4">
                        <label class="form-label">Sale Name <span class="req">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $sale->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="divider"></div>

                    {{-- Discount Type --}}
                    <div class="mb-4">
                        <label class="form-label">Discount Type <span class="req">*</span></label>
                        <div class="tile-row">
                            <div class="tile {{ old('type', $sale->type) === 'percent' ? 'sel' : '' }}"
                                 onclick="setType('percent', this)">
                                <span class="tile-icon">%</span>
                                <span class="tile-lbl">Percentage</span>
                                <span class="tile-sub">e.g. 20% off</span>
                            </div>
                            <div class="tile {{ old('type', $sale->type) === 'fixed' ? 'sel' : '' }}"
                                 onclick="setType('fixed', this)">
                                <span class="tile-icon">₹</span>
                                <span class="tile-lbl">Fixed Amount</span>
                                <span class="tile-sub">e.g. ₹100 off</span>
                            </div>
                        </div>
                    </div>

                    {{-- Discount Value --}}
                    <div class="mb-4">
                        <label class="form-label">
                            Discount Value <span class="req">*</span>
                            <span id="unit-lbl" class="fw-normal text-muted">
                                ({{ old('type', $sale->type) === 'percent' ? '% of price' : '₹ fixed amount' }})
                            </span>
                        </label>
                        <input type="number" name="discount" step="0.01" min="0.01"
                               class="form-control @error('discount') is-invalid @enderror"
                               value="{{ old('discount', $sale->discount) }}" required>
                        @error('discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="divider"></div>

                    {{-- Scope — 3 tiles --}}
                    <div class="mb-4">
                        <label class="form-label">Applies To <span class="req">*</span></label>
                        <div class="scope-tile-row">

                            <div class="scope-tile {{ old('scope', $sale->scope) === 'category' ? 'sel' : '' }}"
                                 onclick="setScope('category', this)">
                                <span class="tile-icon">🏷</span>
                                <span class="tile-lbl">Category</span>
                                <span class="tile-sub">e.g. Men's</span>
                            </div>

                            <div class="scope-tile {{ old('scope', $sale->scope) === 'product_type' ? 'sel' : '' }}"
                                 onclick="setScope('product_type', this)">
                                <span class="tile-icon">📦</span>
                                <span class="tile-lbl">Product Type</span>
                                <span class="tile-sub">e.g. Shirts</span>
                            </div>

                            {{-- Tag scope --}}
                            <div class="scope-tile {{ old('scope', $sale->scope) === 'tag' ? 'sel' : '' }}"
                                 onclick="setScope('tag', this)">
                                <span class="tile-icon">🔖</span>
                                <span class="tile-lbl">Tag</span>
                                <span class="tile-sub">e.g. New Arrival</span>
                            </div>

                        </div>
                    </div>

                    {{-- Category dropdown --}}
                    <div class="mb-4" id="wrap-cat"
                         style="display:{{ old('scope', $sale->scope) === 'category' ? 'block' : 'none' }}">
                        <label class="form-label">Category <span class="req">*</span></label>
                        <select name="scope_id" id="sel-cat" class="form-select">
                            <option value="">— Choose —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('scope_id', $sale->scope === 'category' ? $sale->scope_id : '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('scope_id') <div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div> @enderror
                    </div>

                    {{-- Product Type dropdown --}}
                    <div class="mb-4" id="wrap-type"
                         style="display:{{ old('scope', $sale->scope) === 'product_type' ? 'block' : 'none' }}">
                        <label class="form-label">Product Type <span class="req">*</span></label>
                        <select name="scope_id" id="sel-type" class="form-select">
                            <option value="">— Choose —</option>
                            @foreach($productTypes as $pt)
                                <option value="{{ $pt->id }}"
                                    {{ old('scope_id', $sale->scope === 'product_type' ? $sale->scope_id : '') == $pt->id ? 'selected' : '' }}>
                                    {{ $pt->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tag dropdown --}}
                    <div class="mb-4" id="wrap-tag"
                         style="display:{{ old('scope', $sale->scope) === 'tag' ? 'block' : 'none' }}">
                        <label class="form-label">Tag <span class="req">*</span></label>
                        <select name="scope_id" id="sel-tag" class="form-select"
                                onchange="updateTagHint(this)">
                            <option value="">— Choose Tag —</option>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}"
                                        data-color="{{ $tag->color }}"
                                        data-count="{{ $tag->products_count }}"
                                    {{ old('scope_id', $sale->scope === 'tag' ? $sale->scope_id : '') == $tag->id ? 'selected' : '' }}>
                                    {{ $tag->name }}  ({{ $tag->products_count }} products)
                                </option>
                            @endforeach
                        </select>
                        {{-- Tag color + count hint --}}
                        <div class="hint" id="tag-hint" style="display:none;">
                            <span id="tag-hint-dot" class="tag-dot"></span>
                            <span id="tag-hint-text"></span>
                        </div>
                        @error('scope_id') <div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div> @enderror
                    </div>

                    <div class="divider"></div>

                    {{-- Dates --}}
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label">Starts At <span class="req">*</span></label>
                            <input type="datetime-local" name="starts_at"
                                   class="form-control @error('starts_at') is-invalid @enderror"
                                   value="{{ old('starts_at', $sale->starts_at->format('Y-m-d\TH:i')) }}" required>
                            @error('starts_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ends At <span class="req">*</span></label>
                            <input type="datetime-local" name="ends_at"
                                   class="form-control @error('ends_at') is-invalid @enderror"
                                   value="{{ old('ends_at', $sale->ends_at->format('Y-m-d\TH:i')) }}" required>
                            @error('ends_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="divider"></div>

                    {{-- Status toggle --}}
                    <div class="mb-4">
                        <label class="form-label">Status</label>
                        <div class="switch-wrap">
                            <div>
                                <div style="font-weight:600;font-size:.9rem;color:#1e293b;">Active</div>
                                <div style="font-size:.78rem;color:#94a3b8;">When off, sale won't apply even within date range</div>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="is_active"
                                       {{ old('is_active', $sale->is_active) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn-save">💾 Save Changes</button>
                        <a href="{{ route('admin.sales.index') }}" class="btn-cancel">Cancel</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Tag data from PHP (for hint)
const TAG_DATA = @json($tags->keyBy('id')->map(fn($t) => ['name' => $t->name, 'color' => $t->color, 'count' => $t->products_count]));

/* ── Discount type ── */
function setType(v, el) {
    document.getElementById('f-type').value = v;
    document.querySelectorAll('.tile-row .tile').forEach(t => t.classList.remove('sel'));
    el.classList.add('sel');
    document.getElementById('unit-lbl').textContent =
        v === 'percent' ? '(% of price)' : '(₹ fixed amount)';
}

/* ── Scope ── */
function setScope(v, el) {
    document.getElementById('f-scope').value = v;
    document.querySelectorAll('.scope-tile').forEach(t => t.classList.remove('sel'));
    el.classList.add('sel');
    document.getElementById('wrap-cat').style.display  = v === 'category'     ? 'block' : 'none';
    document.getElementById('wrap-type').style.display = v === 'product_type' ? 'block' : 'none';
    document.getElementById('wrap-tag').style.display  = v === 'tag'          ? 'block' : 'none';
}

/* ── Tag hint ── */
function updateTagHint(sel) {
    const hint    = document.getElementById('tag-hint');
    const hintDot = document.getElementById('tag-hint-dot');
    const hintTxt = document.getElementById('tag-hint-text');

    if (sel.value && TAG_DATA[sel.value]) {
        const d = TAG_DATA[sel.value];
        hintDot.style.background = d.color;
        hintTxt.textContent      = '"' + d.name + '" tag mein ' + d.count + ' products hain — unpe ye sale apply hogi.';
        hint.style.display       = 'block';
    } else {
        hint.style.display = 'none';
    }
}

// Page load pe agar tag scope saved hai to hint dikhao
window.addEventListener('DOMContentLoaded', () => {
    const tagSel = document.getElementById('sel-tag');
    if (tagSel && tagSel.value) updateTagHint(tagSel);
});
</script>
@endsection