@extends('layouts.backend')

@section('title', 'My Shop')

@section('content')

<div class="container mt-4 mb-3">
    <h2 class="mb-4">Edit Category</h2>

    <!-- Display Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Edit Form -->
    <form action="{{ route('category.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT') 

        <div class="mb-3">
            <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                   id="name" value="{{ old('name', $category->name) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" 
                   id="slug" value="{{ old('slug', $category->slug) }}">
            @error('slug')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                      id="description" rows="3">{{ old('description', $category->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="status" id="status" class="form-check-input" 
                   value="1" {{ old('status', $category->status) ? 'checked' : '' }}>
            <label for="status" class="form-check-label">Active</label>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin_category') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

@endsection