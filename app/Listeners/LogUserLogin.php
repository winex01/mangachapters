<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;

class LogUserLogin
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
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        if (config('appsettings.log_user_login')) {
            Log::info('LOGIN', [
                'name' => $event->user->name,
                'email' => $event->user->email,
                'verified' => $event->user->email_verified_at,
                'login_at' => currentDateTime(),
                'created_at' => $event->user->created_at,
            ]);
        }
    }
}
