@extends('layouts.frontend')

@section('title', $product->name)

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

    .sale-ribbon {
        display: inline-flex; align-items: center; gap: 7px;
        background: linear-gradient(135deg, #ef4444, #b91c1c);
        color: #fff; border-radius: 8px; padding: 6px 14px;
        font-size: .82rem; font-weight: 700; margin-bottom: 10px;
        box-shadow: 0 3px 10px rgba(239,68,68,.3);
        animation: sale-pulse 2.5s ease-in-out infinite;
    }
    @keyframes sale-pulse {
        0%,100% { box-shadow: 0 3px 10px rgba(239,68,68,.3); }
        50%      { box-shadow: 0 4px 18px rgba(239,68,68,.5); }
    }
    .sale-ribbon .dot {
        width: 6px; height: 6px; border-radius: 50%; background: #fff;
        animation: blink 1.2s ease-in-out infinite;
    }
    @keyframes blink { 0%,100%{opacity:1;} 50%{opacity:.2;} }

    .price-sale { font-size: 1.75rem; font-weight: 800; color: #ef4444; }
    .price-orig { font-size: 1.1rem; color: #94a3b8; text-decoration: line-through; }
    .save-badge {
        background: #fef2f2; color: #ef4444;
        border: 1px solid #fecaca; border-radius: 5px;
        padding: 2px 8px; font-size: .75rem; font-weight: 800;
    }
    .sale-ends { font-size: .78rem; color: #ef4444; font-weight: 600; margin-top: 4px; }
    .variant-sale-info { font-size: .6rem; color: #ef4444; font-weight: 600; margin-top: 2px; }
</style>
@endsection

@section('content')

@php
    $hasSale         = isset($sale) && $sale && $sale->isActive();
    $firstVariant    = $product->variants->first();
    $basePrice       = $firstVariant->margin_price ?? 0;
    $discountedPrice = $hasSale ? $sale->applyDiscount($basePrice) : $basePrice;
    $savedAmount     = $basePrice - $discountedPrice;
@endphp

<div class="container mt-5">
    <div class="row">

        <div class="col-md-6">
            <div class="mb-3 border text-center p-2 position-relative" style="height:450px; display:flex; align-items:center; justify-content:center;">
                <img id="featuredImage" src="{{ asset('storage/'.$product->image) }}" class="img-fluid" style="max-height:100%;" alt="{{ $product->name }}">
                @if($hasSale)
                <div style="position:absolute; top:12px; left:12px; background:#ef4444; color:#fff; font-size:.78rem; font-weight:800; padding:5px 12px; border-radius:6px; box-shadow:0 2px 8px rgba(239,68,68,.4);">
                    🔥 {{ $sale->discount_label }}
                </div>
                @endif
            </div>
            <div class="d-flex flex-wrap gap-2">
                <img src="{{ asset('storage/'.$product->image) }}" class="img-thumbnail thumb-image active-thumb" style="width:80px; height:80px; cursor:pointer;" alt="Main">
                @if(!empty($product->images))
                    @foreach($product->images as $img)
                        <img src="{{ asset('storage/'.$img) }}" class="img-thumbnail thumb-image" style="width:80px; height:80px; cursor:pointer;" alt="Extra">
                    @endforeach
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active">{{ $product->category->name ?? 'Product' }}</li>
                </ol>
            </nav>

            <h2 class="fw-bold">{{ $product->name }}</h2>

            @if($hasSale)
            <div class="sale-ribbon">
                <span class="dot"></span>
                🔥 {{ $sale->name }} — {{ $sale->discount_label }}
            </div>
            @endif

            <div class="mt-2 mb-1">
                @if($hasSale)
                    <div class="d-flex align-items-baseline gap-3 flex-wrap">
                        <span class="price-sale" id="display-price">₹{{ number_format($discountedPrice, 2) }}</span>
                        <span class="price-orig"  id="display-original-price">₹{{ number_format($basePrice, 2) }}</span>
                        <span class="save-badge"  id="display-save">Save ₹{{ number_format($savedAmount, 2) }}</span>
                    </div>
                    <div class="sale-ends">⏱ Sale ends {{ $sale->ends_at->diffForHumans() }}</div>
                @else
                    <div class="d-flex align-items-baseline gap-3 mt-2">
                        <h3 class="text-danger fw-bold" id="display-price">₹{{ number_format($basePrice, 2) }}</h3>
                    </div>
                @endif
            </div>

            <span class="text-muted small">SKU: <span id="display-sku">{{ $firstVariant->sku ?? 'N/A' }}</span></span>

            <p class="mt-3 text-secondary">{{ $product->description }}</p>

            @if($product->variants->count() > 0)
                <div class="mt-4 mb-4">
                    <label class="fw-bold mb-2 text-uppercase small">Available Options:</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($product->variants as $variant)
                            @php
                                $firstAttrValue   = $variant->attributeValues->first();
                                $vOriginalPrice   = $variant->margin_price ?? 0;
                                $vDiscountedPrice = $hasSale ? $sale->applyDiscount($vOriginalPrice) : $vOriginalPrice;
                                $vSaved           = $vOriginalPrice - $vDiscountedPrice;
                            @endphp
                            <button type="button"
                                class="btn btn-outline-dark variant-selector @if($loop->first) active-variant @endif"
                                data-original-price="{{ $vOriginalPrice }}"
                                data-stock="{{ $variant->stock }}"
                                data-sku="{{ $variant->sku }}"
                                data-id="{{ $variant->id }}">

                                {{ $firstAttrValue->value ?? $variant->name }}

                                @if($firstAttrValue && $firstAttrValue->attribute)
                                    <small class="d-block border-top mt-1 pt-1" style="font-size:.6rem;">
                                        {{ $firstAttrValue->attribute->name }}: {{ $firstAttrValue->value }}
                                    </small>
                                @else
                                    <small class="d-block border-top mt-1 pt-1 text-muted" style="font-size:.6rem;">
                                        {{ $variant->name }}
                                    </small>
                                @endif

                                @if($hasSale)
                                <small class="variant-sale-info d-block">
                                    ₹{{ number_format($vDiscountedPrice, 2) }}
                                    <span class="text-decoration-line-through" style="color:#94a3b8;">
                                        ₹{{ number_format($vOriginalPrice, 2) }}
                                    </span>
                                </small>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <p class="small"><strong>Availability:</strong>
                <span id="display-stock" class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                    {{ $product->stock }}
                </span> In Stock
            </p>

            <div class="mt-4 pt-2 border-top">
                <form id="addToCartForm" action="{{ route('addToCart', ['product_id' => $product->id, 'variant_id' => $firstVariant->id ?? 0]) }}" method="GET">
                    @csrf
                    <div class="d-flex gap-2">
                        <input type="number" name="quantity" value="1" min="1" class="form-control" style="width:80px;">
                        <button type="submit" id="add-to-cart-btn" class="btn btn-primary btn-lg px-5">
                            Add to Cart
                        </button>
                    </form>
                    <form action="{{ route('wishlist.toggle') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        @if($product->isWishlisted())
                            <button type="submit" class="btn btn-danger shadow-sm btn-lg px-5">
                                <i class="fas fa-heart"></i> Remove Wishlist
                            </button>
                        @else
                            <button type="submit" class="btn btn-outline-primary shadow-sm btn-lg px-5">
                                <i class="far fa-heart"></i> Add to Wishlist
                            </button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row g-3 align-items-start">

        <div class="col-lg-7">
            <div class="card border rounded-1 mb-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">Ratings &amp; Reviews</h6>
                </div>
                <div class="card-body">
                    <div class="row align-items-center g-3 mb-3">
                        <div class="col-4 text-center">
                            <div style="font-size:3rem; font-weight:300; line-height:1; color:#212121;">
                                {{ $avgRating ?? '0.0' }}
                            </div>
                            <div style="font-size:16px; letter-spacing:2px;">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($avgRating ?? 0))
                                        <span style="color:#ff9f00;">★</span>
                                    @elseif($i - 0.5 <= ($avgRating ?? 0))
                                        <span style="color:#ff9f00; opacity:.6;">★</span>
                                    @else
                                        <span style="color:#dee2e6;">★</span>
                                    @endif
                                @endfor
                            </div>
                            <div class="text-muted mt-1" style="font-size:12px;">{{ number_format($totalRatings ?? 0) }} ratings</div>
                        </div>
                        <div class="col-8">
                            @foreach([5,4,3,2,1] as $star)
                                @php
                                    $count = $starCounts[$star] ?? 0;
                                    $total = $totalRatings ?? 0;
                                    $pct   = $total > 0 ? round(($count / $total) * 100) : 0;
                                    $color = $star >= 4 ? 'success' : ($star == 3 ? 'warning' : 'danger');
                                @endphp
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="text-muted" style="font-size:12px; width:30px; text-align:right;">{{ $star }} ★</span>
                                    <div class="progress flex-grow-1" style="height:8px; border-radius:4px;">
                                        <div class="progress-bar bg-{{ $color }}" style="width:{{ $pct }}%"></div>
                                    </div>
                                    <span class="text-muted" style="font-size:12px; width:32px;">{{ number_format($count) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row g-2 pt-2 border-top">
                        <div class="col-4">
                            <div class="bg-light rounded-1 text-center py-2">
                                @php
                                    $recommended = $starCounts ? collect($starCounts)->filter(fn($v,$k) => $k >= 4)->sum() : 0;
                                    $recPct = $totalRatings > 0 ? round(($recommended / $totalRatings) * 100) : 0;
                                @endphp
                                <div class="fw-semibold" style="font-size:18px;">{{ $recPct }}%</div>
                                <div class="text-muted" style="font-size:11px;">Recommend</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-light rounded-1 text-center py-2">
                                <div class="fw-semibold" style="font-size:18px;">{{ $avgRating ?? '0.0' }}</div>
                                <div class="text-muted" style="font-size:11px;">Avg Rating</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-light rounded-1 text-center py-2">
                                <div class="fw-semibold" style="font-size:18px;">{{ $totalRatings ?? 0 }}</div>
                                <div class="text-muted" style="font-size:11px;">Total Reviews</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border rounded-1">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h6 class="mb-0 fw-semibold">Customer Reviews</h6>
                    <div class="d-flex gap-1 flex-wrap">
                        <button class="btn btn-sm btn-outline-secondary rounded-pill active fk-filter" data-filter="all">All</button>
                        @foreach([5,4,3,2,1] as $s)
                            <button class="btn btn-sm btn-outline-secondary rounded-pill fk-filter" data-filter="{{ $s }}">
                                {{ $s }} ★
                                @if(isset($starCounts[$s]) && $starCounts[$s] > 0)
                                    <span class="text-muted">({{ $starCounts[$s] }})</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
                <div class="card-body p-0" id="reviewsList">
                    @forelse($ratings as $review)
                        <div class="p-3 border-bottom fk-review-item" data-star="{{ $review->rating }}">
                            @php $bg = $review->rating >= 4 ? 'success' : ($review->rating == 3 ? 'warning' : 'danger'); @endphp
                            <span class="badge bg-{{ $bg }} mb-2" style="font-size:11px;">★ {{ $review->rating }}</span>
                            @if($review->title)
                                <div class="fw-semibold mb-1" style="font-size:14px;">{{ $review->title }}</div>
                            @endif
                            @if($review->review)
                                <p class="text-muted mb-2" style="font-size:13px; line-height:1.6;">{{ $review->review }}</p>
                            @endif
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span style="font-size:12px;"><strong>{{ $review->customer->name ?? 'Anonymous' }}</strong></span>
                                @if($review->is_verified_purchase)
                                    <span class="text-success" style="font-size:11px; font-weight:500;">✓ Certified Buyer</span>
                                @endif
                                <span class="text-muted" style="font-size:11px;">· {{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <span class="text-muted" style="font-size:12px;">Helpful?</span>
                                <form action="{{ route('ratings.helpful', $review->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="vote" value="yes">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:12px;">👍 Yes ({{ $review->helpful_yes ?? 0 }})</button>
                                </form>
                                <form action="{{ route('ratings.helpful', $review->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="vote" value="no">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:12px;">👎 No ({{ $review->helpful_no ?? 0 }})</button>
                                </form>
                                @if(session('customer_id') && session('customer_id') == $review->customer_id)
                                    <form action="{{ route('ratings.destroy', [$product->id, $review->id]) }}" method="POST" class="d-inline ms-auto" onsubmit="return confirm('Delete your review?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:11px;">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5" style="font-size:13px;">
                            <div style="font-size:32px; margin-bottom:8px;">📝</div>
                            No reviews yet. Be the first to review this product!
                        </div>
                    @endforelse
                </div>
                @if($ratings->hasPages())
                    <div class="card-footer bg-white border-top py-2">{{ $ratings->links() }}</div>
                @endif
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border rounded-1 sticky-top" style="top:16px;">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="border rounded-1 overflow-hidden flex-shrink-0" style="width:56px; height:56px; background:#f8f8f8;">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="font-size:24px;">📦</div>
                            @endif
                        </div>
                        <div>
                            <div class="fw-semibold" style="font-size:13px; line-height:1.4;">{{ $product->name }}</div>
                            <div class="text-muted" style="font-size:12px;">Share your experience</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('ratings.store', $product->id) }}" method="POST" id="reviewForm" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted fw-semibold" style="font-size:11px; letter-spacing:.07em; text-transform:uppercase;">
                                Your Rating <span class="text-danger">*</span>
                            </label>
                            <div class="fk-star-picker" id="starPicker">
                                <input type="radio" name="rating" id="star5" value="5" {{ old('rating') == 5 ? 'checked' : '' }} required>
                                <label for="star5" title="Excellent">★</label>
                                <input type="radio" name="rating" id="star4" value="4" {{ old('rating') == 4 ? 'checked' : '' }}>
                                <label for="star4" title="Good">★</label>
                                <input type="radio" name="rating" id="star3" value="3" {{ old('rating') == 3 ? 'checked' : '' }}>
                                <label for="star3" title="Average">★</label>
                                <input type="radio" name="rating" id="star2" value="2" {{ old('rating') == 2 ? 'checked' : '' }}>
                                <label for="star2" title="Poor">★</label>
                                <input type="radio" name="rating" id="star1" value="1" {{ old('rating') == 1 ? 'checked' : '' }}>
                                <label for="star1" title="Terrible">★</label>
                            </div>
                            <div id="ratingDesc" class="fw-semibold mt-1" style="font-size:13px; min-height:18px;">
                                @php $descs = [5=>'Excellent',4=>'Good',3=>'Average',2=>'Poor',1=>'Terrible']; @endphp
                                {{ $descs[old('rating')] ?? '' }}
                            </div>
                            @error('rating')
                                <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted fw-semibold" style="font-size:11px; letter-spacing:.07em; text-transform:uppercase;">Review Title</label>
                            <input type="text" name="title" class="form-control form-control-sm @error('title') is-invalid @enderror"
                                   placeholder="Summarise your review in one line" value="{{ old('title') }}" maxlength="100" style="border-radius:2px;">
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted fw-semibold" style="font-size:11px; letter-spacing:.07em; text-transform:uppercase;">Your Review</label>
                            <textarea name="review" id="reviewBody" class="form-control form-control-sm @error('review') is-invalid @enderror"
                                      placeholder="What did you like or dislike about this product?"
                                      rows="4" maxlength="2000" style="border-radius:2px; resize:vertical;">{{ old('review') }}</textarea>
                            <div class="text-end text-muted mt-1" style="font-size:11px;"><span id="charCount">0</span> / 2000</div>
                            @error('review') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm px-4" style="border-radius:2px; font-size:14px;">Cancel</a>
                            <button type="submit" class="btn btn-sm flex-grow-1 text-white fw-semibold" id="submitBtn"
                                    style="background:#fb641b; border:none; border-radius:2px; font-size:14px;">Submit Review</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Related Products -->
<div id="recent-products-container" class="bg-white border-top border-bottom py-3">
    @if(isset($related_products) && $related_products->count() > 0)
        <section class="container-fluid px-lg-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold m-0 text-dark" style="letter-spacing: -0.5px;">Similar Products</h5>
                <a href="{{ route('category', ['id' => $product->category_id, 'name' => Str::slug($product->category->slug)] ) }}" class="btn btn-primary btn-sm rounded-1 shadow-sm px-3">View All</a>
            </div>

            <div class="row g-2"> 
                @foreach($related_products as $product)
                    @if($product->firstVariant && $product->firstVariant->margin_price !== null)
                        <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                            <div class="card h-100 border-0 rounded-0 p-2 position-relative flipkart-card">
                                <div class="d-flex align-items-center justify-content-center mb-2" style="height: 180px;">
                                    <img src="{{ asset('storage/'.$product->image) }}" 
                                         class="img-fluid h-100 w-100" 
                                         style="object-fit: contain;"
                                         alt="{{ $product->name }}">
                                </div>

                                <div class="card-body p-1 text-start">
                                    <p class="mb-1 text-dark small fw-normal text-truncate" title="{{ $product->name }}">
                                        {{ $product->name }}
                                    </p>
                                    
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold text-dark fs-6">
                                            ₹{{ number_format($product->firstVariant->margin_price) }}
                                        </span>
                                        <!-- <span class="text-muted small text-decoration-line-through">
                                            ₹{{ number_format($product->firstVariant->price + 500) }}
                                        </span>
                                        <span class="text-success small fw-bold">
                                            25% off
                                        </span> -->
                                    </div> 
                                    
                                    <!--
                                    <div class="mt-1 d-flex align-items-center gap-1">
                                        <span class="badge bg-success py-1 px-2 rounded-1" style="font-size: 10px;">
                                            4.2 ★
                                        </span>
                                        <span class="text-muted" style="font-size: 11px;">(1,245)</span>
                                    </div> -->
                                </div> 
                                
                                <a href="{{ route('view_product', $product->id) }}" class="stretched-link"></a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </section>
    @endif
</div>


@endsection

<style>
    .active-variant { background-color: #212529 !important; color: white !important; }
    .thumb-image:hover { border-color: #0d6efd; }
    .fk-star-picker { display: inline-flex; flex-direction: row-reverse; gap: 6px; }
    .fk-star-picker input[type="radio"] { display: none; }
    .fk-star-picker label { font-size: 32px; color: #dee2e6; cursor: pointer; line-height: 1; transition: color .1s, transform .1s; user-select: none; }
    .fk-star-picker input:checked ~ label,
    .fk-star-picker label:hover,
    .fk-star-picker label:hover ~ label { color: #ff9f00; }
    .fk-star-picker label:hover { transform: scale(1.15); }
    form { margin-block-end: 0px; }
</style>

@push('scripts')
<script>
    window.Laravel = {
        product_id:    "{{ $product->id }}",
        customer_id:   "{{ session('customer_id') }}",
        route:         "{{ route('product.saveRecent') }}",
        csrf:          "{{ csrf_token() }}",
        hasSale:       {{ $hasSale ? 'true' : 'false' }},
        saleType:      "{{ $hasSale ? $sale->type : '' }}",
        saleDiscount:  {{ $hasSale ? $sale->discount : 0 }},
    };
</script>
<script src="{{ asset('js/frontendproduct.js') }}"></script>
@endpush