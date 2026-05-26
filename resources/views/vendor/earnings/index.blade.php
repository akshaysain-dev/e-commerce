@extends('vendor.layouts.app')

@section('title', 'Vendor Earnings & Orders')

@section('styles')
<style>

/* ═══════════════════════════════════════════
   DESIGN TOKENS
═══════════════════════════════════════════ */
:root {
    --ink:            #0f0e17;
    --ink-soft:       #2c2b38;
    --surface:        #f8f7ff;
    --surface-2:      #f0effe;
    --accent:         #5b4cf5;
    --accent-2:       #7c6ff7;
    --gold:           #e8a020;
    --gold-soft:      #fff3d6;
    --emerald:        #0a8a5f;
    --emerald-soft:   #d4f4e9;
    --rose:           #d63a5c;
    --rose-soft:      #ffe0e7;
    --sky:            #1a6fc4;
    --sky-soft:       #deeeff;
    --border:         rgba(91,76,245,.1);
    --border-md:      rgba(91,76,245,.18);
    --border-strong:  rgba(91,76,245,.26);
    --shadow-sm:      0 2px 12px rgba(91,76,245,.07);
    --shadow-md:      0 6px 28px rgba(91,76,245,.12);
    --shadow-lg:      0 16px 56px rgba(91,76,245,.18);
    --r:              16px;
    --r-sm:           10px;
    --r-pill:         999px;
    --trans:          .2s cubic-bezier(.4,0,.2,1);
}

/* ═══════════════════════════════════════════
   BASE
═══════════════════════════════════════════ */
*, *::before, *::after { box-sizing: border-box; }

body {
    background: var(--surface);
    font-family: 'DM Sans', sans-serif;
    color: var(--ink);
    margin: 0;
}

/* ═══════════════════════════════════════════
   PAGE WRAPPER
═══════════════════════════════════════════ */
.ve-page {
    padding: 2rem 1.75rem;
    max-width: 1440px;
    margin: 0 auto;
}

/* ═══════════════════════════════════════════
   FLASH
═══════════════════════════════════════════ */
.ve-flash {
    display: flex;
    align-items: center;
    gap: .6rem;
    background: var(--emerald-soft);
    color: var(--emerald);
    border: 1px solid rgba(10,138,95,.18);
    border-radius: var(--r-sm);
    padding: .85rem 1.2rem;
    font-size: .875rem;
    font-weight: 500;
    margin-bottom: 1.5rem;
}
.ve-flash::before { content: '✓'; font-weight: 800; }

/* ═══════════════════════════════════════════
   HEADER STRIP
═══════════════════════════════════════════ */
.ve-header {
    position: relative;
    overflow: hidden;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    background: var(--ink);
    border-radius: var(--r);
    padding: 1.75rem 2rem;
    margin-bottom: 1.75rem;
}
.ve-header::before {
    content: '';
    position: absolute;
    top: -70px; right: -70px;
    width: 250px; height: 250px;
    background: var(--accent);
    border-radius: 50%;
    opacity: .16;
    pointer-events: none;
}
.ve-header::after {
    content: '';
    position: absolute;
    bottom: -80px; left: 28%;
    width: 200px; height: 200px;
    background: var(--gold);
    border-radius: 50%;
    opacity: .1;
    pointer-events: none;
}
.ve-header-text { position: relative; z-index: 1; }
.ve-header-text h1 {
    font-family: 'Syne', sans-serif;
    font-size: clamp(1.25rem, 3vw, 1.7rem);
    font-weight: 800;
    color: #fff;
    margin: 0 0 .3rem;
    letter-spacing: -.3px;
}
.ve-header-text p {
    color: rgba(255,255,255,.5);
    font-size: .85rem;
    font-weight: 300;
    margin: 0;
}
.ve-header-badge {
    position: relative; z-index: 1;
    flex-shrink: 0;
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.18);
    border-radius: var(--r-pill);
    padding: .48rem 1.1rem;
    font-size: .78rem;
    font-weight: 500;
    color: rgba(255,255,255,.8);
    white-space: nowrap;
}

/* ═══════════════════════════════════════════
   STAT CARDS
═══════════════════════════════════════════ */
.ve-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.1rem;
    margin-bottom: 1.75rem;
}
.ve-stat {
    position: relative;
    overflow: hidden;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--r);
    padding: 1.5rem;
    box-shadow: var(--shadow-sm);
    transition: transform var(--trans), box-shadow var(--trans);
}
.ve-stat:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
.ve-stat__bar {
    position: absolute;
    top: 0; left: 0;
    width: 4px; height: 100%;
    border-radius: 4px 0 0 4px;
}
.ve-stat--earn   .ve-stat__bar { background: linear-gradient(180deg, var(--accent), var(--accent-2)); }
.ve-stat--orders .ve-stat__bar { background: linear-gradient(180deg, var(--sky), #5eb3f7); }
.ve-stat--prods  .ve-stat__bar { background: linear-gradient(180deg, var(--emerald), #2ec99a); }

.ve-stat__head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: .9rem;
}
.ve-stat__label {
    font-size: .7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .9px;
    color: #999;
}
.ve-stat__icon {
    width: 40px; height: 40px;
    border-radius: var(--r-sm);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.ve-stat--earn   .ve-stat__icon { background: var(--surface-2); color: var(--accent); }
.ve-stat--orders .ve-stat__icon { background: var(--sky-soft);  color: var(--sky); }
.ve-stat--prods  .ve-stat__icon { background: var(--emerald-soft); color: var(--emerald); }

.ve-stat__value {
    font-family: 'Syne', sans-serif;
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 800;
    color: var(--ink);
    line-height: 1;
    margin-bottom: .25rem;
}
.ve-stat__sub { font-size: .75rem; color: #bbb; }

/* ═══════════════════════════════════════════
   TABLE CARD
═══════════════════════════════════════════ */
.ve-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}
.ve-card-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    padding: 1.35rem 1.6rem 1.1rem;
    border-bottom: 1px solid var(--border);
}
.ve-card-head h2 {
    font-family: 'Syne', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    color: var(--ink);
    margin: 0 0 .15rem;
}
.ve-card-head p { font-size: .78rem; color: #bbb; margin: 0; }
.ve-chip {
    flex-shrink: 0;
    background: var(--surface-2);
    color: var(--accent);
    font-size: .75rem;
    font-weight: 600;
    padding: .3rem .85rem;
    border-radius: var(--r-pill);
    border: 1px solid var(--border-md);
    white-space: nowrap;
}

/* ═══════════════════════════════════════════
   DESKTOP TABLE  (≥768px)
═══════════════════════════════════════════ */
.ve-table-wrap {
    overflow-x: auto;
    scrollbar-width: thin;
    scrollbar-color: var(--border-strong) transparent;
}
.ve-table-wrap::-webkit-scrollbar { height: 4px; }
.ve-table-wrap::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 4px; }

.ve-table {
    width: 100%;
    border-collapse: collapse;
    font-size: .855rem;
    min-width: 900px;
}
.ve-table thead th {
    background: var(--surface);
    padding: .8rem 1rem;
    font-size: .67rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #aaa;
    white-space: nowrap;
    border-bottom: 1px solid var(--border);
}
.ve-table thead th:first-child { padding-left: 1.6rem; }
.ve-table thead th:last-child  { padding-right: 1.6rem; text-align: right; }
.ve-table tbody tr {
    border-bottom: 1px solid rgba(91,76,245,.045);
    transition: background var(--trans);
}
.ve-table tbody tr:last-child { border-bottom: none; }
.ve-table tbody tr:hover { background: #fafafe; }
.ve-table tbody td {
    padding: .95rem 1rem;
    vertical-align: middle;
}
.ve-table tbody td:first-child { padding-left: 1.6rem; }
.ve-table tbody td:last-child  { padding-right: 1.6rem; text-align: right; }

/* ═══════════════════════════════════════════
   MOBILE CARDS  (<768px) — hidden on desktop
═══════════════════════════════════════════ */
.ve-mobile-list { display: none; }

.ve-order-card {
    border-bottom: 1px solid var(--border);
    padding: 1rem 1.1rem;
    transition: background var(--trans);
}
.ve-order-card:last-child { border-bottom: none; }
.ve-order-card:active { background: var(--surface); }

/* card top row */
.ve-oc-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: .75rem;
    margin-bottom: .75rem;
}
.ve-oc-left { display: flex; align-items: center; gap: .7rem; flex: 1; min-width: 0; }
.ve-oc-img {
    width: 48px; height: 48px;
    border-radius: var(--r-sm);
    object-fit: cover;
    border: 1px solid var(--border);
    flex-shrink: 0;
    background: var(--surface);
}
.ve-oc-product-name {
    font-weight: 600;
    font-size: .875rem;
    color: var(--ink);
    line-height: 1.3;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.ve-oc-sku { font-size: .7rem; color: #ccc; font-family: monospace; margin-top: .15rem; }

/* card mid row: meta grid */
.ve-oc-meta {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: .5rem .75rem;
    background: var(--surface);
    border-radius: var(--r-sm);
    padding: .75rem;
    margin-bottom: .75rem;
}
.ve-oc-meta-item { display: flex; flex-direction: column; gap: .2rem; }
.ve-oc-meta-lbl  { font-size: .63rem; font-weight: 600; text-transform: uppercase; letter-spacing: .7px; color: #bbb; }
.ve-oc-meta-val  { font-size: .82rem; font-weight: 500; color: var(--ink-soft); }
.ve-oc-earn      { font-family: 'Syne', sans-serif; font-weight: 700; color: var(--emerald); }

/* card bottom row */
.ve-oc-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: .5rem;
}
.ve-oc-customer { font-size: .8rem; color: #aaa; }
.ve-oc-customer strong { color: var(--ink-soft); font-weight: 500; display: block; font-size: .83rem; }

/* ═══════════════════════════════════════════
   SHARED CELL COMPONENTS
═══════════════════════════════════════════ */

/* order id */
.ve-oid {
    font-family: 'Syne', sans-serif;
    font-size: .77rem;
    font-weight: 700;
    color: var(--accent);
    background: var(--surface-2);
    padding: .25rem .7rem;
    border-radius: var(--r-pill);
    border: 1px solid var(--border-md);
    white-space: nowrap;
    display: inline-block;
}

/* customer */
.ve-cname  { font-weight: 500; color: var(--ink); font-size: .865rem; line-height: 1.3; }
.ve-cemail { font-size: .73rem; color: #ccc; margin-top: .12rem; }

/* product */
.ve-prow { display: flex; align-items: center; gap: .8rem; }
.ve-pimg {
    width: 50px; height: 50px;
    border-radius: var(--r-sm);
    object-fit: cover;
    border: 1px solid var(--border);
    flex-shrink: 0;
    background: var(--surface);
}
.ve-pname { font-weight: 500; color: var(--ink); font-size: .86rem; line-height: 1.3; }
.ve-psku  { font-size: .7rem; color: #ccc; margin-top: .15rem; font-family: monospace; }

/* qty */
.ve-qty {
    font-weight: 600;
    font-size: .83rem;
    color: var(--ink-soft);
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r-pill);
    padding: .18rem .6rem;
    display: inline-block;
}

/* earnings */
.ve-earn {
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: .92rem;
    color: var(--emerald);
}

/* badges */
.ve-badge {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    font-size: .67rem;
    font-weight: 600;
    letter-spacing: .4px;
    text-transform: uppercase;
    padding: .28rem .7rem;
    border-radius: var(--r-pill);
    white-space: nowrap;
}
.ve-badge--pay       { background: var(--sky-soft);      color: var(--sky);     border: 1px solid rgba(26,111,196,.14); }
.ve-badge--paid      { background: var(--emerald-soft);  color: var(--emerald); border: 1px solid rgba(10,138,95,.16); }
.ve-badge--pending   { background: var(--gold-soft);     color: #b07200;        border: 1px solid rgba(232,160,32,.2); }
.ve-badge--processing{ background: var(--surface-2);     color: var(--accent);  border: 1px solid var(--border-md); }
.ve-badge--shipped   { background: var(--sky-soft);      color: var(--sky);     border: 1px solid rgba(26,111,196,.14); }
.ve-badge--delivered { background: var(--emerald-soft);  color: var(--emerald); border: 1px solid rgba(10,138,95,.16); }
.ve-badge--cancelled { background: var(--rose-soft);     color: var(--rose);    border: 1px solid rgba(214,58,92,.14); }
.ve-badge--default   { background: var(--surface);       color: #999;           border: 1px solid var(--border); }

/* dot */
.ve-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    display: inline-block;
    flex-shrink: 0;
}
.ve-dot--paid,
.ve-dot--delivered  { background: var(--emerald); }
.ve-dot--pending    { background: var(--gold); }
.ve-dot--processing { background: var(--accent); animation: blink 1.6s ease-in-out infinite; }
.ve-dot--shipped    { background: var(--sky); }
.ve-dot--cancelled  { background: var(--rose); }
.ve-dot--default    { background: #ccc; }

@keyframes blink {
    0%,100% { opacity:1; transform:scale(1); }
    50%      { opacity:.4; transform:scale(1.5); }
}

/* date */
.ve-date { font-size: .8rem; color: #bbb; white-space: nowrap; }

/* ═══════════════════════════════════════════
   ACTION DROPDOWN
═══════════════════════════════════════════ */
.ve-dd { position: relative; display: inline-block; }

.ve-dd-btn {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    background: var(--surface);
    border: 1px solid var(--border-md);
    border-radius: var(--r-pill);
    padding: .42rem .95rem;
    font-size: .78rem;
    font-weight: 500;
    color: var(--ink-soft);
    cursor: pointer;
    white-space: nowrap;
    font-family: 'DM Sans', sans-serif;
    transition: background var(--trans), border-color var(--trans), box-shadow var(--trans);
}
.ve-dd-btn:hover {
    background: var(--surface-2);
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(91,76,245,.1);
}

.ve-dd-menu {
    display: none;
    position: absolute;
    right: 0;
    top: calc(100% + 7px);
    background: #fff;
    border: 1px solid var(--border-md);
    border-radius: var(--r);
    box-shadow: var(--shadow-lg);
    min-width: 225px;
    z-index: 1000;
    overflow: hidden;
    animation: pop-in .14s ease;
}
@keyframes pop-in {
    from { opacity:0; transform:translateY(-5px) scale(.97); }
    to   { opacity:1; transform:translateY(0) scale(1); }
}
.ve-dd.open .ve-dd-menu { display: block; }

.ve-dd-head {
    padding: .85rem 1.05rem .65rem;
    border-bottom: 1px solid var(--border);
}
.ve-dd-head strong {
    font-family: 'Syne', sans-serif;
    font-size: .83rem;
    font-weight: 700;
    color: var(--ink);
    display: block;
}
.ve-dd-head span { font-size: .72rem; color: #ccc; }
.ve-dd-body { padding: .45rem; }

.ve-s-btn {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    background: none;
    border: none;
    border-radius: var(--r-sm);
    padding: .58rem .8rem;
    font-size: .82rem;
    font-weight: 400;
    color: var(--ink-soft);
    cursor: pointer;
    text-align: left;
    font-family: 'DM Sans', sans-serif;
    transition: background var(--trans);
}
.ve-s-btn:hover { background: var(--surface); }
.ve-s-btn .chk { color: var(--emerald); font-size: .78rem; }
.ve-s-btn.ve-danger { color: var(--rose); }
.ve-s-btn.ve-danger:hover { background: var(--rose-soft); }

/* mobile dropdown — bottom sheet feel */
.ve-dd-btn-sm {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    background: var(--surface-2);
    border: 1px solid var(--border-md);
    border-radius: var(--r-pill);
    padding: .38rem .85rem;
    font-size: .75rem;
    font-weight: 500;
    color: var(--accent);
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    flex-shrink: 0;
    transition: background var(--trans);
}
.ve-dd-btn-sm:hover { background: #e4e0fe; }

/* ═══════════════════════════════════════════
   EMPTY STATE
═══════════════════════════════════════════ */
.ve-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 4.5rem 2rem;
    text-align: center;
}
.ve-empty-ico { font-size: 3.2rem; opacity: .35; margin-bottom: 1rem; }
.ve-empty h3  { font-family:'Syne',sans-serif; font-size:1.05rem; font-weight:700; color:#bbb; margin:0 0 .35rem; }
.ve-empty p   { font-size:.83rem; color:#ddd; margin:0; }

/* ═══════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════ */

/* ── Tablet: hide some columns ── */
@media (max-width: 1024px) {
    .ve-hide-md { display: none; }
}

/* ── Mobile: swap table → cards ── */
@media (max-width: 767px) {
    .ve-page        { padding: 1.1rem 1rem; }
    .ve-header      { padding: 1.3rem 1.2rem; flex-direction: column; align-items: flex-start; gap: .75rem; }
    .ve-header-badge{ align-self: flex-start; }

    .ve-stats       { grid-template-columns: 1fr; gap: .8rem; margin-bottom: 1.25rem; }
    .ve-stat        { padding: 1.2rem 1.1rem 1.1rem; }

    .ve-card-head   { padding: 1rem 1.1rem .85rem; }

    /* hide desktop table, show mobile cards */
    .ve-table-wrap  { display: none; }
    .ve-mobile-list { display: block; }
}

/* ── Small mobile ── */
@media (max-width: 420px) {
    .ve-oc-meta { grid-template-columns: 1fr 1fr; }
    .ve-header-text h1 { font-size: 1.2rem; }
    .ve-stat__value    { font-size: 1.6rem; }
}
</style>
@endsection

@section('content')
<div class="ve-page">

    {{-- Flash --}}
    @if(session('success'))
        <div class="ve-flash">{{ session('success') }}</div>
    @endif

    {{-- ── Header ── --}}
    <div class="ve-header">
        <div class="ve-header-text">
            <h1>Earnings &amp; Orders</h1>
            <p>Manage orders, track revenue and update delivery status</p>
        </div>
        <div class="ve-header-badge">📅 &nbsp;{{ now()->format('d M Y') }}</div>
    </div>

    {{-- ── Stat Cards ── --}}
    <div class="ve-stats">

        <div class="ve-stat ve-stat--earn">
            <div class="ve-stat__bar"></div>
            <div class="ve-stat__head">
                <span class="ve-stat__label">Total Earnings</span>
                <div class="ve-stat__icon" style="font-weight:700;font-family:'Syne',sans-serif;font-size:.95rem;">₹</div>
            </div>
            <div class="ve-stat__value">₹{{ number_format($totalEarning, 2) }}</div>
            <div class="ve-stat__sub">Vendor share after commission</div>
        </div>

        <div class="ve-stat ve-stat--orders">
            <div class="ve-stat__bar"></div>
            <div class="ve-stat__head">
                <span class="ve-stat__label">Total Orders</span>
                <div class="ve-stat__icon"><i class="fa fa-shopping-cart"></i></div>
            </div>
            <div class="ve-stat__value">{{ $orderItems->count() }}</div>
            <div class="ve-stat__sub">Line items across all orders</div>
        </div>

        <div class="ve-stat ve-stat--prods">
            <div class="ve-stat__bar"></div>
            <div class="ve-stat__head">
                <span class="ve-stat__label">Products Sold</span>
                <div class="ve-stat__icon"><i class="fa fa-box"></i></div>
            </div>
            <div class="ve-stat__value">{{ $orderItems->sum('quantity') }}</div>
            <div class="ve-stat__sub">Total units dispatched</div>
        </div>

    </div>

    {{-- ── Orders Table Card ── --}}
    <div class="ve-card">

        <div class="ve-card-head">
            <div>
                <h2>Orders &amp; Earnings</h2>
                <p>All orders associated with your products</p>
            </div>
            <span class="ve-chip">{{ $orderItems->count() }} records</span>
        </div>

        {{-- ════════════════════════════════════
             DESKTOP TABLE  (≥768px)
        ════════════════════════════════════ --}}
        <div class="ve-table-wrap">
            <table class="ve-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Earnings</th>
                        <th class="ve-hide-md">Payment</th>
                        <th>Status</th>
                        <th class="ve-hide-md">Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orderItems as $item)
                    @php $st = $item->order->status ?? 'default';
                         $stClass = in_array($st,['paid','pending','processing','shipped','delivered','cancelled']) ? $st : 'default';
                    @endphp
                    <tr>
                        {{-- Order --}}
                        <td><span class="ve-oid">{{ $item->order->unique_order_id ?? '—' }}</span></td>

                        {{-- Customer --}}
                        <td>
                            <div class="ve-cname">{{ $item->order->customer->name ?? '—' }}</div>
                            <div class="ve-cemail">{{ $item->order->customer->email ?? '' }}</div>
                        </td>

                        {{-- Product --}}
                        <td>
                            <div class="ve-prow">
                                <img class="ve-pimg"
                                     src="{{ asset('storage/'.$item->product->image) }}"
                                     alt="{{ $item->product->name ?? '' }}">
                                <div>
                                    <div class="ve-pname">{{ $item->product->name ?? '—' }}</div>
                                    <div class="ve-psku">SKU: {{ $item->product->firstVariant->sku ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Qty --}}
                        <td><span class="ve-qty">{{ $item->quantity }}</span></td>

                        {{-- Price --}}
                        <td style="color:var(--ink-soft);font-weight:500;">₹{{ number_format($item->price, 2) }}</td>

                        {{-- Earnings --}}
                        <td><span class="ve-earn">₹{{ number_format($item->vendor_amount, 2) }}</span></td>

                        {{-- Payment --}}
                        <td class="ve-hide-md">
                            <span class="ve-badge ve-badge--pay">
                                {{ strtoupper($item->order->payment_method ?? 'COD') }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td>
                            <span class="ve-badge ve-badge--{{ $stClass }}">
                                <span class="ve-dot ve-dot--{{ $stClass }}"></span>
                                {{ ucfirst($st) }}
                            </span>
                        </td>

                        {{-- Date --}}
                        <td class="ve-date ve-hide-md">{{ $item->created_at->format('d M Y') }}</td>

                        {{-- Actions --}}
                        <td>
                            <div class="ve-dd" id="dd-{{ $loop->index }}">
                                <button class="ve-dd-btn"
                                        type="button"
                                        onclick="toggleDD('dd-{{ $loop->index }}')">
                                    <i class="fas fa-pen" style="font-size:.72rem;"></i>
                                    Edit
                                    <i class="fas fa-chevron-down" style="font-size:.65rem;"></i>
                                </button>
                                @include('vendor.partials._status_dropdown', ['item' => $item, 'st' => $st])
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10">
                            <div class="ve-empty">
                                <div class="ve-empty-ico">🛒</div>
                                <h3>No Orders Found</h3>
                                <p>Orders associated with your products will appear here.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ════════════════════════════════════
             MOBILE CARD LIST  (<768px)
        ════════════════════════════════════ --}}
        <div class="ve-mobile-list">
            @forelse($orderItems as $item)
            @php $st = $item->order->status ?? 'default';
                 $stClass = in_array($st,['paid','pending','processing','shipped','delivered','cancelled']) ? $st : 'default';
            @endphp

            <div class="ve-order-card">

                {{-- Top: product image + name + status --}}
                <div class="ve-oc-top">
                    <div class="ve-oc-left">
                        <img class="ve-oc-img"
                             src="{{ asset('storage/'.$item->product->image) }}"
                             alt="{{ $item->product->name ?? '' }}">
                        <div style="min-width:0;">
                            <div class="ve-oc-product-name">{{ $item->product->name ?? '—' }}</div>
                            <div class="ve-oc-sku">SKU: {{ $item->product->firstVariant->sku ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <span class="ve-badge ve-badge--{{ $stClass }}" style="flex-shrink:0;">
                        <span class="ve-dot ve-dot--{{ $stClass }}"></span>
                        {{ ucfirst($st) }}
                    </span>
                </div>

                {{-- Mid: meta grid --}}
                <div class="ve-oc-meta">
                    <div class="ve-oc-meta-item">
                        <span class="ve-oc-meta-lbl">Order</span>
                        <span class="ve-oid" style="font-size:.7rem;padding:.2rem .55rem;">
                            {{ $item->order->unique_order_id ?? '—' }}
                        </span>
                    </div>
                    <div class="ve-oc-meta-item">
                        <span class="ve-oc-meta-lbl">Qty</span>
                        <span class="ve-oc-meta-val">{{ $item->quantity }} unit{{ $item->quantity > 1 ? 's' : '' }}</span>
                    </div>
                    <div class="ve-oc-meta-item">
                        <span class="ve-oc-meta-lbl">Price</span>
                        <span class="ve-oc-meta-val">₹{{ number_format($item->price, 2) }}</span>
                    </div>
                    <div class="ve-oc-meta-item">
                        <span class="ve-oc-meta-lbl">Earnings</span>
                        <span class="ve-oc-earn">₹{{ number_format($item->vendor_amount, 2) }}</span>
                    </div>
                    <div class="ve-oc-meta-item">
                        <span class="ve-oc-meta-lbl">Payment</span>
                        <span class="ve-oc-meta-val">{{ strtoupper($item->order->payment_method ?? 'COD') }}</span>
                    </div>
                    <div class="ve-oc-meta-item">
                        <span class="ve-oc-meta-lbl">Date</span>
                        <span class="ve-oc-meta-val">{{ $item->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                {{-- Bottom: customer + action --}}
                <div class="ve-oc-bottom">
                    <div class="ve-oc-customer">
                        <strong>{{ $item->order->customer->name ?? '—' }}</strong>
                        {{ $item->order->customer->email ?? '' }}
                    </div>
                    <div class="ve-dd" id="ddm-{{ $loop->index }}">
                        <button class="ve-dd-btn-sm"
                                type="button"
                                onclick="toggleDD('ddm-{{ $loop->index }}')">
                            <i class="fas fa-pen" style="font-size:.7rem;"></i>
                            Status
                            <i class="fas fa-chevron-down" style="font-size:.62rem;"></i>
                        </button>
                        @include('vendor.partials._status_dropdown', ['item' => $item, 'st' => $st])
                    </div>
                </div>

            </div>
            @empty
            <div class="ve-empty">
                <div class="ve-empty-ico">🛒</div>
                <h3>No Orders Found</h3>
                <p>Orders associated with your products will appear here.</p>
            </div>
            @endforelse
        </div>

    </div>{{-- /ve-card --}}

</div>{{-- /ve-page --}}

<script>
function toggleDD(id) {
    document.querySelectorAll('.ve-dd.open').forEach(function(el) {
        if (el.id !== id) el.classList.remove('open');
    });
    document.getElementById(id).classList.toggle('open');
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('.ve-dd')) {
        document.querySelectorAll('.ve-dd.open').forEach(function(el) {
            el.classList.remove('open');
        });
    }
});
</script>
@endsection