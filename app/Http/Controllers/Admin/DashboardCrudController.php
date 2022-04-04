<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DashboardCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DashboardCrudController extends CrudController
{
    protected function setupModerateRoutes($segment, $routeName, $controller) {
        Route::post($segment.'/{id}/markAsReadNotification', [
            'as'        => $routeName.'.markAsReadNotification',
            'uses'      => $controller.'@markAsReadNotification',
            'operation' => 'markAsReadNotification',
        ]);

        Route::post($segment.'/clearAllNotification', [
            'as'        => $routeName.'.clearAllNotification',
            'uses'      => $controller.'@clearAllNotification',
            'operation' => 'clearAllNotification',
        ]);
    }

    public function markAsReadNotification($id)
    {
        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        if ($id) {
            return auth()->user()->unreadNotifications->where('id', $id)->markAsRead();
        }

        return;
    }

    public function clearAllNotification()
    {

        return auth()->user()->unreadNotifications->markAsRead();

    }
}
