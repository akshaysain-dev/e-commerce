@extends('vendor.layouts.app')

@section('title', 'Edit Product')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    *, *::before, *::after { box-sizing: border-box; }

    body, .edit-product-wrap {
        font-family: 'DM Sans', sans-serif;
    }

    .edit-product-wrap {
        max-width: 900px;
        margin: 2rem auto;
        padding: 0 1rem 3rem;
    }

    /* ── Page Header ── */
    .ep-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 2rem;
    }
    .ep-back {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1px solid #e4e4e7;
        background: #fff;
        color: #52525b;
        text-decoration: none;
        transition: background .15s, border-color .15s;
        flex-shrink: 0;
    }
    .ep-back:hover { background: #f4f4f5; border-color: #d4d4d8; }
    .ep-back svg { width: 16px; height: 16px; }
    .ep-header h2 { font-size: 1.3rem; font-weight: 600; color: #18181b; margin: 0; }

    /* ── Alert ── */
    .ep-alert {
        background: #fff5f5;
        border: 1px solid #fecaca;
        border-radius: 12px;
        padding: .875rem 1.125rem;
        margin-bottom: 1.5rem;
        font-size: 13.5px;
        color: #dc2626;
    }
    .ep-alert strong { display: block; margin-bottom: .375rem; font-weight: 600; }
    .ep-alert ul { margin: 0; padding-left: 1.2rem; }
    .ep-alert ul li { margin-bottom: 2px; }

    /* ── Cards ── */
    .ep-card {
        background: #fff;
        border: 1px solid #e4e4e7;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }
    .ep-card-title {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .07em;
        text-transform: uppercase;
        color: #a1a1aa;
        margin-bottom: 1.25rem;
        padding-bottom: .875rem;
        border-bottom: 1px solid #f4f4f5;
    }

    /* ── Fields ── */
    .ep-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .ep-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
    .ep-field { display: flex; flex-direction: column; gap: 5px; }
    .ep-field + .ep-field { margin-top: 0; }
    .ep-fields-stack { display: flex; flex-direction: column; gap: 1rem; }

    .ep-label {
        font-size: 13px;
        font-weight: 500;
        color: #3f3f46;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .ep-label .req { color: #ef4444; margin-left: 2px; }
    .ep-label a {
        font-size: 12px;
        font-weight: 500;
        color: #6366f1;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 3px;
    }
    .ep-label a:hover { color: #4f46e5; }
    .ep-label a svg { width: 12px; height: 12px; }

    .ep-input, .ep-select, .ep-textarea {
        height: 38px;
        padding: 0 .75rem;
        border: 1px solid #e4e4e7;
        border-radius: 10px;
        font-size: 14px;
        font-family: 'DM Sans', sans-serif;
        color: #18181b;
        background: #fafafa;
        outline: none;
        transition: border-color .15s, background .15s, box-shadow .15s;
        width: 100%;
    }
    .ep-input:focus, .ep-select:focus, .ep-textarea:focus {
        border-color: #a5b4fc;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(99,102,241,.1);
    }
    .ep-textarea {
        height: 88px;
        padding: .625rem .75rem;
        resize: vertical;
    }
    .ep-input.is-invalid, .ep-select.is-invalid {
        border-color: #fca5a5;
        background: #fff5f5;
    }
    .ep-invalid-msg { font-size: 12px; color: #ef4444; margin-top: 3px; }
    .ep-hint { font-size: 12px; color: #a1a1aa; margin-top: 3px; }

    /* ── Status Toggle ── */
    .ep-toggle-row { display: flex; align-items: center; gap: 10px; height: 38px; }
    .ep-toggle {
        position: relative;
        width: 40px;
        height: 22px;
        flex-shrink: 0;
    }
    .ep-toggle input { opacity: 0; width: 0; height: 0; position: absolute; }
    .ep-toggle-slider {
        position: absolute;
        inset: 0;
        background: #e4e4e7;
        border-radius: 22px;
        cursor: pointer;
        transition: background .2s;
    }
    .ep-toggle-slider::before {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #fff;
        top: 3px;
        left: 3px;
        transition: transform .2s;
        box-shadow: 0 1px 3px rgba(0,0,0,.15);
    }
    .ep-toggle input:checked + .ep-toggle-slider { background: #22c55e; }
    .ep-toggle input:checked + .ep-toggle-slider::before { transform: translateX(18px); }
    .ep-toggle-label { font-size: 13.5px; color: #52525b; }

    /* ── Tags ── */
    .ep-tags-wrap { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 4px; }
    .ep-tag-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px 4px 10px;
        border-radius: 20px;
        border: 1.5px solid #e4e4e7;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        background: #fafafa;
        color: #52525b;
        transition: border-color .15s, background .15s;
        user-select: none;
    }
    .ep-tag-chip input[type="checkbox"] { display: none; }
    .ep-tag-chip:hover { border-color: #d4d4d8; background: #f4f4f5; }
    .ep-tag-chip.active { border-color: transparent !important; color: #fff !important; }
    .ep-tag-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
        opacity: .7;
    }

    /* ── Images ── */
    .ep-images-grid { display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-start; }
    .ep-thumb-item {
        position: relative;
        width: 86px;
        height: 86px;
        border-radius: 12px;
        overflow: visible;
    }
    .ep-thumb-item img {
        width: 86px;
        height: 86px;
        object-fit: cover;
        border-radius: 12px;
        border: 1px solid #e4e4e7;
        display: block;
    }
    .ep-thumb-placeholder {
        width: 86px;
        height: 86px;
        border-radius: 12px;
        background: #f4f4f5;
        border: 1px solid #e4e4e7;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d4d4d8;
    }
    .ep-thumb-placeholder svg { width: 28px; height: 28px; }
    .ep-remove-img {
        position: absolute;
        top: -6px;
        right: -6px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #ef4444;
        border: 2px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #fff;
        z-index: 1;
    }
    .ep-remove-img svg { width: 10px; height: 10px; }
    .ep-remove-img label { display: none; }
    .ep-upload-btn {
        width: 86px;
        height: 86px;
        border-radius: 12px;
        border: 1.5px dashed #d4d4d8;
        background: #fafafa;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        cursor: pointer;
        color: #a1a1aa;
        font-size: 12px;
        font-weight: 500;
        transition: border-color .15s, background .15s, color .15s;
    }
    .ep-upload-btn:hover { border-color: #a5b4fc; background: #eef2ff; color: #6366f1; }
    .ep-upload-btn svg { width: 20px; height: 20px; }
    .ep-upload-btn input { display: none; }

    /* ── Variants ── */
    .ep-variants-header {
        display: grid;
        grid-template-columns: 1fr 1fr 110px 90px 100px 36px;
        gap: 10px;
        padding: 0 .75rem .5rem;
        margin-bottom: 4px;
    }
    .ep-variants-header span {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .05em;
        text-transform: uppercase;
        color: #a1a1aa;
    }
    .ep-variant-row {
        display: grid;
        grid-template-columns: 1fr 1fr 110px 90px 100px 36px;
        gap: 10px;
        align-items: center;
        padding: .75rem;
        border: 1px solid #f0f0f0;
        border-radius: 12px;
        margin-bottom: 6px;
        background: #fafafa;
        transition: border-color .15s;
    }
    .ep-variant-row:hover { border-color: #e4e4e7; }
    .ep-variant-row.ep-new-row { background: #fff; border-color: #e0e7ff; }
    .ep-margin-badge {
        height: 38px;
        display: flex;
        align-items: center;
        padding: 0 .75rem;
        background: #f4f4f5;
        border-radius: 10px;
        font-size: 13px;
        color: #71717a;
        font-variant-numeric: tabular-nums;
        border: 1px solid #e4e4e7;
    }
    .ep-del-cell {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 38px;
    }
    .ep-del-cell input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: #ef4444;
        cursor: pointer;
    }
    .ep-remove-row-btn {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        border: 1px solid #e4e4e7;
        background: #fff;
        color: #a1a1aa;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background .15s, color .15s, border-color .15s;
        padding: 0;
    }
    .ep-remove-row-btn:hover {
        background: #fff5f5;
        color: #ef4444;
        border-color: #fca5a5;
    }
    .ep-remove-row-btn svg { width: 15px; height: 15px; }

    .ep-add-variant-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0 .875rem;
        height: 34px;
        border: 1px dashed #d4d4d8;
        border-radius: 9px;
        background: transparent;
        font-size: 13px;
        font-weight: 500;
        font-family: 'DM Sans', sans-serif;
        color: #52525b;
        cursor: pointer;
        margin-top: 6px;
        transition: border-color .15s, background .15s, color .15s;
    }
    .ep-add-variant-btn:hover { border-color: #a5b4fc; background: #eef2ff; color: #6366f1; }
    .ep-add-variant-btn svg { width: 14px; height: 14px; }

    /* ── Footer ── */
    .ep-footer {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #f4f4f5;
    }
    .ep-btn-primary {
        padding: 0 1.375rem;
        height: 40px;
        background: #18181b;
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        transition: background .15s, opacity .15s;
    }
    .ep-btn-primary:hover { background: #27272a; }
    .ep-btn-secondary {
        padding: 0 1.25rem;
        height: 40px;
        background: #fff;
        color: #52525b;
        border: 1px solid #e4e4e7;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        transition: background .15s, border-color .15s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    .ep-btn-secondary:hover { background: #f4f4f5; border-color: #d4d4d8; }

    @media (max-width: 700px) {
        .ep-grid-2, .ep-grid-3 { grid-template-columns: 1fr; }
        .ep-variants-header { display: none; }
        .ep-variant-row { grid-template-columns: 1fr 1fr; }
    }
</style>
@endpush

@section('content')
<div class="edit-product-wrap">

    {{-- Header --}}
    <div class="ep-header">
        <a href="{{ route('vendor_product') }}" class="ep-back" title="Back">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2>Edit product</h2>
    </div>

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="ep-alert">
            <strong>Please fix the following errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('vendor.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="user_id" value="{{ session('admin_id') }}">

        {{-- ── General Information ── --}}
        <div class="ep-card">
            <div class="ep-card-title">General information</div>
            <div class="ep-fields-stack">

                <div class="ep-grid-3">
                    {{-- Category --}}
                    <div class="ep-field">
                        <label class="ep-label">Category <span class="req">*</span></label>
                        <select name="category_id" class="ep-select">
                            <option value="">— Select category —</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Product Type --}}
                    <div class="ep-field">
                        <label class="ep-label">
                            Product type <span class="req">*</span>
                            <a href="{{ route('product-types.index') }}">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                Add new
                            </a>
                        </label>
                        <select name="product_type_id" id="type_id" class="ep-select @error('product_type_id') is-invalid @enderror">
                            <option value="">— Select type —</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}"
                                    {{ old('product_type_id', $product->product_type_id ?? '') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_type_id')
                            <div class="ep-invalid-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="ep-field">
                        <label class="ep-label">Status</label>
                        <div class="ep-toggle-row">
                            <label class="ep-toggle">
                                <input type="checkbox" name="status" value="1"
                                    {{ old('status', $product->status) ? 'checked' : '' }}>
                                <span class="ep-toggle-slider"></span>
                            </label>
                            <span class="ep-toggle-label">Active</span>
                        </div>
                    </div>
                </div>

                {{-- Product Name --}}
                <div class="ep-field">
                    <label class="ep-label">Product name <span class="req">*</span></label>
                    <input type="text" name="name" class="ep-input" value="{{ old('name', $product->name) }}" placeholder="Enter product name">
                </div>

                {{-- Description --}}
                <div class="ep-field">
                    <label class="ep-label">Description</label>
                    <textarea name="description" class="ep-textarea" placeholder="Describe the product…">{{ old('description', $product->description) }}</textarea>
                </div>

                {{-- Tags --}}
                <div class="ep-field">
                    <label class="ep-label">Tags</label>
                    <div class="ep-tags-wrap">
                        @foreach($tags as $tag)
                            <label class="ep-tag-chip {{ $product->tags->contains($tag->id) ? 'active' : '' }}"
                                   style="{{ $product->tags->contains($tag->id) ? 'background:'.$tag->color.'; border-color:'.$tag->color.';' : '' }}">
                                <input type="checkbox"
                                    name="tags[]"
                                    value="{{ $tag->id }}"
                                    {{ $product->tags->contains($tag->id) ? 'checked' : '' }}
                                    data-color="{{ $tag->color }}">
                                <span class="ep-tag-dot" style="background: {{ $product->tags->contains($tag->id) ? '#fff' : $tag->color }}; opacity:1;"></span>
                                {{ $tag->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Images ── --}}
        <div class="ep-card">
            <div class="ep-card-title">Images</div>

            {{-- Featured Image --}}
            <div class="ep-field" style="margin-bottom:1.25rem">
                <label class="ep-label" style="margin-bottom:8px">Featured image</label>
                <div class="ep-images-grid">
                    @if($product->image)
                        <div class="ep-thumb-item">
                            <img src="{{ asset('storage/'.$product->image) }}" alt="Featured">
                            <div class="ep-remove-img" title="Remove">
                                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                        </div>
                    @else
                        <div class="ep-thumb-placeholder">
                            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                        </div>
                    @endif
                    <label class="ep-upload-btn">
                        <svg fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12V4m0 0L8 8m4-4l4 4"/></svg>
                        <span>{{ $product->image ? 'Replace' : 'Upload' }}</span>
                        <input type="file" name="image" accept="image/*">
                    </label>
                </div>
            </div>

            {{-- Additional Images --}}
            <div class="ep-field">
                <label class="ep-label" style="margin-bottom:8px">Additional images</label>
                <div class="ep-images-grid">
                    @if(!empty($product->images))
                        @foreach($product->images as $key => $img)
                            <div class="ep-thumb-item">
                                <img src="{{ asset('storage/'.$img) }}" alt="Image {{ $key + 1 }}">
                                <div class="ep-remove-img" title="Remove">
                                    <label style="display:flex;align-items:center;justify-content:center;width:100%;height:100%;cursor:pointer">
                                        <input type="checkbox" name="remove_images[]" value="{{ $key }}" style="display:none">
                                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width:10px;height:10px"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <label class="ep-upload-btn">
                        <svg fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        <span>Add more</span>
                        <input type="file" name="images[]" accept="image/*" multiple>
                    </label>
                </div>
                <p class="ep-hint">Check the × on any image to remove it on save.</p>
            </div>
        </div>

        {{-- ── Product Variants ── --}}
        <div class="ep-card">
            <div class="ep-card-title">Product variants</div>

            <div class="ep-variants-header">
                <span>Attribute</span>
                <span>Value</span>
                <span>Price</span>
                <span>Stock</span>
                <span>Margin</span>
                <span></span>
            </div>

            <div id="ep-variant-container">
                @foreach($product->variants as $index => $variant)
                    <div class="ep-variant-row">
                        <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">

                        <select name="variants[{{ $index }}][attribute_id]" class="ep-select ep-attribute-selector">
                            @foreach($attributes as $attr)
                                <option value="{{ $attr->id }}"
                                    {{ optional($variant->attributeValues->first())->attribute_id == $attr->id ? 'selected' : '' }}>
                                    {{ $attr->name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="variants[{{ $index }}][attribute_value_id]" class="ep-select ep-value-selector">
                            @foreach($attributeValues->where('attribute_id', optional($variant->attributeValues->first())->attribute_id) as $val)
                                <option value="{{ $val->id }}"
                                    {{ optional($variant->attributeValues->first())->id == $val->id ? 'selected' : '' }}>
                                    {{ $val->value }}
                                </option>
                            @endforeach
                        </select>

                        <input type="number" step="0.01" name="variants[{{ $index }}][price]"
                            class="ep-input" value="{{ $variant->price }}" placeholder="0.00">

                        <input type="number" name="variants[{{ $index }}][stock]"
                            class="ep-input" value="{{ $variant->stock }}">

                        <div class="ep-margin-badge">{{ $variant->margin_price }}</div>

                        @if($product->variants->count() > 1)
                            <div class="ep-del-cell" title="Mark for deletion">
                                <input type="checkbox" name="delete_variants[]" value="{{ $variant->id }}">
                            </div>
                        @else
                            <div></div>
                        @endif
                    </div>
                @endforeach
            </div>

            <button type="button" id="ep-add-variant-btn" class="ep-add-variant-btn">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Add variant
            </button>
        </div>

        {{-- ── Footer Actions ── --}}
        <div class="ep-footer">
            <button type="submit" class="ep-btn-primary">Save changes</button>
            <a href="{{ route('vendor_product') }}" class="ep-btn-secondary">Cancel</a>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    let variantIndex = {{ $product->variants->count() }};
    const allAttributeValues = @json($attributeValues);

    // ── Add variant row ──
    document.getElementById('ep-add-variant-btn').addEventListener('click', function () {
        const container = document.getElementById('ep-variant-container');

        const attrOptions = allAttributeValues
            .filter((v, i, a) => a.findIndex(x => x.attribute_id === v.attribute_id) === i)
            .map(v => {
                // We need attribute names — build from existing selects
                return '';
            });

        // Build attribute options from existing first select
        const existingAttrSel = container.querySelector('.ep-attribute-selector');
        let attrHtml = '<option value="">— Attribute —</option>';
        if (existingAttrSel) {
            attrHtml = existingAttrSel.innerHTML;
            // Deselect all
            attrHtml = attrHtml.replace(/selected/g, '');
        }

        const row = document.createElement('div');
        row.className = 'ep-variant-row ep-new-row';
        row.innerHTML = `
            <select name="variants[${variantIndex}][attribute_id]" class="ep-select ep-attribute-selector">
                ${attrHtml}
            </select>
            <select name="variants[${variantIndex}][attribute_value_id]" class="ep-select ep-value-selector">
                <option value="">— Value —</option>
            </select>
            <input type="number" step="0.01" name="variants[${variantIndex}][price]" class="ep-input" placeholder="0.00">
            <input type="number" name="variants[${variantIndex}][stock]" class="ep-input" value="0">
            <div class="ep-margin-badge" style="color:#d4d4d8">—</div>
            <button type="button" class="ep-remove-row-btn" title="Remove row">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        `;
        container.appendChild(row);
        variantIndex++;
    });

    // ── Attribute → Value cascade ──
    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('ep-attribute-selector')) {
            const attrId = e.target.value;
            const row = e.target.closest('.ep-variant-row');
            const valueSel = row.querySelector('.ep-value-selector');
            const filtered = allAttributeValues.filter(v => v.attribute_id == attrId);
            valueSel.innerHTML = '<option value="">— Value —</option>';
            filtered.forEach(v => {
                valueSel.innerHTML += `<option value="${v.id}">${v.value}</option>`;
            });
        }
    });

    // ── Remove new row ──
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.ep-remove-row-btn');
        if (btn) btn.closest('.ep-variant-row').remove();
    });

    // ── Tag chip toggle ──
    document.querySelectorAll('.ep-tag-chip input[type="checkbox"]').forEach(function (cb) {
        cb.addEventListener('change', function () {
            const chip = cb.closest('.ep-tag-chip');
            const color = cb.dataset.color;
            const dot = chip.querySelector('.ep-tag-dot');
            if (cb.checked) {
                chip.classList.add('active');
                chip.style.background = color;
                chip.style.borderColor = color;
                chip.style.color = '#fff';
                dot.style.background = '#fff';
            } else {
                chip.classList.remove('active');
                chip.style.background = '';
                chip.style.borderColor = '';
                chip.style.color = '';
                dot.style.background = color;
            }
        });
    });

    // ── Remove additional image (visual feedback) ──
    document.querySelectorAll('.ep-remove-img').forEach(function (btn) {
        const cb = btn.querySelector('input[type="checkbox"]');
        if (!cb) return;
        btn.addEventListener('click', function () {
            const thumb = btn.closest('.ep-thumb-item');
            if (cb.checked) {
                thumb.style.opacity = '';
                thumb.style.transform = '';
            } else {
                thumb.style.opacity = '.35';
                thumb.style.transform = 'scale(.95)';
                thumb.style.transition = 'opacity .2s, transform .2s';
            }
        });
    });
})();
</script>
@endpush