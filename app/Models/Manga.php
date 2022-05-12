<?php

namespace App\Models;

use App\Models\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Notifications\Notifiable;

class Manga extends Model
{
    use HasSlug, Notifiable;

    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \LaravelInteraction\Bookmark\Concerns\Bookmarkable;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'mangas';
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
    public static function boot() 
    {
        parent::boot();

        static::deleted(function($data) {
            if ($data->photo) {
                (new self)->deleteFileFromStorage($data, $data->photo);
            }
        });

        static::addGlobalScope('orderByTitle', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->orderByTitle(); //* order the select field
        });

    }

    public function routeNotificationForDiscord()
    {
        return config('appsettings.discord_manga_channel_id');
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function chapters()
    {
        return $this->hasMany(\App\Models\Chapter::class);
    }

    public function latestChapter()
    {
        return $this->hasOne(\App\Models\Chapter::class)
                ->notInvalidLink()
                ->orderBy('chapter', 'desc')
                ->orderBy('created_at', 'desc');
                // ->latest(); // * use 2 line above instead of this
    }

    public function sources()
    {
        return $this->hasMany(\App\Models\Source::class);
    }

    public function type()
    {
        return $this->belongsTo(\App\Models\Type::class);
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeOrderByTitle($query)
    {
        $query->orderBy('title', 'asc');
    }
    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getPhotoAttribute($value)
    {
        return ($value != null) ? 'storage/'.$value : $value;
    }

    public function getNameAttribute()
    {
        return $this->attributes['title'];
    }

    public function getSourcesInHtmlAttribute()
    {
        $temp = $this->sources()->published()->pluck('url');

        $temp = collect($temp)->map(function ($item, $key) {
            $label = str_limit($item, 30);
            return anchorNewTab($item, $label, $item);
        })->toArray();
                
        return implode('</br>', $temp);
    }

    public function getAlternativeTitleInHtmlAttribute()
    {
        $temp = $this->alternative_title;

        // return str_replace('/', '<br>', $temp);

        return $temp;
    }

    public function getTitleInHtmlAttribute()
    {   
        $title = $this->title;

        if ($this->type_id == 2) {

            $title = $this->title. '<span class="badge badge-success align-middle" title="Novel">N</span>';
        }
     
        return '<a href="'.linkToShow('manga', $this->id).'">'.$title.'</a>';
    }

    public function getChapterListsInHtmlAttribute()
    {
        $chapters = $this->chapters()->orderByRelease()->simplePaginate(config('appsettings.home_chapters_entries')); 

        $html = '';
        foreach ($chapters as $chapter) {
            $link = anchorNewTab($chapter->url, trans('lang.chapter_description', [
                'chapter' => $chapter->chapter, 
                'release' => $chapter->release, 
            ]));

            $html .= $link;
            $html .= '<br>'; 
        } 

        $html .= '<div class="mb-1 mt-2">';
        $html .= $chapters->links();
        $html .= '</div>';

        return $html;
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
}
