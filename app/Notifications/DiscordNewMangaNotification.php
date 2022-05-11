<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Discord\DiscordChannel; 
use NotificationChannels\Discord\DiscordMessage; 

class DiscordNewMangaNotification extends Notification
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
        return [DiscordChannel::class];
    }

    public function toDiscord($notifiable)
    {
        return DiscordMessage::create($this->getMessage());
    }

    private function getMessage() 
    {
        return '```New '.strtolower($this->manga->type->name).': '.$this->manga->title.' - '.$this->manga->alternative_title.' ```';
    }
}
