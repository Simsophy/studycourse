@extends('layouts.admin')

@section('title', 'Courses')
@section('page-title', 'Courses')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-2xl font-bold text-gray-800">Course Management</h1>
    <a href="{{ route('admin.courses.create') }}" 
       class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-5 rounded-lg shadow-sm transition-all transform active:scale-95">
       + Add New Course
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm animate-fade-in">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @forelse($courses as $course)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-md transition-shadow">
            
            {{-- Course Image / Preview --}}
            <div class="relative h-48 bg-gray-200">
                @if($course->image)
                    <img src="{{ asset('storage/'.$course->image) }}" class="w-full h-full object-cover">
                @elseif($course->lessons->count() > 0)
                    <div class="w-full h-full flex items-center justify-center bg-gray-800 text-white italic text-sm">
                        Lesson/Video Available
                    </div>
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-50">
                        No Preview
                    </div>
                @endif
            </div>

            <div class="p-5 flex-grow">
                <h3 class="text-lg font-bold text-gray-900 mb-2 truncate">{{ $course->title }}</h3>
                <p class="text-gray-500 text-sm line-clamp-3 mb-4">{{ $course->description }}</p>
                
                @if($course->lessons->count() > 0)
                    <div class="mb-4">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                            <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/>
                            </svg>
                            Lesson Included
                        </span>
                    </div>
                @endif

                {{-- Admin info --}}
                @if($course->lessons->first()?->admin)
                    <p class="text-gray-400 text-xs">Uploaded by: {{ $course->lessons->first()->admin->username }}</p>
                @endif
            </div>

            {{-- Action Buttons --}}
            <div class="p-5 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <a href="{{ route('admin.courses.edit', $course->id) }}" 
                   class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>

                <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this course?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700 font-medium text-sm flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-full py-12 text-center bg-white rounded-2xl border-2 border-dashed border-gray-200">
            <p class="text-gray-500 italic">No courses found. Start by creating your first course!</p>
        </div>
    @endforelse
</div>
@endsection