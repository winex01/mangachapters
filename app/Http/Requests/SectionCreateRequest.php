<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class SectionCreateRequest extends FormRequest
{
    protected $table;

    public function __construct()
    {
        $this->table = classInstance('Section')->getTable();
    }
    
}
