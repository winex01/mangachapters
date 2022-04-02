<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Model;

class EmploymentInformation extends Model
{
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'employment_informations';
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
        static::addGlobalScope('CurrentEmploymentInfoScope', function (Builder $builder) {
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

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeDaysPerYear($query)
    {
        return $query->where('field_name', '=', 'DAYS_PER_YEAR');
    }

    public function scopeWhereInFieldName($query, $values)
    {
        return $query->whereIn('field_name', $values);
    }

    public function scopeOrderByField($query)
    {   
        $orderByField = modelInstance('EmploymentInfoField')->pluck('name')->toArray();
        $sql = 'FIELD(field_name, "'.implode('","', $orderByField).'")';
        return $query->orderByRaw($sql);
    }

    public function scopeGrouping($query, $arrayIds = null)
    {
        if ($arrayIds == null) {
            return $query->where('field_name', '=', 'GROUPING');
        }

        if (!is_array($arrayIds)) {
            $arrayIds = (array) $arrayIds;
        }
        $query->where('field_name', '=', 'GROUPING');
        return $query->whereIn('field_value->id', $arrayIds);
    }

    public function scopeBasicRate($query)
    {
        return $query->where('field_name', '=', 'BASIC_RATE');
    }

    public function scopeDate($query, $date)
    {
        return $query->withoutGlobalScope('CurrentEmploymentInfoScope')
            ->whereRaw('(
                employment_informations.employee_id, 
                employment_informations.field_name, 
                employment_informations.created_at) = ANY(
                    SELECT 
                        t2.employee_id,
                        t2.field_name,
                        MAX(t2.created_at)
                    FROM employment_informations t2
                    WHERE t2.effectivity_date <= ?
                    GROUP BY t2.employee_id, t2.field_name
            )', $date);
    }
    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getFieldValueAttribute($value)
    {
        // if field_value is in json format
        if (isJson($value)) {
            $obj = json_decode($value);
            if (is_object($obj) && property_exists($obj, 'id')) {
                $class = convertToClassName(strtolower($this->field_name));

                switch ($this->field_name) {
                    case 'DAYS_PER_YEAR':
                        $temp = classInstance($class)->where('id', $obj->id)->first();
                        $fieldValue = $temp->days_per_year.' / '.$temp->days_per_week.' / '.$temp->hours_per_day;
                        break;
                    
                    default:
                        $fieldValue = classInstance($class)->where('id', $obj->id)->pluck('name')->first();
                        break;
                }

                return $fieldValue;                
            }
        }// end if isJson

        switch ($this->field_name) {
            case 'BASIC_ADJUSTMENT':
            case 'BASIC_RATE':
                // return number_format($value, config('appsettings.decimal_precision'));
                return pesoCurrency($value);
                break;
        }

        return $value;
    }

    public function getFieldValueJsonAttribute()
    {
        return $this->attributes['field_value'];
    }

    public function getFieldValueIdAttribute()
    {
        $temp = $this->attributes['field_value'];
        
        return (int)json_decode($temp)->id;
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setFieldNameAttribute($value)
    {
        $this->attributes['field_name'] = strtoupper($value);
    }
}
