<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class MangaRequest extends FormRequest
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
            'photo' => 'required',
            'title' => 'required',
        ];

        return $rules;
    }
}
