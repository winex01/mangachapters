<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Model;

class EmployeeShiftSchedule extends Model
{
    Use \Illuminate\Database\Eloquent\SoftDeletes;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'employee_shift_schedules';
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
        static::addGlobalScope('CurrentEmployeeShiftScheduleScope', function (Builder $builder) {
            (new self)->scopeDate($builder, currentDate());
        });
    }

    public function details($date)
    {
        $day = daysOfWeek()[getWeekday($date)];;
        return $this->{$day}()->first();
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

    public function monday()
    {
        return $this->belongsTo(\App\Models\ShiftSchedule::class, 'monday_id');
    }

    public function tuesday()
    {
        return $this->belongsTo(\App\Models\ShiftSchedule::class, 'tuesday_id');
    }

    public function wednesday()
    {
        return $this->belongsTo(\App\Models\ShiftSchedule::class, 'wednesday_id');
    }

    public function thursday()
    {
        return $this->belongsTo(\App\Models\ShiftSchedule::class, 'thursday_id');
    }

    public function friday()
    {
        return $this->belongsTo(\App\Models\ShiftSchedule::class, 'friday_id');
    }

    public function saturday()
    {
        return $this->belongsTo(\App\Models\ShiftSchedule::class, 'saturday_id');
    }

    public function sunday()
    {
        return $this->belongsTo(\App\Models\ShiftSchedule::class, 'sunday_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeDate($query, $date)
    {
        return $query->withoutGlobalScope('CurrentEmployeeShiftScheduleScope')
            ->whereRaw('(
                    employee_shift_schedules.employee_id,
                    employee_shift_schedules.effectivity_date,
                    employee_shift_schedules.created_at
                ) = ANY(
                    SELECT 
                        t2.employee_id,
                        t2.effectivity_date,
                        MAX(t2.created_at)
                    FROM employee_shift_schedules t2
                    WHERE t2.effectivity_date <= ?
                    AND t2.effectivity_date = (
                        SELECT MAX(t3.effectivity_date) FROM employee_shift_schedules t3 
                        WHERE t3.employee_id = t2.employee_id 
                        AND t3.effectivity_date <= ?
                        AND t3.deleted_at is null
                    )
                    GROUP BY t2.employee_id, t2.effectivity_date
            )', [
                $date,
                $date
            ]);
    }

    public function scopeWhereShiftScheduleId($query, $shiftScheduleId)
    {
        return $query->withoutGlobalScope('CurrentEmployeeShiftScheduleScope')
            ->where(function ($query) use ($shiftScheduleId) {
                $query->where('monday_id', $shiftScheduleId);
                $query->orWhere('tuesday_id', $shiftScheduleId);
                $query->orWhere('wednesday_id', $shiftScheduleId);
                $query->orWhere('thursday_id', $shiftScheduleId);
                $query->orWhere('friday_id', $shiftScheduleId);
                $query->orWhere('saturday_id', $shiftScheduleId);
                $query->orWhere('sunday_id', $shiftScheduleId);
            });
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
}
