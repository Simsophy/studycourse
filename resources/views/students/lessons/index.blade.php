@extends('layouts.student')

@section('title', $course->name ?? __('ui.my_lessons'))

@section('content')
@if(session('success'))
    <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3">
        {{ session('error') }}
    </div>
@endif

<div class="mb-6 flex items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold mb-2">{{ $course->name ?? ((isset($courses) && $courses->count()) ? __('ui.courses') : __('ui.my_lessons')) }}</h2>
        <p class="text-gray-500">{{ $course->description ?? '' }}</p>
    </div>

    <a href="{{ route('user.dashboard') }}"
       class="inline-flex items-center gap-2 bg-indigo-500/90 text-white px-4 py-2 rounded-xl font-semibold hover:bg-indigo-600 transition whitespace-nowrap">
        {{ __('ui.view_schedule') }}
    </a>
</div>

@if(isset($courses) && $courses->count())
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($courses as $item)
            @php
                $isEnrolledInCourse = in_array($item->id, $enrolledCourseIds ?? []);
                $isStartedCourse = in_array($item->id, $startedCourseIds ?? []);
            @endphp
            <div class="bg-white p-5 rounded-2xl shadow hover:shadow-lg transition flex flex-col border border-gray-100">
                <h3 class="font-semibold text-lg mb-2">{{ $item->name }}</h3>
                <p class="text-gray-500 text-sm line-clamp-3 mb-3">{{ $item->description ?: __('ui.no_description_yet') }}</p>
                <p class="text-xs text-gray-400 mb-4">{{ $item->lessons_count }} lesson(s)</p>

                <div class="mt-auto flex gap-2">
                    <a href="{{ $isEnrolledInCourse ? route('user.courses.resume', $item->id) : route('user.courses.lessons.index', $item->id) }}"
                       class="w-full block text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                        {{ $isEnrolledInCourse ? ($isStartedCourse ? __('ui.continue_course') : __('ui.start_course')) : __('ui.open_course') }}
                    </a>

                    @if(!$isEnrolledInCourse && auth()->user()->can_save_content)
                        <form action="{{ route('user.courses.enroll', $item->id) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition">
                                {{ __('ui.enroll') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($lessons as $lesson)
            @php
                $canViewLesson = ($isEnrolled ?? false)
                    || in_array($lesson->course_id, $enrolledCourseIds ?? [])
                    || in_array($lesson->id, $enrolledLessonIds ?? []);
            @endphp
            <div class="bg-white p-5 rounded-2xl shadow hover:shadow-lg transition flex flex-col">

                <!-- Video preview -->
                <div class="relative h-48 bg-gray-200 mb-4 flex items-center justify-center">
                    @if($lesson->video_url)
                        <span class="text-white italic bg-gray-800 px-2 py-1 rounded">{{ __('ui.video_available') }}</span>
                    @else
                        <span class="text-gray-400">{{ __('ui.no_preview') }}</span>
                    @endif
                </div>

                <h3 class="font-semibold text-lg">{{ $lesson->title }}</h3>
                <p class="text-gray-500 text-sm line-clamp-3 mb-2">{{ $lesson->description }}</p>

                <!-- Admin info -->
                <p class="text-xs text-gray-400 mb-2">{{ __('ui.uploaded_by') }}: {{ $lesson->admin->username ?? __('ui.admin') }}</p>

                <!-- Enroll / View button -->
                <div class="mt-auto">
                    @if($canViewLesson)
                        <a href="{{ route('user.lessons.show', $lesson->id) }}"
                           class="w-full block text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                            {{ __('ui.view_lesson') }}
                        </a>
                    @else
                        @if(auth()->user()->can_save_content)
                            <form action="{{ route('user.courses.enroll', $course->id ?? $lesson->course_id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition">
                                    {{ __('ui.enroll_course') }}
                                </button>
                            </form>
                        @else
                            <button type="button" disabled
                                    class="w-full bg-gray-300 text-gray-600 py-2 rounded-lg cursor-not-allowed">
                                {{ __('ui.enroll_disabled_by_admin') }}
                            </button>
                        @endif
                    @endif
                </div>

            </div>
        @empty
            <p class="text-gray-500 italic col-span-full">{{ __('ui.no_lessons_available') }}</p>
        @endforelse
    </div>
@endif
@endsection