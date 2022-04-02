<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class WorkExperienceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id'  => 'required|integer',
            'company'      => 'required|min:5|max:255',
            'position'     => 'required|min:5|max:255',
            'date_started' => 'required|date',
            'salary'       => 'nullable|numeric|gt:0',
            'attachment'   => 'nullable|max:'.config('settings.appsettings_attachment_file_limit'),
        ];
    }
}
