<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-white p-8 rounded-3xl shadow-lg border border-slate-200">
        <h1 class="text-2xl font-bold text-center mb-2">Admin Login</h1>
        <p class="text-center text-slate-500 text-sm mb-6">Sign in to access the admin dashboard.</p>

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

        <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
            @csrf
            <input
                type="text"
                name="login"
                placeholder="Email or name"
                class="w-full p-3 border rounded-xl"
                value="{{ old('login', old('email')) }}"
                autocomplete="username"
                required
            >
            <input
                type="password"
                name="password"
                placeholder="Password"
                class="w-full p-3 border rounded-xl"
                autocomplete="current-password"
                required
            >
            <button type="submit" class="w-full bg-indigo-600 text-white p-3 rounded-xl hover:bg-indigo-700">
                Login
            </button>
        </form>
    </div>
</body>
</html>