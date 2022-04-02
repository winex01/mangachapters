<?php 

namespace App\Http\Controllers\Admin\Traits;

use Illuminate\Support\Str;

/**
 * NOTE:: Global filters are located at CrudExtendTrait
 */
trait FilterTrait
{
    public function simpleFilter($col, $value = 1)
    {
        $this->crud->addFilter([
            'type'  => 'simple',
            'name'  => $col,
            'label' => convertColumnToHumanReadable($col),
        ], 
        false, 
        function() use($col, $value) { // if the filter is active
            $this->crud->query->where($col, '=', $value); // apply the "active" eloquent scope 
        } );
    }

    public function booleanFilter($col, $options = null)
    {
        if ($options == null) {
            $options = [ 
                0 => 'No', 
                1 => 'Yes'
            ];
        }
        $this->crud->addFilter([
          'name'  => $col,
          'label' => convertColumnToHumanReadable($col),
          'type'  => 'dropdown',
        ], 
        $options, 
        function($value) use ($col) { // if the filter is active
            $this->crud->addClause('where', $col, $value);
        });
    }

    public function select2Filter($col, $orderBy = 'name')
    {
        $method = relationshipMethodName($col);
        if (method_exists($this->crud->model, $method)) {
            $this->crud->addFilter([
                    'name'  => $method,
                    'type'  => 'select2',
                    'label' => convertColumnToHumanReadable($method),
                ],
                classInstance($method)::orderBy($orderBy)->pluck('name', 'id')->toArray(),
                function ($value) use ($method){ 
                     $col = \Str::snake($method).'_id';
                     $this->crud->addClause('where', $col, $value); 
                }
            );
        }//end if
    }

    public function select2FromArrayFilter($col, $options = [])
    {
        $this->crud->addFilter([
            'name' => $col,
            'type' => 'select2', 
            'label' => convertColumnToHumanReadable($col),
        ], 
        $options,
        function ($value) use ($col) { // if the filter is active
            $this->crud->query->where($col, '=', $value);
        });
    }

    public function select2MultipleFromArrayFilter($name, $options = [], $label = null)
    {
        $method = str_replace('add_scope_json_params_', '', $name);

        if ($label == null) {
            $label = str_replace('whereIn', '', $method);
            $label = convertColumnToHumanReadable($label);
        } 

        $this->crud->addFilter([
            'name' => $name,
            'type' => 'select2_multiple', 
            'label' => $label,
        ], 
        $options,
        function($values) use ($method) { // if the filter is active
            $this->crud->query->{$method}(json_decode($values));
        });
    }
    
    public function dateRangeFilter($col, $label = null)
    {
        $this->crud->addFilter([
            'name'  => 'date_range_filter_'.$col,
            'type'  => 'date_range',
            'label' => $label ?? convertColumnToHumanReadable($col),
        ],
        false,
        function ($value) use ($col) { // if the filter is active, apply these constraints
            $dates = json_decode($value);
            $table = $this->crud->model->getTable();
            $this->crud->query->whereBetween($table.'.'.$col, [$dates->from, $dates->to]);
        });
    }

    public function removeGlobalScopeFilter($scope, $label = null)
    {
        if ($label == null) {
          $label = str_replace('Current', '', $scope);
          $label = str_replace('Scope', '', $label);
          $label = str_replace('_', ' ', Str::snake($label));
          $label = $label . ' History';
          $label = ucwords($label);
        }

        $this->crud->addFilter([
            // 'type'  => 'simple',
            'type'  => 'custom_simple_hide_bottom_buttons',
            'name'  => 'remove_scope_'.$scope,
            'label' => $label
        ], 
        false, 
        function() use ($scope) { // if the filter is active
            $this->crud->query->withoutGlobalScope($scope);
            $this->crud->denyAccess(lineButtons());
        });
    }

    public function yearMonthFilter($col = 'year_month', $model = null)
    {   
        if ($model == null) {
            $model = $this->crud->model->model;
        }

        $this->crud->addFilter([
            'name' => $col,
            'type' => 'select2', 
            'label' => convertColumnToHumanReadable($col),
        ], 
        modelInstance($model)->select($col)->groupBy($col)->orderBy($col, 'DESC')->pluck($col, $col)->all(),
        function ($value) use ($col) { // if the filter is active
            $this->crud->query->where($col, '=', $value);
        });
    }
}