<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class DaysPerYearRequest extends FormRequest
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
            'days_per_year'  => 'required|numeric',
            'days_per_week'  => 'required|numeric',
            'hours_per_day'  => 'required|numeric',
        ];
    }
}
