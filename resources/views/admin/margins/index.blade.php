@extends('layouts.backend')

@section('title', 'Manage Margins')

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
<div class="container mt-4 mb-4">

	<!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Error Message (Custom from Controller) -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Validation Errors (Form fields) -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
	
    <div class="row">
        <!-- Add New Margin Form -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Add New Margin</div>
                <div class="card-body">
                    <form action="{{ route('admin.margins.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Product Type</label>
                            <select name="type_id" class="form-control">
                                <option value="">Select Type...</option>
                                @foreach($productTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Margin Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Retail">
                        </div>
                        <div class="mb-3">
                            <label>Percentage (%)</label>
                            <input type="number" step="0.01" name="percentage" class="form-control" placeholder="15.00">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Save Margin</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Margin List Table -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Manage Margins</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product Type</th>
                                <th>Name</th>
                                <th>Percentage</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($margins as $margin)
                            <tr>
                                <td>{{ $margin->productType->name }}</td>
                                <td>{{ $margin->name }}</td>
                                <td>{{ $margin->percentage }}%</td>
                                <td>
                                    <form action="{{ route('admin.margins.destroy', $margin->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection