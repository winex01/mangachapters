<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class MangaCreateRequest extends FormRequest
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
            'title' => 'required|min:1|max:255|unique:'.$this->getTable()
        ];

        return $rules;
    }
}
