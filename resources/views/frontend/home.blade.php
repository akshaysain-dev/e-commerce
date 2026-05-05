@extends('layouts.frontend')

@section('title', 'My Shop - Best Deals Online')

@section('styles')
<style>
    /* CARD */
.fk-card {
    background: #fff;
    border-radius: 8px;
    border: 1px solid #eee;
    transition: 0.25s;
    height: 100%;
}

.fk-card:hover {
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    transform: translateY(-3px);
}

/* IMAGE FIX */
.fk-img {
    height: 160px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
}

.fk-img img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
}

/* NAME */
.fk-name {
    font-size: 13px;
    height: 36px;
    overflow: hidden;
    font-weight: 500;
    margin-bottom: 4px;
}

/* RATING */
.fk-rating-row {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 4px;
}

.fk-rating {
    background: #388e3c;
    color: #fff;
    font-size: 11px;
    padding: 2px 6px;
    border-radius: 4px;
}

.fk-count {
    font-size: 11px;
    color: #777;
}

/* PRICE */
.fk-price {
    font-weight: 600;
    color: #212121;
    font-size: 14px;
}
</style>
@endsection

@section('content')

{{-- ===== CATEGORY ROW ===== --}}
<div class="bg-white border-bottom shadow-sm sticky-top" style="z-index:100;">
    <div class="container py-2">
        <div class="d-flex align-items-center gap-1 overflow-auto" id="categoryScroll"
             style="scrollbar-width:none; -ms-overflow-style:none;">

            {{-- For You (All Products) --}}
            <a href="javascript:void(0)"
               class="category-tab d-flex flex-column align-items-center text-decoration-none px-2 py-1 rounded-2 flex-shrink-0 text-primary border-bottom border-2 border-primary fw-semibold"
               data-category-id="all"
               style="min-width:68px;">
                <div class="rounded-circle d-flex align-items-center justify-content-center mb-1"
                     style="width:44px;height:44px;background:#f0f4ff;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M3 9.5L12 3l9 6.5V20a1 1 0 01-1 1H4a1 1 0 01-1-1V9.5z"/>
                        <path d="M9 21V12h6v9"/>
                    </svg>
                </div>
                <span style="font-size:11px;">For You</span>
            </a>

            @isset($categories)
                @forelse($categories as $cat)
                <a href="javascript:void(0)"
                   class="category-tab d-flex flex-column align-items-center text-decoration-none px-2 py-1 rounded-2 flex-shrink-0 text-secondary"
                   data-category-id="{{ $cat->id }}"
                   style="min-width:68px;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mb-1"
                         style="width:44px;height:44px;background:#f0f4ff;">
                        @if($cat->icon)
                            <img src="{{ asset('storage/'.$cat->icon) }}" alt="{{ $cat->name }}" width="24" height="24" style="object-fit:contain;">
                        @else
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="3" width="7" height="7" rx="1"/>
                                <rect x="14" y="3" width="7" height="7" rx="1"/>
                                <rect x="3" y="14" width="7" height="7" rx="1"/>
                                <rect x="14" y="14" width="7" height="7" rx="1"/>
                            </svg>
                        @endif
                    </div>
                    <span style="font-size:11px;">{{ \Illuminate\Support\Str::limit($cat->name, 8) }}</span>
                </a>
                @empty
                <span class="text-muted small px-3 py-2">No categories found</span>
                @endforelse
            @else
                @php
                    $staticCats = [
                        ['name'=>'Fashion','emoji'=>'👗'],
                        ['name'=>'Mobiles','emoji'=>'📱'],
                        ['name'=>'Electronics','emoji'=>'💻'],
                        ['name'=>'Beauty','emoji'=>'💄'],
                        ['name'=>'Home','emoji'=>'🏠'],
                        ['name'=>'Appliances','emoji'=>'🍳'],
                        ['name'=>'Toys','emoji'=>'🧸'],
                        ['name'=>'Sports','emoji'=>'⚽'],
                        ['name'=>'Books','emoji'=>'📚'],
                        ['name'=>'Furniture','emoji'=>'🛋️'],
                    ];
                @endphp
                @foreach($staticCats as $sc)
                <a href="#" class="d-flex flex-column align-items-center text-decoration-none px-2 py-1 rounded-2 flex-shrink-0 text-secondary" style="min-width:68px;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mb-1"
                         style="width:44px;height:44px;background:#f0f4ff;font-size:20px;">
                        {{ $sc['emoji'] }}
                    </div>
                    <span style="font-size:11px;">{{ $sc['name'] }}</span>
                </a>
                @endforeach
            @endisset

        </div>
    </div>
</div>

{{-- ===== BANNER SECTION ===== --}}
<div class="bg-light py-3">
    <div class="container">
        <div class="row g-2">

            {{-- Main Banner --}}
            <div class="col-lg-9 col-md-8">
                @isset($banners)
                    @if($banners->count() > 0)
                    <div id="mainBannerCarousel" class="carousel slide rounded-3 overflow-hidden shadow-sm"
                         data-bs-ride="carousel" data-bs-interval="4000">

                        <div class="carousel-indicators">
                            @foreach($banners->take(5) as $i => $b)
                            <button type="button" data-bs-target="#mainBannerCarousel"
                                    data-bs-slide-to="{{ $i }}"
                                    class="{{ $i === 0 ? 'active' : '' }}"></button>
                            @endforeach
                        </div>

                        <div class="carousel-inner">
                            @foreach($banners->take(5) as $i => $banner)
                            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}" style="position: relative;">
                                <a href="{{ $banner->link ?? '#' }}">
                                    <img src="{{ asset('storage/'.$banner->image) }}"
                                        class="d-block w-100"
                                        alt="{{ $banner->title ?? 'Banner' }}"
                                        style="height:280px;object-fit:cover;">
                                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
                                                background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 60%);
                                                pointer-events: none; z-index: 1;">
                                    </div>
                                </a>
                                @if($banner->title)
                                <div class="carousel-caption d-none d-md-block text-start ps-3 pb-3"
                                    style="left:10px; bottom:0; right:auto; z-index: 2;">
                                    <h5 class="fw-bold mb-0 text-white">{{ $banner->title }}</h5>
                                    @if($banner->subtitle)
                                    <p class="mb-0 small text-white-50">{{ $banner->subtitle }}</p>
                                    @endif
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>

                        @if($banners->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#mainBannerCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#mainBannerCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                        @endif
                    </div>
                    @else
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-center border border-secondary-subtle"
                         style="height:280px;background:linear-gradient(135deg,#e8f0fe,#fce4ec);border-style:dashed!important;">
                        <div class="p-4">
                            <div class="fs-1 mb-2">🎉</div>
                            <h4 class="fw-bold">Big Deals Await!</h4>
                            <p class="text-muted small">Admin panel se banners upload karein</p>
                            <a href="{{ route('all_products') }}" class="btn btn-primary btn-sm px-4">Shop Now</a>
                        </div>
                    </div>
                    @endif
                @else
                <div class="rounded-3 d-flex align-items-center justify-content-center text-center border border-secondary-subtle"
                     style="height:280px;background:linear-gradient(135deg,#e8f0fe,#fce4ec);border-style:dashed!important;">
                    <div class="p-4">
                        <div class="fs-1 mb-2">🛍️</div>
                        <h4 class="fw-bold">Discover Best Deals</h4>
                        <p class="text-muted small">Controller se <code>$banners</code> pass karein</p>
                        <a href="{{ route('all_products') }}" class="btn btn-primary btn-sm px-4">Explore Now</a>
                    </div>
                </div>
                @endisset
            </div>

            {{-- Side Banners --}}
            <div class="col-lg-3 col-md-4 d-flex flex-column gap-2">
                @isset($sideBanners)
                    @if($sideBanners->count() >= 1)
                    <a href="{{ $sideBanners[0]->link ?? '#' }}" class="rounded-3 overflow-hidden shadow-sm d-block flex-fill">
                        <img src="{{ asset('storage/'.$sideBanners[0]->image) }}"
                             class="w-100"
                             alt="{{ $sideBanners[0]->title ?? 'Offer' }}"
                             style="height:133px;object-fit:cover;display:block;">
                    </a>
                    @else
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-center flex-fill border border-secondary-subtle"
                         style="height:133px;background:#fff8e1;border-style:dashed!important;">
                        <div>
                            <div class="fs-3">📦</div>
                            <p class="mb-0 small fw-semibold text-muted">Special Offer</p>
                            <small class="text-muted" style="font-size:10px;">Add side banner in admin</small>
                        </div>
                    </div>
                    @endif

                    @if($sideBanners->count() >= 2)
                    <a href="{{ $sideBanners[1]->link ?? '#' }}" class="rounded-3 overflow-hidden shadow-sm d-block flex-fill">
                        <img src="{{ asset('storage/'.$sideBanners[1]->image) }}"
                             class="w-100"
                             alt="{{ $sideBanners[1]->title ?? 'Offer' }}"
                             style="height:133px;object-fit:cover;display:block;">
                    </a>
                    @else
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-center flex-fill border border-secondary-subtle"
                         style="height:133px;background:#f3e5f5;border-style:dashed!important;">
                        <div>
                            <div class="fs-3">🚀</div>
                            <p class="mb-0 small fw-semibold text-muted">New Arrivals</p>
                            <small class="text-muted" style="font-size:10px;">Add side banner in admin</small>
                        </div>
                    </div>
                    @endif
                @else
                <div class="rounded-3 d-flex align-items-center justify-content-center text-center flex-fill border border-secondary-subtle"
                     style="height:133px;background:#fff8e1;border-style:dashed!important;">
                    <div><div class="fs-3">📦</div><p class="mb-0 small fw-semibold text-muted">Special Offer</p><small class="text-muted" style="font-size:10px;">Add side banner in admin</small></div>
                </div>
                <div class="rounded-3 d-flex align-items-center justify-content-center text-center flex-fill border border-secondary-subtle"
                     style="height:133px;background:#f3e5f5;border-style:dashed!important;">
                    <div><div class="fs-3">🚀</div><p class="mb-0 small fw-semibold text-muted">New Arrivals</p><small class="text-muted" style="font-size:10px;">Add side banner in admin</small></div>
                </div>
                @endisset
            </div>
        </div>
    </div>
</div>

<!-- Home Page Blade File -->
<div id="recent-products-container">
    @if(isset($recentProducts) && $recentProducts->count() > 0)
        <section class="container my-5">

            <h5 class="fw-bold mb-3">Recently Viewed</h5>

            <div class="row gx-3 gy-4">

                @foreach($recentProducts as $product)

                    @if($product->firstVariant && $product->firstVariant->price !== null)

                        @php
                            $avgRating = round($product->ratings_avg_rating ?? 0, 1);
                            $totalReviews = $product->ratings_count ?? 0;
                        @endphp

                        <div class="col-xl-2 col-lg-3 col-md-4 col-6">

                            <a href="{{ route('view_product', $product->id) }}" class="text-decoration-none text-dark">

                                <div class="fk-card">

                                    {{-- IMAGE --}}
                                    <div class="fk-img">
                                        <img src="{{ asset('storage/'.$product->image) }}">
                                    </div>

                                    {{-- DETAILS --}}
                                    <div class="p-2">

                                        <div class="fk-name">
                                            {{ Str::limit($product->name, 45) }}
                                        </div>

                                        {{-- ⭐ RATING --}}
                                        <div class="fk-rating-row">
                                            <span class="fk-rating">
                                                {{ $avgRating }} ★
                                            </span>
                                            <span class="fk-count">
                                                ({{ $totalReviews }})
                                            </span>
                                        </div>

                                        {{-- PRICE --}}
                                        <div class="fk-price">
                                            ₹{{ number_format($product->firstVariant->margin_price) }}
                                        </div>

                                    </div>

                                </div>

                            </a>

                        </div>

                    @endif

                @endforeach

            </div>

        </section>
    @endif
</div>


{{-- ===== PRODUCTS SECTION ===== --}}
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold fs-4 mb-0" id="sectionTitle">Latest Products</h2>
            <p class="text-muted small mb-0 mt-1" id="sectionSubtitle">Showing all products</p>
        </div>
        <a href="{{ route('all_products') }}" class="btn btn-outline-dark btn-sm">View All</a>
    </div>

    {{-- Product Grid (AJAX target) --}}
    <div id="productsContainer" class="row g-4">
        @isset($products)
            @forelse($products as $product)
            @if($product->firstVariant && $product->firstVariant->margin_price !== null)
            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden product-card">
                    <div class="position-relative overflow-hidden">
                        @if($product->image)
                           <a href="{{ route('view_product', $product->id) }}">
                                <img src="{{ asset('storage/'.$product->image) }}"
                                 class="card-img-top product-thumb"
                                 alt="{{ $product->name }}"
                                 style="height:220px;object-fit:cover;">
                            </a>
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center bg-light"
                                 style="height:220px;">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <path d="M21 15l-5-5L5 21"/>
                                </svg>
                                <p class="text-muted small mt-2 mb-0">No Image</p>
                            </div>
                        @endif
                        <span class="badge bg-primary position-absolute top-0 start-0 m-2">
                            {{ $product->category->name ?? 'General' }}
                        </span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-semibold mb-1">
                            {{ $product->name ?? 'Product Name Unavailable' }}
                        </h6>
                        <p class="text-muted small mb-2">
                            {{ \Illuminate\Support\Str::limit($product->description ?? 'No description available.', 70) }}
                        </p>
                        <div class="mt-auto">
                            @php
                                $avgRating = $product->ratings_avg_rating ?? 0;
                                $avgRating = round($avgRating, 1);
                                $fullStars = floor($avgRating);
                                $halfStar = ($avgRating - $fullStars) >= 0.5;
                                $totalReviews = $product->ratings_count ?? 0;
                            @endphp

                            <div class="d-flex align-items-center mb-1">

                                {{-- ⭐ Stars --}}
                                <div class="text-warning me-2" style="font-size:14px;">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if($i <= $fullStars)
                                            <i class="fas fa-star"></i> {{-- full --}}
                                        @elseif($halfStar && $i == $fullStars + 1)
                                            <i class="fas fa-star-half-alt"></i> {{-- half --}}
                                        @else
                                            <i class="far fa-star"></i> {{-- empty --}}
                                        @endif
                                    @endfor
                                </div>

                                {{-- ⭐ Rating number --}}
                                <small class="text-muted">
                                    {{ $avgRating }} ({{ $totalReviews }})
                                </small>

                            </div>
                            <p class="fw-bold text-success mb-1">
                                ₹{{ number_format($product->firstVariant->margin_price) }}
                            </p>
                            {{-- @if(isset($product->firstVariant->stock) && $product->firstVariant->stock > 0)
                                <span class="badge bg-success-subtle text-success border border-success-subtle mb-2">
                                    In Stock ({{ $product->firstVariant->stock }})
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle mb-2">
                                    Out of Stock
                                </span>
                            @endif --}}
                         <div class="d-flex gap-2 mt-1">
                                <a href="{{ route('view_product', $product->id) }}"
                                   class="btn btn-outline-primary btn-sm flex-fill">View</a>
                                <form action="{{ route('wishlist.toggle') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    
                                    @if($product->isWishlisted())
                                        <button type="submit" class="btn btn-danger shadow-sm">
                                            <i class="fas fa-heart"></i> Remove Wishlist
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-outline-primary shadow-sm">
                                            <i class="far fa-heart"></i> Add to Wishlist
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @empty
            <div class="col-12">
                <div class="text-center py-5 bg-light rounded-3 border border-secondary-subtle">
                    <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="#adb5bd" stroke-width="1.2" class="mb-3">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 01-8 0"/>
                    </svg>
                    <h5 class="text-muted fw-semibold">Sorry, Don't Have any product Now</h5>
                    <p class="text-muted small mb-3">We Are Working on.</p>
                    <a href="{{ route('home') }}" class="btn btn-dark btn-sm px-4">Back to Home</a>
                </div>
            </div>
            @endforelse
        @else
        <div class="col-12">
            <div class="text-center py-5 bg-light rounded-3 border">
                <h5 class="text-muted">Products load nahi ho sake</h5>
                <p class="text-muted small">Controller se <code>$products</code> variable pass karein</p>
            </div>
        </div>
        @endisset
    </div>
</div>

@endsection

@section('styles')
<style>
    #categoryScroll::-webkit-scrollbar { display: none; }

    .category-tab { transition: all .2s ease; cursor: pointer; }
    .category-tab.active {
        color: #0d6efd !important;
        border-bottom: 2px solid #0d6efd !important;
        font-weight: 600 !important;
    }
    .category-tab.active .cat-icon-wrap {
        background: #dce8ff !important;
    }
    .category-tab:hover:not(.active) { color: #0d6efd !important; opacity: .85; }

    .product-card { transition: box-shadow .2s, transform .2s; }
    .product-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,.12) !important; transform: translateY(-3px); }
    .product-card:hover .product-thumb { transform: scale(1.04); }
    .product-thumb { transition: transform .3s; }

    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.4s infinite;
        border-radius: 8px;
    }
    @keyframes shimmer {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .product-card-wrapper {
        animation: fadeInUp .35s ease both;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection

@section('scripts')
<script>
    window.Laravel = {
        ajaxUrl: "{{ route('products.by.category') }}",
        csrfToken: "{{ csrf_token() }}"
    };
</script>
<script src="{{ asset('js/homepage.js') }}"></script>
@endsection