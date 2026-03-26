<?php

/*
|--------------------------------------------------------------------------
| Server/API Route Entry Point
|--------------------------------------------------------------------------
|
| To keep server concerns separated logically, this file now delegates
| to dedicated route files under routes/server.
|
*/

require __DIR__.'/server/health.php';
require __DIR__.'/server/contacts.php';
