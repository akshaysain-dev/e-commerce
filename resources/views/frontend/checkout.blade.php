@extends('layouts.frontend')

@section('title', 'Order Checkout')

@section('styles')
<style>
    .transition-transform { transition: transform 0.2s ease; }
    .transition-transform:hover { transform: scale(1.02); }
    #new-shipping-address:hover, #old-shipping-address:hover { cursor: pointer; }
    .coupon-box { border: 1.5px dashed #c7d2fe; border-radius: 12px; padding: 16px 18px; background: #fafbff; transition: border-color .25s, background .25s; margin-bottom: 0; }
    .coupon-box.applied { border-color: #6ee7b7; background: #f0fdf9; }
    .coupon-title { font-weight: 700; font-size: .88rem; color: #374151; display: flex; align-items: center; gap: 7px; margin-bottom: 12px; }
    .coupon-tag-icon { width: 26px; height: 26px; background: #eef2ff; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: .85rem; }
    .coupon-input-row { display: flex; gap: 8px; }
    .coupon-input { flex: 1; border: 1.5px solid #d1d5db; border-radius: 8px; padding: 9px 13px; font-size: .88rem; font-family: 'Courier New', monospace; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; background: #fff; transition: border-color .15s, box-shadow .15s; outline: none; }
    .coupon-input:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.1); }
    .coupon-input:disabled { background: #f9fafb; color: #9ca3af; cursor: not-allowed; }
    .btn-coupon-apply { background: #4f46e5; color: #fff; border: none; border-radius: 8px; padding: 9px 18px; font-size: .85rem; font-weight: 700; cursor: pointer; transition: all .2s; white-space: nowrap; min-width: 82px; display: flex; align-items: center; justify-content: center; gap: 5px; }
    .btn-coupon-apply:hover { background: #4338ca; }
    .btn-coupon-apply:disabled { background: #a5b4fc; cursor: not-allowed; }
    .coupon-msg { margin-top: 7px; font-size: .8rem; min-height: 18px; display: flex; align-items: center; gap: 5px; }
    .coupon-msg.msg-error { color: #dc2626; }
    .coupon-msg.msg-success { color: #059669; }
    .coupon-msg.msg-info { color: #6b7280; }
    .coupon-applied-strip { display: none; align-items: center; justify-content: space-between; background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 8px; padding: 9px 13px; margin-top: 9px; font-size: .83rem; }
    .coupon-applied-strip .strip-left { display: flex; align-items: center; gap: 7px; }
    .applied-code { font-family: 'Courier New', monospace; font-weight: 800; color: #065f46; }
    .applied-save { color: #059669; font-weight: 600; }
    .btn-remove-coupon { background: none; border: none; color: #6b7280; cursor: pointer; font-size: .75rem; font-weight: 700; padding: 2px 7px; border-radius: 5px; transition: all .15s; }
    .btn-remove-coupon:hover { background: #fee2e2; color: #dc2626; }
    .spinner-sm { width: 13px; height: 13px; border: 2px solid rgba(255,255,255,.3); border-top-color: #fff; border-radius: 50%; animation: coupon-spin .6s linear infinite; display: inline-block; }
    @keyframes coupon-spin { to { transform: rotate(360deg); } }
    .discount-row { color: #059669; font-weight: 600; }
    .grand-total-val { transition: color .3s; }
</style>
@endsection

@section('content')

@php
    $hasSaleDiscount = isset($saleResult) && $saleResult['has_any_sale'];
    $saleSaved       = $hasSaleDiscount ? $saleResult['saved_amount']  : 0;
    $saleOriginal    = $hasSaleDiscount ? $saleResult['original_total'] : $totalAmount;
@endphp

<div class="container py-5">
    <div class="row g-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="col-md-7 animate__animated animate__fadeInLeft">
            <form action="{{ route('place.order') }}" method="POST" id="checkout-form" class="card p-4 shadow-sm">
                @csrf
                <input type="hidden" name="coupon_code" id="hidden-coupon"      value="{{ session('applied_coupon') ?? '' }}">
                <input type="hidden" name="final_total" id="hidden-final-total" value="{{ $totalAmount }}">

                <h2 class="h4 mb-4 fw-bold border-bottom pb-2">Shipping Details</h2>
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control form-control-lg" value="{{ $customer->name }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ $customer->email }}" readonly>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold mb-3">Shipping Address</label>
                    @if($customer->area || $customer->city)
                    <div class="form-check border p-3 rounded mb-2 address-card shadow-sm active-border" onclick="selectSavedAddress()">
                        <input class="form-check-input ms-0 me-2" type="radio" name="address_type" id="useSaved" value="saved" checked onclick="toggleNewAddress(false)">
                        <label class="form-check-label w-100 cursor-pointer" for="useSaved">
                            <span class="d-block fw-bold mb-1" id="old-shipping-address">Use My Profile Address</span>
                            <div class="text-muted small">
                                <span>{{ $customer->area }},</span> <span>{{ $customer->city }},</span>
                                <span>{{ $customer->state }},</span> <span>{{ $customer->country }} - </span>
                                <span class="fw-medium">{{ $customer->postal_code }}</span>
                            </div>
                        </label>
                    </div>
                    @endif
                    <div class="form-check border p-3 rounded mb-2 address-card shadow-sm" onclick="selectNewAddress()">
                        <input class="form-check-input ms-0 me-2" type="radio" name="address_type" id="addNew" value="new"
                               {{ !($customer->area || $customer->city) ? 'checked' : '' }} onclick="toggleNewAddress(true)">
                        <label class="form-check-label w-100 cursor-pointer" for="addNew">
                            <span class="d-block fw-bold text-primary" id="new-shipping-address">+ Ship to a Different Address</span>
                        </label>
                    </div>
                    <div id="new-address-field" class="mt-3 animate__animated animate__fadeIn"
                         style="display: {{ !($customer->area || $customer->city) ? 'block' : 'none' }};">
                        <label class="form-label small fw-bold">Enter Full Shipping Address</label>
                        <textarea name="address" id="new_address_textarea" class="form-control" rows="3"
                                  placeholder="Street, Building, Landmark, etc."
                                  {{ !($customer->area || $customer->city) ? 'required' : '' }}></textarea>
                    </div>
                </div>

                <h3 class="h5 mt-2 mb-3 fw-semibold">Discount Coupon</h3>
                <div class="coupon-box mb-4" id="coupon-box" style="{{ $hasSaleDiscount ? 'border-color:#fca5a5; background:#fff5f5;' : '' }}">
                    <div class="coupon-title">
                        <span class="coupon-tag-icon">🏷</span> Have a coupon? Apply it here
                    </div>

                    @if($hasSaleDiscount)
                    <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:9px 13px; font-size:.82rem; color:#b91c1c; font-weight:600; margin-bottom:10px;">
                        🔥 Sale discount is already applied. Coupons cannot be combined with sale offers.
                    </div>
                    @endif

                    <div class="coupon-input-row">
                        <input type="text" id="coupon_code_input" class="coupon-input"
                               placeholder="{{ $hasSaleDiscount ? 'Not available during sale' : 'ENTER CODE' }}"
                               maxlength="20"
                               oninput="this.value = this.value.toUpperCase()"
                               value="{{ session('applied_coupon') ?? '' }}"
                               {{ (session('applied_coupon') || $hasSaleDiscount) ? 'disabled' : '' }}>
                        <button type="button" class="btn-coupon-apply" id="apply-btn"
                                onclick="applyCoupon()"
                                {{ $hasSaleDiscount ? 'disabled' : '' }}>Apply</button>
                    </div>
                    <div class="coupon-msg msg-info" id="coupon-message"></div>
                    <div class="coupon-applied-strip" id="applied-strip"
                         style="{{ session('applied_coupon') ? 'display:flex;' : 'display:none;' }}">
                        <div class="strip-left">
                            <span>✅</span>
                            <span class="applied-code" id="strip-code">{{ session('applied_coupon') ?? '' }}</span>
                            <span style="color:#9ca3af;">—</span>
                            <span class="applied-save" id="strip-saving"></span>
                        </div>
                        <button type="button" class="btn-remove-coupon" onclick="removeCoupon()">✕ Remove</button>
                    </div>
                </div>

                <h3 class="h5 mt-2 fw-semibold">Payment Method</h3>
                <div class="list-group mt-2">
                    <label class="list-group-item d-flex gap-2 cursor-pointer py-3">
                        <input class="form-check-input flex-shrink-0" type="radio" name="payment_method" value="COD" onclick="updateFormAction(this.value)" checked>
                        <span><strong>Cash on Delivery (COD)</strong><small class="d-block text-muted">Pay when your order arrives.</small></span>
                    </label>
                    <label class="list-group-item d-flex gap-2 cursor-pointer py-3">
                        <input class="form-check-input flex-shrink-0" type="radio" name="payment_method" value="STRIPE" onclick="updateFormAction(this.value)">
                        <span><strong>Pay Online (Card)</strong><small class="d-block text-muted">Secure payment via Stripe gateway.</small></span>
                    </label>
                    <label class="list-group-item d-flex gap-2 cursor-pointer py-3">
                        <input class="form-check-input flex-shrink-0" type="radio" name="payment_method" value="PAYPAL" onclick="updateFormAction(this.value)">
                        <span><strong>PayPal</strong><small class="d-block text-muted">Pay securely via your PayPal account.</small></span>
                    </label>
                    <label class="list-group-item d-flex gap-2 cursor-pointer py-3">
                        <input class="form-check-input flex-shrink-0" type="radio" name="payment_method" value="RAZORPAY" onclick="updateFormAction(this.value)">
                        <span><strong>Razorpay (UPI / Cards / NetBanking)</strong><small class="d-block text-muted">Fast & Secure payment via Razorpay.</small></span>
                    </label>
                </div>

                @if($cartItems && $cartItems->count() > 0)
                    <button type="submit" class="btn btn-primary btn-lg w-100 mt-4 py-3 fw-bold shadow-sm transition-transform animate__animated animate__pulse animate__infinite animate__slow">
                        Place Order Now
                    </button>
                @else
                    <div class="mt-4">
                        <button type="button" class="btn btn-secondary btn-lg w-100 py-3 fw-bold opacity-50 cursor-not-allowed" disabled>Place Order Now</button>
                        <p class="text-danger small text-center mt-2 fw-bold"><i class="bi bi-cart-x"></i> Your cart is empty. Add items to continue.</p>
                    </div>
                @endif
            </form>
        </div>

        <div class="col-md-5 animate__animated animate__fadeInRight">
            <div class="card p-4 bg-light shadow-sm border-0 sticky-top" style="top: 2rem;">
                <h2 class="h5 fw-bold mb-4">Order Summary</h2>

                <div class="order-items overflow-auto" style="max-height: 400px;">
                    @foreach(isset($saleResult) ? $saleResult['items'] : [] as $row)
                    @php $item = $row['item']; @endphp
                        <div class="d-flex align-items-center mb-4 border-bottom pb-3">
                            <img src="{{ asset('storage/'.$item->product->image) }}" class="rounded border" style="width:70px; height:70px; object-fit:cover;">
                            <div class="ms-3 flex-grow-1">
                                <h6 class="mb-0 fw-bold">{{ $item->product->name }}</h6>
                                <small class="text-muted d-block">
                                    @php $attrValue = $item->variant->attributeValues->first(); @endphp
                                    @if($attrValue && $attrValue->attribute)
                                        <strong>{{ $attrValue->attribute->name }}</strong> : {{ $item->variant->name }}
                                    @else
                                        {{ $item->variant->name }}
                                    @endif
                                </small>
                                <small class="text-dark">Qty: {{ $item->quantity }}</small>
                                @if($row['has_sale'])
                                    <div><span style="background:#ef4444; color:#fff; font-size:.65rem; font-weight:700; padding:1px 7px; border-radius:4px;">🔥 {{ $row['sale']->discount_label }}</span></div>
                                @endif
                            </div>
                            <div class="text-end">
                                @if($row['has_sale'])
                                    <span class="fw-bold d-block" style="color:#ef4444;">₹{{ number_format($row['item_discounted'], 2) }}</span>
                                    <span class="text-muted text-decoration-line-through" style="font-size:.78rem;">₹{{ number_format($row['item_original'], 2) }}</span>
                                @else
                                    <span class="fw-bold text-primary">₹{{ number_format($row['item_original'], 2) }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax: </span>
                        <span>₹<span id="summary-subtotal">{{ number_format($taxAmount, 2) }}</span></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>₹<span id="summary-subtotal">{{ number_format($saleOriginal, 2) }}</span></span>
                    </div>
                    @if($hasSaleDiscount)
                    <div class="d-flex justify-content-between mb-2" style="color:#ef4444; font-weight:600;">
                        <span>🔥 Sale Discount</span>
                        <span>−₹{{ number_format($subtotal, 2) }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mb-2 discount-row" id="summary-discount-row" style="display:none !important;">
                        <span>Coupon Discount <span id="summary-discount-tag" style="background:#d1fae5; color:#065f46; border-radius:4px; font-size:.72rem; padding:1px 6px; font-weight:700; margin-left:4px; display:inline-block;"></span></span>
                        <span>−₹<span id="summary-saved">0.00</span></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <span class="text-success fw-medium">
                            {{ $shippingCharge > 0 ? '₹' . number_format($shippingCharge, 2) : 'Free' }}
                        </span>

                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h4 mb-0 fw-bold">Grand Total</span>
                        <span class="h4 mb-0 fw-bold text-primary grand-total-val">₹<span id="summary-grand-total">{{ number_format($totalAmount, 2) }}</span></span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/checkout.js'])
<script>
(function () {
    const ORIGINAL = {{ (float) $totalAmount }};
    @if(session('applied_coupon'))
        document.getElementById('coupon-box').classList.add('applied');
    @endif
    window.applyCoupon = function () {
        const code = document.getElementById('coupon_code_input').value.trim();
        if (!code) { showMsg('Please enter a coupon code.', 'error'); return; }
        const btn = document.getElementById('apply-btn');
        btn.disabled = true; btn.innerHTML = '<span class="spinner-sm"></span>';
        fetch("{{ route('coupon.apply') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ code, total: ORIGINAL })
        })
        .then(r => r.json())
        .then(data => {
            btn.disabled = false; btn.innerHTML = 'Apply';
            if (data.success) {
                document.getElementById('summary-saved').textContent = data.saved_amount.toFixed(2);
                document.getElementById('summary-discount-tag').textContent = data.discount_info;
                document.getElementById('summary-grand-total').textContent = data.discounted_total.toFixed(2);
                document.getElementById('summary-discount-row').style.display = 'flex';
                document.getElementById('hidden-final-total').value = data.discounted_total;
                document.getElementById('hidden-coupon').value = code;
                document.getElementById('strip-code').textContent = code;
                document.getElementById('strip-saving').textContent = 'You save ₹' + data.saved_amount.toFixed(2);
                document.getElementById('applied-strip').style.display = 'flex';
                document.getElementById('coupon_code_input').disabled = true;
                document.getElementById('coupon-box').classList.add('applied');
                showMsg('', '');
            } else { showMsg(data.message, 'error'); }
        })
        .catch(() => { btn.disabled = false; btn.innerHTML = 'Apply'; showMsg('Something went wrong. Try again.', 'error'); });
    };
    window.removeCoupon = function () {
        fetch("{{ route('coupon.remove') }}", { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
        .then(r => r.json())
        .then(() => {
            document.getElementById('summary-grand-total').textContent = ORIGINAL.toFixed(2);
            document.getElementById('summary-discount-row').style.display = 'none';
            document.getElementById('applied-strip').style.display = 'none';
            document.getElementById('coupon_code_input').value = '';
            document.getElementById('coupon_code_input').disabled = false;
            document.getElementById('coupon-box').classList.remove('applied');
            document.getElementById('hidden-coupon').value = '';
            document.getElementById('hidden-final-total').value = ORIGINAL;
            showMsg('Coupon removed.', 'info');
        });
    };
    function showMsg(msg, type) {
        const el = document.getElementById('coupon-message');
        const icons = { error:'✕', success:'✓', info:'ℹ' };
        el.className = 'coupon-msg' + (type ? ' msg-' + type : '');
        el.innerHTML = msg ? `<span>${icons[type]||''}</span><span>${msg}</span>` : '';
    }
})();
</script>
@endpush