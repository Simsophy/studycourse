<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended('/dashboard');
        }

        if (
            app()->environment('local')
            && config('mail.force_log_in_local')
            && config('mail.default') === 'smtp'
        ) {
            config(['mail.default' => 'log']);
        }

        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (\Throwable $exception) {
            Log::error('Email verification notification sending failed.', [
                'user_id' => $request->user()->id,
                'email' => $request->user()->email,
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'status' => 'verification-link-failed',
                'message' => __('Unable to send verification email right now. Please check mail configuration and try again.'),
            ], 500);
        }

        return response()->json(['status' => 'verification-link-sent']);
    }
}
