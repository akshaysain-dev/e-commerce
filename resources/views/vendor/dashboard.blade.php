@extends('vendor.layouts.app')

@section('title', 'Vendor Dashboard')

@section('content')

<div class="container-fluid px-4 py-4">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h2 class="fw-bold mb-2">
                        Welcome Back,
                        {{ $user->name }}
                    </h2>

                    <p class="text-muted mb-0">
                        Manage your products, orders, sales and earnings from your vendor dashboard.
                    </p>

                    <div class="mt-3 d-flex flex-wrap gap-2">

                        @if($vendor->stripe_onboarded)

                            <span class="badge bg-success px-3 py-2">
                                Stripe Connected
                            </span>

                        @else

                            <span class="badge bg-danger px-3 py-2">
                                Stripe Not Connected
                            </span>

                        @endif

                        <span class="badge bg-primary px-3 py-2">
                            {{ $vendor->shop_name }}
                        </span>

                    </div>

                </div>

                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">

                    @if(!$vendor->stripe_account_id)

                        <a href="{{ route('vendor.stripe.connect') }}"
                           class="btn btn-primary rounded-pill px-4">

                            Connect Stripe

                        </a>

                    @endif

                </div>

            </div>

        </div>

    </div>

    <div class="row g-4">

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
                             style="width:65px;height:65px;">

                            <i class="fa fa-box fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

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
                             style="width:65px;height:65px;">

                            <i class="fa fa-shopping-cart fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-2">
                                Total Sales
                            </p>

                            <h2 class="fw-bold">
                                ₹{{ number_format($totalSales, 2) }}
                            </h2>

                        </div>

                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center"
                             style="width:65px;height:65px;">

                            <i class="fa fa-chart-line fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-2">
                                Total Earnings
                            </p>

                            <h2 class="fw-bold text-success">
                                ₹{{ number_format($earnings, 2) }}
                            </h2>

                        </div>

                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center"
                             style="width:65px;height:65px;">

                            <i class="fa fa-wallet fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="row mt-4 g-4">

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <h5 class="fw-bold mb-0">
                        Order Statistics
                    </h5>

                </div>

                <div class="card-body px-4 pb-4">

                    <div class="row g-4">

                        <div class="col-6">

                            <div class="border rounded-4 p-4 text-center">

                                <h3 class="fw-bold text-warning">
                                    {{ $pendingOrders }}
                                </h3>

                                <p class="text-muted mb-0">
                                    Pending Orders
                                </p>

                            </div>

                        </div>

                        <div class="col-6">

                            <div class="border rounded-4 p-4 text-center">

                                <h3 class="fw-bold text-success">
                                    {{ $completedOrders }}
                                </h3>

                                <p class="text-muted mb-0">
                                    Completed Orders
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <h5 class="fw-bold mb-0">
                        Store Information
                    </h5>

                </div>

                <div class="card-body px-4 pb-4">

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Shop Name
                        </small>

                        <strong>
                            {{ $vendor->shop_name }}
                        </strong>

                    </div>

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Phone
                        </small>

                        <strong>
                            {{ $vendor->phone }}
                        </strong>

                    </div>

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            GST Number
                        </small>

                        <strong>
                            {{ $vendor->gst_number ?? 'N/A' }}
                        </strong>

                    </div>

                    <div>

                        <small class="text-muted d-block">
                            Address
                        </small>

                        <strong>
                            {{ $vendor->address }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card border-0 shadow-sm rounded-4 mt-4">

        <div class="card-header bg-white border-0 pt-4 px-4">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="fw-bold mb-0">
                    Recent Orders
                </h5>

                <a href="{{ route('vendor.earnings') }}"
                   class="btn btn-sm btn-primary rounded-pill px-3">

                    View All

                </a>

            </div>

        </div>

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead class="bg-light">

                    <tr>

                        <th class="ps-4 py-3">
                            Order
                        </th>

                        <th>
                            Product
                        </th>

                        <th>
                            Amount
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="pe-4">
                            Date
                        </th>

                    </tr>

                </thead>

                <tbody id="liveOrdersTable">

                    @forelse($recentOrders as $item)

                        <tr>

                            <td class="ps-4">

                                {{ $item->order->unique_order_id ?? '-' }}

                            </td>

                            <td>

                                {{ $item->product->name ?? '-' }}

                            </td>

                            <td>

                                ₹{{ number_format($item->vendor_amount, 2) }}

                            </td>

                            <td>

                                @if($item->order->status == 'paid')

                                    <span class="badge bg-success">
                                        Paid
                                    </span>

                                @elseif($item->order->status == 'pending')

                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ ucfirst($item->order->status) }}
                                    </span>

                                @endif

                            </td>

                            <td class="pe-4">

                                {{ $item->created_at->format('d M Y') }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="text-center py-5 text-muted">

                                No recent orders found.

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

function loadLiveOrders()
{
    $.ajax({

        url: "{{ route('vendor.live.orders') }}",

        type: "GET",

        success: function(response)
        {
            let html = '';

            response.forEach(function(item){

                html += `
                
                <tr>

                    <td class="ps-4">
                        ${item.order?.unique_order_id ?? '-'}
                    </td>

                    <td>
                        ${item.product?.name ?? '-'}
                    </td>

                    <td>
                        ₹${parseFloat(item.vendor_amount).toFixed(2)}
                    </td>

                    <td>
                        <span class="badge bg-success">
                            ${item.order?.status ?? '-'}
                        </span>
                    </td>

                    <td class="pe-4">

                        ${new Date(item.created_at).toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        })}

                    </td>

                </tr>
                
                `;
            });

            $('#liveOrdersTable').html(html);
        }

    });
}

loadLiveOrders();

setInterval(function(){

    loadLiveOrders();

}, 5000);

</script>

@endpush