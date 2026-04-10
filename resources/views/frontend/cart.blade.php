@extends('layouts.frontend')

@section('title', 'Cart')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 fw-bold">Your Shopping Cart</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($cartItems->isEmpty())
        <div class="text-center py-5 border rounded bg-light">
            <i class="fa fa-shopping-cart fa-3x text-muted mb-3"></i>
            <p class="lead">Your cart is currently empty.</p>
            <a href="{{ route('all_products') }}" class="btn btn-primary px-4">Continue Shopping</a>
        </div>
    @else
        <div class="row">

            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-3">Product</th>
                                    <th>Variant</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($itemsWithSale as $row)
                                @php $item = $row['item']; @endphp
                                <tr>
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/'.$item->product->image) }}" width="60" class="rounded me-3">
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $item->product->name }}</h6>
                                                <small class="text-muted">SKU: {{ $item->variant->sku }}</small>
                                                @if($row['has_sale'])
                                                    <div>
                                                        <span style="background:#ef4444; color:#fff; font-size:.65rem; font-weight:700; padding:1px 7px; border-radius:4px;">
                                                            🔥 {{ $row['sale']->discount_label }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php $attrValue = $item->variant->attributeValues->first(); @endphp
                                        @if($attrValue && $attrValue->attribute)
                                            <span class="text-muted small">{{ $attrValue->attribute->name }}:</span>
                                        @endif
                                        <span class="badge bg-dark text-uppercase">{{ $item->variant->name }}</span>
                                    </td>
                                    <td>
                                        @if($row['has_sale'])
                                            <div class="fw-bold" style="color:#ef4444;">
                                                ₹{{ number_format($row['discounted_price'], 2) }}
                                            </div>
                                            <div class="text-muted text-decoration-line-through" style="font-size:.8rem;">
                                                ₹{{ number_format($row['original_price'], 2) }}
                                            </div>
                                        @else
                                            ₹{{ number_format($row['original_price'], 2) }}
                                        @endif
                                    </td>
                                    <td style="width:140px;">
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $item->quantity }}"
                                                   min="1" max="{{ $item->variant->stock }}"
                                                   class="form-control form-control-sm me-1" style="width:65px;">
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                                        </form>
                                        <small class="text-muted" style="font-size:.7rem;">Stock: {{ $item->variant->stock }}</small>
                                    </td>
                                    <td class="fw-bold">
                                        @if($row['has_sale'])
                                            <span style="color:#ef4444;">₹{{ number_format($row['item_discounted'], 2) }}</span>
                                            <div class="text-muted text-decoration-line-through" style="font-size:.78rem;">
                                                ₹{{ number_format($row['item_original'], 2) }}
                                            </div>
                                        @else
                                            ₹{{ number_format($row['item_original'], 2) }}
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Remove this item?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger border-0">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4">
                    <h5 class="fw-bold mb-3">Order Summary</h5>

                    <!-- Subtotal -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>₹{{ number_format($originalTotal, 2) }}</span>
                    </div>

                    <!-- Discount -->
                    @if($totalSaved > 0)
                    <div class="d-flex justify-content-between mb-2 text-danger fw-semibold">
                        <span>🔥 Sale Discount</span>
                        <span>−₹{{ number_format($totalSaved, 2) }}</span>
                    </div>
                    @endif

                    <!-- After Discount -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>After Discount</span>
                        <span>₹{{ number_format($grandTotal, 2) }}</span>
                    </div>

                    <!-- Tax -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (18%)</span>
                        <span>₹{{ number_format($tax, 2) }}</span>
                    </div>

                    <!-- Shipping -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <span class="{{ $shipping == 0 ? 'text-success fw-semibold' : '' }}">
                            {{ $shipping == 0 ? 'Free' : '₹'.number_format($shipping,2) }}
                        </span>
                    </div>

                    <hr>

                    <!-- Final Total -->
                    <div class="d-flex justify-content-between mb-3">
                        <span class="h5 fw-bold">Total</span>
                        <span class="h5 fw-bold text-danger">
                            ₹{{ number_format($finalTotal, 2) }}
                        </span>
                    </div>

                    <!-- Savings Message -->
                    @if($totalSaved > 0)
                    <div class="alert py-2 text-center mb-3"
                        style="font-size:.85rem; background:#d1fae5; border:none; color:#065f46;">
                        🎉 You're saving <strong>₹{{ number_format($totalSaved, 2) }}</strong> on this order!
                    </div>
                    @endif

                    <!-- Free Shipping Hint -->
                    @if($shipping > 0)
                    <div class="text-center mb-3 small text-muted">
                        Add ₹{{ number_format(1000 - $grandTotal, 2) }} more for <strong>FREE shipping</strong>
                    </div>
                    @endif

                    <!-- Checkout Button -->
                    <a href="{{ route('checkout.index') }}"
                    class="btn btn-primary btn-lg w-100 py-3 fw-bold shadow-sm">
                        Proceed to Checkout
                    </a>
                </div>
            </div>

        </div>
    @endif
</div>
@endsection