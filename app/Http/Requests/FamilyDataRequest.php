<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class FamilyDataRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 
            'employee_id'      => 'required|integer',
            'relation_id'      => 'required|integer',
            'last_name'        => 'required|min:3|max:255',
            'first_name'       => 'required|min:3|max:255',
            'mobile_number'    => 'nullable|'.phoneNumberRegex(),
            'telephone_number' => 'nullable|'.phoneNumberRegex(),
            'company_email'    => 'nullable|email',
            'personal_email'   => 'nullable|email',
            'birth_date'       => 'nullable|date',
        ];
    }
}
