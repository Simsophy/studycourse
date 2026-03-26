<?php

/*
|--------------------------------------------------------------------------
| Frontend Route Entry Point
|--------------------------------------------------------------------------
|
| To keep frontend concerns separated logically, this file now delegates
| to dedicated route files under routes/frontend.
|
*/

require __DIR__.'/frontend/system.php';
require __DIR__.'/frontend/auth.php';
require __DIR__.'/frontend/student.php';
require __DIR__.'/frontend/admin.php';