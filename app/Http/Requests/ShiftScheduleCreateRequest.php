<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class ShiftScheduleCreateRequest extends FormRequest
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
        $rules = parent::rules();

        if ( (bool)request()->open_time) {
            return $rules;
        }

        $append = [
            'working_hours'      => 'required|json',
            'overtime_hours'     => 'nullable|json',
            'relative_day_start' => 'required|date_format:H:i',
        ];

        $lastWhEnd = null;
        $firstOhStart = null;

        // if json wh is empty then override it to null to activate validation
        if (request()->working_hours == '[{}]') {
            request()->merge([
                'working_hours' => null,
            ]);
        }else {
            // 
            $workingHours = json_decode(request()->working_hours);
            $tempCount = 0;
            foreach ($workingHours ?? [] as $wh) {
                if (!property_exists($wh, 'start') || !property_exists($wh, 'end')) {
                    $append['wh_start_end_field'] = 'required';
                }

                if ($tempCount == (count($workingHours) - 1)) {
                    // last element
                    $lastWhEnd = ($wh->end) ?? null;
                }

                $tempCount++;
            }
        }

        // overtime validation must have start and end
        if (request()->overtime_hours != '[{}]') {
            $overtimeHours = json_decode(request()->overtime_hours);
            $tempCount = 0;
            foreach ($overtimeHours ?? [] as $oh) {
                if (!property_exists($oh, 'start') || !property_exists($oh, 'end')) {
                    $append['ot_start_end_field'] = 'required';
                }

                if ($tempCount == 0) {
                    $firstOhStart = ($oh->start) ?? null;
                }

                $tempCount++;
            }
        }

        // last working hours end and first overtime start must not overlapped
        if ($lastWhEnd != null && $firstOhStart != null) {
            if ( carbonTime($lastWhEnd)->greaterThanOrEqualTo(carbonTime($firstOhStart)) ) {
                $append['wh_and_oh_must_not_overlapped'] = 'required';
            }
        }

        return collect($rules)->merge($append)->toArray();
    }

    public function messages()
    {
        $msg = parent::messages();

        $append = [
            'wh_start_end_field.required' => 'The start and end field of working hours is required.',
            'ot_start_end_field.required' => 'The start and end field of overtime hours is required.',
            'wh_and_oh_must_not_overlapped.required' => 'The Working Hours and Overtime Hours field must not overlapped.',
            'oh_and_relativeDayStart_must_not_overlapped.required' => 'The Overtime Hours last end field and Relative Day Start field must not overlapped.',
        ];

        return collect($msg)->merge($append)->toArray();
    }
}
