<?php 

namespace App\Models\Traits;


trait FileTrait
{
	/*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function deleteFileFromStorage($data, $url)
    {
        $file = str_replace('storage/', '', $url);
            // check if softDelete is enabled
            if ($data->soft_deleting) {
                if ($data->isForceDeleting()) {
                    \Storage::disk('public')->delete($file);
                }
            }else {
                \Storage::disk('public')->delete($file);
            }
    }
}