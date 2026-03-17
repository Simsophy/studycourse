<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminAuthController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        // Validate username and password
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt login using 'admin' guard
        if (Auth::guard('admin')->attempt([
            'username' => $request->username, // changed from email
            'password' => $request->password
        ])) {
            // Regenerate session to prevent fixation attacks
            $request->session()->regenerate();

            return redirect()->intended('/admin/dashboard'); // Admin dashboard route
        }

        // If login fails
        return back()->withErrors([
            'username' => 'Invalid credentials',
        ])->withInput();
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login.submit');
    }
}