<?php

use App\Http\Controllers\Student\CourseController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\StudentAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Student Routes (Frontend)
|--------------------------------------------------------------------------
*/

Route::prefix('user')->name('user.')->middleware(['auth', 'can:access-student-area'])->group(function () {
    Route::get('/logout', function () {
        return view('auth.logout');
    })->name('logout.form');

    Route::post('/logout', [StudentAuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/settings', [StudentController::class, 'settings'])->name('settings');
    Route::put('/settings', [StudentController::class, 'updateSettings'])->name('settings.update');

    Route::get('courses/{course}/lessons', [CourseController::class, 'index'])
        ->name('courses.lessons.index');

    Route::get('courses/{course}/resume', [CourseController::class, 'resume'])
        ->name('courses.resume');

    Route::get('lessons', [CourseController::class, 'allLessons'])->name('lessons.index');

    Route::get('lessons/{lesson}', [CourseController::class, 'show'])
        ->name('lessons.show');

    Route::get('lessons/{lesson}/download', [CourseController::class, 'download'])
        ->name('lessons.download');

    Route::post('courses/{course}/enroll', [CourseController::class, 'enroll'])
        ->name('courses.enroll');
});
