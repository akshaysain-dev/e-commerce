@extends('layouts.frontend')

@section('title', 'My Orders')

@section('styles')
<style>
    body { background: #f8f7f4; }

    .order-card {
        border: 1.5px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        transition: box-shadow 0.2s, transform 0.2s;
        background: #fff;
    }
    .order-card:hover {
        box-shadow: 0 8px 32px rgba(0,0,0,0.09);
        transform: translateY(-2px);
    }
    .order-header {
        background: #f9fafb;
        border-bottom: 1.5px solid #e5e7eb;
        padding: 0.9rem 1.25rem;
    }
    .product-thumb {
        width: 56px;
        height: 56px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
    }
    .badge-pending    { background: #fef9c3; color: #854d0e; }
    .badge-on_the_way { background: #dbeafe; color: #1e40af; }
    .badge-completed  { background: #dcfce7; color: #166534; }
    .badge-cancel     { background: #fee2e2; color: #991b1b; }
    .status-badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.3rem 0.75rem;
        border-radius: 999px;
        text-transform: capitalize;
        letter-spacing: 0.03em;
    }
    .empty-icon {
        font-size: 5rem;
        opacity: 0.25;
    }
	a{
		text-decoration: none;
	}
</style>
@endsection

@section('content')
<div class="container py-5">
    
    {{-- Page Title --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="fw-bold fs-3 mb-0">My Orders</h1>
            <small class="text-muted">Track and manage your purchases</small>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-dark rounded-3 btn-sm fw-semibold">
            + Continue Shopping
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($orders->isEmpty())
        {{-- Empty State --}}
        <div class="text-center py-5 my-5">
            <div class="empty-icon">🛍️</div>
            <h4 class="fw-bold mt-3 mb-1">No orders yet</h4>
            <p class="text-muted mb-4">Looks like you haven't placed any orders. Start shopping!</p>
            <a href="{{ route('home') }}" class="btn btn-dark rounded-3 px-4 fw-semibold">Browse Products</a>
        </div>

    @else
        <div class="d-flex flex-column gap-3">
            @foreach($orders as $order)
            <div class="order-card">
			<a href = "{{ route('order', $order->id) }}">
                {{-- Order Header --}}
                <div class="order-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div>
                            <small class="text-muted d-block" style="font-size:0.7rem; letter-spacing:0.06em; text-transform:uppercase;">Order ID</small>
                            <span class="fw-bold text-dark small">#{{ $order->unique_order_id }}</span>
                        </div>
                        <div class="vr d-none d-sm-block"></div>
                        <div>
                            <small class="text-muted d-block" style="font-size:0.7rem; letter-spacing:0.06em; text-transform:uppercase;">Placed On</small>
                            <span class="small fw-medium">{{ $order->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="vr d-none d-sm-block"></div>
                        <div>
                            <small class="text-muted d-block" style="font-size:0.7rem; letter-spacing:0.06em; text-transform:uppercase;">Total</small>
                            <span class="small fw-bold text-primary">₹{{ number_format($order->total_amount, 2) }}</span>
                        </div>
						@if($order->coupon_code != null)
						<div class="vr d-none d-sm-block"></div>
						<div>
                            <small class="text-muted d-block" style="font-size:0.7rem; letter-spacing:0.06em; text-transform:uppercase;">Applied Coupon</small>
                            <span class="small fw-bold text-primary">{{ $order->coupon_code }}</span>
                        </div>
						<div class="vr d-none d-sm-block"></div>
						<div>
                            <small class="text-muted d-block" style="font-size:0.7rem; letter-spacing:0.06em; text-transform:uppercase;">Discount Amount</small>
                            <span class="small fw-bold text-primary">{{ $order->discount_amount }}</span>
                        </div>
						@endif
                    </div>
                    {{-- Status Badge --}}
                    <span class="status-badge badge-{{ $order->status }}">
                        @php
                            $statusLabels = [
                                'pending'    => '⏳ Pending',
                                'processing' => '🚚 On the Way',
                                'paid'  => '💳 Paid',
                                'cancelled'     => '❌ Cancelled',
								'shipped'   => '📦 Shipped',
								'delivered' => '✅ Delivered'
                            ];
                        @endphp
                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                    </span>
                </div>

                {{-- Order Items --}}
                <div class="p-3">
                    @foreach($order->orderItems as $item)
                    <div class="d-flex align-items-center gap-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <img src="{{ asset('storage/' . $item->product->image) }}"
                             alt="{{ $item->product->name }}"
                             class="product-thumb flex-shrink-0">

                        <div class="flex-grow-1 min-w-0">
                            <p class="mb-0 fw-semibold text-truncate small">{{ $item->product->name }}</p>
                            <small class="text-muted">
                                @php $attrValue = $item->variant->attributeValues->first(); @endphp
                                @if($attrValue)
                                    <strong>{{ $attrValue->attribute->name }}</strong>: {{ $item->variant->name }} &nbsp;·&nbsp;
                                @endif
                                Qty: {{ $item->quantity }}
                            </small>
                        </div>

                        <div class="text-end flex-shrink-0">
                            <span class="fw-bold small text-primary">₹{{ number_format($item->price * $item->quantity, 2) }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Order Footer --}}
                <div class="px-3 pb-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="d-flex align-items-center gap-1 text-muted">
                        <small>📍</small>
                        <small class="text-truncate" style="max-width: 280px;">{{ $order->address ?? 'N/A' }}</small>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-light text-dark border small fw-medium py-2 px-3 rounded-3">
                            💵 {{ $order->payment_method }}
                        </span>
                    </div>
                </div>
			</a>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
        @endif

    @endif
</div>
@endsection