<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class DivisionCreateRequest extends FormRequest
{
    public function getTable()
    {
        return $this->setRequestTable(get_class($this));
    }
}
