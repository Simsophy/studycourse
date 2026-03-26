@extends('layouts.admin')

@section('title', 'Admin Panel')

@section('sidebar')
<nav class="space-y-5">
    <div class="px-3">
        <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-indigo-300/70">Main</p>
    </div>

    <a href="{{ route('admin.panel') }}"
       class="flex items-center gap-3 rounded-2xl px-4 py-3 font-semibold {{ request()->routeIs('admin.panel') || request()->routeIs('admin.dashboard') ? 'bg-indigo-500/20 text-white border border-indigo-300/40' : 'text-slate-300 hover:bg-white/10' }} transition">
        <span>🏠</span>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('admin.users.index') }}"
       class="flex items-center gap-3 rounded-2xl px-4 py-3 font-semibold {{ request()->routeIs('admin.users.*') ? 'bg-cyan-500/20 text-cyan-100 border border-cyan-300/40' : 'text-slate-300 hover:bg-white/10' }} transition">
        <span>👥</span>
        <span>Students</span>
    </a>

    <a href="{{ route('admin.courses.index') }}"
       class="flex items-center gap-3 rounded-2xl px-4 py-3 font-semibold {{ request()->routeIs('admin.courses.*') ? 'bg-emerald-500/20 text-emerald-100 border border-emerald-300/40' : 'text-slate-300 hover:bg-white/10' }} transition">
        <span>📚</span>
        <span>Courses</span>
    </a>

    <a href="{{ route('admin.contacts.index') }}"
       class="flex items-center gap-3 rounded-2xl px-4 py-3 font-semibold {{ request()->routeIs('admin.contacts.*') ? 'bg-amber-500/20 text-amber-100 border border-amber-300/40' : 'text-slate-300 hover:bg-white/10' }} transition">
        <span>📨</span>
        <span>Contacts</span>
    </a>
</nav>
@endsection

@section('content')
@php
    $avgLessons = $stats['courses'] > 0 ? round($stats['lessons'] / $stats['courses'], 1) : 0;
    $enrollRate = $stats['users'] > 0 ? min(100, round(($stats['enrollments'] / $stats['users']) * 100)) : 0;
@endphp

<div class="space-y-8 p-6">
    <section class="relative overflow-hidden rounded-[2.5rem] bg-indigo-900 p-10 shadow-2xl border-4 border-indigo-500/20">
        <div class="absolute top-0 right-0 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl -mr-20"></div>
        
        <div class="relative z-10">
            <h2 class="text-4xl md:text-5xl font-black text-white leading-tight">
                Welcome back, <span class="text-indigo-400">{{ auth('admin')->user()->username }}</span>
            </h2>
            <p class="mt-4 text-indigo-200 text-xl font-medium max-w-xl">
                The academy is performing <span class="text-emerald-400 font-black">excellently</span> today with a <span class="underline decoration-indigo-400">{{ $enrollRate }}%</span> conversion rate.
            </p>
            
            <div class="mt-8 flex gap-4">
                <a href="{{ route('admin.courses.create') }}" class="px-8 py-4 bg-indigo-500 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-lg shadow-indigo-500/40 hover:bg-indigo-400 transition-all">
                    Publish Course
                </a>
                <a href="{{ route('admin.users.create') }}" class="px-8 py-4 bg-transparent border-2 border-indigo-400 text-indigo-300 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-indigo-400/10 transition-all">
                    Register User
                </a>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <article class="bg-indigo-50 rounded-3xl p-6 border-2 border-indigo-100 shadow-sm">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-500">Total Students</p>
            <p class="text-4xl font-black text-indigo-900 mt-2">{{ number_format($stats['users']) }}</p>
            <p class="text-xs font-bold text-indigo-400 mt-4 flex items-center gap-1">
                <span class="text-indigo-600">●</span> Active Directory
            </p>
        </article>

        <article class="bg-rose-50 rounded-3xl p-6 border-2 border-rose-100 shadow-sm">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-rose-500">Live Courses</p>
            <p class="text-4xl font-black text-rose-900 mt-2">{{ number_format($stats['courses']) }}</p>
            <p class="text-xs font-bold text-rose-400 mt-4 flex items-center gap-1">
                <span class="text-rose-600">●</span> Managed Catalog
            </p>
        </article>

        <article class="bg-emerald-50 rounded-3xl p-6 border-2 border-emerald-100 shadow-sm">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-600">Modules/Lessons</p>
            <p class="text-4xl font-black text-emerald-900 mt-2">{{ number_format($stats['lessons']) }}</p>
            <p class="text-xs font-bold text-emerald-400 mt-4 flex items-center gap-1">
                <span class="text-emerald-600">●</span> Content Published
            </p>
        </article>

        <article class="bg-amber-50 rounded-3xl p-6 border-2 border-amber-100 shadow-sm">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-amber-600">Total Enrollments</p>
            <p class="text-4xl font-black text-amber-900 mt-2">{{ number_format($stats['enrollments']) }}</p>
            <p class="text-xs font-bold text-amber-500 mt-4 flex items-center gap-1">
                <span class="text-amber-600">●</span> Successful Sales
            </p>
        </article>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        <div class="bg-white rounded-[2.5rem] p-8 border-2 border-slate-100 shadow-sm">
            <h3 class="text-2xl font-black text-slate-900 mb-8 flex items-center gap-3">
                <span class="p-2 bg-indigo-600 rounded-xl text-white">📈</span>
                Growth Analytics
            </h3>

            <div class="space-y-8">
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-sm font-black text-indigo-900 uppercase tracking-tighter">Student Engagement</span>
                        <span class="px-3 py-1 bg-indigo-600 text-white rounded-full text-xs font-black">{{ $enrollRate }}%</span>
                    </div>
                    <div class="w-full h-4 bg-indigo-100 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-600 rounded-full shadow-[0_0_15px_rgba(79,70,229,0.4)]" style="width: {{ $enrollRate }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-sm font-black text-rose-900 uppercase tracking-tighter">Content Density</span>
                        <span class="px-3 py-1 bg-rose-600 text-white rounded-full text-xs font-black">{{ $avgLessons }} avg</span>
                    </div>
                    <div class="w-full h-4 bg-rose-100 rounded-full overflow-hidden">
                        <div class="h-full bg-rose-600 rounded-full shadow-[0_0_15px_rgba(225,29,72,0.4)]" style="width: {{ min(100, $avgLessons * 10) }}%"></div>
                    </div>
                </div>
            </div>

            <div class="mt-10 p-6 rounded-3xl bg-slate-900 text-white">
                <p class="text-indigo-400 font-black text-xs uppercase tracking-widest">Admin Tip</p>
                <p class="mt-2 font-bold text-lg leading-snug">
                    Your <span class="text-rose-400">Content Density</span> is lower than usual. Add <span class="text-emerald-400">2-3 more lessons</span> per course to increase ranking!
                </p>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] p-8 border-2 border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-2xl font-black text-slate-900 flex items-center gap-3">
                    <span class="p-2 bg-emerald-500 rounded-xl text-white">✨</span>
                    New Students
                </h3>
                <a href="{{ route('admin.users.index') }}" class="text-sm font-black text-indigo-600 bg-indigo-50 px-4 py-2 rounded-xl hover:bg-indigo-600 hover:text-white transition-all">View List</a>
            </div>

            <div class="space-y-4">
                @foreach($recentUsers as $user)
                <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border-2 border-transparent hover:border-indigo-500 hover:bg-white transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-900 text-indigo-100 flex items-center justify-center font-black text-lg group-hover:scale-110 transition-transform">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-black text-indigo-950">{{ $user->name }}</p>
                            <p class="text-xs font-bold text-indigo-400/80">{{ $user->email }}</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-indigo-600 transition-colors">
                        {{ $user->created_at->diffForHumans() }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</div>

<style>
    /* Adding extra clarity through typography */
    body { font-family: 'Inter', sans-serif; }
    .font-black { letter-spacing: -0.02em; }
</style>
@endsection