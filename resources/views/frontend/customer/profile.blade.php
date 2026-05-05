@extends('layouts.frontend')

@section('title', 'My Profile')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body text-center pt-5">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="white" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                        </svg>
                    </div>
                    <h5 class="fw-bold mb-0">{{ $customer->name }}</h5>
                    <p class="text-muted small mb-0">{{ $customer->email }}</p>
                </div>
                <div class="list-group list-group-flush pb-3 mt-3">
                    <a href="#" class="list-group-item list-group-item-action border-0 px-4 py-3 active">
                        <i class="bi bi-person me-2"></i> Account Details
                    </a>
                    <a href="{{ route('customer_logout') }}" class="list-group-item list-group-item-action border-0 px-4 py-3 text-danger">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3 mt-2">
                    <h5 class="mb-0 fw-bold px-3">Update Profile Information</h5>
                </div>
                <div class="card-body p-4">

                    {{-- Profile update success/error (non-OTP) --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('customer.profile.update') }}" method="POST">
                        @csrf

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Full Name</label>
                                <input type="text" name="name"
                                    class="form-control bg-light border-0 py-2 @error('name') is-invalid @enderror"
                                    value="{{ $customer->name }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Phone Number</label>
                                <input type="text" name="phone"
                                    class="form-control bg-light border-0 py-2 @error('phone') is-invalid @enderror"
                                    value="{{ $customer->phone }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small fw-bold">Email</label>
                                <input type="email" name="email" id="emailInput"
                                    class="form-control bg-light border-0 py-2"
                                    value="{{ $customer->email }}">
                                <div class="form-text text-muted">For the change email is verfiy with OTP.</div>
                            </div>

                            <div class="col-md-12">
                                <hr class="my-3">
                                <h6 class="fw-bold mb-3 text-primary">Shipping Address</h6>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small fw-bold">Street Address</label>
                                <textarea name="address" class="form-control bg-light border-0 py-2" rows="2">{{ $customer->area }}</textarea>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold">City</label>
                                <input type="text" name="city" class="form-control bg-light border-0 py-2" value="{{ $customer->city }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold">State</label>
                                <input type="text" name="state" class="form-control bg-light border-0 py-2" value="{{ $customer->state }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Postal Code</label>
                                <input type="text" name="postal_code" class="form-control bg-light border-0 py-2" value="{{ $customer->postal_code }}">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small fw-bold">Country</label>
                                <input type="text" name="country" class="form-control bg-light border-0 py-2" value="{{ $customer->country }}">
                            </div>

                            <div class="col-md-12">
                                <hr class="my-3">
                                <h6 class="fw-bold mb-3">Change Password
                                    <small class="text-muted fw-normal">(Leave blank to keep current)</small>
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">New Password</label>
                                <input type="password" name="password" class="form-control bg-light border-0 py-2">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control bg-light border-0 py-2">
                            </div>

                        </div>

                        <div class="mt-4 pt-2">
                            <button type="submit" id="saveBtn" class="btn btn-primary px-5 py-2 fw-bold rounded-3 shadow-sm">
                                Save Changes
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- ✅ OTP Modal — static backdrop, AJAX submit, error shown inside modal --}}
<div class="modal fade" id="otpModal" tabindex="-1"
     data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="otpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">

            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="otpModalLabel">
                    <i class="bi bi-shield-lock me-2 text-primary"></i> Verify Your Email
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body pt-2 pb-0">
                <p class="text-muted small mb-3">
                    Your <strong id="otpTargetEmail"></strong> on send OTP please Check.
                </p>

                {{-- ✅ Error alert — JS se show/hide hoga --}}
                <div id="otpErrorAlert" class="alert alert-danger py-2 small d-none" role="alert">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    <span id="otpErrorMsg"></span>
                </div>

                {{-- ✅ Success alert --}}
                <div id="otpSuccessAlert" class="alert alert-success py-2 small d-none" role="alert">
                    <i class="bi bi-check-circle me-1"></i>
                    <span id="otpSuccessMsg"></span>
                </div>

                <input type="text" id="otpInput"
                    class="form-control bg-light border-0 py-3 text-center fw-bold"
                    style="font-size: 1.5rem; letter-spacing: 0.5rem;"
                    placeholder="• • • • • •" maxlength="6"
                    autocomplete="one-time-code" inputmode="numeric">

                <p class="text-muted small mt-2 mb-0">OTP Valid only 5 Minutes.</p>
            </div>

            <div class="modal-footer border-0 pt-3">
                <button type="button" id="verifyOtpBtn"
                    class="btn btn-primary px-5 fw-bold rounded-3 w-100 py-2">
                    <span id="verifyBtnText">Verify & Save</span>
                    <span id="verifyBtnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                </button>
            </div>

        </div>
    </div>
</div>

{{-- ✅ Loading Overlay for profile form submit --}}
<div id="loadingOverlay"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5);
            z-index:9999; flex-direction:column; align-items:center;
            justify-content:center; gap:16px;">
    <div class="spinner-border text-light" role="status" style="width:3.5rem; height:3.5rem;">
        <span class="visually-hidden">Loading...</span>
    </div>
    <p style="color:#fff; font-size:16px; margin:0; font-weight:500;">OTP Sending...</p>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ───────────────────────────────────────────
       1. PROFILE FORM SUBMIT (email change check)
    ─────────────────────────────────────────── */
    const form          = document.querySelector('form[action="{{ route('customer.profile.update') }}"]');
    const overlay       = document.getElementById('loadingOverlay');
    const saveBtn       = document.getElementById('saveBtn');
    const emailInput    = document.getElementById('emailInput');
    const originalEmail = "{{ $customer->email }}";

    let isSubmitting = false;

    function showOverlay() {
        overlay.style.display = 'flex';
        saveBtn.disabled = true;
    }

    function hideOverlay() {
        overlay.style.display = 'none';
        saveBtn.disabled = false;
    }

    if (form) {
        form.addEventListener('submit', function (e) {
            const newEmail = emailInput.value.trim();

            // Email same hai — normal POST hone do
            if (newEmail === originalEmail) return;

            // Email change hui — AJAX
            e.preventDefault();
            if (isSubmitting) return;
            isSubmitting = true;

            showOverlay();

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Server error: ' + res.status);
                return res.json();
            })
            .then(data => {
                hideOverlay();
                if (data.otp_sent) {
                    // OTP modal mein target email dikhao
                    document.getElementById('otpTargetEmail').textContent = newEmail;
                    // Reset modal state
                    resetOtpModal();
                    // Modal open karo
                    new bootstrap.Modal(document.getElementById('otpModal')).show();
                } else if (data.message) {
                    alert(data.message);
                }
            })
            .catch(err => {
                console.error(err);
                hideOverlay();
                alert('Opps Something Went Wrong. Please Try Again.');
            })
            .finally(() => {
                isSubmitting = false;
            });
        });
    }

    /* ───────────────────────────────────────────
       2. OTP MODAL — AJAX verify, error in modal
    ─────────────────────────────────────────── */
    const verifyOtpBtn     = document.getElementById('verifyOtpBtn');
    const otpInput         = document.getElementById('otpInput');
    const otpErrorAlert    = document.getElementById('otpErrorAlert');
    const otpErrorMsg      = document.getElementById('otpErrorMsg');
    const otpSuccessAlert  = document.getElementById('otpSuccessAlert');
    const otpSuccessMsg    = document.getElementById('otpSuccessMsg');
    const verifyBtnText    = document.getElementById('verifyBtnText');
    const verifyBtnSpinner = document.getElementById('verifyBtnSpinner');

    let isVerifying = false;

    function resetOtpModal() {
        otpInput.value = '';
        otpErrorAlert.classList.add('d-none');
        otpSuccessAlert.classList.add('d-none');
        otpErrorMsg.textContent = '';
        otpSuccessMsg.textContent = '';
        verifyBtnText.textContent = 'Verify & Save';
        verifyBtnSpinner.classList.add('d-none');
        verifyOtpBtn.disabled = false;
        isVerifying = false;
    }

    function showOtpError(msg) {
        otpSuccessAlert.classList.add('d-none');
        otpErrorMsg.textContent = msg;
        otpErrorAlert.classList.remove('d-none');
        // Input shake animation
        otpInput.classList.add('border', 'border-danger');
        setTimeout(() => otpInput.classList.remove('border', 'border-danger'), 2000);
    }

    function showOtpSuccess(msg) {
        otpErrorAlert.classList.add('d-none');
        otpSuccessMsg.textContent = msg;
        otpSuccessAlert.classList.remove('d-none');
    }

    // OTP only numeric input
    otpInput.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '');
    });

    verifyOtpBtn.addEventListener('click', function () {
        const otp = otpInput.value.trim();

        // Client-side check
        if (otp.length !== 6) {
            showOtpError('Please Enter full 6-digit OTP.');
            return;
        }

        if (isVerifying) return;
        isVerifying = true;

        // Button loading state
        verifyBtnText.textContent = 'Verifying...';
        verifyBtnSpinner.classList.remove('d-none');
        verifyOtpBtn.disabled = true;
        otpErrorAlert.classList.add('d-none');

        const formData = new FormData();
        formData.append('otp', otp);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('/verify-email-otp', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json().then(data => ({ status: res.status, data })))
        .then(({ status, data }) => {
            if (status === 422 || !data.success) {
                // ✅ Wrong/expired OTP — modal BAND NAHI HOGI, error dikhao
                showOtpError(data.message || 'Invalid OTP.');
                verifyBtnText.textContent = 'Verify & Save';
                verifyBtnSpinner.classList.add('d-none');
                verifyOtpBtn.disabled = false;
                isVerifying = false;
            } else if (data.success) {
                // ✅ Success — success message dikhao, phir page reload
                showOtpSuccess(data.message || 'Email verified successfully!');
                verifyBtnText.textContent = 'Verified!';
                verifyBtnSpinner.classList.add('d-none');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        })
        .catch(err => {
            console.error(err);
            showOtpError('Kuch problem aa gayi, dobara try karein.');
            verifyBtnText.textContent = 'Verify & Save';
            verifyBtnSpinner.classList.add('d-none');
            verifyOtpBtn.disabled = false;
            isVerifying = false;
        });
    });

    // Enter key se bhi verify ho
    otpInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') verifyOtpBtn.click();
    });

});
</script>
@endpush