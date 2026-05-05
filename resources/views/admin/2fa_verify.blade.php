@extends('layouts.backend')

@section('title', '2FA Verification')

@section('content')

<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">

        <div class="col-md-4">

            <div class="card border-0 shadow-lg" style="border-radius: 18px;">

                <div class="card-body text-center p-4">

                    {{-- ICON --}}
                    <div class="mb-3">
                        <div style="font-size: 42px;">🔐</div>
                    </div>

                    {{-- TITLE --}}
                    <h4 class="fw-bold mb-2">2-Step Verification</h4>

                    {{-- SUBTEXT --}}
                    <p class="text-muted mb-4">
                        Enter the 6-digit code from your<br>
                        <strong>Google Authenticator</strong> app
                    </p>

                    {{-- ERROR --}}
                    @if ($errors->any())
                        <div class="alert alert-danger text-start">
                            @foreach ($errors->all() as $error)
                                <p class="mb-0">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    {{-- FORM --}}
                    <form method="POST" action="{{ route('admin.2fa.check') }}">
                        @csrf

                        <div class="mb-3">
                            <input 
                                type="number"
                                inputmode="numeric"
                                name="otp" 
                                class="form-control text-center fs-4 fw-bold"
                                placeholder="••••••"
                                maxlength="6"
                                autofocus
                                style="letter-spacing: 8px; height: 55px; border-radius: 10px;"
                            >
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                            Verify Code
                        </button>
                    </form>

                    {{-- FOOTER --}}
                    <div class="mt-3">
                        <small class="text-muted">
                            Code refreshes every 30 seconds ⏱
                        </small>
                    </div>

                </div>

                <div class="card-footer text-center bg-white border-0 pb-4">
                    <small class="text-muted">
                        Having trouble? Make sure your device time is synced.
                    </small>
                </div>

            </div>

        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
document.querySelector('input[name="otp"]').addEventListener('input', function() {
    if (this.value.length === 6) {
        this.form.submit();
    }
});
</script>
@endpush