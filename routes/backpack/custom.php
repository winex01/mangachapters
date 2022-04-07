<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    // allow login and use application and make email verification as optional only
    Route::post('/email/verification-notification', function () {
        auth()->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('backpack.dashboard');
    })->middleware(['auth', 'signed'])->name('verification.verify');
    
    Route::get('/redirect-here-when-email-verify-is-click', function () {
        return redirect()->route('backpack.dashboard');
    })->name('login');

    Route::crud('audittrail', 'AuditTrailCrudController');
    Route::crud('menu', 'MenuCrudController');
    Route::crud('manga', 'MangaCrudController');
    Route::crud('source', 'SourceCrudController');
    Route::crud('chapter', 'ChapterCrudController');
    Route::crud('scanfilter', 'ScanFilterCrudController');
    Route::crud('bookmark', 'BookmarkCrudController');
    Route::crud('dashboard', 'DashboardCrudController');
}); // this should be the absolute last line of this file