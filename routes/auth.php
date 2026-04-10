<?php

use App\Http\Controllers\Admin\Auth\NewPasswordController as AdminNewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController as AdminPasswordResetLinkController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/forgot-password', [AdminPasswordResetLinkController::class, 'create'])
        ->middleware('guest')
        ->name('password.request');

    Route::post('/forgot-password', [AdminPasswordResetLinkController::class, 'store'])
        ->middleware('guest')
        ->name('password.email');

    Route::get('/reset-password', [AdminNewPasswordController::class, 'create'])
        ->middleware('guest')
        ->name('password.reset');

    Route::post('/reset-password', [AdminNewPasswordController::class, 'store'])
        ->middleware('guest')
        ->name('password.store');
});

/*
|--------------------------------------------------------------------------
| User Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Google OAuth
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])
        ->name('google.redirect');

    Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])
        ->name('google.callback');
});

/*
|--------------------------------------------------------------------------
| Email Verification Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

/*
|--------------------------------------------------------------------------
| Logout Route
|--------------------------------------------------------------------------
*/
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
