<?php 

namespace App\Http\Controllers\Admin\Traits;

use Illuminate\Support\Str;

/**
 * import in backpack crud controller
 * use in backpack crud controller
 */
trait CrudExtendTrait
{
    /*
    |--------------------------------------------------------------------------
    | Check Auth User Permissions
    |--------------------------------------------------------------------------
    */ 
    public function userPermissions($role = null)
    {
        // check access for current role & admin
        $this->checkAccess($role);
        $this->checkAccess('admin');

         // filters
         // commented bec. i transfered this to showColumn, to avoid getting called two times when inline create operation is imported
        // bec. filter should be put in setupListOperation instead of setup
         // $this->adminFilters(); 

        // rename entry label and button
        $this->crud->setEntityNameStrings($this->buttonLabel(), $this->entryLabel());

        // show always column visibility button
        $this->crud->enableExportButtons();

        // dont include items that has relationship soft deleted
        foreach ($this->hasRelationshipTo() as $temp) {
            if (method_exists($this->crud->model, $temp) && $this->crud->model->getTable() != 'users') { // users crud allow nullable employee_id
                $this->crud->addClause('has', $temp); 
            }
        }
    }

    public function adminFilters()
    {
        $filters = $this->crud->filters()->pluck('name')->toArray();
        
        // if filters already exist, dont add
        if (!in_array('trashed', $filters)) {
            $this->trashedFilter();
        }

        if (!in_array('employee', $filters)) {
            $this->employeeFilter();
        }
    }

    // dont include items that has relationship soft deleted
    public function hasRelationshipTo()
    {
        return [
            'employee'
        ];
    }

    private function employeeFilter($column = 'employee_id')
    {
        // show filter employee if model belongs to emp model
        if (method_exists($this->crud->model, 'employee') || $column == 'id') {
            $this->crud->addFilter([
                    'name'  => 'employee',
                    // 'type'  => 'custom_employee_filter',
                    'type'  => 'select2',
                    'label' => 'Select Employee',
                ],
                function () {
                  return employeeLists();
                },
                function ($value) use ($column) { // if the filter is active
                    $this->crud->query->where($column, $value);
                }
            );

        }//end if
    }

    private function employeeMultipleFilter($column = 'employee_id')
    {
        // show filter employee if model belongs to emp model
        if (method_exists($this->crud->model, 'employee') || $column == 'id') {
            $this->crud->addFilter([
                    'name'  => 'employee_multiple',
                    // 'type'  => 'custom_employee_filter',
                    'type'  => 'select2_multiple',
                    'label' => 'Select Multiple Employee',
                ],
                function () {
                  return employeeLists();
                },
                function ($values) use ($column) { // if the filter is active
                    $this->crud->query->whereIn($column, json_decode($values));
                }
            );

        }//end if
    }

    private function trashedFilter()
    {
        // filter deleted
        if (hasAuthority('admin_trashed_filter')) {
            // if soft delete is enabled
            if ($this->crud->model->soft_deleting) {
                $this->crud->addFilter([
                  // 'type'  => 'simple',
                  'type'  => 'custom_simple_hide_bottom_buttons',
                  'name'  => 'trashed',
                  'label' => 'Trashed'
                ],
                false,
                function($values) { // if the filter is active
                    $this->crud->query = $this->crud->query->onlyTrashed();
                    $this->crud->denyAccess(lineButtons());
                });
            }//end if soft delete enabled
        }//end hasAuth
    }

    private function checkAccess($role)
    {
        $role = ($role == null) ? $this->crud->model->getTable() : $role;

        $allRolePermissions = \App\Models\Permission::where('name', 'LIKE', "$role%")
                            ->pluck('name')->map(function ($item) use ($role) {
                                $value = str_replace($role.'_', '', $item);
                                $value = Str::camel($value);
                                return $value;
                            })->toArray();

        // deny all access first
        // debug($allRolePermissions);
        $this->crud->denyAccess($allRolePermissions);

        $permissions = auth()->user()->getAllPermissions()
            ->pluck('name')
            ->filter(function ($item) use ($role) {
                return false !== stristr($item, $role);
            })->map(function ($item) use ($role) {
                $value = str_replace($role.'_', '', $item);
                $value = Str::camel($value);
                return $value;
            })->toArray();

        // allow access if user have permission
        // debug($permissions);
        $this->crud->allowAccess($permissions);
    }

    /*
    |--------------------------------------------------------------------------
    | Fields
    |--------------------------------------------------------------------------
    */
    public function addHiddenField($attribute, $value)
    {
        $this->crud->getRequest()->request->add([$attribute => $value]);
        $this->crud->addField(['type' => 'hidden', 'name' => $attribute]);
    }

    public function addHintField($col, $hint)
    {
        $this->crud->modifyField($col, [   
            'hint' => $hint
         ]);
    }

    public function addSelectFromArrayField($col, $options)
    {
        $this->crud->modifyField($col, [   // select_from_array
            'type'        => 'select2_from_array',
            'options'     => $options,
            'allows_null' => true,
            // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
        ]);
    }

    public function addTimestampField($col)
    {
        $this->crud->modifyField($col, [
            'type'    => 'datetime',
        ]);
    }

    public function transferFieldAfter($field, $afterField, $type = 'text')
    {
        $table = $this->crud->model->getTable();

        $this->crud->removeField($field);
        $this->crud->addField([
            'name'        => $field,
            'label'       => convertColumnToHumanReadable($field),
            'type'        => $type,
            'attributes'  => [
                'placeholder' => trans('lang.'.$table.'_'.$field)
            ]
        ])->afterField($afterField);
    }

    public function addRelationshipField($field, $entity = null, $model = null, $attribute = 'name')
    {
        if ($entity == null) {
            $entity = relationshipMethodName($field);
        }

        if ($model == null) {
            $model  = "App\Models\\".ucfirst(relationshipMethodName($field));
        }

        $this->crud->modifyField($field, [
            'type' => 'select2',
            'entity'    => $entity, 
            'model'     => $model, // related model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'allows_null' => true
        ]);
    }

    public function addBooleanField($col, $options = null)
    {
        if ($options == null) {
            $options = booleanOptions();
        } 

        $this->crud->modifyField($col, [
            'type'    => 'radio',
            'label'   => convertColumnToHumanReadable($col),
            'default' => 0,
            'options' => $options,
        ]);
    }

    public function addInlineCreatePivotField($field, $entity = null, $permission = null, $dataSource = null)
    {
        $permission = ($permission == null) ? str_plural($field).'_create' : $permission;
        $entity = ($entity == null) ? str_singular($field) : $entity;

        if ($dataSource == null) {
            $crudModel = strtolower($this->crud->model->model);
            $dataSource = route($crudModel.'.fetch'.ucwords($entity));
        }

        $table = $this->crud->model->getTable();

        return $this->crud->addField([
            'name'          => $field,
            'label'         => convertColumnToHumanReadable($field),
            'type'          => 'relationship',
            'ajax'          => false,
            'allows_null'   => true,
            'placeholder'   => trans('lang.select_placeholder'), 
            'inline_create' => hasAuthority($permission) ? ['entity' => $entity] : null,
            'data_source'   => url($dataSource), 
            'hint'          => trans('lang.'.$table.'_'.$field.'_hint'),
            'placeholder'   => trans('lang.select_placeholder'),
            'multiple'      => true,
        ]);
    }

    public function addInlineCreateField($columnId, $entity = null, $permission = null)
    {
        $col = str_replace('_id', '', $columnId);
        $permission = ($permission == null) ? Str::plural($col).'_create' : $permission;
        $entity = ($entity == null) ? str_replace('_', '', $col) : $entity;

        $this->crud->modifyField($columnId, [
            'label'         => convertColumnToHumanReadable($col),
            'type'          => 'relationship',
            'ajax'          => false,
            'allows_null'   => true,
            'placeholder'   => trans('lang.select_placeholder'), 
            'inline_create' => hasAuthority($permission) ? ['entity' => $entity] : null,

            // need for camel case relationship name, ex: civilStatus
            'model'         => 'App\Models\\'.convertToClassName($columnId),
            'entity'        => relationshipMethodName($columnId),
            'relation_type' => 'BelongsTo',
            'multiple'      => false,
        ]);
    }

    public function addSelectEmployeeField($field = 'employee_id')
    {   
        $this->crud->removeField($field);
        $temp = $this->crud->addField([
            'name'          => $field, 
            'label'         => convertColumnToHumanReadable($field),
            'type'          => 'relationship',
            'attribute'     => 'full_name_with_badge',
            'ajax'          => false,
            'allows_null'   => true,
            'placeholder'   => trans('lang.select_placeholder'), 
            'inline_create' => null
        ]);//->makeFirstField();

        if ($field == 'employee_id') {
            $temp->makeFirstField();
        }
        
        return $temp;
    }

    public function currencyField($fieldName)
    {
        $this->crud->modifyField($fieldName, [
            'type'        => 'number',
            'prefix'      => trans('lang.currency'),
            'attributes'  => [
                'step'        => config('appsettings.inputbox_decimal_precision'),
                'placeholder' => 'Enter Amount'
            ],
        ]);
    }

    public function addAttachmentField($fieldName = 'attachment')
    {
        // attachment field
        $this->crud->modifyField($fieldName, [
            'type'      => 'upload',
            'upload'    => true,
            'disk'      => 'public', 
            'hint'      => 'File attachment limit is <b>'.
                            convertKbToMb(config('settings.appsettings_attachment_file_limit'))
                            .'MB</b>',   
        ]);
    }

    public function inputs($table = null, $tab = null, $removeOthers = null)
    {
        if ($table == null) {
            $table = $this->crud->model->getTable();
        }

        $columns = getTableColumnsWithDataType($table, $removeOthers);
        
        foreach ($columns as $col => $dataType) {

            if ($dataType == 'tinyint') {
                // boolean
                $this->crud->addField([
                    'name'        => $col,
                    'label'       => convertColumnToHumanReadable($col),
                    'type'        => 'radio',
                    'default' => 0,
                    'options' => booleanOptions(),
                    'tab'         => $tab,
                ]);

                continue;
            }

            $type = $this->fieldTypes()[$dataType];

            if ($dataType == 'date') {
                // if dataType is date then dont use in fieldTypes
                // bec. thats prefer for showColumns, field must be
                // date in field.
                // $type = 'date';
                // $type = 'date_picker';
                $type = $this->dateFieldType();
            }

            $this->crud->addField([
                'name'        => $col,
                'label'       => convertColumnToHumanReadable($col),
                'type'        => $type,
                'tab'         => $tab,
                'attributes'  => [
                    'placeholder' => trans('lang.'.$table.'_'.$col)
                ]
            ]);
        }

    }

    public function dateFieldType()
    {
        // return 'date_picker';
        return 'date';
    }

    // NOTE:: this prioritize showColumns
    public function fieldTypes()
    {
        $fieldType = [
            'varchar'   => 'text',
            'timestamp' => 'text',
            'json'      => 'table',
            'text'      => 'textarea',
            'double'    => 'number',
            'float'     => 'number',
            'decimal'   => 'number',
            'bigint'    => 'number',
            'int'       => 'number',
            'smallint'  => 'number',
            'tinyint'   => 'boolean',
            'date'      => config('appsettings.date_column_format'), // if input field = date
        ];

        return $fieldType;
    }

    public function reorderFields()
    {
        return [
            'parent_id',
            'lft',
            'rgt',
            'depth',
        ];
    }
    /*
    |--------------------------------------------------------------------------
    | Columns Related Stuff
    |--------------------------------------------------------------------------
    */
    public function addListColumn($col)
    {
        // NOTE:: model attribute should have prefix getSampleListColumnAttribute. (sample)
        return $this->crud->addColumn([
                    'name' => $col,
                    'label' => convertColumnToHumanReadable($col),
                    'type' => 'closure',
                    'function' => function($entry) use ($col) {
                        return $entry->{$col.'ListColumn'};
                    },
                ]);
    }

    public function addColumn($col)
    {
        return $this->crud->addColumn(['name' => $col]);
    }

    public function limitColumn($col, $limit = null)
    {
        $this->removeColumn($col);
        return $this->crud->addColumn([
            'name' => $col,
            'limit' => $limit,
        ]);
    }

    public function modifyColumnAsClosure($col, $relationshipOrWithAccessor)
    {
        return $this->crud->modifyColumn($col, [
            'type'     => 'closure',
            'function' => function($entry) use ($relationshipOrWithAccessor) {
                if (is_array($relationshipOrWithAccessor)) {
                    $value = $entry;
                    foreach ($relationshipOrWithAccessor as $temp) {
                        $value = $value->{$temp};
                    }

                    return $value;
                }
                return $entry->{$relationshipOrWithAccessor};
            } 
        ]);
    }


    /**
     * @param string $col string or array.
     */
    public function accessorColumn($col, $label = null)
    {
        if ($label == null) {
            $label = convertColumnToHumanReadable($col);
        }

        return $this->crud->addColumn([
            'name'     => $col,
            'label'    => $label,
            'type'     => 'closure',
            'function' => function($entry) use ($col) {
                if (is_array($col)) {
                    $value = $entry;
                    foreach ($col as $temp) {
                        $value = $value->{$temp};
                    }

                    return $value;
                }
                return $entry->{$col};
            }
        ]);
    }
    
    // Alias to accessorColumn
    public function closureColumn($col)
    {
        return $this->accessorColumn($col);
    }

    public function removeColumn($cols)
    {
        $cols = (!is_array($cols)) ? (array) $cols : $cols; // convert params to array
        $this->crud->removeColumns($cols);
    }

    public function removeColumns($cols)
    {
        $this->removeColumn($cols);
    }

    public function showColumnFromArray($col, $options)
    {   
        $this->crud->modifyColumn($col, [
            'type' => 'closure',
            'function' => function($entry) use ($col, $options) {
                return $options[$entry->{$col}];
            }
        ]);
    }

    public function showColumnClosure($col, $accessor)
    {
        $this->crud->modifyColumn($col, [
            'type' => 'closure',
            'function' => function($entry) use ($accessor) {
                return $entry->{$accessor};
            }
        ]);
    }

    public function showColumnFromArrayLists($col, $arrays)
    {
        $this->crud->modifyColumn($col, [
            'type' => 'closure',
            'function' => function($entry) use ($col, $arrays) {
                return $arrays[$entry->{$col}];
            }
        ]);
    }

    public function convertColumnToDouble($col, $precision = 2)
    {
        $this->crud->modifyColumn($col, [
            'type'  => 'number',
            'decimals' => $precision // modified this column bec. of leave_credit field type = number
        ]);
    }

    public function addColumnTitle($col, $title = 'description', $class = null, $addTitleUsingArray = [])
    {
        if ($class == null) {
            $class = trans('lang.column_title_text_color');
        }

        $this->crud->modifyColumn($col, [
            'wrapper'   => [
                'span' => function ($crud, $column, $entry, $related_key) use ($col) {
                    return $entry->{$col};
                },
                'title' => function ($crud, $column, $entry, $related_key) use ($col, $title, $addTitleUsingArray) {
                    if ($addTitleUsingArray) {
                        return $addTitleUsingArray[$entry->{$col}];
                    }
                    return $entry->{relationshipMethodName($col)}->$title;
                },
                'class' => $class
            ],
        ]);
    }

    public function booleanColumn($col, $true = 'Open', $false = 'Close', $falseBadgeClass = 'default', $trueBadgeClass = 'success')
    {
        $this->crud->modifyColumn($col, [
            'wrapper' => [
                'element' => 'span',
                'class' => function ($crud, $column, $entry, $related_key) use ($true, $falseBadgeClass, $trueBadgeClass) {
                    if ($column['text'] == $true) {
                        return 'badge badge-'.$trueBadgeClass;
                    }
                    return 'badge badge-'.$falseBadgeClass;
                },
            ],
            'options' => [0 => $false, 1 => $true]
        ]);
    }

    public function renameLabelColumn($column, $newLabel)
    {
        $this->crud->modifyColumn($column, [
            'label' => $newLabel
        ]);
    }

    public function disableSortColumn($col)
    {
        $this->crud->modifyColumn($col, [
            'orderable'  => false,
        ]);
    }

    public function showRelationshipPivotColumn($column, $entity = null, $model = null, $attribute = 'name', $limit = 1000)
    {
        if ($entity == null) {
            $entity = relationshipMethodName($column);
        }

        if ($model == null) {
            $model  = "App\Models\\".ucfirst(relationshipMethodName($column));
        }

        return $this->crud->addColumn([
            // n-n relationship (with pivot table)
            'label'     => convertColumnToHumanReadable($column), // Table column heading
            'type'      => 'select_multiple',
            'name'      => $column, // the method that defines the relationship in your Model
            'entity'    => $entity, // the method that defines the relationship in your Model
            'attribute' => $attribute, // foreign key attribute that is shown to user
            'model'     => $model, // foreign key model
            'limit'     => $limit, // default no limit
        ]);
    }

    public function showRelationshipColumn($columnId, $relationshipColumn = 'name')
    {
        $col = str_replace('_id', '', $columnId);
        $method = relationshipMethodName($col);
        $currentTable = $this->crud->model->getTable();

        return $this->crud->modifyColumn($columnId, [
           'label' => convertColumnToHumanReadable($col),
           'type'     => 'closure',
            'function' => function($entry) use ($method, $relationshipColumn) {
                if ($entry->{$method} == null) {
                    return;
                }
                return $entry->{$method}->{$relationshipColumn};
            },
            'searchLogic' => function ($query, $column, $searchTerm) use ($method, $relationshipColumn) {
                $query->orWhereHas($method, function ($q) use ($column, $searchTerm, $relationshipColumn) {
                    $q->where($relationshipColumn, 'like', '%'.$searchTerm.'%');
                });
            },
            'orderLogic' => function ($query, $column, $columnDirection) use ($currentTable, $col, $relationshipColumn) {
                $table = classInstance(convertToClassName($col))->getTable();
                return $query->leftJoin($table, $table.'.id', '=', $currentTable.'.'.$col.'_id')
                        ->orderBy($table.'.'.$relationshipColumn, $columnDirection)
                        ->select($currentTable.'.*');
            }
        ]);
    }

    public function showTimestampColumn($col, $format = 'YYYY-MM-D HH:mm A')
    {
        $this->crud->modifyColumn($col, [
            'format' => $format,
            'type' => 'datetime',
        ]);
    }

    public function showEmployeeNameColumn()
    {
        $currentTable = $this->crud->model->getTable();

        $this->crud->modifyColumn('employee_id', [
           'label'     => 'Employee',
           'type'     => 'closure',
            'function' => function($entry) {
                if ($entry->employee) {
                    return $entry->employee->employeeNameAnchor();
                }

                return;
            },
            // NOTE: if you modify this don't forget to change method addOrderInEmployeeNameColumn
            'orderLogic' => function ($query, $column, $columnDirection) use ($currentTable) {
                return $query->leftJoin('employees', 'employees.id', '=', $currentTable.'.employee_id')
                        ->orderBy('employees.last_name', $columnDirection)
                        ->orderBy('employees.first_name', $columnDirection)
                        ->orderBy('employees.middle_name', $columnDirection)
                        ->orderBy('employees.badge_id', $columnDirection)
                        ->select($currentTable.'.*');
            },
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('employee', function ($q) use ($column, $searchTerm) {
                    $q->where('last_name', 'like', '%'.$searchTerm.'%')
                      ->orWhere('first_name', 'like', '%'.$searchTerm.'%')
                      ->orWhere('middle_name', 'like', '%'.$searchTerm.'%')
                      ->orWhere('badge_id', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);
    }

    // this is an extension of showEmployeeNameColumn to combine order
    private function addOrderInEmployeeNameColumn($cols, $orderDirection = 'ASC')
    {   
        if (!is_array($cols)) {
            $cols = (array) $cols;
        }

        // disable column sort/orderable
        foreach ($cols as $col) {
            $this->disableSortColumn($col);
        }

        $currentTable = $this->crud->model->getTable();
        $this->crud->modifyColumn('employee_id', [
            'orderLogic' => function ($query, $column, $columnDirection) use ($currentTable, $cols, $orderDirection) {
                $query->leftJoin('employees', 'employees.id', '=', $currentTable.'.employee_id')
                        ->orderBy('employees.last_name', $columnDirection)
                        ->orderBy('employees.first_name', $columnDirection)
                        ->orderBy('employees.middle_name', $columnDirection)
                        ->orderBy('employees.badge_id', $columnDirection)
                        ->select($currentTable.'.*');
                
                foreach ($cols as $col) {
                    $query->orderBy($currentTable.'.'.$col, $orderDirection);
                }

                return $query;
            },
        ]);
    }

    public function showEmployeeNameAsDifferentColumn($columnId, $relationshipColumn = 'name')
    {
        $col = str_replace('_id', '', $columnId);
        $method = relationshipMethodName($col);
        $currentTable = $this->crud->model->getTable();

        $this->crud->modifyColumn($columnId, [
            'label' => convertColumnToHumanReadable($col),
            'type'     => 'closure',
            'function' => function($entry) use ($method, $relationshipColumn) {
                if ($entry->{$method} == null) {
                    return;
                }
                return $entry->{$method}->{$relationshipColumn};
            },
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, $related_key) use ($columnId) {
                    return employeeInListsLinkUrl($entry->{$columnId});
                },
                'class' => config('appsettings.link_color'),
                'target' => '_blank'
            ],
            'orderLogic' => function ($query, $column, $columnDirection) use ($currentTable, $method, $columnId) {
                return $query->leftJoin('employees as '.$method, $method.'.id', '=', $currentTable.'.'.$columnId)
                        ->orderBy($method.'.last_name', $columnDirection)
                        ->orderBy($method.'.first_name', $columnDirection)
                        ->orderBy($method.'.middle_name', $columnDirection)
                        ->orderBy($method.'.badge_id', $columnDirection)
                        ->select($currentTable.'.*');
            },
            'searchLogic' => function ($query, $column, $searchTerm) use ($method) {
                $query->orWhereHas($method, function ($q) use ($column, $searchTerm) {
                    $q->where('last_name', 'like', '%'.$searchTerm.'%')
                      ->orWhere('first_name', 'like', '%'.$searchTerm.'%')
                      ->orWhere('middle_name', 'like', '%'.$searchTerm.'%')
                      ->orWhere('badge_id', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);
    }

    public function showEmployeeNameColumnUnsortable()
    {
        $currentTable = $this->crud->model->getTable();
        $this->crud->modifyColumn('employee_id', [
           'label'     => 'Employee'.trans('lang.unsortable_column'),
           'type'     => 'closure',
            'function' => function($entry) {
                return $entry->employee->full_name_with_badge;
            },
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('employee/'.$entry->employee_id.'/show');
                },
                'class' => config('appsettings.link_color')
            ],
            'orderable' => false,
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('employee', function ($q) use ($column, $searchTerm) {
                    $q->where('last_name', 'like', '%'.$searchTerm.'%')
                      ->orWhere('first_name', 'like', '%'.$searchTerm.'%')
                      ->orWhere('middle_name', 'like', '%'.$searchTerm.'%')
                      ->orWhere('badge_id', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);
    }

    public function currencyColumnFormatted($fieldName, $decimals = null)
    {
        if ($decimals == null) {
            $decimals = config('appsettings.decimal_precision');
        }

        $this->crud->modifyColumn($fieldName, [
            'type'        => 'number',
            'prefix'      => trans('lang.currency'),
            'decimals'    => $decimals,
            'dec_point'   => '.',
            'searchLogic' => function ($query, $column, $searchTerm) use ($fieldName) {
                $searchTerm = str_replace(',', '', $searchTerm);
                $searchTerm = str_replace(trans('lang.currency'), '', $searchTerm);
                $query->orWhere($fieldName, 'like', '%'.$searchTerm.'%');
            }
        ]);
    }

    public function showColumns($table = null, $removeOthers = null)
    {
        if ($table == null) {
            $table = $this->crud->model->getTable();
        }

        $columns = getTableColumnsWithDataType($table, $removeOthers);

        foreach ($columns as $col => $dataType) {
            $type = $this->fieldTypes()[$dataType];
            $type = (stringContains($col, 'email')) ? 'email' : $type;

            $this->crud->addColumn([
                'name'  => $col,
                'label' => convertColumnToHumanReadable($col),
                'type' => $type,
            ]);

            if ($type == 'boolean') {
                $this->crud->modifyColumn($col, [
                    'wrapper' => [
                        'element' => 'span',
                        'class' => function ($crud, $column, $entry, $related_key) {
                            if ($column['text'] == 'Yes') {
                                return 'badge badge-success';
                            }
                            return 'badge badge-default';
                        },
                    ],
                ]);
            }// end if type == boolean
        }

        $this->adminFilters(); 
    }

    public function downloadableAttachment($attachment = null)
    {
        if ($attachment == null) {
            $attachment = 'attachment';
        }

        $this->crud->modifyColumn($attachment, [
            'type'     => 'closure',
            'function' => function($entry) {
                return $entry->downloadAttachment();
            }
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Forms
    |--------------------------------------------------------------------------
    */
    public function inArrayRules($arrays)
    {
        return \Illuminate\Validation\Rule::in($arrays);
    }

    public function uniqueRules($table, $requestInput = 'id')
    {
        return \Illuminate\Validation\Rule::unique($table)->ignore(
            request($requestInput)
        );
    }

    public function uniqueRulesMultiple($table, $whereLists = [])
    {
        return \Illuminate\Validation\Rule::unique($table)->where(function ($query) use ($whereLists) {
            // where
            foreach ($whereLists as $col => $value) {
                $query->where($col, $value);
            }
            return $query->whereNull('deleted_at'); // ignore softDeleted
         });

        //  NOTE:: below is an example how to customize it and more flexible.
        // return \Illuminate\Validation\Rule::unique($this->getTable())->where(function ($query) {
        //     $query->where('employee_id', request()->employee_id);
        //     $query->where('date', request()->date);
        //     $query->where(function ($q) {
        //         $q->where('status', 0); // pending
        //         $q->orWhere('status', 1); // success
        //         // denied is not duplicate so allow it
        //     });
            
        //     return $query->whereNull('deleted_at'); // ignore softDeleted
        // });
    }

    /*
    |--------------------------------------------------------------------------
    | Operations
    |--------------------------------------------------------------------------
    */
    public function enableLoaderInCreate()
    {
        $this->crud->setCreateView('crud::custom_create_with_loader');
    }
    
    public function enableLoaderInEdit()
    {
        $this->crud->setEditView('crud::custom_edit_with_loader');
    }

    /*
    |--------------------------------------------------------------------------
    | Misc.
    |--------------------------------------------------------------------------
    */
    public function classInstance($class) 
    {
        return classInstance($class);
    }

    public function entryLabel()
    {
        return Str::plural(convertColumnToHumanReadable($this->crud->model->model));
    }

    public function buttonLabel()
    {
        return convertColumnToHumanReadable($this->crud->model->model);
    }

    public function downloadableHint($hint, $file)
    {
        return $this->crud->addField([
            'name' => 'temp',
            'label' => '',
            'attributes' => [
                'hidden' => true
            ],
            'hint' => '<a download href="'.backpack_url($file).'">'.$hint.'</a>',
        ]);
    }

    public function hint($hint, $afterField = null)
    {

        if ($afterField != null) {
            $this->crud->addField([
                'name' => Str::snake($hint).'_temp',
                'label' => '',
                'attributes' => [
                    'hidden' => true
                ],
                'hint' => $hint,
            ])->afterField($afterField);
        }else {
            $this->crud->addField([
                'name' => Str::snake($hint).'_temp',
                'label' => '',
                'attributes' => [
                    'hidden' => true
                ],
                'hint' => $hint,
            ]);
        }
    }// end hint

    public function dumpAllRequest()
    {
        dd(request()->all());
    }

    public function debugAllRequest()
    {
        debug(request()->all());
    }
}