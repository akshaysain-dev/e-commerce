@extends('layouts.frontend')

@section('title', '🔥 Sale — Hot Deals Today')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --fire:      #ef4444;
        --fire-dark: #b91c1c;
        --fire-lt:   #fef2f2;
        --fire-glow: rgba(239,68,68,.18);
        --dark:      #0f172a;
        --muted:     #64748b;
        --border:    #e5e7eb;
        --card-r:    14px;
    }

    body { font-family: 'DM Sans', system-ui, sans-serif; background: #f8fafc; }

    /* ── Hero ─────────────────────────────────────── */
    .sale-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #7c0d0d 100%);
        padding: 56px 0 48px;
        position: relative;
        overflow: hidden;
    }
    .sale-hero::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(ellipse at 70% 50%, rgba(239,68,68,.25) 0%, transparent 65%);
    }
    .sale-hero::after {
        content: '🔥';
        position: absolute;
        right: 6%; top: 50%;
        transform: translateY(-50%);
        font-size: clamp(80px, 14vw, 160px);
        opacity: .12;
        pointer-events: none;
        animation: pulse-fire 3s ease-in-out infinite;
    }
    @keyframes pulse-fire {
        0%,100% { transform: translateY(-50%) scale(1); opacity: .12; }
        50%      { transform: translateY(-52%) scale(1.06); opacity: .18; }
    }

    .hero-eyebrow {
        display: inline-flex; align-items: center; gap: 7px;
        background: rgba(239,68,68,.2); border: 1px solid rgba(239,68,68,.4);
        color: #fca5a5; border-radius: 20px;
        padding: 5px 14px; font-size: .78rem; font-weight: 700;
        letter-spacing: .08em; text-transform: uppercase;
        margin-bottom: 16px;
    }
    .hero-eyebrow .dot {
        width: 6px; height: 6px; border-radius: 50%; background: #ef4444;
        animation: blink 1.2s ease-in-out infinite;
    }
    @keyframes blink { 0%,100% { opacity:1; } 50% { opacity:.2; } }

    .hero-title {
        font-family: 'Bebas Neue', sans-serif;
        font-size: clamp(52px, 9vw, 100px);
        line-height: .95;
        color: #fff;
        letter-spacing: .02em;
        margin: 0 0 6px;
    }
    .hero-title span { color: #ef4444; }

    .hero-sub {
        font-size: clamp(.9rem, 2vw, 1.1rem);
        color: rgba(255,255,255,.55);
        margin: 12px 0 28px;
        max-width: 460px;
    }

    .hero-stats {
        display: flex; gap: 28px; flex-wrap: wrap;
    }
    .hero-stat .n  { font-size: 1.6rem; font-weight: 800; color: #fff; line-height: 1; }
    .hero-stat .l  { font-size: .72rem; color: rgba(255,255,255,.45); text-transform: uppercase; letter-spacing: .08em; margin-top: 2px; }

    /* ── Sale filter tabs ─────────────────────────── */
    .sale-filter-bar {
        background: #fff;
        border-bottom: 1px solid var(--border);
        position: sticky; top: 0; z-index: 90;
        box-shadow: 0 1px 8px rgba(0,0,0,.06);
    }
    .sale-tabs {
        display: flex; gap: 0; overflow-x: auto;
        scrollbar-width: none; padding: 0 4px;
    }
    .sale-tabs::-webkit-scrollbar { display: none; }
    .sale-tab {
        display: flex; align-items: center; gap: 6px;
        padding: 14px 18px;
        font-size: .855rem; font-weight: 600; color: var(--muted);
        text-decoration: none; white-space: nowrap;
        border-bottom: 2px solid transparent;
        transition: all .15s; cursor: pointer; background: none; border-top: none;
        border-left: none; border-right: none;
    }
    .sale-tab:hover { color: var(--fire); }
    .sale-tab.active {
        color: var(--fire);
        border-bottom-color: var(--fire);
        font-weight: 700;
    }
    .sale-tab .tab-count {
        background: var(--fire-lt); color: var(--fire);
        border-radius: 10px; padding: 1px 7px; font-size: .72rem; font-weight: 700;
    }
    .sale-tab.active .tab-count { background: var(--fire); color: #fff; }

    /* ── Active sale cards (top) ──────────────────── */
    .sale-card {
        background: linear-gradient(135deg, var(--fire) 0%, var(--fire-dark) 100%);
        border-radius: 16px; padding: 22px 24px; color: #fff;
        position: relative; overflow: hidden;
        box-shadow: 0 8px 28px rgba(239,68,68,.3);
        transition: transform .2s, box-shadow .2s;
    }
    .sale-card:hover { transform: translateY(-3px); box-shadow: 0 14px 36px rgba(239,68,68,.38); }
    .sale-card::before {
        content: ''; position: absolute;
        top: -30px; right: -30px;
        width: 120px; height: 120px;
        background: rgba(255,255,255,.08); border-radius: 50%;
    }
    .sale-card::after {
        content: ''; position: absolute;
        bottom: -20px; right: 60px;
        width: 70px; height: 70px;
        background: rgba(255,255,255,.05); border-radius: 50%;
    }
    .sc-eyebrow { font-size: .68rem; text-transform: uppercase; letter-spacing: .1em; opacity: .6; margin-bottom: 6px; }
    .sc-name    { font-size: 1.1rem; font-weight: 800; line-height: 1.2; margin-bottom: 8px; }
    .sc-disc    { font-family: 'Bebas Neue', sans-serif; font-size: 2.4rem; line-height: 1; color: #fef2f2; }
    .sc-target  { font-size: .8rem; opacity: .7; margin-top: 5px; }
    .sc-timer   { font-size: .75rem; opacity: .55; margin-top: 8px; }
    .sc-badge   {
        position: absolute; top: 16px; right: 16px;
        background: rgba(255,255,255,.2); border: 1px solid rgba(255,255,255,.3);
        border-radius: 20px; padding: 3px 10px;
        font-size: .72rem; font-weight: 700;
    }

    /* ── Products grid ────────────────────────────── */
    .section-head {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 22px; flex-wrap: wrap; gap: 12px;
    }
    .section-title {
        font-size: 1.25rem; font-weight: 800; color: var(--dark);
        display: flex; align-items: center; gap: 8px;
    }
    .section-title .fire-tag {
        background: var(--fire); color: #fff;
        font-size: .72rem; font-weight: 700; padding: 2px 8px;
        border-radius: 4px; letter-spacing: .04em;
    }

    .product-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: var(--card-r);
        overflow: hidden;
        transition: transform .2s, box-shadow .2s;
        height: 100%;
        display: flex; flex-direction: column;
        position: relative;
    }
    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0,0,0,.1);
    }

    .pc-img-wrap {
        position: relative; overflow: hidden;
        background: #f8fafc;
    }
    .pc-img {
        width: 100%; height: 220px; object-fit: cover;
        transition: transform .35s;
    }
    .product-card:hover .pc-img { transform: scale(1.05); }

    .pc-sale-badge {
        position: absolute; top: 10px; left: 10px;
        background: var(--fire); color: #fff;
        font-size: .72rem; font-weight: 800;
        padding: 4px 10px; border-radius: 6px;
        letter-spacing: .02em;
        box-shadow: 0 2px 8px rgba(239,68,68,.4);
        animation: badge-pop .4s cubic-bezier(.34,1.56,.64,1) both;
    }
    @keyframes badge-pop {
        from { transform: scale(0); opacity: 0; }
        to   { transform: scale(1); opacity: 1; }
    }

    .pc-cat-badge {
        position: absolute; top: 10px; right: 10px;
        background: rgba(15,23,42,.7); color: #f1f5f9;
        font-size: .68rem; font-weight: 600;
        padding: 3px 8px; border-radius: 5px;
        backdrop-filter: blur(4px);
    }

    .pc-body { padding: 14px 16px; display: flex; flex-direction: column; flex: 1; }
    .pc-name {
        font-size: .9rem; font-weight: 700; color: var(--dark);
        margin-bottom: 4px;
        display: -webkit-box; -webkit-line-clamp: 2;
        -webkit-box-orient: vertical; overflow: hidden;
    }
    .pc-sale-name {
        font-size: .72rem; color: var(--fire); font-weight: 600;
        margin-bottom: 10px;
    }
    .pc-price-wrap {
        display: flex; align-items: center; gap: 8px;
        flex-wrap: wrap; margin-bottom: 12px;
    }
    .pc-price-new { font-size: 1.15rem; font-weight: 800; color: var(--fire); }
    .pc-price-old {
        font-size: .85rem; color: #94a3b8;
        text-decoration: line-through;
    }
    .pc-save-chip {
        background: #fef2f2; color: var(--fire);
        border-radius: 4px; padding: 1px 6px;
        font-size: .68rem; font-weight: 800;
    }

    .pc-actions { display: flex; gap: 8px; margin-top: auto; }
    .btn-view {
        flex: 1; padding: 9px 12px;
        background: var(--dark); color: #fff;
        border: none; border-radius: 8px;
        font-size: .82rem; font-weight: 700;
        text-align: center; text-decoration: none;
        transition: background .15s;
    }
    .btn-view:hover { background: #1e293b; color: #fff; }
    .btn-wish {
        width: 38px; height: 38px;
        border: 1.5px solid var(--border); border-radius: 8px;
        background: #fff; display: flex; align-items: center; justify-content: center;
        font-size: .9rem; cursor: pointer; transition: all .15s;
    }
    .btn-wish:hover { border-color: var(--fire); background: var(--fire-lt); }
    .btn-wish.active { border-color: var(--fire); background: var(--fire); }

    /* ── No sale state ────────────────────────────── */
    .no-sale {
        text-align: center; padding: 80px 20px;
    }
    .no-sale .ns-icon { font-size: 4rem; opacity: .25; margin-bottom: 16px; }
    .no-sale h4 { font-weight: 800; color: var(--dark); margin-bottom: 8px; }
    .no-sale p  { color: var(--muted); font-size: .9rem; }

    /* ── Sale countdown chip ──────────────────────── */
    .countdown-chip {
        display: inline-flex; align-items: center; gap: 5px;
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.25);
        border-radius: 8px; padding: 5px 12px;
        font-size: .78rem; font-weight: 700;
        margin-top: 10px;
    }

    /* ── Savings strip ────────────────────────────── */
    .savings-strip {
        background: linear-gradient(90deg, #fef2f2, #fff, #fef2f2);
        border: 1px solid #fecaca;
        border-radius: 10px; padding: 12px 20px;
        display: flex; align-items: center; gap: 14px;
        margin-bottom: 28px; flex-wrap: wrap;
    }
    .savings-strip .ss-icon { font-size: 1.4rem; }
    .savings-strip .ss-text { font-size: .88rem; color: var(--dark); font-weight: 600; }
    .savings-strip .ss-text span { color: var(--fire); font-size: 1rem; }

    /* ── Staggered card animation ─────────────────── */
    .product-card { animation: fadeUp .4s ease both; }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .col-xl-3:nth-child(1) .product-card { animation-delay: .05s; }
    .col-xl-3:nth-child(2) .product-card { animation-delay: .10s; }
    .col-xl-3:nth-child(3) .product-card { animation-delay: .15s; }
    .col-xl-3:nth-child(4) .product-card { animation-delay: .20s; }
    .col-xl-3:nth-child(5) .product-card { animation-delay: .25s; }
    .col-xl-3:nth-child(6) .product-card { animation-delay: .30s; }
    .col-xl-3:nth-child(7) .product-card { animation-delay: .35s; }
    .col-xl-3:nth-child(8) .product-card { animation-delay: .40s; }
</style>
@endsection

@section('content')


<section class="sale-hero">
    <div class="container position-relative" style="z-index:2;">
        <div class="hero-eyebrow">
            <span class="dot"></span>
            Limited Time Deals
        </div>
        <h1 class="hero-title">BIG <span>SALE</span><br>IS LIVE</h1>
        <p class="hero-sub">
            Massive discounts on top categories. No coupon needed — prices already slashed!
        </p>
        <div class="hero-stats">
            <div class="hero-stat">
                <div class="n">{{ $activeSales->count() }}</div>
                <div class="l">Active Sales</div>
            </div>
            <div class="hero-stat">
                <div class="n">{{ $saleProducts->count() }}</div>
                <div class="l">Products on Sale</div>
            </div>
            <div class="hero-stat">
                <div class="n">Up to {{ $maxDiscount }}%</div>
                <div class="l">Max Discount</div>
            </div>
        </div>
    </div>
</section>


<div class="sale-filter-bar">
    <div class="container">
        <div class="sale-tabs">
            <button class="sale-tab active" data-filter="all" onclick="filterSale('all', this)">
                🔥 All Deals
                <span class="tab-count">{{ $saleProducts->count() }}</span>
            </button>
            @foreach($activeSales as $sale)
                <button class="sale-tab" data-filter="sale-{{ $sale->id }}" onclick="filterSale('sale-{{ $sale->id }}', this)">
                    {{ $sale->scope === 'category' ? '🏷' : '📦' }}
                    {{ $sale->scope_label }}
                    <span class="tab-count">{{ $sale->product_count ?? 0 }}</span>
                </button>
            @endforeach
        </div>
    </div>
</div>

<div class="container py-5">

    @if($activeSales->count() > 0)
    <div class="row g-3 mb-5">
        @foreach($activeSales as $sale)
        <div class="col-md-6 col-lg-4">
            <div class="sale-card">
                <span class="sc-badge">{{ $sale->type === 'percent' ? $sale->discount.'%' : '₹'.$sale->discount }} OFF</span>
                <div class="sc-eyebrow">
                    {{ $sale->scope === 'category' ? '🏷 Category Sale' : '📦 Type Sale' }}
                </div>
                <div class="sc-name">{{ $sale->name }}</div>
                <div class="sc-disc">{{ $sale->discount_label }}</div>
                <div class="sc-target">
                    On all <strong>{{ $sale->scope_label }}</strong> products
                </div>
                <div class="countdown-chip">
                    ⏱ Ends {{ $sale->ends_at->diffForHumans() }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if($saleProducts->count() > 0)
    <div class="savings-strip">
        <span class="ss-icon">💰</span>
        <div class="ss-text">
            Save up to <span>{{ $maxDiscount }}% OFF</span> —
            {{ $saleProducts->count() }} products on sale right now. No coupon needed!
        </div>
    </div>
    @endif


    @if($saleProducts->count() > 0)

        @foreach($activeSales as $sale)
        <div class="sale-section mb-5" data-sale-id="sale-{{ $sale->id }}">
            <div class="section-head">
                <div class="section-title">
                    {{ $sale->scope === 'category' ? '🏷' : '📦' }}
                    {{ $sale->scope_label }}
                    <span class="fire-tag">{{ $sale->discount_label }}</span>
                </div>
                <small class="text-muted">Ends {{ $sale->ends_at->diffForHumans() }}</small>
            </div>

            <div class="row g-3">
                @php $saleItems = $saleProducts->filter(fn($p) => $p->active_sale && $p->active_sale->id === $sale->id); @endphp

                @forelse($saleItems as $product)
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6" data-sale="sale-{{ $sale->id }}">
                    <div class="product-card">

                        <div class="pc-img-wrap">
                            @if($product->image)
                                <a href="{{ route('view_product', $product->id) }}">
                                    <img src="{{ asset('storage/'.$product->image) }}"
                                         class="pc-img" alt="{{ $product->name }}">
                                </a>
                            @else
                                <div class="pc-img d-flex align-items-center justify-content-center bg-light">
                                    <span style="font-size:2.5rem;opacity:.2;">📦</span>
                                </div>
                            @endif

                            <div class="pc-sale-badge">
                                🔥 {{ $product->active_sale->discount_label }}
                            </div>

                            <div class="pc-cat-badge">
                                {{ optional($product->category)->name ?? 'General' }}
                            </div>
                        </div>

                        <div class="pc-body">
                            <div class="pc-name">{{ $product->name }}</div>
                            <div class="pc-sale-name">{{ $product->active_sale->name }}</div>

                            <div class="pc-price-wrap">
                                <span class="pc-price-new">
                                    ₹{{ number_format($product->discounted_price, 2) }}
                                </span>
                                <span class="pc-price-old">
                                    ₹{{ number_format($product->base_price, 2) }}
                                </span>
                                @if($product->active_sale->type === 'percent')
                                <span class="pc-save-chip">{{ $product->active_sale->discount }}% OFF</span>
                                @endif
                            </div>

                            <div class="pc-actions">
                                <a href="{{ route('view_product', $product->id) }}" class="btn-view">
                                    View Deal →
                                </a>
                                {{-- @auth('customer') --}}
                                <form action="{{ route('wishlist.toggle') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn-wish {{ $product->isWishlisted() ? 'active' : '' }}">
                                        {{ $product->isWishlisted() ? '❤️' : '🤍' }}
                                    </button>
                                </form>
                                {{-- @endauth --}}
                            </div>
                        </div>

                    </div>
                </div>
                @empty
                <div class="col-12">
                    <p class="text-muted text-center py-4" style="font-size:.9rem;">
                        No products found in this sale category.
                    </p>
                </div>
                @endforelse
            </div>
        </div>
        @endforeach

    @else
        <div class="no-sale">
            <div class="ns-icon">🔥</div>
            <h4>No Active Sales Right Now</h4>
            <p>Check back soon — our next big sale is coming!</p>
            <a href="{{ route('all_products') }}" class="btn btn-dark px-5 mt-3">Browse All Products</a>
        </div>
    @endif

</div>

@endsection

@section('scripts')
<script>
function filterSale(filter, el) {
    document.querySelectorAll('.sale-tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');

    const sections = document.querySelectorAll('.sale-section');

    if (filter === 'all') {
        sections.forEach(s => s.style.display = 'block');
        return;
    }

    sections.forEach(s => {
        s.style.display = s.dataset.saleId === filter ? 'block' : 'none';
    });
}
</script>
@endsection