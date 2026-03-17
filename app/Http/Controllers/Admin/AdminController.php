<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin; // or User model if needed
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Show the list of admins (or users) in admin panel
    public function index()
    {
        // Example: fetch all admins or users
        $admins = Admin::all(); // or User::all() if you manage users here
        return view('admin.admins.index', compact('admins'));
    }

    // Optional: show dashboard if needed (but usually DashboardController handles it)
    public function dashboard()
    {
        return view('admin.dashboard'); // layout/admin used inside dashboard.blade.php
    }

    // Example: method to show a single admin (or user)
    public function show(Admin $admin)
    {
        return view('admin.admins.show', compact('admin'));
    }

    // Optional: any other admin-related management methods
}