<?php

namespace App\Http\Requests;

use App\Http\Requests\GravityOfSanctionCreateRequest;

class GravityOfSanctionUpdateRequest extends GravityOfSanctionCreateRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        
        $rules['name'] = $this->uniqueRules($this->getTable());
        
        return $rules;
    }
}
