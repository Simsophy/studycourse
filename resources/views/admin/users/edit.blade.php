@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded-2xl shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-center">Edit User</h2>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-gray-500 mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                class="w-full border rounded px-3 py-2" required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-gray-500 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                class="w-full border rounded px-3 py-2" required>
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-gray-500 mb-1">Password (Leave blank to keep current)</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2">
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 rounded transition">
            Update User
        </button>
    </form>
</div>
@endsection