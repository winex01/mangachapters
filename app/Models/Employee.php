<?php

namespace App\Models;

use App\Models\Model;

class Employee extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'employees';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    public static function boot() 
    {
        parent::boot();

        static::deleted(function($data) {
            if ($data->photo) {
                (new self)->deleteFileFromStorage($data, $data->photo);
            }
        });

        static::addGlobalScope('orderByFullName', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $orderBy = 'asc';
            $builder->orderBy('last_name', $orderBy);
            $builder->orderBy('first_name', $orderBy);
            $builder->orderBy('middle_name', $orderBy);
            $builder->orderBy('badge_id', $orderBy);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - A
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - B
    |--------------------------------------------------------------------------
    */
    public function bloodType()
    {
        return $this->belongsTo(\App\Models\BloodType::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - C
    |--------------------------------------------------------------------------
    */
    public function changeShiftSchedules()
    {
        return $this->hasMany(\App\Models\ChangeShiftSchedule::class);
    }

    public function citizenship()
    {
        return $this->belongsTo(\App\Models\Citizenship::class);
    }

    public function civilStatus()
    {
        return $this->belongsTo(\App\Models\CivilStatus::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - D
    |--------------------------------------------------------------------------
    */
    public function dailyTimeRecords()
    {
        return $this->hasMany(\App\Models\DailyTimeRecord::class);
    }

    public function dependents()
    {
        return $this->hasMany(\App\Models\Dependent::class);
    }

    public function dtrLogs()
    {
        return $this->hasMany(\App\Models\DtrLog::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - E
    |--------------------------------------------------------------------------
    */
    public function educationalBackgrounds()
    {
        return $this->hasMany(\App\Models\EducationalBackground::class);
    }

    public function employeeShiftSchedules()
    {
        return $this->hasMany(\App\Models\EmployeeShiftSchedule::class);
    }

    public function employmentInformation()
    {
        return $this->hasMany(\App\Models\EmploymentInformation::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - F
    |--------------------------------------------------------------------------
    */
    public function familyDatas()
    {
        return $this->hasMany(\App\Models\FamilyData::class);
    }
    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS - G
    |--------------------------------------------------------------------------
    */
    public function gender()
    {
        return $this->belongsTo(\App\Models\Gender::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - H
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - I
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - J
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - K
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - L
    |--------------------------------------------------------------------------
    */
    public function leaveApplications()
    {
        return $this->hasMany(\App\Models\LeaveApplication::class);
    }

    public function leaveApprovers()
    {
        return $this->hasMany(\App\Models\LeaveApprover::class);
    }

    public function leaveCredits()
    {
        return $this->hasMany(\App\Models\LeaveCredit::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - M
    |--------------------------------------------------------------------------
    */
    public function medicalInformations()
    {
        return $this->hasMany(\App\Models\MedicalInformation::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - N
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - O
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - P
    |--------------------------------------------------------------------------
    */
    public function performanceAppraisals()
    {
        return $this->hasMany(\App\Models\PerformanceAppraisal::class);
    }

    public function professionalOrgs()
    {
        return $this->hasMany(\App\Models\ProfessionalOrg::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - Q
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - R
    |--------------------------------------------------------------------------
    */
     public function religion()
    {
        return $this->belongsTo(\App\Models\Religion::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - S
    |--------------------------------------------------------------------------
    */
    public function skillAndTalents()
    {
        return $this->hasMany(\App\Models\SkillAndTalent::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - T
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - U
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->hasOne(\App\Models\User::class);
    }
    

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - V
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - W
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - X
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - Y
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - Z
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeOrderByFullName($query)
    {
        return $query->orderBy('last_name')
                ->orderBy('first_name')
                ->orderBy('middle_name')
                ->orderBy('badge_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getFullNameAttribute()
    {
        return $this->last_name.' '.$this->first_name.' '.$this->middle_name;
    }

    //alias of full_name
    public function getNameAttribute()
    {
        return $this->full_name_with_badge;
    }

    public function getFullNameWithBadgeAttribute()
    {
        return $this->full_name.' - ('.$this->badge_id.')';
    }

    public function getPhotoAttribute($value)
    {
        return ($value != null) ? 'storage/'.$value : $value;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setPhotoAttribute($value)
    {
        $attribute_name = 'photo';
        // or use your own disk, defined in config/filesystems.php
        $disk = 'public'; 
        // destination path relative to the disk above
        $destination_path = 'images/photo'; 

        $this->uploadImageToDisk($value, $attribute_name, $disk, $destination_path);
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function searchEmployeeNameLike($searchTerm) // this method is not a scope
    {
        return (new self())
                ->where('last_name', 'like', '%'.$searchTerm.'%')
                ->orWhere('first_name', 'like', '%'.$searchTerm.'%')
                ->orWhere('middle_name', 'like', '%'.$searchTerm.'%')
                ->orWhere('badge_id', 'like', '%'.$searchTerm.'%');
    }

    public function employeeNameAnchor()
    {
        $temp = $this->full_name_with_badge;
        $temp = str_limit($temp, 50);
        
        return anchorNewTab(
            employeeInListsLinkUrl($this->id), 
            $temp,
            $this->full_name_with_badge
        );
    }
}
