<?php

use App\Http\Controllers\Admin\Courses\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\Contacts\ContactController as AdminContactPageController;
use App\Http\Controllers\Admin\CourseMaterialController;
use App\Http\Controllers\Admin\Auth\NewPasswordController as AdminNewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController as AdminPasswordResetLinkController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Users\UserController as AdminUserController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes (Frontend)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Login routes - accessible to everyone (even authenticated admins)
    Route::get('/login', [DashboardController::class, 'preLogin'])->name('login');
    Route::post('/login', [DashboardController::class, 'login'])
        ->middleware('throttle:auth-login')
        ->name('login.submit');

    // Password reset routes - guests only
    Route::middleware('guest:admin')->group(function () {
        Route::get('/forgot-password', [AdminPasswordResetLinkController::class, 'create'])
            ->name('password.request');
        Route::post('/forgot-password', [AdminPasswordResetLinkController::class, 'store'])
            ->middleware('throttle:auth-password-reset')
            ->name('password.email');

        Route::get('/reset-password', [AdminNewPasswordController::class, 'create'])
            ->name('password.reset');
        Route::post('/reset-password', [AdminNewPasswordController::class, 'store'])
            ->name('password.store');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('/logout', function () {
            return view('admin.auth.logout');
        })->name('logout.form');
        Route::post('/logout', [DashboardController::class, 'logout'])->name('logout');
    });

    Route::middleware(['admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashbord', [DashboardController::class, 'index'])->name('dashbord');
        Route::get('/panel', [DashboardController::class, 'index'])->name('panel');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        Route::get('/contacts', [AdminContactPageController::class, 'index'])->name('contacts.index');

        Route::resource('courses', AdminCourseController::class)->except(['show']);

        // Course Materials Management
        Route::prefix('courses/{course}')->name('courses.materials.')->group(function () {
            Route::get('/materials', [CourseMaterialController::class, 'index'])->name('index');
            Route::get('/materials/create', [CourseMaterialController::class, 'create'])->name('create');
            Route::post('/materials', [CourseMaterialController::class, 'store'])->name('store');
            Route::get('/materials/{material}/edit', [CourseMaterialController::class, 'edit'])->name('edit');
            Route::put('/materials/{material}', [CourseMaterialController::class, 'update'])->name('update');
            Route::delete('/materials/{material}', [CourseMaterialController::class, 'destroy'])->name('destroy');
            Route::get('/materials/{material}/download', [CourseMaterialController::class, 'download'])->name('download');
        });

        Route::get('/upload', [VideoController::class, 'create'])->name('videos.create');
        Route::post('/upload', [VideoController::class, 'store'])->name('videos.upload');
    });
});
