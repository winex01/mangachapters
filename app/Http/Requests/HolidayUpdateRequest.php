<?php

namespace App\Http\Requests;

use App\Http\Requests\HolidayCreateRequest;

class HolidayUpdateRequest extends HolidayCreateRequest
{

   /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        
        $rules['date'] = $this->uniqueRules($this->getTable());
        
        return $rules;
    }

    
}
