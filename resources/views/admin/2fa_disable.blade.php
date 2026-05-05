@extends('layouts.backend')

@section('title', 'Disable 2FA')

@section('content')

<div class="container">
    <div class="row justify-content-center mt-5">

        <div class="col-md-5">

            <div class="card shadow border-0" style="border-radius: 15px;">

                <div class="card-body text-center p-4">

                    {{-- ICON --}}
                    <div class="mb-3">
                        <div style="font-size: 40px;">🔓</div>
                    </div>

                    {{-- TITLE --}}
                    <h4 class="mb-2 fw-bold">Disable Two-Factor Authentication</h4>

                    {{-- DESCRIPTION --}}
                    <p class="text-muted mb-4">
                        For security reasons, please enter the 6-digit code from your 
                        <strong>Google Authenticator</strong> app to disable 2FA.
                    </p>

                    {{-- ALERT --}}
                    <div class="alert alert-warning text-start">
                        ⚠️ Disabling 2FA will reduce your account security.  
                        Make sure you really want to proceed.
                    </div>

                    {{-- ERROR --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p class="mb-0">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    {{-- FORM --}}
                    <form method="POST" action="{{ route('admin.2fa.disable') }}">
                        @csrf

                        <div class="mb-3">
                            <input 
                                type="text" 
                                name="otp" 
                                class="form-control text-center fs-5"
                                placeholder="Enter 6-digit OTP"
                                maxlength="6"
                                style="letter-spacing: 5px;"
                            >
                        </div>

                        <button type="submit" class="btn btn-danger w-100 py-2 fw-bold">
                            Disable 2FA
                        </button>
                    </form>

                    {{-- FOOTER TEXT --}}
                    <small class="text-muted d-block mt-3">
                        Having trouble? Make sure your device time is synced.
                    </small>

                </div>

            </div>

        </div>

    </div>
</div>

@endsection