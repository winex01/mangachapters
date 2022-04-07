<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use App\Notifications\WelcomeMessageNotification;

class SendWelcomeMessage
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $event->user->notify(new WelcomeMessageNotification());
    }
}
