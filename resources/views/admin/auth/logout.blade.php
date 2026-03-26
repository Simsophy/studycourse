@extends('layouts.admin')

@section('title', 'Admin Logout')
@section('page-title', 'Confirm Logout')

@section('sidebar')
<nav class="space-y-5">
    <div class="px-3">
        <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-indigo-300/70">Main</p>
    </div>

    <a href="{{ route('admin.panel') }}"
       class="flex items-center gap-3 rounded-2xl px-4 py-3 font-semibold text-slate-300 hover:bg-white/10 transition">
        <span>🏠</span>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('admin.logout.form') }}"
       class="flex items-center gap-3 rounded-2xl px-4 py-3 font-semibold bg-rose-500/20 text-rose-100 border border-rose-300/40 transition">
        <span>🚪</span>
        <span>Logout</span>
    </a>
</nav>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white border border-rose-200 rounded-3xl p-6 md:p-8 shadow-sm">
        <h2 class="text-2xl font-black text-slate-900">Confirm Logout</h2>
        <p class="text-sm text-slate-500 mt-2">Are you sure you want to sign out from the admin panel?</p>

        <form method="POST" action="{{ route('admin.logout') }}" class="mt-6 flex flex-col sm:flex-row gap-3 sm:justify-end">
            @csrf

            <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-center font-semibold hover:bg-slate-50 transition">
                Cancel
            </a>

            <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 text-white font-bold hover:bg-rose-700 transition">
                Logout
            </button>
        </form>
    </div>
</div>
@endsection