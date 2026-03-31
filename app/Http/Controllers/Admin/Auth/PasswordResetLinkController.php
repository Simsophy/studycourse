<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PasswordResetLinkController extends Controller
{
    public function create()
    {
        return view('admin.auth.forgot-password');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email',
        ]);

        $email = $request->input('email');
        $otp = (string) random_int(100000, 999999);

        try {
            DB::table('admin_password_reset_tokens')->updateOrInsert(
                ['email' => $email],
                [
                    'token' => Hash::make($otp),
                    'created_at' => now(),
                ]
            );

            $this->sendOtpMail($email, $otp, 'Your Admin Password Reset Code');
        } catch (\Throwable $exception) {
            Log::error('Admin password reset OTP sending failed.', [
                'email' => $email,
                'message' => $exception->getMessage(),
                'mailer' => config('mail.default'),
                'mail_host' => config('mail.mailers.smtp.host'),
                'mail_port' => config('mail.mailers.smtp.port'),
                'mail_scheme' => config('mail.mailers.smtp.scheme'),
            ]);

            return back()->withErrors([
                'email' => __('Unable to send verification code right now. Please check mail configuration and try again.'),
            ]);
        }

        return redirect()->route('admin.password.reset', ['email' => $email])
            ->with('status', __('A 6-digit verification code has been sent to your email.'));
    }

    private function sendOtpMail(string $email, string $otp, string $subject): void
    {
        Mail::send('emails.password-otp', ['otp' => $otp, 'email' => $email], function ($message) use ($email, $subject) {
            $message->to($email)->subject($subject);
        });
    }
}
