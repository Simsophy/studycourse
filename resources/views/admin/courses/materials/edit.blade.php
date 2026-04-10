@extends('layouts.admin')

@section('title', 'Edit Material')
@section('page-title', 'Edit Material')

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
        <form action="{{ route('admin.courses.materials.update', [$course, $material]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-8">
                {{-- Current File Info --}}
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-800 mb-2">Current File</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            @php
                                $icon = match($material->type) {
                                    'video' => '🎬',
                                    'image' => '🖼️',
                                    'document' => '📄',
                                    default => '📎'
                                };
                            @endphp
                            <span class="text-2xl">{{ $icon }}</span>
                        </div>
                        <div class="flex-grow min-w-0">
                            <div class="font-medium text-gray-900 truncate">{{ $material->title }}</div>
                            <div class="text-sm text-gray-500">
                                {{ ucfirst($material->type) }} • {{ number_format($material->file_size / 1024, 2) }} KB
                            </div>
                        </div>
                        <a href="{{ route('admin.courses.materials.download', [$course, $material]) }}"
                           class="text-indigo-600 hover:text-indigo-800 flex items-center gap-1 text-sm font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download
                        </a>
                    </div>
                </div>

                {{-- File Replacement --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-3">Replace File (Optional)</label>
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
                                        Choose a new file
                                    </span>
                                    <input id="file-upload" name="file" type="file" class="sr-only"
                                           accept=".pdf,.doc,.docx,.xls,.xlsx,.txt,.jpg,.jpeg,.png,.gif,.mp4,.avi,.mov,.mpeg"
                                           x-on:change="fileName = $event.target.files[0].name; fileSize = ($event.target.files[0].size / 1024).toFixed(2) + ' KB'">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">Leave blank to keep current file. Max 10MB.</p>
                            <div class="text-sm text-gray-700 font-medium" x-show="fileName" x-text="fileName + ' (' + fileSize + ')'"></div>
                        </div>
                    </div>
                    @error('file')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-800 mb-2">Title *</label>
                    <input type="text" name="title" id="title"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                           value="{{ old('title', $material->title) }}"
                           required>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Type --}}
                <div>
                    <label for="type" class="block text-sm font-semibold text-gray-800 mb-2">Material Type *</label>
                    <select name="type" id="type"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all bg-white"
                            {{ $material->file_path ? 'disabled' : '' }}>
                        <option value="document" {{ (old('type', $material->type) == 'document') ? 'selected' : '' }}>Document (PDF, Word, Excel, Text)</option>
                        <option value="image" {{ (old('type', $material->type) == 'image') ? 'selected' : '' }}>Image (JPG, PNG, GIF)</option>
                        <option value="video" {{ (old('type', $material->type) == 'video') ? 'selected' : '' }}>Video (MP4, AVI, MOV, MPEG)</option>
                        <option value="other" {{ (old('type', $material->type) == 'other') ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if($material->file_path)
                        <p class="mt-2 text-sm text-gray-500">Type cannot be changed when file is already uploaded</p>
                    @endif
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-800 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none"
                              placeholder="Provide a brief description">{{ old('description', $material->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Order --}}
                <div>
                    <label for="order" class="block text-sm font-semibold text-gray-800 mb-2">Display Order</label>
                    <input type="number" name="order" id="order" min="0" value="{{ old('order', $material->order) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    <p class="mt-2 text-sm text-gray-500">Materials are sorted by this number in ascending order</p>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update Material
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
