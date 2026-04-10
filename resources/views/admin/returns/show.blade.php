@extends('layouts.backend')

@section('title', 'Return Requests')

@section('content')
<div class="container mt-4">
@if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
    <div class="row">
        <!-- Bank Details Section -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Customer Bank Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>Account Holder:</strong> {{ $returnOrder->account_holder_name }}</p>
                    <p><strong>Bank Name:</strong> {{ $returnOrder->bank_name }}</p>
                    <p><strong>Account Number:</strong> <code class="text-dark">{{ $returnOrder->account_number }}</code></p>
                    <p><strong>IFSC / Routing:</strong> {{ $returnOrder->ifsc_code }}</p>
                    <hr>
                    <p><strong>Reason for Return:</strong><br> {{ $returnOrder->reason }}</p>
                </div>
            </div>
        </div>

        <!-- Action Section -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Process Refund</h5>
                </div>
                <div class="card-body text-center">
                    <h3 class="mb-4">Amount: ${{ number_format($returnOrder->refund_amount, 2) }}</h3>
                    
                    @if($returnOrder->status !== 'refunded')
                        <form action="{{ route('admin.returns.process', $returnOrder->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to send money to this bank account?')">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-lg w-100">
                                <i class="fas fa-university"></i> Send Refund via Stripe
                            </button>
                        </form>
                        <p class="text-muted mt-2"><small>This will use Stripe Connect Payouts to transfer funds.</small></p>
                    @else
                        <div class="alert alert-success">
                            <strong>Status: Refunded</strong><br>
                            Stripe ID: {{ $returnOrder->stripe_refund_id }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
