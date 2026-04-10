@extends('layouts.backend')
@section('title', 'Edit Banner')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0 fw-bold">Edit Banner #{{ $banner->id }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6 text-center border-end">
                        <label class="form-label d-block fw-bold">Current Image</label>
                        <img src="{{ asset('storage/' . $banner->image) }}" class="rounded shadow-sm mb-3" style="max-height: 200px; width: auto;">
                        <input type="file" name="image" class="form-control">
                        <small class="text-muted">Leave empty to keep current image</small>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Banner Type</label>
                            <select name="type" class="form-select">
                                <option value="main" {{ $banner->type == 'main' ? 'selected' : '' }}>Main Slider</option>
                                <option value="side" {{ $banner->type == 'side' ? 'selected' : '' }}>Side Banner</option>
                            </select>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-bold">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control" value="{{ $banner->sort_order }}">
                            </div>
                            <div class="col-6 d-flex align-items-end pb-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" {{ $banner->status ? 'checked' : '' }}>
                                    <label class="form-check-label">Active Status</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Title</label>
                            <input type="text" name="title" class="form-control" value="{{ $banner->title }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Sub Title</label>
                            <input type="text" name="subtitle" class="form-control" value="{{ $banner->subtitle }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Link URL</label>
                            <input type="text" name="link" class="form-control" value="{{ $banner->link }}">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">Update Changes</button>
                            <a href="{{ route('banners.index') }}" class="btn btn-light px-4">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
