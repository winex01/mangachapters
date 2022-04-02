<?php 

namespace App\Models\Traits;


trait AttachmentTrait
{
	/*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function downloadAttachment() {
       
       if ($this->attachment) {
            return '<a class="'.config('appsettings.link_color').'" href="'.url('storage/'.$this->attachment).'" download><i class="nav-icon la la-cloud-download"></i> Download</a>';
       }

       return;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    // convert column/field name attachment to downloadable link
    public function setAttachmentAttribute($value)
    {
        $attribute_name = "attachment";
        $disk = "public";
        $destination_path = \Str::plural($attribute_name).'/'.\Str::snake($this->model);

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);

    // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
    }
}