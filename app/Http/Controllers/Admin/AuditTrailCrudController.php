<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AuditTrailCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AuditTrailCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;
    use \App\Http\Controllers\Admin\Operations\RestoreReviseOperation;
    use \App\Http\Controllers\Admin\Operations\ExportOperation; 
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\AuditTrail::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/audittrail');

        $this->userPermissions('audit_trails');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->showData();

        // filter user
        $this->crud->addFilter([
                'name'  => 'user',
                'type'  => 'select2',
                'label' => trans('lang.filter_user'),
            ],
            \App\Models\User::withTrashed()->pluck('name', 'id')->toArray(),
            function ($value) { // if the filter is active
                $this->crud->addClause('whereHas', 'user', function ($query) use ($value) {
                    $query->where('user_id', '=', $value);
                });
            }
        );

        // filter model
        $this->crud->addFilter([
          'name'  => 'model',
          'type'  => 'dropdown',
          'label' => trans('lang.model')
        ], function () {
            $audit = \App\Models\AuditTrail::select('revisionable_type')
                    ->groupBy('revisionable_type')
                    ->pluck('revisionable_type');

            $audit = $audit->mapWithKeys(function ($item) {
                $value = str_replace('App\\Models\\', '', $item);
                return [
                  $value => ucwords(str_replace('_', ' ', \Str::snake($value)))
                ];
            });

            return $audit->toArray();
        }, function ($value) { // if the filter is active
            $this->crud->addClause('where', 'revisionable_type', 'LIKE', "%$value%");
        });
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false);
        $this->showData();

        $id = $this->crud->getCurrentEntryId() ?? $id;

        $revision = \Venturecraft\Revisionable\Revision::findOrFail($id);

        $model = classInstance($revision->revisionable_type)
          ->findOrFail($revision->revisionable_id);

        $this->crud->addColumn([
            'label' => ucwords('model latest value'),
            'type'  => 'custom_var_dump_model',
            'value' => $model,
        ]);
    }

    private function showData()
    {
        $columns = [
            'user_id',
            'key',
            'old_value',
            'new_value',
            'change',
            'revisionable_type',
            'revisionable_id',
        ];

        $this->crud->removeColumns($columns);

        foreach ($columns as $column) {
            if ($column == 'user_id') {
                $this->crud->addColumn('user', [
                    'name'      => 'user',
                    'attribute' => 'name',
                ]);

                continue; //exit foreach
            }

            $this->crud->addColumn($column, [
                'name' => $column,
            ]);
        }

        // modify unsearchable column label
        $this->crud->modifyColumn('change', [
            'label' => 'Change'.trans('lang.unsortable_column'),
        ]);

        $this->crud->modifyColumn('user', [
            'label' => 'User'.trans('lang.unsortable_column'),
        ]);
        
    }
}
