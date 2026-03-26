@extends('layouts.student')

@section('title', 'Student Login')

@section('content')
<div class="flex items-center justify-center min-h-[80vh]">
    <div class="w-full max-w-md bg-white p-8 rounded-3xl shadow-lg">
        <h1 class="text-2xl font-bold text-center mb-6">Student Login</h1>

        @if (session('status'))
            <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-500 rounded text-green-700 text-sm">
                {{ session('status') }}
            </div>
        @endif

        {{-- Display errors --}}
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 rounded">
                @foreach ($errors->all() as $error)
                    <p class="text-red-600 text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Login Form --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

           <div>
    <label class="block text-gray-600 mb-1">Username</label>
    <input type="text" name="login" placeholder="Username"
           class="w-full p-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400" required>
</div>

            <div>
                <label class="block text-gray-600 mb-1">Password</label>
                <input type="password" name="password" placeholder="Password"
                       autocomplete="current-password"
                       class="w-full p-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400" required>
            </div>

            <div class="text-right">
                <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:underline">Forgot password?</a>
            </div>

            <button type="submit"
                    class="w-full bg-green-600 text-white p-3 rounded-xl hover:bg-green-700 transition">
                Login
            </button>
        </form>

        <p class="mt-4 text-center text-gray-500">
            Don't have an account? <a href="{{ route('register') }}" class="text-green-600 hover:underline">Register</a>
        </p>
    </div>
</div>
@endsection