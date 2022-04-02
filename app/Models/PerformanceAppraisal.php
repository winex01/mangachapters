<?php

namespace App\Models;

use App\Models\Model;

class PerformanceAppraisal extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'performance_appraisals';
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
    public function appraiser()
    {
        return $this->belongsTo(\App\Models\Employee::class, 'appraiser_id');
    }   

    public function appraisalType()
    {
        return $this->belongsTo(\App\Models\AppraisalType::class);
    }

    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee::class);
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
    public function scopeTotalRatingBetween($query, $rating_from, $rating_to)
    {
        // NOTE:: if you modify this please check also JS at custom_performance_appraisal_select2.blade.php
        $total_rating = "
            (
                (job_function + productivity + attendance) / 30  * 50 
                +
                (planning_and_organizing + innovation + technical_domain) / 30 * 25 
                +
                (sense_of_ownership + customer_relation + professional_conduct) /30 * 25
            ) 
        ";
        return $query->whereRaw("($total_rating BETWEEN ? AND ?)", [$rating_from, $rating_to]);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getInterpretationAttribute()
    {
        // NOTE:: if you modify this please check also JS at custom_performance_appraisal_select2.blade.php
        $totalRaing = $this->total_rating;
        return \App\Models\AppraisalInterpretation::where(function ($q) use ($totalRaing) {
            $q->where('rating_from', '<=', $totalRaing);
            $q->where('rating_to', '>=', $totalRaing);
        })->get(['name', 'rating_from','rating_to'])
        ->pluck('name_with_rating_percentage')
        ->first();
    }

    public function getTotalRatingAttribute()
    {
        return $this->individual_performance_rating + $this->job_competencies_rating + $this->organizational_competencies_rating;
    }

    public function getIndividualPerformanceRatingAttribute()
    {
        $result = ( ($this->job_function + $this->productivity + $this->attendance) / 30 ) * 50; // 50% 
        
        return number_format($result, config('appsettings.decimal_precision'));
    }

    public function getJobCompetenciesRatingAttribute()
    {
        $result = ( ($this->planning_and_organizing + $this->innovation + $this->technical_domain) / 30 ) * 25; // 25% 
        
        return number_format($result, config('appsettings.decimal_precision'));   
    }


    public function getOrganizationalCompetenciesRatingAttribute()
    {
        $result = ( ($this->sense_of_ownership + $this->customer_relation + $this->professional_conduct) / 30 ) * 25; // 25% 
        
        return number_format($result, config('appsettings.decimal_precision'));   
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
