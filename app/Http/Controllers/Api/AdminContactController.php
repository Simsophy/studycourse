<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    /**
     * Get all contacts with filtering and search
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:pending,read,replied',
            'search' => 'nullable|string|max:255',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $query = Contact::latest();

        // Filter by status
        if (!empty($validated['status'])) {
            $query->byStatus($validated['status']);
        }

        // Search in name, email, subject, message
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
        $contacts = $query->paginate($perPage)->withQueryString();

        return response()->json([
            'success' => true,
            'message' => 'All contacts retrieved',
            'data' => $contacts,
        ]);
    }

    /**
     * Get specific contact
     */
    public function show(Contact $contact)
    {
        // Mark as read if not already
        if ($contact->status === 'pending') {
            $contact->update(['status' => 'read']);
        }

        return response()->json([
            'success' => true,
            'data' => $contact,
        ]);
    }

    /**
     * Reply to a contact message
     */
    public function reply(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'admin_reply' => 'required|string|min:10',
        ]);

        $contact->update([
            'admin_reply' => $validated['admin_reply'],
            'status' => 'replied',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reply sent successfully',
            'data' => $contact,
        ]);
    }

    /**
     * Mark contact as read
     */
    public function markAsRead(Contact $contact)
    {
        if ($contact->status === 'pending') {
            $contact->update(['status' => 'read']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Contact marked as read',
            'data' => $contact,
        ]);
    }

    /**
     * Delete a contact
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact deleted successfully',
        ]);
    }

    /**
     * Get contact statistics
     */
    public function statistics()
    {
        $stats = [
            'total' => Contact::count(),
            'pending' => Contact::byStatus('pending')->count(),
            'read' => Contact::byStatus('read')->count(),
            'replied' => Contact::byStatus('replied')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}
