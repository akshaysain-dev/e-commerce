@extends('layouts.frontend')

@section('title', 'Customer Login')
@section('styles')
<style>
    .login-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        transition: transform 0.3s ease;
    }
    .glass-card:hover {
        transform: translateY(-5px);
    }
    .btn-gradient {
        background: linear-gradient(to right, #6a11cb, #2575fc);
        color: white;
        border: none;
        transition: opacity 0.3s;
    }
    .btn-gradient:hover {
        opacity: 0.9;
        color: white;
    }


   /* LOGIN BUTTON */
.btn-login {
    background: linear-gradient(45deg, #ff416c, #ff4b2b);
    color: #fff;
    border: none;
    font-size: 16px;
    letter-spacing: 1px;
    transition: all 0.3s ease;
}

.btn-login:hover {
    background: linear-gradient(45deg, #ff4b2b, #ff416c);
    transform: translateY(-1px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}

/* GOOGLE BUTTON */
.btn-google {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background: #fff;
    color: #444;
    border: 1px solid #ddd;
    font-size: 15px;
    text-decoration: none;
    margin-top: 12px;
    transition: all 0.3s ease;
}

.btn-google img {
    width: 20px;
    height: 20px;
}

.btn-google:hover {
    background: #f8f9fa;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    transform: translateY(-1px);
}



</style>
@endsection

@section('content')

<div class="login-container">
    <div class="container">
       {{-- Apne existing alerts ki jagah yeh lagao --}}

        @if(session('success') || session('error') || $errors->any())

        <!-- Modal -->
        <div class="modal fade" id="alertModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 text-center p-4">

            @if(session('success'))
                <div class="d-flex justify-content-center mb-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                    style="width:64px;height:64px;background:#d1fae5;">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>
                </div>
                <h5 class="fw-semibold mb-1">Success!</h5>
                <p class="text-muted small mb-4">{{ session('success') }}</p>
                <button type="button" class="btn w-100 text-white fw-medium py-2"
                        style="background:#16a34a;border-radius:8px;"
                        data-bs-dismiss="modal">OK, Got it</button>

            @elseif(session('error'))
                <div class="d-flex justify-content-center mb-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                    style="width:64px;height:64px;background:#fee2e2;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5" stroke-linecap="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </div>
                </div>
                <h5 class="fw-semibold mb-1">Error!</h5>
                <p class="text-muted small mb-4">{{ session('error') }}</p>
                <button type="button" class="btn w-100 text-white fw-medium py-2"
                        style="background:#dc2626;border-radius:8px;"
                        data-bs-dismiss="modal">Close</button>

            @elseif($errors->any())
                <div class="d-flex justify-content-center mb-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                    style="width:64px;height:64px;background:#fef3c7;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2.5" stroke-linecap="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                </div>
                <h5 class="fw-semibold mb-1">Validation Error!</h5>
                <ul class="text-start text-muted small mb-4 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
                <button type="button" class="btn w-100 text-white fw-medium py-2"
                        style="background:#d97706;border-radius:8px;"
                        data-bs-dismiss="modal">OK</button>
            @endif

            </div>
        </div>
        </div>

        @endif


        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card glass-card p-4 animate__animated animate__fadeInUp">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold">Welcome Back</h3>
                        <p class="text-muted small">Please enter your details to sign in</p>
                    </div>
                    @if ($errors->has('email'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Login Failed!</strong> {{ $errors->first('email') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form action="{{ route('login_customer') }}" method="POST">
                        @csrf
                        <!-- Floating Label Email -->
                        <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control border-0 bg-light" id="floatingInput" placeholder="name@example.com" value="{{old('email')}}" required>
                            <label for="floatingInput">Email address</label>
                        </div>

                        <!-- Floating Label Password -->
                        <div class="form-floating mb-3">
                            <input type="password" name="password" class="form-control border-0 bg-light" id="floatingPassword" placeholder="Password" required>
                            <label for="floatingPassword">Password</label>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember">
                                <label class="form-check-label small" for="remember">Remember me</label>
                            </div>
                           <a href="#" class="text-decoration-none small text-primary" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                                Forgot Password?
                            </a>
                        </div>

                        <button type="submit" class="btn-login w-100 py-3 fw-bold rounded-3">
                            LOGIN
                        </button>

                        <a href="{{ route('google.login') }}" class="btn-google w-100 py-3 fw-bold rounded-3">
                            <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google">
                            <span>Continue with Google</span>
                        </a>
                        <div class="text-center mt-4">
                            <p class="small mb-0">Don't have an account? <a href="{{ route('customer_register') }}" class="fw-bold text-decoration-none">Sign Up</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Forgot Password Modal --}}
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 p-4">

      {{-- Icon --}}
      <div class="d-flex justify-content-center mb-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center"
             style="width:64px;height:64px;background:#ede9fe;">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="4" width="20" height="16" rx="2"/>
            <path d="M22 7l-10 7L2 7"/>
          </svg>
        </div>
      </div>

      {{-- Title --}}
      <h5 class="fw-semibold text-center mb-1">Forgot Password?</h5>
      <p class="text-muted small text-center mb-4">Enter your email and we'll send you a reset link.</p>

      {{-- Form --}}
      <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <div class="mb-3">
          <label for="forgot_email" class="form-label small fw-medium">Email Address</label>
          <input type="email" name="email" id="forgot_email"
                 class="form-control"
                 placeholder="you@example.com" required />
        </div>
        <button type="submit" class="btn w-100 text-white fw-medium py-2"
                style="background:#7c3aed;border-radius:8px;">
          Send Reset Link
        </button>
      </form>

      {{-- Cancel --}}
      <button type="button" class="btn btn-link text-muted small mt-2 w-100"
              data-bs-dismiss="modal">Cancel</button>

    </div>
  </div>
</div>
@endsection

@push('scripts')

<!-- Auto open -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    new bootstrap.Modal(document.getElementById('alertModal')).show();
  });
</script>

@endpush