<footer style="background:#1a1a2e;border-top:3px solid #2874f0;" class="text-white mt-5">

    {{-- TOP SECTION --}}
    <div class="container py-5">
        <div class="row g-4">

            {{-- BRAND --}}
            <div class="col-12 col-md-4 col-lg-3">
                <a href="{{ route('home') }}" class="text-decoration-none d-inline-block mb-3">
                    <span class="fw-bold" style="font-size:22px;color:#ffe500;">My</span><span class="fw-bold text-white" style="font-size:22px;">Shop</span>
                    <small class="d-block text-white-50" style="font-size:9px;letter-spacing:2px;margin-top:-2px;">EXPLORE PLUS</small>
                </a>
                <p class="text-white-50 mb-3" style="font-size:13px;line-height:1.7;">
                    India's best online store for fashion, electronics and lifestyle products. Shop smarter, live better.
                </p>
                {{-- Social Icons --}}
                <div class="d-flex gap-2 flex-wrap">

                    {{-- Facebook --}}
                    <a href="#" class="d-flex align-items-center justify-content-center rounded-2 text-decoration-none"
                    style="width:36px;height:36px;background:#1877f2;transition:opacity .2s;"
                    onmouseover="this.style.opacity='.8'" onmouseout="this.style.opacity='1'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="#fff">
                            <path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.41c0-3.025 1.792-4.697 4.533-4.697 1.312 0 2.686.236 2.686.236v2.97h-1.513c-1.491 0-1.956.93-1.956 1.886v2.268h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/>
                        </svg>
                    </a>

                    {{-- Instagram --}}
                    <a href="#" class="d-flex align-items-center justify-content-center rounded-2 text-decoration-none"
                    style="width:36px;height:36px;background:radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);transition:opacity .2s;"
                    onmouseover="this.style.opacity='.8'" onmouseout="this.style.opacity='1'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="#fff">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/>
                        </svg>
                    </a>

                    {{-- X (Twitter) --}}
                    <a href="#" class="d-flex align-items-center justify-content-center rounded-2 text-decoration-none"
                    style="width:36px;height:36px;background:#000;transition:opacity .2s;"
                    onmouseover="this.style.opacity='.8'" onmouseout="this.style.opacity='1'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="#fff">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.746l7.73-8.835L1.254 2.25H8.08l4.253 5.622 5.911-5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>

                    {{-- YouTube --}}
                    <a href="#" class="d-flex align-items-center justify-content-center rounded-2 text-decoration-none"
                    style="width:36px;height:36px;background:#ff0000;transition:opacity .2s;"
                    onmouseover="this.style.opacity='.8'" onmouseout="this.style.opacity='1'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="#fff">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>

                    {{-- WhatsApp --}}
                    <a href="https://wa.me/919027116545" class="d-flex align-items-center justify-content-center rounded-2 text-decoration-none"
                    style="width:36px;height:36px;background:#25d366;transition:opacity .2s;"
                    onmouseover="this.style.opacity='.8'" onmouseout="this.style.opacity='1'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="#fff">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
                        </svg>
                    </a>

                </div>
            </div>

            {{-- QUICK LINKS --}}
            <div class="col-6 col-md-2 col-lg-2">
                <h6 class="fw-semibold mb-3 pb-2 d-flex align-items-center gap-2"
                    style="font-size:13px;color:#ffe500;border-bottom:1px solid rgba(255,255,255,.1);">
                    <i class="bi bi-grid-fill"></i> Quick Links
                </h6>
                <ul class="list-unstyled mb-0" style="font-size:13px;">
                    <li class="mb-2">
                        <a href="{{ route('home') }}" class="text-white-50 text-decoration-none d-flex align-items-center gap-2"
                           onmouseover="this.style.color='#ffe500'" onmouseout="this.style.color=''">
                            <i class="bi bi-chevron-right" style="font-size:10px;"></i> Home
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('all_products') }}" class="text-white-50 text-decoration-none d-flex align-items-center gap-2"
                           onmouseover="this.style.color='#ffe500'" onmouseout="this.style.color=''">
                            <i class="bi bi-chevron-right" style="font-size:10px;"></i> Products
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('wishlist.index') }}" class="text-white-50 text-decoration-none d-flex align-items-center gap-2"
                           onmouseover="this.style.color='#ffe500'" onmouseout="this.style.color=''">
                            <i class="bi bi-chevron-right" style="font-size:10px;"></i> Wishlist
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('cart_index') }}" class="text-white-50 text-decoration-none d-flex align-items-center gap-2"
                           onmouseover="this.style.color='#ffe500'" onmouseout="this.style.color=''">
                            <i class="bi bi-chevron-right" style="font-size:10px;"></i> Cart
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-white-50 text-decoration-none d-flex align-items-center gap-2"
                           onmouseover="this.style.color='#ffe500'" onmouseout="this.style.color=''">
                            <i class="bi bi-chevron-right" style="font-size:10px;"></i> Contact
                        </a>
                    </li>
                </ul>
            </div>

            {{-- MY ACCOUNT --}}
            <div class="col-6 col-md-2 col-lg-2">
                <h6 class="fw-semibold mb-3 pb-2 d-flex align-items-center gap-2"
                    style="font-size:13px;color:#ffe500;border-bottom:1px solid rgba(255,255,255,.1);">
                    <i class="bi bi-person-fill"></i> My Account
                </h6>
                <ul class="list-unstyled mb-0" style="font-size:13px;">
                    @if(session('customer_id'))
                    <li class="mb-2">
                        <a href="{{ route('customer_profile') }}" class="text-white-50 text-decoration-none d-flex align-items-center gap-2"
                           onmouseover="this.style.color='#ffe500'" onmouseout="this.style.color=''">
                            <i class="bi bi-chevron-right" style="font-size:10px;"></i> My Profile
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('customer.orders') }}" class="text-white-50 text-decoration-none d-flex align-items-center gap-2"
                           onmouseover="this.style.color='#ffe500'" onmouseout="this.style.color=''">
                            <i class="bi bi-chevron-right" style="font-size:10px;"></i> My Orders
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('wishlist.index') }}" class="text-white-50 text-decoration-none d-flex align-items-center gap-2"
                           onmouseover="this.style.color='#ffe500'" onmouseout="this.style.color=''">
                            <i class="bi bi-chevron-right" style="font-size:10px;"></i> My Wishlist
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('customer_logout') }}" class="text-white-50 text-decoration-none d-flex align-items-center gap-2"
                           onmouseover="this.style.color='#ff6b6b'" onmouseout="this.style.color=''">
                            <i class="bi bi-chevron-right" style="font-size:10px;"></i> Logout
                        </a>
                    </li>
                    @else
                    <li class="mb-2">
                        <a href="{{ route('customer_login') }}" class="text-white-50 text-decoration-none d-flex align-items-center gap-2"
                           onmouseover="this.style.color='#ffe500'" onmouseout="this.style.color=''">
                            <i class="bi bi-chevron-right" style="font-size:10px;"></i> Login
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('customer_register') }}" class="text-white-50 text-decoration-none d-flex align-items-center gap-2"
                           onmouseover="this.style.color='#ffe500'" onmouseout="this.style.color=''">
                            <i class="bi bi-chevron-right" style="font-size:10px;"></i> Register
                        </a>
                    </li>
                    @endif
                </ul>
            </div>

            {{-- CONTACT --}}
            <div class="col-12 col-md-4 col-lg-3">
                <h6 class="fw-semibold mb-3 pb-2 d-flex align-items-center gap-2"
                    style="font-size:13px;color:#ffe500;border-bottom:1px solid rgba(255,255,255,.1);">
                    <i class="bi bi-headset"></i> Contact Us
                </h6>
                <ul class="list-unstyled mb-0" style="font-size:13px;">
                    <li class="mb-3 d-flex align-items-start gap-2">
                        <i class="bi bi-geo-alt-fill mt-1 flex-shrink-0" style="color:#2874f0;"></i>
                        <span class="text-white-50">123, Shop Street, Saharanpur, Utter Pradesh - 400001</span>
                    </li>
                    <li class="mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-telephone-fill flex-shrink-0" style="color:#2874f0;"></i>
                        <a href="tel:+919027116545" class="text-white-50 text-decoration-none"
                           onmouseover="this.style.color='#ffe500'" onmouseout="this.style.color=''">+91 9027116545</a>
                    </li>
                    <li class="mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-envelope-fill flex-shrink-0" style="color:#2874f0;"></i>
                        <a href="mailto:support@myshop.com" class="text-white-50 text-decoration-none"
                           onmouseover="this.style.color='#ffe500'" onmouseout="this.style.color=''">support@myshop.com</a>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="bi bi-clock-fill flex-shrink-0" style="color:#2874f0;"></i>
                        <span class="text-white-50">Mon - Sat: 9:00 AM – 6:00 PM</span>
                    </li>
                </ul>
            </div>

            {{-- NEWSLETTER --}}
            <div class="col-12 col-md-12 col-lg-2">
                <h6 class="fw-semibold mb-3 pb-2 d-flex align-items-center gap-2"
                    style="font-size:13px;color:#ffe500;border-bottom:1px solid rgba(255,255,255,.1);">
                    <i class="bi bi-envelope-paper-fill"></i> Newsletter
                </h6>
                <p class="text-white-50 mb-2" style="font-size:12px;line-height:1.6;">
                    Subscribe to get the latest deals and offers.
                </p>
                <form onsubmit="return false;">
                    <div class="input-group mb-2" style="border-radius:6px;overflow:hidden;">
                        <input type="email" class="form-control border-0 shadow-none bg-white"
                               placeholder="Your email" style="font-size:12px;padding:8px 10px;">
                        <button class="btn border-0 fw-bold" type="submit"
                                style="background:#2874f0;color:#fff;font-size:12px;padding:8px 12px;">
                            Send
                        </button>
                    </div>
                </form>
                {{-- App Badges --}}
                <p class="text-white-50 mt-3 mb-2" style="font-size:12px;">Download App:</p>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="#" class="text-decoration-none">
                        <span class="badge d-flex align-items-center gap-1 py-2 px-2 rounded-2"
                              style="background:rgba(255,255,255,.1);font-size:11px;font-weight:500;">
                            <i class="bi bi-apple" style="font-size:14px;"></i> App Store
                        </span>
                    </a>
                    <a href="#" class="text-decoration-none">
                        <span class="badge d-flex align-items-center gap-1 py-2 px-2 rounded-2"
                              style="background:rgba(255,255,255,.1);font-size:11px;font-weight:500;">
                            <i class="bi bi-google-play" style="font-size:14px;"></i> Play Store
                        </span>
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- BOTTOM BAR --}}
    <div style="background:rgba(0,0,0,.3);border-top:1px solid rgba(255,255,255,.08);">
        <div class="container py-3">
            <div class="row align-items-center g-2">
                <div class="col-12 col-md-6 text-center text-md-start">
                    <p class="mb-0 text-white-50" style="font-size:12px;">
                        © {{ date('Y') }} <span style="color:#ffe500;font-weight:600;">MyShop</span>. All Rights Reserved.
                        Made with <i class="bi bi-heart-fill text-danger" style="font-size:10px;"></i> in India | Desgined By Akshay Sain
                    </p>
                </div>
                <div class="col-12 col-md-6 text-center text-md-end">
                    <span class="text-white-50 me-3" style="font-size:12px;">
                        <i class="bi bi-shield-check text-success me-1"></i>Secure Payments
                    </span>
                    <span class="text-white-50 me-3" style="font-size:12px;">
                        <i class="bi bi-truck text-primary me-1"></i>Free Delivery
                    </span>
                    <span class="text-white-50" style="font-size:12px;">
                        <i class="bi bi-arrow-counterclockwise text-warning me-1"></i>Easy Returns
                    </span>
                </div>
            </div>
        </div>
    </div>

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>