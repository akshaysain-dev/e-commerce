@extends('layouts.frontend')

@section('title', 'Register')

@section('content')
{{-- Apne existing alerts ki jagah yeh lagao --}}

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
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
    <div class="col-md-10">

        <div class="card shadow-lg border-0">

            <div class="card-header bg-gradient bg-primary text-white">
                <h4 class="mb-0">Customer Registration</h4>
            </div>

            <div class="card-body p-4">


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

@push('scripts')

<!-- Auto open -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    new bootstrap.Modal(document.getElementById('alertModal')).show();
  });
</script>

@endpush