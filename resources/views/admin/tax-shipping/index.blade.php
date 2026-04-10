@extends('layouts.backend')

@section('title', 'Manage Tax & Shipping')

@section('content')
<div class="container mt-4 mb-4">

    <!-- Status Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Update/Add Settings Form -->
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Tax & Shipping Settings</div>
                <div class="card-body">
                    <!-- Update the route name to match your controller -->
                    <form action="{{ route('admin.tax_shipping.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tax_id" value="{{ $currentSettings->id ?? 'null' }}">
                        <div class="mb-3">
                            <label class="form-label">Tax (%)</label>
                            <input type="text" name="tax" class="form-control" placeholder="e.g. 18" value="{{ $currentSettings->tax ?? '' }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Shipping Charge</label>
                            <input type="text" name="shipping_charge" class="form-control" placeholder="e.g. 50.00" value="{{ $currentSettings->shipping_charge ?? '' }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Free Shipping Above (Max Charge Trigger)</label>
                            <input type="text" name="max_charge_for_shipping" class="form-control" placeholder="e.g. 1000.00" value="{{ $currentSettings->max_charge_for_shipping ?? '' }}" required>
                            <small class="text-muted">Orders above this amount will have no shipping fee.</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Save Configuration</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Current Configuration Overview -->
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Current Charges</div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Setting</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($currentSettings)
                            <tr>
                                <td><strong>Tax Rate</strong></td>
                                <td>{{ $currentSettings->tax }}%</td>
                            </tr>
                            <tr>
                                <td><strong>Base Shipping</strong></td>
                                <td>{{ number_format($currentSettings->shipping_charge, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Threshold for Free Shipping</strong></td>
                                <td>{{ number_format($currentSettings->max_charge_for_shipping, 2) }}</td>
                            </tr>
                            <tr>
                                <td><a href="{{ route('admin.tax_shipping.delete', $currentSettings->id) }}" class="btn btn-danger">Delete</a></td>
                            </tr>
                            @else
                            <tr>
                                <td colspan="2" class="text-center text-muted">No settings configured yet.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
