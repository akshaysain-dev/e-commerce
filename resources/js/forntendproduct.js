document.querySelectorAll('.thumb-image').forEach(thumb => {
    thumb.addEventListener('click', function() {
        document.getElementById('featuredImage').src = this.src;
    });
});

const selectors    = document.querySelectorAll('.variant-selector');
const cartForm     = document.getElementById('addToCartForm');
const productId    = window.Laravel.product_id;
const HAS_SALE     = window.Laravel.hasSale      || false;
const SALE_TYPE    = window.Laravel.saleType     || null;
const SALE_DISCOUNT= parseFloat(window.Laravel.saleDiscount) || 0;

function formatINR(num) {
    return '₹' + parseFloat(num).toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

selectors.forEach(btn => {
    btn.addEventListener('click', function() {
        selectors.forEach(b => b.classList.remove('active-variant'));
        this.classList.add('active-variant');

        const originalPrice = parseFloat(this.dataset.originalPrice || this.dataset.original || 0);
        let   displayPrice  = originalPrice;

        if (HAS_SALE && originalPrice > 0) {
            if (SALE_TYPE === 'percent') {
                displayPrice = originalPrice - (originalPrice * SALE_DISCOUNT / 100);
            } else {
                displayPrice = Math.max(0, originalPrice - SALE_DISCOUNT);
            }
        }

        const savedAmount = originalPrice - displayPrice;

        document.getElementById('display-price').textContent = formatINR(displayPrice);

        const origEl = document.getElementById('display-original-price');
        const saveEl = document.getElementById('display-save');
        if (origEl) origEl.textContent = formatINR(originalPrice);
        if (saveEl && HAS_SALE) saveEl.textContent = 'Save ' + formatINR(savedAmount);

        const stockDisplay = document.getElementById('display-stock');
        const stockCount   = parseInt(this.dataset.stock);
        stockDisplay.innerText = stockCount;

        document.getElementById('display-sku').innerText = this.dataset.sku;

        const variantId = this.dataset.id;
        cartForm.setAttribute('action', `/customer/${productId}/${variantId}/add_cart`);

        const cartBtn = document.getElementById('add-to-cart-btn');
        if (stockCount <= 0) {
            stockDisplay.className = 'badge bg-danger';
            cartBtn.disabled       = true;
            cartBtn.innerText      = 'Out of Stock';
        } else {
            stockDisplay.className = 'badge bg-success';
            cartBtn.disabled       = false;
            cartBtn.innerText      = 'Add to Cart';
        }
    });
});

document.querySelector('.variant-selector')?.click();

document.addEventListener("DOMContentLoaded", function() {
    let url = window.Laravel.route;
    if (window.Laravel.customer_id) {
        fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": window.Laravel.csrf
            },
            body: JSON.stringify({ product_id: window.Laravel.product_id })
        })
        .then(response => response.json())
        .then(data => console.log("Recent view saved:", data))
        .catch(error => console.error("Error:", error));
    } else {
        const cookieName = "recent_products";
        const newId      = window.Laravel.product_id;
        let   productIds = [];

        const cookieString = document.cookie.split('; ').find(row => row.startsWith(cookieName + '='));
        if (cookieString) {
            try {
                productIds = JSON.parse(cookieString.split('=')[1]);
            } catch (e) {
                productIds = [];
            }
        }

        if (!productIds.includes(newId)) productIds.push(newId);
        while (productIds.length > 6) productIds.shift();

        document.cookie = `${cookieName}=${JSON.stringify(productIds)}; max-age=7200; path=/; SameSite=Lax`;
    }
});

(function () {
    var descs  = {'5':'Excellent','4':'Good','3':'Average','2':'Poor','1':'Terrible'};
    var descEl = document.getElementById('ratingDesc');
    document.querySelectorAll('#starPicker input[type=radio]').forEach(function(r) {
        r.addEventListener('change', function() {
            descEl.textContent = descs[this.value] || '';
        });
    });

    var ta = document.getElementById('reviewBody');
    var cc = document.getElementById('charCount');
    if (ta && cc) {
        cc.textContent = ta.value.length;
        ta.addEventListener('input', function() { cc.textContent = this.value.length; });
    }

    document.querySelectorAll('.fk-filter').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.fk-filter').forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');
            var f = this.dataset.filter;
            document.querySelectorAll('.fk-review-item').forEach(function(item) {
                item.style.display = (f === 'all' || item.dataset.star === f) ? '' : 'none';
            });
        });
    });

    var form = document.getElementById('reviewForm');
    var btn  = document.getElementById('submitBtn');
    if (form && btn) {
        form.addEventListener('submit', function() {
            btn.textContent = 'Submitting…';
            btn.disabled    = true;
        });
    }
})();