<?php

use Illuminate\Support\Facades\Route;
use App\Events\ResendEmailVerification;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\ContactController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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

Route::group(['middleware' => ['auth']], function() {

	Route::get('/email/verify', function () {
		
		if (auth()->user()->hasVerifiedEmail()) {
			abort(404);
		}
	
		return view(backpack_view('auth.verification-notice'));
	})->middleware('auth')->name('verification.notice');

	
	Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
		$request->fulfill();
		return redirect()->route('backpack.dashboard');
	})->middleware(['signed'])->name('verification.verify');
	
	
	Route::post('/email/verification-notification', function () {
		// auth()->user()->sendEmailVerificationNotification();
		event(new ResendEmailVerification(auth()->user()));
		return back()->with('message', 'Verification link sent!');
	})->middleware(['throttle:3,1'])->name('verification.send');
});


Route::group(['middleware' => ['guest']], function() {
	Route::get('/', [HomeController::class, 'index']); 
	Route::get('/about-us', [AboutUsController::class, 'index']);
	Route::get('/terms', [TermsController::class, 'index']);
	Route::get('/contact', [ContactController::class, 'index']);
	Route::post('/contact', [ContactController::class, 'store'])
			->middleware(['throttle:5,1'])
			->name('contact.send');
});

