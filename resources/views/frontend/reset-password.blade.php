@extends('layouts.frontend')

@section('title', 'Reset Password')

@section('content')

        @if(session('success') || session('error') || $errors->any())

        <!-- Modal -->
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
                <h5 class="fw-semibold mb-1">Success!</h5>
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

            @elseif($errors->any())
                <div class="d-flex justify-content-center mb-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                    style="width:64px;height:64px;background:#fef3c7;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2.5" stroke-linecap="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                </div>
                <h5 class="fw-semibold mb-1">Validation Error!</h5>
                <ul class="text-start text-muted small mb-4 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
                <button type="button" class="btn w-100 text-white fw-medium py-2"
                        style="background:#d97706;border-radius:8px;"
                        data-bs-dismiss="modal">OK</button>
            @endif

            </div>
        </div>
        </div>

        @endif
<div class="container d-flex justify-content-center align-items-center py-5">
    <div class="card shadow-sm border-0 rounded-4" style="width: 100%; max-width: 480px;">

        <!-- Header -->
        <div class="card-header bg-success text-white rounded-top-4 p-4">
            <p class="text-white-50 small mb-1 text-uppercase fw-semibold" style="letter-spacing:1px">
                Secure Reset
            </p>
            <h2 class="fw-bold mb-2">Reset Password</h2>
        </div>

        <!-- Body -->
        <div class="card-body p-4">

            @if(session('error'))
                <div class="alert alert-danger small py-2">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ url('/reset-password') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-3">
                    <input type="email" name="email" class="form-control rounded-3" placeholder="Email" value="{{ $email }} " readonly>
                </div>

                <div class="mb-3">
                    <input type="password" name="password" class="form-control rounded-3" placeholder="New Password" required>
                </div>

                <div class="mb-3">
                    <input type="password" name="password_confirmation" class="form-control rounded-3" placeholder="Confirm Password" required>
                </div>

                <button type="submit" class="btn btn-success w-100 py-3 fw-bold">
                    🔒 Reset Password
                </button>
            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')

<!-- Auto open -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    new bootstrap.Modal(document.getElementById('alertModal')).show();
  });
</script>

@endpush