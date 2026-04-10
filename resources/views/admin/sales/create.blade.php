@extends('layouts.backend')

@section('title', 'Create Sale')

@section('styles')
<style>
    .fc {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 34px 38px;
        box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 4px 16px rgba(0,0,0,.04);
    }

    .page-back {
        display: inline-flex; align-items: center; gap: 7px;
        color: #64748b; font-size: .88rem; text-decoration: none;
        padding: 7px 14px; border-radius: 8px;
        border: 1px solid #e5e7eb; background: #fff; transition: all .15s;
    }
    .page-back:hover { background: #fef2f2; color: #ef4444; border-color: #fca5a5; }

    .form-label { font-weight: 600; font-size: .875rem; color: #374151; margin-bottom: 6px; }
    .req { color: #ef4444; }

    .form-control, .form-select {
        border: 1.5px solid #e5e7eb; border-radius: 9px;
        padding: 10px 14px; font-size: .9rem; background: #fafafa;
        transition: border-color .15s, box-shadow .15s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,.1);
        background: #fff; outline: none;
    }
    .form-control.is-invalid { border-color: #ef4444; }

    .tile-row { display: flex; gap: 10px; }
    .tile {
        flex: 1; padding: 14px 10px; border: 1.5px solid #e5e7eb;
        border-radius: 10px; text-align: center; cursor: pointer;
        transition: all .15s; user-select: none;
    }
    .tile:hover { border-color: #fca5a5; background: #fef2f2; }
    .tile.sel   { border-color: #ef4444; background: #fef2f2; color: #dc2626; }
    .tile-icon  { font-size: 1.3rem; display: block; margin-bottom: 4px; }
    .tile-lbl   { font-size: .82rem; font-weight: 700; display: block; }
    .tile-sub   { font-size: .72rem; color: #94a3b8; display: block; margin-top: 2px; }
    .tile.sel .tile-sub { color: #fca5a5; }

    /* Scope tiles — 3 now */
    .scope-tile-row { display: flex; gap: 10px; }
    .scope-tile {
        flex: 1; padding: 14px 10px; border: 1.5px solid #e5e7eb;
        border-radius: 10px; text-align: center; cursor: pointer;
        transition: all .15s; user-select: none;
    }
    .scope-tile:hover { border-color: #fca5a5; background: #fef2f2; }
    .scope-tile.sel   { border-color: #ef4444; background: #fef2f2; color: #dc2626; }
    .scope-tile .tile-sub { font-size: .72rem; color: #94a3b8; display: block; margin-top: 2px; }
    .scope-tile.sel .tile-sub { color: #fca5a5; }

    /* Tag chips inside select dropdown preview */
    .tag-dot {
        display: inline-block; width: 9px; height: 9px;
        border-radius: 50%; margin-right: 5px; vertical-align: middle;
    }

    .divider { height: 1px; background: #e5e7eb; margin: 26px 0; }
    .hint { font-size: .78rem; color: #94a3b8; margin-top: 5px; }

    .preview-banner {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border-radius: 12px; padding: 18px 22px; color: #fff;
        display: none; margin-top: 22px;
    }
    .preview-banner .pb-label { font-size: .68rem; text-transform: uppercase; letter-spacing: .1em; opacity: .55; }
    .preview-banner .pb-name  { font-size: 1.1rem; font-weight: 800; margin: 4px 0 6px; }
    .preview-banner .pb-disc  { font-size: 1.3rem; font-weight: 800; color: #fef2f2; }
    .preview-banner .pb-scope { font-size: .8rem; opacity: .65; margin-top: 4px; }
    .preview-banner .pb-dates { font-size: .75rem; opacity: .5; margin-top: 5px; }

    .btn-submit {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff; border: none; border-radius: 10px;
        padding: 12px 36px; font-size: .95rem; font-weight: 700;
        cursor: pointer; transition: all .2s;
        box-shadow: 0 4px 12px rgba(239,68,68,.3);
    }
    .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(239,68,68,.4); }

    .btn-cancel {
        background: #fff; color: #64748b; border: 1.5px solid #e5e7eb;
        border-radius: 10px; padding: 12px 28px; font-size: .9rem;
        font-weight: 600; text-decoration: none; display: inline-block; transition: all .15s;
    }
    .btn-cancel:hover { background: #f8fafc; color: #374151; }
</style>
@endsection

@section('content')
<div class="container-fluid py-4 px-4" style="background:#f8fafc; min-height:100vh;">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.sales.index') }}" class="page-back">← Back</a>
        <div>
            <h2 style="margin:0; font-weight:800; font-size:1.35rem; color:#1e293b;">Create New Sale</h2>
            <small style="color:#94a3b8;">Auto-applies to products in selected category, type, or tag</small>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-7 col-lg-9">
            <div class="fc">

                @if($errors->any())
                    <div class="alert mb-4" style="background:#fee2e2;color:#991b1b;border:none;border-radius:10px;font-size:.88rem;">
                        <strong>Fix these errors:</strong>
                        <ul class="mb-0 mt-1 ps-3">
                            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.sales.store') }}" id="sale-form">
                    @csrf
                    <input type="hidden" name="type"  id="f-type"  value="{{ old('type','percent') }}">
                    <input type="hidden" name="scope" id="f-scope" value="{{ old('scope','category') }}">

                    {{-- Sale Name --}}
                    <div class="mb-4">
                        <label class="form-label">Sale Name <span class="req">*</span></label>
                        <input type="text" name="name" id="f-name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="e.g. Men's Summer Sale, Electronics 20% Off"
                               oninput="updatePreview()">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="divider"></div>

                    {{-- Discount Type --}}
                    <div class="mb-4">
                        <label class="form-label">Discount Type <span class="req">*</span></label>
                        <div class="tile-row">
                            <div class="tile {{ old('type','percent') === 'percent' ? 'sel' : '' }}"
                                 onclick="setType('percent', this)">
                                <span class="tile-icon">%</span>
                                <span class="tile-lbl">Percentage</span>
                                <span class="tile-sub">e.g. 20% off</span>
                            </div>
                            <div class="tile {{ old('type') === 'fixed' ? 'sel' : '' }}"
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
                            <span id="unit-lbl" class="fw-normal text-muted">(% of price)</span>
                        </label>
                        <input type="number" name="discount" id="f-disc"
                               step="0.01" min="0.01"
                               class="form-control @error('discount') is-invalid @enderror"
                               value="{{ old('discount') }}"
                               placeholder="20"
                               oninput="updatePreview()">
                        @error('discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="divider"></div>

                    {{-- Scope — 3 tiles --}}
                    <div class="mb-4">
                        <label class="form-label">Applies To <span class="req">*</span></label>
                        <div class="scope-tile-row">

                            <div class="scope-tile {{ old('scope','category') === 'category' ? 'sel' : '' }}"
                                 onclick="setScope('category', this)">
                                <span class="tile-icon">🏷</span>
                                <span class="tile-lbl">Category</span>
                                <span class="tile-sub">e.g. Men's, Women's</span>
                            </div>

                            <div class="scope-tile {{ old('scope') === 'product_type' ? 'sel' : '' }}"
                                 onclick="setScope('product_type', this)">
                                <span class="tile-icon">📦</span>
                                <span class="tile-lbl">Product Type</span>
                                <span class="tile-sub">e.g. Shirts, Shoes</span>
                            </div>

                            {{-- ✅ NEW: Tag based --}}
                            <div class="scope-tile {{ old('scope') === 'tag' ? 'sel' : '' }}"
                                 onclick="setScope('tag', this)">
                                <span class="tile-icon">🔖</span>
                                <span class="tile-lbl">Tag</span>
                                <span class="tile-sub">e.g. Sale, New Arrival</span>
                            </div>

                        </div>
                    </div>

                    {{-- Category dropdown --}}
                    <div class="mb-4" id="wrap-cat"
                         style="display:{{ old('scope','category') === 'category' ? 'block' : 'none' }}">
                        <label class="form-label">Select Category <span class="req">*</span></label>
                        <select name="scope_id" id="sel-cat" class="form-select" onchange="updatePreview()">
                            <option value="">— Choose Category —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('scope_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('scope_id') <div class="text-danger mt-1" style="font-size:.78rem;">{{ $message }}</div> @enderror
                    </div>

                    {{-- Product Type dropdown --}}
                    <div class="mb-4" id="wrap-type"
                         style="display:{{ old('scope') === 'product_type' ? 'block' : 'none' }}">
                        <label class="form-label">Select Product Type <span class="req">*</span></label>
                        <select name="scope_id" id="sel-type" class="form-select" onchange="updatePreview()">
                            <option value="">— Choose Product Type —</option>
                            @foreach($productTypes as $pt)
                                <option value="{{ $pt->id }}"
                                    {{ old('scope_id') == $pt->id ? 'selected' : '' }}>
                                    {{ $pt->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- ✅ NEW: Tag dropdown --}}
                    <div class="mb-4" id="wrap-tag"
                         style="display:{{ old('scope') === 'tag' ? 'block' : 'none' }}">
                        <label class="form-label">Select Tag <span class="req">*</span></label>
                        <select name="scope_id" id="sel-tag" class="form-select" onchange="updatePreview()">
                            <option value="">— Choose Tag —</option>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}"
                                        data-color="{{ $tag->color }}"
                                        data-count="{{ $tag->products_count }}"
                                    {{ old('scope_id') == $tag->id ? 'selected' : '' }}>
                                    {{ $tag->name }}  ({{ $tag->products_count }} products)
                                </option>
                            @endforeach
                        </select>
                        {{-- Tag info hint --}}
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
                            <input type="datetime-local" name="starts_at" id="f-start"
                                   class="form-control @error('starts_at') is-invalid @enderror"
                                   value="{{ old('starts_at') }}"
                                   oninput="updatePreview()">
                            @error('starts_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ends At <span class="req">*</span></label>
                            <input type="datetime-local" name="ends_at" id="f-end"
                                   class="form-control @error('ends_at') is-invalid @enderror"
                                   value="{{ old('ends_at') }}"
                                   oninput="updatePreview()">
                            @error('ends_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="hint">Sale auto-expires after this date/time</div>
                        </div>
                    </div>

                    {{-- Live Preview --}}
                    <div class="preview-banner" id="preview-banner">
                        <div class="pb-label">Sale Preview</div>
                        <div class="pb-name"  id="pb-name">Sale Name</div>
                        <div class="pb-disc"  id="pb-disc">— OFF</div>
                        <div class="pb-scope" id="pb-scope">—</div>
                        <div class="pb-dates" id="pb-dates"></div>
                    </div>

                    <div class="divider"></div>

                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn-submit">🔥 Create Sale</button>
                        <a href="{{ route('admin.sales.index') }}" class="btn-cancel">Cancel</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
const CAT_NAMES  = @json($categories->pluck('name', 'id'));
const TYPE_NAMES = @json($productTypes->pluck('name', 'id'));
const TAG_DATA   = @json($tags->keyBy('id')->map(fn($t) => ['name' => $t->name, 'color' => $t->color, 'count' => $t->products_count]));

/* ── Discount type ── */
function setType(v, el) {
    document.getElementById('f-type').value = v;
    document.querySelectorAll('.tile').forEach(t => t.classList.remove('sel'));
    el.classList.add('sel');
    document.getElementById('unit-lbl').textContent =
        v === 'percent' ? '(% of price)' : '(₹ fixed amount)';
    updatePreview();
}

/* ── Scope ── */
function setScope(v, el) {
    document.getElementById('f-scope').value = v;
    document.querySelectorAll('.scope-tile').forEach(t => t.classList.remove('sel'));
    el.classList.add('sel');

    document.getElementById('wrap-cat').style.display  = v === 'category'     ? 'block' : 'none';
    document.getElementById('wrap-type').style.display = v === 'product_type' ? 'block' : 'none';
    document.getElementById('wrap-tag').style.display  = v === 'tag'          ? 'block' : 'none';

    updatePreview();
}

/* ── Tag select hint ── */
document.getElementById('sel-tag').addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    const hint    = document.getElementById('tag-hint');
    const hintDot = document.getElementById('tag-hint-dot');
    const hintTxt = document.getElementById('tag-hint-text');

    if (this.value) {
        const d = TAG_DATA[this.value];
        hintDot.style.background = d.color;
        hintTxt.textContent      = '"' + d.name + '" tag mein ' + d.count + ' products hain — unpe ye sale apply hogi.';
        hint.style.display       = 'block';
    } else {
        hint.style.display = 'none';
    }
    updatePreview();
});

/* ── Date formatter ── */
function fmtDate(str) {
    if (!str) return '';
    const d = new Date(str);
    return d.toLocaleDateString('en-IN', {
        day: '2-digit', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit'
    });
}

/* ── Live preview ── */
function updatePreview() {
    const name  = document.getElementById('f-name').value;
    const disc  = document.getElementById('f-disc').value;
    const type  = document.getElementById('f-type').value;
    const scope = document.getElementById('f-scope').value;
    const start = document.getElementById('f-start').value;
    const end   = document.getElementById('f-end').value;

    if (!name && !disc) {
        document.getElementById('preview-banner').style.display = 'none';
        return;
    }

    document.getElementById('preview-banner').style.display = 'block';
    document.getElementById('pb-name').textContent = name || 'Sale Name';
    document.getElementById('pb-disc').textContent = disc
        ? (type === 'percent' ? disc + '% OFF' : '₹' + parseFloat(disc).toFixed(2) + ' OFF')
        : '— OFF';

    let scopeText = '';
    if (scope === 'category') {
        const id = document.getElementById('sel-cat').value;
        scopeText = '🏷 Category: ' + (CAT_NAMES[id] || '—');
    } else if (scope === 'product_type') {
        const id = document.getElementById('sel-type').value;
        scopeText = '📦 Type: ' + (TYPE_NAMES[id] || '—');
    } else if (scope === 'tag') {
        const id = document.getElementById('sel-tag').value;
        const t  = TAG_DATA[id];
        scopeText = t ? ('🔖 Tag: ' + t.name + ' (' + t.count + ' products)') : '🔖 Tag: —';
    }
    document.getElementById('pb-scope').textContent = scopeText;

    if (start && end) {
        document.getElementById('pb-dates').textContent =
            fmtDate(start) + ' → ' + fmtDate(end);
    }
}

updatePreview();
</script>
@endsection