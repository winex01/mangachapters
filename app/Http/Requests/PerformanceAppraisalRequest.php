<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class PerformanceAppraisalRequest extends FormRequest
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
            'employee_id'             => 'required|integer',
            'date_evaluated'          => 'required|date',
            'appraisal_type_id'       => 'required|integer',
            'appraiser_id'            => 'required|integer',
            'job_function'            => 'required|integer|between:1,10',
            'productivity'            => 'required|integer|between:1,10',
            'attendance'              => 'required|integer|between:1,10',
            'planning_and_organizing' => 'required|integer|between:1,10',
            'innovation'              => 'required|integer|between:1,10',
            'technical_domain'        => 'required|integer|between:1,10',
            'sense_of_ownership'      => 'required|integer|between:1,10',
            'customer_relation'       => 'required|integer|between:1,10',
            'professional_conduct'    => 'required|integer|between:1,10',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        $msg = parent::messages();
        
        $appendMsg = [
            'appraisal_type_id.required' => 'The appraisal type field is required.',
            'appraiser_id.required'      => 'The appraiser field is required.',

        ];

        return collect($msg)->merge($appendMsg)->toArray();
    }

}
