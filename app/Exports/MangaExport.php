<?php

namespace App\Exports;

use App\Exports\BaseExport;

class MangaExport extends BaseExport
{
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

                if ($filter == 'add_scope_show_only') {
                    $this->query->whereBookmarkedBy(auth()->user());
                }else {
                    $this->query->{$scopeName}();
                }

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
}
