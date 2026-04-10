@extends('layouts.frontend')

@section('title', 'Register')

@section('content')

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
    <div class="col-md-10">

        <div class="card shadow-lg border-0">

            <div class="card-header bg-gradient bg-primary text-white">
                <h4 class="mb-0">Customer Registration</h4>
            </div>

            <div class="card-body p-4">

                {{-- Success Message --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('customer.store') }}" method="POST">
                    @csrf

                    <div class="row">

                        {{-- Name --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="form-control shadow-sm @error('name') is-invalid @enderror"
                                   placeholder="Enter full name">

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   class="form-control shadow-sm @error('email') is-invalid @enderror"
                                   placeholder="Enter email">

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text"
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   class="form-control shadow-sm @error('phone') is-invalid @enderror"
                                   placeholder="Enter phone">

                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Address --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address"
                                      rows="3"
                                      class="form-control shadow-sm @error('address') is-invalid @enderror"
                                      placeholder="Enter address">{{ old('address') }}</textarea>

                            @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- City --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">City</label>
                            <input type="text"
                                   name="city"
                                   value="{{ old('city') }}"
                                   class="form-control shadow-sm @error('city') is-invalid @enderror">

                            @error('city')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- State --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">State</label>
                            <input type="text"
                                   name="state"
                                   value="{{ old('state') }}"
                                   class="form-control shadow-sm @error('state') is-invalid @enderror">

                            @error('state')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Country --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Country</label>
                            <input type="text"
                                   name="country"
                                   value="{{ old('country') }}"
                                   class="form-control shadow-sm @error('country') is-invalid @enderror">

                            @error('country')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Postal Code --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Postal Code</label>
                            <input type="text"
                                   name="postal_code"
                                   value="{{ old('postal_code') }}"
                                   class="form-control shadow-sm @error('postal_code') is-invalid @enderror">

                            @error('postal_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password"
                                   name="password"
                                   class="form-control shadow-sm @error('password') is-invalid @enderror"
                                   placeholder="Enter password">

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control shadow-sm"
                                   placeholder="Confirm password">
                        </div>

                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success px-4">
                            Register Customer
                        </button>

                        <a href="{{ url()->previous() }}" class="btn btn-secondary px-4">
                            Back
                        </a>
                    </div>

                </form>
                <div class="text-center mt-4">
                    <p class="small mb-0">If You Have Already an Account? <a href="{{ route('login_customer') }}" class="fw-bold text-decoration-none">Login</a></p>
                </div>
            </div>

        </div>

    </div>

</div> 

</div>

@endsection
