<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback.
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google authentication failed. Please try again.');
        }

        // Check if user already exists with this google_id
        $user = User::where('google_id', $googleUser->id)->first();

        if (!$user) {
            // Check if user exists with this email (regular registration)
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Link existing user with Google
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                ]);
            } else {
                // Create new user with Google data
                $user = User::create([
                    'name' => $googleUser->name,
                    'username' => $this->generateUniqueUsername($googleUser->name),
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => null, // No password for socialite users
                    'email_verified_at' => now(), // Google verifies email
                ]);
            }
        }

        // Log in the user
        Auth::login($user);

        // Regenerate session for security
        $request->session()->regenerate();

        // Redirect to intended page or dashboard
        return redirect()->intended('/user/dashboard')->with('success', 'Successfully logged in with Google!');
    }

    /**
     * Generate a unique username from name.
     */
    private function generateUniqueUsername(string $name): string
    {
        $baseUsername = strtolower(str_replace(' ', '', $name));
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }
}

