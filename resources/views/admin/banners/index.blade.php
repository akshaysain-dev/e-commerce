@extends('layouts.backend')
@section('title', 'Manage Banners')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 alert-dismissible fade show mb-4">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Section 1: Upload Form --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold"><i class="bi bi-plus-circle me-2"></i>Add New Banner</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Banner Image</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                            <div class="form-text mt-1 text-muted small">
                                <i class="bi bi-info-circle me-1"></i>Main: 1200x280px | Side: 400x133px
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-7">
                                <label class="form-label fw-semibold">Type</label>
                                <select name="type" class="form-select">
                                    <option value="">--Select--</option>
                                    <option value="main">Main Slider</option>
                                    <option value="side">Side Banner</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Order</label>
                                <input type="number" name="sort_order" class="form-control" value="0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Title & Subtitle</label>
                            <input type="text" name="title" class="form-control mb-2" placeholder="Main Heading">
                            <input type="text" name="subtitle" class="form-control" placeholder="Sub Heading">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Redirect Link</label>
                            <input type="text" name="link" class="form-control" placeholder="e.g. /shop/electronics">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                            <i class="bi bi-upload me-2"></i>Upload Banner
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Section 2: Banner Listing --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold"><i class="bi bi-images me-2"></i>Live Banners</h5>
                    <span class="badge bg-light text-dark border">{{ $banners->count() ?? 0 }} Total</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Preview</th>
                                    <th>Info</th>
                                    <th>Type</th>
                                    <th>Order</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($banners as $banner)
                                <tr>
                                    <td class="ps-4">
                                        <div class="rounded-3 overflow-hidden shadow-sm border" style="width: 100px; height: 45px;">
                                            <img src="{{ asset('storage/'.$banner->image) }}" class="w-100 h-100 object-fit-cover">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark small">{{ $banner->title ?: 'No Title' }}</div>
                                        <div class="text-muted small" style="font-size: 11px;">{{ Str::limit($banner->link, 30) }}</div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $banner->type == 'main' ? 'bg-primary' : 'bg-info text-dark' }} rounded-pill">
                                            {{ ucfirst($banner->type) }}
                                        </span>
                                    </td>
                                    <td><span class="text-muted small">#{{ $banner->sort_order }}</span></td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group shadow-sm rounded gap-2">
                                            <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-sm btn-outline-secondary">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Delete this banner?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <img src="https://cdn-icons-png.flaticon.com" width="60" class="opacity-25 mb-3">
                                        <p class="text-muted">No banners found. Start by uploading one!</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
