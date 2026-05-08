@extends('layouts.backend')

@section('title', 'Manage Vendors')

@section('content')

<div class="container-fluid py-4">

    <div class="row">

        <div class="col-12">

            {{-- Success Message --}}

            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    {{ session('success') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"></button>

                </div>

            @endif

            {{-- Error Message --}}

            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <div class="card border-0 shadow-sm rounded-3">

                <!-- Header -->

                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">

                    <h5 class="mb-0 fw-bold">

                        <i class="fa fa-store me-2"></i>

                        Vendor Management

                    </h5>

                    <span class="badge bg-dark">

                        {{ $vendors->count() }} Vendors

                    </span>

                </div>

                <!-- Body -->

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>ID</th>

                                    <th>Vendor</th>

                                    <th>Shop</th>

                                    <th>Email</th>

                                    <th>Status</th>

                                    <th>Commission</th>

                                    <th class="text-end pe-4">Actions</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($vendors as $vendor)

                                <tr>

                                    <td>
                                        #{{ $vendor->id }}
                                    </td>

                                    <td>

                                        <div class="fw-bold">

                                            {{ $vendor->user->name }}

                                        </div>

                                    </td>

                                    <td>

                                        {{ $vendor->shop_name }}

                                    </td>

                                    <td>

                                        {{ $vendor->user->email }}

                                    </td>

                                    <td>

                                        <span class="badge
                                            @if($vendor->user->status == 'approved')
                                                bg-success
                                            @elseif($vendor->user->status == 'pending')
                                                bg-warning text-dark
                                            @elseif($vendor->user->status == 'rejected')
                                                bg-danger
                                            @else
                                                bg-secondary
                                            @endif
                                        ">

                                            {{ ucfirst($vendor->user->status) }}

                                        </span>

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
                                                   class="form-control form-control-sm"
                                                   style="width:90px;">

                                            <button class="btn btn-sm btn-dark">

                                                Save

                                            </button>

                                        </form>

                                    </td>

                                    <td class="text-end pe-4">

                                        <form action="{{ route('admin.vendors.status', $vendor->id) }}"
                                              method="POST"
                                              class="d-flex justify-content-end gap-2">

                                            @csrf

                                            <select name="status"
                                                    class="form-select form-select-sm"
                                                    style="width:140px;">

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

                                            <button type="submit"
                                                    class="btn btn-primary btn-sm">

                                                Update

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                                @empty

                                <tr>

                                    <td colspan="7"
                                        class="text-center py-5">

                                        <h5 class="text-muted">

                                            No vendors found

                                        </h5>

                                    </td>

                                </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection