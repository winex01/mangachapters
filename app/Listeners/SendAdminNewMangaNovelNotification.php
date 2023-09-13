<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\NewMangaOrNovelAdded;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminNewMangaNotification;

class SendAdminNewMangaNovelNotification
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
        //
        $usersWithAdminPermission = User::permission('admin_notify_newly_created_manga')->get();
        Notification::send($usersWithAdminPermission, new AdminNewMangaNotification($event->manga));
    }
}
