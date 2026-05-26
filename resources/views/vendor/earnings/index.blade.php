@extends('vendor.layouts.app')

@section('title', 'Vendor Earnings & Orders')

@section('styles')

<style>

    .table thead th {
        letter-spacing: .5px;
        border-bottom: 1px solid #f1f1f1;
        white-space: nowrap;
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

    .stats-card {
        border-radius: 20px;
        transition: .3s ease;
    }

    .stats-card:hover {
        transform: translateY(-4px);
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

    .bg-soft-primary {
        background: #e3f2fd;
        color: #1565c0;
    }

    .dropdown-menu {
        min-width: 220px;
    }

</style>

@endsection

@section('content')

<div class="container-fluid px-4 py-4">

    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm rounded-4">

            {{ session('success') }}

        </div>

    @endif

    <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-4 rounded-4 shadow-sm">

        <div>

            <h2 class="fw-bold mb-1">
                Vendor Earnings & Orders
            </h2>

            <p class="text-muted mb-0">
                Manage your orders, track earnings and update delivery status
            </p>

        </div>

    </div>

    <div class="row g-4 mb-4">

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm earning-card h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h6 class="text-white-50 mb-2">

                                Total Earnings

                            </h6>

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

        </div>

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm stats-card h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-2">
                                Total Orders
                            </p>

                            <h2 class="fw-bold">

                                {{ $orderItems->count() }}

                            </h2>

                        </div>

                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                             style="width:65px;height:65px;">

                            <i class="fa fa-shopping-cart fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm stats-card h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-2">
                                Total Products Sold
                            </p>

                            <h2 class="fw-bold">

                                {{ $orderItems->sum('quantity') }}

                            </h2>

                        </div>

                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
                             style="width:65px;height:65px;">

                            <i class="fa fa-box fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <div class="card-header bg-white border-0 p-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">

                        Orders & Earnings

                    </h5>

                    <p class="text-muted small mb-0">

                        All orders associated with your products

                    </p>

                </div>

            </div>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="bg-light">

                    <tr class="text-uppercase small fw-bold text-muted">

                        <th class="ps-4 py-3">
                            Order
                        </th>

                        <th>
                            Customer
                        </th>

                        <th>
                            Product
                        </th>

                        <th>
                            Qty
                        </th>

                        <th>
                            Price
                        </th>

                        <th>
                            Earnings
                        </th>

                        <th>
                            Payment
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Date
                        </th>

                        <th class="text-end pe-4">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($orderItems as $item)

                        <tr>

                            <td class="ps-4">

                                <div class="fw-bold">

                                    {{ $item->order->unique_order_id ?? '-' }}

                                </div>

                            </td>

                            <td>

                                <div class="fw-semibold">

                                    {{ $item->order->customer->name ?? '-' }}

                                </div>

                                <small class="text-muted">

                                    {{ $item->order->customer->email ?? '-' }}

                                </small>

                            </td>

                            <td>

                                <div class="d-flex align-items-center">

                                    <div class="rounded-3 me-3 overflow-hidden border"
                                         style="width:55px;height:55px;">

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

                                <span class="badge bg-soft-primary rounded-pill px-3">

                                    {{ strtoupper($item->order->payment_method ?? 'COD') }}

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

                                @elseif($item->order->status == 'processing')

                                    <span class="badge bg-primary rounded-pill px-3">

                                        Processing

                                    </span>

                                @elseif($item->order->status == 'shipped')

                                    <span class="badge bg-info rounded-pill px-3">

                                        Shipped

                                    </span>

                                @elseif($item->order->status == 'delivered')

                                    <span class="badge bg-success rounded-pill px-3">

                                        Delivered

                                    </span>

                                @elseif($item->order->status == 'cancelled')

                                    <span class="badge bg-soft-danger rounded-pill px-3">

                                        Cancelled

                                    </span>

                                @else

                                    <span class="badge bg-secondary rounded-pill px-3">

                                        {{ ucfirst($item->order->status) }}

                                    </span>

                                @endif

                            </td>

                            <td>

                                {{ $item->created_at->format('d M Y') }}

                            </td>

                            <td class="text-end pe-4">

    <div class="dropdown">

        <button class="btn btn-light border shadow-sm rounded-pill px-3"
                type="button"
                data-bs-toggle="dropdown">

            <i class="fas fa-edit me-1"></i>
            Edit Status

        </button>

        <div class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4 p-2">

            <div class="px-3 py-2 border-bottom mb-2">

                <h6 class="mb-0 fw-bold">
                    Update Order Status
                </h6>

                <small class="text-muted">
                    Change delivery progress
                </small>

            </div>

            <form action="{{ route('vendor.orders.update.status', $item->order->id) }}"
                  method="POST">

                @csrf

                <input type="hidden"
                       name="status"
                       value="pending">

                <button type="submit"
                        class="dropdown-item d-flex align-items-center justify-content-between rounded-3 py-2 mb-1">

                    <span>
                        ⏳ Pending
                    </span>

                    @if($item->order->status == 'pending')
                        <i class="fas fa-check text-success"></i>
                    @endif

                </button>

            </form>

            <form action="{{ route('vendor.orders.update.status', $item->order->id) }}"
                  method="POST">

                @csrf

                <input type="hidden"
                       name="status"
                       value="processing">

                <button type="submit"
                        class="dropdown-item d-flex align-items-center justify-content-between rounded-3 py-2 mb-1">

                    <span>
                        🔄 Processing
                    </span>

                    @if($item->order->status == 'processing')
                        <i class="fas fa-check text-success"></i>
                    @endif

                </button>

            </form>

            <form action="{{ route('vendor.orders.update.status', $item->order->id) }}"
                  method="POST">

                @csrf

                <input type="hidden"
                       name="status"
                       value="paid">

                <button type="submit"
                        class="dropdown-item d-flex align-items-center justify-content-between rounded-3 py-2 mb-1">

                    <span>
                        💳 Paid
                    </span>

                    @if($item->order->status == 'paid')
                        <i class="fas fa-check text-success"></i>
                    @endif

                </button>

            </form>

            <form action="{{ route('vendor.orders.update.status', $item->order->id) }}"
                  method="POST">

                @csrf

                <input type="hidden"
                       name="status"
                       value="shipped">

                <button type="submit"
                        class="dropdown-item d-flex align-items-center justify-content-between rounded-3 py-2 mb-1">

                    <span>
                        📦 Shipped
                    </span>

                    @if($item->order->status == 'shipped')
                        <i class="fas fa-check text-success"></i>
                    @endif

                </button>

            </form>

            <form action="{{ route('vendor.orders.update.status', $item->order->id) }}"
                  method="POST">

                @csrf

                <input type="hidden"
                       name="status"
                       value="delivered">

                <button type="submit"
                        class="dropdown-item d-flex align-items-center justify-content-between rounded-3 py-2 mb-1">

                    <span>
                        ✅ Delivered
                    </span>

                    @if($item->order->status == 'delivered')
                        <i class="fas fa-check text-success"></i>
                    @endif

                </button>

            </form>

            <form action="{{ route('vendor.orders.update.status', $item->order->id) }}"
                  method="POST">

                @csrf

                <input type="hidden"
                       name="status"
                       value="cancelled">

                <button type="submit"
                        class="dropdown-item d-flex align-items-center justify-content-between rounded-3 py-2 text-danger">

                    <span>
                        ❌ Cancelled
                    </span>

                    @if($item->order->status == 'cancelled')
                        <i class="fas fa-check text-success"></i>
                    @endif

                </button>

            </form>

        </div>

    </div>

</td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="10"
                                class="text-center py-5">

                                <i class="bi bi-wallet2 fs-1 text-muted d-block mb-3"></i>

                                <h5 class="text-muted">

                                    No Orders Or Earnings Found

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