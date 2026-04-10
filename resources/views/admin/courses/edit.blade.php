@extends('layouts.admin')

@section('title', 'Edit Course')
@section('page-title', 'Edit Course')
@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
    <h2 class="text-2xl font-bold mb-8 text-gray-800 text-center">Edit Course</h2>

<div class="mb-6 p-4 rounded-xl border border-indigo-100 bg-indigo-50/40">
    <h3 class="font-semibold text-indigo-800 mb-2">Current Course Data</h3>
    <div class="text-sm text-gray-700 space-y-1">
        <p><span class="font-medium">Course ID:</span> {{ $course->id }}</p>
        <p><span class="font-medium">Name:</span> {{ $course->name }}</p>
        <p><span class="font-medium">Description:</span> {{ $course->description ?: 'No description' }}</p>
        <p><span class="font-medium">Total Lessons:</span> {{ $course->lessons->count() }}</p>
        <p><span class="font-medium">Total Materials:</span> {{ $course->materials->count() }}</p>
        @if($course->lessons->first()?->admin)
            <p><span class="font-medium">Uploaded by:</span> {{ $course->lessons->first()->admin->username }}</p>
        @endif
    </div>
</div>

<div class="mb-6 p-4 rounded-xl border border-purple-100 bg-purple-50/40">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="font-semibold text-purple-800 mb-1">Course Materials</h3>
            <p class="text-sm text-purple-600">Upload documents, PDFs, images, and videos for this course</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.courses.materials.index', $course) }}"
               class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg text-sm font-semibold hover:bg-purple-200 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Manage Materials ({{ $course->materials->count() }})
            </a>
            <a href="{{ route('admin.courses.materials.create', $course) }}"
               class="px-4 py-2 bg-purple-600 text-white rounded-lg text-sm font-semibold hover:bg-purple-700 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Material
            </a>
        </div>
    </div>
</div>

<form action="{{ route('admin.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')

@php
    $firstLesson = $course->lessons->first();
@endphp

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Course Title</label>
    <input type="text" name="name" value="{{ old('name', $course->name) }}"
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition"
        required>
    @error('name')
        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
    @enderror
</div>

<div class="mt-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
    <textarea name="description" rows="4"
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition"
        >{{ old('description', $course->description) }}</textarea>
    @error('description')
        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
    @enderror
</div>

<div class="space-y-6 mt-6">

    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
        <label class="block text-sm font-semibold text-gray-700 mb-3">Course Thumbnail</label>

        @if($course->image)
        <div class="mb-3">
            <img src="{{ asset('storage/'.$course->image) }}"
                class="w-40 h-24 object-cover rounded-lg shadow-sm border border-gray-200">

            <span class="text-xs text-gray-400 mt-1 block italic">
                Current: {{ basename($course->image) }}
            </span>
        </div>
        @endif

        <input type="file" name="image" accept="image/*"
            class="block w-full text-sm text-gray-500
            file:mr-4 file:py-2 file:px-4
            file:rounded-full file:border-0
            file:text-sm file:font-semibold
            file:bg-yellow-50 file:text-yellow-700
            hover:file:bg-yellow-100">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Lesson Title</label>
            <input type="text" name="lesson_title" value="{{ old('lesson_title', $firstLesson?->title) }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition"
                placeholder="Enter lesson title">
            @error('lesson_title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Lesson Description</label>
            <textarea name="lesson_description" rows="2"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition"
                placeholder="Briefly describe the lesson...">{{ old('lesson_description', $firstLesson?->description) }}</textarea>
            @error('lesson_description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Lesson Video (MP4)</label>
            <input type="file" name="lesson_video" accept="video/mp4"
                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
            @if($firstLesson?->video_url)
                <span class="text-xs text-gray-400 mt-1 block italic">Current: {{ basename($firstLesson->video_url) }}</span>
            @endif
            @error('lesson_video') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>
    </div>

@if($course->lessons->count())
<div class="mt-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-3">Lessons in this course</h3>
    <div class="overflow-x-auto border border-gray-200 rounded-xl">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="text-left px-4 py-2">Title</th>
                    <th class="text-left px-4 py-2">Description</th>
                    <th class="text-left px-4 py-2">Video</th>
                </tr>
            </thead>
            <tbody>
                @foreach($course->lessons as $lesson)
                    <tr class="border-t border-gray-100">
                        <td class="px-4 py-2 font-medium text-gray-800">{{ $lesson->title }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ \Illuminate\Support\Str::limit($lesson->description, 80) }}</td>
                        <td class="px-4 py-2">
                            @if($lesson->video_url)
                                <span class="inline-flex px-2 py-1 rounded bg-green-100 text-green-700 text-xs font-semibold">Available</span>
                            @else
                                <span class="inline-flex px-2 py-1 rounded bg-gray-100 text-gray-600 text-xs font-semibold">No video</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="pt-6 space-y-3">
    <button type="submit"
        class="w-full bg-yellow-500 text-white font-bold py-3 px-6 rounded-xl hover:bg-yellow-600 shadow-md transform active:scale-95 transition-all">
        Update Course
    </button>

    <a href="{{ route('admin.courses.index') }}"
        class="block text-center w-full py-2 text-sm text-gray-500 hover:text-gray-700 transition">
        Cancel Changes
    </a>
</div>

</form>
</div>
@endsection