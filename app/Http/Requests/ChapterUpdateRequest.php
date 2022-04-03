<?php

namespace App\Http\Requests;

use App\Http\Requests\ChapterCreateRequest;

class ChapterUpdateRequest extends ChapterCreateRequest
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
            'manga_id' => ['required', 'integer',
                $this->customUniqueRules()->ignore(request()->id)
            ],
        ];

      return collect($rules)->merge($append)->toArray();
   }
}
