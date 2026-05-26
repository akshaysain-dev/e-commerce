@extends('vendor.layouts.app')

@section('title', 'Create New Product')

@push('styles')
<style>
    :root {
        --ink:        #0e0e12;
        --ink-muted:  #6b6b7a;
        --surface:    #ffffff;
        --surface-2:  #f5f4f1;
        --surface-3:  #eeede9;
        --accent:     #5b5ef4;
        --accent-2:   #e8e8fd;
        --accent-dk:  #4749d6;
        --success:    #1a9e75;
        --success-bg: #e2f5ee;
        --danger:     #c63434;
        --danger-bg:  #fde8e8;
        --border:     rgba(14,14,18,.08);
        --border-md:  rgba(14,14,18,.13);
        --radius-sm:  10px;
        --radius-md:  16px;
        --radius-lg:  24px;
        --shadow-sm:  0 1px 3px rgba(14,14,18,.06), 0 1px 2px rgba(14,14,18,.04);
        --shadow-md:  0 4px 16px rgba(14,14,18,.08), 0 1px 4px rgba(14,14,18,.04);
        --ff-display: 'Syne', sans-serif;
        --ff-body:    'DM Sans', sans-serif;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: var(--ff-body); background: var(--surface-2); color: var(--ink); -webkit-font-smoothing: antialiased; }

    /* ── Wrapper ── */
    .vcp-wrap {
        padding: 2rem 1.75rem 3rem;
        width: 100%; min-width: 0; overflow-x: hidden;
    }

    /* ── Header ── */
    .vcp-header { margin-bottom: 1.75rem; }
    .vcp-eyebrow {
        font-size: .72rem; font-weight: 600; letter-spacing: .1em;
        text-transform: uppercase; color: var(--accent); margin-bottom: .3rem;
    }
    .vcp-title {
        font-family: var(--ff-display);
        font-size: clamp(1.4rem, 2.5vw, 2rem);
        font-weight: 700; color: var(--ink); line-height: 1.2; margin-bottom: .3rem;
    }
    .vcp-subtitle { font-size: .85rem; color: var(--ink-muted); }

    /* ── Error alert ── */
    .vcp-alert {
        background: var(--danger-bg);
        border: 1px solid rgba(198,52,52,.2);
        border-radius: var(--radius-sm);
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        color: var(--danger);
        font-size: .875rem;
    }
    .vcp-alert-title { font-weight: 700; margin-bottom: .5rem; display: flex; align-items: center; gap: .5rem; }
    .vcp-alert ul { padding-left: 1.25rem; margin: 0; }
    .vcp-alert li { margin-bottom: .2rem; }

    /* ── Two-col layout ── */
    .vcp-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 1.25rem;
        align-items: start;
    }
    .vcp-main { display: flex; flex-direction: column; gap: 1.25rem; }
    .vcp-side { display: flex; flex-direction: column; gap: 1.25rem; position: sticky; top: 1.5rem; }

    /* ── Section card ── */
    .vcp-section {
        background: var(--surface);
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .vcp-section-head {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; gap: .75rem;
    }
    .vcp-section-icon {
        width: 32px; height: 32px; border-radius: 8px;
        background: var(--accent-2); color: var(--accent);
        display: flex; align-items: center; justify-content: center;
        font-size: .85rem; flex-shrink: 0;
    }
    .vcp-section-title {
        font-family: var(--ff-display);
        font-size: .95rem; font-weight: 600; color: var(--ink);
    }
    .vcp-section-body { padding: 1.5rem; }

    /* ── Fields ── */
    .vcp-fields { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .vcp-field-full { grid-column: 1 / -1; }
    .vcp-field { display: flex; flex-direction: column; gap: .4rem; }

    .vcp-label {
        font-size: .72rem; font-weight: 700; letter-spacing: .05em;
        text-transform: uppercase; color: var(--ink-muted);
    }
    .vcp-label .req { color: var(--danger); margin-left: 2px; }

    .vcp-input,
    .vcp-select,
    .vcp-textarea {
        width: 100%;
        padding: .7rem 1rem;
        border: 1.5px solid var(--border-md);
        border-radius: var(--radius-sm);
        font-family: var(--ff-body);
        font-size: .875rem;
        color: var(--ink);
        background: var(--surface);
        transition: border-color .2s, box-shadow .2s;
        outline: none;
        -webkit-appearance: none;
        appearance: none;
    }
    .vcp-select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b6b7a' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
        cursor: pointer;
    }
    .vcp-input:focus, .vcp-select:focus, .vcp-textarea:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(91,94,244,.12);
    }
    .vcp-input.is-invalid, .vcp-select.is-invalid, .vcp-textarea.is-invalid {
        border-color: var(--danger);
    }
    .vcp-input::placeholder, .vcp-textarea::placeholder { color: rgba(14,14,18,.3); }
    .vcp-textarea { resize: vertical; min-height: 100px; }
    .vcp-field-hint { font-size: .72rem; color: var(--ink-muted); margin-top: .2rem; }
    .vcp-error-msg { font-size: .72rem; color: var(--danger); margin-top: .2rem; }

    /* ── Toggle (status) ── */
    .vcp-toggle-row {
        display: flex; align-items: center;
        justify-content: space-between;
        padding: 1rem 1.5rem;
    }
    .vcp-toggle-label { font-size: .875rem; font-weight: 500; color: var(--ink); }
    .vcp-toggle-sub { font-size: .75rem; color: var(--ink-muted); }
    .vcp-toggle {
        position: relative;
        width: 44px; height: 24px;
        flex-shrink: 0;
    }
    .vcp-toggle input { opacity: 0; width: 0; height: 0; position: absolute; }
    .vcp-toggle-track {
        position: absolute; inset: 0;
        background: var(--surface-3);
        border-radius: 999px;
        cursor: pointer;
        transition: background .2s;
        border: 1.5px solid var(--border-md);
    }
    .vcp-toggle-track::after {
        content: '';
        position: absolute;
        left: 3px; top: 50%;
        transform: translateY(-50%);
        width: 16px; height: 16px;
        border-radius: 50%;
        background: var(--ink-muted);
        transition: left .2s, background .2s;
    }
    .vcp-toggle input:checked + .vcp-toggle-track { background: var(--accent); border-color: var(--accent); }
    .vcp-toggle input:checked + .vcp-toggle-track::after { left: calc(100% - 19px); background: #fff; }

    /* ── Upload zones ── */
    .vcp-upload {
        border: 1.5px dashed var(--border-md);
        border-radius: var(--radius-sm);
        padding: 1.5rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: border-color .2s, background .2s;
        background: var(--surface-2);
        position: relative;
    }
    .vcp-upload:hover { border-color: var(--accent); background: var(--accent-2); }
    .vcp-upload input[type="file"] {
        position: absolute; inset: 0;
        opacity: 0; cursor: pointer; width: 100%; height: 100%;
    }
    .vcp-upload-icon {
        width: 40px; height: 40px; border-radius: 10px;
        background: var(--accent-2); color: var(--accent);
        display: flex; align-items: center; justify-content: center;
        font-size: .95rem; margin: 0 auto .6rem;
    }
    .vcp-upload-text { font-size: .8rem; color: var(--ink-muted); line-height: 1.5; }
    .vcp-upload-text strong { color: var(--accent); display: block; font-size: .85rem; }

    /* preview strip */
    .vcp-preview-strip {
        display: flex; flex-wrap: wrap; gap: .5rem;
        margin-top: .75rem;
    }
    .vcp-preview-strip img {
        width: 52px; height: 52px;
        border-radius: 8px; object-fit: cover;
        border: 1.5px solid var(--border-md);
    }

    /* ── Variant section ── */
    .vcp-variant-head {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    }
    .vcp-variant-body { padding: 1rem 1.5rem; display: flex; flex-direction: column; gap: .75rem; }

    .vcp-variant-row {
        display: grid;
        grid-template-columns: 1fr 1fr 110px 90px auto;
        gap: .75rem;
        align-items: end;
        background: var(--surface-2);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 1rem;
        position: relative;
    }
    .vcp-variant-idx {
        position: absolute;
        top: -10px; left: 12px;
        font-size: .6rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em;
        color: var(--accent); background: var(--accent-2);
        padding: .1rem .5rem; border-radius: 999px;
    }

    .vcp-btn-remove {
        display: inline-flex; align-items: center; justify-content: center;
        width: 36px; height: 36px;
        border-radius: 8px;
        border: 1px solid rgba(198,52,52,.2);
        background: var(--danger-bg);
        color: var(--danger);
        cursor: pointer;
        font-size: .8rem;
        transition: background .15s, transform .12s;
        flex-shrink: 0;
        align-self: flex-end;
    }
    .vcp-btn-remove:hover { background: var(--danger); color: #fff; transform: scale(1.05); }

    .vcp-btn-add-variant {
        display: inline-flex; align-items: center; gap: .45rem;
        padding: .6rem 1.25rem;
        border-radius: 999px;
        border: 1.5px dashed var(--border-md);
        background: transparent;
        color: var(--accent);
        font-family: var(--ff-body);
        font-size: .8rem; font-weight: 600;
        cursor: pointer;
        transition: background .15s, border-color .15s;
        align-self: flex-start;
    }
    .vcp-btn-add-variant:hover { background: var(--accent-2); border-color: var(--accent); }

    /* ── Submit bar ── */
    .vcp-submit-bar {
        background: var(--surface);
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        padding: 1.1rem 1.5rem;
        display: flex; align-items: center;
        justify-content: space-between;
        gap: 1rem; flex-wrap: wrap;
    }
    .vcp-submit-note { font-size: .8rem; color: var(--ink-muted); display: flex; align-items: center; gap: .4rem; }
    .vcp-submit-note i { color: var(--accent); }
    .vcp-btn-actions { display: flex; align-items: center; gap: .75rem; flex-wrap: wrap; }

    .vcp-btn {
        display: inline-flex; align-items: center; gap: .45rem;
        padding: .7rem 1.75rem; border-radius: 999px; border: none;
        font-family: var(--ff-body); font-size: .875rem; font-weight: 600;
        cursor: pointer; text-decoration: none;
        transition: transform .15s, box-shadow .15s, background .15s;
    }
    .vcp-btn-primary { background: var(--accent); color: #fff; }
    .vcp-btn-primary:hover {
        background: var(--accent-dk); transform: translateY(-1px);
        box-shadow: 0 6px 24px rgba(91,94,244,.35); color: #fff; text-decoration: none;
    }
    .vcp-btn-ghost {
        background: var(--surface-2); color: var(--ink-muted);
        border: 1px solid var(--border-md);
    }
    .vcp-btn-ghost:hover { background: var(--surface-3); color: var(--ink); text-decoration: none; }

    /* ── Responsive ── */
    @media (max-width: 1100px) {
        .vcp-grid { grid-template-columns: 1fr; }
        .vcp-side { position: static; }
    }
    @media (max-width: 991px) {
        .vcp-wrap { padding: 1.5rem 1.25rem 2.5rem; }
    }
    @media (max-width: 768px) {
        .vcp-fields { grid-template-columns: 1fr; }
        .vcp-field-full { grid-column: 1; }
        .vcp-variant-row { grid-template-columns: 1fr 1fr; }
        .vcp-variant-row > *:last-child { grid-column: 1 / -1; }
    }
    @media (max-width: 575px) {
        .vcp-wrap { padding: 1rem 1rem 1.5rem; }
        .vcp-section-body { padding: 1.1rem; }
        .vcp-variant-row { grid-template-columns: 1fr; }
        .vcp-submit-bar { flex-direction: column; align-items: flex-start; }
        .vcp-btn-actions { width: 100%; flex-direction: column; }
        .vcp-btn { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="vcp-wrap">

    {{-- Header --}}
    <div class="vcp-header">
        <p class="vcp-eyebrow">Inventory</p>
        <h1 class="vcp-title">Add New Product</h1>
        <p class="vcp-subtitle">Fill in the details below to list a new product in your store</p>
    </div>

    {{-- Errors --}}
    @if($errors->any())
        <div class="vcp-alert">
            <div class="vcp-alert-title"><i class="fa fa-exclamation-circle"></i> Please fix the following errors:</div>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('vendor_add_products') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="user_id" value="{{ session('admin_id') }}">

        <div class="vcp-grid">

            {{-- ── Main column ── --}}
            <div class="vcp-main">

                {{-- Basic Info --}}
                <div class="vcp-section">
                    <div class="vcp-section-head">
                        <div class="vcp-section-icon"><i class="fa fa-box"></i></div>
                        <span class="vcp-section-title">Basic Information</span>
                    </div>
                    <div class="vcp-section-body">
                        <div class="vcp-fields">
                            <div class="vcp-field">
                                <label class="vcp-label">Category <span class="req">*</span></label>
                                <select name="category_id" class="vcp-select @error('category_id') is-invalid @enderror">
                                    <option value="">— Select Category —</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')<div class="vcp-error-msg">{{ $message }}</div>@enderror
                            </div>

                            <div class="vcp-field">
                                <label class="vcp-label">Product Type <span class="req">*</span></label>
                                <select name="product_type_id" id="type_id" class="vcp-select @error('product_type_id') is-invalid @enderror">
                                    <option value="">— Select Type —</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ old('product_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_type_id')<div class="vcp-error-msg">{{ $message }}</div>@enderror
                            </div>

                            <div class="vcp-field vcp-field-full">
                                <label class="vcp-label">Product Name <span class="req">*</span></label>
                                <input type="text" name="name" class="vcp-input @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" placeholder="e.g. Premium Cotton T-Shirt">
                                @error('name')<div class="vcp-error-msg">{{ $message }}</div>@enderror
                            </div>

                            <div class="vcp-field vcp-field-full">
                                <label class="vcp-label">Description</label>
                                <textarea name="description" rows="4"
                                          class="vcp-textarea @error('description') is-invalid @enderror"
                                          placeholder="Describe your product…">{{ old('description') }}</textarea>
                                @error('description')<div class="vcp-error-msg">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Variants --}}
                <div class="vcp-section">
                    <div class="vcp-variant-head">
                        <div style="display:flex;align-items:center;gap:.75rem;">
                            <div class="vcp-section-icon"><i class="fa fa-layer-group"></i></div>
                            <span class="vcp-section-title">Product Variants</span>
                        </div>
                    </div>
                    <div class="vcp-variant-body" id="variant-container">

                        {{-- First row --}}
                        <div class="vcp-variant-row variant-row">
                            <span class="vcp-variant-idx">Variant 1</span>

                            <div class="vcp-field">
                                <label class="vcp-label">Attribute</label>
                                <select name="variants[0][attribute_id]" class="vcp-select attribute-selector">
                                    <option value="">— Type —</option>
                                    @foreach($attributes as $attr)
                                        <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="vcp-field">
                                <label class="vcp-label">Value</label>
                                <select name="variants[0][attribute_value_id]" class="vcp-select value-selector">
                                    <option value="">— Select —</option>
                                </select>
                            </div>

                            <div class="vcp-field">
                                <label class="vcp-label">Price (₹)</label>
                                <input type="number" step="0.01" name="variants[0][price]"
                                       class="vcp-input" placeholder="0.00">
                            </div>

                            <div class="vcp-field">
                                <label class="vcp-label">Stock</label>
                                <input type="number" name="variants[0][stock]"
                                       class="vcp-input" value="0">
                            </div>

                            <button type="button" class="vcp-btn-remove remove-variant" title="Remove">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>

                    </div>
                    <div style="padding:.75rem 1.5rem 1.25rem;">
                        <button type="button" id="add-variant-btn" class="vcp-btn-add-variant">
                            <i class="fa fa-plus"></i> Add Another Variant
                        </button>
                    </div>
                </div>

            </div>{{-- end main --}}

            {{-- ── Side column ── --}}
            <div class="vcp-side">

                {{-- Status --}}
                <div class="vcp-section">
                    <div class="vcp-section-head">
                        <div class="vcp-section-icon"><i class="fa fa-toggle-on"></i></div>
                        <span class="vcp-section-title">Visibility</span>
                    </div>
                    <div class="vcp-toggle-row">
                        <div>
                            <div class="vcp-toggle-label">Active</div>
                            <div class="vcp-toggle-sub">Product visible to customers</div>
                        </div>
                        <label class="vcp-toggle">
                            <input type="checkbox" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                            <span class="vcp-toggle-track"></span>
                        </label>
                    </div>
                </div>

                {{-- Featured Image --}}
                <div class="vcp-section">
                    <div class="vcp-section-head">
                        <div class="vcp-section-icon"><i class="fa fa-image"></i></div>
                        <span class="vcp-section-title">Featured Image</span>
                    </div>
                    <div class="vcp-section-body">
                        <div class="vcp-upload" id="featured-zone">
                            <input type="file" name="image" id="image-input" accept="image/*"
                                   onchange="previewFeatured(this)">
                            <div class="vcp-upload-icon"><i class="fa fa-cloud-upload-alt"></i></div>
                            <div class="vcp-upload-text">
                                <strong>Click to upload</strong>
                                PNG, JPG — main product photo
                            </div>
                        </div>
                        <div class="vcp-preview-strip" id="featured-preview"></div>
                        @error('image')<div class="vcp-error-msg" style="margin-top:.5rem;">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Additional Images --}}
                <div class="vcp-section">
                    <div class="vcp-section-head">
                        <div class="vcp-section-icon"><i class="fa fa-images"></i></div>
                        <span class="vcp-section-title">Gallery Images</span>
                    </div>
                    <div class="vcp-section-body">
                        <div class="vcp-upload" id="gallery-zone">
                            <input type="file" name="images[]" id="gallery-input" accept="image/*" multiple
                                   onchange="previewGallery(this)">
                            <div class="vcp-upload-icon"><i class="fa fa-layer-group"></i></div>
                            <div class="vcp-upload-text">
                                <strong>Click to upload multiple</strong>
                                Select several images at once
                            </div>
                        </div>
                        <div class="vcp-preview-strip" id="gallery-preview"></div>
                        @error('images')<div class="vcp-error-msg" style="margin-top:.5rem;">{{ $message }}</div>@enderror
                    </div>
                </div>

            </div>{{-- end side --}}

        </div>{{-- end grid --}}

        {{-- Submit bar --}}
        <div class="vcp-submit-bar" style="margin-top:1.25rem;">
            <span class="vcp-submit-note">
                <i class="fa fa-shield-alt"></i>
                All fields marked <strong style="color:var(--danger)">*</strong> are required
            </span>
            <div class="vcp-btn-actions">
                <a href="{{ route('vendor_product') }}" class="vcp-btn vcp-btn-ghost">
                    <i class="fa fa-arrow-left"></i> Cancel
                </a>
                <button type="submit" class="vcp-btn vcp-btn-primary">
                    <i class="fa fa-plus"></i> Add Product
                </button>
            </div>
        </div>

    </form>

</div>
@endsection

@push('scripts')
<script>
    let variantIndex = 1;
    const allAttributeValues = @json($attributeValues);

    /* ── Image previews ── */
    function previewFeatured(input) {
        const strip = document.getElementById('featured-preview');
        strip.innerHTML = '';
        if (input.files && input.files[0]) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(input.files[0]);
            strip.appendChild(img);
        }
    }

    function previewGallery(input) {
        const strip = document.getElementById('gallery-preview');
        strip.innerHTML = '';
        Array.from(input.files).forEach(file => {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            strip.appendChild(img);
        });
    }

    /* ── Add variant row ── */
    document.getElementById('add-variant-btn').addEventListener('click', function () {
        const container = document.getElementById('variant-container');
        const firstRow  = document.querySelector('.variant-row').cloneNode(true);

        firstRow.querySelectorAll('input, select').forEach(el => {
            el.name  = el.name.replace(/\[\d+\]/, `[${variantIndex}]`);
            el.value = el.tagName === 'SELECT' ? '' : (el.name.includes('stock') ? 0 : '');
        });

        // Update index badge
        const badge = firstRow.querySelector('.vcp-variant-idx');
        if (badge) badge.textContent = `Variant ${variantIndex + 1}`;

        // Reset value selector
        const valueSel = firstRow.querySelector('.value-selector');
        if (valueSel) valueSel.innerHTML = '<option value="">— Select —</option>';

        container.appendChild(firstRow);
        variantIndex++;
    });

    /* ── Attribute → Value dependent dropdown ── */
    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('attribute-selector')) {
            const attrId = e.target.value;
            const row    = e.target.closest('.variant-row');
            const valSel = row.querySelector('.value-selector');
            const filtered = allAttributeValues.filter(v => v.attribute_id == attrId);
            valSel.innerHTML = '<option value="">— Select —</option>';
            filtered.forEach(v => {
                valSel.innerHTML += `<option value="${v.id}">${v.value}</option>`;
            });
        }
    });

    /* ── Remove variant row ── */
    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-variant')) {
            const rows = document.querySelectorAll('.variant-row');
            if (rows.length > 1) e.target.closest('.variant-row').remove();
        }
    });
</script>
@endpush