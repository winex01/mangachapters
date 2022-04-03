<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class ScanFilterCreateRequest extends FormRequest
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

        $rules['filter'] = 'required|min:1|max:255';

        return $rules;
    }
}
