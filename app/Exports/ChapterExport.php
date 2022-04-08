<?php

namespace App\Exports;

use App\Exports\BaseExport;

class ChapterExport extends BaseExport
{
    /**
     * add ons order
     */
    protected function orderByAddOns()
    {
        $this->query->orderByRelease();
    }

    public static function exportColumnCheckboxes()
    {   
        $temp = getTableColumnsWithDataType('chapters');

        // if has no access in invalid link column then remove it from export
        if (!auth()->user()->can('chapters_invalid_link')) {
            unset($temp['invalid_link']);
        }
        
        return collect($temp)->keys()->toArray();
    }

    public function dbColumnsWithDataType()
    {
        $temp = getTableColumnsWithDataType($this->model->getTable());
        
        // if has no access in invalid link column then remove it from export
        if (!auth()->user()->can('chapters_invalid_link')) {
            unset($temp['invalid_link']);
        }
        
        return $temp;
    }
}
