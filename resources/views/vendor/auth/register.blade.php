<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #0d0d0f;
            --surface: #141416;
            --surface2: #1c1c20;
            --border: rgba(255,255,255,0.07);
            --border-hover: rgba(255,255,255,0.15);
            --accent: #c8f75e;
            --accent-dim: rgba(200,247,94,0.12);
            --accent-glow: rgba(200,247,94,0.25);
            --text: #f0f0f0;
            --muted: #888;
            --error: #ff6b6b;
            --radius: 14px;
            --radius-sm: 10px;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient blobs */
        body::before {
            content: '';
            position: fixed;
            top: -200px;
            right: -200px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(200,247,94,0.06) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -200px;
            left: -200px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(120,80,255,0.07) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .page-wrapper {
            width: 100%;
            max-width: 980px;
            position: relative;
            z-index: 1;
        }

        /* Top label */
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--accent-dim);
            border: 1px solid rgba(200,247,94,0.2);
            color: var(--accent);
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 6px 14px;
            border-radius: 100px;
            margin-bottom: 24px;
        }
        .eyebrow i { font-size: 11px; }

        /* Main heading */
        .page-title {
            font-family: 'Syne', sans-serif;
            font-size: clamp(36px, 5vw, 58px);
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: -0.02em;
            margin-bottom: 12px;
        }
        .page-title span {
            color: var(--accent);
        }
        .page-subtitle {
            color: var(--muted);
            font-size: 16px;
            font-weight: 300;
            margin-bottom: 48px;
            max-width: 420px;
            line-height: 1.6;
        }

        /* Layout: left info + right form */
        .layout {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 40px;
            align-items: start;
        }

        /* Left column */
        .left-col {}

        .steps-list {
            display: flex;
            flex-direction: column;
            gap: 0;
            margin-top: 48px;
        }
        .step-item {
            display: flex;
            gap: 18px;
            padding: 0 0 32px 0;
            position: relative;
        }
        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 17px;
            top: 38px;
            bottom: 0;
            width: 1px;
            background: var(--border);
        }
        .step-num {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--surface2);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: var(--accent);
            flex-shrink: 0;
        }
        .step-text strong {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 4px;
        }
        .step-text p {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.6;
        }

        /* Right: Card */
        .form-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            overflow: hidden;
            position: relative;
        }
        .form-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(200,247,94,0.4), transparent);
        }

        .form-inner {
            padding: 36px;
        }

        /* Section dividers */
        .section-label {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* Grid rows */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }
        .form-row.full {
            grid-template-columns: 1fr;
        }

        /* Field */
        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .field label {
            font-size: 13px;
            font-weight: 500;
            color: #ccc;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .field label i {
            font-size: 12px;
            color: var(--muted);
        }
        .required-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--accent);
            display: inline-block;
            margin-left: 2px;
        }

        .field input,
        .field textarea {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            padding: 13px 16px;
            outline: none;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
            width: 100%;
        }
        .field input::placeholder,
        .field textarea::placeholder {
            color: rgba(255,255,255,0.2);
        }
        .field input:hover,
        .field textarea:hover {
            border-color: var(--border-hover);
            background: rgba(255,255,255,0.04);
        }
        .field input:focus,
        .field textarea:focus {
            border-color: rgba(200,247,94,0.4);
            background: rgba(200,247,94,0.03);
            box-shadow: 0 0 0 3px rgba(200,247,94,0.08);
        }
        .field textarea {
            resize: vertical;
            min-height: 100px;
            line-height: 1.6;
        }

        /* Password wrapper */
        .pw-wrap {
            position: relative;
        }
        .pw-wrap input {
            padding-right: 44px;
        }
        .pw-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--muted);
            cursor: pointer;
            font-size: 14px;
            padding: 4px;
            transition: color 0.2s;
        }
        .pw-toggle:hover { color: var(--text); }

        /* Optional tag */
        .opt-tag {
            font-size: 11px;
            font-weight: 400;
            color: var(--muted);
            background: var(--surface2);
            padding: 2px 8px;
            border-radius: 100px;
            border: 1px solid var(--border);
            margin-left: auto;
        }

        /* Spacer */
        .spacer { height: 24px; }

        /* Submit btn */
        .btn-submit {
            width: 100%;
            padding: 16px;
            background: var(--accent);
            color: #0d0d0f;
            border: none;
            border-radius: var(--radius-sm);
            font-family: 'Syne', sans-serif;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.01em;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            margin-top: 28px;
        }
        .btn-submit:hover {
            background: #d8ff6e;
            box-shadow: 0 0 30px var(--accent-glow);
            transform: translateY(-1px);
        }
        .btn-submit:active {
            transform: translateY(0);
        }
        .btn-submit i { font-size: 16px; }

        /* Login link */
        .login-row {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: var(--muted);
        }
        .login-row a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.2s;
        }
        .login-row a:hover { opacity: 0.75; }

        /* Alerts */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            margin-bottom: 24px;
        }
        .alert-success {
            background: rgba(100,210,120,0.1);
            border: 1px solid rgba(100,210,120,0.25);
            color: #7ee8a2;
        }
        .alert-danger {
            background: rgba(255,107,107,0.1);
            border: 1px solid rgba(255,107,107,0.2);
            color: #ff9999;
        }
        .alert ul { padding-left: 18px; }

        /* Trust badges at bottom of left col */
        .trust-row {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 40px;
        }
        .trust-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: var(--muted);
        }
        .trust-item i {
            color: var(--accent);
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .layout { grid-template-columns: 1fr; }
            .steps-list, .trust-row { display: none; }
            .form-row { grid-template-columns: 1fr; }
            .form-inner { padding: 24px 20px; }
            .page-title { font-size: 32px; }
        }
    </style>
</head>
<body>

<div class="page-wrapper">

    <div class="eyebrow">
        <i class="fa fa-store"></i>
        Become a Seller
    </div>

    <h1 class="page-title">
        Start selling<br>
        <span>your products.</span>
    </h1>
    <p class="page-subtitle">
        Set up your vendor shop in minutes and reach thousands of customers today.
    </p>

    <div class="layout">

        <!-- Left -->
        <div class="left-col">
            <div class="steps-list">
                <div class="step-item">
                    <div class="step-num">01</div>
                    <div class="step-text">
                        <strong>Create your account</strong>
                        <p>Fill in your personal and business details to get started.</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num">02</div>
                    <div class="step-text">
                        <strong>Verify & Approval</strong>
                        <p>Our team reviews your application within 24 hours.</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num">03</div>
                    <div class="step-text">
                        <strong>List your products</strong>
                        <p>Upload your catalog and start selling immediately.</p>
                    </div>
                </div>
            </div>

            <div class="trust-row">
                <div class="trust-item">
                    <i class="fa fa-shield-halved"></i>
                    Secure & encrypted registration
                </div>
                <div class="trust-item">
                    <i class="fa fa-bolt"></i>
                    Get approved in under 24 hrs
                </div>
                <div class="trust-item">
                    <i class="fa fa-headset"></i>
                    Dedicated vendor support
                </div>
            </div>
        </div>

        <!-- Right: Form Card -->
        <div class="form-card">
            <div class="form-inner">

                <!-- Alerts (Laravel Blade) -->
                <!-- @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
                @endif -->

                <form action="{{ route('vendor.register.submit') }}" method="POST">
                    @csrf

                    <!-- Personal Info -->
                    <div class="section-label">Personal Info</div>

                    <div class="form-row">
                        <div class="field">
                            <label>
                                <i class="fa fa-user"></i>
                                Full Name
                                <span class="required-dot"></span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Arjun Sharma" required>
                        </div>
                        <div class="field">
                            <label>
                                <i class="fa fa-envelope"></i>
                                Email Address
                                <span class="required-dot"></span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="field">
                            <label>
                                <i class="fa fa-lock"></i>
                                Password
                                <span class="required-dot"></span>
                            </label>
                            <div class="pw-wrap">
                                <input type="password" name="password" id="pw1" placeholder="Min. 8 characters" required>
                                <button type="button" class="pw-toggle" onclick="togglePw('pw1', this)">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="field">
                            <label>
                                <i class="fa fa-lock"></i>
                                Confirm Password
                                <span class="required-dot"></span>
                            </label>
                            <div class="pw-wrap">
                                <input type="password" name="password_confirmation" id="pw2" placeholder="Repeat password" required>
                                <button type="button" class="pw-toggle" onclick="togglePw('pw2', this)">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="spacer"></div>

                    <!-- Shop Info -->
                    <div class="section-label">Shop Details</div>

                    <div class="form-row">
                        <div class="field">
                            <label>
                                <i class="fa fa-store"></i>
                                Shop Name
                                <span class="required-dot"></span>
                            </label>
                            <input type="text" name="shop_name" value="{{ old('shop_name') }}" placeholder="Your shop name" required>
                        </div>
                        <div class="field">
                            <label>
                                <i class="fa fa-phone"></i>
                                Phone Number
                                <span class="required-dot"></span>
                            </label>
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+91 98765 43210" required>
                        </div>
                    </div>

                    <div class="form-row full">
                        <div class="field">
                            <label>
                                <i class="fa fa-location-dot"></i>
                                Address
                                <span class="required-dot"></span>
                            </label>
                            <textarea name="address" placeholder="Street, City, State, PIN" required>{{ old('address') }}</textarea>
                        </div>
                    </div>

                    <div class="spacer"></div>

                    <!-- Tax Info -->
                    <div class="section-label">Tax Information</div>

                    <div class="form-row">
                        <div class="field">
                            <label>
                                <i class="fa fa-file-invoice"></i>
                                GST Number
                                <span class="opt-tag">Optional</span>
                            </label>
                            <input type="text" name="gst_number" value="{{ old('gst_number') }}" placeholder="22AAAAA0000A1Z5">
                        </div>
                        <div class="field">
                            <label>
                                <i class="fa fa-id-card"></i>
                                PAN Number
                                <span class="opt-tag">Optional</span>
                            </label>
                            <input type="text" name="pan_number" value="{{ old('pan_number') }}" placeholder="ABCDE1234F">
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn-submit">
                        <i class="fa fa-user-plus"></i>
                        Register as Vendor
                    </button>

                </form>

                <div class="login-row">
                    Already have an account?
                    <a href="{{ route('vendor.login') }}">Sign in here &rarr;</a>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
    function togglePw(id, btn) {
        const input = document.getElementById(id);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fa fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fa fa-eye';
        }
    }
</script>

</body>
</html>