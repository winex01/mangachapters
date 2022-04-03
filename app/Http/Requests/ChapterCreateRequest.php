<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;


class ChapterCreateRequest extends FormRequest
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

        $rules = [
            'manga_id'  => ['required', 'integer', $this->customUniqueRules()],
            'chapter'   => 'required',
            'url'       => 'required|url',
        ];

        return $rules;
    }

    protected function customUniqueRules()
    {        
        return $this->uniqueRulesMultiple($this->getTable(), [
            'manga_id' => request()->manga_id,
            'chapter' => request()->chapter     
        ]);

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
            'manga_id.unique' => trans('lang.duplicate_entry'),
        ];

        return collect($msg)->merge($appendMsg)->toArray();
    }
}
