@extends('layouts.backend')

@section('title', 'Add Coupon')

@section('content')
<div class="container-fluid px-4">

    <div class="d-flex align-items-center mt-4 mb-4">
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-light me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="mb-0 fw-bold">Add New Coupon</h2>
            <small class="text-muted">Create a new discount coupon</small>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.coupons.store') }}">
                        @csrf

                        {{-- Name --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Coupon Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="e.g. Diwali Sale 2025">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Code --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Coupon Code <span class="text-danger">*</span></label>
                            <div class="input-group input-group-lg">
                                <input type="text" name="code" id="coupon-code-input"
                                       class="form-control font-monospace text-uppercase @error('code') is-invalid @enderror"
                                       value="{{ old('code') }}" placeholder="e.g. DIWALI50"
                                       oninput="this.value = this.value.toUpperCase()">
                                <button type="button" class="btn btn-outline-secondary" onclick="generateCode()">
                                    <i class="fas fa-random"></i> Generate
                                </button>
                            </div>
                            @error('code') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            <small class="text-muted">Code will be auto-uppercased. Must be unique.</small>
                        </div>

                        {{-- Discount Type + Value --}}
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label fw-semibold">Discount Type <span class="text-danger">*</span></label>
                                <select name="type" id="discount-type" class="form-select form-select-lg @error('type') is-invalid @enderror"
                                        onchange="updateDiscountLabel()">
                                    <option value="fixed"   {{ old('type') === 'fixed'   ? 'selected' : '' }}>Fixed Amount (₹)</option>
                                    <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>Percentage (%)</option>
                                </select>
                                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">
                                    Discount Value <span class="text-danger">*</span>
                                    <small id="discount-unit" class="text-muted">(₹)</small>
                                </label>
                                <input type="number" name="discount" step="0.01" min="1"
                                       class="form-control form-control-lg @error('discount') is-invalid @enderror"
                                       value="{{ old('discount') }}" placeholder="50">
                                @error('discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Validity --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Valid For (Days) <span class="text-danger">*</span></label>
                            <input type="number" name="expires_in_days" min="1"
                                   class="form-control form-control-lg @error('expires_in_days') is-invalid @enderror"
                                   value="{{ old('expires_in_days') }}" placeholder="e.g. 7">
                            @error('expires_in_days') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted">
                                Expiry = Today + N days.
                                @if(old('expires_in_days'))
                                    Expires on: <strong>{{ now()->addDays(old('expires_in_days'))->format('d M Y') }}</strong>
                                @endif
                            </small>
                        </div>

                        {{-- Preview Card --}}
                        <div class="bg-light rounded p-3 mb-4 border" id="preview-card" style="display:none">
                            <div class="text-muted small mb-1">Preview</div>
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-dark font-monospace fs-6 px-3 py-2" id="preview-code">—</span>
                                <span class="text-success fw-bold" id="preview-discount">—</span>
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-plus me-1"></i> Create Coupon
                            </button>
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-light btn-lg px-4">Cancel</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function generateCode() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let code = '';
    for (let i = 0; i < 8; i++) code += chars[Math.floor(Math.random() * chars.length)];
    document.getElementById('coupon-code-input').value = code;
    updatePreview();
}

function updateDiscountLabel() {
    const type = document.getElementById('discount-type').value;
    document.getElementById('discount-unit').textContent = type === 'percent' ? '(%)' : '(₹)';
    updatePreview();
}

function updatePreview() {
    const code     = document.getElementById('coupon-code-input').value;
    const discount = document.querySelector('[name=discount]').value;
    const type     = document.getElementById('discount-type').value;

    if (code || discount) {
        document.getElementById('preview-card').style.display = 'block';
        document.getElementById('preview-code').textContent    = code || '—';
        document.getElementById('preview-discount').textContent =
            discount ? (type === 'percent' ? discount + '% OFF' : '₹' + discount + ' OFF') : '—';
    }
}

document.querySelectorAll('[name=discount], #coupon-code-input').forEach(el => {
    el.addEventListener('input', updatePreview);
});
</script>
@endsection