<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Server Health Routes
|--------------------------------------------------------------------------
*/

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
    ]);
});
