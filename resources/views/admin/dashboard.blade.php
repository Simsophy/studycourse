@extends('layouts.admin')

@section('title', 'Admin Login')
@section('page-title', 'Authentication')

@section('sidebar')
    <div class="mt-10 px-4">
        <div class="bg-slate-800 p-4 rounded-lg border border-slate-700">
            <p class="text-xs text-slate-400 leading-relaxed">
                Please log in to access course management, student records, and video uploads.
            </p>
        </div>
    </div>
@endsection

@section('content')
<div class="flex flex-col items-center justify-center min-h-[70vh]">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight mb-3">
            Welcome Back
        </h1>
        <p class="text-slate-500 text-lg">Enter your admin credentials to continue.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 w-full max-w-md">
        <div class="bg-white border border-slate-200 p-4 rounded-xl shadow-sm">
            <p class="text-xs text-slate-400 uppercase font-bold mb-1">Current Focus</p>
            <p class="text-slate-700 font-medium">Laravel Basics</p>
        </div>
        <div class="bg-white border border-slate-200 p-4 rounded-xl shadow-sm">
            <p class="text-xs text-slate-400 uppercase font-bold mb-1">Next Up</p>
            <p class="text-slate-700 font-medium">Admin Panel Development</p>
    </div>

    <div class="w-full max-w-md">
        <form action="{{ route('admin.login.submit') }}" method="POST" class="bg-white p-8 rounded-2xl shadow-xl border border-slate-100">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Username or Email</label>
                <input type="text" name="username" 
                    class="w-full border border-slate-300 px-4 py-3 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" 
                    required>
            </div>

            <div class="mb-8">
                <div class="flex justify-between mb-2">
                    <label class="text-sm font-semibold text-slate-700">Password</label>
                    <a href="#" class="text-xs text-indigo-600 hover:underline">Forgot?</a>
                </div>
                <input type="password" name="password" 
                    class="w-full border border-slate-300 px-4 py-3 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" 
                    placeholder="••••••••" required>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transform transition-active active:scale-95 shadow-lg shadow-indigo-200">
                Sign In
            </button>
        </form>
        
        <p class="text-center text-slate-400 text-sm mt-8">
            &copy; 2026 EduPlatform Study Course. All rights reserved.
        </p>
    </div>
</div>
@endsection