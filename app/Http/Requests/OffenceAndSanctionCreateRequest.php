<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

class OffenceAndSanctionCreateRequest extends FormRequest
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
            'employee_id' => [
                'required', 'integer',
                 Rule::unique('offence_and_sanctions')->where(function ($query) {
                    return $query
                        ->where('employee_id', request()->employee_id)
                        ->where('offence_classification_id', request()->offenceClassification)
                        ->where('gravity_of_sanction_id', request()->gravityOfSanction)
                        ->whereNull('deleted_at'); // ignore softDeleted
                 })
            ],
            'date_issued'               => 'required|date',
            'offence_classification_id' => 'required|integer',
            'gravity_of_sanction_id'    => 'required|integer',
            'attachment'                => 'nullable|max:'.config('settings.appsettings_attachment_file_limit'),
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        $msg = parent::messages();
        
        $appendMsg = [
            'employee_id.unique' => 'Duplicate entry for emplyoee offence classification and gravity sanction.',
        ];

        return collect($msg)->merge($appendMsg)->toArray();
    }
}
