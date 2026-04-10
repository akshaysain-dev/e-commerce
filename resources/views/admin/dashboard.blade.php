@extends('layouts.backend')

@section('title', 'Admin Dashboard')

@section('styles')
<style>
    :root {
        --glass: rgba(255, 255, 255, 0.7);
        --shadow: 0 15px 35px rgba(0,0,0,0.05);
    }
    .dashboard-container {
        padding-top: 30px;
        background: #f4f7fe;
        min-height: 100vh;
    }
    .stat-card {
        border: none;
        border-radius: 24px;
        background: #fff;
        transition: all 0.3s ease;
        box-shadow: var(--shadow);
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
    }
    .trend-badge {
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 8px;
        font-weight: 700;
    }
    .icon-shape {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
    }
    .icon-shape svg{
        width:22px;
        height:22px;
    }
    .recent-activity {
        background: var(--glass);
        backdrop-filter: blur(10px);
        border-radius: 24px;
        border: 1px solid #fff;
    }
    .table thead th {
        background: transparent;
        border-bottom: 1px solid #eee;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 1px;
        color: #8898aa;
    }
	a{
		text-decoration:none;
	}
    .form-select:hover{
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <div class="container-fluid px-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-dark">Dashboard Overview</h2>
                <p class="text-muted">Welcome back,
                <span class="text-primary fw-600">{{ session('admin_name') }}</span></p>
            </div>
        </div>
    <div class="row mt-4 mb-4">

        <!-- Revenue Chart -->
        <div class="col-md-6">
            <div class="card shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Revenue</h6>
                    <select class="form-select w-auto" id="revenueFilter">
                        <option value="7">Last 7 Days</option>
                        <option value="30">Last 30 Days</option>
                        <option value="92">Last 3 Months</option>
                        <option value="183">Last 6 Months</option>
                        <option value="365">Last 1 Year</option>
                        <option value="all">All Time</option>
                    </select>
                </div>
                <div style="position:relative; height:280px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Orders Chart -->
        <div class="col-md-6">
            <div class="card shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Orders</h6>
                    <select class="form-select w-auto" id="ordersFilter">
                        <option value="7">Last 7 Days</option>
                        <option value="30">Last 30 Days</option>
                        <option value="92">Last 3 Months</option>
                        <option value="183">Last 6 Months</option>
                        <option value="365">Last 1 Year</option>
                        <option value="all">All Time</option>
                    </select>
                </div>
                <div style="position:relative; height:280px;">
                    <canvas id="ordersChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Customers Chart -->
        <div class="col-md-6 mt-4">
            <div class="card chart-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Customers Analytics</span>
                    <select id="customersFilter" class="form-select w-auto">
                        <option value="7">Last 7 Days</option>
                        <option value="30">Last 30 Days</option>
                        <option value="92">Last 3 Months</option>
                        <option value="183">Last 6 Months</option>
                        <option value="365">Last 1 Year</option>
                        <option value="all">All Time</option>
                    </select>
                </div>
                <div class="card-body">
                    <div style="position:relative; height:260px;">
                        <canvas id="customersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Chart -->
        <div class="col-md-6 mt-4">
            <div class="card chart-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Sales Analytics</span>
                    <select id="salesFilter" class="form-select w-auto">
                        <option value="7">Last 7 Days</option>
                        <option value="30">Last 30 Days</option>
                        <option value="92">Last 3 Months</option>
                        <option value="183">Last 6 Months</option>
                        <option value="365">Last 1 Year</option>
                        <option value="all">All Time</option>
                    </select>
                </div>
                <div class="card-body">
                    <div style="position:relative; height:260px;">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
        
    <div class="row g-4 mb-5">

        <!-- Products -->
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('admin_product') }}">
            <div class="card stat-card p-3">
                <div class="d-flex justify-content-between align-items-start mb-3">

                    <div class="icon-shape bg-light-success">

                        <svg fill="none" stroke="green" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        </svg>

                    </div>

                    <span class="trend-badge bg-light-success text-success" style="font-size:20px;" >+12%</span>
                </div>

                <h6 class="text-muted mb-1 small text-uppercase fw-bold">Total Products</h6>
                <h3 class="fw-bold mb-0">{{ $products_count ?? '1,240' }}</h3>

            </div>
            </a>
        </div>

        <!-- Categories -->
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('admin_category') }}">
            <div class="card stat-card p-3">
                <div class="d-flex justify-content-between align-items-start mb-3">

                    <div class="icon-shape bg-light-primary">

                        <svg fill="none" stroke="#0d6efd" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                        </svg>

                    </div>

                    <span class="trend-badge bg-light-primary text-primary" style="font-size:20px;">Active</span>
                </div>

                <h6 class="text-muted mb-1 small text-uppercase fw-bold">Categories</h6>
                <h3 class="fw-bold mb-0">{{ $categories_count ?? '45' }}</h3>

            </div>
            </a>
        </div>

        <!-- Orders -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card p-3">

                <div class="d-flex justify-content-between align-items-start mb-3">

                    <div class="icon-shape bg-light-info">

                        <svg fill="none" stroke="#0dcaf0" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="9" cy="21" r="1"/>
                        <circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>

                    </div>

                    <span class="trend-badge bg-light-info text-info" style="font-size:20px;"><span style="color:black;">Today </span> + {{ $ordersToday ?? '45' }}</span>
                </div>

                <h6 class="text-muted mb-1 small text-uppercase fw-bold">Pending Orders</h6>
                <h3 class="fw-bold mb-0">{{ $orders_count ?? '18' }}</h3>

            </div>
        </div>

        <!-- Customers -->
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('admin_customers') }}">
            <div class="card stat-card p-3">

                <div class="d-flex justify-content-between align-items-start mb-3">

                    <div class="icon-shape bg-light-warning">

                        <svg fill="none" stroke="orange" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M7 21v-2a4 4 0 0 1 3-3.87"/>
                        <circle cx="12" cy="7" r="4"/>
                        </svg>

                    </div>

                    <span class="trend-badge bg-light-warning text-warning" style="font-size:20px;" ><span style="color:black;">Today</span> + {{ $newCustomers ?? 0 }}</span>
                </div>

                <h6 class="text-muted mb-1 small text-uppercase fw-bold">Total Customers</h6>
                <h3 class="fw-bold mb-0">{{ $customers_count ?? '850' }}</h3>

            </div>
            </a>
        </div>

    </div>
	
	<div class="row g-4 mb-5">
	<h1 class="text-muted">Reports </h1>
        <!-- Total revanue -->
        <div class="col-xl-3 col-md-6">
		<a href="{{ route('admin.revenue') }}" >
            <div class="card stat-card p-3">
                <div class="d-flex justify-content-between align-items-start mb-3">

                    <div class="icon-shape bg-light-success">

                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
						  <path fill-rule="evenodd" d="M7 6a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-2v-4a3 3 0 0 0-3-3H7V6Z" clip-rule="evenodd"/>
						  <path fill-rule="evenodd" d="M2 11a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-7Zm7.5 1a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5Z" clip-rule="evenodd"/>
						  <path d="M10.5 14.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>
						</svg>
						</div>

                    <span class="trend-badge bg-light-success text-success" style="font-size:20px;"><span style="color:black;">Today</span> +{{ $totalRevenueToday ?? 100 }}</span>
                </div>

                <h6 class="text-muted mb-1 small text-uppercase fw-bold">Total Revanue</h6>
                <h3 class="fw-bold mb-0">{{ $totalRevenue ?? '1,240' }}</h3>

            </div>
			</a>
        </div>

		<!-- Low Stock Alert -->
        <div class="col-xl-3 col-md-6">
		<a href="{{ route('admin.stock.alert') }}">
            <div class="card stat-card p-3">

                <div class="d-flex justify-content-between align-items-start mb-3">

                    <div class="icon-shape bg-light-info">

						<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 256 257"><path fill="#FF364E" d="m177.246 78.768l61.363 30.971a31.484 31.484 0 0 1 17.29 25.637l.101 2.494v70.9a47.263 47.263 0 0 1-44.57 47.184l-2.676.077H.158l78.682-78.783h98.406v-98.48ZM255.856 0l-78.608 78.769H78.84v98.479l-61.52-30.885A31.513 31.513 0 0 1 .099 120.71L0 118.218V47.334A47.263 47.263 0 0 1 44.162.102l3.1-.102h208.594Z"/></svg>

                    </div>

                    <span class="trend-badge bg-light-info text-info" style="font-size:20px;" ><span style="color:black;">Today </span> + {{ $lowStockToday ?? '18' }}</span>
                </div>

                <h6 class="text-muted mb-1 small text-uppercase fw-bold">Low Stock Products</h6>
                <h3 class="fw-bold mb-0">{{ $lowStockAlert ?? '18' }}</h3>

            </div>
			</a>
        </div>

        <!-- Total Order Today -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card p-3">
                <div class="d-flex justify-content-between align-items-start mb-3">

                    <div class="icon-shape bg-light-primary">

						<svg fill="none" stroke="#0dcaf0" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="9" cy="21" r="1"/>
                        <circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>

                    </div>

                    <span class="trend-badge bg-light-primary text-primary" style="font-size:20px;" >Active</span>
                </div>

                <h6 class="text-muted mb-1 small text-uppercase fw-bold">Orders Today</h6>
                <h3 class="fw-bold mb-0">{{ $ordersToday ?? '45' }}</h3>

            </div>
        </div>

        <!-- Customers -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card p-3">

                <div class="d-flex justify-content-between align-items-start mb-3">

                    <div class="icon-shape bg-light-warning">

                        <svg fill="none" stroke="orange" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M7 21v-2a4 4 0 0 1 3-3.87"/>
                        <circle cx="12" cy="7" r="4"/>
                        </svg>

                    </div>

                    <span class="trend-badge bg-light-warning text-warning" style="font-size:20px;">Today</span>
                </div>

                <h6 class="text-muted mb-1 small text-uppercase fw-bold">New Customers</h6>
                <h3 class="fw-bold mb-0">{{ $newCustomers ?? '850' }}</h3>

            </div>
        </div>

    </div>

    <!-- Recent Orders -->
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm recent-activity p-4">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Recent Orders</h5>
                        <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-light rounded-pill px-3">View All</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td class="fw-bold text-dark small">#{{ $order->unique_order_id }}</td>
                                    <td>
                                        <div class="fw-semibold small">{{ $order->customer->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $order->created_at->format('d M Y') }}</small>
                                    </td>
                                    <td class="fw-bold text-primary small">₹{{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        @php
                                            $pillClass = [
                                                'pending'    => 'bg-warning text-dark',
                                                'on_the_way' => 'bg-info text-dark',
                                                'completed'  => 'bg-success text-white',
                                                'cancel'     => 'bg-danger text-white',
                                            ][$order->status] ?? 'bg-secondary text-white';
                                        @endphp
                                        <span class="badge rounded-pill {{ $pillClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="#"
                                        class="btn btn-sm btn-light px-3 rounded-pill">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No orders yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        <div class="col-lg-4 mb-4">

            <div class="card border-0 shadow-sm p-4" style="border-radius:24px;">

                <h5 class="fw-bold mb-4">Quick Shortcuts</h5>

                    <div class="d-grid gap-2">

                        <a href="{{ route('admin_product') }}" class="btn btn-outline-primary py-2 rounded-3 text-start">
                            ➕ Manage Products
                        </a>

                        <a href="{{ route('admin_category') }}" class="btn btn-outline-info py-2 rounded-3 text-start">
                            🏷 Manage Category
                        </a>

                        <a href="{{ route('admin_customers') }}" class="btn btn-outline-dark py-2 rounded-3 text-start">
                            📊 View Customers
                        </a>

                        <a href="{{ route('banners.index') }}" class="btn btn-outline-primary py-2 rounded-3 text-start">
                            🖼️ Home Page Banners
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="loadModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger">Stock Alert!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        You have {{ $lowStockAlert }} low-stock items that need your attention. Do you want to check them now?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Later</button>
        <a href="{{ route('admin.stock.alert') }}" class="btn btn-primary">View Now</a>
      </div>
    </div>
  </div>
</div>

@endsection 


    
@push('scripts')
@if($lowStockAlert > 0)
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('loadModal'));
        myModal.show();
    });
</script>
@endif



<script>
let revenueChart = null;
let ordersChart = null;
let customersChart = null;
let salesChart = null;

async function fetchData(days) {
    try {
        const res = await fetch(`/admin/chart-data?days=${days}`);
        return await res.json();
    } catch (err) {
        console.error("API Error:", err);
        return [];
    }
}

function baseOptions(count) {
    return {
        responsive: true,
        maintainAspectRatio: false,
        animation: { duration: 500, easing: 'easeInOutQuart' },
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: {
                labels: {
                    color: '#555',
                    font: { size: 12, weight: '500' },
                    boxWidth: 12,
                    boxHeight: 12,
                    borderRadius: 3
                }
            },
            tooltip: {
                backgroundColor: '#fff',
                titleColor: '#333',
                bodyColor: '#555',
                borderColor: 'rgba(0,0,0,0.1)',
                borderWidth: 1,
                padding: 10,
                cornerRadius: 8
            }
        },
        scales: {
            x: {
                grid: { color: 'rgba(0,0,0,0.05)' },
                ticks: {
                    color: '#888',
                    font: { size: 11 },
                    maxRotation: count > 30 ? 45 : 0,
                    autoSkip: true,
                    maxTicksLimit: count > 90 ? 12 : count > 30 ? 10 : count > 14 ? 8 : count
                }
            },
            y: {
                grid: { color: 'rgba(0,0,0,0.05)' },
                ticks: { color: '#888', font: { size: 11 } },
                beginAtZero: true
            }
        }
    };
}

async function loadRevenueChart(days = 7) {
    const data = await fetchData(days);
    const labels  = data.map(i => i.date);
    const revenue = data.map(i => i.revenue || 0);
    const cost    = data.map(i => i.cost    || 0);
    const profit  = data.map(i => i.profit  || 0);

    if (revenueChart) revenueChart.destroy();

    const ctx = document.getElementById('revenueChart').getContext('2d');

    const gradRevenue = ctx.createLinearGradient(0, 0, 0, 300);
    gradRevenue.addColorStop(0, 'rgba(78,115,223,0.3)');
    gradRevenue.addColorStop(1, 'rgba(78,115,223,0)');

    const gradProfit = ctx.createLinearGradient(0, 0, 0, 300);
    gradProfit.addColorStop(0, 'rgba(28,200,138,0.25)');
    gradProfit.addColorStop(1, 'rgba(28,200,138,0)');

    revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Revenue',
                    data: revenue,
                    borderColor: '#4e73df',
                    backgroundColor: gradRevenue,
                    borderWidth: 2.5,
                    tension: 0.4,
                    fill: true,
                    pointRadius: data.length > 20 ? 0 : 3,
                    pointHoverRadius: 5
                },
                {
                    label: 'Cost',
                    data: cost,
                    borderColor: '#e74a3b',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    borderDash: [5, 3],
                    tension: 0.4,
                    fill: false,
                    pointRadius: data.length > 20 ? 0 : 3,
                    pointHoverRadius: 5
                },
                {
                    label: 'Profit / Loss',
                    data: profit,
                    borderColor: '#1cc88a',
                    backgroundColor: gradProfit,
                    borderWidth: 2.5,
                    tension: 0.4,
                    fill: true,
                    pointRadius: data.length > 20 ? 0 : 3,
                    pointHoverRadius: 5
                }
            ]
        },
        options: baseOptions(data.length)
    });
}

async function loadOrdersChart(days = 7) {
    const data   = await fetchData(days);
    const labels = data.map(i => i.date);
    const orders = data.map(i => i.orders || 0);

    if (ordersChart) ordersChart.destroy();

    ordersChart = new Chart(document.getElementById('ordersChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Orders',
                data: orders,
                backgroundColor: 'rgba(28,200,138,0.75)',
                hoverBackgroundColor: '#1cc88a',
                borderRadius: data.length > 60 ? 2 : data.length > 30 ? 4 : 6,
                borderSkipped: false
            }]
        },
        options: {
            ...baseOptions(data.length),
            scales: {
                ...baseOptions(data.length).scales,
                x: {
                    ...baseOptions(data.length).scales.x,
                    categoryPercentage: data.length > 30 ? 0.9 : 0.7,
                    barPercentage:      data.length > 30 ? 0.95 : 0.8
                }
            }
        }
    });
}

async function loadCustomersChart(days = 7) {
    const data      = await fetchData(days);
    const labels    = data.map(i => i.date);
    const customers = data.map(i => i.customers || 0);

    if (customersChart) customersChart.destroy();

    const ctx = document.getElementById('customersChart').getContext('2d');

    const grad = ctx.createLinearGradient(0, 0, 0, 260);
    grad.addColorStop(0, 'rgba(246,194,62,0.3)');
    grad.addColorStop(1, 'rgba(246,194,62,0)');

    customersChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Customers',
                data: customers,
                borderColor: '#f6c23e',
                backgroundColor: grad,
                borderWidth: 2.5,
                tension: 0.4,
                fill: true,
                pointRadius: data.length > 20 ? 0 : 3,
                pointHoverRadius: 5
            }]
        },
        options: baseOptions(data.length)
    });
}

async function loadSalesChart(days = 7) {
    const data   = await fetchData(days);
    const labels = data.map(i => i.date);
    const sales  = data.map(i => i.sales || 0);

    if (salesChart) salesChart.destroy();

    const ctx = document.getElementById('salesChart').getContext('2d');

    const grad = ctx.createLinearGradient(0, 0, 0, 260);
    grad.addColorStop(0, 'rgba(231,74,59,0.25)');
    grad.addColorStop(1, 'rgba(231,74,59,0)');

    salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Sales',
                data: sales,
                borderColor: '#e74a3b',
                backgroundColor: grad,
                borderWidth: 2.5,
                tension: 0.4,
                fill: true,
                pointRadius: data.length > 20 ? 0 : 3,
                pointHoverRadius: 5
            }]
        },
        options: baseOptions(data.length)
    });
}

document.addEventListener('DOMContentLoaded', () => {
    loadRevenueChart();
    loadOrdersChart();
    loadCustomersChart();
    loadSalesChart();
});

document.getElementById('revenueFilter')?.addEventListener('change', function () { loadRevenueChart(this.value); });
document.getElementById('ordersFilter')?.addEventListener('change', function () { loadOrdersChart(this.value); });
document.getElementById('customersFilter')?.addEventListener('change', function () { loadCustomersChart(this.value); });
document.getElementById('salesFilter')?.addEventListener('change', function () { loadSalesChart(this.value); });
</script>
@endpush


