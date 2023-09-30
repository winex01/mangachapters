<?php

use Illuminate\Support\Facades\Route;

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
    Route::crud('audittrail', 'AuditTrailCrudController');
    Route::crud('menu', 'MenuCrudController');
    Route::crud('manga', 'MangaCrudController');
    Route::crud('source', 'SourceCrudController');
    Route::crud('chapter', 'ChapterCrudController');
    Route::crud('scanfilter', 'ScanFilterCrudController');
    Route::crud('bookmark', 'BookmarkCrudController');
    Route::crud('dashboard', 'DashboardCrudController');
    Route::crud('auth', 'AuthCrudController');
    Route::crud('notification', 'NotificationCrudController');
    Route::crud('message', 'MessageCrudController');
}); // this should be the absolute last line of this file