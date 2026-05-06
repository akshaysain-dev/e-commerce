<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Vendor Registration</title>

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
            padding:40px 15px;
        }

        .register-wrapper{
            width:100%;
            max-width:950px;
        }

        .register-card{
            border:none;
            border-radius:20px;
            overflow:hidden;
            box-shadow:0 10px 30px rgba(0,0,0,0.08);
            background:#fff;
        }

        .register-header{
            background:#212529;
            color:#fff;
            padding:30px;
        }

        .register-header h2{
            font-weight:700;
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

        textarea.form-control{
            height:auto;
        }

        .btn-register{
            height:50px;
            border-radius:10px;
            font-size:17px;
            font-weight:600;
        }

        .login-link a{
            text-decoration:none;
        }

        .login-link a:hover{
            text-decoration:underline;
        }

    </style>

</head>

<body>

<div class="register-wrapper">

    <div class="card register-card">

        <!-- Header -->

        <div class="register-header">

            <h2 class="mb-2">
                Vendor Registration
            </h2>

            <p class="mb-0">
                Register your shop and start selling products.
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

            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <!-- Form -->

            <form action="{{ route('vendor.register.submit') }}"
                  method="POST">

                @csrf

                <div class="row">

                    <!-- Name -->

                    <div class="col-md-6 mb-4">

                        <label class="form-label">

                            Full Name
                            <span class="required">*</span>

                        </label>

                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name') }}"
                               placeholder="Enter full name"
                               required>

                    </div>

                    <!-- Email -->

                    <div class="col-md-6 mb-4">

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

                    <div class="col-md-6 mb-4">

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

                    <!-- Confirm Password -->

                    <div class="col-md-6 mb-4">

                        <label class="form-label">

                            Confirm Password
                            <span class="required">*</span>

                        </label>

                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               placeholder="Confirm password"
                               required>

                    </div>

                    <!-- Shop Name -->

                    <div class="col-md-6 mb-4">

                        <label class="form-label">

                            Shop Name
                            <span class="required">*</span>

                        </label>

                        <input type="text"
                               name="shop_name"
                               class="form-control"
                               value="{{ old('shop_name') }}"
                               placeholder="Enter shop name"
                               required>

                    </div>

                    <!-- Phone -->

                    <div class="col-md-6 mb-4">

                        <label class="form-label">

                            Phone Number
                            <span class="required">*</span>

                        </label>

                        <input type="text"
                               name="phone"
                               class="form-control"
                               value="{{ old('phone') }}"
                               placeholder="Enter phone number"
                               required>

                    </div>

                    <!-- Address -->

                    <div class="col-12 mb-4">

                        <label class="form-label">

                            Address
                            <span class="required">*</span>

                        </label>

                        <textarea name="address"
                                  class="form-control"
                                  rows="4"
                                  placeholder="Enter full address"
                                  required>{{ old('address') }}</textarea>

                    </div>

                    <!-- GST -->

                    <div class="col-md-6 mb-4">

                        <label class="form-label">

                            GST Number

                        </label>

                        <input type="text"
                               name="gst_number"
                               class="form-control"
                               value="{{ old('gst_number') }}"
                               placeholder="Enter GST number">

                    </div>

                    <!-- PAN -->

                    <div class="col-md-6 mb-4">

                        <label class="form-label">

                            PAN Number

                        </label>

                        <input type="text"
                               name="pan_number"
                               class="form-control"
                               value="{{ old('pan_number') }}"
                               placeholder="Enter PAN number">

                    </div>

                </div>

                <!-- Submit -->

                <button type="submit"
                        class="btn btn-dark w-100 btn-register">

                    <i class="fa fa-user-plus me-2"></i>

                    Register Vendor

                </button>

            </form>

            <!-- Login -->

            <div class="text-center mt-4 login-link">

                Already have an account?

                <a href="{{ route('vendor.login') }}"
                   class="fw-bold">

                    Login Here

                </a>

            </div>

        </div>

    </div>

</div>

</body>

</html>