<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/payments/khqr/generate', [\App\Http\Controllers\Api\DynamicKHQRController::class, 'generate']);
