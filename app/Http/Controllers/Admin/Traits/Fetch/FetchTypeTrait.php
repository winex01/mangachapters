<?php 

namespace App\Http\Controllers\Admin\Traits\Fetch;

trait FetchTypeTrait
{
    public function fetchType()
    {
        return $this->fetch(\App\Models\Type::class);
    }
    
}