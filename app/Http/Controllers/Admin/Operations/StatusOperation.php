<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;

trait StatusOperation
{
    private $statusButton = 'custom_status';
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupStatusRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/{id}/status', [
            'as'        => $routeName.'.status',
            'uses'      => $controller.'@status',
            'operation' => 'status',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupStatusDefaults()
    {
        $this->crud->allowAccess('status');

        $this->crud->operation('status', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation(['list', 'show'], function () {
            $this->crud->addButtonFromView('line', 'status', $this->statusButton, 'beginning');
        });

    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function status($id)
    {
        $this->crud->hasAccessOrFail('status');
        
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $status = request()->status;

        // validate only accept this 3 values
        if (!in_array($status, [0,1,2])) { // pending, approved, denied
            return;
        }

        if ($id) {
            $item = classInstance($this->setModelStatusOperation())->findOrFail($id);
            $item->status = $status;
            return $item->save();
        }

        return;
    }

    // override this in your crud controller (optional)
    public function setModelStatusOperation()
    {
        return $this->crud->model->model;
        // return 'ModelClassNameHere';
    }

    private function statusOperationOptions()
    {
        return [
            0 => 'Pending', // pending
            1 => 'Approved',// approved
            2 => 'Denied',  // denied
        ];
    }

    private function statusOperationBadage()
    {
        return [
            0 => trans('lang.pending_badge'),
            1 => trans('lang.approved_badge'),
            2 => trans('lang.denied_badge'),
        ];
    }

    private function statusOperationSearchLogic($searchTerm)
    {
        $searchTerm = strtolower($searchTerm);
        $value = null;
        if ( str_contains('approved', $searchTerm) ) {
            
            // $query->orWhere('status', 1);
            $value = 1;

        }else if ( str_contains('denied', $searchTerm) ) {
            
            // $query->orWhere('status', 2);
            $value = 2;

        }else if ( str_contains('pending', $searchTerm) ) {
            
            // $query->orWhere('status', 0);
            $value = 0;

        }else {
            // do nothing
        }

        return $value;
    }
}
