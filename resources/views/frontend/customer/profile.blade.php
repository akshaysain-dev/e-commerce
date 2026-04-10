@extends('layouts.frontend')

@section('title', 'My Profile')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body text-center pt-5">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <svg xmlns="http://www.w3.org" width="40" height="40" fill="white" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z""")/>>
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
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    <form action="{{ route('customer.profile.update') }}" method="POST">
                        @csrf
                        
                        <div class="row g-3">
                           
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Full Name</label>
                                <input type="text" name="name" class="form-control bg-light border-0 py-2 @error('name') is-invalid @enderror" value="{{ $customer->name }}">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Phone Number</label>
                                <input type="text" name="phone" class="form-control bg-light border-0 py-2" value="{{ $customer->phone }}">
                                @error('phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small fw-bold">Email (Read Only)</label>
                                <input type="email" class="form-control bg-light border-0 py-2" value="{{ $customer->email }}" readonly>
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
                                <h6 class="fw-bold mb-3">Change Password <small class="text-muted fw-normal">(Leave blank to keep current)</small></h6>
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
                            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-3 shadow-sm">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
