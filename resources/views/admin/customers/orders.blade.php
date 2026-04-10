@extends('layouts.backend')

@section('title', 'Order History: ' . $customer->name)

@section('styles')
<style>
    .customer-card { border: none; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); background: #ffffff; }
    .table thead th { background-color: #f1f4f9; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 1px; font-weight: 700; color: #555; border: none; padding: 15px; }
    .order-id-badge { background: #eef2ff; color: #4f46e5; font-weight: 700; padding: 5px 12px; border-radius: 6px; font-size: 0.8rem; }
    .product-img { width: 40px; height: 40px; object-fit: cover; border-radius: 8px; border: 1px solid #eee; }
    .status-badge { font-size: 0.7rem; font-weight: 700; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- Header -->
   <div class="container-fluid px-4 py-4">

    <div class="card p-3 shadow-sm border-0 mb-4">

        <!-- Top Row -->
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

            <!-- LEFT: Title -->
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin_customers') }}" class="text-decoration-none">Customers</a>
                        </li>
                        <li class="breadcrumb-item active">Order History</li>
                    </ol>
                </nav>

                <h4 class="fw-bold mb-0">Orders for {{ $customer->name }}</h4>
                <small class="text-muted">
                    Viewing {{ $orders->count() }} transaction(s)
                </small>
            </div>

            <!-- RIGHT: Buttons -->
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.customer.orders.export', $customer->id) }}?{{ http_build_query(request()->query()) }}"
                   class="btn btn-success">
                   <i class="bi bi-file-earmark-excel me-1"></i> Export
                </a>

                <a href="{{ route('admin_customers') }}"
                   class="btn btn-outline-secondary">
                   Back
                </a>
            </div>
        </div>

        <!-- FILTER ROW -->
        <form method="GET" class="row g-2 mt-3 align-items-end">

            <div class="col-6 col-md-3 col-lg-2">
                <label class="small text-muted">From</label>
                <input type="date" name="from_date"
                    value="{{ request('from_date') }}"
                    class="form-control">
            </div>

            <div class="col-6 col-md-3 col-lg-2">
                <label class="small text-muted">To</label>
                <input type="date" name="to_date"
                    value="{{ request('to_date') }}"
                    class="form-control">
            </div>

            <div class="col-6 col-md-3 col-lg-2">
                <label class="small text-muted">Status</label>
                <select name="payment_status" class="form-control">
                    <option value="">All</option>
                    <option value="paid" {{ request('payment_status')=='paid'?'selected':'' }}>Paid</option>
                    <option value="pending" {{ request('payment_status')=='pending'?'selected':'' }}>Pending</option>
                    <option value="cancelled" {{ request('payment_status')=='cancelled'?'selected':'' }}>Cancelled</option>
                    <option value="delivered" {{ request('payment_status')=='delivered'?'selected':'' }}>Delivered</option>
                </select>
            </div>

            <div class="col-6 col-md-3 col-lg-2">
                <label class="small text-muted">Order ID</label>
                <input type="text" name="order_id"
                    value="{{ request('order_id') }}"
                    placeholder="#123"
                    class="form-control">
            </div>

            <div class="col-6 col-md-3 col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    Filter
                </button>
                <a href="{{ url()->current() }}" class="btn btn-light w-100 border">
                    Reset
                </a>
            </div>

        </form>

    </div>

</div>

    <!-- Orders Table -->
    <div class="card customer-card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Order ID</th>
                        <th>Items Purchased</th>
                        <th>Order Date</th>
                        <th>Payment Status</th>
                        <th>Total Amount</th>
                        <th class="text-end pe-4">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="ps-4">
                            <span class="order-id-badge">#{{ $order->unique_order_id }}</span>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-2">
                                @foreach($order->orderItems->take(2) as $item)
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/'.$item->product->image) }}" class="product-img me-2" alt="">
                                        <div style="line-height: 1.2;">
                                            <div class="small fw-bold text-dark">{{ $item->product->name }}</div>
                                            <small class="text-muted" style="font-size: 0.65rem;">
                                                Qty: {{ $item->quantity }}
                                                @if($item->variant)
                                                    • {{ $item->variant->attributeValues->pluck('value')->join(', ') }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                @endforeach
                                @if($order->orderItems->count() > 2)
                                    <small class="text-primary fw-bold">+ {{ $order->orderItems->count() - 2 }} more items</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="text-dark small fw-bold">{{ $order->created_at->format('d M, Y') }}</div>
                            <div class="text-muted" style="font-size: 0.7rem;">{{ $order->created_at->format('h:i A') }}</div>
                        </td>
                        <td>
                            <span class="status-badge {{ $order->status == 'paid' || $order->status == 'delivered' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}">
                                {{ $order->status ?? 'Pending' }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">Rs. {{ number_format($order->total_amount, 2) }}</div>
                        </td>
                        <td class="text-end pe-4">
                            <button type="button" class="btn btn-sm btn-light border fw-bold text-muted px-3"
                                data-bs-toggle="modal"
                                data-bs-target="#orderModal{{ $order->id }}">
                                View Full
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-cart-x display-4 d-block mb-3 opacity-25"></i>
                                <h5>No Orders Found</h5>
                                <p class="small">This customer hasn't placed any orders yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ===================== -->
    <!-- Modals (Table ke bahar) -->
    <!-- ===================== -->
    @foreach($orders as $order)
    <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1" aria-labelledby="orderModalLabel{{ $order->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel{{ $order->id }}">
                        Order Details &nbsp;
                        <span class="badge bg-primary">#{{ $order->unique_order_id }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <!-- Order Meta Info -->
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <small class="text-muted d-block">Order Date</small>
                            <strong>{{ $order->created_at->format('d M, Y h:i A') }}</strong>
                        </div>
                        <div class="col-sm-4">
                            <small class="text-muted d-block">Payment Status</small>
                            <span class="badge {{ $order->payment_status == 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ ucfirst($order->status ?? 'Pending') }}
                            </span>
                        </div>
                        <div class="col-sm-4">
                            <small class="text-muted d-block">Total Amount</small>
                            <strong>Rs. {{ number_format($order->total_amount, 2) }}</strong>
                        </div>
                    </div>

                    <hr>

                    <!-- All Order Items -->
                    <h6 class="fw-bold mb-3">Items Purchased ({{ $order->orderItems->count() }})</h6>
                    <table class="table table-bordered table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th class="text-center">Qty</th>
                                <th>Variant</th>
                                <th class="text-end">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ asset('storage/'.$item->product->image) }}"
                                            width="38" height="38"
                                            class="rounded"
                                            style="object-fit:cover; border: 1px solid #eee;"
                                            alt="">
                                        <span class="fw-bold small">{{ $item->product->name }}</span>
                                    </div>
                                </td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td>
                                    @if($item->variant)
                                        <span class="badge bg-secondary">
                                            {{ $item->variant->attributeValues->pluck('value')->join(', ') }}
                                        </span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td class="text-end">Rs. {{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="3" class="text-end fw-bold">Grand Total</td>
                                <td class="text-end fw-bold">Rs. {{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    <!-- End Modals -->

</div>
@endsection