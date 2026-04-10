<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__.'/frontend/auth.php';
require __DIR__.'/frontend/admin.php';
require __DIR__.'/frontend/student.php';
require __DIR__.'/frontend/system.php';

