@extends('layouts.admin')

@section('title', 'Admin Panel')
@section('page-title', 'Dashboard Overview')

@section('sidebar')
<nav class="flex flex-col space-y-1">
    <a href="{{ route('admin.panel') }}" 
       class="flex items-center space-x-3 px-4 py-3 rounded-lg bg-indigo-600 text-white shadow-lg shadow-indigo-900/20 transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        <span class="font-medium">Dashboard</span>
    </a>

    <a href="{{ route('admin.users.index') }}" 
       class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        <span class="font-medium">Users</span>
    </a>

    <a href="{{ route('admin.courses.index') }}" 
       class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        <span class="font-medium">Courses</span>
    </a>
</nav>
@endsection

@section('content')
<div class="py-6">
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
            Welcome back, <span class="text-indigo-600">{{ auth('admin')->user()->username }}</span>!
        </h1>
        <p class="text-slate-500 mt-2 text-lg">Here’s what’s happening with your study platform today.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl">
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-indigo-50 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <span class="text-xs font-bold text-green-500 bg-green-50 px-2 py-1 rounded-full">+4%</span>
            </div>
            <h3 class="text-slate-500 font-medium">Total Users</h3>
            <p class="text-4xl font-bold text-slate-900 my-2">123</p>
            <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 flex items-center">
                View detailed list <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-amber-50 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <span class="text-xs font-bold text-amber-500 bg-amber-50 px-2 py-1 rounded-full">New</span>
            </div>
            <h3 class="text-slate-500 font-medium">Total Courses</h3>
            <p class="text-4xl font-bold text-slate-900 my-2">45</p>
            <a href="{{ route('admin.courses.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 flex items-center">
                Manage curriculum <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </div>
</div>
@endsection