<?php

namespace App\Http\Controllers\Admin\Contacts;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('access-admin-panel');

        $validated = $request->validate([
            'status' => 'nullable|in:pending,read,replied',
            'search' => 'nullable|string|max:255',
            'per_page' => 'nullable|integer|min:5|max:100',
        ]);

        $query = Contact::query()->latest();

        if (!empty($validated['status'])) {
            $query->byStatus($validated['status']);
        }

        if (!empty($validated['search'])) {
            $search = $validated['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $perPage = (int) ($validated['per_page'] ?? 15);

        $contacts = $query
            ->paginate($perPage)
            ->withQueryString();

        $stats = [
            'total' => Contact::count(),
            'pending' => Contact::byStatus('pending')->count(),
            'read' => Contact::byStatus('read')->count(),
            'replied' => Contact::byStatus('replied')->count(),
        ];

        return view('admin.contacts.index', [
            'contacts' => $contacts,
            'stats' => $stats,
            'filters' => [
                'status' => $validated['status'] ?? '',
                'search' => $validated['search'] ?? '',
                'per_page' => $perPage,
            ],
        ]);
    }
}
