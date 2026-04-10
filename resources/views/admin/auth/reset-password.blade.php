<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reset Password</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 1px, transparent 1px);
            background-size: 40px 40px;
            animation: float 25s linear infinite;
        }

        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-50px, -50px) rotate(360deg); }
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 2rem;
            padding: 3rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            position: relative;
            z-index: 1;
            max-width: 28rem;
            width: 100%;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

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

        .form-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

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

        .alert {
            border-radius: 0.75rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            border: none;
            animation: fadeIn 0.3s ease;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
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

        @media (max-width: 640px) {
            .auth-card {
                padding: 2rem;
                border-radius: 1.5rem;
            }

            .auth-title {
                font-size: 1.75rem;
            }

            .icon-wrapper {
                width: 4rem;
                height: 4rem;
            }

            .icon-wrapper svg {
                width: 2rem;
                height: 2rem;
            }
        }
    </style>
</head>
<body>
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

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('admin.password.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="form-label" for="email">Email Address</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email', request('email')) }}"
                    placeholder="admin@example.com"
                    class="form-input"
                    required
                    autofocus
                >
            </div>

            <div>
                <label class="form-label" for="otp">6-Digit Verification Code</label>
                <input
                    type="text"
                    name="otp"
                    id="otp"
                    value="{{ old('otp') }}"
                    placeholder="123456"
                    maxlength="6"
                    pattern="\d{6}"
                    class="form-input"
                    required
                >
            </div>

            <div>
                <label class="form-label" for="password">New Password</label>
                <div class="input-group">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-input"
                        required
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

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
            <a href="{{ route('admin.login') }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Admin Login
            </a>
        </div>
    </div>

    <script>
        function togglePassword(fieldId, button) {
            const field = document.getElementById(fieldId);
            const icon = button.querySelector('svg');

            if (field.type === 'password') {
                field.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            } else {
                field.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }
    </script>
</body>
</html>
