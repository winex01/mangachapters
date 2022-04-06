<?php

namespace App\Providers;

use App\Events\NewChapterScanned;
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
            // TODO:: notify admin if someone register
            // TODO:: let the user verify the account again if the user change the email.
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
