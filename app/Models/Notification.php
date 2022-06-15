<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;

class Notification extends Model
{
    use MassPrunable;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'notifications';
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

    //* https://medium.com/code16/automatically-delete-read-database-notifications-in-laravel-59871ee3c98
    public function prunable(): Builder
    {
        return static::whereNotNull('read_at')->orWhere('created_at', '<', now()->subMonths(3));
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
