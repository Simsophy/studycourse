@extends('layouts.admin')

@section('title', 'Edit Course')
@section('page-title', 'Edit Course')
@include('courses.video_form')
@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
    <h2 class="text-2xl font-bold mb-8 text-gray-800 text-center">Edit Course</h2>

<form action="{{ route('admin.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')

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
        required>{{ old('description', $course->description) }}</textarea>
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

    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
        <label class="block text-sm font-semibold text-gray-700 mb-3">Course Video</label>

      <form action="{{ route('admin.videos.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="video_file" required>
    <button type="submit">Upload Video</button>
</form>
</div>

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