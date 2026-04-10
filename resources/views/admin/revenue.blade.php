@extends('layouts.backend')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mt-4 mb-4">
{{-- Correct way to call a named route --}}
<a href="{{ route('admin.download.revenue') }}" class="btn btn-secondary mb-3">
    <i class="fa fa-download"></i> Download Revenue Report
</a>

	<div class="row mb-4">
		<!-- Total Order Amount (Revenue) -->
		<div class="col-md-4">
			<div class="card shadow border-left-primary">
				<div class="card-body">
					<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Order Amount</div>
					<div class="h5 mb-0 font-weight-bold text-gray-800">
						Rs. {{ number_format($totalPaidByCustomers, 2) }}
					</div>
				</div>
			</div>
		</div>

		<!-- Total Items Cost -->
		<div class="col-md-4">
			<div class="card shadow border-left-info">
				<div class="card-body">
					<div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Items Base Cost</div>
					<div class="h5 mb-0 font-weight-bold text-gray-800">
						Rs. {{ number_format($totalBaseCost, 2) }}
					</div>
				</div>
			</div>
		</div>
		<!-- Net Profit (The Difference) -->
		@if($totalBaseCost > $totalPaidByCustomers)
		<div class="col-md-4">
			<div class="card shadow border-left-danger">
				<div class="card-body">
					<div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Margin (Difference)</div>
					<div class="h5 mb-0 font-weight-bold text-gray-800">
						Rs. {{ number_format($totalMargin, 2) }}
						<small class="text-danger">Lose from these orders</small>
					</div>
				</div>
			</div>
		</div>
		@else
		<div class="col-md-4">
			<div class="card shadow border-left-success">
				<div class="card-body">
					<div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Margin (Difference)</div>
					<div class="h5 mb-0 font-weight-bold text-gray-800">
						Rs. {{ number_format($totalMargin, 2) }}
						<small class="text-muted">Profit earned from these orders</small>
					</div>
				</div>
			</div>
		</div>
		@endif
	</div>

	<!-- Daily Table -->
	<div class="card shadow">
		<div class="card-header">Daily Revenue Summary</div>
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Date</th>
						<th>Total Revenue</th>
					</tr>
				</thead>
				<tbody>
					@foreach($dailyRevenue as $day)
					<tr>
						<td>{{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}</td>
						<td>Rs. {{ number_format($day->daily_total, 2) }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection