@extends('layouts.backend')

@section('title', 'My Shop')

@section('content')

<div class="container mt-4 mb-3">
    <h2>Edit Product</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please fix the following errors:<br><br>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="hidden" name="user_id" value="{{ session('admin_id') }}">

        <div class="mb-3">
            <label class="form-label">Category <span class="text-danger">*</span></label>
            <select name="category_id" class="form-select">
                <option value="">-- Select Category --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Product Type Selection -->
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label for="type_id" class="form-label mb-0">Product Type <span class="text-danger">*</span></label>
                <a href="{{ route('product-types.index') }}" class="text-primary text-decoration-none small">
                    <i class="bi bi-plus-lg"></i> Add New
                </a>
            </div>
            
            <select name="product_type_id" id="type_id" class="form-select @error('type_id') is-invalid @enderror">
                <option value="">-- Select Type --</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}" {{ old('product_type_id', $product->product_type_id ?? '') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>

            @error('product_type_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Tags</label>
            <div class="d-flex flex-wrap gap-2">
                @foreach($tags as $tag)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                            name="tags[]" value="{{ $tag->id }}"
                            id="tag-{{ $tag->id }}"
                            {{ $product->tags->contains($tag->id) ? 'checked' : '' }}>
                        <label class="form-check-label" for="tag-{{ $tag->id }}">
                            <span style="background:{{ $tag->color }}; color:#fff;
                                        padding:2px 10px; border-radius:20px; font-size:.78rem;">
                                {{ $tag->name }}
                            </span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Product Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" rows="3" class="form-control">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Featured Image</label>
            @if($product->image)
                <div class="mb-2">
                    <img src="{{ asset('storage/'.$product->image) }}" width="120">
                </div>
            @endif
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <div class="mb-3">
            <label class="form-label">Additional Images</label>
            @if(!empty($product->images))
                <div class="mb-2 d-flex flex-wrap">
                    @foreach($product->images as $key => $img)
                        <div class="me-2 mb-2 text-center">
                            <img src="{{ asset('storage/'.$img) }}" width="100" class="mb-1"><br>
                            <label class="form-check-label">
                                <input type="checkbox" name="remove_images[]" value="{{ $key }}"> Remove
                            </label>
                        </div>
                    @endforeach
                </div>
            @endif
            <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
            <small class="text-muted">You can add multiple images.</small>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="status" class="form-check-input" value="1" {{ old('status', $product->status) ? 'checked' : '' }}>
            <label class="form-check-label">Active</label>
        </div>

        <hr>
            <h5>Product Variants</h5>
            <div id="variant-container">
                @foreach($product->variants as $index => $variant)
                    <div class="row mb-3 variant-row border p-2 rounded bg-light">
                        <!-- Hidden ID to know we are UPDATING an existing variant -->
                        <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">

                        <div class="col-md-2">
                            <label class="form-label">Attribute</label>
                            <select name="variants[{{ $index }}][attribute_id]" class="form-select attribute-selector">
                                @foreach($attributes as $attr)
                                    <option value="{{ $attr->id }}" 
                                        {{ optional($variant->attributeValues->first())->attribute_id == $attr->id ? 'selected' : '' }}>
                                        {{ $attr->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Value</label>
                            <select name="variants[{{ $index }}][attribute_value_id]" class="form-select value-selector">
                                @foreach($attributeValues->where('attribute_id', optional($variant->attributeValues->first())->attribute_id) as $val)
                                    <option value="{{ $val->id }}" 
                                        {{ optional($variant->attributeValues->first())->id == $val->id ? 'selected' : '' }}>
                                        {{ $val->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Price</label>
                            <input type="number" step="0.01" name="variants[{{ $index }}][price]" class="form-control" value="{{ $variant->price }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Stock</label>
                            <input type="number" name="variants[{{ $index }}][stock]" class="form-control" value="{{ $variant->stock }}">
                        </div>
						<div class="col-md-2">
                            <label class="form-label">Margin Price</label>
                            <input type="text" class="form-control" value="{{ $variant->margin_price }}" disabled readonly>
                        </div>
                        <!-- <div class="col-md-2">
                            <label class="form-label">SKU</label>
                            <input type="text" name="variants[{{ $index }}][sku]" class="form-control" value="{{ $variant->sku }}">
                        </div> -->
                        @if($product->variants->count() > 1)
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-check mb-2">
                                <input type="checkbox" name="delete_variants[]" value="{{ $variant->id }}" class="form-check-input">
                                <label class="text-danger">Delete</label>
                            </div>
                        </div>
						@endif
                    </div>
                @endforeach
            </div>

            <button type="button" id="add-variant-btn" class="btn btn-dark btn-sm mt-3">+ Add New Variant</button>
        <hr>


        <button class="btn btn-primary">Update Product</button>
        <a href="{{ route('admin_product') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

@endsection

@push('scripts')
<script>
    let variantIndex = {{ $product->variants->count() }}; 
    const allAttributeValues = @json($attributeValues);

    document.getElementById('add-variant-btn').addEventListener('click', function() {
        const container = document.getElementById('variant-container');
        
        const newRow = `
            <div class="row mb-3 variant-row border p-2 rounded bg-white mt-2">
                <!-- No ID hidden input here because this is a NEW variant -->
                
                <div class="col-md-2">
                    <label class="form-label text-muted small">Attribute</label>
                    <select name="variants[${variantIndex}][attribute_id]" class="form-select attribute-selector">
                        <option value="">-- Type --</option>
                        @foreach($attributes as $attr)
                            <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label text-muted small">Value</label>
                    <select name="variants[${variantIndex}][attribute_value_id]" class="form-select value-selector">
                        <option value="">-- Select --</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label text-muted small">Price</label>
                    <input type="number" step="0.01" name="variants[${variantIndex}][price]" class="form-control" placeholder="0.00">
                </div>

                <div class="col-md-2">
                    <label class="form-label text-muted small">Stock</label>
                    <input type="number" name="variants[${variantIndex}][stock]" class="form-control" value="0">
                </div>

               <!-- <div class="col-md-2">
                    <label class="form-label text-muted small">SKU</label>
                    <input type="text" name="variants[${variantIndex}][sku]" class="form-control" placeholder="SKU">
                </div> -->
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger remove-new-row w-100">Cancel</button>
                </div>
            </div>`;

        container.insertAdjacentHTML('beforeend', newRow);
        variantIndex++;
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('attribute-selector')) {
            const attrId = e.target.value;
            const row = e.target.closest('.variant-row');
            const valueSelector = row.querySelector('.value-selector');

            const filteredValues = allAttributeValues.filter(v => v.attribute_id == attrId);

            valueSelector.innerHTML = '<option value="">-- Select --</option>';
            filteredValues.forEach(v => {
                valueSelector.innerHTML += `<option value="${v.id}">${v.value}</option>`;
            });
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-new-row')) {
            e.target.closest('.variant-row').remove();
        }
    });
</script>

@endpush