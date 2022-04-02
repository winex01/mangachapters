<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class WithholdingTaxBasisCreateRequest extends FormRequest
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
            'name' => 'required',
            'withholding_tax_version_id' => 'required|numeric',
        ];

        $rules = array_merge($rules, $addRules);

        return $rules;
    }
}