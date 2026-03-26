@extends('layouts.admin')

@section('title', 'Users')
@section('page-title', 'Users')

@section('content')
<a href="{{ route('admin.users.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded mb-4 inline-block">Create User</a>

<table class="w-full bg-white shadow rounded-lg">
    <thead>
        <tr class="bg-gray-100">
            <th class="p-2">ID</th>
            <th class="p-2">Name</th>
            <th class="p-2">Email</th>
            <th class="p-2">Permissions</th>
            <th class="p-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr class="border-b">
            <td class="p-2">{{ $user->id }}</td>
            <td class="p-2">{{ $user->name }}</td>
            <td class="p-2">{{ $user->email }}</td>
            <td class="p-2 text-xs">
                <div class="flex flex-wrap gap-1">
                    <span class="px-2 py-1 rounded {{ $user->can_view_content ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">View: {{ $user->can_view_content ? 'On' : 'Off' }}</span>
                    <span class="px-2 py-1 rounded {{ $user->can_save_content ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">Save: {{ $user->can_save_content ? 'On' : 'Off' }}</span>
                    <span class="px-2 py-1 rounded {{ $user->can_download_content ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">Download: {{ $user->can_download_content ? 'On' : 'Off' }}</span>
                </div>
            </td>
            <td class="p-2 flex gap-2">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-yellow-400 px-2 py-1 rounded">Edit</a>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="bg-red-500 px-2 py-1 rounded text-white" onclick="return confirm('Delete user?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection