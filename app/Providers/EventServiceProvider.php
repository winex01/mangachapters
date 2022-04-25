<?php

namespace App\Providers;

use App\Events\NewChapterScanned;
use Illuminate\Auth\Events\Login;
use App\Listeners\SendWelcomeMessage;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Events\ResendEmailVerification;
use App\Listeners\AssignNormalUserRole;
use App\Listeners\LogUserLogin;
use App\Listeners\SendAdminNewUserNotification;
use App\Listeners\SendUserNewChapterNotification;
use App\Listeners\SendEmailVerificationNotification;
use App\Listeners\ResendEmailVerificationNotification;
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
            //* Not Queue
            AssignNormalUserRole::class, 
            SendWelcomeMessage::class,
            
            //* Queue
            SendEmailVerificationNotification::class, // my custom listener, let the user still use the app, even not verified 
            SendAdminNewUserNotification::class,
        ],

        // i added this event so i dont need to modify resend verification controller to queue resending email verification
        ResendEmailVerification::class => [
            // i created this new listener, bec. i dont want to convert SendEmailVerificationNotification as subscriber listener
            // in short it's pain in the ass
            //* Queue
            ResendEmailVerificationNotification::class,
        ],
        
        NewChapterScanned::class => [
            //* Not Queue
            SendUserNewChapterNotification::class,
        ],
        
        Login::class => [
            //* Queue
            LogUserLogin::class,
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
