@extends('layouts.admin')

@section('title', 'Admin Login')
@section('page-title', 'Admin Login')

@section('content')
<div class="flex items-center justify-center min-h-[70vh]">
    <div class="w-full max-w-md bg-white p-8 rounded-3xl shadow-lg">
        <h1 class="text-2xl font-bold text-center mb-6">Admin Login</h1>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 rounded">
                @foreach ($errors->all() as $error)
                    <p class="text-red-600 text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
            @csrf
            <input type="text" name="username" placeholder="Username"
                class="w-full p-3 border rounded-xl" value="{{ old('username') }}" required>
            <input type="password" name="password" placeholder="Password"
                class="w-full p-3 border rounded-xl" required>
            <button type="submit" class="w-full bg-indigo-600 text-white p-3 rounded-xl hover:bg-indigo-700">
                Login
            </button>
        </form>
    </div>
</div>
@endsection