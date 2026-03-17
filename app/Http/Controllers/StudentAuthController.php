<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

   public function login(Request $request)
{
    $request->validate([
        'login' => 'required',
        'password' => 'required',
    ]);

    $login = $request->input('login');
    $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    if (Auth::attempt([$field => $login, 'password' => $request->password])) {
        $request->session()->regenerate();
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
        'username' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
    ]);

    $user = User::create([
        'username' => $request->username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    Auth::login($user);

    return redirect()->route('user.dashboard');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('user.login');
    }
}