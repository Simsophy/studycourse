@extends('layouts.student')

@section('title', 'Forgot Password')

@section('content')
<div class="flex items-center justify-center min-h-[70vh]">
    <div class="w-full max-w-md bg-white p-8 rounded-3xl shadow-lg">
        <h1 class="text-2xl font-bold text-center mb-4">Forgot Password</h1>
        <p class="text-gray-500 text-sm text-center mb-6">Enter your account email and we will send a 6-digit verification code.</p>

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

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com"
                       class="w-full p-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
            </div>

            <button type="submit"
                    class="w-full bg-indigo-600 text-white p-3 rounded-xl hover:bg-indigo-700 transition">
                Send Verification Code
            </button>
        </form>

        <p class="mt-4 text-center text-gray-500 text-sm">
            Back to <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Login</a>
        </p>
    </div>
</div>
@endsection
