{{--
    Vendor Sidebar — fully responsive
    • Desktop (≥992px) : fixed left sidebar, 260px wide
    • Tablet  (768–991px): collapsible sidebar via hamburger
    • Mobile  (768px)  : top navbar + bottom tab bar
--}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap');
/* ── Design tokens ───────────────────────────── */
:root {
    --sb-bg:        #0f0e17;
    --sb-surface:   #1a1928;
    --sb-border:    rgba(255,255,255,.07);
    --sb-accent:    #5b4cf5;
    --sb-accent-2:  #7c6ff7;
    --sb-text:      rgba(255,255,255,.62);
    --sb-text-hi:   #ffffff;
    --sb-danger:    #d63a5c;
    --sb-danger-bg: rgba(214,58,92,.12);
    --sb-w:         260px;
    --sb-top-h:     62px;
    --sb-bot-h:     66px;
    --r:            12px;
    --rp:           999px;
    --trans:        .22s cubic-bezier(.4,0,.2,1);
}

/* ── Reset scoped ────────────────────────────── */
.vs-wrap *, .vs-wrap *::before, .vs-wrap *::after { box-sizing: border-box; }
.vs-wrap a { text-decoration: none; }
.vs-wrap button { cursor: pointer; border: none; background: none; font-family: inherit; }

/* ═══════════════════════════════════════════════
   DESKTOP SIDEBAR  (≥992px)
═══════════════════════════════════════════════ */
.vs-sidebar {
    position: fixed;
    top: 0; left: 0;
    width: var(--sb-w);
    height: 100vh;
    background: var(--sb-bg);
    display: flex;
    flex-direction: column;
    padding: 0;
    z-index: 900;
    border-right: 1px solid var(--sb-border);
    transition: transform var(--trans);
}

/* decorative top orb */
.vs-sidebar::before {
    content: '';
    position: absolute;
    top: -80px; right: -80px;
    width: 220px; height: 220px;
    background: var(--sb-accent);
    border-radius: 50%;
    opacity: .15;
    pointer-events: none;
}

/* brand */
.vs-brand {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: 1.5rem 1.4rem 1.2rem;
    border-bottom: 1px solid var(--sb-border);
    position: relative;
    z-index: 1;
}
.vs-brand-icon {
    width: 38px; height: 38px;
    border-radius: 10px;
    background: var(--sb-accent);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(91,76,245,.45);
}
.vs-brand-text {
    font-family: 'Syne', sans-serif;
    font-size: .95rem;
    font-weight: 800;
    color: var(--sb-text-hi);
    letter-spacing: -.2px;
    line-height: 1.1;
}
.vs-brand-sub {
    font-size: .68rem;
    color: var(--sb-text);
    font-weight: 400;
    display: block;
    margin-top: .1rem;
}

/* nav section */
.vs-nav {
    flex: 1;
    padding: 1.1rem .9rem;
    overflow-y: auto;
    scrollbar-width: none;
}
.vs-nav::-webkit-scrollbar { display: none; }

.vs-section-lbl {
    font-size: .6rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    color: rgba(255,255,255,.25);
    padding: 0 .6rem;
    margin: .9rem 0 .4rem;
}
.vs-section-lbl:first-child { margin-top: 0; }

/* nav link */
.vs-link {
    display: flex;
    align-items: center;
    gap: .8rem;
    padding: .7rem .9rem;
    border-radius: var(--r);
    color: var(--sb-text);
    font-family: 'DM Sans', sans-serif;
    font-size: .875rem;
    font-weight: 400;
    transition: background var(--trans), color var(--trans), transform .15s;
    position: relative;
    margin-bottom: 2px;
}
.vs-link:hover {
    background: rgba(255,255,255,.06);
    color: var(--sb-text-hi);
    transform: translateX(3px);
}
.vs-link.active {
    background: rgba(91,76,245,.22);
    color: var(--sb-text-hi);
    font-weight: 500;
}
.vs-link.active::before {
    content: '';
    position: absolute;
    left: 0; top: 20%; bottom: 20%;
    width: 3px;
    background: var(--sb-accent-2);
    border-radius: 0 3px 3px 0;
}
.vs-link-icon {
    width: 32px; height: 32px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem;
    flex-shrink: 0;
    background: rgba(255,255,255,.05);
    transition: background var(--trans);
}
.vs-link:hover .vs-link-icon,
.vs-link.active .vs-link-icon {
    background: rgba(91,76,245,.3);
}
.vs-link-label { flex: 1; }
.vs-badge-pill {
    font-size: .62rem;
    font-weight: 700;
    background: var(--sb-accent);
    color: #fff;
    border-radius: var(--rp);
    padding: .15rem .5rem;
}

/* footer / logout */
.vs-footer {
    padding: .9rem;
    border-top: 1px solid var(--sb-border);
}
.vs-user-row {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .65rem .7rem;
    border-radius: var(--r);
    margin-bottom: .6rem;
    background: rgba(255,255,255,.04);
    border: 1px solid var(--sb-border);
}
.vs-avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--sb-accent), var(--sb-accent-2));
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
    font-family: 'Syne', sans-serif;
}
.vs-user-name  { font-size: .82rem; font-weight: 500; color: var(--sb-text-hi); }
.vs-user-role  { font-size: .68rem; color: var(--sb-text); }

.vs-logout-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .55rem;
    width: 100%;
    padding: .65rem;
    border-radius: var(--r);
    background: var(--sb-danger-bg);
    color: var(--sb-danger);
    font-family: 'DM Sans', sans-serif;
    font-size: .83rem;
    font-weight: 500;
    border: 1px solid rgba(214,58,92,.18);
    transition: background var(--trans), transform .15s;
}
.vs-logout-btn:hover {
    background: rgba(214,58,92,.22);
    transform: translateY(-1px);
}

/* ═══════════════════════════════════════════════
   TOPBAR  (shown on tablet + mobile)
═══════════════════════════════════════════════ */
.vs-topbar {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0;
    height: var(--sb-top-h);
    background: var(--sb-bg);
    border-bottom: 1px solid var(--sb-border);
    z-index: 901;
    align-items: center;
    justify-content: space-between;
    padding: 0 1.1rem;
    gap: .75rem;
}
.vs-hamburger {
    width: 40px; height: 40px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    background: rgba(255,255,255,.06);
    color: var(--sb-text-hi);
    font-size: 1rem;
    flex-shrink: 0;
    transition: background var(--trans);
}
.vs-hamburger:hover { background: rgba(255,255,255,.12); }
.vs-topbar-brand {
    font-family: 'Syne', sans-serif;
    font-size: .95rem;
    font-weight: 800;
    color: var(--sb-text-hi);
    flex: 1;
}
.vs-topbar-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--sb-accent), var(--sb-accent-2));
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem;
    font-weight: 700;
    color: #fff;
    font-family: 'Syne', sans-serif;
    flex-shrink: 0;
}

/* overlay */
.vs-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.55);
    backdrop-filter: blur(2px);
    z-index: 899;
    opacity: 0;
    transition: opacity var(--trans);
}
.vs-overlay.show { display: block; opacity: 1; }

/* ═══════════════════════════════════════════════
   BOTTOM TAB BAR  (mobile only, <576px)
═══════════════════════════════════════════════ */
.vs-bottom-nav {
    display: none;
    position: fixed;
    bottom: 0; left: 0; right: 0;
    height: var(--sb-bot-h);
    background: var(--sb-bg);
    border-top: 1px solid var(--sb-border);
    z-index: 901;
    align-items: center;
    justify-content: space-around;
    padding: 0 .5rem;
    padding-bottom: env(safe-area-inset-bottom, 0);
}
.vs-tab {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .18rem;
    color: var(--sb-text);
    font-family: 'DM Sans', sans-serif;
    font-size: .58rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: .4px;
    padding: .4rem .6rem;
    border-radius: 10px;
    transition: color var(--trans), background var(--trans);
    min-width: 52px;
}
.vs-tab.active { color: var(--sb-accent-2); }
.vs-tab-icon { font-size: 1.1rem; }
.vs-tab-logout { color: var(--sb-danger); }
.vs-tab-logout.active { color: var(--sb-danger); }

/* content push — desktop */
.vs-content-push { margin-left: var(--sb-w); }

/* ═══════════════════════════════════════════════
   RESPONSIVE BREAKPOINTS
═══════════════════════════════════════════════ */

/* Tablet: 768px – 991px */
@media (max-width: 991px) {
    .vs-topbar  { display: flex; }
    .vs-sidebar {
        transform: translateX(-100%);
        top: 0;
        /* slide over topbar */
        z-index: 902;
    }
    .vs-sidebar.open { transform: translateX(0); }
    .vs-content-push { margin-left: 0; padding-top: var(--sb-top-h); }
    .vs-close-btn {
        display: flex;
        position: absolute;
        top: 1rem; right: 1rem;
        width: 32px; height: 32px;
        border-radius: 8px;
        align-items: center; justify-content: center;
        background: rgba(255,255,255,.06);
        color: var(--sb-text);
        font-size: .85rem;
        z-index: 1;
    }
    .vs-close-btn:hover { background: rgba(255,255,255,.12); color: #fff; }
}

/* Mobile: <576px — show bottom tab bar, hide sidebar link text */
@media (max-width: 575px) {
    .vs-bottom-nav { display: flex; }
    .vs-content-push {
        padding-top: var(--sb-top-h);
        padding-bottom: var(--sb-bot-h);
    }
}

/* Desktop: ≥992px — hide topbar/bottom nav, show close btn never */
@media (min-width: 992px) {
    .vs-close-btn  { display: none; }
    .vs-overlay    { display: none !important; }
    .vs-topbar     { display: none; }
    .vs-bottom-nav { display: none; }
    .vs-sidebar    { transform: none !important; }
}
</style>

{{-- ══════════════════════════════════════════
     TOP BAR  (tablet + mobile)
══════════════════════════════════════════ --}}
<div class="vs-topbar">
    <button class="vs-hamburger" id="vs-open" aria-label="Open menu" aria-expanded="false">
        <i class="fas fa-bars"></i>
    </button>
    <span class="vs-topbar-brand">Vendor Panel</span>
    <div class="vs-topbar-avatar">V</div>
</div>

{{-- overlay --}}
<div class="vs-overlay" id="vs-overlay" aria-hidden="true"></div>

{{-- ══════════════════════════════════════════
     SIDEBAR
══════════════════════════════════════════ --}}
<aside class="vs-wrap vs-sidebar" id="vs-sidebar" aria-label="Vendor navigation">

    {{-- close (tablet only) --}}
    <button class="vs-close-btn" id="vs-close" aria-label="Close menu">
        <i class="fas fa-times"></i>
    </button>

    {{-- brand --}}
    <div class="vs-brand">
        <div class="vs-brand-icon">🏪</div>
        <div>
            <div class="vs-brand-text">
                Vendor Panel
                <span class="vs-brand-sub">Store management</span>
            </div>
        </div>
    </div>

    {{-- nav --}}
    <nav class="vs-nav" role="navigation">

        <div class="vs-section-lbl">Main</div>

        <a href="{{ route('vendor.dashboard') }}"
           class="vs-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
            <span class="vs-link-icon"><i class="fa fa-dashboard"></i></span>
            <span class="vs-link-label">Dashboard</span>
        </a>

        <a href="{{ route('vendor_product') }}"
           class="vs-link {{ request()->routeIs('vendor_product') ? 'active' : '' }}">
            <span class="vs-link-icon"><i class="fa fa-box"></i></span>
            <span class="vs-link-label">Products</span>
        </a>

        <a href="{{ route('vendor.earnings') }}"
           class="vs-link {{ request()->routeIs('vendor.earnings') ? 'active' : '' }}">
            <span class="vs-link-icon"><i class="fa fa-money-bill"></i></span>
            <span class="vs-link-label">Earnings</span>
        </a>

        <div class="vs-section-lbl">Account</div>

        <a href="{{ route('vendor.profile') }}"
           class="vs-link {{ request()->routeIs('vendor.profile') ? 'active' : '' }}">
            <span class="vs-link-icon"><i class="fa fa-user"></i></span>
            <span class="vs-link-label">Profile</span>
        </a>

    </nav>

    {{-- footer --}}
    <div class="vs-footer">
        <div class="vs-user-row">
            <div class="vs-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'V', 0, 1)) }}</div>
            <div>
                <div class="vs-user-name">{{ auth()->user()->name ?? 'Vendor' }}</div>
                <div class="vs-user-role">Vendor Account</div>
            </div>
        </div>
        <form action="{{ route('vendor.logout') }}" method="POST">
            @csrf
            <button type="submit" class="vs-logout-btn">
                <i class="fa fa-sign-out-alt"></i>
                Logout
            </button>
        </form>
    </div>

</aside>

{{-- ══════════════════════════════════════════
     BOTTOM TAB BAR  (mobile <576px)
══════════════════════════════════════════ --}}
<nav class="vs-bottom-nav" aria-label="Mobile navigation">

    <a href="{{ route('vendor.dashboard') }}"
       class="vs-tab {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
        <span class="vs-tab-icon"><i class="fa fa-dashboard"></i></span>
        <span>Home</span>
    </a>

    <a href="{{ route('vendor_product') }}"
       class="vs-tab {{ request()->routeIs('vendor_product') ? 'active' : '' }}">
        <span class="vs-tab-icon"><i class="fa fa-box"></i></span>
        <span>Products</span>
    </a>

    <a href="{{ route('vendor.earnings') }}"
       class="vs-tab {{ request()->routeIs('vendor.earnings') ? 'active' : '' }}">
        <span class="vs-tab-icon"><i class="fa fa-money-bill"></i></span>
        <span>Earnings</span>
    </a>

    <a href="{{ route('vendor.profile') }}"
       class="vs-tab {{ request()->routeIs('vendor.profile') ? 'active' : '' }}">
        <span class="vs-tab-icon"><i class="fa fa-user"></i></span>
        <span>Profile</span>
    </a>

    <form action="{{ route('vendor.logout') }}" method="POST" style="display:contents">
        @csrf
        <button type="submit" class="vs-tab vs-tab-logout">
            <span class="vs-tab-icon"><i class="fa fa-sign-out-alt"></i></span>
            <span>Logout</span>
        </button>
    </form>

</nav>

<script>
(function () {
    const sidebar  = document.getElementById('vs-sidebar');
    const overlay  = document.getElementById('vs-overlay');
    const openBtn  = document.getElementById('vs-open');
    const closeBtn = document.getElementById('vs-close');

    function open() {
        sidebar.classList.add('open');
        overlay.classList.add('show');
        openBtn && openBtn.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }
    function close() {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
        openBtn && openBtn.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }

    openBtn  && openBtn.addEventListener('click', open);
    closeBtn && closeBtn.addEventListener('click', close);
    overlay  && overlay.addEventListener('click', close);

    // close on Escape
    document.addEventListener('keydown', e => { if (e.key === 'Escape') close(); });
})();
</script>