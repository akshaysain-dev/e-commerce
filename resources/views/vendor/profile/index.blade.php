@extends('vendor.layouts.app')

@section('title', 'Vendor Profile')

@section('content')

<div class="container-fluid px-4 py-4">

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>
                    <h2 class="fw-bold mb-1">
                        Vendor Profile
                    </h2>

                    <p class="text-muted mb-0">
                        Manage your account and business details
                    </p>
                </div>

                @if($vendor->logo)

                    <img src="{{ asset('storage/'.$vendor->logo) }}"
                         width="80"
                         height="80"
                         class="rounded-circle border object-fit-cover">

                @endif

            </div>

            @if(session('success'))

                <div class="alert alert-success">

                    {{ session('success') }}

                </div>

            @endif

            <form action="{{ route('vendor.profile.update') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Name
                        </label>

                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name', $user->name) }}">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Email
                        </label>

                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email', $user->email) }}">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Password
                        </label>

                        <input type="password"
                               name="password"
                               class="form-control">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Phone
                        </label>

                        <input type="text"
                               name="phone"
                               class="form-control"
                               value="{{ old('phone', $vendor->phone) }}">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Shop Name
                        </label>

                        <input type="text"
                               name="shop_name"
                               class="form-control"
                               value="{{ old('shop_name', $vendor->shop_name) }}">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            GST Number
                        </label>

                        <input type="text"
                               name="gst_number"
                               class="form-control"
                               value="{{ old('gst_number', $vendor->gst_number) }}">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            PAN Number
                        </label>

                        <input type="text"
                               name="pan_number"
                               class="form-control"
                               value="{{ old('pan_number', $vendor->pan_number) }}">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Bank Name
                        </label>

                        <input type="text"
                               name="bank_name"
                               class="form-control"
                               value="{{ old('bank_name', $vendor->bank_name) }}">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Account Number
                        </label>

                        <input type="text"
                               name="account_number"
                               class="form-control"
                               value="{{ old('account_number', $vendor->account_number) }}">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            IFSC Code
                        </label>

                        <input type="text"
                               name="ifsc_code"
                               class="form-control"
                               value="{{ old('ifsc_code', $vendor->ifsc_code) }}">

                    </div>

                    <div class="col-12 mb-3">

                        <label class="form-label">
                            Address
                        </label>

                        <textarea name="address"
                                  rows="4"
                                  class="form-control">{{ old('address', $vendor->address) }}</textarea>

                    </div>

                    <div class="col-12 mb-4">

                        <label class="form-label">
                            Shop Logo
                        </label>

                        <input type="file"
                               name="logo"
                               class="form-control">

                    </div>

                </div>

                <button type="submit"
                        class="btn btn-primary px-5">

                    Update Profile

                </button>

            </form>

        </div>

    </div>

</div>

@endsection