<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class TrainingAndSeminarRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id'    => 'required|integer',
            'organizer'      => 'required|min:3|max:255',
            'training_title' => 'required|min:3|max:255',
            'attachment'     => 'nullable|max:'.config('settings.appsettings_attachment_file_limit'),
        ];
    }
}
