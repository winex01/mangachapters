<?php

namespace App\Models;

use App\Models\Model;

class ShiftSchedule extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $revisionFormattedFields = [
        'dynamic_break' => 'boolean:No|Yes',
        'open_time'     => 'boolean:No|Yes',
    ];

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'shift_schedules';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    protected $fakeColumns = [
        'working_hours',
        'overtime_hours',
    ];

    protected $casts = [
        'working_hours' => 'array',
        'overtime_hours' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    protected static function booted()
    {
        static::addGlobalScope(new \App\Scopes\OrderByNameScope);
    }
    
    private function jsonHoursText($arrayKey)
    {
        if ($this->open_time) {
            return;
        }

        $value = null;

        $data = array_key_exists($arrayKey, $this->{$arrayKey}) ? $this->{$arrayKey}[$arrayKey] : $this->{$arrayKey};

        $value = [];
        foreach ($data as $hr) {
            if (!empty($hr)) {
                $start = carbonInstance($hr['start'])->format(config('appsettings.carbon_time_format'));
                $end = carbonInstance($hr['end'])->format(config('appsettings.carbon_time_format'));
                $value[] = $start .' - '.$end;
            }
        }

        return implode(",<br>", $value);
    }

    public function nameAnchor()
    {
        $url = backpack_url('shiftschedules/'.$this->id.'/show');
        return anchorNewTab($url, $this->name);
    }
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
    public function getWorkingHoursAsTextAttribute()
    {
        if ($this->open_time) {
            return trans('lang.shift_schedules_open_time');
        }

        return $this->jsonHoursText('working_hours');
    }

    public function getOvertimeHoursAsTextAttribute()
    {
        return $this->jsonHoursText('overtime_hours');
    }

    public function getDynamicBreakCreditAttribute($value)
    {
        return ($this->dynamic_break) ? $value : null;
    }

    public function getWorkingHoursAsExportAttribute()
    {
        return str_replace('<br>', "\n", $this->working_hours_as_text);
    }
    
    public function getOvertimeHoursAsExportAttribute()
    {
        return str_replace('<br>', "\n", $this->overtime_hours_as_text);
    }

    public function getWorkingHoursAttribute($value)
    {
        return ($this->open_time) ? null : json_decode($value, true);
    }

    public function getOvertimeHoursAttribute($value)
    {
        return ($this->open_time) ? null : json_decode($value, true);
    }

    // won't show in model attribute but it can be use
    public function getStartWorkingHoursAttribute()
    {
        return $this->working_hours['working_hours'][0]['start'];
    }

     // won't show in model attribute but it can be use   
    public function getEndWorkingHoursAttribute()
    {
        return $this->working_hours['working_hours'][count($this->working_hours['working_hours'])-1]['end'];
    }

    public function getOvertimeHoursInArrayAttribute()
    {
        $data = [];
        foreach ($this->overtime_hours as $temp) {
            if ( array_key_exists('start', $temp) && array_key_exists('end', $temp) ) {
                $start = carbonInstance($temp['start'])->format(config('appsettings.carbon_time_format'));
                $end = carbonInstance($temp['end'])->format(config('appsettings.carbon_time_format'));
                $data[] = $start .' - '. $end;
            }
        }

        return $data;
    }

    public function getWorkingHoursInArrayAttribute()
    {
        if ($this->open_time == true) {
            return;
        }

        $data = [];
        foreach ($this->working_hours as $temp) {
            if ( array_key_exists('start', $temp) && array_key_exists('end', $temp) ) {
                $start = carbonInstance($temp['start'])->format(config('appsettings.carbon_time_format'));
                $end = carbonInstance($temp['end'])->format(config('appsettings.carbon_time_format'));
                $data[] = $start .' - '. $end;
            }
        }

        return $data;
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setRelativeDayStartAttribute($value)
    {
        $this->attributes['relative_day_start'] = ($this->open_time) ? null : $value;
    }

    public function setDynamicBreakCreditAttribute($value)
    {
        $this->attributes['dynamic_break_credit'] = ($this->dynamic_break) ? $value : null;
    }
}