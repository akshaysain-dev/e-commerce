<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Vendor Login</title>

    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Font Awesome -->

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>

        body{
            background:#f4f6f9;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:20px;
        }

        .login-wrapper{
            width:100%;
            max-width:500px;
        }

        .login-card{
            border:none;
            border-radius:20px;
            overflow:hidden;
            box-shadow:0 10px 30px rgba(0,0,0,0.08);
            background:#fff;
        }

        .login-header{
            background:#212529;
            color:#fff;
            padding:30px;
            text-align:center;
        }

        .login-header h2{
            font-weight:700;
            margin-bottom:10px;
        }

        .card-body{
            padding:35px;
        }

        .form-label{
            font-weight:600;
            margin-bottom:8px;
        }

        .required{
            color:red;
        }

        .form-control{
            height:50px;
            border-radius:10px;
        }

        .btn-login{
            height:50px;
            border-radius:10px;
            font-size:17px;
            font-weight:600;
        }

        .register-link a{
            text-decoration:none;
        }

        .register-link a:hover{
            text-decoration:underline;
        }

    </style>

</head>

<body>

<div class="login-wrapper">

    <div class="card login-card">

        <!-- Header -->

        <div class="login-header">

            <h2>
                Vendor Login
            </h2>

            <p class="mb-0">
                Login to access your vendor dashboard.
            </p>

        </div>

        <!-- Body -->

        <div class="card-body">

            {{-- Success Message --}}

            @if(session('success'))

                <div class="alert alert-success">

                    {{ session('success') }}

                </div>

            @endif

            {{-- Error Message --}}

            @if(session('error'))

                <div class="alert alert-danger">

                    {{ session('error') }}

                </div>

            @endif

            {{-- Validation Errors --}}

            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <!-- Login Form -->

            <form action="{{ route('vendor.login.submit') }}"
                  method="POST">

                @csrf

                <!-- Email -->

                <div class="mb-4">

                    <label class="form-label">

                        Email Address
                        <span class="required">*</span>

                    </label>

                    <input type="email"
                           name="email"
                           class="form-control"
                           value="{{ old('email') }}"
                           placeholder="Enter email address"
                           required>

                </div>

                <!-- Password -->

                <div class="mb-4">

                    <label class="form-label">

                        Password
                        <span class="required">*</span>

                    </label>

                    <input type="password"
                           name="password"
                           class="form-control"
                           placeholder="Enter password"
                           required>

                </div>

                <!-- Remember Me -->

                <div class="form-check mb-4">

                    <input class="form-check-input"
                           type="checkbox"
                           name="remember"
                           id="remember">

                    <label class="form-check-label"
                           for="remember">

                        Remember Me

                    </label>

                </div>

                <!-- Submit -->

                <button type="submit"
                        class="btn btn-dark w-100 btn-login">

                    <i class="fa fa-sign-in-alt me-2"></i>

                    Login

                </button>

            </form>

            <!-- Register -->

            <div class="text-center mt-4 register-link">

                Don't have a vendor account?

                <a href="{{ route('vendor.register') }}"
                   class="fw-bold">

                    Register Here

                </a>

            </div>

        </div>

    </div>

</div>

</body>

</html>