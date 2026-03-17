@extends('layouts.student')

@section('title', 'Lesson - ' . $lesson->title)

@section('content')
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
@else
    <p class="text-gray-500 italic">No video available for this lesson.</p>
@endif
@endsection