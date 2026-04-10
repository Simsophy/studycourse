<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;

class NewPasswordController extends Controller
{
    /**
     * Show the reset password form.
     */
    public function create(Request $request)
    {
        $email = $request->query('email');

        if (! $email) {
            return redirect()->route('password.request');
        }

        // Check if OTP exists for this email
        $resetRecord = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (! $resetRecord) {
            return redirect()->route('password.request')->withErrors([
                'email' => __('Invalid or expired verification code. Please request a new one.'),
            ]);
        }

        return view('auth.reset-password', ['email' => $email]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'digits:6'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->letters()->numbers()],
        ]);

        $email = $request->input('email');
        $otp = $request->input('otp');

        // Find the OTP record
        $resetRecord = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (! $resetRecord) {
            return back()->withErrors(['otp' => __('Invalid or expired verification code.')])->withInput();
        }

        // Verify OTP
        if (! Hash::check($otp, $resetRecord->token)) {
            return back()->withErrors(['otp' => __('Invalid verification code.')])->withInput();
        }

        // Check if OTP expired (15 minutes)
        if (now()->diffInMinutes(Carbon::parse($resetRecord->created_at)) > 15) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();

            return back()->withErrors(['otp' => __('Verification code has expired. Please request a new one.')])->withInput();
        }

        // Reset the user's password
        $user = \App\Models\User::where('email', $email)->first();

        if (! $user) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return back()->withErrors(['email' => __('User not found.')])->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->remember_token = Str::random(60);
        $user->save();

        // Delete the OTP record
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        event(new PasswordReset($user));

        return redirect()->route('login')->with('status', __('Your password has been reset. You can now log in with your new password.'));
    }
}
