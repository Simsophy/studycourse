@extends('layouts.student')

@section('title', 'Student Dashboard')

@section('content')
<div class="space-y-6 pb-10">
    <section class="rounded-3xl bg-gradient-to-r from-indigo-600 to-blue-500 p-6 sm:p-8 text-white shadow-xl">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">
            <div>
                <h2 class="text-2xl sm:text-3xl font-black">Hello, {{ auth()->user()->name ?? 'Student' }}</h2>
                <p class="mt-2 text-indigo-100">Welcome back! Continue your learning journey today.</p>
            </div>
            <a href="{{ route('user.lessons.index') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-indigo-700 hover:bg-indigo-50 transition">
                View All Lessons
            </a>
        </div>
    </section>

    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <article class="rounded-2xl border border-blue-100 bg-blue-50 px-5 py-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-blue-500">Students</p>
            <p class="text-3xl font-black text-blue-900 mt-1">{{ $stats['enrolled_courses'] ?? 0 }}</p>
        </article>
        <article class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-500">Courses</p>
            <p class="text-3xl font-black text-emerald-900 mt-1">{{ $stats['total_courses'] ?? 0 }}</p>
        </article>
        <article class="rounded-2xl border border-violet-100 bg-violet-50 px-5 py-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-violet-500">Lessons</p>
            <p class="text-3xl font-black text-violet-900 mt-1">{{ $stats['total_lessons'] ?? 0 }}</p>
        </article>
        <article class="rounded-2xl border border-amber-100 bg-amber-50 px-5 py-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-amber-500">Permissions</p>
            <p class="text-3xl font-black text-amber-900 mt-1">{{ $stats['my_permissions'] ?? 0 }}/3</p>
        </article>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800">My Active Courses</h3>
                <a href="{{ route('user.lessons.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">See all</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($courses->take(6) as $course)
                    @php
                        $isEnrolledInCourse = isset($enrolledCourseIds) && $enrolledCourseIds->contains($course->id);
                        $isStartedCourse = isset($startedCourseIds) && $startedCourseIds->contains($course->id);
                    @endphp
                    <div class="px-5 py-4 flex items-center justify-between gap-3">
                        <div>
                            <p class="font-semibold text-slate-800">{{ $course->name }}</p>
                            <p class="text-xs text-slate-500">{{ $course->lessons_count }} lessons</p>
                        </div>
                        <a href="{{ $isEnrolledInCourse ? route('user.courses.resume', $course) : route('user.courses.lessons.index', $course) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition">
                            {{ $isEnrolledInCourse ? ($isStartedCourse ? __('ui.continue_course') : __('ui.start_course')) : __('ui.open_course') }}
                        </a>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center text-sm text-slate-500">No courses available yet.</div>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800">Notice Board</h3>
            </div>
            <div class="p-5 space-y-4">
                @forelse($recommendedCourses->take(3) as $course)
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <p class="font-semibold text-slate-800">{{ $course->name }}</p>
                        <p class="text-xs text-slate-500 mt-1">{{ $course->lessons_count }} lessons • {{ $course->students_count }} students</p>
                        <div class="mt-3">
                            @if(isset($enrolledCourseIds) && $enrolledCourseIds->contains($course->id))
                                <span class="inline-flex rounded-lg bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-700">Enrolled</span>
                            @else
                                <form method="POST" action="{{ route('user.courses.enroll', $course) }}">
                                    @csrf
                                    <button type="submit" class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-indigo-700 transition">Enroll now</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No announcements right now.</p>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection