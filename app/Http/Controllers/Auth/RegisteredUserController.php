<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration form for students.
     */
    public function create()
    {
        return view('auth.register'); // your register blade
    }

    /**
     * Handle a registration request for students.
     */
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Create student user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Hash the password
        ]);

        // Fire the Registered event
        event(new Registered($user));

        // Log in the user immediately
        Auth::login($user);

        // Redirect to student dashboard
        return redirect()->route('user.dashboard');
    }
}