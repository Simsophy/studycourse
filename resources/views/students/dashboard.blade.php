@extends('Layouts.student')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    
    <div class="relative overflow-hidden rounded-3xl bg-indigo-600 p-8 md:p-12 shadow-xl shadow-indigo-200">
        <div class="relative z-10 max-w-2xl">
            <h2 class="text-3xl md:text-4xl font-extrabold text-white leading-tight">
                Welcome back, {{ auth()->user()->name ?? 'Student' }}! 👋
            </h2>
            <p class="mt-4 text-indigo-100 text-lg font-medium">
                You have 3 lessons in progress this week. Ready to continue your learning journey?
            </p>
           @foreach($courses as $course)
    <a href="{{ route('user.courses.lessons.index', $course->id) }}"
       class="inline-flex items-center gap-2 bg-white text-indigo-600 px-6 py-3 rounded-2xl font-bold hover:bg-indigo-50 transition-all active:scale-95 shadow-lg">
       Resume Learning: {{ $course->name }}
    </a>
@endforeach
    <button class="inline-flex items-center gap-2 bg-indigo-500/30 text-white border border-indigo-400/30 px-6 py-3 rounded-2xl font-bold hover:bg-indigo-500/50 transition-all">
                    View Schedule
                </button>
            </div>
        </div>
        
        <i data-lucide="graduation-cap" class="absolute -right-8 -bottom-8 w-64 h-64 text-indigo-500/20 -rotate-12 pointer-events-none"></i>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-200 hover:border-indigo-300 transition-colors shadow-sm group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-amber-50 rounded-2xl text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-all">
                    <i data-lucide="book-open" class="w-6 h-6"></i>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Active</span>
            </div>
            <h3 class="text-2xl font-black text-slate-800">12</h3>
            <p class="text-sm font-medium text-slate-500">Lessons Enrolled</p>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200 hover:border-indigo-300 transition-colors shadow-sm group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-emerald-50 rounded-2xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                    <i data-lucide="award" class="w-6 h-6"></i>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Completed</span>
            </div>
            <h3 class="text-2xl font-black text-slate-800">04</h3>
            <p class="text-sm font-medium text-slate-500">Certificates Earned</p>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200 hover:border-indigo-300 transition-colors shadow-sm group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-indigo-50 rounded-2xl text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                    <i data-lucide="clock" class="w-6 h-6"></i>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Study Time</span>
            </div>
            <h3 class="text-2xl font-black text-slate-800">24h</h3>
            <p class="text-sm font-medium text-slate-500">Learning this month</p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-900 tracking-tight">Recent Activity</h3>
            <a href="{{ route('user.lessons.index') }}" class="text-sm font-bold text-indigo-600 hover:underline">View All</a>
        </div>
        
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                <i data-lucide="sparkles" class="w-10 h-10 text-slate-300"></i>
            </div>
            <h4 class="text-slate-900 font-bold italic">"The expert in anything was once a beginner."</h4>
            <p class="text-slate-500 text-sm mt-2 max-w-xs">You haven't started any lessons today. Pick a course and start growing!</p>
        </div>
    </div>

</div>

<script>
    if (window.lucide) {
        lucide.createIcons();
    }
</script>
@endsection