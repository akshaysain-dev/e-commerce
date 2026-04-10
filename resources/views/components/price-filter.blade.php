@php
    $globalMin      = 0;
    $globalMax      = \App\Models\Product::where('status', 1)->max('price') ?? 100000;
    $currentMin     = request('min_price', $globalMin);
    $currentMax     = request('max_price', $globalMax);
    $activeCategory = request('category_id');
@endphp

<style>
.filter-card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    margin-bottom: 12px;
}
.filter-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 13px 16px;
    font-size: 13px;
    font-weight: 700;
    color: #212121;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    user-select: none;
}
.filter-chevron {
    font-size: 13px;
    color: #757575;
    transition: transform .2s;
    font-weight: 400;
    text-transform: none;
    letter-spacing: 0;
}
.filter-chevron.collapsed { transform: rotate(180deg); }
.filter-card-body { padding: 16px; }

.filter-select {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid #c0c0c0;
    border-radius: 3px;
    font-size: 13px;
    color: #212121;
    background: #fafafa;
    outline: none;
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23757575' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
}
.filter-select:focus { border-color: #2874f0; }

.price-slider-wrapper {
    position: relative;
    height: 36px;
    margin: 10px 4px 4px;
}
.price-slider-track {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 100%;
    height: 4px;
    background: #e0e0e0;
    border-radius: 4px;
    z-index: 1;
}
.price-slider-range {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    height: 4px;
    background: #2874f0;
    border-radius: 4px;
    z-index: 2;
    pointer-events: none;
}
.price-range-input {
    position: absolute;
    width: 100%;
    top: 50%;
    transform: translateY(-50%);
    -webkit-appearance: none;
    appearance: none;
    background: transparent;
    pointer-events: none;
    z-index: 3;
    margin: 0;
}
.price-range-input::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #fff;
    border: 2px solid #2874f0;
    cursor: pointer;
    pointer-events: all;
    box-shadow: 0 1px 4px rgba(40,116,240,0.4);
    transition: transform 0.15s, box-shadow 0.15s;
}
.price-range-input::-webkit-slider-thumb:hover {
    transform: scale(1.2);
    box-shadow: 0 2px 8px rgba(40,116,240,0.5);
}
.price-range-input::-moz-range-thumb {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #fff;
    border: 2px solid #2874f0;
    cursor: pointer;
    pointer-events: all;
}
.price-labels {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 14px;
    gap: 8px;
}
.price-input-box {
    display: flex;
    align-items: center;
    border: 1px solid #c0c0c0;
    border-radius: 3px;
    padding: 5px 8px;
    width: 95px;
    background: #fafafa;
    gap: 3px;
}
.price-input-box span.rupee {
    color: #757575;
    font-size: 12px;
    flex-shrink: 0;
}
.price-input-box input {
    border: none;
    background: transparent;
    width: 100%;
    font-size: 13px;
    font-weight: 600;
    color: #212121;
    outline: none;
    padding: 0;
    -moz-appearance: textfield;
}
.price-input-box input::-webkit-outer-spin-button,
.price-input-box input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
.price-labels .separator { color: #bbb; font-size: 13px; }

.filter-apply-btn {
    display: block;
    width: 100%;
    margin-top: 14px;
    padding: 8px 0;
    background: #2874f0;
    color: #fff;
    border: none;
    border-radius: 3px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s;
}
.filter-apply-btn:hover { background: #1a5ecf; }

.filter-clear-btn {
    display: block;
    width: 100%;
    margin-top: 8px;
    padding: 7px 0;
    background: #fff;
    color: #2874f0;
    border: 1px solid #2874f0;
    border-radius: 3px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
    text-align: center;
}
.filter-clear-btn:hover { background: #e8f0fe; }

.active-chips-wrap { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 10px; }
.active-filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #e8f0fe;
    border: 1px solid #2874f0;
    color: #2874f0;
    border-radius: 2px;
    padding: 3px 8px;
    font-size: 11px;
    font-weight: 600;
}
.active-filter-chip .chip-close {
    cursor: pointer;
    font-size: 13px;
    font-weight: 700;
    line-height: 1;
}
</style>

{{-- ======= CATEGORY FILTER ======= --}}
<div class="filter-card">
    <div class="filter-card-header" onclick="toggleFilter('catBody', 'catChevron')">
        <span>Category</span>
        <span class="filter-chevron" id="catChevron">∧</span>
    </div>
    <div class="filter-card-body" id="catBody">

        @if($activeCategory)
            <div class="active-chips-wrap">
                <div class="active-filter-chip">
                    {{ $category_globle->firstWhere('id', $activeCategory)?->name ?? 'Category' }}
                    <span class="chip-close" id="clearCategoryChip">✕</span>
                </div>
            </div>
        @endif

        <select class="filter-select" id="categorySelect">
            <option value="">All Categories</option>
            @foreach($category_globle as $cat)
                <option value="{{ $cat->id }}" {{ $activeCategory == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

    </div>
</div>

{{-- ======= PRICE FILTER ======= --}}
<div class="filter-card">
    <div class="filter-card-header" onclick="toggleFilter('priceBody', 'priceChevron')">
        <span>Price</span>
        <span class="filter-chevron" id="priceChevron">∧</span>
    </div>
    <div class="filter-card-body" id="priceBody">

        @if(request()->hasAny(['min_price', 'max_price']))
            <div class="active-chips-wrap">
                <div class="active-filter-chip">
                    ₹{{ number_format($currentMin) }} – ₹{{ number_format($currentMax) }}
                    <span class="chip-close" id="clearPriceChip">✕</span>
                </div>
            </div>
        @endif

        <div class="price-slider-wrapper">
            <div class="price-slider-track"></div>
            <div class="price-slider-range" id="sliderRange"></div>
            <input type="range" class="price-range-input" id="minRange"
                   min="{{ $globalMin }}" max="{{ $globalMax }}"
                   step="100" value="{{ $currentMin }}">
            <input type="range" class="price-range-input" id="maxRange"
                   min="{{ $globalMin }}" max="{{ $globalMax }}"
                   step="100" value="{{ $currentMax }}">
        </div>

        <div class="price-labels">
            <div class="price-input-box">
                <span class="rupee">₹</span>
                <input type="number" id="minInput" value="{{ $currentMin }}"
                       min="{{ $globalMin }}" max="{{ $globalMax }}" placeholder="Min">
            </div>
            <span class="separator">—</span>
            <div class="price-input-box">
                <span class="rupee">₹</span>
                <input type="number" id="maxInput" value="{{ $currentMax }}"
                       min="{{ $globalMin }}" max="{{ $globalMax }}" placeholder="Max">
            </div>
        </div>

    </div>
</div>

{{-- ======= BUTTONS ======= --}}
<button class="filter-apply-btn" id="applyAllFilters">Apply Filters</button>

@if(request()->hasAny(['min_price', 'max_price', 'category_id']))
    <button class="filter-clear-btn" id="clearAllFilters">Clear All Filters</button>
@endif

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
        if (catSelect.value) {
            url.searchParams.set('category_id', catSelect.value);
        } else {
            url.searchParams.delete('category_id');
        }
        url.searchParams.delete('page');
        window.location.href = url.toString();
    });

    const clearAllBtn = document.getElementById('clearAllFilters');
    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function () {
            const url = new URL(window.location.href);
            url.searchParams.delete('min_price');
            url.searchParams.delete('max_price');
            url.searchParams.delete('category_id');
            url.searchParams.delete('page');
            window.location.href = url.toString();
        });
    }

    const clearPriceChip = document.getElementById('clearPriceChip');
    if (clearPriceChip) {
        clearPriceChip.addEventListener('click', function () {
            const url = new URL(window.location.href);
            url.searchParams.delete('min_price');
            url.searchParams.delete('max_price');
            window.location.href = url.toString();
        });
    }

    const clearCatChip = document.getElementById('clearCategoryChip');
    if (clearCatChip) {
        clearCatChip.addEventListener('click', function () {
            const url = new URL(window.location.href);
            url.searchParams.delete('category_id');
            window.location.href = url.toString();
        });
    }

    window.toggleFilter = function (bodyId, chevronId) {
        const body    = document.getElementById(bodyId);
        const chevron = document.getElementById(chevronId);
        const isOpen  = body.style.display !== 'none';
        body.style.display = isOpen ? 'none' : 'block';
        chevron.classList.toggle('collapsed', isOpen);
    };

    updateFill();
})();
</script>