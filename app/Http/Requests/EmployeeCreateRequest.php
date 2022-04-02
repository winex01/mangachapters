<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class EmployeeCreateRequest extends FormRequest
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
        return [
            'last_name'        => 'required|min:3|max:255',
            'first_name'       => 'required|min:3|max:255',
            'badge_id'         => 'nullable|unique:'.$this->getTable(),
            'zip_code'         => 'nullable|numeric',
            'birth_date'       => 'nullable|date',
            'mobile_number'    => 'nullable|'.phoneNumberRegex(),
            'telephone_number' => 'nullable|'.phoneNumberRegex(),
            'personal_email'   => 'nullable|email',
            'company_email'    => 'nullable|email',
            'pagibig'          => 'nullable|regex:/^[0-9\-]+$/',
            'philhealth'       => 'nullable|regex:/^[0-9\-]+$/',
            'sss'              => 'nullable|regex:/^[0-9\-]+$/',
            'tin'              => 'nullable|regex:/^[0-9\-]+$/',
            'date_applied'     => 'nullable|date',
            'date_hired'       => 'required|date',
            'gender_id'        => 'nullable|integer',
            'civil_status_id'  => 'nullable|integer',
            'citizenship_id'   => 'nullable|integer',
            'religion_id'      => 'nullable|integer',
            'blood_type_id'    => 'nullable|integer',
        ];
    }

}
