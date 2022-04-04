<?php

namespace App\Models;

use App\Models\Model;

class Chapter extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'chapters';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];

    protected $dates = [
        'created_at',
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
    public function manga()
    {
        return $this->belongsTo(\App\Models\Manga::class);
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
    public function getReleaseAttribute()
    {
        $textColor = '';

        if (isTimestampLessThanHoursAgo($this->created_at, 1)) {
            $textColor = 'text-danger';
        }elseif (isTimestampLessThanHoursAgo($this->created_at, 2)) {
            $textColor = 'text-success';
        }else {
            $textColor = 'text-dark';            
        }

        return '<span class="font-weight-light '.$textColor.'">'.$this->created_at->diffForHumans().'</span>';
    }

    public function getChapterLinkAttribute()
    {
        return anchorNewTab($this->url, $this->chapter, $this->url);
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
