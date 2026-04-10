@extends('layouts.admin')

@section('title', 'Upload Material')
@section('page-title', 'Upload Material for: ' . $course->name)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.courses.materials.index', $course) }}"
           class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center transition-colors">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Materials
        </a>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm">
            <div class="font-semibold mb-1">Please fix the following errors:</div>
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('admin.courses.materials.store', $course) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-8">
                {{-- File Upload --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-3">File Upload *</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-indigo-400 transition-colors"
                         x-data="{ fileName: '', fileSize: '' }"
                         x-on:change="fileName = $event.target.files[0].name; fileSize = ($event.target.files[0].size / 1024).toFixed(2) + ' KB'">
                        <div class="space-y-3 text-center">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <div class="text-sm text-gray-600">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span class="flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Upload a file
                                    </span>
                                    <input id="file-upload" name="file" type="file" class="sr-only" required
                                           accept=".pdf,.doc,.docx,.xls,.xlsx,.txt,.jpg,.jpeg,.png,.gif,.mp4,.avi,.mov,.mpeg"
                                           x-on:change="fileName = $event.target.files[0].name; fileSize = ($event.target.files[0].size / 1024).toFixed(2) + ' KB'">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PDF, DOC, images, videos up to 10MB</p>
                            <div class="text-sm text-gray-700 font-medium" x-show="fileName" x-text="fileName + ' (' + fileSize + ')'"></div>
                        </div>
                    </div>
                    @error('file')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-800 mb-2">Material Title *</label>
                    <input type="text" name="title" id="title"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                           placeholder="Enter a descriptive title"
                           value="{{ old('title') }}"
                           required>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Type --}}
                <div>
                    <label for="type" class="block text-sm font-semibold text-gray-800 mb-2">Material Type *</label>
                    <select name="type" id="type"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all bg-white">
                        <option value="document" {{ old('type') == 'document' ? 'selected' : '' }}>Document (PDF, Word, Excel, Text)</option>
                        <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>Image (JPG, PNG, GIF)</option>
                        <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Video (MP4, AVI, MOV, MPEG)</option>
                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-800 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none"
                              placeholder="Provide a brief description of this material (optional)">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Order --}}
                <div>
                    <label for="order" class="block text-sm font-semibold text-gray-800 mb-2">Display Order</label>
                    <input type="number" name="order" id="order" min="0" value="{{ old('order', 0) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    <p class="mt-2 text-sm text-gray-500">Materials are sorted by this number in ascending order (0, 1, 2...)</p>
                    @error('order')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="mt-10 flex items-center justify-end gap-4">
                <a href="{{ route('admin.courses.materials.index', $course) }}"
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm transition-all transform active:scale-95 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    Upload Material
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
