@extends('layouts.frontend')
@section('title', 'Enter OTP')
@section('content')

<div class="container mt-5 mb-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-gradient bg-warning text-white rounded-top-4">
          <h5 class="mb-0">Enter OTP</h5>
        </div>
        <div class="card-body p-4">

          @if(session('otp_sent'))
            <div class="alert alert-success small">OTP sent to your email successfully!</div>
          @endif

          <p class="text-muted small mb-4 text-center">
            Enter the 6-digit OTP sent to<br>
            <strong>{{ session('pending_verification_email') }}</strong>
          </p>

          <form action="{{ route('verification.verifyOtp') }}" method="POST">
            @csrf

            <div class="mb-3">
              <label class="form-label fw-medium">OTP Code</label>
              <input type="text"
                     name="otp"
                     maxlength="6"
                     class="form-control form-control-lg text-center shadow-sm @error('otp') is-invalid @enderror"
                     placeholder="_ _ _ _ _ _"
                     autocomplete="off">
              @error('otp')
                <div class="invalid-feedback text-center">{{ $message }}</div>
              @enderror
            </div>

            <button type="submit" class="btn btn-warning w-100 fw-medium py-2 text-white">
              Verify OTP
            </button>
          </form>

          <div class="text-center mt-3">
            <form action="{{ route('verification.sendOtp') }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-link btn-sm text-decoration-none">
                Resend OTP
              </button>
            </form>
            <span class="text-muted small">|</span>
            <a href="{{ route('verification.choice') }}" class="btn btn-link btn-sm text-decoration-none">
              Back to Options
            </a>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

@endsection