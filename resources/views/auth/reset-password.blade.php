@extends('layouts.student')

@section('title', 'Reset Password')

@section('content')
<div class="flex items-center justify-center min-h-[70vh]">
    <div class="w-full max-w-md bg-white p-8 rounded-3xl shadow-lg">
        <h1 class="text-2xl font-bold text-center mb-4">Reset Password</h1>
        <p class="text-gray-500 text-sm text-center mb-6">Enter your email, 6-digit code, and new password.</p>

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

        <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ request('email') }}"
                       class="w-full p-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
            </div>

            <div>
                <label class="block text-gray-600 mb-1">6-Digit Code</label>
                <input type="text" name="otp" value="{{ old('otp') }}" maxlength="6" pattern="\d{6}"
                       class="w-full p-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
            </div>

            <div>
                <label class="block text-gray-600 mb-1">New Password</label>
                <input type="password" name="password"
                       class="w-full p-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
            </div>

            <div>
                <label class="block text-gray-600 mb-1">Confirm New Password</label>
                <input type="password" name="password_confirmation"
                       class="w-full p-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
            </div>

            <button type="submit"
                    class="w-full bg-indigo-600 text-white p-3 rounded-xl hover:bg-indigo-700 transition">
                Reset Password
            </button>
        </form>
    </div>
</div>
@endsection
