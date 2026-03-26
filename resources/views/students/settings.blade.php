@extends('layouts.student')

@section('title', 'Account Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h2 class="text-3xl font-black text-slate-900">Account Settings</h2>
        <p class="text-sm text-slate-500 mt-1">Update your profile information and password.</p>
    </div>

    @if (session('status'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700 font-semibold">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-700">
            <p class="font-bold mb-1">Please fix the following:</p>
            <ul class="list-disc ml-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('user.settings.update') }}" class="space-y-6 bg-white border border-slate-200 rounded-3xl p-6 md:p-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Full Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400" required>
            </div>

            <div>
                <label for="username" class="block text-sm font-bold text-slate-700 mb-2">Username</label>
                <input id="username" name="username" type="text" value="{{ old('username', $user->username) }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400" required>
            </div>
        </div>

        <div>
            <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}"
                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400" required>
        </div>

        <div class="border-t border-slate-200 pt-6">
            <h3 class="text-lg font-black text-slate-900 mb-1">Change Password (Optional)</h3>
            <p class="text-xs text-slate-500 mb-4">Leave password fields blank if you do not want to change it.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label for="current_password" class="block text-sm font-bold text-slate-700 mb-2">Current Password</label>
                    <input id="current_password" name="current_password" type="password"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-slate-700 mb-2">New Password</label>
                    <input id="password" name="password" type="password"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 rounded-xl bg-indigo-600 text-white font-black text-sm uppercase tracking-wider hover:bg-indigo-700 transition-all">
                Save Settings
            </button>
        </div>
    </form>

    <div class="bg-white border border-rose-200 rounded-3xl p-6 md:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="text-lg font-black text-slate-900">Logout</h3>
                <p class="text-sm text-slate-500 mt-1">Go to logout confirmation before signing out.</p>
            </div>

            <a href="{{ route('user.logout.form') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-rose-600 text-white font-black text-sm uppercase tracking-wider hover:bg-rose-700 transition-all">
                Logout
            </a>
        </div>
    </div>
</div>
@endsection