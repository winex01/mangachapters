<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class MenuRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'label' => 'required|min:3|max:255',
            'link'  => 'nullable|min:3|max:255',
        ];
    }
}
