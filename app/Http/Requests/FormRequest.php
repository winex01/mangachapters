<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

class FormRequest extends BaseFormRequest
{
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:1|max:255|unique:'.$this->getTable()
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
            'employee_id.required' => 'The employee field is required.'
        ];
    }

    // override in class inherited
    public function getTable()
    {
        return $this->setRequestTable(get_class($this));
    }

    public function setRequestTable($class)
    {   
        $model = str_replace('App\Http\Requests\\', '', $class);
        $model = str_replace('Request', '', $model);
        $model = str_replace('Create', '', $model);
        $model = str_replace('Update', '', $model);

        return classInstance($model)->getTable();
    }

    // override and put column => values in array for conditions
    protected function customUniqueRules()
    {
        return $this->uniqueRulesMultiple($this->getTable(), [
            // 'grouping_id' => request()->grouping_id,
            // 'status' => 1     
        ]);
    }
}
