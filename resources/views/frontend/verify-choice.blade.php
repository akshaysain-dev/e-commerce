@extends('layouts.frontend')
@section('title', 'Verify Your Account')
@section('content')

@if(session('success') || session('error'))
<div class="modal fade" id="alertModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 text-center p-4">
      @if(session('success'))
        <div class="d-flex justify-content-center mb-3">
          <div class="rounded-circle d-flex align-items-center justify-content-center"
               style="width:64px;height:64px;background:#d1fae5;">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </div>
        </div>
        <h5 class="fw-semibold mb-1">Email Sent!</h5>
        <p class="text-muted small mb-4">{{ session('success') }}</p>
        <button type="button" class="btn w-100 text-white fw-medium py-2"
                style="background:#16a34a;border-radius:8px;"
                data-bs-dismiss="modal">OK, Got it</button>
      @elseif(session('error'))
        <div class="d-flex justify-content-center mb-3">
          <div class="rounded-circle d-flex align-items-center justify-content-center"
               style="width:64px;height:64px;background:#fee2e2;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5" stroke-linecap="round">
              <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
          </div>
        </div>
        <h5 class="fw-semibold mb-1">Error!</h5>
        <p class="text-muted small mb-4">{{ session('error') }}</p>
        <button type="button" class="btn w-100 text-white fw-medium py-2"
                style="background:#dc2626;border-radius:8px;"
                data-bs-dismiss="modal">Close</button>
      @endif
    </div>
  </div>
</div>
@endif

<div class="container mt-5 mb-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-gradient bg-primary text-white rounded-top-4">
          <h5 class="mb-0">Verify Your Account</h5>
        </div>
        <div class="card-body p-4 text-center">
          <p class="text-muted mb-4">
            Choose how you want to verify your account for<br>
            <strong>{{ session('pending_verification_email') }}</strong>
          </p>

          <div class="row g-3">
            {{-- Email Link --}}
            <div class="col-md-6">
              <div class="border rounded-3 p-3 h-100 d-flex flex-column align-items-center justify-content-center"
                   style="cursor:pointer;" onclick="document.getElementById('linkForm').submit()">
                <div class="rounded-circle d-flex align-items-center justify-content-center mb-3"
                     style="width:56px;height:56px;background:#dbeafe;">
                  <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                  </svg>
                </div>
                <h6 class="fw-semibold mb-1">Email Link</h6>
                <p class="text-muted small mb-3">We'll send a verification link to your email</p>
                <form id="linkForm" action="{{ route('verification.sendLink') }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-primary btn-sm px-3">Send Link</button>
                </form>
              </div>
            </div>

            {{-- OTP --}}
            <div class="col-md-6">
              <div class="border rounded-3 p-3 h-100 d-flex flex-column align-items-center justify-content-center"
                   style="cursor:pointer;" onclick="document.getElementById('otpForm').submit()">
                <div class="rounded-circle d-flex align-items-center justify-content-center mb-3"
                     style="width:56px;height:56px;background:#fef3c7;">
                  <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="5" y="2" width="14" height="20" rx="2" ry="2"/>
                    <line x1="12" y1="18" x2="12.01" y2="18"/>
                  </svg>
                </div>
                <h6 class="fw-semibold mb-1">OTP Code</h6>
                <p class="text-muted small mb-3">We'll send a 6-digit OTP to your email</p>
                <form id="otpForm" action="{{ route('verification.sendOtp') }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-warning btn-sm px-3">Send OTP</button>
                </form>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
@if(session('success') || session('error'))
<script>
  document.addEventListener('DOMContentLoaded', function () {
    new bootstrap.Modal(document.getElementById('alertModal')).show();
  });
</script>
@endif
@endpush