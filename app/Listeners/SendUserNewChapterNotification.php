<?php

namespace App\Listeners;

use App\Events\NewChapterScanned;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewChapterNotification;

class SendUserNewChapterNotification
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
     * @param  NewChapterScanned  $event
     * @return void
     */
    public function handle(NewChapterScanned $event)
    {
        $bookmarkedByUsers = $event->chapter->manga->bookmarkers;

        Notification::send($bookmarkedByUsers, new NewChapterNotification($event->chapter));
    }
}
