@extends('layouts.backend')

@section('title', 'Import Products')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center p-4 mb-4 bg-white border-bottom shadow-sm rounded animate__animated animate__fadeIn">
        <div class="mb-3 mb-md-0">
            <h2 class="fw-bold mb-1 text-dark">Import Products</h2>
            <p class="text-muted small mb-0">
                <span class="badge bg-soft-primary text-primary border border-primary-subtle me-2">Admin Portal</span>
                Upload a CSV file to import products
            </p>
        </div>
        <div>
            <a href="{{ route('admin_product') }}" class="btn btn-outline-secondary rounded-pill px-4 btn-sm shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Products
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-4 alert-dismissible fade show">
            <i class="bi bi-x-circle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4 animate__animated animate__fadeInUp">
                <div class="card-body p-5">

                    <div class="text-center mb-4">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                            <i class="fas fa-file-csv text-warning fs-2"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Upload CSV File</h5>
                        <p class="text-muted small">Make sure your CSV file is exported from this system for correct column mapping</p>
                    </div>

                    <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">Select CSV File</label>
                            <div class="border-2 border-dashed rounded-4 p-4 text-center" style="border: 2px dashed #dee2e6; cursor: pointer;" id="dropZone">
                                <input type="file" name="csv_file" id="csv_file" accept=".csv,.txt" class="d-none" required>
                                <label for="csv_file" style="cursor: pointer;" class="w-100">
                                    <i class="bi bi-cloud-upload fs-1 text-muted d-block mb-2"></i>
                                    <span class="fw-semibold text-primary">Click to browse</span>
                                    <span class="text-muted"> or drag & drop your file here</span>
                                    <p class="text-muted small mt-1 mb-0">Supported format: .csv</p>
                                </label>
                            </div>
                            <div id="fileInfo" class="mt-2 d-none">
                                <div class="d-flex align-items-center gap-2 bg-light rounded-3 px-3 py-2">
                                    <i class="fas fa-file-csv text-success"></i>
                                    <span class="small fw-semibold text-dark" id="fileName"></span>
                                    <span class="small text-muted ms-auto" id="fileSize"></span>
                                </div>
                            </div>
                            @error('csv_file')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="card bg-light border-0 rounded-4 mb-4">
                            <div class="card-body px-4 py-3">
                                <p class="fw-semibold text-dark small mb-2"><i class="bi bi-info-circle me-1 text-primary"></i> CSV Column Order</p>
                                <div class="row g-1">
                                    @foreach([
                                        'Product ID', 'User ID', 'Product Name', 'Category ID', 'Category Name',
                                        'Product Type ID', 'Product Type Name', 'Description', 'Main Image',
                                        'All Images', 'Status', 'Product Created At', 'Variant ID',
                                        'Variant Product Id FK', 'Variant SKU', 'Variant Name', 'Variant Price',
                                        'Variant Stock', 'Attribute ID', 'Attribute Value', 'Variant Created At'
                                    ] as $index => $col)
                                    <div class="col-6">
                                        <span class="text-muted" style="font-size: 0.75rem;">
                                            <span class="badge bg-secondary bg-opacity-25 text-secondary me-1">{{ $index }}</span>
                                            {{ $col }}
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning fw-semibold rounded-pill py-2" id="submitBtn">
                                <i class="fas fa-file-import me-2"></i> Import Products
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const csvFile = document.getElementById('csv_file');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const submitBtn = document.getElementById('submitBtn');
    const importForm = document.getElementById('importForm');

    csvFile.addEventListener('change', function () {
        if (this.files.length > 0) {
            const file = this.files[0];
            const size = (file.size / 1024).toFixed(1) + ' KB';
            fileName.textContent = file.name;
            fileSize.textContent = size;
            fileInfo.classList.remove('d-none');
        }
    });

    importForm.addEventListener('submit', function () {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Importing...';
    });
</script>
@endsection