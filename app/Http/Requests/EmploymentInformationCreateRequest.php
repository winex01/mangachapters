<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class EmploymentInformationCreateRequest extends FormRequest
{
    private $fields;

    public function __construct()
    {
        $this->fields = crudInstance('EmploymentInformationCrudController')->inputFields(); 
    }

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
        
        $rules = [];
        foreach ($this->fields as $field) {
            $rules[$field] = 'nullable|numeric';
        }

        $rules = collect($rules)->merge([
            'employee_id'       => 'required|integer',
            'COMPANY'           => 'required|integer',
            'LOCATION'          => 'required|integer',
            'DAYS_PER_YEAR'     => 'required|integer',
            'PAY_BASIS'         => 'required|integer',
            'PAYMENT_METHOD'    => 'required|integer',
            'EMPLOYMENT_STATUS' => 'required|integer',
            'JOB_STATUS'        => 'required|integer',
            'GROUPING'          => 'required|integer',
            'BASIC_RATE'        => 'required|numeric',
            'effectivity_date'  => 'required|date|after_or_equal:'.currentDate(),
        ])->toArray();

        return $rules;
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        $msg = [];
        foreach ($this->fields as $field => $type) {
            $msg[$field.'.required'] = 'The '.str_replace('_', ' ', strtolower($field)).' field is required.';
        }

        return $msg;
    }

}