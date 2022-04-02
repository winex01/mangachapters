<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

class ChangeShiftScheduleCreateRequest extends FormRequest
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
            'employee_id' => [
                'required', 'integer',
                 Rule::unique('change_shift_schedules')->where(function ($query) {
                    return $query
                        ->where('employee_id', request('employee_id'))
                        ->where('date', request('date'))
                        ->whereNull('deleted_at'); // ignore softDeleted
                 })
            ],
            'date'              => 'required|date',
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
             'employee_id.unique' => 'Duplicate entry for employee shift schedule for this date.',
        ];
    
        return collect($msg)->merge($appendMsg)->toArray();
    }
}
