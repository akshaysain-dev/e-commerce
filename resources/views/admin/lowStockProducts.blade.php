@extends('layouts.backend')

@section('title', 'Low Stock')

@section('content')

<div class="container-fluid bg-white py-4 border-top">
    <div class="d-flex justify-content-between align-items-center mb-4 px-lg-5">
        <h5 class="fw-bold text-dark m-0">Currently Out of Stock</h5>
        <span class="badge bg-light text-dark border fw-normal">{{ $lowStockproducts->total() }} Items Found</span>
    </div>

    <div class="row g-3 px-lg-5">
        @foreach($lowStockproducts as $variant)
            <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                <div class="card h-100 border-0 rounded-0 p-3 position-relative shadow-sm" style="transition: transform 0.2s;">
                    
                    <!-- Grayscale Image Container for Out of Stock Look -->
                    <div class="d-flex align-items-center justify-content-center mb-3 position-relative" style="height: 200px;">
                        <img src="{{ asset('storage/'.$variant->product->image) }}" 
                             class="img-fluid h-100 opacity-50" 
                             style="object-fit: contain; filter: grayscale(100%);"
                             alt="{{ $variant->product->name }}">
                        
                        <!-- Floating "Sold Out" Label -->
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <span class="bg-dark text-white px-3 py-2 fw-bold small opacity-75 rounded-1">OUT OF STOCK</span>
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="card-body p-0">
                        <p class="text-muted small mb-1">{{ $variant->product->category->name ?? 'Category' }}</p>
                        <h6 class="text-dark fw-normal text-truncate mb-2" title="{{ $variant->product->name }}">
                            {{ $variant->product->name }}
                        </h6>

                        <div class="d-flex align-items-baseline gap-2 mb-3">
                            <span class="text-muted fw-bold fs-5">₹{{ number_format($variant->price) }}</span>
                        </div>

                        <div class="d-grid mt-auto">
                            <button class="btn btn-primary rounded-1 fw-bold py-2 shadow-sm">
                                <i class="bi bi-bell-fill"></i> Update Inventory
                            </button>
                        </div>
                    </div>

                    <a href="{{ route('products.edit', $variant->product->id) }}" class="stretched-link"></a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Professional Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $lowStockproducts->links('pagination::bootstrap-5') }}
    </div>
</div>


@endsection 