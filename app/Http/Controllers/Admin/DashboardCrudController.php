<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;
use Backpack\CRUD\app\Http\Controllers\CrudController;

/**
 * Class DashboardCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DashboardCrudController extends CrudController
{
    protected function setupModerateRoutes($segment, $routeName, $controller) {
        Route::post($segment.'/markAsReadNotification', [
            'as'        => $routeName.'.markAsReadNotification',
            'uses'      => $controller.'@markAsReadNotification',
            'operation' => 'markAsReadNotification',
        ]);

        Route::post($segment.'/markAllAsReadChapterNotification', [
            'as'        => $routeName.'.markAllAsReadChapterNotification',
            'uses'      => $controller.'@markAllAsReadChapterNotification',
            'operation' => 'markAllAsReadChapterNotification',
        ]);
    }

    public function markAsReadNotification()
    {
        $id = request()->id;

        if ($id) {
            return auth()->user()->unreadNotifications->where('id', $id)->markAsRead();
        }

        return;
    }

    public function markAllAsReadChapterNotification()
    {
        return auth()->user()->unreadNotifications->where('type', 'App\Notifications\NewChapterNotification')->markAsRead();
    }
    
}
