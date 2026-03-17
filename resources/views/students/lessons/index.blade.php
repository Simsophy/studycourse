@extends('layouts.student')

@section('title', $course->title ?? 'Course Lessons')

@section('content')
<h2 class="text-2xl font-bold mb-2">{{ $course->title ?? 'Course Lessons' }}</h2>
<p class="text-gray-500 mb-6">{{ $course->description ?? '' }}</p>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($lessons as $lesson)
        <div class="bg-white p-5 rounded-2xl shadow hover:shadow-lg transition flex flex-col">

            <!-- Video preview -->
            <div class="relative h-48 bg-gray-200 mb-4 flex items-center justify-center">
                @if($lesson->video_url)
                    <span class="text-white italic bg-gray-800 px-2 py-1 rounded">Video Available</span>
                @else
                    <span class="text-gray-400">No Preview</span>
                @endif
            </div>

            <h3 class="font-semibold text-lg">{{ $lesson->title }}</h3>
            <p class="text-gray-500 text-sm line-clamp-3 mb-2">{{ $lesson->description }}</p>

            <!-- Admin info -->
            <p class="text-xs text-gray-400 mb-2">Uploaded by: {{ $lesson->admin->username ?? 'Admin' }}</p>

            <!-- Enroll / View button -->
            <div class="mt-auto">
                @if(in_array($lesson->id, $enrolledLessonIds))
                    <a href="{{ route('lessons.show', $lesson->id) }}" 
                       class="w-full block text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                        View Lesson
                    </a>
                @else
                    <form action="{{ route('lessons.enroll', $lesson->id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition">
                            Enroll
                        </button>
                    </form>
                @endif
            </div>

        </div>
    @empty
        <p class="text-gray-500 italic col-span-full">No lessons available for this course.</p>
    @endforelse
</div>
@endsection