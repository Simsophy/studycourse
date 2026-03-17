@extends('layouts.student')

@section('title', 'Reset Password')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 75vh;">
    <div class="card border-0 shadow-lg rounded-4" style="max-width: 400px; width: 100%;">
        <div class="card-body p-4 p-md-5">
            
            <div class="text-center mb-4">
                <div class="bg-warning bg-opacity-10 d-inline-block p-3 rounded-circle mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#ffc107" class="bi bi-shield-lock-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 0c-.69 0-1.843.245-3.128.934-1.327.71-2.737 1.82-3.035 3.303a.25.25 0 0 0 .068.233l.534.534a.25.25 0 0 0 .33 0l.534-.534a.25.25 0 0 0 .067-.233c.257-1.273 1.482-2.257 2.707-2.912C6.234 1.05 7.41 1 8 1s1.766.05 2.988.707c1.225.655 2.45 1.639 2.707 2.912a.25.25 0 0 0 .067.233l.534.534a.25.25 0 0 0 .33 0l.534-.534a.25.25 0 0 0 .068-.233c-.298-1.483-1.708-2.592-3.035-3.303C9.843.245 8.69 0 8 0m0 7a3 3 0 1 0 0 6 3 3 0 0 0 0-6m-5 0h10v1.5a.5.5 0 0 1-1 0V8H4v.5a.5.5 0 0 1-1 0z"/>
                    </svg>
                </div>
                <h2 class="fw-bold h4">Forgot Password?</h2>
                <p class="text-muted small">No worries! Enter your email and we'll send you a reset link.</p>
            </div>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                
                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-lg border-0 bg-light shadow-sm" placeholder="your@email.com" required autofocus>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-3 py-3 shadow-sm transition-up">
                        Send Reset Link
                    </button>
                </div>
            </form>

            <div class="text-center mt-4">
                <p class="mb-0 text-muted small">
                    Remember your password? 
                    <a href="{{ route('admin.login.submit') }}" class="text-primary fw-bold text-decoration-none">Back to Login</a>
                </p>
            </div>

        </div>
    </div>
</div>

<style>
    /* Styling for a "Beauty" focus */
    .form-control:focus {
        background-color: #ffffff !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1) !important;
        border: 1px solid #0d6efd !important;
    }
    .transition-up {
        transition: transform 0.2s ease;
    }
    .transition-up:hover {
        transform: translateY(-2px);
    }
</style>
@endsection