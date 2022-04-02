<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class ProfessionalOrgRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id'       => 'required|integer',
            'organization_name' => 'required|min:5|max:255',
            'attachment'        => 'nullable|max:'.config('settings.appsettings_attachment_file_limit'),
        ];
    }
}
