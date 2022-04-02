<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class AppraisalInterpretationCreateRequest extends FormRequest
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
        $rules = parent::rules();

        $addRules = [
            'rating_from' => 'required|numeric|lte:rating_to',
            'rating_to'   => 'required|numeric|gte:rating_from',
        ];

        $rules = array_merge($rules, $addRules);

        return $rules;
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'rating_from.lte' => 'The "Rating from" must be less than or equal to "Rating to".',
            'rating_to.gte' => 'The "Rating to" must be greater than or equal to "Rating from".',
        ];
    }

}
