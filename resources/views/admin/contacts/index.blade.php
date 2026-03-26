@extends('layouts.admin')

@section('title', 'Contacts')
@section('page-title', 'Contact Messages')

@section('sidebar')
<nav class="flex flex-col space-y-2 p-4 bg-slate-900 h-full min-h-screen">
    <div class="px-4 mb-4">
        <span class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-400">Main Control</span>
    </div>

    <a href="{{ route('admin.panel') }}" class="group flex items-center space-x-3 px-4 py-3 rounded-2xl text-slate-400 hover:bg-slate-800 hover:text-indigo-400 transition-all">
        <span class="font-bold">Dashboard</span>
    </a>

    <a href="{{ route('admin.users.index') }}" class="group flex items-center space-x-3 px-4 py-3 rounded-2xl text-slate-400 hover:bg-slate-800 hover:text-indigo-400 transition-all">
        <span class="font-bold">User Management</span>
    </a>

    <a href="{{ route('admin.courses.index') }}" class="group flex items-center space-x-3 px-4 py-3 rounded-2xl text-slate-400 hover:bg-slate-800 hover:text-rose-400 transition-all">
        <span class="font-bold">Course Catalog</span>
    </a>

    <a href="{{ route('admin.contacts.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl bg-emerald-600 text-white shadow-lg shadow-emerald-500/20 transition-all">
        <span class="font-bold">Contact Messages</span>
    </a>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-4 border border-slate-200"><p class="text-xs text-slate-500">Total</p><p class="text-2xl font-bold">{{ number_format($stats['total']) }}</p></div>
        <div class="bg-white rounded-2xl p-4 border border-amber-200"><p class="text-xs text-amber-600">Pending</p><p class="text-2xl font-bold text-amber-700">{{ number_format($stats['pending']) }}</p></div>
        <div class="bg-white rounded-2xl p-4 border border-sky-200"><p class="text-xs text-sky-600">Read</p><p class="text-2xl font-bold text-sky-700">{{ number_format($stats['read']) }}</p></div>
        <div class="bg-white rounded-2xl p-4 border border-emerald-200"><p class="text-xs text-emerald-600">Replied</p><p class="text-2xl font-bold text-emerald-700">{{ number_format($stats['replied']) }}</p></div>
    </div>

    <form method="GET" action="{{ route('admin.contacts.index') }}" class="bg-white p-4 rounded-2xl border border-slate-200 grid grid-cols-1 md:grid-cols-4 gap-3">
        <input type="text" name="search" value="{{ $filters['search'] }}" placeholder="Search name, email, subject, message" class="border border-slate-300 rounded-xl px-3 py-2">
        <select name="status" class="border border-slate-300 rounded-xl px-3 py-2">
            <option value="">All statuses</option>
            <option value="pending" @selected($filters['status'] === 'pending')>Pending</option>
            <option value="read" @selected($filters['status'] === 'read')>Read</option>
            <option value="replied" @selected($filters['status'] === 'replied')>Replied</option>
        </select>
        <select name="per_page" class="border border-slate-300 rounded-xl px-3 py-2">
            @foreach([15, 25, 50, 100] as $size)
                <option value="{{ $size }}" @selected((int) $filters['per_page'] === $size)>{{ $size }} / page</option>
            @endforeach
        </select>
        <button type="submit" class="bg-indigo-600 text-white rounded-xl px-4 py-2 font-semibold">Apply Filters</button>
    </form>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-100 text-left">
                <tr>
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Sender</th>
                    <th class="px-4 py-3">Subject</th>
                    <th class="px-4 py-3">Message</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($contacts as $contact)
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-3">{{ $contact->id }}</td>
                        <td class="px-4 py-3">
                            <div class="font-semibold text-slate-900">{{ $contact->name }}</div>
                            <div class="text-xs text-slate-500">{{ $contact->email }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $contact->subject }}</td>
                        <td class="px-4 py-3">{{ \Illuminate\Support\Str::limit($contact->message, 90) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                {{ $contact->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $contact->status === 'read' ? 'bg-sky-100 text-sky-700' : '' }}
                                {{ $contact->status === 'replied' ? 'bg-emerald-100 text-emerald-700' : '' }}">
                                {{ ucfirst($contact->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $contact->created_at?->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-slate-500">No contact messages found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $contacts->links() }}
    </div>
</div>
@endsection
