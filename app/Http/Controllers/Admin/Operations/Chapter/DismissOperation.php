<?php

namespace App\Http\Controllers\Admin\Operations\Chapter;

use Illuminate\Support\Facades\Route;

trait DismissOperation
{
    private $dismissButton = 'chapters.custom_dismiss';

    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupDismissRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/{id}/dismiss', [
            'as'        => $routeName.'.dismiss',
            'uses'      => $controller.'@dismiss',
            'operation' => 'dismiss',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupDismissDefaults()
    {
        $this->crud->allowAccess('dismiss');

        $this->crud->operation('dismiss', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });


        $this->crud->operation(['list', 'show'], function () {
            $this->crud->addButtonFromView('line', 'dismiss', $this->dismissButton, 'beginning');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function dismiss($id)
    {
        $this->crud->hasAccessOrFail('dismiss');

        $id = $this->crud->getCurrentEntryId() ?? $id;

        $item = classInstance($this->crud->model->model)->findOrFail($id);
        $item->dismiss = true;
        return $item->save();
    }
}
