@extends('layouts.backend')

@section('title', 'Coupons')

@section('content')
<div class="container-fluid px-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mt-4 mb-3">
        <div>
            <h2 class="mb-0 fw-bold">🎟 Coupons</h2>
            <small class="text-muted">Manage all discount coupons</small>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary px-4">
            <i class="fas fa-plus me-1"></i> Add Coupon
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold text-primary">{{ $coupons->total() }}</div>
                <div class="text-muted small">Total Coupons</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold text-success">
                    {{ $coupons->getCollection()->where('is_active', true)->where('is_used', false)->count() }}
                </div>
                <div class="text-muted small">Active & Available</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold text-danger">
                    {{ $coupons->getCollection()->where('is_used', true)->count() }}
                </div>
                <div class="text-muted small">Used</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold text-warning">
                    {{ $coupons->getCollection()->filter(fn($c) => now()->gt($c->expires_at))->count() }}
                </div>
                <div class="text-muted small">Expired</div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Discount</th>
                            <th>Valid For</th>
                            <th>Expires At</th>
                            <th>Status</th>
                            <th>Used</th>
                            <th>Generated For</th>
                            <th>Used By</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($coupons as $coupon)
                        <tr>
                            <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $coupon->name }}</td>
                            <td>
                                <span class="badge bg-dark fs-6 font-monospace px-3 py-2">
                                    {{ $coupon->code }}
                                </span>
                            </td>
                            <td>
                                @if($coupon->type === 'percent')
                                    <span class="text-success fw-bold">{{ $coupon->discount }}% OFF</span>
                                @else
                                    <span class="text-success fw-bold">₹{{ number_format($coupon->discount, 2) }} OFF</span>
                                @endif
                            </td>
                            <td>{{ $coupon->expires_in_days }} day(s)</td>
                            <td>
                                <span class="{{ now()->gt($coupon->expires_at) ? 'text-danger' : 'text-dark' }}">
                                    {{ $coupon->expires_at->format('d M Y') }}
                                </span>
                                <br>
                                <small class="text-muted">{{ $coupon->expires_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                @if($coupon->is_active && !$coupon->is_used && now()->lte($coupon->expires_at))
                                    <span class="badge bg-success-subtle text-success border border-success">● Active</span>
                                @elseif($coupon->is_used)
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary">Used</span>
                                @elseif(now()->gt($coupon->expires_at))
                                    <span class="badge bg-danger-subtle text-danger border border-danger">Expired</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @if($coupon->is_used)
                                    <i class="fas fa-check-circle text-danger"></i> Used
                                @else
                                    <i class="fas fa-circle text-success"></i> Available
                                @endif
                            </td>
                            <td>
                                @if($coupon->generated_for)
                                    <span style="font-size:.8rem; color:#4338ca;">
                                        👤 {{ optional($coupon->customer_generated)->name ?? 'Customer #'.$coupon->generated_for }}
                                    </span>
                                @else
                                    <span style="color:#94a3b8; font-size:.8rem;">Everyone</span>
                                @endif
                            </td>
                            <td>
                                @if($coupon->used_by)
                                    <span style="font-size:.8rem; color:#059669;">
                                        ✓ {{ optional($coupon->customer_used)->name ?? 'Customer #'.$coupon->used_by }}
                                    </span>
                                @else
                                    <span style="color:#94a3b8; font-size:.8rem;">—</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}"
                                   class="btn btn-sm btn-outline-primary me-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete coupon {{ $coupon->code }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fas fa-ticket-alt fa-2x mb-2 d-block"></i>
                                No coupons found. <a href="{{ route('admin.coupons.create') }}">Add one</a>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($coupons->hasPages())
        <div class="card-footer bg-white border-top-0 py-3 px-4">
            {{ $coupons->links() }}
        </div>
        @endif
    </div>

</div>
@endsection