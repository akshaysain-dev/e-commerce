@extends('layouts.backend')

@section('title', 'Manage Products')

@section('styles')
<style>
    /* Table Enhancements */
    .table thead th {
        letter-spacing: 0.5px;
        border-bottom: 1px solid #f1f1f1;
    }
    .table tbody tr {
        transition: background 0.2s ease;
    }
    .table tbody tr:hover {
        background-color: #fcfcfc !important;
    }
    /* Soft Badges */
    .bg-soft-success { background: #e8f5e9; color: #2e7d32; }
    .bg-soft-info { background: #e3f2fd; color: #1565c0; }
    .bg-soft-secondary { background: #f5f5f5; color: #757575; }
    
    /* Action Buttons */
    .btn-white {
        background: #fff;
        border: 1px solid #eee;
    }
    .btn-white:hover {
        background: #f8f9fa;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center p-4 mb-4 bg-white border-bottom shadow-sm rounded animate__animated animate__fadeIn">
        <div class="mb-3 mb-md-0">
            <h2 class="fw-bold mb-1 text-dark tracking-tight">Products Inventory</h2>
            <p class="text-muted small mb-0">
                <span class="badge bg-soft-primary text-primary border border-primary-subtle me-2">Admin Portal</span>
                Manage your stock, pricing, and product status
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('product-types.index') }}" class="btn btn-outline-primary rounded-pill px-3 shadow-sm hover-lift">
                <i class="bi bi-tag me-1"></i> New Type
            </a>
            <a href="{{ route('admin.attributes.index') }}" class="btn btn-outline-primary rounded-pill px-3 shadow-sm hover-lift">
                <i class="bi bi-layers me-1"></i> New Variant
            </a>
            <a href="{{ route('admin_create_products') }}" class="btn btn-primary rounded-pill px-4 shadow-sm hover-lift">
                <i class="bi bi-plus-lg me-1"></i> Add Product
            </a>
            <a href="{{ route('products.import') }}" class="btn btn-info">
                <i class="bi bi-plus-lg me-1"></i> Import Products CSV
            </a>
            <a href="{{ route('products.export') }}" class="btn btn-success">
                <i class="fas fa-file-csv"></i> Export Products CSV
            </a>
        </div>
    </div>


    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 alert-dismissible fade show animate__animated animate__flipInX">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden animate__animated animate__fadeInUp">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-uppercase small fw-bold text-muted">
                        <th class="ps-4 py-3">Product</th>
                        <th class="py-3">Category</th>
                        <th class="py-3">Status</th>
                        <th class="py-3 text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <!-- Image Preview -->
                                <div class="rounded-3 me-3 overflow-hidden border" style="width: 50px; height: 50px;">
                                    <img src="{{ asset('storage/'.$product->image) }}" 
                                         alt="" class="w-100 h-100 object-fit-cover">
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $product->name }}</div>
                                    <small class="text-muted text-uppercase" style="font-size: 0.7rem;">SKU: {{ $product->firstVariant->sku ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-soft-info text-info rounded-pill px-3">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td>
                            @if($product->status)
                                <span class="badge bg-soft-success text-success rounded-pill px-3">
                                    <i class="bi bi-check-circle me-1"></i> Active
                                </span>
                            @else
                                <span class="badge bg-soft-secondary text-secondary rounded-pill px-3">
                                    <i class="bi bi-pause-circle me-1"></i> Inactive
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-white btn-sm px-3 border-end" title="Edit">
                                    Edit
                                </a>
                                <a href="{{ route('products.delete', $product->id) }}" 
                                   class="btn btn-white btn-sm px-3 text-danger" 
                                   onclick="return confirm('Delete this product permanently?');" title="Delete">
                                    Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-bag-x fs-1 text-muted d-block mb-3"></i>
                            <h5 class="text-muted">No products found in inventory</h5>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Section -->
        <div class="d-flex justify-content-between align-items-center p-4 bg-light border-top">
            <div class="small text-muted d-none d-md-block">
                Showing <span class="fw-bold">{{ $products->firstItem() }}</span> 
                to <span class="fw-bold">{{ $products->lastItem() }}</span> 
                of <span class="fw-bold">{{ $products->total() }}</span> items
            </div>
            <nav class="custom-pagination">
                {{ $products->links('pagination::bootstrap-5') }}
            </nav>
        </div>
    </div>
</div>
@endsection
