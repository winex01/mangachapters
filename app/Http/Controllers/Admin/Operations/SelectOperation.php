<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;

trait SelectOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupSelectRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/{id}/select', [
            'as'        => $routeName.'.select',
            'uses'      => $controller.'@select',
            'operation' => 'select',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupSelectDefaults()
    {
        $this->crud->allowAccess('select');

        $this->crud->operation('select', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation(['list', 'show'], function () {
            $this->crud->addButtonFromView('line', 'select', 'custom_select', 'beginning');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function select($id)
    {
        $this->crud->hasAccessOrFail('select');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        if ($id) {
            // i fetch first all the item instance and loop then save so the event listener would get trigger
            $items = classInstance($this->setModelSelectOperation())->where('id', '!=', $id)->get();
            foreach ($items as $item) {
                $item->selected = 0;
                $item->save();
            }

            $result = $this->crud->update($id, ['selected' => 1]);
            
            if (!empty($result)) {
                return true;
            }
        }

        return;
    }

    // override this in your crud controller (optional)
    public function setModelSelectOperation()
    {
        return $this->crud->model->model;
        // return 'ModelClassNameHere';
    }
}