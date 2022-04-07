<?php

namespace App\Providers;

use App\Events\NewChapterScanned;
use App\Listeners\AssignNormalUserRole;
use App\Listeners\SendAdminNewUserNotification;
use App\Listeners\SendUserNewChapterNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            // SendEmailVerificationNotification::class, // dont verify email when register, but when he clicks the alert msg
            // TODO:: send new registered email verification but let the user still use the app even not verified
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
