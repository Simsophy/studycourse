@extends('layouts.student')

@section('title', 'Logout')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white border border-rose-200 rounded-3xl p-6 md:p-8">
        <h2 class="text-2xl font-black text-slate-900">Confirm Logout</h2>
        <p class="text-sm text-slate-500 mt-2">Are you sure you want to sign out from this account?</p>

        <form method="POST" action="{{ route('user.logout') }}" class="mt-6 flex flex-col sm:flex-row gap-3 sm:justify-end">
            @csrf

            <a href="{{ route('user.dashboard') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-center font-semibold hover:bg-slate-50 transition">
                Cancel
            </a>

            <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 text-white font-bold hover:bg-rose-700 transition">
                Logout
            </button>
        </form>
    </div>
</div>
@endsection