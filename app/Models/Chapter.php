<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Notifications\Notifiable;

class Chapter extends Model
{
    use Notifiable;

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
    public function routeNotificationForDiscord()
    {
        return config('appsettings.discord_chapter_channel_id');
    }

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
    public function scopeOrderByRelease($query)
    {
        $query->orderBy('created_at', 'desc');
        $query->orderBy('chapter', 'desc');
    }

    public function scopeNotInvalidLink($query)
    {
        $query->where('invalid_link', false);
    }
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
        }elseif (isTimestampLessThanHoursAgo($this->created_at, 3)) {
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
