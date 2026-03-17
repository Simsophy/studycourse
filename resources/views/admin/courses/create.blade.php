@extends('layouts.admin')

@section('title', 'Add Course')
@section('page-title', 'Add Course')
@include('courses.video_form')
@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
    <h2 class="text-2xl font-bold mb-8 text-gray-800 text-center">Add New Course</h2>

    <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Course Name</label>
            <input type="text" name="name" value="{{ old('name') }}" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" 
                placeholder="Enter course title" required>
            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Course Description</label>
            <textarea name="description" rows="4" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" 
                placeholder="Briefly describe the course contents...">{{ old('description') }}</textarea>
            @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Course Thumbnail (Image)</label>
                <input type="file" name="image" accept="image/*"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                @error('image') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
<form action="{{ route('admin.videos.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="video_file" required>
    <button type="submit">Upload Video</button>
</form>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lesson Title</label>
                <input type="text" name="lesson_title" value="{{ old("lesson_title") }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" 
                    placeholder="Enter lesson title">
                @error("lesson_title") <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lesson Description</label>
                <textarea name="lesson_description" rows="2" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" 
                    placeholder="Briefly describe the lesson...">{{ old("lesson_description") }}</textarea>
                @error("lesson_description") <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lesson Video (MP4)</label>
                <input type="file" name="lesson_video" accept="video/mp4"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                @error("lesson_video") <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="pt-4">
            <button type="submit" 
                class="w-full bg-indigo-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-indigo-700 transform active:scale-95 transition-all shadow-md hover:shadow-lg">
                Create Course
            </button>
            <div class="text-center mt-4">
                <a href="{{ route('admin.courses.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition">Cancel and Go Back</a>
            </div>
        </div>
    </form>
</div>
@endsection