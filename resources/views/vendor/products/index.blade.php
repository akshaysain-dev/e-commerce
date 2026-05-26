@extends('vendor.layouts.app')

@section('title', 'Manage Products')

@push('styles')
<style>
    :root {
        --ink:        #0e0e12;
        --ink-muted:  #6b6b7a;
        --surface:    #ffffff;
        --surface-2:  #f5f4f1;
        --surface-3:  #eeede9;
        --accent:     #5b5ef4;
        --accent-2:   #e8e8fd;
        --accent-dk:  #4749d6;
        --success:    #1a9e75;
        --success-bg: #e2f5ee;
        --warn:       #c47a14;
        --warn-bg:    #fef3dc;
        --danger:     #c63434;
        --danger-bg:  #fde8e8;
        --info:       #1565c0;
        --info-bg:    #e3f2fd;
        --border:     rgba(14,14,18,.08);
        --border-md:  rgba(14,14,18,.13);
        --radius-sm:  10px;
        --radius-md:  16px;
        --radius-lg:  24px;
        --shadow-sm:  0 1px 3px rgba(14,14,18,.06), 0 1px 2px rgba(14,14,18,.04);
        --shadow-md:  0 4px 16px rgba(14,14,18,.08), 0 1px 4px rgba(14,14,18,.04);
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

    /* ── Page wrapper ── */
    .vpr-wrap {
        padding: 2rem 1.75rem 3rem;
        width: 100%;
        min-width: 0;
        overflow-x: hidden;
    }

    /* ── Page header ── */
    .vpr-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1.75rem;
    }
    .vpr-header-left {}
    .vpr-eyebrow {
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: .3rem;
    }
    .vpr-title {
        font-family: var(--ff-display);
        font-size: clamp(1.4rem, 2.5vw, 2rem);
        font-weight: 700;
        color: var(--ink);
        line-height: 1.2;
        margin-bottom: .3rem;
    }
    .vpr-subtitle {
        font-size: .85rem;
        color: var(--ink-muted);
    }
    .vpr-add-btn {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .75rem 1.5rem;
        border-radius: 999px;
        border: none;
        font-family: var(--ff-body);
        font-size: .875rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        background: var(--accent);
        color: #fff;
        transition: transform .15s, box-shadow .15s, background .15s;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .vpr-add-btn:hover {
        background: var(--accent-dk);
        transform: translateY(-1px);
        box-shadow: 0 6px 24px rgba(91,94,244,.35);
        color: #fff;
        text-decoration: none;
    }
    .vpr-add-btn i { font-size: .85rem; }

    /* ── Alert ── */
    .vpr-alert {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .9rem 1.1rem;
        border-radius: var(--radius-sm);
        background: var(--success-bg);
        border: 1px solid rgba(26,158,117,.2);
        color: var(--success);
        font-size: .875rem;
        font-weight: 500;
        margin-bottom: 1.5rem;
    }
    .vpr-alert i { font-size: 1rem; flex-shrink: 0; }

    /* ── Stats row ── */
    .vpr-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .vpr-stat {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        position: relative;
        overflow: hidden;
    }
    .vpr-stat::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 3px;
    }
    .vpr-stat-blue::after  { background: var(--accent); }
    .vpr-stat-green::after { background: var(--success); }
    .vpr-stat-amber::after { background: var(--warn); }
    .vpr-stat-icon {
        width: 42px; height: 42px;
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .vpr-stat-icon-blue  { background: var(--accent-2); color: var(--accent); }
    .vpr-stat-icon-green { background: var(--success-bg); color: var(--success); }
    .vpr-stat-icon-amber { background: var(--warn-bg); color: var(--warn); }
    .vpr-stat-label {
        font-size: .72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: var(--ink-muted);
        margin-bottom: .2rem;
    }
    .vpr-stat-value {
        font-family: var(--ff-display);
        font-size: 1.65rem;
        font-weight: 700;
        color: var(--ink);
        line-height: 1;
    }

    /* ── Table card ── */
    .vpr-card {
        background: var(--surface);
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .vpr-card-head {
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .vpr-card-title {
        font-family: var(--ff-display);
        font-size: .95rem;
        font-weight: 600;
        color: var(--ink);
    }
    .vpr-live-badge {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        font-size: .7rem;
        font-weight: 600;
        color: var(--success);
        background: var(--success-bg);
        padding: .2rem .7rem;
        border-radius: 999px;
        border: 1px solid rgba(26,158,117,.15);
    }
    .vpr-live-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: var(--success);
        animation: blink 1.6s ease-in-out infinite;
    }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

    /* ── Table ── */
    .vpr-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .vpr-table {
        width: 100%;
        min-width: 580px;
        border-collapse: collapse;
        font-size: .875rem;
    }
    .vpr-table thead tr { background: var(--surface-2); }
    .vpr-table thead th {
        padding: .75rem 1rem;
        text-align: left;
        font-size: .68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: var(--ink-muted);
        white-space: nowrap;
        border-bottom: 1px solid var(--border);
    }
    .vpr-table thead th:first-child { padding-left: 1.5rem; }
    .vpr-table thead th:last-child  { padding-right: 1.5rem; text-align: right; }

    .vpr-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background .12s;
    }
    .vpr-table tbody tr:last-child { border-bottom: none; }
    .vpr-table tbody tr:hover { background: var(--surface-2); }

    .vpr-table td {
        padding: .9rem 1rem;
        vertical-align: middle;
        color: var(--ink);
    }
    .vpr-table td:first-child { padding-left: 1.5rem; }
    .vpr-table td:last-child  { padding-right: 1.5rem; }

    /* Product cell */
    .vpr-product-cell {
        display: flex;
        align-items: center;
        gap: .85rem;
    }
    .vpr-product-img {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-sm);
        object-fit: cover;
        border: 1px solid var(--border);
        flex-shrink: 0;
        background: var(--surface-2);
    }
    .vpr-product-name {
        font-weight: 600;
        color: var(--ink);
        margin-bottom: .15rem;
        font-size: .875rem;
    }
    .vpr-product-sku {
        font-size: .68rem;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--ink-muted);
        font-weight: 500;
    }

    /* Badges */
    .vpr-pill {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .25rem .75rem;
        border-radius: 999px;
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: .03em;
        white-space: nowrap;
    }
    .vpr-pill::before {
        content: '';
        width: 5px; height: 5px;
        border-radius: 50%;
        background: currentColor;
    }
    .vpr-pill-success { background: var(--success-bg); color: var(--success); }
    .vpr-pill-gray    { background: var(--surface-3);  color: var(--ink-muted); }
    .vpr-pill-info    { background: var(--info-bg);    color: var(--info); }

    /* Action buttons */
    .vpr-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: .5rem;
    }
    .vpr-btn-edit,
    .vpr-btn-del {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .4rem .9rem;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        border: 1px solid transparent;
        transition: background .15s, transform .12s, box-shadow .15s;
        white-space: nowrap;
        font-family: var(--ff-body);
    }
    .vpr-btn-edit {
        background: var(--accent-2);
        color: var(--accent);
        border-color: rgba(91,94,244,.2);
    }
    .vpr-btn-edit:hover {
        background: var(--accent);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(91,94,244,.3);
        text-decoration: none;
    }
    .vpr-btn-del {
        background: var(--danger-bg);
        color: var(--danger);
        border-color: rgba(198,52,52,.15);
    }
    .vpr-btn-del:hover {
        background: var(--danger);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(198,52,52,.3);
        text-decoration: none;
    }
    .vpr-btn-edit i,
    .vpr-btn-del i { font-size: .75rem; }

    /* ── Empty state ── */
    .vpr-empty {
        padding: 4rem 1.5rem;
        text-align: center;
    }
    .vpr-empty-icon {
        width: 72px; height: 72px;
        border-radius: 50%;
        background: var(--surface-2);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.75rem;
        color: var(--ink-muted);
        opacity: .5;
    }
    .vpr-empty-title {
        font-family: var(--ff-display);
        font-size: 1rem;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: .4rem;
    }
    .vpr-empty-sub {
        font-size: .85rem;
        color: var(--ink-muted);
        margin-bottom: 1.25rem;
    }

    /* ── Pagination bar ── */
    .vpr-pagination {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border);
        background: var(--surface-2);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: .75rem;
    }
    .vpr-pagination-info {
        font-size: .8rem;
        color: var(--ink-muted);
    }
    .vpr-pagination-info strong { color: var(--ink); font-weight: 600; }

    /* Override Bootstrap pagination to match design */
    .vpr-pagination .pagination {
        margin: 0;
        gap: .25rem;
        display: flex;
    }
    .vpr-pagination .page-link {
        border-radius: 8px !important;
        border: 1px solid var(--border-md);
        color: var(--ink);
        font-size: .8rem;
        font-weight: 500;
        padding: .35rem .7rem;
        background: var(--surface);
        transition: background .15s, color .15s;
        font-family: var(--ff-body);
    }
    .vpr-pagination .page-link:hover {
        background: var(--accent-2);
        color: var(--accent);
        border-color: rgba(91,94,244,.25);
    }
    .vpr-pagination .page-item.active .page-link {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
    }
    .vpr-pagination .page-item.disabled .page-link {
        opacity: .4;
        pointer-events: none;
    }

    /* ── Responsive ── */
    @media (max-width: 991px) {
        .vpr-wrap { padding: 1.5rem 1.25rem 2.5rem; }
        .vpr-stats { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .vpr-stats { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 575px) {
        .vpr-wrap { padding: 1rem 1rem 1.5rem; }
        .vpr-header { flex-direction: column; align-items: flex-start; }
        .vpr-add-btn { width: 100%; justify-content: center; }
        .vpr-stats { grid-template-columns: 1fr 1fr; gap: .75rem; }
        .vpr-stat { padding: 1rem; }
        .vpr-stat-value { font-size: 1.35rem; }
        .vpr-pagination { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush

@section('content')
<div class="vpr-wrap">

    {{-- ── Header ── --}}
    <div class="vpr-header">
        <div class="vpr-header-left">
            <p class="vpr-eyebrow">Inventory</p>
            <h1 class="vpr-title">Products</h1>
            <p class="vpr-subtitle">Manage your stock, pricing and product status</p>
        </div>
        <a href="{{ route('vendor_create_products') }}" class="vpr-add-btn">
            <i class="fa fa-plus"></i> Add Product
        </a>
    </div>

    {{-- ── Alert ── --}}
    @if(session('success'))
        <div class="vpr-alert">
            <i class="fa fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── Stats ── --}}
    <div class="vpr-stats">
        <div class="vpr-stat vpr-stat-blue">
            <div class="vpr-stat-icon vpr-stat-icon-blue"><i class="fa fa-box"></i></div>
            <div>
                <div class="vpr-stat-label">Total Products</div>
                <div class="vpr-stat-value">{{ $products->total() }}</div>
            </div>
        </div>
        <div class="vpr-stat vpr-stat-green">
            <div class="vpr-stat-icon vpr-stat-icon-green"><i class="fa fa-check-circle"></i></div>
            <div>
                <div class="vpr-stat-label">Active</div>
                <div class="vpr-stat-value">{{ $products->where('status', true)->count() }}</div>
            </div>
        </div>
        <div class="vpr-stat vpr-stat-amber">
            <div class="vpr-stat-icon vpr-stat-icon-amber"><i class="fa fa-pause-circle"></i></div>
            <div>
                <div class="vpr-stat-label">Inactive</div>
                <div class="vpr-stat-value">{{ $products->where('status', false)->count() }}</div>
            </div>
        </div>
    </div>

    {{-- ── Table card ── --}}
    <div class="vpr-card">

        <div class="vpr-card-head">
            <span class="vpr-card-title">All Products</span>
            <span class="vpr-live-badge">
                <span class="vpr-live-dot"></span> Live Inventory
            </span>
        </div>

        <div class="vpr-table-wrap">
            <table class="vpr-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>
                            <div class="vpr-product-cell">
                                <img src="{{ asset('storage/'.$product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="vpr-product-img">
                                <div>
                                    <div class="vpr-product-name">{{ $product->name }}</div>
                                    <div class="vpr-product-sku">SKU: {{ $product->firstVariant->sku ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="vpr-pill vpr-pill-info">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td>
                            @if($product->status)
                                <span class="vpr-pill vpr-pill-success">Active</span>
                            @else
                                <span class="vpr-pill vpr-pill-gray">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="vpr-actions">
                                <a href="{{ route('vendor.products.edit', $product->id) }}"
                                   class="vpr-btn-edit">
                                    <i class="fa fa-pen"></i> Edit
                                </a>
                                <a href="{{ route('vendor.products.delete', $product->id) }}"
                                   class="vpr-btn-del"
                                   onclick="return confirm('Delete this product permanently?');">
                                    <i class="fa fa-trash"></i> Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="vpr-empty">
                                <div class="vpr-empty-icon"><i class="fa fa-box-open"></i></div>
                                <div class="vpr-empty-title">No products yet</div>
                                <div class="vpr-empty-sub">Add your first product to start selling</div>
                                <a href="{{ route('vendor_create_products') }}" class="vpr-add-btn" style="display:inline-flex;">
                                    <i class="fa fa-plus"></i> Add Product
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
        <div class="vpr-pagination">
            <div class="vpr-pagination-info">
                Showing <strong>{{ $products->firstItem() }}</strong>–<strong>{{ $products->lastItem() }}</strong>
                of <strong>{{ $products->total() }}</strong> products
            </div>
            <nav>{{ $products->links('pagination::bootstrap-5') }}</nav>
        </div>
        @endif

    </div>

</div>
@endsection