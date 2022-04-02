<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class EducationalBackgroundRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id'          => 'required|integer',
            'educational_level_id' => 'required|integer',
            'school'               => 'required',
            'attachment'           => 'nullable|max:'.config('settings.appsettings_attachment_file_limit'),
        ];
    }
}
