<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminNewMangaNotification extends Notification
{
    use Queueable;

    protected $manga;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($manga)
    {
        //
        $this->manga = $manga;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
            'model'      => $this->manga->model,
            'id'         => $this->manga->id,
            'by_user_id' => auth()->id(),
        ];
    }
}
