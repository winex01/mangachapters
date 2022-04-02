<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class LeaveCreditCreateRequest extends FormRequest
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
            'employee_id'   => ['required', 'integer', $this->customUniqueRules()],
            'leave_type_id' => 'required|integer',
            // 'leave_credit'  => 'required|numeric|gt:0',
            'leave_credit'  => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    if (fmod($value, .5) != 0) {
                        $fail('The '.str_replace('_', ' ', $attribute).' is invalid.');
                    }
                },
            ],
        ];
    }

    protected function customUniqueRules()
    {
        return $this->uniqueRulesMultiple($this->getTable(), [
            'leave_type_id' => request()->leave_type_id,
        ]);
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
            'employee_id.unique' => 'Duplicate entry for employee leave type.',
        ];

        return collect($msg)->merge($appendMsg)->toArray();
    }
}
