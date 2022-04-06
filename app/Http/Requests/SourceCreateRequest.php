<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class SourceCreateRequest extends FormRequest
{
    public function getTable()
    {
        return $this->setRequestTable(get_class($this));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
            'manga_id'       => 'required|integer',
            'url'            => ['required', 'url', $this->customUniqueRules()],
            'scan_filter_id' => 'required|integer',
        ];

        return $rules;
    }

    protected function customUniqueRules()
    {        
        return $this->uniqueRulesMultiple($this->getTable(), [
            'url' => request()->url     
        ]);

    }
}
