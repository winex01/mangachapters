<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class SkillAndTalentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id'     => 'required|integer',
            'skill_or_talent' => 'required|min:5|max:255'
        ];
    }
}
