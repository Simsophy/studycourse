<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class NewPasswordController extends Controller
{
    public function create($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'token'=>'required',
            'email'=>'required|email',
            'password'=>'required|confirmed|min:6',
        ]);

        $status = Password::reset(
            $request->only('email','password','password_confirmation','token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

       $guard = request()->is('admins/*') ? 'admins' : 'user';

return $status === Password::PASSWORD_RESET
            ? redirect()->route($guard . '.login')->with('status', __($status))
            : back()->withErrors(['email'=>[__($status)]]);
    }
}