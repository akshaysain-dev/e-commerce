@extends('layouts.frontend')

@section('title', 'Order Placed Successfully!')

@section('styles')
<style>
    .check-circle svg {
        stroke-dasharray: 60;
        stroke-dashoffset: 60;
        animation: drawCheck 0.5s ease 0.4s forwards;
    }
    @keyframes drawCheck {
        to { stroke-dashoffset: 0; }
    }
    .pop-in {
        animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }
    @keyframes popIn {
        from { transform: scale(0); opacity: 0; }
        to   { transform: scale(1); opacity: 1; }
    }
    .fade-up {
        opacity: 0;
        animation: fadeUp 0.5s ease forwards;
    }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .delay-1 { animation-delay: 0.5s; }
    .delay-2 { animation-delay: 0.65s; }
    .delay-3 { animation-delay: 0.8s; }
    .delay-4 { animation-delay: 0.95s; }
    .delay-5 { animation-delay: 1.1s; }
    .delay-6 { animation-delay: 1.2s; }

    .order-id-badge {
        border: 1.5px dashed #86efac;
    }
    .top-stripe {
        height: 5px;
        background: linear-gradient(90deg, #2ecc71, #27ae60, #1abc9c);
        border-radius: 16px 16px 0 0;
    }
    .btn-dark-custom {
        background: #1a1a1a;
        transition: background 0.2s, transform 0.15s;
    }
    .btn-dark-custom:hover {
        background: #333;
        transform: translateY(-1px);
    }
</style>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-center py-5" style="min-height: 80vh; background: #f8f7f4;">
    <div class="card border-0 shadow rounded-4 overflow-hidden" style="max-width: 540px; width: 100%;">

        {{-- Green top stripe --}}
        <div class="top-stripe"></div>

        <div class="card-body p-4 p-md-5 text-center">

            {{-- Animated Checkmark --}}
            <div class="check-circle pop-in rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4 shadow"
                 style="width: 88px; height: 88px; background: linear-gradient(135deg, #2ecc71, #27ae60);">
                <svg viewBox="0 0 24 24" width="40" height="40"
                     fill="none" stroke="white" stroke-width="3"
                     stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>

            {{-- Title --}}
            <h1 class="fw-bold fs-2 mb-1 fade-up delay-1">Order Placed! 🎉</h1>
            <p class="text-muted mb-4 fade-up delay-2">
                Thank you for your purchase. We'll get it packed and on its way soon.
            </p>

            {{-- Order ID Badge --}}
            @if(session('order_id'))
            <div class="order-id-badge rounded-3 bg-success bg-opacity-10 px-4 py-3 mb-4 fade-up delay-3">
                <small class="d-block text-muted text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.08em;">
                    Your Order ID
                </small>
                <span class="fw-bold fs-5 text-success">#{{ session('order_id') }}</span>
            </div>
            @endif

            {{-- Info Cards Row --}}
            <div class="row g-3 mb-4 fade-up delay-3">
                <div class="col-4">
                    <div class="bg-light rounded-3 p-3 text-start h-100">
                        <div class="fs-4 mb-1">📦</div>
                        <small class="text-uppercase text-muted d-block" style="font-size: 0.68rem; letter-spacing: 0.06em;">Status</small>
                        <span class="fw-semibold small">{{ session('success') ?? 'Processing' }}</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-light rounded-3 p-3 text-start h-100">
                        <div class="fs-4 mb-1">🚚</div>
                        <small class="text-uppercase text-muted d-block" style="font-size: 0.68rem; letter-spacing: 0.06em;">Delivery</small>
                        <span class="fw-semibold small">3–5 Days</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-light rounded-3 p-3 text-start h-100">
                        <div class="fs-4 mb-1">💵</div>
                        <small class="text-uppercase text-muted d-block" style="font-size: 0.68rem; letter-spacing: 0.06em;">Payment</small>
                        <span class="fw-semibold small"><p>{{ session('payment_method') ?? 'COD' }}</p></span>
                    </div>
                </div>
            </div>

            <hr class="border-dashed opacity-25 mb-4">

            {{-- Buttons --}}
            <a href="{{ route('home') }}"
               class="btn btn-dark-custom btn-dark btn-lg w-100 fw-semibold rounded-3 mb-2 fade-up delay-5">
                Continue Shopping
            </a>

            @if(Route::has('customer.orders'))
            <a href="{{ route('customer.orders') }}"
               class="btn btn-outline-secondary btn-lg w-100 fw-medium rounded-3 fade-up delay-6">
                View My Orders
            </a>
            @endif

        </div>
    </div>
</div>
@endsection