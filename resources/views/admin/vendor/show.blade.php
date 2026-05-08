@extends('layouts.backend')

@section('title', 'Vendor Products')

@section('content')

<div class="container-fluid py-4">

    {{-- Top Card --}}
    <div class="card border-0 shadow rounded-4 mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h3 class="fw-bold mb-1">
                        {{ $vendor->shop_name }}
                    </h3>

                    <p class="text-muted mb-2">
                        {{ $vendor->address }}
                    </p>

                    <div class="d-flex flex-wrap gap-3">

                        <span>
                            <strong>Owner:</strong>
                            {{ $vendor->user->name }}
                        </span>

                        <span>
                            <strong>Email:</strong>
                            {{ $vendor->user->email }}
                        </span>

                        <span>
                            <strong>Phone:</strong>
                            {{ $vendor->phone }}
                        </span>

                    </div>

                </div>

                <div class="col-md-4 text-md-end mt-3 mt-md-0">

                    <a href="{{ route('admin.vendors.index') }}"
                       class="btn btn-dark">

                        Back

                    </a>

                </div>

            </div>

        </div>

    </div>

    {{-- Products --}}
    <div class="row">

        @forelse($vendor->products as $product)

        <div class="col-md-6 col-lg-4 mb-4">

            <div class="card border-0 shadow rounded-4 h-100">

                <img src="{{ asset('storage/'.$product->image) }}"
                     class="card-img-top"
                     style="height:240px;object-fit:cover;">

                <div class="card-body">

                    <h5 class="fw-bold">
                        {{ $product->name }}
                    </h5>

                    <p class="text-muted small">
                        {{ Str::limit($product->description, 80) }}
                    </p>

                    <div class="mb-3">

                        @if($product->status)

                            <span class="badge bg-success">
                                Active
                            </span>

                        @else

                            <span class="badge bg-danger">
                                Inactive
                            </span>

                        @endif

                    </div>

                    {{-- Variants --}}
                    <div class="border rounded p-2 mb-3 bg-light">

                        @foreach($product->variants as $variant)

                            <div class="mb-2">

                                <strong>
                                    {{ $variant->name }}
                                </strong>

                                <br>

                                ₹{{ $variant->price }}

                                | Stock:
                                {{ $variant->stock }}

                            </div>

                        @endforeach

                    </div>

                    {{-- Status --}}
                    <form action="{{ route('admin.products.status', $product->id) }}"
                          method="POST"
                          class="mb-2">

                        @csrf

                        <div class="d-flex gap-2">

                            <select name="status"
                                    class="form-select">

                                <option value="1"
                                    {{ $product->status == 1 ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="0"
                                    {{ $product->status == 0 ? 'selected' : '' }}>
                                    Inactive
                                </option>

                            </select>

                            <button class="btn btn-primary">
                                Save
                            </button>

                        </div>

                    </form>

                    {{-- Delete --}}
                    <form action="{{ route('admin.products.delete', $product->id) }}"
                          method="POST"
                          onsubmit="return confirm('Delete product permanently?')">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger w-100">
                            Delete Product
                        </button>

                    </form>

                </div>

            </div>

        </div>

        @empty

        <div class="col-12">

            <div class="alert alert-info">
                No products found.
            </div>

        </div>

        @endforelse

    </div>

</div>

@endsection