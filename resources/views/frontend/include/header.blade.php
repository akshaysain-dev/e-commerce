<style>
    .navbar-nav .dropdown-menu {
        position: absolute !important;
    }
</style>
<nav class="navbar navbar-dark p-0 shadow-sm" style="background:#2874f0;">
    <div class="container">

        <div class="d-flex align-items-center w-100 gap-2 gap-md-3 py-2 pt-3">

            {{-- LOGO --}}
            <a class="navbar-brand fw-bold lh-1 flex-shrink-0" href="{{ route('home') }}" style="font-size:20px;">
                <span style="color:#ffe500;">My</span>Shop
                <small class="d-block fw-normal text-white-50" style="font-size:9px;letter-spacing:1px;">Explore Plus</small>
            </a>

            {{-- SEARCH --}}
            <div class="flex-grow-1 position-relative" id="searchWrapper">
                <form action="{{ route('search') }}" method="GET" autocomplete="off">
                    <div class="input-group" style="border-radius:3px;overflow:hidden;">
                        <input type="text" name="q" id="globalSearch"
                               class="form-control border-0 shadow-none py-2"
                               placeholder="Search for products, brands and more"
                               value="{{ request('q') }}" style="font-size:14px;">
                        <button class="btn btn-warning fw-bold px-3 border-0" type="submit" style="color:#2874f0;">
                            <i class="bi bi-search"></i>
                            <span class="d-none d-md-inline ms-1">Search</span>
                        </button>
                    </div>
                </form>
                {{-- Suggestions --}}
                <div id="searchSuggestions" class="bg-white rounded-2 shadow"
                     style="display:none;position:absolute;top:calc(100% + 4px);left:0;right:0;z-index:9999;max-height:320px;overflow-y:auto;border:1px solid #eee;">
                    <div id="suggestionList"></div>
                </div>
            </div>

            {{-- WISHLIST --}}
            <a href="{{ route('wishlist.index') }}"
               class="d-flex align-items-center gap-1 text-white text-decoration-none fw-semibold position-relative flex-shrink-0 px-2 py-2 rounded-2"
               style="font-size:14px;" onmouseover="this.style.background='rgba(255,255,255,.15)'" onmouseout="this.style.background='transparent'">
                <i class="bi bi-heart fs-5"></i>
                <span class="d-none d-sm-inline">Wishlist</span>
                @if(isset($wishlist_count) && $wishlist_count > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:9px;">{{ $wishlist_count }}</span>
                @endif
            </a>

            {{-- CART --}}
            <a href="{{ route('cart_index') }}"
               class="d-flex align-items-center gap-1 text-white text-decoration-none fw-semibold position-relative flex-shrink-0 px-2 py-2 rounded-2"
               style="font-size:14px;" onmouseover="this.style.background='rgba(255,255,255,.15)'" onmouseout="this.style.background='transparent'">
                <i class="bi bi-cart3 fs-5"></i>
                <span class="d-none d-sm-inline">Cart</span>
                @if(isset($cart_count) && $cart_count > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:9px;">{{ $cart_count }}</span>
                @endif
            </a>

            {{-- NOTIFICATIONS --}}
            <div class="dropdown flex-shrink-0">
                <button class="btn btn-link text-white text-decoration-none d-flex align-items-center gap-1 fw-semibold px-2 py-2 rounded-2 position-relative"
                        style="font-size:14px;" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                        onmouseover="this.style.background='rgba(255,255,255,.15)'" onmouseout="this.style.background='transparent'">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="d-none d-sm-inline">Alerts</span>
                    @if(isset($notif_count) && $notif_count > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:9px;">{{ $notif_count }}</span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 rounded-3 p-0 overflow-hidden mt-1 shadow"
                    style="min-width:300px;background:#fff;z-index:9999;">
                    {{-- Header --}}
                    <li class="px-3 py-2 d-flex justify-content-between align-items-center border-bottom" style="background:#f8f9fa;">
                        <span class="fw-semibold" style="font-size:13px;color:#212529;">Notifications</span>
                        <a href="{{ route('notifications.markRead') }}" class="text-decoration-none fw-semibold" style="font-size:11px;color:#2874f0;">Mark all read</a>
                    </li>
                    {{-- Notification Items --}}
                    @isset($notifications)
                        @forelse($notifications as $notif)
                        <li class="px-3 py-2 border-bottom {{ is_null($notif->read_at) ? '' : 'bg-light' }}"
                            style="{{ is_null($notif->read_at) ? 'background:#f0f5ff;' : '' }}">
                            <div class="d-flex gap-2 align-items-start">
                                <span class="mt-1 flex-shrink-0" style="width:8px;height:8px;border-radius:50%;background:{{ is_null($notif->read_at) ? '#2874f0' : '#ccc' }};display:inline-block;"></span>
                                <div>
                                    <p class="mb-0 fw-semibold" style="font-size:12px;color:#212529;">{{ $notif->title ?? 'Notification' }}</p>
                                    <small class="text-muted" style="font-size:11px;">{{ $notif->message ?? '' }}</small><br>
                                    <small style="font-size:10px;color:#aaa;">{{ $notif->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="text-center py-4">
                            <i class="bi bi-bell-slash fs-4 text-muted d-block mb-1"></i>
                            <small class="text-muted">No new notifications yet</small>
                        </li>
                        @endforelse
                    @else
                    <li class="text-center py-4">
                        <i class="bi bi-bell-slash fs-4 text-muted d-block mb-1"></i>
                        <small class="text-muted">No notifications yet</small>
                    </li>
                    @endisset
                    {{-- Footer --}}
                    <li class="text-center py-2 border-top" style="background:#fafafa;">
                        <a href="{{ route('notifications.index') }}" class="text-decoration-none fw-semibold" style="font-size:12px;color:#2874f0;">View all notifications →</a>
                    </li>
                </ul>
            </div>

            {{-- ACCOUNT --}}
            <div class="dropdown flex-shrink-0">
                <button class="btn btn-link text-white text-decoration-none d-flex align-items-center gap-1 fw-semibold px-2 py-2 rounded-2"
                        style="font-size:14px;" data-bs-toggle="dropdown"
                        onmouseover="this.style.background='rgba(255,255,255,.15)'" onmouseout="this.style.background='transparent'">
                    <i class="bi bi-person-circle fs-5"></i>
                    <span class="d-none d-sm-inline">
                        @if(session('customer_id')) {{ \Str::limit(session('customer_name'),15) }} @else Login @endif
                    </span>
                    <i class="bi bi-chevron-down" style="font-size:10px;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 rounded-3 p-0 overflow-hidden mt-1 shadow"
                    style="min-width:220px;background:#fff;z-index:9999;">
                    @if(session('customer_id'))
                    <li class="px-3 py-3 border-bottom" style="background:#f0f5ff;">
                        <p class="mb-0 fw-semibold text-dark" style="font-size:13px;">{{ session('customer_name') }}</p>
                        <small class="text-muted">{{ session('customer_email') }}</small>
                    </li>
                    <li><a class="dropdown-item py-2 d-flex align-items-center gap-2" style="font-size:13px;" href="{{ route('customer_profile') }}"><i class="bi bi-person text-primary"></i> My Profile</a></li>
                    <li><a class="dropdown-item py-2 d-flex align-items-center gap-2" style="font-size:13px;" href="{{ route('customer.orders') }}"><i class="bi bi-bag-check text-primary"></i> My Orders</a></li>
                    <li><a class="dropdown-item py-2 d-flex align-items-center gap-2" style="font-size:13px;" href="{{ route('wishlist.index') }}"><i class="bi bi-heart text-primary"></i> My Wishlist</a></li>
                    <li><hr class="dropdown-divider my-0"></li>
                    <li><a class="dropdown-item py-2 d-flex align-items-center gap-2 text-danger" style="font-size:13px;" href="{{ route('customer_logout') }}"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                    @else
                    <li class="px-3 py-3 text-center border-bottom bg-light">
                        <p class="mb-2 small text-muted">Get access to your Orders, Wishlist and Recommendations</p>
                        <a href="{{ route('customer_login') }}" class="btn btn-primary btn-sm w-100 fw-semibold">Login / Register</a>
                    </li>
                    <li><a class="dropdown-item py-2 d-flex align-items-center gap-2" style="font-size:13px;" href="{{ route('customer_login') }}"><i class="bi bi-box-arrow-in-right text-primary"></i> Login</a></li>
                    <li><a class="dropdown-item py-2 d-flex align-items-center gap-2" style="font-size:13px;" href="{{ route('customer_register') }}"><i class="bi bi-person-plus text-primary"></i> Register</a></li>
                    @endif
                </ul>
            </div>

            {{-- MOBILE TOGGLE --}}
            <button class="navbar-toggler border-0 p-1 d-lg-none flex-shrink-0"
                    type="button" data-bs-toggle="collapse" data-bs-target="#navRow2">
                <span class="navbar-toggler-icon"></span>
            </button>

        </div>

        {{-- ROW 2: Nav links (desktop always visible, mobile collapse) --}}
        <div class="collapse navbar-collapse d-lg-block" id="navRow2"
             style="border-top:1px solid rgba(255,255,255,.15);">
            <ul class="navbar-nav flex-lg-row flex-wrap py-1 align-items-lg-center gap-0">

                <li class="nav-item">
                    <a class="nav-link px-3 py-2 rounded-1 d-flex align-items-center gap-1 {{ request()->routeIs('home') ? 'fw-semibold bg-white bg-opacity-10' : '' }}"
                       href="{{ route('home') }}" style="font-size:13px;"
                       onmouseover="this.style.background='rgba(255,255,255,.15)'" onmouseout="this.style.background='{{ request()->routeIs('home') ? 'rgba(255,255,255,.1)' : 'transparent' }}'">
                        <i class="bi bi-house"></i> Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link px-3 py-2 rounded-1 d-flex align-items-center gap-1 {{ request()->routeIs('all_products') ? 'fw-semibold bg-white bg-opacity-10' : '' }}"
                       href="{{ route('all_products') }}" style="font-size:13px;"
                       onmouseover="this.style.background='rgba(255,255,255,.15)'" onmouseout="this.style.background='{{ request()->routeIs('all_products') ? 'rgba(255,255,255,.1)' : 'transparent' }}'">
                        <i class="bi bi-grid"></i> Products
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link px-3 py-2 rounded-1 d-flex align-items-center gap-1"
                       href="{{ route('sales.page') }}" style="font-size:13px;"
                       onmouseover="this.style.background='rgba(255,255,255,.15)'" onmouseout="this.style.background='transparent'">
                        <i class="bi bi-telephone"></i> Sale
                    </a>
                </li>

                @yield('specific_menu')

            </ul>
        </div>

    </div>
</nav>

<script>
(function(){
    const input=document.getElementById('globalSearch'),
          box=document.getElementById('searchSuggestions'),
          list=document.getElementById('suggestionList');
    if(!input) return;
    let timer;

    input.addEventListener('input',function(){
        clearTimeout(timer);
        const q=this.value.trim();
        if(q.length<2){box.style.display='none';return;}
        timer=setTimeout(()=>{
            fetch(`{{ route('search.suggest') }}?q=${encodeURIComponent(q)}`,{headers:{'X-Requested-With':'XMLHttpRequest'}})
            .then(r=>r.json())
            .then(data=>{
                if(!data||!data.length){box.style.display='none';return;}
                list.innerHTML=data.slice(0,7).map(i=>`
                    <a href="{{ url('products') }}/${i.id}" class="suggestion-item d-flex align-items-center gap-3 px-3 py-2 text-decoration-none border-bottom"
                       style="color:#212529;background:#fff;" onmouseover="this.style.background='#f0f5ff'" onmouseout="this.style.background='#fff'">
                        <img src="/storage/${i.image||''}" onerror="this.src='https://placehold.co/40x40/e8f0fe/2874f0?text=?'"
                             width="40" height="40" class="rounded-2 flex-shrink-0" style="object-fit:cover;border:1px solid #eee;">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 fw-semibold text-truncate" style="font-size:13px;">${i.name}</p>
                            <small class="text-success fw-bold">₹${parseFloat(i.price||0).toLocaleString('en-IN')}</small>
                        </div>
                        <i class="bi bi-arrow-up-left text-muted" style="font-size:11px;"></i>
                    </a>`).join('')+
                `<a href="{{ route('search') }}?q=${encodeURIComponent(q)}" class="d-block text-center py-2 text-decoration-none fw-semibold"
                    style="font-size:13px;color:#2874f0;background:#fafafa;border-top:1px solid #eee;"
                    onmouseover="this.style.background='#e8f0fe'" onmouseout="this.style.background='#fafafa'">
                    See all results for "<strong>${q}</strong>"
                </a>`;
                box.style.display='block';
            }).catch(()=>box.style.display='none');
        },300);
    });

    input.addEventListener('focus',function(){if(this.value.trim().length>=2)this.dispatchEvent(new Event('input'));});
    document.addEventListener('click',e=>{if(!document.getElementById('searchWrapper').contains(e.target))box.style.display='none';});

    input.addEventListener('keydown',function(e){
        const items=list.querySelectorAll('.suggestion-item'),curr=list.querySelector('.ks-sel');
        let idx=Array.from(items).indexOf(curr);
        if(e.key==='ArrowDown'){e.preventDefault();curr&&(curr.classList.remove('ks-sel'),curr.style.background='#fff');idx=(idx+1)%items.length;items[idx].classList.add('ks-sel');items[idx].style.background='#f0f5ff';items[idx].scrollIntoView({block:'nearest'});}
        else if(e.key==='ArrowUp'){e.preventDefault();curr&&(curr.classList.remove('ks-sel'),curr.style.background='#fff');idx=(idx-1+items.length)%items.length;items[idx].classList.add('ks-sel');items[idx].style.background='#f0f5ff';items[idx].scrollIntoView({block:'nearest'});}
        else if(e.key==='Enter'&&curr){e.preventDefault();curr.click();}
        else if(e.key==='Escape'){box.style.display='none';this.blur();}
    });
})();
</script>

<script>
let timer;

document.getElementById('globalSearch').addEventListener('keyup', function() {
    clearTimeout(timer);

    let query = this.value;

    if (query.length < 2) {
        document.getElementById('searchSuggestions').style.display = 'none';
        return;
    }

    timer = setTimeout(() => {
        fetch(`/search/ajax?q=${query}`)
        .then(res => res.json())
        .then(data => {
            let html = '';

            if (!data || data.length === 0) {
                html = `<p class="p-2 text-muted">No results found</p>`;
            } else {
                data.forEach(item => {
                    html += `
                    <a href="/product/${item.id}" class="d-block px-3 py-2 text-decoration-none text-dark border-bottom">
                        <div class="fw-semibold">${item.name}</div>
                        <small class="text-muted">₹${item.price ?? 0}</small>
                    </a>`;
                });
            }

            document.getElementById('suggestionList').innerHTML = html;
            document.getElementById('searchSuggestions').style.display = 'block';
        })
        .catch(err => {
            console.error("Search error:", err);
        });

    }, 300); // debounce
});

// Hide on click outside
document.addEventListener('click', function(e) {
    if (!document.getElementById('searchWrapper').contains(e.target)) {
        document.getElementById('searchSuggestions').style.display = 'none';
    }
});
</script>