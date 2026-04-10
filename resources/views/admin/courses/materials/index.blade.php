@extends('layouts.admin')

@section('title', 'Course Materials')
@section('page-title', 'Materials: ' . $course->name)

@section('content')
<div class="flex justify-between items-center mb-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.courses.index') }}"
           class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center transition-colors">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Courses
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Course Materials</h1>
    </div>
    <a href="{{ route('admin.courses.materials.create', $course) }}"
       class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-5 rounded-lg shadow-sm transition-all transform active:scale-95 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Material
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm animate-fade-in">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    @if($materials->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Size</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($materials as $material)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($material->type == 'video') bg-purple-100 text-purple-800
                                    @elseif($material->type == 'image') bg-green-100 text-green-800
                                    @elseif($material->type == 'document') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($material->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $material->title }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500 max-w-xs truncate">
                                    {{ $material->description ?: '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $material->order }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    {{ number_format($material->file_size / 1024, 2) }} KB
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.courses.materials.download', [$course, $material]) }}"
                                       class="text-indigo-600 hover:text-indigo-900 flex items-center gap-1 transition-colors"
                                       title="Download">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        <span class="hidden sm:inline">Download</span>
                                    </a>
                                    <a href="{{ route('admin.courses.materials.edit', [$course, $material]) }}"
                                       class="text-yellow-600 hover:text-yellow-900 flex items-center gap-1 transition-colors"
                                       title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span class="hidden sm:inline">Edit</span>
                                    </a>
                                    <form action="{{ route('admin.courses.materials.destroy', [$course, $material]) }}" method="POST"
                                          onsubmit="return confirm('Are you sure? This will permanently delete the file.');"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 flex items-center gap-1 transition-colors" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span class="hidden sm:inline">Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="py-16 px-6 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Materials Yet</h3>
            <p class="text-gray-500 mb-6">Upload documents, videos, or images for students to access.</p>
            <a href="{{ route('admin.courses.materials.create', $course) }}"
               class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm transition-all transform active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Upload First Material
            </a>
        </div>
    @endif
</div>

{{-- Quick stats --}}
<div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="text-sm text-gray-500 mb-1">Total Materials</div>
        <div class="text-3xl font-bold text-indigo-600">{{ $materials->count() }}</div>
    </div>
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="text-sm text-gray-500 mb-1">Documents</div>
        <div class="text-3xl font-bold text-blue-600">{{ $materials->where('type', 'document')->count() }}</div>
    </div>
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="text-sm text-gray-500 mb-1">Videos</div>
        <div class="text-3xl font-bold text-purple-600">{{ $materials->where('type', 'video')->count() }}</div>
    </div>
</div>
@endsection
