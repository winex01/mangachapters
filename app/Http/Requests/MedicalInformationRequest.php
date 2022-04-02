<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class MedicalInformationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id'                    => 'required|integer',
            'medical_examination_or_history' => 'required|min:5|max:255',
            'date_taken'                     => 'required|date',
            'attachment'                     => 'nullable|max:'.config('settings.appsettings_attachment_file_limit'),
        ];
    }
}
