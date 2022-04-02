<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class SupportingDocumentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id' => 'required|integer',
            'document'    => 'required|min:3|max:255',
            'attachment'  => 'nullable|max:'.config('settings.appsettings_attachment_file_limit'),
        ];
    }
}
