<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Legacy Frontend URL Compatibility
|--------------------------------------------------------------------------
|
| Older links may still point to static-style frontend URLs.
| Keep these redirects so existing bookmarks don't break.
|
*/

Route::redirect('/frontend/login.html', '/login', 302)->name('legacy.frontend.login');

Route::get('/locale/{locale}', function (Request $request, string $locale) {
    if (!in_array($locale, ['en', 'kh'], true)) {
        abort(404);
    }

    $request->session()->put('locale', $locale);

    return redirect()->back();
})->name('locale.switch');

Route::get('/', function () {
    return response()->view('auth.login');
});

Route::get('/system/portfolio', function () {
    return view('system.portfolio');
})->name('system.portfolio');
