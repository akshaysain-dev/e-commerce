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

<style>
.fk-filter.active {
    background: #212121 !important;
    color: #fff !important;
    border-color: #212121 !important;
}
.fk-review-img-wrap {
    transition: transform .15s, box-shadow .15s;
}
.fk-review-img-wrap:hover {
    transform: scale(1.05);
    box-shadow: 0 2px 10px rgba(0,0,0,.18);
}
.fk-reply-input::placeholder { color: #9e9e9e; }
.fk-lb-thumb {
    width: 52px; height: 52px; object-fit: cover;
    border-radius: 4px; border: 2px solid transparent;
    cursor: pointer; opacity: .7;
    transition: opacity .15s, border-color .15s;
}
.fk-lb-thumb.active, .fk-lb-thumb:hover { opacity: 1; border-color: #fff; }
@keyframes fkSlideIn {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0); }
}
.fk-reply-new { animation: fkSlideIn .25s ease; }
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

<div class="container mt-5 mb-5">
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
 
        {{-- ===== LEFT COL: Ratings Summary ===== --}}
        <div class="col-lg-5">
            <div class="card border rounded-1">
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
        </div>
        {{-- ===== END LEFT COL ===== --}}
 
        {{-- ===== RIGHT COL: Customer Reviews ===== --}}
        <div class="col-lg-7">
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
                        @php
                            $bg = $review->rating >= 4 ? 'success' : ($review->rating == 3 ? 'warning' : 'danger');
                            $images = [];
                            if (!empty($review->images)) {
                                $decoded = is_array($review->images) ? $review->images : json_decode($review->images, true);
                                $images = is_array($decoded) ? $decoded : [];
                            }
                        @endphp
 
                        <div class="fk-review-item p-3 border-bottom" data-star="{{ $review->rating }}">
 
                            {{-- Badge --}}
                            <span class="badge bg-{{ $bg }} mb-2" style="font-size:11px;">★ {{ $review->rating }}</span>
 
                            {{-- Title --}}
                            @if($review->title)
                                <div class="fw-semibold mb-1" style="font-size:14px;">{{ $review->title }}</div>
                            @endif
 
                            {{-- Review Text --}}
                            @if($review->review)
                                <p class="text-muted mb-2" style="font-size:13px; line-height:1.6;">{{ $review->review }}</p>
                            @endif
 
                            {{-- ===== IMAGES SECTION ===== --}}
                            @if(count($images) > 0)
                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    @foreach($images as $idx => $imgPath)
                                        @if($idx < 4)
                                            <div class="fk-review-img-wrap"
                                                 style="width:70px; height:70px; border-radius:6px; overflow:hidden; border:1px solid #e0e0e0; cursor:pointer; flex-shrink:0;"
                                                 onclick="fkOpenLightbox({{ $review->id }}, {{ $idx }})">
                                                <img src="{{ asset('storage/' . $imgPath) }}"
                                                     alt="Review image {{ $idx + 1 }}"
                                                     style="width:100%; height:100%; object-fit:cover; display:block;"
                                                     onerror="this.parentElement.style.display='none'">
                                            </div>
                                        @endif
                                    @endforeach
 
                                    @if(count($images) > 4)
                                        <div class="fk-review-img-wrap d-flex align-items-center justify-content-center"
                                             style="width:70px; height:70px; border-radius:6px; background:#f5f5f5; border:1px solid #e0e0e0; cursor:pointer; flex-shrink:0; font-size:12px; color:#555; font-weight:600;"
                                             onclick="fkOpenLightbox({{ $review->id }}, 4)">
                                            +{{ count($images) - 4 }} more
                                        </div>
                                    @endif
                                </div>
                                <div id="fk-imgs-{{ $review->id }}" style="display:none;"
                                     data-images="{{ json_encode(array_map(fn($p) => asset('storage/' . $p), $images)) }}"></div>
                            @endif
                            {{-- ===== END IMAGES ===== --}}
 
                            {{-- Reviewer Meta --}}
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                                <span style="font-size:12px;"><strong>{{ $review->customer->name ?? 'Anonymous' }}</strong></span>
                                @if($review->is_verified_purchase)
                                    <span class="text-success" style="font-size:11px; font-weight:500;">✓ Certified Buyer</span>
                                @endif
                                <span class="text-muted" style="font-size:11px;">· {{ $review->created_at->diffForHumans() }}</span>
                            </div>
 
                            {{-- Actions --}}
                            <div class="d-flex align-items-center gap-2 flex-wrap">
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
 
                                <button class="btn btn-sm btn-outline-secondary py-0 px-2 fk-reply-toggle"
                                        data-review="{{ $review->id }}"
                                        style="font-size:12px;">
                                    💬 Reply
                                    @if($review->replies && $review->replies->count() > 0)
                                        <span class="text-muted">({{ $review->replies->count() }})</span>
                                    @endif
                                </button>
 
                                @if(session('customer_id') && session('customer_id') == $review->customer_id)
                                    <form action="{{ route('ratings.destroy', [$product->id, $review->id]) }}" method="POST"
                                          class="d-inline ms-auto" onsubmit="return confirm('Delete your review?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:11px;">Delete</button>
                                    </form>
                                @endif
                            </div>
 
                            {{-- ===== REPLY SECTION ===== --}}
                            <div class="fk-replies-section mt-2" id="fk-replies-{{ $review->id }}" style="display:none;">
 
                                @if($review->replies && $review->replies->count() > 0)
                                    <div class="fk-replies-list mb-2"
                                         style="border-left:2px solid #e9ecef; padding-left:12px; margin-left:4px;">
                                        @foreach($review->replies as $reply)
                                            <div class="d-flex gap-2 mb-2">
                                                <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle"
                                                     style="width:28px; height:28px; background:#e9ecef; font-size:11px; font-weight:600; color:#555; text-transform:uppercase;">
                                                    {{ substr($reply->author_name ?? 'A', 0, 1) }}
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-inline-block px-3 py-2 rounded-3"
                                                         style="background:#f1f3f4; font-size:13px; line-height:1.5; max-width:100%;">
                                                        <div style="font-size:11px; font-weight:600; color:#333; margin-bottom:2px;">
                                                            {{ $reply->author_name ?? 'Anonymous' }}
                                                            @if($reply->is_seller)
                                                                <span class="badge bg-primary ms-1" style="font-size:9px; padding:2px 5px;">Seller</span>
                                                            @endif
                                                        </div>
                                                        <div style="color:#444;">{{ $reply->body }}</div>
                                                    </div>
                                                    <div class="text-muted mt-1" style="font-size:10px; padding-left:2px;">
                                                        {{ $reply->created_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
 
                                <div class="d-flex gap-2 align-items-center" style="padding-left:4px;">
                                    <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle"
                                         style="width:28px; height:28px; background:#dee2e6; font-size:11px; font-weight:600; color:#555;">
                                        {{ strtoupper(substr(session('customer_name', 'Y'), 0, 1)) ?: 'Y' }}
                                    </div>
                                    <div class="flex-grow-1 d-flex gap-1 align-items-center"
                                         style="background:#f1f3f4; border-radius:20px; padding:5px 10px 5px 14px;">
                                        <input type="text"
                                               class="fk-reply-input flex-grow-1 border-0 bg-transparent"
                                               placeholder="Write a reply…"
                                               data-review="{{ $review->id }}"
                                               style="outline:none; font-size:13px; color:#333;"
                                               maxlength="500">
                                        <button class="fk-reply-send"
                                                data-review="{{ $review->id }}"
                                                style="background:none; border:none; color:#1976d2; font-size:18px; line-height:1; cursor:pointer; padding:0 4px;"
                                                title="Send">➤</button>
                                    </div>
                                </div>
                                <div id="fk-reply-err-{{ $review->id }}"
                                     class="text-danger"
                                     style="font-size:11px; display:none; padding-left:40px; margin-top:4px;"></div>
                            </div>
                            {{-- ===== END REPLY SECTION ===== --}}
 
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
        {{-- ===== END RIGHT COL ===== --}}
 
    </div>
</div>
 
{{-- ===== LIGHTBOX ===== --}}
<div id="fkLightbox"
     style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.88);
            align-items:center; justify-content:center; flex-direction:column;">
    <button onclick="fkCloseLightbox()"
            style="position:absolute; top:16px; right:20px; background:none; border:none;
                   color:#fff; font-size:28px; cursor:pointer; line-height:1;">✕</button>
    <button id="fkLbPrev" onclick="fkLbNav(-1)"
            style="position:absolute; left:16px; top:50%; transform:translateY(-50%);
                   background:rgba(255,255,255,.15); border:none; color:#fff; font-size:24px;
                   width:44px; height:44px; border-radius:50%; cursor:pointer;">‹</button>
    <button id="fkLbNext" onclick="fkLbNav(1)"
            style="position:absolute; right:16px; top:50%; transform:translateY(-50%);
                   background:rgba(255,255,255,.15); border:none; color:#fff; font-size:24px;
                   width:44px; height:44px; border-radius:50%; cursor:pointer;">›</button>
    <img id="fkLbImg" src="" alt="Review image"
         style="max-width:88vw; max-height:78vh; object-fit:contain; border-radius:6px;
                box-shadow:0 4px 32px rgba(0,0,0,.5);">
    <div id="fkLbThumbs" class="d-flex gap-2 mt-3"
         style="flex-wrap:wrap; justify-content:center; max-width:90vw;"></div>
    <div id="fkLbCounter" style="color:#ffffffaa; font-size:12px; margin-top:8px;"></div>
</div>
{{-- ===== END LIGHTBOX ===== --}}


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

<script>
// ============================================================
// STAR FILTER
// ============================================================
document.querySelectorAll('.fk-filter').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.fk-filter').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const filter = this.dataset.filter;
        document.querySelectorAll('.fk-review-item').forEach(item => {
            item.style.display = (filter === 'all' || item.dataset.star === filter) ? '' : 'none';
        });
    });
});

// ============================================================
// LIGHTBOX
// ============================================================
let _lbImgs = [], _lbIdx = 0;

function fkOpenLightbox(reviewId, startIdx) {
    const el = document.getElementById('fk-imgs-' + reviewId);
    if (!el) return;
    _lbImgs = JSON.parse(el.dataset.images || '[]');
    _lbIdx  = startIdx;
    fkLbRender();
    const lb = document.getElementById('fkLightbox');
    lb.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function fkCloseLightbox() {
    document.getElementById('fkLightbox').style.display = 'none';
    document.body.style.overflow = '';
}

function fkLbNav(dir) {
    _lbIdx = (_lbIdx + dir + _lbImgs.length) % _lbImgs.length;
    fkLbRender();
}

function fkLbRender() {
    document.getElementById('fkLbImg').src = _lbImgs[_lbIdx];
    document.getElementById('fkLbCounter').textContent = (_lbIdx + 1) + ' / ' + _lbImgs.length;

    // Prev/Next visibility
    document.getElementById('fkLbPrev').style.display = _lbImgs.length > 1 ? '' : 'none';
    document.getElementById('fkLbNext').style.display = _lbImgs.length > 1 ? '' : 'none';

    // Thumbnails
    const wrap = document.getElementById('fkLbThumbs');
    wrap.innerHTML = '';
    _lbImgs.forEach((src, i) => {
        const img = document.createElement('img');
        img.src = src;
        img.className = 'fk-lb-thumb' + (i === _lbIdx ? ' active' : '');
        img.onclick = () => { _lbIdx = i; fkLbRender(); };
        wrap.appendChild(img);
    });
}

// Close lightbox on backdrop click
document.getElementById('fkLightbox').addEventListener('click', function(e) {
    if (e.target === this) fkCloseLightbox();
});

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    const lb = document.getElementById('fkLightbox');
    if (lb.style.display === 'flex') {
        if (e.key === 'ArrowRight') fkLbNav(1);
        if (e.key === 'ArrowLeft')  fkLbNav(-1);
        if (e.key === 'Escape')     fkCloseLightbox();
    }
});

// ============================================================
// REPLY SYSTEM
// ============================================================

// Toggle reply box
document.querySelectorAll('.fk-reply-toggle').forEach(btn => {
    btn.addEventListener('click', function () {
        const reviewId = this.dataset.review;
        const section  = document.getElementById('fk-replies-' + reviewId);
        if (!section) return;
        const isOpen = section.style.display !== 'none';
        section.style.display = isOpen ? 'none' : 'block';
        if (!isOpen) {
            const inp = section.querySelector('.fk-reply-input');
            if (inp) inp.focus();
        }
    });
});

// Send on Enter key
document.querySelectorAll('.fk-reply-input').forEach(inp => {
    inp.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            fkSendReply(this.dataset.review);
        }
    });
});

// Send on button click
document.querySelectorAll('.fk-reply-send').forEach(btn => {
    btn.addEventListener('click', function() {
        fkSendReply(this.dataset.review);
    });
});

function fkSendReply(reviewId) {
    const section  = document.getElementById('fk-replies-' + reviewId);
    const input    = section ? section.querySelector('.fk-reply-input') : null;
    const errBox   = document.getElementById('fk-reply-err-' + reviewId);
    if (!input) return;

    const body = input.value.trim();
    if (!body) return;

    // Disable while sending
    input.disabled = true;
    const sendBtn = section.querySelector('.fk-reply-send');
    if (sendBtn) sendBtn.disabled = true;
    if (errBox) errBox.style.display = 'none';

    fetch('{{ route("ratings.reply.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ rating_id: reviewId, body: body })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Append new reply bubble
            const list = section.querySelector('.fk-replies-list') || fkCreateReplyList(section);
            const bubble = fkBuildBubble(data.reply);
            list.appendChild(bubble);
            input.value = '';

            // Update reply count badge in toggle button
            const toggleBtn = document.querySelector('.fk-reply-toggle[data-review="' + reviewId + '"]');
            if (toggleBtn) {
                let countSpan = toggleBtn.querySelector('span');
                const currentCount = parseInt((countSpan ? countSpan.textContent.replace(/\D/g,'') : '0')) || 0;
                if (!countSpan) {
                    countSpan = document.createElement('span');
                    countSpan.className = 'text-muted';
                    toggleBtn.appendChild(countSpan);
                }
                countSpan.textContent = '(' + (currentCount + 1) + ')';
            }
        } else {
            if (errBox) { errBox.textContent = data.message || 'Something went wrong.'; errBox.style.display = 'block'; }
        }
    })
    .catch(() => {
        if (errBox) { errBox.textContent = 'Could not send reply. Please try again.'; errBox.style.display = 'block'; }
    })
    .finally(() => {
        input.disabled = false;
        if (sendBtn) sendBtn.disabled = false;
        input.focus();
    });
}

function fkCreateReplyList(section) {
    const list = document.createElement('div');
    list.className = 'fk-replies-list mb-2';
    list.style.cssText = 'border-left:2px solid #e9ecef; padding-left:12px; margin-left:4px;';
    section.insertBefore(list, section.querySelector('.fk-reply-input-wrap'));
    return list;
}

function fkBuildBubble(reply) {
    const wrap = document.createElement('div');
    wrap.className = 'fk-reply-bubble d-flex gap-2 mb-2 fk-reply-new';

    const initial = (reply.author_name || 'Y').charAt(0).toUpperCase();
    const isSeller = reply.is_seller ? '<span class="badge bg-primary ms-1" style="font-size:9px;padding:2px 5px;">Seller</span>' : '';

    wrap.innerHTML = `
        <div class="fk-avatar flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle"
             style="width:28px;height:28px;background:#e9ecef;font-size:11px;font-weight:600;color:#555;text-transform:uppercase;">
            ${initial}
        </div>
        <div class="flex-grow-1">
            <div class="d-inline-block px-3 py-2 rounded-3"
                 style="background:#f1f3f4;font-size:13px;line-height:1.5;max-width:100%;">
                <div style="font-size:11px;font-weight:600;color:#333;margin-bottom:2px;">
                    ${escHtml(reply.author_name || 'You')}${isSeller}
                </div>
                <div style="color:#444;">${escHtml(reply.body)}</div>
            </div>
            <div class="text-muted mt-1" style="font-size:10px;padding-left:2px;">Just now</div>
        </div>`;
    return wrap;
}

function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>
@endpush