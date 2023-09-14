<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\NewSourceAdded;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminNewSourceNotification;

class SendAdminNewSourceNotification
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
     * @param  \App\Events\NewSourceAdded  $event
     * @return void
     */
    public function handle(NewSourceAdded $event)
    {
        // NOTE:: i only use 1 permission both from new added Manga/Source: admin_notify_newly_created_manga
        $usersWithAdminPermission = User::permission('admin_notify_newly_created_manga')->get();
        Notification::send($usersWithAdminPermission, new AdminNewSourceNotification($event->source));
    }
}
