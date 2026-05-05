@extends('layouts.backend')

@section('title', 'Reviews Management')

@section('content')

<div class="container-fluid mt-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">⭐ Review Management</h4>
    </div>

    {{-- FILTER --}}
    <div class="mb-3 d-flex gap-2 flex-wrap">
        <a href="?status=pending" class="btn btn-warning btn-sm px-3">Pending</a>
        <a href="?status=approved" class="btn btn-success btn-sm px-3">Approved</a>
        <a href="?status=rejected" class="btn btn-danger btn-sm px-3">Rejected</a>
        <a href="{{ route('admin.reviews') }}" class="btn btn-dark btn-sm px-3">All</a>
    </div>

    {{-- CARD --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table align-middle mb-0">

                    <thead class="bg-light">
                        <tr class="text-muted small">
                            <th>ID</th>
                            <th>Product</th>
                            <th>User</th>
                            <th>Rating</th>
                            <th>Review</th>
                            <th>Images</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($reviews as $review)
                        <tr class="border-bottom">

                            {{-- ID --}}
                            <td class="fw-bold text-muted">#{{ $review->id }}</td>

                            {{-- PRODUCT --}}
                            <td style="max-width:250px;">
                                <div class="fw-semibold">
                                    {{ \Str::limit($review->product->name ?? '-', 50) }}
                                </div>
                            </td>

                            {{-- USER --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center"
                                         style="width:35px;height:35px;">
                                        {{ strtoupper(substr($review->customer->name ?? 'U',0,1)) }}
                                    </div>
                                    <span>{{ $review->customer->name ?? '-' }}</span>
                                </div>
                            </td>

                            {{-- RATING --}}
                            <td>
                                <div class="text-warning">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star text-muted"></i>
                                        @endif
                                    @endfor
                                </div>
                            </td>

                            {{-- REVIEW --}}
                            <td style="max-width:300px;">
                                <strong>{{ $review->title }}</strong>
                                <p class="small text-muted mb-0">
                                    {{ \Str::limit($review->review, 80) }}
                                </p>
                            </td>

                            {{-- IMAGES --}}
                            <td>
                                @if($review->images)
                                    <div class="d-flex gap-1 flex-wrap">
                                        @foreach($review->images as $img)
                                            <img src="{{ asset('storage/'.$img) }}"
                                                 style="width:40px;height:40px;object-fit:cover;border-radius:6px;cursor:pointer;"
                                                 onclick="window.open(this.src)">
                                        @endforeach
                                    </div>
                                @endif
                            </td>

                            {{-- STATUS --}}
                            <td>
                                <span class="badge px-3 py-2 bg-{{ 
                                    $review->status == 'approved' ? 'success' : 
                                    ($review->status == 'rejected' ? 'danger' : 'warning') 
                                }}">
                                    {{ ucfirst($review->status) }}
                                </span>
                            </td>

                            {{-- ACTION --}}
                            <td class="text-center">

                                <div class="d-flex justify-content-center gap-2">

                                    {{-- APPROVE --}}
                                    <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-success btn-sm rounded-circle">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>

                                    {{-- REJECT --}}
                                    <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-danger btn-sm rounded-circle">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>

                                    {{-- DELETE --}}
                                    <form action="{{ route('admin.reviews.delete', $review->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-dark btn-sm rounded-circle">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                </div>

                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                No reviews found
                            </td>
                        </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-3">
        {{ $reviews->links() }}
    </div>

</div>

@endsection