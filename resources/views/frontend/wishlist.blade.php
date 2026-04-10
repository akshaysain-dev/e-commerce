@extends('layouts.frontend')

@section('title', 'WishList')

@section('content')
<div class="container py-4">
    <h5 class="fw-bold mb-4"><i class="bi bi-heart-fill text-danger me-2"></i>My Wishlist
        <span class="badge bg-primary ms-1" style="font-size:12px;">{{ $wishlists->count() }}</span>
    </h5>

    @if($wishlists->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-heart fs-1 text-muted d-block mb-3"></i>
            <p class="text-muted">Your wishlist is empty.</p>
            <a href="{{ route('all_products') }}" class="btn btn-primary btn-sm">Browse Products</a>
        </div>
    @else
        <div class="row g-3">
            @foreach($wishlists as $item)
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100 rounded-3">
                    <a href="{{ url('products/'.$item->product->id) }}">
                        <img src="{{ asset('storage/'.$item->product->image) }}"
                             class="card-img-top rounded-top-3"
                             style="height:180px;object-fit:cover;"
                             onerror="this.src='https://placehold.co/300x180/e8f0fe/2874f0?text=No+Image'">
                    </a>
                    <div class="card-body p-2">
                        <p class="mb-1 fw-semibold text-truncate" style="font-size:13px;">{{ $item->product->name }}</p>
                        <p class="mb-2 text-success fw-bold" style="font-size:13px;">
                            ₹{{ number_format($item->product->firstVariant->margin_price) }}
                        </p>
                        <div class="d-flex gap-1">
                            <a href="{{ route('view_product', $item->product->id) }}"
                               class="btn btn-primary btn-sm flex-grow-1" style="font-size:12px;">
                                <i class="bi bi-cart-plus"></i> View
                            </a>
                            <form action="{{ route('wishlist.remove', $item->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" style="font-size:12px;">
                                    Remove
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection