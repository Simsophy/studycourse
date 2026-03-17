<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserContactController extends Controller
{
    /**
     * Get all contacts for authenticated user
     */
    public function index()
    {
        $contacts = Contact::byUser(auth()->id())
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Contacts retrieved successfully',
            'data' => $contacts,
        ]);
    }

    /**
     * Get specific contact
     */
    public function show(Contact $contact)
    {
        // Check if user owns this contact
        if ($contact->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

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
     * Submit a new contact message
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        $contact = Contact::create([
            ...$validated,
            'user_id' => auth()->id(),
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contact message submitted successfully',
            'data' => $contact,
        ], 201);
    }

    /**
     * Update own contact message (only if pending)
     */
    public function update(Request $request, Contact $contact)
    {
        // Check if user owns this contact
        if ($contact->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        // Only allow update if status is still pending
        if ($contact->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update contact that has been read or replied to',
            ], 422);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'subject' => 'sometimes|string|max:255',
            'message' => 'sometimes|string|min:10',
        ]);

        $contact->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Contact updated successfully',
            'data' => $contact,
        ]);
    }

    /**
     * Delete own contact message
     */
    public function destroy(Contact $contact)
    {
        // Check if user owns this contact
        if ($contact->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact deleted successfully',
        ]);
    }
}
