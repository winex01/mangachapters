<?php

namespace App\Http\Requests;

use App\Http\Requests\OffenceAndSanctionCreateRequest;
use Illuminate\Validation\Rule;

class OffenceAndSanctionUpdateRequest extends OffenceAndSanctionCreateRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        $append = [
            'employee_id' => [
                'required', 'integer',
                 Rule::unique('offence_and_sanctions')->where(function ($query) {
                    return $query
                        ->where('employee_id', request()->employee_id)
                        ->where('offence_classification_id', request()->offenceClassification)
                        ->where('gravity_of_sanction_id', request()->gravityOfSanction)
                        ->whereNull('deleted_at'); // ignore softDeleted
                 })->ignore(request()->id)
            ],
        ];

        return collect($rules)->merge($append)->toArray();
        
    }
}
