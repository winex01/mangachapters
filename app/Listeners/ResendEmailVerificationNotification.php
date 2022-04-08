<?php

namespace App\Listeners;

use App\Events\ResendEmailVerification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResendEmailVerificationNotification implements ShouldQueue
{
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'high';

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
     * @param  ResendEmailVerification  $event
     * @return void
     */
    public function handle(ResendEmailVerification $event)
    {
        $event->user->sendEmailVerificationNotification();
    }
}
