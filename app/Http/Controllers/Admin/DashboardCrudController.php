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
    }

    public function markAsReadNotification()
    {
        $ids = request()->ids;

        if ($ids) {
            return auth()->user()->unreadNotifications->whereIn('id', $ids)->markAsRead();
        }

        return;
    }
    
}
