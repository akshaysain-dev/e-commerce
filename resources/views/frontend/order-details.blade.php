@extends('layouts.frontend')

@section('title', 'Order Details - #' . $order->unique_order_id)

@section('content')
<div class="container py-5">
    <!-- Breadcrumb & Back Button -->
    <nav aria-label="breadcrumb" class="mb-4">
        <a href="{{ route('customer.orders') }}" class="text-decoration-none text-muted small">
            ← Back to My Orders
        </a>
    </nav>

    <div class="row g-4">
	@if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
        <!-- Left Column: Order Items -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold">Items in Order</h5>
                </div>
                <div class="card-body p-0">
                    @foreach($order->orderItems as $item)

                    @php
                        $alreadyReviewed = \App\Models\Rating::where('product_id', $item->product_id)
                            ->where('customer_id', session('customer_id'))
                            ->exists();
                    @endphp

                    <div class="p-3 {{ !$loop->last ? 'border-bottom' : '' }}">

                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                alt="{{ $item->product->name }}" 
                                class="rounded-3" style="width: 80px; height: 80px; object-fit: cover;">
                            
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold">{{ $item->product->name }}</h6>
                                <p class="small text-muted mb-0">
                                    @php $attrValue = $item->variant->attributeValues->first(); @endphp
                                    @if($attrValue)
                                        <span class="badge bg-light text-dark border fw-normal">
                                            {{ $attrValue->attribute->name }}: {{ $item->variant->name }}
                                        </span>
                                    @endif
                                    <span class="ms-2">Qty: {{ $item->quantity }}</span>
                                </p>
                            </div>

                            <div class="text-end">
                                <span class="fw-bold text-primary">₹{{ number_format($item->price * $item->quantity, 2) }}</span>
                                <br>
                                <small class="text-muted">₹{{ number_format($item->price, 2) }} each</small>
                            </div>
                        </div>

                        {{-- ⭐ REVIEW SECTION --}}
                        @if($order->status === 'delivered')
                        <div class="mt-2">

                            @if(!$alreadyReviewed)
                                <button class="btn btn-sm btn-outline-primary"
                                    onclick="toggleReviewForm({{ $item->id }})">
                                    ⭐ Rate Product
                                </button>
                            @else
                                <span class="badge bg-success">✔ Already Reviewed</span>
                            @endif

                        </div>

                        {{-- REVIEW FORM --}}
                        @if(!$alreadyReviewed)
                        <div id="review-form-{{ $item->id }}" class="mt-3" style="display:none;">

                            <div class="card border rounded-3 p-3">

                                <form action="{{ route('ratings.store', $item->product_id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    {{-- Rating --}}
                                    <div class="mb-2">
                                        <label>Rating</label>
                                        <select name="rating" class="form-control" required>
                                            <option value="">Select</option>
                                            <option value="5">5 ⭐</option>
                                            <option value="4">4 ⭐</option>
                                            <option value="3">3 ⭐</option>
                                            <option value="2">2 ⭐</option>
                                            <option value="1">1 ⭐</option>
                                        </select>
                                    </div>

                                    {{-- Title --}}
                                    <input type="text" name="title" class="form-control mb-2" placeholder="Review title">

                                    {{-- Review --}}
                                    <textarea name="review" class="form-control mb-2" placeholder="Write review"></textarea>

                                    {{-- Images --}}
                                    <input type="file" name="images[]" class="form-control mb-2" multiple>

                                    <button class="btn btn-success btn-sm">Submit</button>

                                </form>

                            </div>

                        </div>
                        @endif

                        @endif

                    </div>

                    @endforeach
                </div>
            </div>

            <!-- Shipping Information Card -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Shipping Address</h6>
                    <p class="text-muted small mb-0">
                        <i class="bi bi-geo-alt me-2"></i>{{ $order->address ?? 'No address provided' }}
                    </p>
                </div>
            </div>
        </div>

        

        <!-- Right Column: Order Summary & Actions -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <small class="text-muted text-uppercase d-block mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Status</small>
                            <span class="badge rounded-pill 
                                @if($order->status == 'completed') bg-success 
                                @elseif($order->status == 'cancelled') bg-danger 
                                @else bg-warning text-dark @endif py-2 px-3">
                                {{ strtoupper(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </div>
                        <div class="text-end">
                            <small class="text-muted text-uppercase d-block mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Order ID</small>
                            <span class="fw-bold">#{{ $order->unique_order_id }}</span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="fw-bold mb-3">Payment Summary</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Subtotal</span>
                        <span class="small">₹{{ number_format($order->total_amount + ($order->discount_amount ?? 0), 2) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span class="small">Discount ({{ $order->coupon_code }})</span>
                        <span class="small">- ₹{{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                    @endif
					
					<div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Payment method</span>
                        <span class="small">{{ $order->payment_method }}</span>
                    </div>
					
                    <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                        <span class="fw-bold">Total Amount</span>
                        <span class="fw-bold text-primary fs-5">₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
					@if($order->refunded_amount)
					<div class="d-flex justify-content-between mt-3 pt-3 border-top">
                        <span class="fw-bold">Refund Amount</span>
                        <span class="fw-bold text-primary fs-5">₹{{ number_format($order->refunded_amount, 2) }}</span>
                    </div>
					@endif
					
                    <div class="mt-4 d-grid gap-2">
                        <!-- Only show Cancel button if status is pending -->
                        @if($order->status === 'pending')
                        <form action="{{ route('order.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                            @csrf
							<input type="hidden" name="payment_method" value="{{ $order->payment_method }}" />
                            <button type="submit" class="btn btn-outline-danger w-100 py-2 rounded-3">
                                <i class="bi bi-x-circle me-2"></i>Cancel Order
                            </button>
                        </form>
						@elseif($order->status === 'paid')
						<form action="{{ route('order.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                            @csrf
							<input type="hidden" name="payment_method" value="{{ $order->payment_method }}" />
                            <button type="submit" class="btn btn-outline-danger w-100 py-2 rounded-3">
                                <i class="bi bi-x-circle me-2"></i>Cancel Order & Refund Payment
                            </button>
							<span>If order status is Pending your Refund Amount is {{ number_format($order->total_amount, 2) }}. Else your Refund Amount is {{ number_format(($order->total_amount)*0.97, 2) }} </span>
                        </form>
                        @endif
                        
						@if($order->status === 'delivered')
							{{-- Check if the order was delivered less than 7 days ago --}}
							@if($order->created_at->addDays(7)->isFuture())
								
									<button type="submit" class="btn btn-outline-danger w-100 py-2 rounded-3" data-bs-toggle="modal" data-bs-target="#returnModal{{ $order->id }}">
										<i class="bi bi-arrow-left-circle me-2"></i>Return Order
									</button>
									<small class="text-muted d-block text-center mt-1" style="font-size: 0.7rem;">
										Return available until 
										@php
											$deadline = $order->created_at->addDays(7);
										@endphp

										@if($deadline->isToday())
											<strong>Today</strong> ({{ $deadline->format('d M Y') }})
										@elseif($deadline->isTomorrow())
											<strong>Tomorrow</strong> ({{ $deadline->format('d M Y') }})
										@else
											{{ $deadline->format('d M Y') }}
										@endif
									</small>
								
							@else
								<div class="alert alert-light border-0 small text-muted py-2 mb-2" >
									<i class="bi bi-info-circle me-1"></i> Return period expired
								</div>
							@endif

							<a href="{{ route('customer.invoice.download', $order->id) }}" class="btn btn-secondary">
								Download Invoice
							</a>
						@endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Return Order Modal -->
<div class="modal fade" id="returnModal{{ $order->id }}" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="returnModalLabel">Return Request - Order #{{ $order->unique_order_id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('order.return', $order->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
					<input type="hidden" name="refund_amount" value="{{ number_format(($order->total_amount)*0.97, 2) }}">
                    
                    <div class="mb-3">
                        <label class="form-label">Reason for Return</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Why are you returning this?" required></textarea>
                    </div>

                    <hr>
                    <h6 class="text-muted mb-3">Bank Details for Refund</h6>
                    
                    <div class="mb-3">
                        <input type="text" name="bank_name" class="form-control" placeholder="Bank Name" required>
                    </div>
                    
                    <div class="mb-3">
                        <input type="text" name="account_holder_name" class="form-control" placeholder="Account Holder Name" required>
                    </div>

                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <input type="text" name="account_number" class="form-control" placeholder="Account Number" required>
                        </div>
                        <div class="col-md-5 mb-3">
                            <input type="text" name="ifsc_code" class="form-control" placeholder="IFSC Code" required>
                        </div>
                    </div>

                    <div class="alert alert-info py-2" style="font-size: 0.85rem;">
                        <i class="bi bi-info-circle"></i> Funds will be transferred to this account once the return is approved.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit Return Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function toggleReviewForm(id) {
    let el = document.getElementById('review-form-' + id);

    if (el.style.display === 'none') {
        el.style.display = 'block';
    } else {
        el.style.display = 'none';
    }
}
</script>
@endpush