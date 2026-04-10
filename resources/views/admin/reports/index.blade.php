@extends('layouts.backend')

@section('title', 'Sales Report')

@section('content')
<div class="container mt-4 mb-5">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- 📅 Filter Section --}}
        <div class="col-md-4 col-lg-3">

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom fw-semibold py-3">
                    📅 Filter Sales
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('sales.report') }}" id="filterForm">

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold text-uppercase">From Date</label>
                            <input type="date" name="from"
                                class="form-control form-control-sm"
                                value="{{ request('from') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold text-uppercase">To Date</label>
                            <input type="date" name="to"
                                class="form-control form-control-sm"
                                value="{{ request('to') }}">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-sm">
                                🔍 Apply Filter
                            </button>

                            {{-- ✅ Reset Button --}}
                            <a href="{{ route('sales.report') }}" class="btn btn-outline-secondary btn-sm">
                                ✕ Reset Filter
                            </a>

                            <a href="{{ route('sales.export', request()->all()) }}"
                               class="btn btn-success btn-sm">
                                ⬇ Export Excel
                            </a>
                        </div>

                    </form>
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body">
                    <p class="text-muted small fw-semibold text-uppercase mb-3">Quick Stats</p>

                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="text-muted small">Total Orders</span>
                        <span class="fw-bold fs-5">{{ $count ?? $orders->count() }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2">
                        <span class="text-muted small">Total Sales</span>
                        <span class="fw-bold fs-5 text-success">
                            ₹{{ number_format($total ?? $orders->sum('total_amount'), 2) }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        {{-- 📊 Sales Table --}}
        <div class="col-md-8 col-lg-9">
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <span class="fw-semibold">📊 Sales Report</span>
                    <span class="badge bg-success-subtle text-success fs-6 px-3 py-2 rounded-pill">
                        ₹{{ number_format($total ?? $orders->sum('total_amount'), 2) }}
                    </span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3 text-muted small text-uppercase fw-semibold">#</th>
                                    <th class="text-muted small text-uppercase fw-semibold">Order ID</th>
                                    <th class="text-muted small text-uppercase fw-semibold">Total</th>
                                    <th class="text-muted small text-uppercase fw-semibold">Status</th>
                                    <th class="text-muted small text-uppercase fw-semibold">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td class="ps-3 text-muted">{{ $loop->iteration }}</td>

                                        <td>
                                            <span class="fw-semibold">#{{ $order->unique_order_id ?? $order->id }}</span>
                                        </td>

                                        <td class="fw-bold text-success">
                                            ₹{{ number_format($order->total_amount, 2) }}
                                        </td>

                                        <td>
                                            <span class="badge rounded-pill
                                                @if($order->status == 'delivered') bg-success-subtle text-success
                                                @elseif($order->status == 'processing') bg-info-subtle text-info
                                                @elseif($order->status == 'shipped') bg-primary-subtle text-primary
                                                @elseif($order->status == 'paid') bg-secondary-subtle text-secondary
                                                @elseif($order->status == 'pending') bg-warning-subtle text-warning
                                                @else bg-danger-subtle text-danger
                                                @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>

                                        <td class="text-muted small">
                                            {{ $order->created_at->format('d M Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">
                                            🚫 No sales data found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection