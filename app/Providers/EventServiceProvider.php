<?php

namespace App\Providers;

use App\Events\NewChapterScanned;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Listeners\AssignNormalUserRole;
use App\Listeners\SendAdminNewUserNotification;
use App\Listeners\SendUserNewChapterNotification;
use App\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class, // my custom listener, let the user still use the app, even not verified 
            SendAdminNewUserNotification::class,
            AssignNormalUserRole::class,
        ],

        NewChapterScanned::class => [
            SendUserNewChapterNotification::class,
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
