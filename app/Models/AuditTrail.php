<?php

namespace App\Models;

use App\Models\Model;

class AuditTrail extends Model
{
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'revisions';
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
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

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
    public function getOldValueAttribute($value)
    {
        if ($this->key == 'deleted_at') {
            $value = ($value == '') ? 'Active' : 'Deleted';
        }

        return $value;
    }

    public function getNewValueAttribute($value)
    {
        if ($this->key == 'deleted_at') {
            $value = ($value == '') ? 'Active' : 'Deleted';
        }

        return $value;
    }

    public function getChangeAttribute($value)
    {
        return $this->created_at->diffForHumans();
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
