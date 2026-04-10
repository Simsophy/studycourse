<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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

        .alert svg {
            flex-shrink: 0;
            width: 1.25rem;
            height: 1.25rem;
            margin-top: 0.125rem;
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

        .back-link svg {
            width: 1rem;
            height: 1rem;
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
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>

        <!-- Header -->
        <h1 class="auth-title">Forgot Password?</h1>
        <p class="auth-subtitle">
            No worries! Enter your email address and we'll send you a verification code to reset your password.
        </p>

        <!-- Success Message -->
        @if (session('status'))
            <div class="alert alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>{{ session('status') }}</div>
            </div>
        @endif

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
        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <div>
                <label class="form-label" for="email">Email Address</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email') }}"
                    placeholder="you@example.com"
                    class="form-input"
                    required
                    autofocus
                    autocomplete="email"
                >
            </div>

            <button type="submit" class="btn-submit">
                <span class="flex items-center justify-center gap-2">
                    Send Verification Code
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </span>
            </button>
        </form>

        <!-- Back to Login -->
        <div class="back-link">
            <a href="{{ route('login') }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Login
            </a>
        </div>
    </div>
</body>
</html>
