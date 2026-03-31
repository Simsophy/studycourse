<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;

class NewPasswordController extends Controller
{
    public function create()
    {
        return view('admin.auth.reset-password');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email',
            'otp' => 'required|digits:6',
            'password' => ['required', 'confirmed', PasswordRule::min(8)->letters()->numbers()],
        ]);

        $reset = DB::table('admin_password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $reset || ! Hash::check($request->otp, $reset->token)) {
            return back()->withErrors(['otp' => __('Invalid verification code.')])->withInput($request->only('email'));
        }

        if (now()->diffInMinutes(Carbon::parse($reset->created_at)) > 15) {
            return back()->withErrors(['otp' => __('Verification code has expired. Please request a new one.')])->withInput($request->only('email'));
        }

        $admin = Admin::where('email', $request->email)->first();
        $admin->password = Hash::make($request->password);
        $admin->save();

        DB::table('admin_password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('admin.login')->with('status', __('Password reset successful. You can now log in.'));
    }
}
