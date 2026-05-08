@extends('vendor.layouts.app')

@section('title', 'Vendor Dashboard')

@section('content')

<div class="container-fluid">

    <!-- Welcome Card -->

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h3 class="fw-bold mb-1">

                        Welcome,
                        {{ $user->name }}

                    </h3>

                    <p class="text-muted mb-0">

                        {{ $vendor->shop_name ?? 'Vendor Shop' }}

                    </p>

                </div>

                <div>
@if(!$vendor->stripe_account_id)

    <a href="{{ route('vendor.stripe.connect') }}"
       class="btn btn-primary">

        Connect Stripe

    </a>

@endif
                    <i class="fa fa-store fa-3x text-dark opacity-50"></i>

                </div>

            </div>

        </div>

    </div>

    <!-- Stats -->

    <div class="row g-4">
@if($vendor->stripe_onboarded)

    <span class="badge bg-success">
        Stripe Connected
    </span>

@else

    <span class="badge bg-danger">
        Stripe Not Connected
    </span>

@endif
        <!-- Products -->

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-2">
                                Total Products
                            </p>
    
                            <h2 class="fw-bold">
                                {{ $totalProducts }}
                            </h2>

                        </div>

                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                             style="width:60px;height:60px;">

                            <i class="fa fa-box fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Orders -->

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-2">
                                Total Orders
                            </p>

                            <h2 class="fw-bold">
                                {{ $totalOrders }}
                            </h2>

                        </div>

                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
                             style="width:60px;height:60px;">

                            <i class="fa fa-shopping-cart fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Sales -->

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-2">
                                Total Sales
                            </p>

                            <h2 class="fw-bold">
                                ₹{{ $totalSales }}
                            </h2>

                        </div>

                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center"
                             style="width:60px;height:60px;">

                            <i class="fa fa-chart-line fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Earnings -->

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-2">
                                Earnings
                            </p>

                            <h2 class="fw-bold">
                                ₹{{ $earnings }}
                            </h2>

                        </div>

                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center"
                             style="width:60px;height:60px;">

                            <i class="fa fa-wallet fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Recent Activity -->

    <div class="card border-0 shadow-sm rounded-4 mt-5">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0 fw-bold">

                Recent Activity

            </h5>

        </div>

        <div class="card-body">

            <p class="text-muted mb-0">

                No recent activity found.

            </p>

        </div>

    </div>

</div>

@endsection