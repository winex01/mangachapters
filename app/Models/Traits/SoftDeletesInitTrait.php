<?php 

namespace App\Models\Traits;

/**
 * 
 */
trait SoftDeletesInitTrait
{
	public function getSoftDeletingAttribute()
    {
        // ... check if 'this' model uses the soft deletes trait
        return in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this)) && ! $this->forceDeleting;
    }
}