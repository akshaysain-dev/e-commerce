@extends('layouts.backend')

@section('title', 'Setup 2FA')

@section('content')

<div class="container">
    <div class="row justify-content-center mt-4">

        <div class="col-md-5">

            <div class="card shadow">

                <div class="card-header text-center">
                    <h5>Setup Google Authenticator</h5>
                </div>

                <div class="card-body text-center">

                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Error Message --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p class="mb-0">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <p class="mb-2">
                        Scan this QR code in 
                        <strong>Google Authenticator</strong>
                    </p>

                    <div class="mb-3 d-flex justify-content-center">
                        <div style="background:#fff; padding:10px; border-radius:10px;">
                            {!! $QR_Image !!}
                        </div>
                    </div>

                    {{-- Secret Key (Backup Option) --}}
                    <div class="mb-3">
                        <small class="text-muted">Manual Key:</small><br>
                        <code>{{ $secret }}</code>
                    </div>

                    <hr>

                    <p>Enter the OTP from your app to enable 2FA</p>

                    {{-- OTP Form --}}
                    <form method="POST" action="{{ route('admin.2fa.enable') }}">
                        @csrf

                        <div class="mb-3">
                            <input 
                                type="text" 
                                name="otp" 
                                class="form-control text-center" 
                                placeholder="Enter 6-digit OTP"
                                maxlength="6"
                            >
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            Enable 2FA
                        </button>
                    </form>

                </div>

            </div>

        </div>

    </div>
</div>

@endsection