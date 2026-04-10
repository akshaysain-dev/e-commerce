@extends('layouts.backend')

@section('title', 'Sales Management')

@section('styles')
<style>
    :root {
        --accent: #ef4444;
        --border: #e5e7eb;
        --bg:     #f8fafc;
    }

    .pg-header {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border-radius: 14px;
        padding: 26px 30px;
        color: #fff;
        margin-bottom: 26px;
    }
    .pg-header h1 { font-size: 1.5rem; font-weight: 800; margin: 0; }
    .pg-header p  { opacity: .75; margin: 4px 0 0; font-size: .88rem; }

    .btn-new {
        background: rgba(255,255,255,.2);
        color: #fff;
        border: 1.5px solid rgba(255,255,255,.4);
        border-radius: 9px;
        padding: 9px 20px;
        font-size: .88rem;
        font-weight: 700;
        text-decoration: none;
        transition: all .15s;
    }
    .btn-new:hover { background: rgba(255,255,255,.3); color: #fff; }

    .stat {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 18px 22px;
        transition: transform .15s, box-shadow .15s;
    }
    .stat:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.07); }
    .stat .n   { font-size: 1.9rem; font-weight: 800; line-height: 1; }
    .stat .lbl { font-size: .72rem; text-transform: uppercase; letter-spacing: .07em; color: #94a3b8; margin-top: 3px; }

    .tbl-wrap {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
    }
    .tbl-wrap thead th {
        background: #f8fafc;
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: #94a3b8;
        border-bottom: 1px solid var(--border);
        padding: 12px 16px;
        font-weight: 700;
    }
    .tbl-wrap tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: .875rem;
    }
    .tbl-wrap tbody tr:last-child td { border-bottom: none; }
    .tbl-wrap tbody tr:hover td { background: #fafafa; }

    .disc-val { font-weight: 800; color: #ef4444; font-size: .95rem; }

    .scope-pill {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 20px; font-size: .75rem; font-weight: 700;
    }
    .pill-cat  { background: #fdf4ff; color: #7e22ce; }
    .pill-type { background: #fff7ed; color: #c2410c; }

    .status-pill {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 10px; border-radius: 20px; font-size: .75rem; font-weight: 700;
    }
    .pill-live     { background: #d1fae5; color: #065f46; }
    .pill-upcoming { background: #fef3c7; color: #92400e; }
    .pill-ended    { background: #f3f4f6; color: #6b7280; }
    .pill-inactive { background: #fee2e2; color: #991b1b; }

    .btn-a {
        padding: 5px 12px; border-radius: 7px; font-size: .78rem;
        font-weight: 600; border: 1px solid; text-decoration: none;
        transition: all .15s; cursor: pointer; background: none;
    }
    .btn-edit { border-color: #c7d2fe; color: #4f46e5; background: #eef2ff; }
    .btn-edit:hover { background: #4f46e5; color: #fff; }
    .btn-del  { border-color: #fecaca; color: #dc2626; background: #fff5f5; }
    .btn-del:hover { background: #dc2626; color: #fff; }

    .flash { display:flex; align-items:center; gap:9px; padding:12px 18px; border-radius:10px; margin-bottom:20px; font-size:.88rem; }
    .flash.ok  { background:#d1fae5; color:#065f46; }
    .flash.err { background:#fee2e2; color:#991b1b; }

    .empty { padding: 55px; text-align: center; color: #94a3b8; }
    .empty .ei { font-size: 2.5rem; opacity: .3; margin-bottom: 10px; }

    .date-col { font-size: .83rem; color: #374151; }
    .date-sub { font-size: .72rem; color: #94a3b8; }
</style>
@endsection

@section('content')
<div class="container-fluid py-4 px-4" style="background:#f8fafc; min-height:100vh;">

    <div class="pg-header d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h1>🔥 Sales</h1>
            <p>Auto-apply category & product-type based discounts — no coupon code needed</p>
        </div>
        <a href="{{ route('admin.sales.create') }}" class="btn-new">+ New Sale</a>
    </div>

    @if(session('success'))
        <div class="flash ok">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash err">❌ {{ session('error') }}</div>
    @endif

    @php
        $all      = $sales->getCollection();
        $live     = $all->filter(fn($s) => $s->isActive())->count();
        $upcoming = $all->filter(fn($s) => $s->is_active && now()->lt($s->starts_at))->count();
        $ended    = $all->filter(fn($s) => now()->gt($s->ends_at))->count();
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat">
                <div class="n" style="color:#6366f1;">{{ $sales->total() }}</div>
                <div class="lbl">Total Sales</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat">
                <div class="n" style="color:#ef4444;">{{ $live }}</div>
                <div class="lbl">Live Now</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat">
                <div class="n" style="color:#d97706;">{{ $upcoming }}</div>
                <div class="lbl">Upcoming</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat">
                <div class="n" style="color:#94a3b8;">{{ $ended }}</div>
                <div class="lbl">Ended</div>
            </div>
        </div>
    </div>

    <div class="tbl-wrap">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sale Name</th>
                        <th>Discount</th>
                        <th>Scope</th>
                        <th>Target</th>
                        <th>Starts</th>
                        <th>Ends</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td class="text-muted" style="font-size:.8rem;">
                            {{ ($sales->currentPage() - 1) * $sales->perPage() + $loop->iteration }}
                        </td>
                        <td style="font-weight:700; color:#1e293b;">{{ $sale->name }}</td>
                        <td><span class="disc-val">{{ $sale->discount_label }}</span></td>
                        <td>
                            @if($sale->scope === 'category')
                                <span class="scope-pill pill-cat">🏷 Category</span>
                            @elseif($sale->scope === 'product_type')
                                <span class="scope-pill pill-type">📦 Type</span>
                            @else
                                <span class="scope-pill pill-type">🔖 Tags</span>
                            @endif
                        </td>
                        <td style="font-weight:600; color:#374151;">{{ $sale->scope_label }}</td>
                        <td>
                            <div class="date-col">{{ $sale->starts_at->format('d M Y') }}</div>
                            <div class="date-sub">{{ $sale->starts_at->format('h:i A') }}</div>
                        </td>
                        <td>
                            <div class="date-col {{ now()->gt($sale->ends_at) ? 'text-danger' : '' }}">
                                {{ $sale->ends_at->format('d M Y') }}
                            </div>
                            <div class="date-sub">{{ $sale->ends_at->diffForHumans() }}</div>
                        </td>
                        <td>
                            @if(!$sale->is_active)
                                <span class="status-pill pill-inactive">● Inactive</span>
                            @elseif(now()->greaterThanOrEqualTo($sale->starts_at) && now()->lessThanOrEqualTo($sale->ends_at))
                                <span class="status-pill pill-live">🔥 Live</span>
                            @elseif(now()->lessThan($sale->starts_at))
                                <span class="status-pill pill-upcoming">⏳ Upcoming</span>
                            @else
                                <span class="status-pill pill-ended">Ended</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('admin.sales.edit', $sale) }}" class="btn-a btn-edit">
                                    ✏ Edit
                                </a>
                                <form action="{{ route('admin.sales.destroy', $sale) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete sale: {{ $sale->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-a btn-del">🗑</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty">
                                <div class="ei">🔥</div>
                                <div style="font-weight:700; color:#374151; margin-bottom:6px;">No sales yet</div>
                                <a href="{{ route('admin.sales.create') }}" style="color:#ef4444; font-size:.88rem;">
                                    Create your first sale
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($sales->hasPages())
        <div class="mt-4 d-flex justify-content-end">
            {{ $sales->links() }}
        </div>
    @endif

</div>
@endsection