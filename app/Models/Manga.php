<?php

namespace App\Models;

use App\Models\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Manga extends Model
{
    use HasSlug;

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
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeMyBookmarked($query)
    {
        $query->whereBookmarkedBy(auth()->user());
    }

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

        return str_replace('/', '<br>', $temp);
    }

    public function getTitleInHtmlAttribute()
    {
        return $this->title. '<span class="mt-n5 badge badge-success" title="Noval">N</span>';
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
