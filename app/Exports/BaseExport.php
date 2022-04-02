<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\getActiveSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Thumbnails;

class BaseExport implements 
    FromQuery, 
    WithMapping,
    WithHeadings,
    ShouldAutoSize,
    WithCustomStartCell,
    WithStyles,
    WithProperties,
    WithEvents,
    WithColumnFormatting
{
    use Exportable;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;

    protected $model;
    protected $entries;
    protected $exportColumns;
    protected $tableColumns;
    protected $userFilteredColumns;
    protected $rowStartAt = 5;
    protected $exportType;
    protected $filters;
    protected $currentTable;
    protected $currentColumnOrder;
    protected $setWrapText = false;
    protected $query;
    protected $formats = [
        'date'    => NumberFormat::FORMAT_DATE_YYYYMMDD,
        'double'  => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        'decimal' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        'varchar' => NumberFormat::FORMAT_TEXT,
        'text'    => NumberFormat::FORMAT_TEXT,
    ];

    public function __construct($data)
    {
        $this->model               = classInstance($data['model']);
        $this->entries             = $data['entries'] ?? null; // checkbox id's
        $this->userFilteredColumns = $data['exportColumns'];
        $this->exportType          = $data['exportType'];
        $this->filters             = $data['filters'];
        $this->currentColumnOrder  = $data['currentColumnOrder'];
        $this->currentTable        = $this->model->getTable();    
        $this->query               = $this->model->query();
        $this->tableColumns        = $this->dbColumnsWithDataType();
        $this->exportColumns       = $this->setExportColumns();

        $this->filteredSoftDeletedItems();

        // debug($this->exportColumns);
    }

    // dont include items that has relationship soft deleted
    private function filteredSoftDeletedItems()
    {
        foreach ($this->hasRelationshipTo() as $temp) {
            $fk = $temp.'_id';
            if (array_key_exists($fk, $this->tableColumns)) {
                $this->query->has($temp);
            }
        }
    }

    public function query()
    {
        // if has filters
        if ($this->filters) {
            $this->applyActiveFilters();
        } 
        // if user check/select checkbox/entries
        // and order by check sequence
    	if ($this->entries) {
            $this->getOnlySelectedEntries();
    	}else {
            // if no entries selected
            // and user order the column desc/asc
            if ($this->currentColumnOrder != null) {
                $column = strtolower($this->currentColumnOrder['column']);
                $column = Str::snake($column);
                $orderBy = $this->currentColumnOrder['orderBy'];

                $this->orderByCurrentColumnOrder($column, $orderBy);

            }else {
                 // if user didnt order column
                 $this->orderByDefault();
            }        
        }
        
        $this->orderByAddOns();

        return $this->query->orderBy($this->currentTable.'.created_at');
    }

    protected function orderByCurrentColumnOrder($col, $direction)
    {
        $this->orderBy($col, $direction);
    }

    public function map($entry): array
    {
        $obj = [];
        foreach ($this->exportColumns as $col => $dataType) {
            $value = null;
            if ( endsWith($col, '_custom_map') ) {
                $value = $this->customMap($col, $entry, $dataType);
            }elseif ($col == 'badge_id' && ($this->exportType == 'pdf' || $this->exportType == 'html')) {
                // NOTE:: prefend white space if export is PDF/HTML
                $value = ' '.$entry->{$col};
            }elseif (endsWith($col, '_id') && $entry->{relationshipMethodName($col)} ) {
                // if column has suffix _id,then it must be relationship
                $value = $entry->{relationshipMethodName($col)}->name; // app settings relationship                
            }elseif (startsWith($col, 'accessor_')) {
                $accessor = str_replace('accessor_', '', $col);
                $value = $entry->{$accessor};
            }else {
                $value = $entry->{$col};                
            }

            // if dataType
            if ($dataType == 'date') {
                $value = Date::PHPToExcel($value); 
            }elseif ($dataType == 'tinyint') {
                $value = booleanOptions()[$value];                
            }else {
                // do nothing
            }

            // override column with no relationship labels using col
            $value = $this->changeColumnValue($col, $value);
            

            $obj[] = $value;
        }// end foreach

        return $obj;
    }

    protected function customMap($col, $entry, $dataType)
    {

    }

    protected function changeColumnValue($col, $value)
    {
        // override this method and add condition here
        return $value;
    }

    public function headings(): array
    {
        $header = collect($this->exportColumns)->map(function ($dataType, $col) {
            $col = str_replace('accessor_', '', $col);
            $col = str_replace('_as_export', '', $col);
            $col = str_replace('_custom_map', '', $col);
            return convertColumnToHumanReadable($col);
        })->toArray();

        return $header;
    }

    public function columnFormats(): array
    {
        $data = [];
        $inc = 'A';
        foreach ($this->exportColumns as $col => $dataType) {
            if (array_key_exists($dataType, $this->formats)) {
                $data[$inc] = $this->formats[$dataType];
            }
            $inc++;
        }

        return $data;
    }

    public function startCell(): string
    {
        return 'A'.$this->rowStartAt;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the row as bold text.
            $this->rowStartAt => ['font' => ['bold' => true]],
        ];
    }

    public function properties(): array
    {
        return [
            'creator' => auth()->user()->name,
        ];
    }

    public function registerEvents(): array
    {
        $report = convertColumnToHumanReadable(
            $this->model->model
        );
        
        return [
            AfterSheet::class    => function(AfterSheet $event) use ($report) {
                $event->sheet->setCellValue('A2', $report);
                $event->sheet->setCellValue('A3', 'Generated: '. currentDateTime());

                // wrap text to auto set height
                if ($this->setWrapText) {
                    $tempCol = 'A';
                    for ($i = 0; $i < count($this->userFilteredColumns); $i++) {
                        $event->sheet->getStyle($tempCol++)->getAlignment()->setWrapText(true);
                    }
                }

            },
        ];
    }

    protected function applyActiveFilters()
    {
        foreach ($this->filters as $filter => $value) {
            if ($filter == 'persistent-table') {
                continue;
            }

            if (startsWith($filter, 'custom_filter_')) {
                $this->customFilters($filter, $value);

            }elseif (startsWith($filter, 'select2_multiple_')) {
                $this->select2MultipleFilters($filter, json_decode($value));

            }elseif (array_key_exists($filter, $this->tableColumns)) {
                // if filter is tablecolumn
                $this->query->where($this->currentTable.'.'.$filter, $value);
            
            }elseif (stringContains($filter, 'add_scope_json_params_')) {

                $scopeName = str_replace('add_scope_json_params_', '', $filter);
                $this->query->{$scopeName}(json_decode($value));

            }elseif (stringContains($filter, 'remove_scope_')) {
                // if filter is remove scope
                $scopeName = str_replace('remove_scope_', '', $filter);
                $this->query->withoutGlobalScope($scopeName);

            }elseif (stringContains($filter, 'add_scope_')) {
                // if filter is add scope
                $scopeName = str_replace('add_scope_', '', $filter);
                $this->query->{$scopeName}();

            }elseif (stringContains($filter, 'date_range_filter_')) {
                // if filter is date
                $dates = json_decode($value);
                $column = str_replace('date_range_filter_', '', $filter);
                $this->query->whereBetween($this->currentTable.'.'.$column, [$dates->from, $dates->to]);

            }elseif ($filter == 'payrollPeriod_scope') {
                // if filter is add scope
                $scopeName = str_replace('_scope', '', $filter);
                $this->query->{$scopeName}($value);
                
            }elseif ($filter == 'trashed') {
                $this->query->onlyTrashed();
            
            }elseif ($filter == 'employee_multiple') {
                // global filter employee
                $this->query->whereIn('employee_id', json_decode($value));
            }else {
                // else as relationship
                $this->query->whereHas($filter, function (Builder $q) use ($value, $filter) {
                    $table = $q->getModel()->getTable();
                    $q->where($table.'.id', $value);
                });
            }
        }

        $this->additionalApplyActiveFilters($filter);
    }

    protected function customFilters()
    {

    }

    protected function select2MultipleFilters($filter, $values)
    {

    }

    // override this method in export file to add more flexible filters
    protected function additionalApplyActiveFilters($filter)
    {

    }

    protected function getOnlySelectedEntries()
    {
        $ids_ordered = implode(',', $this->entries);

        $this->query->whereIn($this->currentTable.'.id', $this->entries)
            ->orderByRaw("FIELD($this->currentTable.id, $ids_ordered)");
    }

    protected function orderBy($column, $orderBy)
    {   
        if (in_array($column, $this->orderColumnAsEmployeesTable())) {
            $this->orderByEmployee($orderBy, $column);
        }elseif (method_exists($this->model, Str::camel($column))) {
            $joinTable = Str::plural($column);
            $this->query->join($joinTable, $joinTable.'.id', '=', $this->currentTable.'.'.$column.'_id')
                ->orderBy($joinTable.'.name', $orderBy);  
        }else {
            $this->query->orderBy($column, $orderBy);
        }
    }

    protected function orderColumnAsEmployeesTable()
    {
        return [
            'employee'
        ];

        // override extend this method in your specific export class eg below:
        // $result = parent::orderColumnAsEmployeesTable();
        // $result[] = 'approver';

        // return $result;
    }

    protected function orderByDefault()
    {
        if (array_key_exists('employee_id', $this->tableColumns)) {
            // if has relationship with employee then sort asc employee name
            $this->orderByEmployee('asc');
        }elseif ($this->currentTable == 'employees') {
            // if crud or table is employees then sort default
            $this->query->orderByFullName();
        }elseif (array_key_exists('name', $this->tableColumns)) {
            // if table has Name column
            $this->query->orderBy('name', 'asc');
        }else {
            // do nothing
        }
    }

    /**
     * add ons order
     */
    protected function orderByAddOns()
    {

    }

    protected function orderByEmployee($column_direction = 'asc', $aliasJoinTable = 'employees', $customFk = null)
    {
        $foreignKey = str_singular($aliasJoinTable).'_id';
        if ($customFk != null) {
            $foreignKey = $customFk;
        }

        $this->query->join('employees as '.$aliasJoinTable, $aliasJoinTable.'.id', '=', $this->currentTable.'.'.$foreignKey)
            ->orderBy($aliasJoinTable.'.last_name', $column_direction)
            ->orderBy($aliasJoinTable.'.first_name', $column_direction)
            ->orderBy($aliasJoinTable.'.middle_name', $column_direction)
            ->orderBy($aliasJoinTable.'.badge_id', $column_direction);   
    }

    protected function setExportColumns()
    {
        // dont include this columns in exports see at config/hris.php
        $data = collect($this->userFilteredColumns)->diff(
            config('appsettings.dont_include_in_exports')
        )->toArray();
        
        // add dataType - 'column' => 'dataType'
        $data = collect($this->tableColumns)
            ->filter(function ($dataType, $col) use ($data) {
                return in_array($col, $data);
        })->toArray();
    
        return $data;
    }

    public function dbColumnsWithDataType()
    {
        return getTableColumnsWithDataType($this->model->getTable());
    }

    // override this if you want to modify what column shows in column dropdown with checkbox
    public static function exportColumnCheckboxes()
    {
        return [
            // 
        ];
    }

    // declare if you want to idenfy which checkbox is check on default
    public static function checkOnlyCheckbox()
    {
        return [
            // 
        ];
    }
}
