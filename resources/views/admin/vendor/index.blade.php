@extends('layouts.backend')

@section('title', 'Vendor Management')

@section('content')

<div class="container-fluid py-4">

    {{-- Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow rounded-4">

        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">

            <h4 class="fw-bold mb-0">
                <i class="fa fa-store me-2"></i>
                Vendor Management
            </h4>

            <span class="badge bg-dark fs-6">
                {{ $vendors->count() }} Vendors
            </span>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table align-middle table-hover mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>ID</th>
                            <th>Vendor</th>
                            <th>Shop</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Stripe</th>
                            <th>Commission</th>
                            <th width="300">Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($vendors as $vendor)

                        <tr>

                            <td>#{{ $vendor->id }}</td>

                            <td>

                                <div class="d-flex align-items-center">

                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                         style="width:45px;height:45px;">

                                        {{ strtoupper(substr($vendor->user->name,0,1)) }}

                                    </div>

                                    <div>

                                        <div class="fw-bold">
                                            {{ $vendor->user->name }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $vendor->user->email }}
                                        </small>

                                    </div>

                                </div>

                            </td>

                            <td>

                                <div class="fw-semibold">
                                    {{ $vendor->shop_name }}
                                </div>

                                <small class="text-muted">
                                    {{ $vendor->address }}
                                </small>

                            </td>

                            <td>
                                {{ $vendor->phone }}
                            </td>

                            <td>

                                @if($vendor->user->status == 'approved')

                                    <span class="badge bg-success">
                                        Approved
                                    </span>

                                @elseif($vendor->user->status == 'pending')

                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>

                                @elseif($vendor->user->status == 'rejected')

                                    <span class="badge bg-danger">
                                        Rejected
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Suspended
                                    </span>

                                @endif

                            </td>

                            <td>

                                @if($vendor->stripe_onboarded)

                                    <span class="badge bg-success">
                                        Connected
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Not Connected
                                    </span>

                                @endif

                            </td>

                            <td>

                                <form action="{{ route('admin.vendors.commission', $vendor->id) }}"
                                      method="POST"
                                      class="d-flex gap-2">

                                    @csrf

                                    <input type="number"
                                           step="0.01"
                                           name="commission_rate"
                                           value="{{ $vendor->commission_rate }}"
                                           class="form-control form-control-sm">

                                    <button class="btn btn-dark btn-sm">
                                        Save
                                    </button>

                                </form>

                            </td>

                            <td>

                                <div class="d-flex flex-wrap gap-2">

                                    {{-- Status --}}
                                    <form action="{{ route('admin.vendors.status', $vendor->id) }}"
                                          method="POST"
                                          class="d-flex gap-2">

                                        @csrf

                                        <select name="status"
                                                class="form-select form-select-sm">

                                            <option value="pending"
                                                {{ $vendor->user->status == 'pending' ? 'selected' : '' }}>
                                                Pending
                                            </option>

                                            <option value="approved"
                                                {{ $vendor->user->status == 'approved' ? 'selected' : '' }}>
                                                Approved
                                            </option>

                                            <option value="rejected"
                                                {{ $vendor->user->status == 'rejected' ? 'selected' : '' }}>
                                                Rejected
                                            </option>

                                            <option value="suspended"
                                                {{ $vendor->user->status == 'suspended' ? 'selected' : '' }}>
                                                Suspended
                                            </option>

                                        </select>

                                        <button class="btn btn-primary btn-sm">
                                            Update
                                        </button>

                                    </form>

                                    {{-- View --}}
                                    <a href="{{ route('admin.vendors.show', $vendor->id) }}"
                                       class="btn btn-info btn-sm text-white">

                                        View Products

                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.vendors.delete', $vendor->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete vendor permanently?')">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger btn-sm">
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="8"
                                class="text-center py-5">

                                No vendors found

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection