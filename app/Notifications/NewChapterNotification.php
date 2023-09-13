<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
// use NotificationChannels\Discord\DiscordChannel; 
// use NotificationChannels\Discord\DiscordMessage;

class NewChapterNotification extends Notification
{
    use Queueable;

    protected $chapter;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($chapter)
    {
        $this->chapter = $chapter;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            'database',
            // DiscordChannel::class,
        ];
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
            'model' => $this->chapter->model,
            'id' => $this->chapter->id,
        ];
    }

    // public function toDiscord($notifiable)
    // {
    //     return DiscordMessage::create($this->getMessage());
    // }

    // private function getMessage() 
    // {
    //     return '```'.$this->chapter->manga->title.': Chapter '.$this->chapter->chapter.'! ```' .$this->chapter->url;
    // }
}
