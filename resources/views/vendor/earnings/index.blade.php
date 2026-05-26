@extends('vendor.layouts.app')

@section('title', 'Vendor Earnings')

@section('styles')
<style>
    .table thead th {
        letter-spacing: .5px;
        border-bottom: 1px solid #f1f1f1;
    }

    .table tbody tr {
        transition: .2s ease;
    }

    .table tbody tr:hover {
        background: #fcfcfc !important;
    }

    .earning-card {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: #fff;
        border-radius: 20px;
    }

    .bg-soft-success {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .bg-soft-warning {
        background: #fff8e1;
        color: #f57c00;
    }

    .bg-soft-danger {
        background: #ffebee;
        color: #c62828;
    }
</style>
@endsection

@section('content')

<div class="container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-4 rounded shadow-sm border-bottom">
        <div>
            <h2 class="fw-bold mb-1">Vendor Earnings</h2>
            <p class="text-muted small mb-0">
                Track all your product orders and earnings
            </p>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 earning-card">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-white-50 mb-2">Total Earnings</h6>
                    <h2 class="fw-bold mb-0">
                        ₹ {{ number_format($totalEarning, 2) }}
                    </h2>
                </div>

                <div class="display-5">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="bg-light">
                    <tr class="text-uppercase small fw-bold text-muted">
                        <th class="ps-4 py-3">Order</th>
                        <th class="py-3">Customer</th>
                        <th class="py-3">Product</th>
                        <th class="py-3">Qty</th>
                        <th class="py-3">Price</th>
                        <th class="py-3">Earning</th>
                        <th class="py-3">Status</th>
                        <th class="py-3 pe-4">Date</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($orderItems as $item)

                    <tr>

                        <td class="ps-4">
                            <span class="fw-semibold">
                                {{ $item->order->unique_order_id ?? '-' }}
                            </span>
                        </td>

                        <td>
                            {{ $item->order->customer->name ?? '-' }}
                        </td>

                        <td>
                            <div class="d-flex align-items-center">

                                <div class="rounded-3 me-3 overflow-hidden border"
                                     style="width:50px;height:50px;">

                                    <img src="{{ asset('storage/'.$item->product->image) }}"
                                         class="w-100 h-100 object-fit-cover">
                                </div>

                                <div>
                                    <div class="fw-bold">
                                        {{ $item->product->name ?? '-' }}
                                    </div>

                                    <small class="text-muted">
                                        SKU :
                                        {{ $item->product->firstVariant->sku ?? 'N/A' }}
                                    </small>
                                </div>

                            </div>
                        </td>

                        <td>
                            {{ $item->quantity }}
                        </td>

                        <td>
                            ₹ {{ number_format($item->price, 2) }}
                        </td>

                        <td>
                            <span class="fw-bold text-success">
                                ₹ {{ number_format($item->vendor_amount, 2) }}
                            </span>
                        </td>

                        <td>

                            @if($item->order->status == 'paid')

                                <span class="badge bg-soft-success rounded-pill px-3">
                                    Paid
                                </span>

                            @elseif($item->order->status == 'pending')

                                <span class="badge bg-soft-warning rounded-pill px-3">
                                    Pending
                                </span>

                            @else

                                <span class="badge bg-soft-danger rounded-pill px-3">
                                    {{ ucfirst($item->order->status) }}
                                </span>

                            @endif

                        </td>

                        <td class="pe-4">
                            {{ $item->created_at->format('d M Y') }}
                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="8" class="text-center py-5">

                            <i class="bi bi-wallet2 fs-1 text-muted d-block mb-3"></i>

                            <h5 class="text-muted">
                                No Earnings Found
                            </h5>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection