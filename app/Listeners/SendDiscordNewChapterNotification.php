<?php

namespace App\Listeners;

use App\Events\NewChapterScanned;
use App\Notifications\NewChapterNotification;

class SendDiscordNewChapterNotification
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
     * @param  \App\Events\NewChapterScanned  $event
     * @return void
     */
    public function handle(NewChapterScanned $event)
    {
        $event->chapter->notify(new NewChapterNotification($event->chapter));
    }
}
