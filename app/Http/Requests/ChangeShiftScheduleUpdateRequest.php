<?php

namespace App\Http\Requests;

use App\Http\Requests\ChangeShiftScheduleCreateRequest;
use Illuminate\Validation\Rule;

class ChangeShiftScheduleUpdateRequest extends ChangeShiftScheduleCreateRequest
{
    /**
        * Get the validation rules that apply to the request.
        *
        * @return array
        */
       public function rules()
       {
            $rules = parent::rules();

            $append = [
                'employee_id' => [
                    'required', 'integer',
                     Rule::unique('change_shift_schedules')->where(function ($query) {
                        return $query
                            ->where('employee_id', request('employee_id'))
                            ->where('date', request('date'))
                            ->whereNull('deleted_at'); // ignore softDeleted
                     })->ignore(request('id'))
                ],
            ];


            return collect($rules)->merge($append)->toArray();
       }   
}
