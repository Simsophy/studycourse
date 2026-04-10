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

<!-- Course Materials / Documents -->
<div class="mt-12">
    <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
        <span>📂</span> Course Documents
    </h3>
    
    @php
        $materials = $lesson->course ? $lesson->course->materials : collect();
    @endphp

    @if($materials->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($materials as $material)
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0">
                            @if($material->type === 'document')
                                📄
                            @elseif($material->type === 'video')
                                🎥
                            @else
                                📁
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="font-bold text-slate-800 truncate">{{ $material->title }}</h4>
                            <p class="text-xs text-slate-500 mb-2 leading-tight">{{ $material->description }}</p>
                            <a href="{{ asset('storage/' . $material->file_path) }}" 
                               target="_blank"
                               class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                                <span>Download / View</span>
                                <span>→</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-slate-50 p-6 rounded-2xl border border-dashed border-slate-300 text-center">
            <p class="text-slate-500">No additional documents available for this course.</p>
        </div>
    @endif
</div>
@endsection