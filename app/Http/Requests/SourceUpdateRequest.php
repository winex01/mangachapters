<?php

namespace App\Http\Requests;

use App\Http\Requests\SourceCreateRequest;

class SourceUpdateRequest extends SourceCreateRequest
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
            'url' => ['required', 'url',
                $this->customUniqueRules()->ignore(request()->id)
            ],
        ];

      return collect($rules)->merge($append)->toArray();
   }
   
}
