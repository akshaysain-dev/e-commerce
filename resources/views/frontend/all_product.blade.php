@extends('layouts.frontend')

@section('title', 'All Products')

@section('specific_menu')
<li class="nav-item dropdown custom-hover">
    <a class="nav-link dropdown-toggle px-3" href="" id="catDropdown" role="button" data-bs-toggle="dropdown">
        Categories
    </a>
    <ul class="dropdown-menu shadow border-0">
        @foreach($categories as $category)
            <li>
                <a class="dropdown-item py-2"
                href="{{ route('category', ['id' => $category->id, 'name' => Str::slug($category->slug)]) }}">
                    {{ $category->name }}
                </a>
            </li>
        @endforeach
    </ul>
</li>
@endsection

@section('styles')
<style>
    @media (min-width: 992px) {
        .custom-hover:hover .dropdown-menu { display: block; }
        .dropdown-toggle::after { vertical-align: middle; }
    }

    /* Sticky sidebar fix */
    .filter-sticky-col {
        position: sticky;
        top: 20px;
        height: fit-content;
        align-self: flex-start;
    }

    /* Range slider — browser required */
    .price-range-input {
        -webkit-appearance: none;
        appearance: none;
        background: transparent;
        pointer-events: none;
        position: absolute;
        width: 100%;
        top: 50%;
        transform: translateY(-50%);
        z-index: 3;
        margin: 0;
    }
    .price-range-input::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #0d6efd;
        cursor: pointer;
        pointer-events: all;
        box-shadow: 0 0 3px rgba(13,110,253,.3);
    }
    .price-range-input::-moz-range-thumb {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #0d6efd;
        cursor: pointer;
        pointer-events: all;
    }
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }
</style>
@endsection

@section('content')
@php
    $globalMin      = 0;
    $globalMax      = (\App\Models\ProductVariant::all()->max('price'))+10000 ?? 100000;
    $currentMin     = request('min_price', $globalMin);
    $currentMax     = request('max_price', $globalMax);
    $activeCategory = request('category_id');
@endphp

<div class="container px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">
            Products
            <small class="text-muted fw-normal fs-6 ms-1">({{ $products->total() }} results)</small>
        </h4>
    </div>

    {{-- Use d-flex so sticky works correctly --}}
    <div class="d-flex gap-3 align-items-start">

        {{-- ═══════════ STICKY SIDEBAR ═══════════ --}}
        <div class="filter-sticky-col" style="width: 260px; min-width: 260px;">

            <p class="fw-bold text-uppercase small border-bottom pb-2 mb-3 text-dark ls-1">Filters</p>

            {{-- Category Card --}}
            <div class="card border rounded-3 mb-3">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-2 px-3"
                     role="button" onclick="toggleFilter('catBody','catChevron')">
                    <span class="fw-semibold small text-uppercase text-dark">Category</span>
                    <span id="catChevron" class="text-secondary" style="transition:transform .2s; display:inline-block;">&#x2303;</span>
                </div>
                <div class="card-body px-3 py-3" id="catBody">
                    @if($activeCategory)
                        <div class="mb-2">
                            <span class="badge d-inline-flex align-items-center gap-1 rounded-pill px-2 py-1"
                                  style="background:#e8f0fe; color:#1a73e8; border:1px solid #1a73e8; font-size:11px;">
                                {{ $category_globle->firstWhere('id', $activeCategory)?->name ?? 'Category' }}
                                <span id="clearCategoryChip" role="button" style="font-size:13px; line-height:1;">&#x2715;</span>
                            </span>
                        </div>
                    @endif
                    <select class="form-select form-select-sm" id="categorySelect">
                        <option value="">All Categories</option>
                        @foreach($category_globle as $cat)
                            <option value="{{ $cat->id }}" {{ $activeCategory == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Price Card --}}
            <div class="card border rounded-3 mb-3">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-2 px-3"
                     role="button" onclick="toggleFilter('priceBody','priceChevron')">
                    <span class="fw-semibold small text-uppercase text-dark">Price</span>
                    <span id="priceChevron" class="text-secondary" style="transition:transform .2s; display:inline-block;">&#x2303;</span>
                </div>
                <div class="card-body px-3 py-3" id="priceBody">
                    @if(request()->hasAny(['min_price','max_price']))
                        <div class="mb-2">
                            <span class="badge d-inline-flex align-items-center gap-1 rounded-pill px-2 py-1"
                                  style="background:#e8f0fe; color:#1a73e8; border:1px solid #1a73e8; font-size:11px;">
                                ₹{{ number_format($currentMin) }} – ₹{{ number_format($currentMax) }}
                                <span id="clearPriceChip" role="button" style="font-size:13px; line-height:1;">&#x2715;</span>
                            </span>
                        </div>
                    @endif

                    {{-- Dual slider --}}
                    <div class="position-relative" style="height:36px;">
                        <div class="position-absolute w-100 rounded bg-secondary bg-opacity-25"
                             style="top:50%; transform:translateY(-50%); height:4px; z-index:1;"></div>
                        <div class="position-absolute rounded bg-primary" id="sliderRange"
                             style="top:50%; transform:translateY(-50%); height:4px; z-index:2; pointer-events:none;"></div>
                        <input type="range" class="price-range-input" id="minRange"
                               min="{{ $globalMin }}" max="{{ $globalMax }}"
                               step="100" value="{{ $currentMin }}">
                        <input type="range" class="price-range-input" id="maxRange"
                               min="{{ $globalMin }}" max="{{ $globalMax }}"
                               step="100" value="{{ $currentMax }}">
                    </div>

                    {{-- Min / Max inputs --}}
                    <div class="d-flex align-items-center gap-2 mt-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted">₹</span>
                            <input type="number" id="minInput"
                                   class="form-control form-control-sm border-start-0 fw-semibold"
                                   value="{{ $currentMin }}"
                                   min="{{ $globalMin }}" max="{{ $globalMax }}" placeholder="Min">
                        </div>
                        <span class="text-muted fw-bold">—</span>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted">₹</span>
                            <input type="number" id="maxInput"
                                   class="form-control form-control-sm border-start-0 fw-semibold"
                                   value="{{ $currentMax }}"
                                   min="{{ $globalMin }}" max="{{ $globalMax }}" placeholder="Max">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="d-grid gap-2">
                <button class="btn btn-primary btn-sm fw-semibold" id="applyAllFilters">Apply Filters</button>
                @if(request()->hasAny(['min_price','max_price','category_id']))
                    <button class="btn btn-outline-primary btn-sm fw-semibold" id="clearAllFilters">Clear All Filters</button>
                @endif
            </div>

        </div>
        {{-- ═══════════ END SIDEBAR ═══════════ --}}

        {{-- ═══════════ PRODUCT GRID ═══════════ --}}
        <div class="flex-grow-1 min-w-0">

            <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4 g-3">
                @forelse($products as $product)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                        <div class="position-relative overflow-hidden" style="height:200px;">
                            <img src="{{ asset('storage/'.$product->image) }}"
                                 class="w-100 h-100"
                                 style="object-fit:cover;"
                                 alt="{{ $product->name }}">
                            @if(($product->firstVariant?->stock ?? 0) == 0)
                                <span class="badge bg-danger position-absolute top-0 start-0 m-2">Out of Stock</span>
                            @else
                                <span class="badge bg-success position-absolute top-0 end-0 m-2">In Stock</span>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column p-3">
                            <small class="text-muted text-uppercase fw-semibold" style="font-size:10px; letter-spacing:.5px;">
                                {{ $product->category->name ?? '' }}
                            </small>
                            <h6 class="fw-semibold mt-1 mb-1" style="font-size:14px; line-height:1.3;">
                                {{ \Illuminate\Support\Str::limit($product->name, 40) }}
                            </h6>
                            <p class="text-muted small mb-2" style="font-size:12px;">
                                {{ \Illuminate\Support\Str::limit($product->description, 60) }}
                            </p>
                            <div class="mt-auto">
                                <p class="fw-bold text-success mb-2 fs-6">
                                    ₹{{ number_format($product->firstVariant?->price ?? 0, 2) }}
                                </p>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('view_product', $product->id) }}"
                                       class="btn btn-outline-primary btn-sm flex-fill">View</a>
                                    <button class="btn btn-dark btn-sm flex-fill"
                                            {{ $product->stock == 0 ? 'disabled' : '' }}>Add to Cart</button>
                                </div>
                                <span>Rating: {{ number_format($product->averageRating(), 1) }} / 5 Stars</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted fs-5 mb-2">No products found.</p>
                        <a href="{{ route('all_products') }}" class="btn btn-outline-primary btn-sm">Clear Filters</a>
                    </div>
                @endforelse
            </div>

            @if($products->hasPages())
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 p-3 bg-white rounded-3 border">
                <div class="small text-muted mb-2 mb-md-0">
                    Showing <strong>{{ $products->firstItem() }}</strong>
                    to <strong>{{ $products->lastItem() }}</strong>
                    of <strong>{{ $products->total() }}</strong> products
                </div>
                <nav>{{ $products->links('pagination::bootstrap-5') }}</nav>
            </div>
            @endif

        </div>
        {{-- ═══════════ END PRODUCT GRID ═══════════ --}}

    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const GLOBAL_MIN = {{ $globalMin }};
    const GLOBAL_MAX = {{ $globalMax }};

    const minRange  = document.getElementById('minRange');
    const maxRange  = document.getElementById('maxRange');
    const minInput  = document.getElementById('minInput');
    const maxInput  = document.getElementById('maxInput');
    const rangeFill = document.getElementById('sliderRange');
    const catSelect = document.getElementById('categorySelect');

    function updateFill() {
        const min   = parseInt(minRange.value);
        const max   = parseInt(maxRange.value);
        const total = GLOBAL_MAX - GLOBAL_MIN;
        rangeFill.style.left  = ((min - GLOBAL_MIN) / total * 100) + '%';
        rangeFill.style.right = ((GLOBAL_MAX - max)  / total * 100) + '%';
    }

    minRange.addEventListener('input', function () {
        let val = parseInt(this.value);
        if (val >= parseInt(maxRange.value)) { val = parseInt(maxRange.value) - 100; this.value = val; }
        minInput.value = val;
        updateFill();
    });

    maxRange.addEventListener('input', function () {
        let val = parseInt(this.value);
        if (val <= parseInt(minRange.value)) { val = parseInt(minRange.value) + 100; this.value = val; }
        maxInput.value = val;
        updateFill();
    });

    minInput.addEventListener('input', function () {
        let val = parseInt(this.value);
        if (isNaN(val)) return;
        val = Math.max(GLOBAL_MIN, Math.min(val, parseInt(maxInput.value) - 100));
        minRange.value = val;
        updateFill();
    });

    maxInput.addEventListener('input', function () {
        let val = parseInt(this.value);
        if (isNaN(val)) return;
        val = Math.min(GLOBAL_MAX, Math.max(val, parseInt(minInput.value) + 100));
        maxRange.value = val;
        updateFill();
    });

    document.getElementById('applyAllFilters').addEventListener('click', function () {
        const url = new URL(window.location.href);
        url.searchParams.set('min_price', parseInt(minInput.value) || GLOBAL_MIN);
        url.searchParams.set('max_price', parseInt(maxInput.value) || GLOBAL_MAX);
        catSelect.value
            ? url.searchParams.set('category_id', catSelect.value)
            : url.searchParams.delete('category_id');
        url.searchParams.delete('page');
        window.location.href = url.toString();
    });

    const clearAllBtn = document.getElementById('clearAllFilters');
    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function () {
            window.location.href = '{{ route("all_products") }}';
        });
    }

    const clearPriceChip = document.getElementById('clearPriceChip');
    if (clearPriceChip) {
        clearPriceChip.addEventListener('click', function () {
            const url = new URL(window.location.href);
            url.searchParams.delete('min_price');
            url.searchParams.delete('max_price');
            url.searchParams.delete('page');
            window.location.href = url.toString();
        });
    }

    const clearCatChip = document.getElementById('clearCategoryChip');
    if (clearCatChip) {
        clearCatChip.addEventListener('click', function () {
            const url = new URL(window.location.href);
            url.searchParams.delete('category_id');
            url.searchParams.delete('page');
            window.location.href = url.toString();
        });
    }

    window.toggleFilter = function (bodyId, chevronId) {
        const body    = document.getElementById(bodyId);
        const chevron = document.getElementById(chevronId);
        const isOpen  = body.style.display !== 'none';
        body.style.display = isOpen ? 'none' : 'block';
        chevron.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';
    };

    updateFill();
})();
</script>
@endpush