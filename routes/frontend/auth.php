<?php

use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\StudentAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Global Student Auth Routes (Frontend)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Login & Registration
    Route::get('/login', [StudentAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [StudentAuthController::class, 'login'])->middleware('throttle:auth-login');

    Route::get('/register', [StudentAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [StudentAuthController::class, 'register'])->middleware('throttle:auth-register');

    // Password Reset
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('throttle:auth-password-reset')
        ->name('password.email');

    Route::get('/reset-password', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

    // Google OAuth
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])
        ->name('google.redirect');

    Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])
        ->name('google.callback');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [StudentAuthController::class, 'logout'])->name('logout');

    Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});
