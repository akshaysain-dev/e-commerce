@extends('vendor.layouts.app')

@section('title', 'Vendor Profile')

@push('styles')
<style>
    :root {
        --ink:        #0e0e12;
        --ink-muted:  #6b6b7a;
        --surface:    #ffffff;
        --surface-2:  #f5f4f1;
        --surface-3:  #eeede9;
        --accent:     #5b5ef4;
        --accent-2:   #e8e8fd;
        --accent-dk:  #4749d6;
        --success:    #1a9e75;
        --success-bg: #e2f5ee;
        --border:     rgba(14,14,18,.08);
        --border-md:  rgba(14,14,18,.13);
        --radius-sm:  10px;
        --radius-md:  16px;
        --radius-lg:  24px;
        --shadow-sm:  0 1px 3px rgba(14,14,18,.06), 0 1px 2px rgba(14,14,18,.04);
        --shadow-md:  0 4px 16px rgba(14,14,18,.08), 0 1px 4px rgba(14,14,18,.04);
        --ff-display: 'Syne', sans-serif;
        --ff-body:    'DM Sans', sans-serif;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: var(--ff-body);
        background: var(--surface-2);
        color: var(--ink);
        -webkit-font-smoothing: antialiased;
    }

    /* ── Page wrapper ── */
    .vp-wrap {
        padding: 2rem 1.75rem 3rem;
        width: 100%;
        min-width: 0;
        overflow-x: hidden;
    }

    /* ── Page header ── */
    .vp-header {
        margin-bottom: 1.75rem;
    }
    .vp-eyebrow {
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: .35rem;
    }
    .vp-title {
        font-family: var(--ff-display);
        font-size: clamp(1.5rem, 2.5vw, 2rem);
        font-weight: 700;
        color: var(--ink);
        line-height: 1.2;
        margin-bottom: .35rem;
    }
    .vp-subtitle {
        font-size: .875rem;
        color: var(--ink-muted);
    }

    /* ── Alert ── */
    .vp-alert {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .9rem 1.1rem;
        border-radius: var(--radius-sm);
        background: var(--success-bg);
        border: 1px solid rgba(26,158,117,.2);
        color: var(--success);
        font-size: .875rem;
        font-weight: 500;
        margin-bottom: 1.5rem;
    }
    .vp-alert i { font-size: 1rem; flex-shrink: 0; }

    /* ── Main grid ── */
    .vp-grid {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 1.25rem;
        align-items: start;
    }

    /* ── Left panel — logo + identity ── */
    .vp-identity {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        position: sticky;
        top: 1.5rem;
    }

    .vp-logo-card {
        background: var(--surface);
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        padding: 2rem 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        text-align: center;
    }
    .vp-avatar-wrap {
        position: relative;
        width: 96px;
        height: 96px;
    }
    .vp-avatar {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--accent-2);
    }
    .vp-avatar-placeholder {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent), #7c7ff7);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--ff-display);
        font-size: 2rem;
        font-weight: 700;
        color: #fff;
        border: 3px solid var(--accent-2);
    }
    .vp-avatar-badge {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--accent);
        border: 2px solid var(--surface);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: .6rem;
    }
    .vp-shop-name {
        font-family: var(--ff-display);
        font-size: 1rem;
        font-weight: 700;
        color: var(--ink);
        line-height: 1.3;
    }
    .vp-shop-role {
        font-size: .75rem;
        color: var(--ink-muted);
        font-weight: 400;
    }

    /* upload zone */
    .vp-upload-zone {
        width: 100%;
        border: 1.5px dashed var(--border-md);
        border-radius: var(--radius-sm);
        padding: .85rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: border-color .2s, background .2s;
        background: var(--surface-2);
    }
    .vp-upload-zone:hover {
        border-color: var(--accent);
        background: var(--accent-2);
    }
    .vp-upload-zone input[type="file"] { display: none; }
    .vp-upload-label {
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .4rem;
    }
    .vp-upload-icon {
        width: 36px; height: 36px;
        border-radius: 8px;
        background: var(--accent-2);
        color: var(--accent);
        display: flex; align-items: center; justify-content: center;
        font-size: .9rem;
        margin: 0 auto;
    }
    .vp-upload-text {
        font-size: .75rem;
        color: var(--ink-muted);
        line-height: 1.5;
    }
    .vp-upload-text strong { color: var(--accent); font-weight: 600; }

    /* quick info strips */
    .vp-info-strip {
        background: var(--surface);
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .vp-info-strip-item {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .85rem 1.1rem;
        border-bottom: 1px solid var(--border);
    }
    .vp-info-strip-item:last-child { border-bottom: none; }
    .vp-info-strip-icon {
        width: 30px; height: 30px;
        border-radius: 8px;
        background: var(--accent-2);
        color: var(--accent);
        display: flex; align-items: center; justify-content: center;
        font-size: .8rem;
        flex-shrink: 0;
    }
    .vp-info-strip-key {
        font-size: .68rem;
        color: var(--ink-muted);
        text-transform: uppercase;
        letter-spacing: .05em;
        font-weight: 500;
        margin-bottom: .1rem;
    }
    .vp-info-strip-val {
        font-size: .82rem;
        font-weight: 500;
        color: var(--ink);
        word-break: break-all;
    }

    /* ── Right panel — form ── */
    .vp-form-panel {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .vp-section {
        background: var(--surface);
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .vp-section-head {
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: .75rem;
    }
    .vp-section-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        background: var(--accent-2);
        color: var(--accent);
        display: flex; align-items: center; justify-content: center;
        font-size: .85rem;
        flex-shrink: 0;
    }
    .vp-section-title {
        font-family: var(--ff-display);
        font-size: .95rem;
        font-weight: 600;
        color: var(--ink);
    }
    .vp-section-body {
        padding: 1.5rem;
    }

    /* ── Form fields ── */
    .vp-fields {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .vp-fields-full { grid-column: 1 / -1; }

    .vp-field {
        display: flex;
        flex-direction: column;
        gap: .4rem;
    }
    .vp-label {
        font-size: .75rem;
        font-weight: 600;
        letter-spacing: .04em;
        text-transform: uppercase;
        color: var(--ink-muted);
    }
    .vp-input,
    .vp-textarea {
        width: 100%;
        padding: .7rem 1rem;
        border: 1.5px solid var(--border-md);
        border-radius: var(--radius-sm);
        font-family: var(--ff-body);
        font-size: .875rem;
        color: var(--ink);
        background: var(--surface);
        transition: border-color .2s, box-shadow .2s;
        outline: none;
        -webkit-appearance: none;
    }
    .vp-input:focus,
    .vp-textarea:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(91,94,244,.12);
    }
    .vp-input::placeholder,
    .vp-textarea::placeholder {
        color: rgba(14,14,18,.3);
    }
    .vp-textarea {
        resize: vertical;
        min-height: 100px;
    }

    /* ── Submit bar ── */
    .vp-submit-bar {
        background: var(--surface);
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        padding: 1.1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .vp-submit-note {
        font-size: .8rem;
        color: var(--ink-muted);
        display: flex;
        align-items: center;
        gap: .4rem;
    }
    .vp-submit-note i { color: var(--accent); }
    .vp-btn {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .75rem 2rem;
        border-radius: 999px;
        border: none;
        font-family: var(--ff-body);
        font-size: .9rem;
        font-weight: 600;
        cursor: pointer;
        transition: transform .15s, box-shadow .15s, background .15s;
        background: var(--accent);
        color: #fff;
        white-space: nowrap;
    }
    .vp-btn:hover {
        background: var(--accent-dk);
        transform: translateY(-1px);
        box-shadow: 0 6px 24px rgba(91,94,244,.35);
    }
    .vp-btn:active { transform: scale(.98); }
    .vp-btn i { font-size: .85rem; }

    /* ── Responsive ── */
    @media (max-width: 991px) {
        .vp-wrap { padding: 1.5rem 1.25rem 2.5rem; }
        .vp-grid { grid-template-columns: 1fr; }
        .vp-identity { position: static; }
        .vp-logo-card { flex-direction: row; text-align: left; padding: 1.25rem; }
        .vp-logo-card > div:last-child { text-align: left; }
    }

    @media (max-width: 575px) {
        .vp-wrap { padding: 1rem 1rem 1.5rem; }
        .vp-fields { grid-template-columns: 1fr; }
        .vp-fields-full { grid-column: 1; }
        .vp-logo-card { flex-direction: column; text-align: center; }
        .vp-section-body { padding: 1.1rem; }
        .vp-submit-bar { flex-direction: column; align-items: flex-start; }
        .vp-btn { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="vp-wrap">

    {{-- Header --}}
    <div class="vp-header">
        <p class="vp-eyebrow">Account Settings</p>
        <h1 class="vp-title">Vendor Profile</h1>
        <p class="vp-subtitle">Manage your personal, business, and banking details</p>
    </div>

    @if(session('success'))
        <div class="vp-alert">
            <i class="fa fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('vendor.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="vp-grid">

            {{-- ── Left: identity card ── --}}
            <div class="vp-identity">

                <div class="vp-logo-card">
                    <div class="vp-avatar-wrap">
                        @if($vendor->logo)
                            <img src="{{ asset('storage/'.$vendor->logo) }}"
                                 class="vp-avatar"
                                 alt="Shop logo">
                        @else
                            <div class="vp-avatar-placeholder">
                                {{ strtoupper(substr($vendor->shop_name ?? 'V', 0, 1)) }}
                            </div>
                        @endif
                        <div class="vp-avatar-badge"><i class="fa fa-store"></i></div>
                    </div>
                    <div>
                        <div class="vp-shop-name">{{ $vendor->shop_name ?? 'Your Shop' }}</div>
                        <div class="vp-shop-role">Vendor Account</div>
                    </div>
                </div>

                {{-- Logo upload --}}
                <div class="vp-upload-zone" onclick="document.getElementById('logo-input').click()">
                    <label class="vp-upload-label">
                        <div class="vp-upload-icon"><i class="fa fa-cloud-upload-alt"></i></div>
                        <div class="vp-upload-text">
                            <strong>Click to upload</strong> shop logo<br>
                            PNG, JPG up to 2MB
                        </div>
                    </label>
                    <input type="file" name="logo" id="logo-input" accept="image/*">
                </div>

                {{-- Quick info strip --}}
                <div class="vp-info-strip">
                    <div class="vp-info-strip-item">
                        <div class="vp-info-strip-icon"><i class="fa fa-user"></i></div>
                        <div>
                            <div class="vp-info-strip-key">Name</div>
                            <div class="vp-info-strip-val">{{ $user->name }}</div>
                        </div>
                    </div>
                    <div class="vp-info-strip-item">
                        <div class="vp-info-strip-icon"><i class="fa fa-envelope"></i></div>
                        <div>
                            <div class="vp-info-strip-key">Email</div>
                            <div class="vp-info-strip-val">{{ $user->email }}</div>
                        </div>
                    </div>
                    <div class="vp-info-strip-item">
                        <div class="vp-info-strip-icon"><i class="fa fa-phone"></i></div>
                        <div>
                            <div class="vp-info-strip-key">Phone</div>
                            <div class="vp-info-strip-val">{{ $vendor->phone ?? '—' }}</div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── Right: form sections ── --}}
            <div class="vp-form-panel">

                {{-- Personal details --}}
                <div class="vp-section">
                    <div class="vp-section-head">
                        <div class="vp-section-icon"><i class="fa fa-user"></i></div>
                        <span class="vp-section-title">Personal Details</span>
                    </div>
                    <div class="vp-section-body">
                        <div class="vp-fields">
                            <div class="vp-field">
                                <label class="vp-label">Full Name</label>
                                <input type="text" name="name" class="vp-input"
                                       value="{{ old('name', $user->name) }}"
                                       placeholder="Your full name">
                            </div>
                            <div class="vp-field">
                                <label class="vp-label">Email Address</label>
                                <input type="email" name="email" class="vp-input"
                                       value="{{ old('email', $user->email) }}"
                                       placeholder="you@example.com">
                            </div>
                            <div class="vp-field">
                                <label class="vp-label">New Password</label>
                                <input type="password" name="password" class="vp-input"
                                       placeholder="Leave blank to keep current">
                            </div>
                            <div class="vp-field">
                                <label class="vp-label">Phone Number</label>
                                <input type="text" name="phone" class="vp-input"
                                       value="{{ old('phone', $vendor->phone) }}"
                                       placeholder="+91 00000 00000">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Business details --}}
                <div class="vp-section">
                    <div class="vp-section-head">
                        <div class="vp-section-icon"><i class="fa fa-store"></i></div>
                        <span class="vp-section-title">Business Details</span>
                    </div>
                    <div class="vp-section-body">
                        <div class="vp-fields">
                            <div class="vp-field">
                                <label class="vp-label">Shop Name</label>
                                <input type="text" name="shop_name" class="vp-input"
                                       value="{{ old('shop_name', $vendor->shop_name) }}"
                                       placeholder="Your shop name">
                            </div>
                            <div class="vp-field">
                                <label class="vp-label">GST Number</label>
                                <input type="text" name="gst_number" class="vp-input"
                                       value="{{ old('gst_number', $vendor->gst_number) }}"
                                       placeholder="22AAAAA0000A1Z5">
                            </div>
                            <div class="vp-field">
                                <label class="vp-label">PAN Number</label>
                                <input type="text" name="pan_number" class="vp-input"
                                       value="{{ old('pan_number', $vendor->pan_number) }}"
                                       placeholder="ABCDE1234F">
                            </div>
                            <div class="vp-field vp-fields-full">
                                <label class="vp-label">Business Address</label>
                                <textarea name="address" class="vp-textarea"
                                          placeholder="Full business address">{{ old('address', $vendor->address) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Banking details --}}
                <div class="vp-section">
                    <div class="vp-section-head">
                        <div class="vp-section-icon"><i class="fa fa-university"></i></div>
                        <span class="vp-section-title">Banking Details</span>
                    </div>
                    <div class="vp-section-body">
                        <div class="vp-fields">
                            <div class="vp-field">
                                <label class="vp-label">Bank Name</label>
                                <input type="text" name="bank_name" class="vp-input"
                                       value="{{ old('bank_name', $vendor->bank_name) }}"
                                       placeholder="State Bank of India">
                            </div>
                            <div class="vp-field">
                                <label class="vp-label">Account Number</label>
                                <input type="text" name="account_number" class="vp-input"
                                       value="{{ old('account_number', $vendor->account_number) }}"
                                       placeholder="XXXX XXXX XXXX">
                            </div>
                            <div class="vp-field">
                                <label class="vp-label">IFSC Code</label>
                                <input type="text" name="ifsc_code" class="vp-input"
                                       value="{{ old('ifsc_code', $vendor->ifsc_code) }}"
                                       placeholder="SBIN0001234">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit bar --}}
                <div class="vp-submit-bar">
                    <span class="vp-submit-note">
                        <i class="fa fa-shield-alt"></i>
                        Your data is encrypted and stored securely.
                    </span>
                    <button type="submit" class="vp-btn">
                        <i class="fa fa-save"></i>
                        Save Changes
                    </button>
                </div>

            </div>{{-- end form panel --}}

        </div>{{-- end grid --}}

    </form>

</div>
@endsection