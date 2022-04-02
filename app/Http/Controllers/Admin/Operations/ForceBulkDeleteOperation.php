<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;

trait ForceBulkDeleteOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupForceBulkDeleteRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/forceBulkDelete', [
            'as'        => $routeName.'.forceBulkDelete',
            'uses'      => $controller.'@forceBulkDelete',
            'operation' => 'forceBulkDelete',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupForceBulkDeleteDefaults()
    {
        $this->crud->allowAccess('forceBulkDelete');

        $this->crud->operation('forceBulkDelete', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
            $this->crud->enableBulkActions();
            $this->crud->addButtonFromView('bottom', 'forceBulkDelete', 'custom_force_bulk_delete', 'end');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function forceBulkDelete()
    {
        $this->crud->hasAccessOrFail('forceBulkDelete');

        $entries = request()->input('entries');
        $deletedEntries = [];

        foreach ($entries as $key => $id) {
            if ($entry = $this->crud->model::findOrFail($id)) {
                $deletedEntries[] = $entry->forceDelete();
            }
        }

        return $deletedEntries;
    }
}
