@extends('Layouts.student')

@section('content')
<style>
    /* 1. Global Reset & Modern Font */
    body { 
        background-color: #f4f7fe; 
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #2d3748;
    }

    /* 2. Soft UI Card Design */
    .auth-card {
        border: none;
        border-radius: 1.5rem;
        background: #ffffff;
        box-shadow: 0 20px 27px 0 rgba(0, 0, 0, 0.05);
        padding: 2.5rem !important;
    }

    /* 3. Refined Typography */
    .auth-title {
        font-weight: 800;
        letter-spacing: -0.025em;
        color: #1a202c;
    }

    .label-custom {
        font-size: 0.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        margin-left: 0.25rem;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* 4. Sleek Inputs */
    .form-control-custom {
        display: block;
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #d2d6da;
        border-radius: 0.75rem;
        transition: all 0.2s ease;
    }

    .form-control-custom:focus {
        border-color: #825ee4;
        outline: 0;
        box-shadow: 0 0 0 2px rgba(130, 94, 228, 0.2);
    }

    /* 5. Button Aesthetic */
    .btn-indigo {
        background: linear-gradient(310deg, #7928ca 0%, #ff0080 100%); /* Optional vibrant gradient */
        /* background: #6610f2;  Alternative solid color */
        background: linear-gradient(135deg, #6610f2 0%, #450af5 100%);
        color: #fff;
        border: none;
        border-radius: 0.75rem;
        letter-spacing: -0.025em;
        transition: all 0.3s ease;
    }

    .btn-indigo:hover {
        box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
        transform: translateY(-1px);
        color: #fff;
    }

    /* 6. Custom Divider */
    .divider {
        height: 1px;
        background: #e9ecef;
        margin: 2rem 0;
        position: relative;
    }

    .divider-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #fff;
        padding: 0 1rem;
        color: #adb5bd;
        font-size: 0.8rem;
    }
</style>

<div class="container py-5 mt-lg-5">
    <div class="row justify-content-center">
        <div class="col-xl-4 col-lg-5 col-md-7">
            <div class="card auth-card">
                
                <div class="text-center mb-4">
                    <h1 class="auth-title h2">Create Account</h1>
                    <p class="text-secondary opacity-7">Enter your details to register</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-light border-0 shadow-sm mb-4 py-3 rounded-4">
                        <ul class="mb-0 list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li class="text-danger small d-flex align-items-center mb-1">
                                    <span class="me-2">●</span> {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    

                    <div class="mb-3">
                        <label class="label-custom">Username</label>
                        <input type="text" name="username" value="{{ old('username') }}" required 
                            placeholder="e.g. johndoe" class="form-control-custom">
                        @error('username')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="label-custom">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                            placeholder="john@example.com" class="form-control-custom">
                    </div>

                    <div class="mb-3">
                        <label class="label-custom">Password</label>
                        <input type="password" name="password" required 
                            placeholder="Min. 8 characters" class="form-control-custom">
                    </div>

                    <div class="mb-4">
                        <label class="label-custom">Confirm Password</label>
                        <input type="password" name="password_confirmation" required 
                            placeholder="Repeat password" class="form-control-custom">
                    </div>

                    <button type="submit" class="btn btn-indigo w-100 py-3 fw-bold">
                        Create My Account
                    </button>
                </form>

                <div class="divider">
                    <span class="divider-text text-uppercase fw-bold">or</span>
                </div>

                <div class="text-center">
                    <p class="text-sm text-secondary mb-0">
                        Already a member? 
                        <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">
                            Sign In
                        </a>
                    </p>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <p class="small text-secondary">© 2026 Your Brand. Secure Registration.</p>
            </div>
        </div>
    </div>
</div>
@endsection