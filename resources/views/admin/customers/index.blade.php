@extends('layouts.backend')

@section('title', 'Customer Management')

@section('styles')
<style>
    .customer-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        background: #ffffff;
    }
    /* Table Header */
    .table thead th {
        background-color: #f1f4f9;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 1px;
        font-weight: 700;
        color: #555;
        border: none;
        padding: 15px;
    }
    /* User Initials Circle */
    .avatar-circle {
        width: 42px;
        height: 42px;
        background: #eef2ff;
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 700;
        font-size: 0.9rem;
    }
    /* Action Buttons with Text */
    .btn-action-text {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: 0.2s;
    }
    .btn-edit { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
    .btn-edit:hover { background: #bae6fd; }
    
    .btn-delete { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    .btn-delete:hover { background: #fecaca; }

    .location-badge {
        background: #f8f9fa;
        border: 1px solid #eee;
        color: #666;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeIn">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Customer Directory</h2>
            <p class="text-muted small">Manage and view your registered user base</p>
        </div>
        <button class="btn btn-outline-primary rounded-pill px-4 shadow-sm fw-bold">
            <i class="bi bi-download me-1"></i> Export Customers
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 alert-dismissible fade show mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card customer-card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Customer Details</th>
                        <th>Contact Information</th>
                        <th>Location</th>
                        <th>Joined Date</th>
                        <th>Status</th>
                        <th>Total Orders</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-3">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $customer->name }}</div>
                                    <small class="text-muted" style="font-size: 0.7rem;">ID: #{{ str_pad($customer->id, 5, '0', STR_PAD_LEFT) }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small d-flex align-items-center mb-1">
                                <i class="bi bi-envelope text-muted me-2"></i> {{ $customer->email }}
                            </div>
                            <div class="small d-flex align-items-center text-muted">
                                <i class="bi bi-telephone text-muted me-2"></i> {{ $customer->phone ?? 'Not Provided' }}
                            </div>
                        </td>
                        <td>
                            <span class="location-badge">
                                <i class="bi bi-geo-alt-fill text-primary me-1"></i> {{ $customer->city ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <div class="text-muted small fw-bold">
                                {{ $customer->created_at->format('d M, Y') }}
                            </div>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input status-toggle" type="checkbox" role="switch" 
                                    id="status-{{ $customer->id }}" 
                                    data-id="{{ $customer->id }}"
                                    {{ $customer->status ? 'checked' : '' }}>
                                <label class="form-check-label" for="status-{{ $customer->id }}">
                                    {{ $customer->status ? 'Active' : 'Inactive' }}
                                </label>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('admin.customer.orders.records', $customer->id) }}" style="text-decoration:none">
                            <div class="d-flex align-items-center">
                                <span class="badge rounded-pill {{ $customer->orders->count() > 0 ? 'bg-primary-subtle text-primary' : 'bg-light text-muted border' }} px-3 py-2">
                                    <i class="bi bi-cart-fill me-1"></i> <!-- Bootstrap Icon -->
                                    {{ $customer->orders->count() }} 
                                    <span class="ms-1 fw-normal">{{ Str::plural('Order', $customer->orders->count()) }}</span>
                                </span>
                            </div>
                            </a>
                        </td>

                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <!-- <a href="#" class="btn-action-text btn-edit">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a> -->
                                <a href="{{ route('delete_customer', ['id' => $customer->id]) }}" class="btn-action-text btn-delete border-0" onclick="return confirm('Permanently remove this customer?')">
                                Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-people display-4 d-block mb-3 opacity-25"></i>
                                <h5>No Customers Found</h5>
                                <p class="small">Wait for users to register on your store.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.status-toggle').on('change', function() {
            let status = $(this).prop('checked') ? 1 : 0;
            let customerId = $(this).data('id');
            let label = $(this).siblings('.form-check-label');

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{ route('customers.update-status') }}",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'status': status,
                    'id': customerId
                },
                success: function(data) {
                    // Update label text dynamically
                    label.text(status == 1 ? 'Active' : 'Inactive');
                    console.log(data.success);
                },
                error: function(xhr) {
                    console.error('Error updating status');
                }
            });
        });
    });
</script>
@endpush