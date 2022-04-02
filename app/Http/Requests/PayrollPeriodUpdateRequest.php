<?php

namespace App\Http\Requests;

use App\Http\Requests\PayrollPeriodCreateRequest;
use Illuminate\Validation\Rule;

class PayrollPeriodUpdateRequest extends PayrollPeriodCreateRequest
{
   /**
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
   public function rules()
   {
       $rules = parent::rules();
       
       $append = [
            'name' => $this->uniqueRules($this->getTable()),
            'grouping_id' => ['required', 'numeric',
                $this->customUniqueRules()->ignore(request()->id)
            ],
        ];

      return collect($rules)->merge($append)->toArray();
   }
}
