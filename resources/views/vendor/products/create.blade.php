@extends('vendor.layouts.app')

@section('title', 'Create New Product')

@section('content')

<div class="container mt-4 mb-3">
    <h2>Add New Product</h2>

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

    <form action="{{ route('admin_add_products') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- User ID -->
        <input type="hidden" name="user_id" value="{{ session('admin_id') }}">

        <!-- Category dropdown -->
        <div class="mb-3">
            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" >
                <option value="">-- Select Category --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
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
                    <option value="{{ $type->id }}" {{ old('product_type_id') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>

            @error('product_type_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Product Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Featured Image -->
        <div class="mb-3">
            <label for="image" class="form-label">Featured Image</label>
            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Additional Images -->
        <div class="mb-3">
            <label for="images" class="form-label">Additional Images</label>
            <input type="file" name="images[]" id="images" class="form-control @error('images') is-invalid @enderror" accept="image/*" multiple>
            <small class="text-muted">You can select multiple images.</small>
            @error('images')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Status checkbox -->
        <div class="mb-3 form-check">
            <input type="checkbox" name="status" id="status" class="form-check-input" value="1" {{ old('status', 1) ? 'checked' : '' }}>
            <label for="status" class="form-check-label">Active</label>
        </div>

        <hr>
            <h5>Product Variants</h5>
            <div id="variant-container">
                <div class="row mb-3 variant-row border p-2 rounded bg-light">
                    <!-- Attribute Selection (e.g., Color) -->
                    <div class="col-md-2">
                        <label class="form-label">Attribute</label>
                        <select name="variants[0][attribute_id]" class="form-select attribute-selector">
                            <option value="">-- Type --</option>
                            @foreach($attributes as $attr)
                                <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Attribute Value Selection (e.g., Red) -->
                    <div class="col-md-2">
                        <label class="form-label">Value</label>
                        <select name="variants[0][attribute_value_id]" class="form-select value-selector">
                            <option value="">-- Select --</option>
                        </select>
                    </div>

                    <!-- Price, Stock, SKU -->
                    <div class="col-md-2">
                        <label class="form-label">Price</label>
                        <input type="number" step="0.01" name="variants[0][price]" class="form-control" placeholder="0.00">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Stock</label>
                        <input type="number" name="variants[0][stock]" class="form-control" value="0">
                    </div>
                   <!--  <div class="col-md-2">
                        <label class="form-label">SKU</label>
                        <input type="text" name="variants[0][sku]" class="form-control" placeholder="SKU">
                    </div> -->
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-danger remove-variant w-100">Remove</button>
                    </div>
                </div>
            </div>

            <button type="button" id="add-variant-btn" class="btn btn-dark btn-sm mt-3">+ Add Another Variant</button>
        <hr>


        <button type="submit" class="btn btn-primary">Add Product</button>
        <a href="{{ route('admin_product') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

@endsection

@push('scripts')
<script>
    let variantIndex = 1;
    const allAttributeValues = @json($attributeValues); // Pass values from Controller

    // Add New Row
    document.getElementById('add-variant-btn').addEventListener('click', function() {
        const container = document.getElementById('variant-container');
        const firstRow = document.querySelector('.variant-row').cloneNode(true);
        
        // Clean inputs and update names for the new index
        firstRow.querySelectorAll('input, select').forEach(input => {
            input.name = input.name.replace(/\[\d+\]/, `[${variantIndex}]`);
            input.value = (input.tagName === 'SELECT') ? "" : (input.name.includes('stock') ? 0 : "");
        });

        container.appendChild(firstRow);
        variantIndex++;
    });

    // Handle "Attribute -> Value" dependent dropdown logic
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('attribute-selector')) {
            const attrId = e.target.value;
            const row = e.target.closest('.variant-row');
            const valueSelector = row.querySelector('.value-selector');

            // Filter values based on selected attribute ID
            const filteredValues = allAttributeValues.filter(v => v.attribute_id == attrId);

            valueSelector.innerHTML = '<option value="">-- Select --</option>';
            filteredValues.forEach(v => {
                valueSelector.innerHTML += `<option value="${v.id}">${v.value}</option>`;
            });
        }
    });

    // Remove Row
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-variant')) {
            const rows = document.querySelectorAll('.variant-row');
            if (rows.length > 1) e.target.closest('.variant-row').remove();
        }
    });
</script>
@endpush