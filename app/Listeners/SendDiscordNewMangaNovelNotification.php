<?php

namespace App\Listeners;

use App\Events\NewMangaOrNovelAdded;
use App\Notifications\DiscordNewMangaNotification;

class SendDiscordNewMangaNovelNotification
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
     * @param  \App\Events\NewMangaOrNovelAdded  $event
     * @return void
     */
    public function handle(NewMangaOrNovelAdded $event)
    {
        $event->manga->notify(new DiscordNewMangaNotification($event->manga));
    }
}
