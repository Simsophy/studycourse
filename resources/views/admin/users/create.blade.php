@extends('layouts.admin')

@section('title', 'Create User')
@section('page-title', 'Create User')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded-2xl shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-center">Create New User</h2>

    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-gray-500 mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" 
                class="w-full border rounded px-3 py-2" required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-gray-500 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" 
                class="w-full border rounded px-3 py-2" required>
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-gray-500 mb-1">Password</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="border rounded-lg p-4 bg-gray-50">
            <p class="font-semibold text-gray-700 mb-3">User Permissions</p>

            <label class="flex items-center gap-2 mb-2">
                <input type="checkbox" name="can_view_content" value="1" {{ old('can_view_content', 1) ? 'checked' : '' }}>
                <span>Can View Lessons</span>
            </label>

            <label class="flex items-center gap-2 mb-2">
                <input type="checkbox" name="can_save_content" value="1" {{ old('can_save_content', 1) ? 'checked' : '' }}>
                <span>Can Save / Enroll Courses</span>
            </label>

            <label class="flex items-center gap-2">
                <input type="checkbox" name="can_download_content" value="1" {{ old('can_download_content', 0) ? 'checked' : '' }}>
                <span>Can Download Lesson Video</span>
            </label>
        </div>

        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded transition">
            Create User
        </button>
    </form>
</div>
@endsection