<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;
    use CrudTrait; // backpack permission manager
    use HasRoles; // backpack permission manager
    use \Venturecraft\Revisionable\RevisionableTrait;
    use \App\Models\Traits\RevisionableInitTrait;
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \App\Models\Traits\SoftDeletesInitTrait;
    use \LaravelInteraction\Bookmark\Concerns\Bookmarker;

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Functions 
    |--------------------------------------------------------------------------
    */
    public function markEmailAsNotVerified()
    {
        $this->email_verified_at = null;
        $this->save();
    }
    /*
    |--------------------------------------------------------------------------
    | ACCESSORS 
    |--------------------------------------------------------------------------
    */
    public function getModelAttribute()
    {   
        $class = get_class($this);
        
        return str_replace('App\\Models\\', '', $class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS 
    |--------------------------------------------------------------------------
    */
    
}
