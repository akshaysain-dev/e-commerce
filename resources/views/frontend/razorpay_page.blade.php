@extends('layouts.frontend')

@section('title', 'Razorpay Payment')

@section('content')
<div class="container text-center my-5">
    <div class="card shadow-sm p-5">
        <h3 class="mb-3">Order Summary</h3>
        <p class="text-muted">Order ID: <strong>{{ $tempOrderId }}</strong></p>
        <h4 class="mb-4">Total to Pay: <span class="text-primary">₹ {{ number_format($totalAmount, 2) }}</span></h4>

        <button id="rzp-button1" class="btn btn-primary btn-lg px-5">Pay with Razorpay</button>
        
        <div class="mt-3">
            <small class="text-muted">Please do not refresh or close this page.</small>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    var options = {
        "key": "{{ $razorpayKey }}",
        "amount": "{{ $totalAmount * 100 }}",
        "currency": "INR",
        "name": "MyStore",
        "order_id": "{{ $razorpayOrderId }}",
        "handler": function (response) {
            window.location.href = "{{ route('razorpay.callback') }}?payment_id=" +
                                    response.razorpay_payment_id +
                                    "&order_id=" + response.razorpay_order_id +
                                    "&signature=" + response.razorpay_signature;
        },
        "prefill": {
            "name": "{{ $customer->name }}",
            "email": "{{ $customer->email }}",
            "contact": "{{ $customer->phone }}"
        }
    };

    var rzp1 = new Razorpay(options);

    // Button click handler
    document.getElementById('rzp-button1').addEventListener('click', function (e) {
        rzp1.open();
        e.preventDefault();
    });

    // Auto-open after SDK is ready
    window.addEventListener('load', function () {
        setTimeout(function () {
            rzp1.open();
        }, 500);
    });
</script>
@endpush

