<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Forgot Password</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-white p-8 rounded-3xl shadow-lg border border-slate-200">
        <h1 class="text-2xl font-bold text-center mb-2">Forgot Password</h1>
        <p class="text-center text-slate-500 text-sm mb-6">Enter your admin email to receive a 6-digit verification code.</p>

        @if (session('status'))
            <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-500 rounded text-green-700 text-sm">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 rounded">
                @foreach ($errors->all() as $error)
                    <p class="text-red-600 text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.password.email') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-gray-600 mb-1">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="admin@example.com"
                    class="w-full p-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    required
                >
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white p-3 rounded-xl hover:bg-indigo-700 transition">
                Send Verification Code
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-slate-500">
            Back to <a href="{{ route('admin.login') }}" class="text-indigo-600 hover:underline">Admin Login</a>
        </p>
    </div>
</body>
</html>
