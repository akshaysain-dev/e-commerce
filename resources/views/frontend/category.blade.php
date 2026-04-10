@extends('layouts.frontend')

@section('title', $category->name)

@section('content')
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">{{ $category->name }}</h2>
        <!-- <a href="#" class="btn btn-outline-dark btn-sm">View All</a> -->
    </div>

    <div class="row g-4">
        @foreach($products as $product)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="position-relative">
                    <img src="{{ asset('storage/'.$product->image) }}"
                         class="card-img-top img-fluid"
                         style="height:220px; object-fit:cover;">
                    <span class="badge bg-success position-absolute top-0 end-0 m-2">
                        ₹{{ $product->price }}
                    </span>
                </div>

                <div class="card-body d-flex flex-column">
                    <small class="text-muted">
                        {{ $product->category->name ?? 'Category' }}
                    </small>
                    <h5 class="card-title mt-1">
                        {{ $product->name }}
                    </h5>
                    <p class="text-muted small">
                        {{ \Illuminate\Support\Str::limit($product->description, 70) }}
                    </p>

                    <div class="mt-auto">
                        <p class="fw-bold text-success mb-1">
                            ₹{{ number_format($product->price,2) }}
                        </p>

                        @if($product->stock > 0)
                            <span class="badge bg-success mb-2">In Stock ({{ $product->stock }})</span>
                        @else
                            <span class="badge bg-danger mb-2">Out of Stock</span>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('view_product', $product->id) }}" class="btn btn-outline-primary btn-sm">
                                View
                            </a>
                            <button class="btn btn-dark btn-sm" {{ $product->stock == 0 ? 'disabled' : '' }}>
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-between align-items-center mt-4 p-3 bg-white shadow-sm rounded-4 border animate__animated animate__fadeInUp">
    
        <!-- Info Text -->
        <div class="small text-muted d-none d-md-block">
            Showing <span class="fw-bold text-dark">{{ $products->firstItem() }}</span> 
            to <span class="fw-bold text-dark">{{ $products->lastItem() }}</span> 
            of <span class="fw-bold text-dark">{{ $products->total() }}</span> products
        </div>
        
        <!-- Pagination Links -->
        <nav class="shadow-none">
            {{ $products->links('pagination::bootstrap-5') }}
        </nav>

    </div>

</div>
@endsection