<?php

namespace App\Http\Requests;

use App\Http\Requests\MangaCreateRequest;

class MangaUpdateRequest extends MangaCreateRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        
        $rules['title'] = $this->uniqueRules($this->getTable());
        
        return $rules;
    }
}
