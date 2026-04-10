@extends('layouts.frontend')

@section('title', 'Complete Payment')

@section('content')
<div class="container d-flex justify-content-center align-items-center py-5">
    <div class="card shadow-sm border-0 rounded-4" style="width: 100%; max-width: 480px;">

        <div class="card-header bg-primary text-white rounded-top-4 p-4">
            <p class="text-white-50 small mb-1 text-uppercase fw-semibold" style="letter-spacing:1px">Amount Due</p>
            <h2 class="fw-bold mb-2">₹{{ number_format($totalAmount, 2) }}</h2>
            <!-- <span class="badge bg-white text-primary fw-semibold">
                🔒 256-bit SSL Encrypted
            </span> -->
        </div>

        <div class="px-4 py-2 bg-light border-bottom d-flex justify-content-between align-items-center">
            <div>
                <span class="text-muted small">Order Ref: </span>
                <span class="fw-semibold small">{{ $tempOrderId }}</span>
            </div>
            <span class="badge text-bg-warning">Pending</span>
        </div>

        <div class="card-body p-4">
            <p class="text-muted small text-uppercase fw-semibold mb-3" style="letter-spacing:1px">Payment Details</p>

            <form id="payment-form">
                <div id="payment-element" class="border rounded-3 p-3 bg-light mb-4"></div>

                <div id="error-message" class="alert alert-danger d-none small py-2"></div>

                <button id="submit" type="submit" class="btn btn-primary w-100 py-3 fw-bold fs-6">
                    <span id="btn-text">🔒 Pay ₹{{ number_format($totalAmount, 2) }}</span>
                    <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
            </form>

            <div class="d-flex justify-content-center gap-4 mt-4">
                <span class="text-muted small">✅ Secure Payment</span>
                <span class="text-muted small">✅ No Card Storage</span>
                <span class="text-muted small">✅ Powered by Stripe</span>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe   = Stripe("{{ $stripeKey }}");
    const elements = stripe.elements({ clientSecret: "{{ $clientSecret }}" });

    const paymentElement = elements.create("payment");
    paymentElement.mount("#payment-element");

    const form      = document.getElementById('payment-form');
    const submitBtn = document.getElementById('submit');
    const btnText   = document.getElementById('btn-text');
    const spinner   = document.getElementById('spinner');
    const errorDiv  = document.getElementById('error-message');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        submitBtn.disabled = true;
        btnText.classList.add('d-none');
        spinner.classList.remove('d-none');
        errorDiv.classList.add('d-none');

        const { error } = await stripe.confirmPayment({
            elements,
            confirmParams: {
                return_url: "{{ route('stripe.success') }}",
            },
        });

        if (error) {
            errorDiv.textContent = error.message;
            errorDiv.classList.remove('d-none');
            submitBtn.disabled = false;
            btnText.classList.remove('d-none');
            spinner.classList.add('d-none');
        }
    });
</script>
@endpush