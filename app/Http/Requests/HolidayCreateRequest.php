<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class HolidayCreateRequest extends FormRequest
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
            'name'            => 'required|min:2|max:255',
            'date'            => 'required|unique:'.$this->getTable(),
            'holiday_type_id' => 'required|integer',
            'locations'       => 'nullable|array',
            'locations.*'     => 'integer' 
        ];
    }

    public function messages()
    {
        return [
            //
            'locations.*.integer' => ' Invalid locations value.'
        ];
    }
}
