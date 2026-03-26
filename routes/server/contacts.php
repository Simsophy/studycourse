<?php

use App\Http\Controllers\Api\AdminContactController;
use App\Http\Controllers\Api\UserContactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Contact API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('user')->middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/contacts', [UserContactController::class, 'index']);
    Route::post('/contacts', [UserContactController::class, 'store']);
    Route::get('/contacts/{contact}', [UserContactController::class, 'show']);
    Route::put('/contacts/{contact}', [UserContactController::class, 'update']);
    Route::delete('/contacts/{contact}', [UserContactController::class, 'destroy']);
});

Route::prefix('admin')->middleware(['auth:admin', 'can:access-admin-panel', 'throttle:60,1'])->group(function () {
    Route::get('/contacts', [AdminContactController::class, 'index']);
    Route::get('/contacts/statistics', [AdminContactController::class, 'statistics']);
    Route::get('/contacts/{contact}', [AdminContactController::class, 'show']);
    Route::post('/contacts/{contact}/reply', [AdminContactController::class, 'reply']);
    Route::patch('/contacts/{contact}/mark-read', [AdminContactController::class, 'markAsRead']);
    Route::delete('/contacts/{contact}', [AdminContactController::class, 'destroy']);
});
