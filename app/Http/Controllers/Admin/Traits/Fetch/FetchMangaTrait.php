<?php 

namespace App\Http\Controllers\Admin\Traits\Fetch;

trait FetchMangaTrait
{
    public function fetchManga()
    {
        return $this->fetch(\App\Models\Manga::class);
    }
    
}