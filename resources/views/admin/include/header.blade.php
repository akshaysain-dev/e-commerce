@php
    $currentRoute = Route::currentRouteName();

    $navGroups = [
        [
            'label' => 'Main',
            'items' => [
                ['label' => 'Dashboard',  'route' => 'admin_dashboard',          'icon' => '⚡'],
            ]
        ],
        [
            'label' => 'Catalogue',
            'items' => [
                ['label' => 'Categories', 'route' => 'admin_category',           'icon' => '🏷'],
                ['label' => 'Products',   'route' => 'admin_product',            'icon' => '📦'],
                ['label' => 'Attributes', 'route' => 'admin.attributes.index',   'icon' => '🎛'],
                ['label' => 'Types',      'route' => 'product-types.index',      'icon' => '🗂'],
                ['label' => 'Sale',       'route' => 'admin.sales.index',        'icon' => '🔥'],
                ['label' => 'Tags',       'route' => 'admin.tags.index',         'icon' => '🔖'],
				['label' => 'Margins',    'route' => 'admin.margins.index',      'icon' => '🏷'],
                ['label' => 'Tax & Shipping',    'route' => 'tax_shipping',      'icon' => '📦'],
            ]
        ],
        [
            'label' => 'Commerce',
            'items' => [
                ['label' => 'Orders',     'route' => 'admin.orders',             'icon' => '🛒'],
                ['label' => 'Coupons',    'route' => 'admin.coupons.index',      'icon' => '🎟'],
                ['label' => 'Customers & Orders',  'route' => 'admin_customers',          'icon' => '👥'],
                ['label' => 'Sales Report',  'route' => 'sales.report',          'icon' => '🔖'],
            ]
        ],
        [
            'label' => 'Content',
            'items' => [
                ['label' => 'Banners',    'route' => 'banners.index',            'icon' => '🖼'],
            ]
        ],
    ];
@endphp

<style>
    :root {
        --sb-w:       240px;
        --sb-bg:      #0f172a;
        --sb-border:  rgba(255,255,255,.07);
        --sb-muted:   #64748b;
        --sb-text:    #94a3b8;
        --sb-active:  #f1f5f9;
        --sb-hover:   rgba(255,255,255,.05);
        --sb-accent:  #6366f1;
        --tb-h:       56px;
    }

    body {
        margin: 0;
        padding-top: var(--tb-h);
        padding-left: var(--sb-w);
        background: #f1f5f9;
        transition: padding-left .25s;
    }

    /* ── Sidebar ─────────────────────────────────────── */
    #adminSidebar {
        position: fixed;
        top: 0; left: 0; bottom: 0;
        width: var(--sb-w);
        background: var(--sb-bg);
        display: flex;
        flex-direction: column;
        z-index: 1040;
        overflow-y: auto;
        overflow-x: hidden;
        scrollbar-width: none;
        transition: transform .25s cubic-bezier(.4,0,.2,1);
    }
    #adminSidebar::-webkit-scrollbar { display: none; }

    .sb-logo {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 18px 20px;
        border-bottom: 1px solid var(--sb-border);
        text-decoration: none;
    }
    .sb-logo-icon {
        width: 32px; height: 32px;
        background: var(--sb-accent);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: .9rem; flex-shrink: 0;
        box-shadow: 0 0 14px rgba(99,102,241,.35);
    }
    .sb-logo-name {
        font-size: .92rem;
        font-weight: 700;
        color: #f8fafc;
        letter-spacing: -.01em;
        line-height: 1.1;
    }
    .sb-logo-sub {
        font-size: .65rem;
        color: var(--sb-muted);
        text-transform: uppercase;
        letter-spacing: .06em;
        font-weight: 600;
    }

    .sb-nav { flex: 1; padding: 10px 0; }

    .sb-group-label {
        font-size: .62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--sb-muted);
        padding: 14px 20px 5px;
    }

    .sb-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 20px;
        color: var(--sb-text);
        text-decoration: none;
        font-size: .855rem;
        font-weight: 500;
        transition: background .13s, color .13s;
        position: relative;
        white-space: nowrap;
    }
    .sb-link:hover {
        background: var(--sb-hover);
        color: #e2e8f0;
        text-decoration: none;
    }
    .sb-link.active {
        background: rgba(99,102,241,.12);
        color: var(--sb-active);
    }
    .sb-link.active::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: var(--sb-accent);
        border-radius: 0 3px 3px 0;
    }
    .sb-link .sb-icon {
        font-size: .95rem;
        width: 20px;
        text-align: center;
        flex-shrink: 0;
    }

    .sb-footer {
        padding: 14px;
        border-top: 1px solid var(--sb-border);
    }
    .sb-user {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 9px 10px;
        background: rgba(255,255,255,.04);
        border-radius: 9px;
        margin-bottom: 8px;
    }
    .sb-avatar {
        width: 30px; height: 30px;
        background: var(--sb-accent);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: .75rem; font-weight: 700; color: #fff;
        flex-shrink: 0;
    }
    .sb-uname  { font-size: .82rem; font-weight: 600; color: #e2e8f0; line-height: 1.2; }
    .sb-urole  { font-size: .68rem; color: var(--sb-muted); }
    .sb-logout {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        width: 100%;
        padding: 8px;
        background: rgba(239,68,68,.08);
        border: 1px solid rgba(239,68,68,.18);
        border-radius: 8px;
        color: #f87171;
        font-size: .82rem;
        font-weight: 600;
        text-decoration: none;
        transition: background .15s, color .15s;
    }
    .sb-logout:hover { background: rgba(239,68,68,.18); color: #fca5a5; text-decoration: none; }

    /* ── Topbar ──────────────────────────────────────── */
    #adminTopbar {
        position: fixed;
        top: 0;
        left: var(--sb-w);
        right: 0;
        height: var(--tb-h);
        background: #fff;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 20px;
        z-index: 1030;
        transition: left .25s;
        box-shadow: 0 1px 4px rgba(0,0,0,.05);
    }

    .tb-left { display: flex; align-items: center; gap: 12px; }

    #sidebarToggleBtn {
        display: none;
        background: none;
        border: 1px solid #e5e7eb;
        border-radius: 7px;
        padding: 5px 9px;
        cursor: pointer;
        color: #64748b;
        font-size: 1.1rem;
        line-height: 1;
        transition: background .15s;
    }
    #sidebarToggleBtn:hover { background: #f8fafc; }

    .tb-breadcrumb {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: .83rem;
        color: #94a3b8;
    }
    .tb-breadcrumb .tb-page {
        font-weight: 600;
        color: #1e293b;
    }

    .tb-right { display: flex; align-items: center; gap: 6px; }

    .tb-icon-btn {
        width: 34px; height: 34px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: #fff;
        display: flex; align-items: center; justify-content: center;
        color: #64748b;
        text-decoration: none;
        font-size: .9rem;
        transition: background .13s, color .13s;
        cursor: pointer;
    }
    .tb-icon-btn:hover { background: #f8fafc; color: #1e293b; }

    .tb-divider {
        width: 1px; height: 20px;
        background: #e5e7eb;
        margin: 0 2px;
    }

    .tb-admin-pill {
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 4px 10px;
        border-radius: 8px;
        text-decoration: none;
        transition: background .13s;
        cursor: default;
    }
    .tb-admin-pill:hover { background: #f1f5f9; }
    .tb-pill-avatar {
        width: 28px; height: 28px;
        background: var(--sb-accent);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: .72rem; font-weight: 700; color: #fff;
    }
    .tb-admin-name {
        font-size: .82rem;
        font-weight: 600;
        color: #374151;
    }

    /* ── Overlay (mobile) ───────────────────────────── */
    #sidebarOverlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.45);
        z-index: 1039;
    }

    /* ── Responsive ─────────────────────────────────── */
    @media (max-width: 991px) {
        body {
            padding-left: 0 !important;
        }
        #adminSidebar {
            transform: translateX(-100%);
        }
        #adminSidebar.open {
            transform: translateX(0);
        }
        #adminTopbar {
            left: 0 !important;
        }
        #sidebarToggleBtn {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #sidebarOverlay.open {
            display: block;
        }
        .tb-breadcrumb { display: none; }
    }
</style>

@if(session()->has('admin_id'))

{{-- ── Sidebar ──────────────────────────────────────────── --}}
<aside id="adminSidebar">

    <a href="{{ route('admin_dashboard') }}" class="sb-logo">
        <div class="sb-logo-icon">🛍</div>
        <div>
            <div class="sb-logo-name">ShopAdmin</div>
            <div class="sb-logo-sub">Control Panel</div>
        </div>
    </a>

    <nav class="sb-nav">
        @foreach($navGroups as $group)
            <div class="sb-group-label">{{ $group['label'] }}</div>
            @foreach($group['items'] as $item)
                @if(Route::has($item['route']))
                    <a href="{{ route($item['route']) }}"
                       class="sb-link {{ $currentRoute === $item['route'] ? 'active' : '' }}">
                        <span class="sb-icon">{{ $item['icon'] }}</span>
                        {{ $item['label'] }}
                    </a>
                @endif
            @endforeach
        @endforeach
    </nav>

    <div class="sb-footer">
        <div class="sb-user">
            <div class="sb-avatar">A</div>
            <div>
                <div class="sb-uname">Administrator</div>
                <div class="sb-urole">Super Admin</div>
            </div>
        </div>
        <a href="{{ route('admin_logout') }}" class="sb-logout">
            ↩ Sign Out
        </a>
    </div>

</aside>

{{-- ── Mobile overlay ───────────────────────────────────── --}}
<div id="sidebarOverlay" onclick="adminCloseSidebar()"></div>

{{-- ── Topbar ───────────────────────────────────────────── --}}
<header id="adminTopbar">

    <div class="tb-left">
        <button id="sidebarToggleBtn" onclick="adminToggleSidebar()" aria-label="Toggle menu">
            ☰
        </button>
        <div class="tb-breadcrumb">
            <span>Admin</span>
            <span style="color:#cbd5e1;">/</span>
            @php
                $activeItem = collect($navGroups)
                    ->flatMap(fn($g) => $g['items'])
                    ->firstWhere('route', $currentRoute);
                $pageLabel = $activeItem
                    ? $activeItem['label']
                    : Str::title(str_replace(['.','_','-'], ' ', $currentRoute ?? 'Dashboard'));
            @endphp
            <span class="tb-page">{{ $pageLabel }}</span>
        </div>
    </div>

    @php
        $admin = \App\Models\User::find(session('admin_id'));
    @endphp

    <div class="tb-right">
        <a href="{{ route('home') }}" target="_blank" class="tb-icon-btn" title="View Store">
            ↗
        </a>

        <a href="{{ route('admin.orders') }}" class="tb-icon-btn" title="Orders">
            🛒
        </a>

        <a href="{{ route('admin.coupons.index') }}" class="tb-icon-btn" title="Coupons">
            🎟
        </a>

        {{-- 🔐 2FA BUTTON --}}
        @if($admin && $admin->google2fa_enabled)

            {{-- Disable Button --}}
            <a href="{{ route('admin.2fa.disable.page') }}" 
            class="tb-icon-btn text-danger" 
            title="Disable 2FA">
                🔓
            </a>

        @else

            {{-- Enable Button --}}
            <a href="{{ route('admin.2fa.setup') }}" 
            class="tb-icon-btn text-success" 
            title="Enable 2FA">
                🔐
            </a>

        @endif

        <div class="tb-divider"></div>

        <div class="tb-admin-pill">
            <div class="tb-pill-avatar">A</div>
            <span class="tb-admin-name d-none d-md-inline">Admin</span>
        </div>
    </div>

</header>

<script>
    function adminToggleSidebar() {
        document.getElementById('adminSidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('open');
    }
    function adminCloseSidebar() {
        document.getElementById('adminSidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('open');
    }
</script>

@endif