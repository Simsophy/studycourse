<?php


namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // First page (guest admin)
    public function preLogin()
    {
        return view('admin.dashboard'); // show login link + related fields
    }

    // Login form submit
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('admin')->attempt([
            'username' => $request->username,
            'password' => $request->password
        ])) {
            $request->session()->regenerate();
            return redirect()->route('admin.panel'); // second page
        }

        return back()->withErrors(['username' => 'Invalid credentials']);
    }

    // Second page (full admin panel)
    public function index()
    {
        return view('admin.panel'); // full layout/admin with sidebar + main content
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.dashboard'); // back to first page
    }
}