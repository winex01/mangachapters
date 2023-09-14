<?php

namespace App\Notifications;

use App\Models\Source;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AdminNewSourceNotification extends Notification
{
    use Queueable;

    protected $source;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Source $source)
    {
        //
        $this->source = $source;
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
            'model'      => $this->source->model,
            'id'         => $this->source->id,
            'by_user_id' => auth()->id(),
        ];
    }
}
