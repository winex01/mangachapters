<?php

namespace App\Models;

use App\Models\Model;

class DtrLogType extends Model
{
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'dtr_log_types';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getNameBadgeAttribute()
    {
        // btn-info - time in - IN - 1
        // btn-danger - time out - OUT - 2
        // btn-warning - break start - BREAK START - 3
        // btn-success - break end - BREAK END - 4	

        switch ($this->id) {
            case 1:
                return badge('btn-info', $this->name);
            break;

            case 2:
                return badge('btn-danger', $this->name);
            break;

            case 3:
                return badge('btn-warning', $this->name);
            break;

            case 4:
                return badge('btn-success', $this->name);
            break;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
