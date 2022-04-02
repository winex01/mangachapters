<?php

namespace App\Models;

use App\Models\Model;
class LeaveApplication extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'leave_applications';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    protected $revisionFormattedFields = [
        'status'      => 'options: 0.Pending|1.Approved|2.Denied',
    ];

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
    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(\App\Models\LeaveType::class);
    }

    public function leaveApprover()
    {
        return $this->belongsTo(\App\Models\LeaveApprover::class);
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeApproved($query)
    {
        return $query->where('status', 1);
    }

    public function scopeDenied($query)
    {
        return $query->where('status', 2);
    }

    public function scopePending($query)
    {
        return $query->where('status', 0);
    }

    public function scopeOrderByStatus($query, $columnDirection)
    {   
        $columnDirection = strtolower($columnDirection);
        $value = null;
        if ($columnDirection == 'asc') {
            $value = [1,2,0]; // A,D,P
        }else if ($columnDirection == 'desc') {
            $value = [0,2,1]; // P,D,A
        }

        $sql = 'FIELD(status, "'.implode('","', $value).'")';

        return $query->orderByRaw($sql);
    }

    
    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getApproversAttribute($value) 
    {
        return getApproversAttribute($this->attributes['approvers']);
    }

    public function getCreditUnitNameAttribute()
    {
        $unit = $this->credit_unit;
        if ($unit == 1) { 
            $unit = '1';
        }else {
            $unit = '.5';
        }

        return creditUnitLists()[$unit];
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
