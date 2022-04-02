<?php

namespace App\Models;

use App\Models\Model;

class PayrollPeriod extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'payroll_periods';
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
    public function close()
    {
        $this->attributes['status'] = 0;
        return $this;
    }

    public function open()
    {
        $this->attributes['status'] = 1;
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function grouping()
    {
        return $this->belongsTo(\App\Models\Grouping::class);
    }

    public function withholdingTaxBasis()
    {
        return $this->belongsTo(\App\Models\WithholdingTaxBasis::class);
    }

    public function dailyTimeRecords()
    {
        return $this->hasMany(\App\Models\DailyTimeRecord::class);
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeOpened($query)
    {
        return $query->where('status', 1);
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 0);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    // i cast it since date_range field in backpack is in timestamp form so revision not affected
    public function setPayrollStartAttribute($value) {
        $this->attributes['payroll_start'] = carbonTimestampToDate($value);
    }

    // i cast it since date_range field in backpack is in timestamp form so revision not affected
    public function setPayrollEndAttribute($value) {
        $this->attributes['payroll_end'] = carbonTimestampToDate($value);
    }
}
