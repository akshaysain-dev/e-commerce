@extends('vendor.layouts.app')

@section('title', 'Vendor Dashboard')

@push('styles')
<style>
    :root {
        --ink:       #0e0e12;
        --ink-soft:  #2a2a35;
        --ink-muted: #6b6b7a;
        --surface:   #ffffff;
        --surface-2: #f5f4f1;
        --surface-3: #eeede9;
        --accent:    #5b5ef4;
        --accent-2:  #e8e8fd;
        --success:   #1a9e75;
        --success-bg:#e2f5ee;
        --warn:      #c47a14;
        --warn-bg:   #fef3dc;
        --danger:    #c63434;
        --danger-bg: #fde8e8;
        --border:    rgba(14,14,18,.08);
        --radius-sm: 10px;
        --radius-md: 16px;
        --radius-lg: 24px;
        --shadow-sm: 0 1px 3px rgba(14,14,18,.06), 0 1px 2px rgba(14,14,18,.04);
        --shadow-md: 0 4px 16px rgba(14,14,18,.08), 0 1px 4px rgba(14,14,18,.04);
        --ff-display: 'Syne', sans-serif;
        --ff-body:    'DM Sans', sans-serif;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: var(--ff-body);
        background: var(--surface-2);
        color: var(--ink);
        -webkit-font-smoothing: antialiased;
    }

    /* ── Dashboard wrapper ── */
    .vd-wrap {
        max-width: 1280px;
        margin: 0 auto;
        padding: 2rem 1.5rem 3rem;
    }

    /* ── Hero banner ── */
    .vd-hero {
        position: relative;
        background: var(--ink);
        border-radius: var(--radius-lg);
        padding: 2.5rem 2.5rem 2.5rem 2.5rem;
        overflow: hidden;
        margin-bottom: 1.75rem;
    }
    .vd-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 55% 80% at 90% 50%, rgba(91,94,244,.35) 0%, transparent 70%),
            radial-gradient(ellipse 40% 60% at 5% 100%, rgba(26,158,117,.2) 0%, transparent 60%);
        pointer-events: none;
    }
    .vd-hero-grid {
        position: relative;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 2rem;
        align-items: center;
    }
    .vd-hero-eyebrow {
        font-family: var(--ff-body);
        font-size: .75rem;
        font-weight: 500;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: rgba(255,255,255,.5);
        margin-bottom: .5rem;
    }
    .vd-hero-title {
        font-family: var(--ff-display);
        font-size: clamp(1.6rem, 3vw, 2.4rem);
        font-weight: 700;
        color: #fff;
        line-height: 1.15;
        margin-bottom: .75rem;
    }
    .vd-hero-title span { color: #a5a7fa; }
    .vd-hero-sub {
        font-size: .9rem;
        color: rgba(255,255,255,.55);
        line-height: 1.6;
        max-width: 44ch;
        margin-bottom: 1.25rem;
    }
    .vd-badges { display: flex; flex-wrap: wrap; gap: .5rem; }
    .vd-badge {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .3rem .85rem;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 500;
        letter-spacing: .02em;
    }
    .vd-badge-success { background: rgba(26,158,117,.2); color: #5fddba; border: 1px solid rgba(95,221,186,.2); }
    .vd-badge-danger  { background: rgba(198,52,52,.25);  color: #f59c9c; border: 1px solid rgba(245,156,156,.2); }
    .vd-badge-accent  { background: rgba(91,94,244,.25);  color: #a5a7fa; border: 1px solid rgba(165,167,250,.2); }
    .vd-badge-dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: currentColor; opacity: .8;
        animation: blink 1.8s ease-in-out infinite;
    }
    @keyframes blink { 0%,100%{opacity:.8} 50%{opacity:.3} }

    .vd-hero-cta {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 999px;
        padding: .8rem 1.75rem;
        font-family: var(--ff-body);
        font-size: .9rem;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: transform .15s, box-shadow .15s, background .15s;
        white-space: nowrap;
    }
    .vd-hero-cta:hover {
        background: #4749d6;
        transform: translateY(-1px);
        box-shadow: 0 6px 24px rgba(91,94,244,.4);
    }
    .vd-hero-cta svg { width: 16px; height: 16px; }

    /* Decorative grid lines */
    .vd-hero-deco {
        position: absolute;
        right: 0; top: 0; bottom: 0;
        width: 300px;
        opacity: .06;
        pointer-events: none;
        overflow: hidden;
    }
    .vd-hero-deco svg { width: 100%; height: 100%; }

    /* ── Stat cards grid ── */
    .vd-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.75rem;
    }
    .vd-stat {
        background: var(--surface);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        display: flex;
        flex-direction: column;
        gap: .25rem;
        position: relative;
        overflow: hidden;
        transition: box-shadow .2s, transform .2s;
    }
    .vd-stat:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
    .vd-stat-icon {
        width: 44px; height: 44px;
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        margin-bottom: .75rem;
        flex-shrink: 0;
    }
    .vd-stat-icon-blue   { background: #e8e8fd; color: var(--accent); }
    .vd-stat-icon-green  { background: var(--success-bg); color: var(--success); }
    .vd-stat-icon-amber  { background: var(--warn-bg); color: var(--warn); }
    .vd-stat-icon-red    { background: var(--danger-bg); color: var(--danger); }
    .vd-stat-label {
        font-size: .78rem;
        font-weight: 500;
        letter-spacing: .04em;
        text-transform: uppercase;
        color: var(--ink-muted);
    }
    .vd-stat-value {
        font-family: var(--ff-display);
        font-size: 2rem;
        font-weight: 700;
        color: var(--ink);
        line-height: 1.1;
        letter-spacing: -.02em;
    }
    .vd-stat-value-green { color: var(--success); }
    .vd-stat-stripe {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 3px;
        border-radius: 0 0 var(--radius-md) var(--radius-md);
    }
    .vd-stat-stripe-blue  { background: var(--accent); }
    .vd-stat-stripe-green { background: var(--success); }
    .vd-stat-stripe-amber { background: #f0a732; }
    .vd-stat-stripe-red   { background: var(--danger); }

    /* ── Middle row ── */
    .vd-mid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.75rem;
    }
    .vd-card {
        background: var(--surface);
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .vd-card-head {
        padding: 1.25rem 1.5rem .75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--border);
    }
    .vd-card-title {
        font-family: var(--ff-display);
        font-size: 1rem;
        font-weight: 600;
        color: var(--ink);
    }
    .vd-card-body { padding: 1.5rem; }

    /* Order stats */
    .vd-order-stats { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }
    .vd-os-box {
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 1.25rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .vd-os-box::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
    }
    .vd-os-box-warn::before  { background: #f0a732; }
    .vd-os-box-green::before { background: var(--success); }
    .vd-os-num {
        font-family: var(--ff-display);
        font-size: 2.25rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: .35rem;
    }
    .vd-os-num-warn  { color: var(--warn); }
    .vd-os-num-green { color: var(--success); }
    .vd-os-label { font-size: .78rem; color: var(--ink-muted); font-weight: 500; text-transform: uppercase; letter-spacing: .04em; }

    /* Store info */
    .vd-info-list { display: flex; flex-direction: column; gap: .9rem; }
    .vd-info-row { display: flex; align-items: flex-start; gap: .85rem; }
    .vd-info-icon {
        width: 32px; height: 32px; border-radius: 8px;
        background: var(--surface-2);
        display: flex; align-items: center; justify-content: center;
        color: var(--ink-muted);
        font-size: .85rem;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .vd-info-key { font-size: .72rem; color: var(--ink-muted); font-weight: 500; text-transform: uppercase; letter-spacing: .05em; margin-bottom: .15rem; }
    .vd-info-val { font-size: .9rem; font-weight: 500; color: var(--ink); }

    /* ── Orders table card ── */
    .vd-orders { background: var(--surface); border-radius: var(--radius-md); border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden; }
    .vd-orders-head {
        padding: 1.25rem 1.5rem;
        display: flex; align-items: center; justify-content: space-between;
        border-bottom: 1px solid var(--border);
    }
    .vd-orders-live {
        display: inline-flex; align-items: center; gap: .4rem;
        font-size: .72rem; font-weight: 500;
        color: var(--success);
        background: var(--success-bg);
        padding: .25rem .75rem;
        border-radius: 999px;
        border: 1px solid rgba(26,158,117,.15);
    }
    .vd-orders-live-dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: var(--success);
        animation: blink 1.6s ease-in-out infinite;
    }
    .vd-view-all {
        display: inline-flex; align-items: center; gap: .35rem;
        font-size: .8rem; font-weight: 500;
        color: var(--accent);
        text-decoration: none;
        padding: .35rem .9rem;
        border: 1px solid rgba(91,94,244,.25);
        border-radius: 999px;
        transition: background .15s;
    }
    .vd-view-all:hover { background: var(--accent-2); }

    .vd-table-wrap { overflow-x: auto; }
    .vd-table {
        width: 100%;
        border-collapse: collapse;
        font-size: .875rem;
    }
    .vd-table thead tr { background: var(--surface-2); }
    .vd-table thead th {
        padding: .75rem 1rem;
        text-align: left;
        font-size: .7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: var(--ink-muted);
        white-space: nowrap;
        border-bottom: 1px solid var(--border);
    }
    .vd-table thead th:first-child { padding-left: 1.5rem; }
    .vd-table thead th:last-child  { padding-right: 1.5rem; }
    .vd-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background .12s;
    }
    .vd-table tbody tr:last-child { border-bottom: none; }
    .vd-table tbody tr:hover { background: var(--surface-2); }
    .vd-table td {
        padding: .9rem 1rem;
        color: var(--ink);
        vertical-align: middle;
    }
    .vd-table td:first-child { padding-left: 1.5rem; }
    .vd-table td:last-child  { padding-right: 1.5rem; }
    .vd-order-id {
        font-family: var(--ff-body);
        font-size: .78rem;
        font-weight: 600;
        letter-spacing: .04em;
        color: var(--accent);
        background: var(--accent-2);
        padding: .2rem .6rem;
        border-radius: 6px;
        white-space: nowrap;
    }
    .vd-product-name { font-weight: 500; color: var(--ink); }
    .vd-amount { font-family: var(--ff-display); font-weight: 600; color: var(--ink); }
    .vd-pill {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .25rem .7rem;
        border-radius: 999px;
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: .03em;
        white-space: nowrap;
    }
    .vd-pill::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: currentColor; }
    .vd-pill-success { background: var(--success-bg); color: var(--success); }
    .vd-pill-warn    { background: var(--warn-bg);    color: var(--warn); }
    .vd-pill-gray    { background: var(--surface-3);  color: var(--ink-muted); }
    .vd-date { font-size: .8rem; color: var(--ink-muted); }

    /* ── Empty state ── */
    .vd-empty {
        padding: 3.5rem 1.5rem;
        text-align: center;
        color: var(--ink-muted);
        font-size: .9rem;
    }
    .vd-empty i { font-size: 2.5rem; opacity: .3; display: block; margin-bottom: .75rem; }

    /* ── Responsive ── */
    @media (max-width: 1024px) {
        .vd-stats { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .vd-wrap { padding: 1rem 1rem 2.5rem; }
        .vd-hero { padding: 1.75rem 1.5rem; }
        .vd-hero-grid { grid-template-columns: 1fr; gap: 1.25rem; }
        .vd-hero-cta { align-self: flex-start; }
        .vd-hero-deco { display: none; }
        .vd-mid { grid-template-columns: 1fr; }
        .vd-card-body { padding: 1.25rem; }
        .vd-stat { padding: 1.25rem; }
        .vd-stat-value { font-size: 1.65rem; }
    }
    @media (max-width: 480px) {
        .vd-stats { grid-template-columns: 1fr 1fr; gap: .75rem; }
        .vd-hero-title { font-size: 1.5rem; }
        .vd-order-stats { grid-template-columns: 1fr 1fr; }
        .vd-os-num { font-size: 1.75rem; }
        .vd-table { font-size: .8rem; }
    }
</style>
@endpush

@section('content')
<div class="vd-wrap">

    {{-- ── Hero ── --}}
    <div class="vd-hero">

        <div class="vd-hero-deco" aria-hidden="true">
            <svg viewBox="0 0 300 260" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMaxYMid slice">
                <line x1="0" y1="40" x2="300" y2="40" stroke="white" stroke-width="1"/>
                <line x1="0" y1="90" x2="300" y2="90" stroke="white" stroke-width="1"/>
                <line x1="0" y1="140" x2="300" y2="140" stroke="white" stroke-width="1"/>
                <line x1="0" y1="190" x2="300" y2="190" stroke="white" stroke-width="1"/>
                <line x1="0" y1="240" x2="300" y2="240" stroke="white" stroke-width="1"/>
                <line x1="50" y1="0" x2="50" y2="260" stroke="white" stroke-width="1"/>
                <line x1="100" y1="0" x2="100" y2="260" stroke="white" stroke-width="1"/>
                <line x1="150" y1="0" x2="150" y2="260" stroke="white" stroke-width="1"/>
                <line x1="200" y1="0" x2="200" y2="260" stroke="white" stroke-width="1"/>
                <line x1="250" y1="0" x2="250" y2="260" stroke="white" stroke-width="1"/>
                <circle cx="50"  cy="40"  r="3" fill="white"/>
                <circle cx="100" cy="90"  r="3" fill="white"/>
                <circle cx="150" cy="140" r="3" fill="white"/>
                <circle cx="200" cy="190" r="3" fill="white"/>
                <circle cx="250" cy="240" r="3" fill="white"/>
            </svg>
        </div>

        <div class="vd-hero-grid">
            <div>
                <p class="vd-hero-eyebrow">Vendor Dashboard</p>
                <h1 class="vd-hero-title">
                    Welcome back, <span>{{ $user->name }}</span>
                </h1>
                <p class="vd-hero-sub">
                    Manage your products, orders, sales and earnings all from one place.
                </p>
                <div class="vd-badges">
                    @if($vendor->stripe_onboarded)
                        <span class="vd-badge vd-badge-success">
                            <span class="vd-badge-dot"></span> Stripe Connected
                        </span>
                    @else
                        <span class="vd-badge vd-badge-danger">
                            <span class="vd-badge-dot"></span> Stripe Not Connected
                        </span>
                    @endif
                    <span class="vd-badge vd-badge-accent">
                        {{ $vendor->shop_name }}
                    </span>
                </div>
            </div>

            @if(!$vendor->stripe_account_id)
                <div>
                    <a href="{{ route('vendor.stripe.connect') }}" class="vd-hero-cta">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                        Connect Stripe
                    </a>
                </div>
            @endif
        </div>

    </div>

    {{-- ── Stat cards ── --}}
    <div class="vd-stats">

        <div class="vd-stat">
            <div class="vd-stat-icon vd-stat-icon-blue">
                <i class="fa fa-box"></i>
            </div>
            <div class="vd-stat-label">Total Products</div>
            <div class="vd-stat-value">{{ $totalProducts }}</div>
            <div class="vd-stat-stripe vd-stat-stripe-blue"></div>
        </div>

        <div class="vd-stat">
            <div class="vd-stat-icon vd-stat-icon-green">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <div class="vd-stat-label">Total Orders</div>
            <div class="vd-stat-value">{{ $totalOrders }}</div>
            <div class="vd-stat-stripe vd-stat-stripe-green"></div>
        </div>

        <div class="vd-stat">
            <div class="vd-stat-icon vd-stat-icon-amber">
                <i class="fa fa-chart-line"></i>
            </div>
            <div class="vd-stat-label">Total Sales</div>
            <div class="vd-stat-value">₹{{ number_format($totalSales, 2) }}</div>
            <div class="vd-stat-stripe vd-stat-stripe-amber"></div>
        </div>

        <div class="vd-stat">
            <div class="vd-stat-icon vd-stat-icon-red">
                <i class="fa fa-wallet"></i>
            </div>
            <div class="vd-stat-label">Total Earnings</div>
            <div class="vd-stat-value vd-stat-value-green">₹{{ number_format($earnings, 2) }}</div>
            <div class="vd-stat-stripe vd-stat-stripe-red"></div>
        </div>

    </div>

    {{-- ── Middle row ── --}}
    <div class="vd-mid">

        {{-- Order statistics --}}
        <div class="vd-card">
            <div class="vd-card-head">
                <span class="vd-card-title">Order Statistics</span>
            </div>
            <div class="vd-card-body">
                <div class="vd-order-stats">
                    <div class="vd-os-box vd-os-box-warn">
                        <div class="vd-os-num vd-os-num-warn">{{ $pendingOrders }}</div>
                        <div class="vd-os-label">Pending</div>
                    </div>
                    <div class="vd-os-box vd-os-box-green">
                        <div class="vd-os-num vd-os-num-green">{{ $completedOrders }}</div>
                        <div class="vd-os-label">Completed</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Store information --}}
        <div class="vd-card">
            <div class="vd-card-head">
                <span class="vd-card-title">Store Information</span>
            </div>
            <div class="vd-card-body">
                <div class="vd-info-list">
                    <div class="vd-info-row">
                        <div class="vd-info-icon"><i class="fa fa-store"></i></div>
                        <div>
                            <div class="vd-info-key">Shop Name</div>
                            <div class="vd-info-val">{{ $vendor->shop_name }}</div>
                        </div>
                    </div>
                    <div class="vd-info-row">
                        <div class="vd-info-icon"><i class="fa fa-phone"></i></div>
                        <div>
                            <div class="vd-info-key">Phone</div>
                            <div class="vd-info-val">{{ $vendor->phone }}</div>
                        </div>
                    </div>
                    <div class="vd-info-row">
                        <div class="vd-info-icon"><i class="fa fa-file-invoice"></i></div>
                        <div>
                            <div class="vd-info-key">GST Number</div>
                            <div class="vd-info-val">{{ $vendor->gst_number ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="vd-info-row">
                        <div class="vd-info-icon"><i class="fa fa-map-marker-alt"></i></div>
                        <div>
                            <div class="vd-info-key">Address</div>
                            <div class="vd-info-val">{{ $vendor->address }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Recent orders ── --}}
    <div class="vd-orders">

        <div class="vd-orders-head">
            <div style="display:flex;align-items:center;gap:.85rem;">
                <span class="vd-card-title">Recent Orders</span>
                <span class="vd-orders-live">
                    <span class="vd-orders-live-dot"></span>
                    Live
                </span>
            </div>
            <a href="{{ route('vendor.earnings') }}" class="vd-view-all">
                View All
                <i class="fa fa-arrow-right" style="font-size:.75rem;"></i>
            </a>
        </div>

        <div class="vd-table-wrap">
            <table class="vd-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Product</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody id="liveOrdersTable">
                    @forelse($recentOrders as $item)
                        <tr>
                            <td>
                                <span class="vd-order-id">{{ $item->order->unique_order_id ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="vd-product-name">{{ $item->product->name ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="vd-amount">₹{{ number_format($item->vendor_amount, 2) }}</span>
                            </td>
                            <td>
                                @if($item->order->status == 'paid')
                                    <span class="vd-pill vd-pill-success">Paid</span>
                                @elseif($item->order->status == 'pending')
                                    <span class="vd-pill vd-pill-warn">Pending</span>
                                @else
                                    <span class="vd-pill vd-pill-gray">{{ ucfirst($item->order->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="vd-date">{{ $item->created_at->format('d M Y') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="vd-empty">
                                    <i class="fa fa-inbox"></i>
                                    No recent orders found.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
function loadLiveOrders() {
    $.ajax({
        url: "{{ route('vendor.live.orders') }}",
        type: "GET",
        success: function(response) {
            let html = '';
            if (!response || response.length === 0) {
                html = `<tr><td colspan="5"><div class="vd-empty"><i class="fa fa-inbox"></i>No recent orders found.</div></td></tr>`;
                $('#liveOrdersTable').html(html);
                return;
            }
            response.forEach(function(item) {
                const status = item.order?.status ?? '';
                let pillClass = 'vd-pill-gray';
                if (status === 'paid')    pillClass = 'vd-pill-success';
                if (status === 'pending') pillClass = 'vd-pill-warn';
                const label = status ? status.charAt(0).toUpperCase() + status.slice(1) : '-';
                const date = item.created_at
                    ? new Date(item.created_at).toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' })
                    : '-';
                html += `
                <tr>
                    <td><span class="vd-order-id">${item.order?.unique_order_id ?? '-'}</span></td>
                    <td><span class="vd-product-name">${item.product?.name ?? '-'}</span></td>
                    <td><span class="vd-amount">₹${parseFloat(item.vendor_amount).toFixed(2)}</span></td>
                    <td><span class="vd-pill ${pillClass}">${label}</span></td>
                    <td><span class="vd-date">${date}</span></td>
                </tr>`;
            });
            $('#liveOrdersTable').html(html);
        }
    });
}
loadLiveOrders();
setInterval(loadLiveOrders, 5000);
</script>
@endpush