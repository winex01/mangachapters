<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;

trait RestoreReviseOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupRestoreReviseRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/{id}/restoreRevise', [
            'as'        => $routeName.'.restoreRevise',
            'uses'      => $controller.'@restoreRevise',
            'operation' => 'restoreRevise',
        ]);

        // bulk
        Route::post($segment.'/bulkRestoreRevise', [
            'as'        => $routeName.'.bulkRestoreRevise',
            'uses'      => $controller.'@bulkRestoreRevise',
            'operation' => 'bulkRestoreRevise',
        ]);

    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupRestoreReviseDefaults()
    {
        $this->crud->allowAccess('restoreRevise');

        $this->crud->operation('restoreRevise', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation(['list', 'show'], function () {
            $this->crud->addButtonFromView('line', 'restoreRevise',  'custom_restore_revise', 'end');
        });

        // bulk
        $this->crud->allowAccess('bulkRestoreRevise');

        $this->crud->operation('bulkRestoreRevise', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
            $this->crud->enableBulkActions();
            $this->crud->addButtonFromView('bottom', 'bulkRestoreRevise', 'custom_bulk_restore_revise', 'end');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function restoreRevise($id)
    {
        $this->crud->hasAccessOrFail('restoreRevise');

        $id = $this->crud->getCurrentEntryId() ?? $id;
        
        if (! $id) {
            abort(500, 'Can\'t restore revision without revision_id');
        } else {
            return $this->restoreItem($id);   
        }

        return;
    }

    public function bulkRestoreRevise()
    {
        $this->crud->hasAccessOrFail('bulkRestoreRevise');

        $entries = request()->input('entries');

        $returnEntries = [];
        foreach ($entries as $key => $id) {
            $returnEntries[] = $this->restoreItem($id);
        }

        return $returnEntries;
    }

    private function restoreItem($id)
    {
        $revision = \Venturecraft\Revisionable\Revision::findOrFail($id);

        $entry = $this->classInstance($revision->revisionable_type);

        // check if soft delete is enabled
        if ($entry->soft_deleting) {
            $entry = $entry->withTrashed()->findOrFail($revision->revisionable_id);
        }else {
            $entry = $entry->findOrFail($revision->revisionable_id);
        }

        // // Update the revisioned field with the old value
        return $entry->update([$revision->key => $revision->old_value]);
    }

}
