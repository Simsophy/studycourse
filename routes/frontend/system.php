<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
