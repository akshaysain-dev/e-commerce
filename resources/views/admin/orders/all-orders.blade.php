@extends('layouts.backend')

@section('title', 'Manage Orders')

@section('styles')
<style>
    .modern-pagination .pagination { margin-bottom: 0; gap: 6px; }
    .modern-pagination .page-item .page-link {
        border: none; width: 40px; height: 40px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50% !important; color: #555; font-weight: 600; transition: all 0.3s ease;
    }
    .modern-pagination .page-item.active .page-link {
        background-color: #0d6efd; color: white; box-shadow: 0 4px 12px rgba(13,110,253,0.3);
    }
    .modern-pagination .page-item .page-link:hover:not(.active) {
        background-color: #f0f2f5; color: #0d6efd; transform: translateY(-2px);
    }
    .info-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 14px 16px;
    }
    .info-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #6c757d;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .info-value {
        font-size: 0.88rem;
        font-weight: 600;
        color: #212529;
    }
    .card-chip {
        background: linear-gradient(135deg, #1a1a2e, #16213e);
        color: white;
        border-radius: 12px;
        padding: 16px 20px;
    }
    .timeline-dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        background: #0d6efd;
        flex-shrink: 0;
        margin-top: 4px;
    }
    .section-divider {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #adb5bd;
        font-weight: 700;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 6px;
        margin-bottom: 12px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Order Management</h2>
            <small class="text-muted">View, filter, and update all customer orders</small>
        </div>
        <span class="badge bg-warning text-dark px-3 py-2 rounded-3 fw-semibold">
            {{ $pendingCount }} Pending
        </span>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filter Tabs --}}
    <div class="d-flex gap-2 flex-wrap mb-4">
        <a href="{{ route('admin.orders') }}" class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-outline-secondary' }}">
            All <span class="badge bg-secondary ms-1">{{ $totalCount }}</span>
        </a>
        <a href="{{ route('admin.orders', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') == 'pending' ? 'btn-warning text-dark' : 'btn-outline-warning' }}">⏳ Pending</a>
        <a href="{{ route('admin.orders', ['status' => 'paid']) }}" class="btn btn-sm {{ request('status') == 'paid' ? 'btn-success' : 'btn-outline-success' }}">💳 Paid</a>
        <a href="{{ route('admin.orders', ['status' => 'processing']) }}" class="btn btn-sm {{ request('status') == 'processing' ? 'btn-info text-white' : 'btn-outline-info' }}">🔄 Processing</a>
        <a href="{{ route('admin.orders', ['status' => 'shipped']) }}" class="btn btn-sm {{ request('status') == 'shipped' ? 'btn-primary' : 'btn-outline-primary' }}">📦 Shipped</a>
        <a href="{{ route('admin.orders', ['status' => 'delivered']) }}" class="btn btn-sm {{ request('status') == 'delivered' ? 'btn-success' : 'btn-outline-success' }}">✅ Delivered</a>
        <a href="{{ route('admin.orders', ['status' => 'cancelled']) }}" class="btn btn-sm {{ request('status') == 'cancelled' ? 'btn-danger' : 'btn-outline-danger' }}">❌ Cancelled</a>
    </div>

    {{-- Orders Table --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="pe-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    @php
                        $statusConfig = [
                            'pending'    => ['bg-warning',  'text-dark',  '⏳'],
                            'paid'       => ['bg-success',  'text-white', '💳'],
                            'processing' => ['bg-info',     'text-white', '🔄'],
                            'shipped'    => ['bg-primary',  'text-white', '📦'],
                            'delivered'  => ['bg-success',  'text-white', '✅'],
                            'cancelled'  => ['bg-danger',   'text-white', '❌'],
                        ];
                        $cfg = $statusConfig[$order->status] ?? ['bg-secondary', 'text-white', ''];
                    @endphp
                    <tr>
                        <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                        <td><span class="fw-bold small">#{{ $order->unique_order_id }}</span></td>
                        <td>
                            <div class="fw-semibold small">{{ $order->customer->name ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $order->customer->email ?? '' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $order->orderItems->count() }} item(s)</span>
                        </td>
                        <td class="fw-bold text-primary small">₹{{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            <span class="badge bg-light text-dark border small">{{ $order->payment_method }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $cfg[0] }} {{ $cfg[1] }} rounded-pill">
                                {{ $cfg[2] }} {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="small text-muted">
                            {{ $order->created_at->format('d M Y') }}<br>
                            <small>{{ $order->created_at->format('h:i A') }}</small>
                        </td>
                        <td class="pe-4 text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <button class="btn btn-sm btn-light rounded-pill px-2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#orderModal{{ $order->id }}">
                                    🔍
                                </button>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light rounded-pill px-2 dropdown-toggle" data-bs-toggle="dropdown">✏️</button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 p-2">
                                        <li><span class="dropdown-header small">Update Status</span></li>
                                        @foreach(['pending' => '⏳ Pending', 'paid' => '💳 Paid', 'processing' => '🔄 Processing', 'shipped' => '📦 Shipped', 'delivered' => '✅ Delivered', 'cancelled' => '❌ Cancelled'] as $val => $label)
                                        <li>
                                            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="{{ $val }}">
                                                <button type="submit" class="dropdown-item rounded-2 small {{ $order->status == $val ? 'fw-bold' : '' }}">
                                                    {{ $label }}
                                                </button>
                                            </form>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>

                    {{-- ===== FULL DETAIL MODAL ===== --}}
                    <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1">
                        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content border-0 rounded-4 shadow-lg">

                                {{-- Modal Header --}}
                                <div class="modal-header px-4 pt-4 pb-3 border-0">
                                    <div>
                                        <h5 class="modal-title fw-bold mb-0">Order Details</h5>
                                        <small class="text-muted">#{{ $order->unique_order_id }} &nbsp;·&nbsp; {{ $order->created_at->format('d M Y, h:i A') }}</small>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body px-4 pb-4">
                                    <div class="row g-4">

                                        {{-- LEFT COLUMN --}}
                                        <div class="col-lg-7">

                                            {{-- Customer Info --}}
                                            <p class="section-divider">👤 Customer Information</p>
                                            <div class="row g-2 mb-4">
                                                <div class="col-sm-6">
                                                    <div class="info-card">
                                                        <div class="info-label">Full Name</div>
                                                        <div class="info-value">{{ $order->customer->name ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="info-card">
                                                        <div class="info-label">Email</div>
                                                        <div class="info-value text-truncate">{{ $order->customer->email ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="info-card">
                                                        <div class="info-label">📍 Shipping Address</div>
                                                        <div class="info-value fw-normal">{{ $order->address ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Ordered Items --}}
                                            <p class="section-divider">🛒 Ordered Items</p>
                                            @foreach($order->orderItems as $item)
                                            <div class="d-flex align-items-center gap-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                                     class="rounded-3 flex-shrink-0"
                                                     style="width:55px;height:55px;object-fit:cover;border:1px solid #e5e7eb;"
                                                     alt="{{ $item->product->name }}">
                                                <div class="flex-grow-1">
                                                    <div class="fw-semibold small">{{ $item->product->name }}</div>
                                                    <small class="text-muted">
                                                        @php $attr = $item->variant->attributeValues->first(); @endphp
                                                        @if($attr)
                                                            <strong>{{ $attr->attribute->name }}</strong>: {{ $item->variant->name }} &nbsp;·&nbsp;
                                                        @endif
                                                        Qty: <strong>{{ $item->quantity }}</strong>
                                                        &nbsp;·&nbsp; ₹{{ number_format($item->price, 2) }} each
                                                    </small>
                                                </div>
                                                <div class="fw-bold small text-primary text-nowrap">
                                                    ₹{{ number_format($item->price * $item->quantity, 2) }}
                                                </div>
                                            </div>
                                            @endforeach

                                            {{-- Grand Total --}}
                                            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                                <span class="fw-bold">Grand Total</span>
                                                <span class="fw-bold fs-5 text-primary">₹{{ number_format($order->total_amount, 2) }}</span>
                                            </div>
                                        </div>

                                        {{-- RIGHT COLUMN --}}
                                        <div class="col-lg-5">

                                            {{-- Order Status --}}
                                            <p class="section-divider">📋 Order Status</p>
                                            <div class="row g-2 mb-4">
                                                <div class="col-6">
                                                    <div class="info-card text-center">
                                                        <div class="info-label">Status</div>
                                                        <span class="badge {{ $cfg[0] }} {{ $cfg[1] }} rounded-pill mt-1">
                                                            {{ $cfg[2] }} {{ ucfirst($order->status) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="info-card text-center">
                                                        <div class="info-label">Order Date</div>
                                                        <div class="info-value" style="font-size:0.8rem;">{{ $order->created_at->format('d M Y') }}</div>
                                                        <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="info-card text-center">
                                                        <div class="info-label">Last Updated</div>
                                                        <div class="info-value" style="font-size:0.8rem;">{{ $order->updated_at->format('d M Y') }}</div>
                                                        <small class="text-muted">{{ $order->updated_at->format('h:i A') }}</small>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="info-card text-center">
                                                        <div class="info-label">Items Count</div>
                                                        <div class="info-value">{{ $order->orderItems->count() }} item(s)</div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Payment Info --}}
                                            <p class="section-divider">💳 Payment Information</p>

                                            @if($order->payment_method === 'STRIPE')
                                                {{-- Card Visual --}}
                                                <div class="card-chip mb-3">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <span style="font-size:0.7rem; opacity:0.7; letter-spacing:0.08em;">STRIPE PAYMENT</span>
                                                        <span style="font-size:1.2rem;">💳</span>
                                                    </div>
                                                    <div style="font-size:1rem; letter-spacing:0.2em; font-weight:600; margin-bottom:12px;">
                                                        @if($order->card_last_four)
                                                            •••• &nbsp; •••• &nbsp; •••• &nbsp; {{ $order->card_last_four }}
                                                        @else
                                                            •••• &nbsp; •••• &nbsp; •••• &nbsp; ••••
                                                        @endif
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-end">
                                                        <div>
                                                            <div style="font-size:0.6rem; opacity:0.6; letter-spacing:0.05em;">CARD HOLDER</div>
                                                            <div style="font-size:0.85rem; font-weight:600;">{{ $order->card_holder_name ?? 'N/A' }}</div>
                                                        </div>
                                                        <div class="text-end">
                                                            <div style="font-size:0.6rem; opacity:0.6; letter-spacing:0.05em;">AMOUNT PAID</div>
                                                            <div style="font-size:0.85rem; font-weight:600;">₹{{ number_format($order->total_amount, 2) }}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Transaction ID --}}
                                                @if($order->transaction_id)
                                                <div class="info-card mb-2">
                                                    <div class="info-label">Transaction ID</div>
                                                    <div class="info-value text-break" style="font-size:0.78rem; font-family: monospace;">
                                                        {{ $order->transaction_id }}
                                                    </div>
                                                </div>
                                                @endif

                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="info-card text-center">
                                                            <div class="info-label">Payment Method</div>
                                                            <div class="info-value">STRIPE</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="info-card text-center">
                                                            <div class="info-label">Payment Status</div>
                                                            <span class="badge bg-success text-white rounded-pill">✅ Paid</span>
                                                        </div>
                                                    </div>
                                                </div>

                                            @elseif($order->payment_method === 'PAYPAL')
                                                {{-- Card Visual --}}
                                                <div class="card-chip mb-3">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <span style="font-size:0.7rem; opacity:0.7; letter-spacing:0.08em;">STRIPE PAYMENT</span>
                                                        <span style="font-size:1.2rem;">💳</span>
                                                    </div>
                                                    <div style="font-size:1rem; letter-spacing:0.2em; font-weight:600; margin-bottom:12px;">
                                                        @if($order->card_last_four)
                                                            •••• &nbsp; •••• &nbsp; •••• &nbsp; {{ $order->card_last_four }}
                                                        @else
                                                            •••• &nbsp; •••• &nbsp; •••• &nbsp; 1111
                                                        @endif
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-end">
                                                        <div>
                                                            <div style="font-size:0.6rem; opacity:0.6; letter-spacing:0.05em;">CARD HOLDER</div>
                                                            <div style="font-size:0.85rem; font-weight:600;">{{ $order->card_holder_name ?? 'N/A' }}</div>
                                                        </div>
                                                        <div class="text-end">
                                                            <div style="font-size:0.6rem; opacity:0.6; letter-spacing:0.05em;">AMOUNT PAID</div>
                                                            <div style="font-size:0.85rem; font-weight:600;">₹{{ number_format($order->total_amount, 2) }}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Transaction ID --}}
                                                @if($order->transaction_id)
                                                <div class="info-card mb-2">
                                                    <div class="info-label">Transaction ID</div>
                                                    <div class="info-value text-break" style="font-size:0.78rem; font-family: monospace;">
                                                        {{ $order->transaction_id }}
                                                    </div>
                                                </div>
                                                @endif

                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="info-card text-center">
                                                            <div class="info-label">Payment Method</div>
                                                            <div class="info-value">PAYPAL</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="info-card text-center">
                                                            <div class="info-label">Payment Status</div>
                                                            <span class="badge bg-success text-white rounded-pill">✅ Paid</span>
                                                        </div>
                                                    </div>
                                                </div>


                                            @else
                                                {{-- COD --}}
                                                <div class="info-card text-center mb-2" style="background: #fff3cd;">
                                                    <div style="font-size:2rem;">🚚</div>
                                                    <div class="fw-bold mt-1">Cash on Delivery</div>
                                                    <small class="text-muted">Payment collected at doorstep</small>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="info-card text-center">
                                                            <div class="info-label">Payment Method</div>
                                                            <div class="info-value">COD</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="info-card text-center">
                                                            <div class="info-label">Payment Status</div>
                                                            <span class="badge bg-warning text-dark rounded-pill">⏳ Pending</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer px-4 pb-4 border-0">
                                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Close</button>
                                </div>

                            </div>
                        </div>
                    </div>
                    {{-- ===== END MODAL ===== --}}

                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <div style="font-size:3rem;">🧾</div>
                            <div class="mt-2">No orders found.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($orders->hasPages())
            <div class="d-flex justify-content-center align-items-center py-5">
                <nav class="modern-pagination shadow-sm p-2 bg-white rounded-pill border">
                    {{ $orders->links() }}
                </nav>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection