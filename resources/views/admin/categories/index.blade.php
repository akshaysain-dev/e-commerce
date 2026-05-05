@extends('layouts.backend')

@section('title', 'Manage Categories')

@section('styles')
<style>
    /* ✅ FOOTER FIX */
    .page-wrapper {
        min-height: calc(100vh - 120px);
    }

    .dashboard-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        background: #ffffff;
    }
    .table thead th {
        background-color: #f1f4f9;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 1px;
        font-weight: 700;
        color: #555;
        border: none;
        padding: 15px;
    }
    .table tbody tr {
        border-bottom: 1px solid #f1f1f1;
        transition: background 0.2s;
    }
    .table tbody tr:hover {
        background-color: #f9fbff;
    }
    .status-badge {
        padding: 5px 12px;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 6px;
    }
    .bg-soft-success { background: #d1fae5; color: #065f46; }
    .bg-soft-danger { background: #fee2e2; color: #991b1b; }
    
    .btn-action-text {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: 0.2s;
    }
    .btn-edit { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
    .btn-edit:hover { background: #ffeeba; }
    
    .btn-delete { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    .btn-delete:hover { background: #f5c6cb; }

    #category-table tr {
        cursor: move;
    }
    #bulk-form select:focus {
        box-shadow: none;
        border-color: #0d6efd;
    }
    .form-check-input {
        width: 18px;
        height: 18px;
    }
</style>
@endsection

@section('content')

{{-- ✅ page-wrapper footer ko neeche rakhta hai --}}
<div class="page-wrapper">
<div class="container-fluid px-4 py-4">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Categories</h2>
            <p class="text-muted small">Organize your products collection</p>
        </div>
        <a href="{{ route('categories.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Add New Category
        </a>
    </div>

    <form id="bulk-form" class="d-flex align-items-center gap-2 mb-3">
        <select id="bulk-action" class="form-select form-select-sm w-auto shadow-sm" required>
            <option value="">⚡ Bulk Action</option>
            <option value="delete">🗑 Delete</option>
            <option value="activate">✅ Activate</option>
            <option value="deactivate">🚫 Deactivate</option>
        </select>
        <button type="button" onclick="applyBulkAction()" 
            class="btn btn-sm btn-dark px-3 shadow-sm" disabled>
            Apply
        </button>
    </form>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 alert-dismissible fade show mb-4">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card dashboard-card overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">
                                <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input" type="checkbox" id="select-all">
                                </div>
                            </th>
                            <th></th>
                            <th class="ps-4">#</th>
                            <th>Category Name</th>
                            <th>URL Slug</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="category-table">
                        @forelse($categories as $category)
                        <tr data-id="{{ $category->id }}">
                            <td class="text-center">
                                <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input category-checkbox" type="checkbox" value="{{ $category->id }}">
                                </div>
                            </td>
                            <td>☰</td>
                            <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                            <td>
                                <span class="fw-bold text-dark">{{ $category->name }}</span>
                            </td>
                            <td>
                                <span class="text-primary small">/{{ $category->slug }}</span>
                            </td>
                            <td>
                                @if($category->status)
                                    <span class="status-badge bg-soft-success">Active</span>
                                @else
                                    <span class="status-badge bg-soft-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-muted small">
                                {{ $category->created_at->format('d M, Y') }}
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('category.edit', $category->id) }}" class="btn-action-text btn-edit">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <a href="{{ route('category.delete', $category->id) }}" 
                                       class="btn-action-text btn-delete" 
                                       onclick="return confirm('Delete this category?');">
                                        <i class="bi bi-trash3"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">No categories available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="card-footer bg-light border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    Showing <b>{{ $categories->firstItem() }}</b> to <b>{{ $categories->lastItem() }}</b> of {{ $categories->total() }}
                </div>
                <div>
                    {{ $categories->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

</div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
new Sortable(document.getElementById('category-table'), {
    animation: 150,
    onEnd: function () {
        let order = [];
        document.querySelectorAll('#category-table tr').forEach((row, index) => {
            if(row.dataset.id){
                order.push({ id: row.dataset.id, position: index + 1 });
            }
        });
        fetch("{{ route('category.order') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ order: order })
        });
    }
});

document.getElementById('select-all').addEventListener('change', function () {
    document.querySelectorAll('.category-checkbox').forEach(cb => {
        cb.checked = this.checked;
    });
});

function applyBulkAction() {
    let selected = [];
    let action = document.getElementById('bulk-action').value;

    document.querySelectorAll('.category-checkbox:checked').forEach(cb => {
        selected.push(cb.value);
    });

    if (selected.length === 0) { alert('Select at least one category'); return; }
    if (!action) { alert('Select an action'); return; }

    fetch("{{ route('category.bulk') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ ids: selected, action: action })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        location.reload();
    });
}

document.getElementById('bulk-action').addEventListener('change', function () {
    document.querySelector('#bulk-form button').disabled = !this.value;
});
</script>
@endpush