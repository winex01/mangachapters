<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class CompanyCreateRequest extends FormRequest
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

        $rules['fax_number']        = 'nullable|'.phoneNumberRegex();
        $rules['mobile_number']     = 'nullable|'.phoneNumberRegex();
        $rules['telephone_number']  = 'nullable|'.phoneNumberRegex();
        $rules['pagibig_number']    = 'nullable|regex:/^[0-9\-]+$/';
        $rules['philhealth_number'] = 'nullable|regex:/^[0-9\-]+$/';
        $rules['sss_number']        = 'nullable|regex:/^[0-9\-]+$/';
        $rules['tax_id_number']     = 'nullable|regex:/^[0-9\-]+$/';
        $rules['bir_rdo']           = 'nullable|regex:/^[0-9\-]+$/';

        return $rules;
    }
}
