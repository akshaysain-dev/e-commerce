@extends('layouts.frontend')

@section('title', ucfirst($query))

@section('content')

<div class="container bg-white py-4 px-lg-5">
    <h5 class="mb-4 text-dark">
        Showing results for "<span class="fw-bold text-primary">{{ $query }}</span>"
        <small class="text-muted fs-6">({{ $products->total() }} items found)</small>
    </h5>

    <div class="row g-2 border-top border-start">
        @foreach($products as $product)
            <!-- Using col-lg-2 to mimic Flipkart's 6-item desktop row -->
            <div class="col-xl-2 col-lg-3 col-md-4 col-6 border-end border-bottom p-0">
                <div class="card h-100 border-0 rounded-0 p-3 position-relative">
                    
                    <!-- Image -->
                    <div class="d-flex align-items-center justify-content-center mb-2" style="height: 180px;">
                        <img src="{{ asset('storage/'.$product->image) }}" 
                             class="img-fluid h-100" style="object-fit: contain;">
                    </div>

                    <!-- Details -->
                    <div class="card-body p-0 mt-2">
                        <p class="mb-1 text-muted small">{{ $product->brand ?? 'Brand' }}</p>
                        <h6 class="text-dark fw-normal text-truncate mb-1">{{ $product->name }}</h6>
                        
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold text-dark fs-6">₹{{ number_format($product->firstVariant->price ?? 0) }}</span>
                            <span class="text-success small fw-bold">Special Price</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('view_product', $product->id) }}" class="stretched-link"></a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-5 d-flex justify-content-center">
        {{ $products->appends(['q' => $query])->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection