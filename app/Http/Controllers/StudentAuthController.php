<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StudentAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

   public function login(Request $request)
{
    $request->validate([
        'password' => 'required',
        'login' => 'nullable|string',
        'email' => 'nullable|email',
    ]);

    $loginInput = $request->input('login', $request->input('email'));

    if (!$loginInput) {
        return back()->withErrors(['login' => 'Login field is required'])->withInput();
    }

    $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    if (Auth::attempt([$field => $loginInput, 'password' => $request->password])) {
        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return response()->noContent();
        }

        return redirect()->route('user.dashboard');
    }

    return back()->withErrors(['login' => 'Invalid credentials'])->withInput();
}

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
{
    $request->validate([
        'name' => 'nullable|string|max:255',
        'username' => 'nullable|string|max:255|unique:users,username',
        'email' => 'required|email|unique:users,email',
        'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
    ]);

    $name = $request->input('name', 'Student User');
    $baseUsername = $request->input('username')
        ?: strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(' ', '_', $name)));
    $username = $baseUsername ?: ('user_'.uniqid());

    while (User::where('username', $username)->exists()) {
        $username = $baseUsername.'_'.rand(1000, 9999);
    }

    $user = User::create([
        'name' => $name,
        'username' => $username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    event(new Registered($user));

    Auth::login($user);

    if ($request->expectsJson()) {
        return response()->noContent();
    }

    return redirect()->route('user.dashboard');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->noContent();
        }

        return redirect()->route('login');
    }
}