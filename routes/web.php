<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\ContactController;

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


Route::group(['middleware' => 'guest'], function () { 
	Route::get('/', [HomeController::class, 'index']);
	Route::get('/about-us', [AboutUsController::class, 'index']);
	Route::get('/contact', [ContactController::class, 'index']);
	Route::get('/terms', [TermsController::class, 'index']);
}); 

Route::get('/redirect-here-when-email-verify-is-click', function () {
	return redirect()->route('backpack.dashboard');
})->name('login');