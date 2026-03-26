<?php

use App\Http\Controllers\Admin\Courses\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\Contacts\ContactController as AdminContactPageController;
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
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [DashboardController::class, 'preLogin'])->name('login');
        Route::post('/login', [DashboardController::class, 'login'])
            ->middleware('throttle:auth-login')
            ->name('login.submit');
    });

    Route::middleware(['auth:admin', 'can:access-admin-panel'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashbord', [DashboardController::class, 'index'])->name('dashbord');
        Route::get('/panel', [DashboardController::class, 'index'])->name('panel');
        Route::get('/logout', function () {
            return view('admin.auth.logout');
        })->name('logout.form');
        Route::post('/logout', [DashboardController::class, 'logout'])->name('logout');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        Route::get('/contacts', [AdminContactPageController::class, 'index'])->name('contacts.index');

        Route::resource('courses', AdminCourseController::class)->except(['show']);
        Route::get('/upload', [VideoController::class, 'create'])->name('videos.create');
        Route::post('/upload', [VideoController::class, 'store'])->name('videos.upload');
    });
});
