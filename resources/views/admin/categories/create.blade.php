@extends('layouts.backend')

@section('title', 'Add New Category')

@section('styles')
<style>
    /* ✅ FOOTER FIX */
    .page-wrapper {
        min-height: calc(100vh - 120px);
    }
</style>
@endsection

@section('content')

<div class="page-wrapper">
<div class="container mt-4 mb-3">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>Add New Category</h4>
                </div>

                <div class="card-body">
                    {{-- Display Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Category Form --}}
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="status" id="status" class="form-check-input" value="1" checked>
                            <label for="status" class="form-check-label">Active</label>
                        </div>

                        <button type="submit" class="btn btn-success">Add Category</button>
                        <a href="{{ route('admin_category') }}" class="btn btn-secondary">Back</a>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
</div>

@endsection