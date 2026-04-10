document.addEventListener('DOMContentLoaded', function () {

    const tabs        = document.querySelectorAll('.category-tab');
    const container   = document.getElementById('productsContainer');
    const title       = document.getElementById('sectionTitle');
    const subtitle    = document.getElementById('sectionSubtitle');
    const ajaxUrl = window.Laravel.ajaxUrl;
    const csrfToken = window.Laravel.csrfToken;

    function skeletonHTML(count = 8) {
        let html = '';
        for (let i = 0; i < count; i++) {
            html += `
            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="skeleton" style="height:220px;"></div>
                    <div class="card-body">
                        <div class="skeleton mb-2" style="height:14px;width:60%;"></div>
                        <div class="skeleton mb-1" style="height:12px;width:90%;"></div>
                        <div class="skeleton mb-3" style="height:12px;width:75%;"></div>
                        <div class="skeleton mb-2" style="height:18px;width:40%;"></div>
                        <div class="d-flex gap-2 mt-2">
                            <div class="skeleton flex-fill" style="height:32px;border-radius:6px;"></div>
                            <div class="skeleton flex-fill" style="height:32px;border-radius:6px;"></div>
                        </div>
                    </div>
                </div>
            </div>`;
        }
        return html;
    }

    function renderProducts(products) {
        if (!products || products.length === 0) {
            return `
            <div class="col-12">
                <div class="text-center py-5 bg-light rounded-3 border border-secondary-subtle">
                    <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="#adb5bd" stroke-width="1.2" class="mb-3">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 01-8 0"/>
                    </svg>
                    <h5 class="text-muted fw-semibold">Don't Have Any Product In This Category</h5>
                    <p class="text-muted small mb-0">Please Select Other Catrgory</p>
                </div>
            </div>`;
        }

        return products.map((p, idx) => {
            const imageHtml = p.image
                ? `<img src="${p.image_url}" class="card-img-top product-thumb" alt="${p.name}" style="height:220px;object-fit:cover;">`
                : `<div class="d-flex flex-column align-items-center justify-content-center bg-light" style="height:220px;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <path d="M21 15l-5-5L5 21"/>
                        </svg>
                        <p class="text-muted small mt-2 mb-0">No Image</p>
                   </div>`;

            const stockBadge = (p.stock > 0)
                ? `<span class="badge bg-success-subtle text-success border border-success-subtle mb-2">In Stock (${p.stock})</span>`
                : `<span class="badge bg-danger-subtle text-danger border border-danger-subtle mb-2">Out of Stock</span>`;

            const cartDisabled = (p.stock == 0) ? 'disabled' : '';
            const delay = (idx % 8) * 0.05;

            return `
            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 product-card-wrapper" style="animation-delay:${delay}s;">
                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden product-card">
                    <div class="position-relative overflow-hidden">
                        ${imageHtml}
                        <span class="badge bg-primary position-absolute top-0 start-0 m-2">${p.category_name ?? 'General'}</span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-semibold mb-1">${p.name}</h6>
                        <p class="text-muted small mb-2">${p.short_description}</p>
                        <div class="mt-auto">
                            <p class="fw-bold text-success mb-1">₹${p.formatted_price}</p>
                            <div class="d-flex gap-2 mt-1">
                                <a href="${p.view_url}" class="btn btn-outline-primary btn-sm flex-fill">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
        }).join('');
    }

    function loadProducts(categoryId, categoryName) {
        title.textContent = (categoryId === 'all') ? 'Latest Products' : `${categoryName} Products`;
        subtitle.textContent = 'Loading...';
    
        container.innerHTML = skeletonHTML(8);

        fetch(ajaxUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ category_id: categoryId })
        })
        .then(res => {
            if (!res.ok) throw new Error('Server error: ' + res.status);
            return res.json();
        })
        .then(data => {
            if (data.success) {
                container.innerHTML = renderProducts(data.products);
                const count = data.products ? data.products.length : 0;
                subtitle.textContent = count > 0
                    ? `${count} product${count > 1 ? 's' : ''} found`
                    : 'No products found';
            } else {
                container.innerHTML = `<div class="col-12"><div class="alert alert-warning">Products load karne mein error aaya.</div></div>`;
                subtitle.textContent = '';
            }
        })
        .catch(err => {
            console.error('AJAX Error:', err);
            container.innerHTML = `<div class="col-12"><div class="alert alert-danger">Network error. Please try again.</div></div>`;
            subtitle.textContent = '';
        });
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            tabs.forEach(t => t.classList.remove('active', 'text-primary', 'fw-semibold'));
            tabs.forEach(t => {
                t.classList.add('text-secondary');
                t.style.borderBottom = '';
            });

            this.classList.add('active', 'text-primary', 'fw-semibold');
            this.classList.remove('text-secondary');

            const categoryId   = this.dataset.categoryId;
            const categoryName = this.querySelector('span') ? this.querySelector('span').textContent.trim() : 'Category';

            loadProducts(categoryId, categoryName);
        });
    });
});
