<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Notifications\ContactUsNotification;
use Illuminate\Support\Facades\Notification;
use Backpack\CRUD\app\Http\Controllers\CrudController;

/**
 * Class MessageCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MessageCrudController extends CrudController
{

    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupReplyContactUsRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/replyContactUs', [
            'as'        => $routeName.'.replyContactUs',
            'uses'      => $controller.'@replyContactUs',
            'operation' => 'replyContactUs',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupAddMangaDefaults()
    {
        if (hasAuthority('admin_reply_contact_us')) {

            $this->crud->allowAccess('replyContactUs');
            
            $this->crud->operation('replyContactUs', function () {
                $this->crud->loadDefaultOperationSettingsFromConfig();
            });
        }

    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function replyContactUs()
    {
        $this->crud->hasAccessOrFail('replyContactUs');

        if (auth()->check()) {
            $data = [
                'email' => auth()->user()->email,
                'name' => auth()->user()->name,
                'message' => request()->message,
                'auth_user' => auth()->user()->id,
            ];
            
            // send notification
            $user = User::find(request()->userId); 
            Notification::send($user, new ContactUsNotification($data));
        }

        return;
    }

}
