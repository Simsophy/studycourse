@extends('layouts.student')

@section('title', 'Reset Password')

@section('content')
<style>
    /* Modern gradient background */
    .auth-page-wrapper {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        position: relative;
        overflow: hidden;
    }

    /* Animated background elements */
    .auth-page-wrapper::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 50px 50px;
        animation: float 20s linear infinite;
    }

    @keyframes float {
        0% { transform: translate(0, 0) rotate(0deg); }
        100% { transform: translate(-50px, -50px) rotate(360deg); }
    }

    /* Glass morphism card */
    .auth-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 2rem;
        padding: 3rem;
        box-shadow:
            0 25px 50px -12px rgba(0, 0, 0, 0.25),
            0 0 0 1px rgba(255, 255, 255, 0.5) inset;
        position: relative;
        z-index: 1;
        max-width: 28rem;
        width: 100%;
        animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Icon container */
    .icon-wrapper {
        width: 5rem;
        height: 5rem;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.4);
    }

    .icon-wrapper svg {
        width: 2.5rem;
        height: 2.5rem;
        color: white;
    }

    /* Typography */
    .auth-title {
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: -0.025em;
        color: #1a202c;
        text-align: center;
        margin-bottom: 0.75rem;
    }

    .auth-subtitle {
        color: #718096;
        font-size: 0.95rem;
        text-align: center;
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    /* Form labels */
    .form-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #4a5568;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Password strength indicator */
    .password-strength {
        height: 4px;
        border-radius: 2px;
        margin-top: 0.5rem;
        background-color: #e2e8f0;
        overflow: hidden;
    }

    .password-strength-bar {
        height: 100%;
        width: 0;
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    .password-strength-bar.weak {
        width: 33%;
        background-color: #f56565;
    }

    .password-strength-bar.medium {
        width: 66%;
        background-color: #ed8936;
    }

    .password-strength-bar.strong {
        width: 100%;
        background-color: #48bb78;
    }

    /* Form inputs */
    .form-input {
        width: 100%;
        padding: 0.875rem 1rem;
        font-size: 1rem;
        font-weight: 400;
        color: #2d3748;
        background-color: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 1rem;
        transition: all 0.2s ease;
        margin-bottom: 1.5rem;
    }

    .form-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        transform: translateY(-1px);
    }

    .form-input::placeholder {
        color: #a0aec0;
    }

    /* Password toggle button */
    .password-toggle {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #a0aec0;
        padding: 0.25rem;
        transition: color 0.2s ease;
    }

    .password-toggle:hover {
        color: #667eea;
    }

    .input-group {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .input-group input {
        padding-right: 3rem;
    }

    /* Submit button */
    .btn-submit {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 1rem;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: -0.025em;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
        margin-top: 1rem;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    .btn-submit::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn-submit:hover::before {
        left: 100%;
    }

    /* Alert styling */
    .alert {
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        border: none;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    /* Password requirements */
    .password-requirements {
        margin-top: 0.5rem;
        font-size: 0.75rem;
        color: #718096;
    }

    .password-requirements ul {
        list-style: none;
        padding: 0;
        margin: 0.5rem 0 0;
    }

    .password-requirements li {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.25rem;
    }

    .password-requirements li.met {
        color: #48bb78;
    }

    .password-requirements li svg {
        width: 14px;
        height: 14px;
    }

    /* Back link */
    .back-link {
        text-align: center;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    .back-link a {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: color 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .back-link a:hover {
        color: #764ba2;
        text-decoration: underline;
    }

    .back-link svg {
        width: 1rem;
        height: 1rem;
    }

    /* Responsive adjustments */
    @media (max-width: 640px) {
        .auth-card {
            padding: 2rem;
            border-radius: 1.5rem;
        }

        .auth-title {
            font-size: 1.75rem;
        }
    }
</style>

<div class="auth-page-wrapper">
    <div class="auth-card">
        <!-- Icon -->
        <div class="icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 3a6 6 0 01-7.743 5.743L12 17h-1.5a9 9 0 11-13.397-6.387 9 9 0 0113.397 6.387z" />
            </svg>
        </div>

        <!-- Header -->
        <h1 class="auth-title">Reset Password</h1>
        <p class="auth-subtitle">
            Enter your email, verification code, and new password to regain access to your account.
        </p>

        <!-- Success Message -->
        @if (session('status'))
            <div class="alert alert-success">
                <svg class="inline-block mr-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="alert alert-error">
                <svg class="inline-block mr-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.708c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
            @csrf

            <!-- Email Field -->
            <div>
                <label class="form-label" for="email">Email Address</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ $email }}"
                    class="form-input"
                    required
                    readonly
                >
            </div>

            <!-- OTP Field -->
            <div>
                <label class="form-label" for="otp">Verification Code</label>
                <input
                    type="text"
                    name="otp"
                    id="otp"
                    value="{{ old('otp') }}"
                    placeholder="Enter 6-digit code"
                    class="form-input"
                    required
                    autofocus
                    maxlength="6"
                    pattern="[0-9]{6}"
                >
            </div>

            <!-- New Password Field -->
            <div>
                <label class="form-label" for="password">New Password</label>
                <div class="input-group">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-input"
                        required
                        minlength="8"
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                        <svg id="eye-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <div class="password-strength">
                    <div class="password-strength-bar" id="password-strength-bar"></div>
                </div>
                <div class="password-requirements">
                    <ul>
                        <li id="req-length">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            At least 8 characters
                        </li>
                        <li id="req-uppercase">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            At least one uppercase letter
                        </li>
                        <li id="req-lowercase">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            At least one lowercase letter
                        </li>
                        <li id="req-number">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            At least one number
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Confirm Password Field -->
            <div>
                <label class="form-label" for="password_confirmation">Confirm New Password</label>
                <div class="input-group">
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        class="form-input"
                        required
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <span class="flex items-center justify-center gap-2">
                    Reset Password
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </span>
            </button>
        </form>

        <div class="back-link">
            <a href="{{ route('login') }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Login
            </a>
        </div>
    </div>
</div>

<script>
    function togglePassword(fieldId, button) {
        const field = document.getElementById(fieldId);
        const icon = button.querySelector('svg');

        if (field.type === 'password') {
            field.type = 'text';
            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            `;
        } else {
            field.type = 'password';
            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `;
        }
    }

    // Password strength checker
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('password-strength-bar');
    const requirements = {
        length: document.getElementById('req-length'),
        uppercase: document.getElementById('req-uppercase'),
        lowercase: document.getElementById('req-lowercase'),
        number: document.getElementById('req-number')
    };

    function updatePasswordStrength() {
        const password = passwordInput.value;
        let strength = 0;

        // Check length
        if (password.length >= 8) {
            strength++;
            requirements.length.classList.add('met');
            requirements.length.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />';
        } else {
            requirements.length.classList.remove('met');
            requirements.length.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />';
        }

        // Check uppercase
        if (/[A-Z]/.test(password)) {
            strength++;
            requirements.uppercase.classList.add('met');
            requirements.uppercase.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />';
        } else {
            requirements.uppercase.classList.remove('met');
            requirements.uppercase.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />';
        }

        // Check lowercase
        if (/[a-z]/.test(password)) {
            strength++;
            requirements.lowercase.classList.add('met');
            requirements.lowercase.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />';
        } else {
            requirements.lowercase.classList.remove('met');
            requirements.lowercase.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />';
        }

        // Check number
        if (/\d/.test(password)) {
            strength++;
            requirements.number.classList.add('met');
            requirements.number.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />';
        } else {
            requirements.number.classList.remove('met');
            requirements.number.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />';
        }

        // Update strength bar
        strengthBar.className = 'password-strength-bar';
        if (password.length > 0) {
            if (strength <= 1) {
                strengthBar.classList.add('weak');
            } else if (strength === 2 || strength === 3) {
                strengthBar.classList.add('medium');
            } else {
                strengthBar.classList.add('strong');
            }
        }
    }

    passwordInput.addEventListener('input', updatePasswordStrength);
    updatePasswordStrength();
</script>
@endsection
