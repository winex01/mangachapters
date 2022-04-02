<?php

namespace App\Models;

use App\Models\Model;

class Holiday extends Model
{
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'holidays';
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
    public function locations()
    {
        return $this->belongsToMany(
            \App\Models\Location::class, 
            'holiday_location', 
            'holiday_id', 
            'location_id'
        );
    }

    public function holidayType()
    {
        return $this->belongsTo(\App\Models\HolidayType::class);
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
    public function getLocationsAsExportAttribute()
    {
        return implode(', ', $this->locations->pluck('name')->toArray());
    }

    public function getLocationsAsTextAttribute()
    {
        return $this->locations_as_export;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
