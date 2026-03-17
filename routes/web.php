<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Student\CourseController;
use App\Http\Controllers\Admin\Users\UserController as AdminUserController;

use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Courses\CourseController as AdminCourseController;
use App\Models\Admin;
use App\Http\Controllers\VideoController;
/*
|--------------------------------------------------------------------------
| Global Student Auth Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [StudentAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [StudentAuthController::class, 'login']);

    Route::get('/register', [StudentAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [StudentAuthController::class, 'register']);

});


/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

Route::prefix('user')->name('user.')->middleware('auth')->group(function () {
 Route::post('/logout', [StudentAuthController::class, 'logout'])->name('logout');
    // Dashboard
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');

    // Show lessons of a course
    Route::get('courses/{course}/lessons', [CourseController::class, 'index'])
        ->name('courses.lessons.index');  // route name: user.courses.lessons.index

  

    // Show all lessons for a specific course (course page)
Route::get('lessons', [CourseController::class, 'allLessons'])->name('lessons.index');

    // Show a single lesson
    Route::get('lessons/{lesson}', [CourseController::class, 'showLesson'])
        ->name('lessons.show');

    // Enroll in a lesson
    Route::post('lessons/{lesson}/enroll', [CourseController::class, 'enroll'])
        ->name('lessons.enroll');
});
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // First page: dashboard pre-login (guest admin)
    Route::get('/dashboard', [DashboardController::class, 'preLogin'])->name('dashboard');

    // Login action
    Route::post('/dashboard/login', [DashboardController::class, 'login'])->name('login.submit');

    // Second page: authenticated admin panel
    Route::middleware('auth:admin')->group(function () {
        Route::get('/panel', [DashboardController::class, 'index'])->name('panel'); // full panel
        Route::post('/logout', [DashboardController::class, 'logout'])->name('logout');

         // Users page
   Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');


       Route::get('courses', [AdminCourseController::class, 'index'])->name('courses.index'); // optional list
    Route::get('courses/create', [AdminCourseController::class, 'create'])->name('courses.create'); // optional form
   Route::get('admin/courses/{course}/edit', [AdminCourseController::class, 'edit'])->name('admin.courses.edit');
Route::put('admin/courses/{course}', [AdminCourseController::class, 'update'])->name('admin.courses.update');
    Route::resource('courses', AdminCourseController::class);
    Route::post('courses', [AdminCourseController::class, 'store'])->name('courses.store'); // store new course
    Route::get('/upload', [VideoController::class, 'create'])->name('videos.create');
Route::post('/upload', [VideoController::class, 'store'])->name('videos.upload');
    Route::delete('courses/{course}', [AdminCourseController::class, 'destroy'])->name('courses.destroy'); // delete
    });
});