@extends('layouts.frontend')

@section('title', 'Paypal')

@section('content')
<div class="container d-flex justify-content-center align-items-center py-5">
    <div class="card shadow-sm border-0 rounded-4" style="width:100%;max-width:480px;">

        <div class="card-header text-dark rounded-top-4 p-4" style="background:#FFC439;">
            <p class="small mb-1 text-uppercase fw-semibold opacity-75" style="letter-spacing:1px">Amount Due</p>
            <h2 class="fw-bold mb-2">₹{{ number_format($totalAmount, 2) }}</h2>
            <span class="badge bg-dark fw-semibold" style="color:#FFC439;">🔒 256-bit SSL Encrypted</span>
        </div>

        <div class="px-4 py-2 bg-light border-bottom d-flex justify-content-between align-items-center">
            <div>
                <span class="text-muted small">Order Ref: </span>
                <span class="fw-semibold small">{{ $tempOrderId }}</span>
            </div>
            <span class="badge text-bg-warning">Pending</span>
        </div>

        <div class="card-body p-4">

            <div class="d-flex align-items-center gap-2 mb-3">
                <img src="https://www.paypalobjects.com/webstatic/mktg/Logo/pp-logo-100px.png" height="24" alt="PayPal">
                <span class="text-muted small fw-semibold">Secure Checkout</span>
            </div>

            <div id="paypal-error" class="alert alert-danger d-none small py-2"></div>

            <div id="paypal-container">
                <div id="card-form">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Card Number</label>
                        <div id="card-number" class="form-control bg-light" style="height:42px;"></div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-muted">Expiry Date</label>
                            <div id="expiration-date" class="form-control bg-light" style="height:42px;"></div>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-muted">CVV</label>
                            <div id="cvv" class="form-control bg-light" style="height:42px;"></div>
                        </div>
                    </div>
                    <button id="submit-btn" type="button" class="btn w-100 py-3 fw-bold fs-6 text-dark border-0" style="background:#FFC439;">
                        <span id="btn-text">🔒 Pay ₹{{ number_format($totalAmount, 2) }}</span>
                        <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-center gap-4 mt-4 pt-3 border-top">
                <span class="text-muted small">✅ Secure Payment</span>
                <span class="text-muted small">✅ No Card Storage</span>
                <span class="text-muted small">✅ Powered by PayPal</span>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://www.paypal.com/sdk/js?client-id={{ $clientId }}&components=hosted-fields,buttons&intent=capture&currency=USD"></script>
<script>
    const orderId  = "{{ $paypalOrderId }}";
    const errorDiv = document.getElementById('paypal-error');

    if (paypal.HostedFields.isEligible()) {
        paypal.HostedFields.render({
            createOrder: () => orderId,
            fields: {
                number:         { selector: '#card-number',     placeholder: '4111 1111 1111 1111' },
                expirationDate: { selector: '#expiration-date', placeholder: 'MM/YY' },
                cvv:            { selector: '#cvv',             placeholder: '123' },
            }
        }).then(hostedFields => {
            document.getElementById('submit-btn').addEventListener('click', async () => {
                const btn     = document.getElementById('submit-btn');
                const btnText = document.getElementById('btn-text');
                const spinner = document.getElementById('spinner');

                btn.disabled = true;
                btnText.classList.add('d-none');
                spinner.classList.remove('d-none');
                errorDiv.classList.add('d-none');

                try {
                    const { orderId: capturedId } = await hostedFields.submit({ contingencies: ['3D_SECURE'] });
                    window.location.href = "{{ route('paypal.success') }}?order_id=" + capturedId;
                } catch (err) {
                    errorDiv.textContent = err.message || 'Payment failed. Try again.';
                    errorDiv.classList.remove('d-none');
                    btn.disabled = false;
                    btnText.classList.remove('d-none');
                    spinner.classList.add('d-none');
                }
            });
        });

    } else {
        document.getElementById('card-form').innerHTML = `
            <p class="text-muted small mb-3 text-center">Login with your PayPal account to complete payment</p>
            <div id="paypal-button"></div>
        `;

        paypal.Buttons({
            style: {
                layout : 'vertical',
                color  : 'gold',
                shape  : 'rect',
                label  : 'pay',
                height : 48,
            },
            createOrder: () => orderId,
            onApprove: (data) => {
                window.location.href = "{{ route('paypal.success') }}?order_id=" + data.orderID;
            },
            onError: (err) => {
                errorDiv.textContent = 'PayPal Error: ' + err;
                errorDiv.classList.remove('d-none');
            }
        }).render('#paypal-button');
    }
</script>
@endpush