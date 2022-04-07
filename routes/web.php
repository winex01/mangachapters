<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	// return redirect()->route('backpack.auth.login');
	if (auth()->user()) {
		return redirect()->route('backpack.dashboard');
	}

	return view('home');
});

Route::get('/redirect-here-when-email-verify-is-click', function () {
	return redirect()->route('backpack.dashboard');
})->name('login');