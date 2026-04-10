@extends('layouts.backend')

@section('title', 'Edit Coupon')

@section('content')
<div class="container-fluid px-4">

    <div class="d-flex align-items-center mt-4 mb-4">
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-light me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="mb-0 fw-bold">Edit Coupon</h2>
            <small class="text-muted font-monospace">{{ $coupon->code }}</small>
        </div>
    </div>

    {{-- Status Banner --}}
    @if($coupon->is_used)
        <div class="alert alert-warning d-flex align-items-center mb-4">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>This coupon has already been used.</strong>&nbsp;It cannot be applied again.
        </div>
    @elseif(now()->gt($coupon->expires_at))
        <div class="alert alert-danger d-flex align-items-center mb-4">
            <i class="fas fa-clock me-2"></i>
            <strong>This coupon has expired.</strong>&nbsp;Update the days below to reactivate it.
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}">
                        @csrf @method('PUT')

                        <input type="hidden" name="coupon_id" value="{{ $coupon->id }}">
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Coupon Name</label>
                            <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   value="{{ old('name', $coupon->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Code readonly --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Coupon Code <span class="text-muted small">(cannot change)</span></label>
                            <input type="text" class="form-control form-control-lg font-monospace bg-light"
                                   value="{{ $coupon->code }}" disabled>
                        </div>

                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label fw-semibold">Discount Type</label>
                                <select name="type" class="form-select form-select-lg @error('type') is-invalid @enderror">
                                    <option value="fixed"   {{ old('type', $coupon->type) === 'fixed'   ? 'selected' : '' }}>Fixed (₹)</option>
                                    <option value="percent" {{ old('type', $coupon->type) === 'percent' ? 'selected' : '' }}>Percent (%)</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Discount Value</label>
                                <input type="number" name="discount" step="0.01" min="1"
                                       class="form-control form-control-lg @error('discount') is-invalid @enderror"
                                       value="{{ old('discount', $coupon->discount) }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Valid For (Days) — resets expiry from today</label>
                            <input type="number" name="expires_in_days" min="1"
                                   class="form-control form-control-lg @error('expires_in_days') is-invalid @enderror"
                                   value="{{ old('expires_in_days', $coupon->expires_in_days) }}" required>
                            <small class="text-muted">
                                Current expiry: <strong>{{ $coupon->expires_at->format('d M Y') }}</strong>
                                ({{ $coupon->expires_at->diffForHumans() }})
                            </small>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active"
                                       id="is_active" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_active">
                                    Active (users can apply this coupon)
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-save me-1"></i> Update Coupon
                            </button>
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-light btn-lg px-4">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection