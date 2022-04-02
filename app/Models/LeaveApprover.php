<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Builder;
class LeaveApprover extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'leave_approvers';
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
    protected static function booted()
    {
        static::addGlobalScope('CurrentLeaveApproverScope', function (Builder $builder) {
            (new self)->scopeDate($builder, currentDate());
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee::class);
    }

    public function leaveApplications()
    {
        return $this->hasMany(\App\Models\LeaveApplication::class);
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeDate($query, $date)
    {
        return $query->withoutGlobalScope('CurrentLeaveApproverScope')
            ->whereRaw('(
                '.$this->table.'.employee_id, 
                '.$this->table.'.created_at) = ANY(
                    SELECT 
                        t2.employee_id,
                        MAX(t2.created_at)
                    FROM '.$this->table.' t2
                    WHERE t2.effectivity_date <= ?
                    AND t2.deleted_at is null
                    GROUP BY t2.employee_id
            )', $date);
    }

    public function scopeApproversEmployeeId($query, $arrayOrInt)
    {
        if (!is_array($arrayOrInt)) {
            $arrayOrInt = (array)$arrayOrInt;
        }

        $firstLoop = true;
        foreach ($arrayOrInt as $emp_id) {
            
            if ($firstLoop) {
                $query->whereJsonContains(
                    'approvers', 
                    [['employee_id' => (string)$emp_id]]
                );
            }else { // use orWhere
                $query->orWhereJsonContains(
                    'approvers', 
                    [['employee_id' => (string)$emp_id]]
                );
            }

            $firstLoop = false;
        }
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
