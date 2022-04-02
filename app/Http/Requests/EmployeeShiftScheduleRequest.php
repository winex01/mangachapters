<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class EmployeeShiftScheduleRequest extends FormRequest
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
            'employee_id'      => 'required|integer',
            'effectivity_date' => 'required|date|after_or_equal:'.currentDate(),
        ];

        $daysOfWeek = classInstance('\App\Http\Controllers\Admin\EmployeeShiftScheduleCrudController', true)->daysOfWeek();

        $append = [];
        foreach ($daysOfWeek as $day_id) {
            $append[$day_id] = 'nullable|integer'; 
        }

        return collect($rules)->merge($append)->toArray();
    }
}
