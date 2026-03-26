@extends('layouts.student')

@section('title', 'Lesson - ' . $lesson->title)

@section('content')
@if(session('error'))
    <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3">
        {{ session('error') }}
    </div>
@endif

<h2 class="text-2xl font-bold mb-4">{{ $lesson->title }}</h2>
<p class="text-gray-500 mb-4">{{ $lesson->description }}</p>

<!-- Admin info -->
<p class="text-xs text-gray-400 mb-4">Uploaded by: {{ $lesson->admin->username ?? 'Admin' }}</p>

<!-- Video -->
@if($lesson->video_url)
    <video class="w-full rounded-lg shadow mb-4" controls>
        <source src="{{ asset('storage/' . $lesson->video_url) }}" type="video/mp4">
        Your browser does not support HTML video.
    </video>

    @if(auth()->user()->can_download_content)
        <a href="{{ route('user.lessons.download', $lesson->id) }}"
           class="inline-flex items-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            Download Video
        </a>
    @else
        <button type="button" disabled
                class="inline-flex items-center bg-gray-300 text-gray-600 px-4 py-2 rounded-lg cursor-not-allowed">
            Download Disabled by Admin
        </button>
    @endif
@else
    <p class="text-gray-500 italic">No video available for this lesson.</p>
@endif
@endsection