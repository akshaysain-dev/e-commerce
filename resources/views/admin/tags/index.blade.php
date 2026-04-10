@extends('layouts.backend')

@section('title', 'Tags')

@section('styles')
<style>
    .pg-header {
        background: linear-gradient(135deg, #6366f1, #7c3aed);
        border-radius: 14px; padding: 24px 28px; color: #fff; margin-bottom: 24px;
    }
    .pg-header h1 { font-size: 1.45rem; font-weight: 800; margin: 0; }
    .pg-header p  { opacity: .7; margin: 4px 0 0; font-size: .88rem; }

    .card-box {
        background: #fff; border: 1px solid #e5e7eb;
        border-radius: 14px; overflow: hidden;
    }
    .card-box-header {
        background: #f8fafc; padding: 16px 20px;
        border-bottom: 1px solid #e5e7eb;
        font-size: .82rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .07em; color: #64748b;
    }
    .card-box-body { padding: 20px; }

    .form-control, .form-select {
        border: 1.5px solid #e5e7eb; border-radius: 8px;
        padding: 9px 13px; font-size: .88rem; background: #fafafa;
        transition: border-color .15s, box-shadow .15s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.1);
        background: #fff; outline: none;
    }

    .btn-create {
        background: #6366f1; color: #fff; border: none;
        border-radius: 8px; padding: 9px 20px; font-size: .88rem;
        font-weight: 700; cursor: pointer; transition: all .15s;
        white-space: nowrap;
    }
    .btn-create:hover { background: #4f46e5; }

    .tag-chip {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 5px 12px; border-radius: 20px;
        font-size: .82rem; font-weight: 700; color: #fff;
        margin: 3px;
    }
    .tag-chip .chip-count {
        background: rgba(255,255,255,.25);
        border-radius: 10px; padding: 0px 6px; font-size: .72rem;
    }

    .tbl thead th {
        background: #f8fafc; font-size: .72rem; text-transform: uppercase;
        letter-spacing: .07em; color: #94a3b8;
        border-bottom: 1px solid #e5e7eb; padding: 11px 16px; font-weight: 700;
    }
    .tbl tbody td {
        padding: 12px 16px; border-bottom: 1px solid #f1f5f9;
        vertical-align: middle; font-size: .875rem;
    }
    .tbl tbody tr:last-child td { border-bottom: none; }
    .tbl tbody tr:hover td { background: #fafafa; }

    .btn-sm-a {
        padding: 4px 11px; border-radius: 6px; font-size: .78rem;
        font-weight: 600; border: 1px solid; cursor: pointer;
        text-decoration: none; transition: all .15s; background: none;
    }
    .btn-edit     { border-color: #c7d2fe; color: #4f46e5; background: #eef2ff; }
    .btn-edit:hover { background: #4f46e5; color: #fff; }
    .btn-del      { border-color: #fecaca; color: #dc2626; background: #fff5f5; }
    .btn-del:hover  { background: #dc2626; color: #fff; }
    .btn-add-prod { border-color: #bbf7d0; color: #15803d; background: #f0fdf4; }
    .btn-add-prod:hover { background: #15803d; color: #fff; border-color: #15803d; }

    .flash { display:flex; align-items:center; gap:8px; padding:11px 16px; border-radius:9px; margin-bottom:18px; font-size:.88rem; }
    .flash.ok  { background:#d1fae5; color:#065f46; }
    .flash.err { background:#fee2e2; color:#991b1b; }

    /* ── Edit modal ── */
    .modal-backdrop-custom {
        position: fixed; inset: 0; background: rgba(0,0,0,.45);
        z-index: 1050; display: none; align-items: center; justify-content: center;
    }
    .modal-backdrop-custom.open { display: flex; }
    .modal-card {
        background: #fff; border-radius: 14px; padding: 28px 32px;
        width: 100%; max-width: 420px;
        box-shadow: 0 20px 60px rgba(0,0,0,.2);
    }
    .modal-card h5 { font-weight: 800; margin-bottom: 20px; font-size: 1.1rem; color: #1e293b; }

    /* ── Assign Products modal ── */
    .prod-modal-wrap {
        position: fixed; inset: 0; background: rgba(0,0,0,.45);
        z-index: 1060; display: none;
        align-items: flex-start; justify-content: center;
        padding: 32px 16px; overflow-y: auto;
    }
    .prod-modal-wrap.open { display: flex; }
    .prod-modal-card {
        background: #fff; border-radius: 16px;
        width: 100%; max-width: 680px;
        box-shadow: 0 24px 70px rgba(0,0,0,.22);
        display: flex; flex-direction: column;
        max-height: calc(100vh - 64px);
    }
    .prod-modal-head {
        padding: 20px 24px 16px;
        border-bottom: 1px solid #e5e7eb;
        display: flex; align-items: center; justify-content: space-between;
        flex-shrink: 0;
    }
    .prod-modal-head h5 { font-weight: 800; font-size: 1.05rem; color: #1e293b; margin: 0; }
    .close-btn {
        background: #f1f5f9; border: none; border-radius: 8px;
        width: 32px; height: 32px; cursor: pointer; font-size: 1rem;
        display: flex; align-items: center; justify-content: center;
        color: #475569; transition: background .15s;
    }
    .close-btn:hover { background: #e2e8f0; }

    .prod-search {
        padding: 12px 24px; border-bottom: 1px solid #f1f5f9; flex-shrink: 0;
    }
    .prod-search input {
        width: 100%; border: 1.5px solid #e5e7eb; border-radius: 8px;
        padding: 8px 13px; font-size: .88rem; background: #fafafa;
        outline: none; transition: border-color .15s;
    }
    .prod-search input:focus { border-color: #6366f1; background: #fff; }

    .prod-modal-body { overflow-y: auto; flex: 1; padding: 4px 0; }

    .cat-group { border-bottom: 1px solid #f1f5f9; }
    .cat-group:last-child { border-bottom: none; }

    .cat-header {
        padding: 10px 24px;
        display: flex; align-items: center; justify-content: space-between;
        cursor: pointer; user-select: none; background: #f8fafc;
        font-size: .77rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .06em; color: #475569;
        transition: background .12s;
    }
    .cat-header:hover { background: #f1f5f9; }
    .cat-badge {
        background: #e0e7ff; color: #4f46e5;
        border-radius: 10px; padding: 1px 8px; font-size: .72rem; font-weight: 700;
        margin-left: 8px;
    }
    .cat-arrow { transition: transform .2s; font-size: .8rem; }
    .cat-header.collapsed .cat-arrow { transform: rotate(-90deg); }

    .cat-select-all {
        font-size: .74rem; color: #6366f1; font-weight: 600;
        cursor: pointer; text-decoration: underline;
        background: none; border: none; padding: 0; margin-right: 10px;
    }

    .cat-body { padding: 2px 0; }
    .cat-body.hidden { display: none; }

    .prod-item {
        display: flex; align-items: center; gap: 12px;
        padding: 9px 24px; transition: background .1s; cursor: pointer;
    }
    .prod-item:hover { background: #f8fafc; }
    .prod-item input[type=checkbox] {
        width: 16px; height: 16px; accent-color: #6366f1;
        cursor: pointer; flex-shrink: 0;
    }
    .prod-item label { font-size: .875rem; color: #1e293b; cursor: pointer; flex: 1; line-height: 1.3; }
    .prod-sku { font-size: .75rem; color: #94a3b8; font-family: monospace; display: block; }
    .prod-item.hidden-item { display: none; }

    .prod-modal-foot {
        padding: 14px 24px;
        border-top: 1px solid #e5e7eb;
        display: flex; align-items: center; justify-content: space-between;
        flex-shrink: 0; background: #f8fafc;
        border-radius: 0 0 16px 16px;
    }
    .sel-count { font-size: .85rem; color: #475569; font-weight: 600; }
    .sel-count span { color: #4f46e5; font-weight: 800; }
</style>
@endsection

@section('content')
<div class="container-fluid py-4 px-4" style="background:#f8fafc; min-height:100vh;">

    <div class="pg-header">
        <h1>🏷 Tags</h1>
        <p>Create and manage product tags</p>
    </div>

    @if(session('success'))
        <div class="flash ok">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash err">❌ {{ session('error') }}</div>
    @endif

    <div class="row g-4">

        {{-- ── Create Tag ── --}}
        <div class="col-lg-4">
            <div class="card-box">
                <div class="card-box-header">Add New Tag</div>
                <div class="card-box-body">
                    @if($errors->any())
                        <div class="flash err mb-3">{{ $errors->first() }}</div>
                    @endif
                    <form method="POST" action="{{ route('admin.tags.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Tag Name *</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name') }}"
                                   placeholder="e.g. New Arrival, Trending, Sale">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Badge Color</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="color" name="color" value="{{ old('color', '#6366f1') }}"
                                       class="form-control form-control-color"
                                       style="width:50px; height:38px; border-radius:8px; padding:3px;">
                                <span style="font-size:.82rem; color:#64748b;">Pick a color for the tag badge</span>
                            </div>
                        </div>
                        <button type="submit" class="btn-create w-100">+ Create Tag</button>
                    </form>
                </div>
            </div>

            @if($tags->count() > 0)
            <div class="card-box mt-4">
                <div class="card-box-header">All Tags Preview</div>
                <div class="card-box-body">
                    @foreach($tags as $tag)
                        <span class="tag-chip" style="background:{{ $tag->color }};">
                            {{ $tag->name }}
                            <span class="chip-count">{{ $tag->products_count }}</span>
                        </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- ── Tags Table ── --}}
        <div class="col-lg-8">
            <div class="card-box">
                <div class="card-box-header">All Tags ({{ $tags->total() }})</div>
                <div class="table-responsive">
                    <table class="table tbl mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tag</th>
                                <th>Slug</th>
                                <th>Products</th>
                                <th>Created</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($tags as $tag)
                            <tr>
                                <td style="color:#94a3b8; font-size:.8rem;">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="tag-chip"
                                          style="background:{{ $tag->color }}; font-size:.8rem; padding:3px 10px;">
                                        {{ $tag->name }}
                                    </span>
                                </td>
                                <td style="font-family:monospace; color:#64748b; font-size:.82rem;">{{ $tag->slug }}</td>
                                <td>
                                    <span style="background:#eef2ff; color:#4f46e5; border-radius:5px;
                                                 padding:2px 8px; font-size:.78rem; font-weight:700;">
                                        {{ $tag->products_count }} products
                                    </span>
                                </td>
                                <td style="color:#94a3b8; font-size:.82rem;">{{ $tag->created_at->format('d M Y') }}</td>
                                <td class="text-end">
                                    <div class="d-flex gap-2 justify-content-end">

                                        {{-- ✅ Add Products --}}
                                        <button class="btn-sm-a btn-add-prod"
                                                onclick="openProdModal({{ $tag->id }}, '{{ addslashes($tag->name) }}', '{{ $tag->color }}')">
                                            + Products
                                        </button>

                                        <button class="btn-sm-a btn-edit"
                                                onclick="openEdit({{ $tag->id }}, '{{ addslashes($tag->name) }}', '{{ $tag->color }}')">
                                            ✏ Edit
                                        </button>

                                        <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST"
                                              onsubmit="return confirm('Delete tag: {{ $tag->name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-sm-a btn-del">🗑</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5" style="color:#94a3b8;">
                                    No tags yet. Create your first tag.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if($tags->hasPages())
                    <div class="p-3 border-top">{{ $tags->links() }}</div>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- ════════════════════════════════════════════════════════
     ASSIGN PRODUCTS MODAL
════════════════════════════════════════════════════════ --}}
<div class="prod-modal-wrap" id="prodModal">
    <div class="prod-modal-card">

        {{-- Head --}}
        <div class="prod-modal-head">
            <h5>
                <span id="pm-dot"
                      style="display:inline-block;width:11px;height:11px;border-radius:50%;
                             margin-right:6px;vertical-align:middle;background:#6366f1;"></span>
                <span id="pm-tag-name">Tag</span> — Assign Products
            </h5>
            <button class="close-btn" onclick="closeProdModal()">✕</button>
        </div>

        {{-- Search --}}
        <div class="prod-search">
            <input type="text" id="pm-search"
                   placeholder="🔍  Search by product name or SKU…"
                   oninput="filterProducts(this.value)">
        </div>

        {{-- Products grouped by category --}}
        <div class="prod-modal-body" id="pm-body">
            @forelse($groupedProducts as $catName => $products)
                <div class="cat-group">
                    <div class="cat-header" onclick="toggleCat(this)">
                        <span>
                            📁 {{ $catName }}
                            <span class="cat-badge">{{ $products->count() }}</span>
                        </span>
                        <span style="display:flex;align-items:center;">
                            <button class="cat-select-all" type="button"
                                    onclick="event.stopPropagation(); selectAllCat(this)">
                                Select all
                            </button>
                            <span class="cat-arrow">▾</span>
                        </span>
                    </div>
                    <div class="cat-body">
                        @foreach($products as $product)
                            <div class="prod-item"
                                 data-name="{{ strtolower($product->name) }}"
                                 data-sku="{{ strtolower($product->sku ?? '') }}">
                                <input type="checkbox"
                                       name="products[]"
                                       value="{{ $product->id }}"
                                       id="pp-{{ $product->id }}"
                                       form="assignForm"
                                       onchange="updateCount()">
                                <label for="pp-{{ $product->id }}">
                                    {{ $product->name }}
                                    @if($product->sku ?? false)
                                        <span class="prod-sku">SKU: {{ $product->sku }}</span>
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-5" style="color:#94a3b8; font-size:.88rem;">
                    Koi product nahi mila.
                </div>
            @endforelse
        </div>

        {{-- Footer --}}
        <div class="prod-modal-foot">
            <div class="sel-count">
                <span id="pm-sel-count">0</span> products selected
            </div>
            <div class="d-flex gap-2">
                <button type="button" onclick="closeProdModal()"
                        style="background:#f1f5f9;border:none;border-radius:8px;
                               padding:9px 20px;font-weight:600;cursor:pointer;color:#374151;font-size:.88rem;">
                    Cancel
                </button>
                <button type="submit" form="assignForm" class="btn-create">
                    💾 Save Assignment
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Assign form (outside modal card, referenced via form="assignForm") --}}
<form id="assignForm" method="POST" action="">
    @csrf
</form>


{{-- ════════════════════════════════════════════════════════
     EDIT TAG MODAL
════════════════════════════════════════════════════════ --}}
<div class="modal-backdrop-custom" id="editModal">
    <div class="modal-card">
        <h5>✏ Edit Tag</h5>
        <form method="POST" id="editForm">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.85rem;">Tag Name *</label>
                <input type="text" name="name" id="edit-name" class="form-control" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:.85rem;">Badge Color</label>
                <input type="color" name="color" id="edit-color"
                       class="form-control form-control-color"
                       style="width:50px; height:38px; border-radius:8px; padding:3px;">
            </div>
            <div class="d-flex gap-3">
                <button type="submit" class="btn-create flex-grow-1">💾 Save</button>
                <button type="button" onclick="closeEdit()"
                        style="background:#f1f5f9;border:none;border-radius:8px;
                               padding:9px 20px;font-weight:600;cursor:pointer;color:#374151;">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>


<script>
const BASE_URL    = "{{ url('admin/tags') }}";
const tagProducts = @json($tagProducts);  // { tagId: [productId, ...], ... }

/* ─── Assign Products Modal ─── */
function openProdModal(tagId, tagName, tagColor) {
    document.getElementById('pm-tag-name').textContent   = tagName;
    document.getElementById('pm-dot').style.background   = tagColor;
    document.getElementById('assignForm').action         =
        BASE_URL + '/' + tagId + '/assign-products';

    // Pre-check already assigned products
    const assigned = (tagProducts[tagId] || []).map(Number);
    document.querySelectorAll('[form="assignForm"]').forEach(cb => {
        cb.checked = assigned.includes(Number(cb.value));
    });

    document.getElementById('pm-search').value = '';
    filterProducts('');
    updateCount();
    document.getElementById('prodModal').classList.add('open');
    setTimeout(() => document.getElementById('pm-search').focus(), 120);
}

function closeProdModal() {
    document.getElementById('prodModal').classList.remove('open');
}
document.getElementById('prodModal').addEventListener('click', e => {
    if (e.target === document.getElementById('prodModal')) closeProdModal();
});

/* Search */
function filterProducts(q) {
    q = q.trim().toLowerCase();
    document.querySelectorAll('.prod-item').forEach(item => {
        const match = !q ||
            (item.dataset.name || '').includes(q) ||
            (item.dataset.sku  || '').includes(q);
        item.classList.toggle('hidden-item', !match);
    });
    // Hide categories with no visible items
    document.querySelectorAll('.cat-group').forEach(grp => {
        const anyVisible = [...grp.querySelectorAll('.prod-item')]
            .some(i => !i.classList.contains('hidden-item'));
        grp.style.display = anyVisible ? '' : 'none';
    });
}

/* Select / deselect all in one category */
function selectAllCat(btn) {
    const boxes     = btn.closest('.cat-group').querySelectorAll('input[type=checkbox]');
    const allChecked = [...boxes].every(cb => cb.checked);
    boxes.forEach(cb => cb.checked = !allChecked);
    btn.textContent = allChecked ? 'Select all' : 'Deselect all';
    updateCount();
}

/* Footer selected count */
function updateCount() {
    const n = document.querySelectorAll('[form="assignForm"]:checked').length;
    document.getElementById('pm-sel-count').textContent = n;
}

/* Toggle category expand/collapse */
function toggleCat(header) {
    header.nextElementSibling.classList.toggle('hidden');
    header.classList.toggle('collapsed');
}

/* ─── Edit Tag Modal ─── */
function openEdit(id, name, color) {
    document.getElementById('edit-name').value  = name;
    document.getElementById('edit-color').value = color;
    document.getElementById('editForm').action  = BASE_URL + '/' + id;
    document.getElementById('editModal').classList.add('open');
}
function closeEdit() {
    document.getElementById('editModal').classList.remove('open');
}
document.getElementById('editModal').addEventListener('click', e => {
    if (e.target === document.getElementById('editModal')) closeEdit();
});
</script>
@endsection