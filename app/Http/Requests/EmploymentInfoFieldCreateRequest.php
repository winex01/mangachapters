<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class EmploymentInfoFieldCreateRequest extends FormRequest
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
        $rules = parent::rules();

        $rules['field_type'] = 'required|integer';

        return $rules;
    }

}
