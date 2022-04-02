<?php

namespace App\Http\Requests;

use App\Http\Requests\EmployeeCreateRequest;

class EmployeeUpdateRequest extends EmployeeCreateRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        $rules['badge_id'] = [$this->uniqueRules($this->getTable()),'nullable'];

        return $rules;
    }
}
