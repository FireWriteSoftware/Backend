<?php

namespace App\Notifications\Webhook;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Channels\DiscordMessage;
use App\Channels\DiscordWebhookChannel;

class AnnouncementCreated extends Notification
{
    use Queueable;

    protected $announcement;

    public function __construct($announcement)
    {
        $this->announcement = $announcement;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [DiscordWebhookChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toDiscord($notifiable)
    {
        return DiscordMessage::create()
            ->username(config('app.name', 'Articly'))
            ->embeds([
                [
                    "title" => $this->announcement->title,
                    "description" => $this->announcement->description,
                    "url" => config('app.url_frontend'),
                    "author" => [
                        "name" => $this->announcement->user->name,
                        "icon_url" => $this->announcement->user->profile_picture
                    ]
                ]
            ])
            ->tts(false);
    }
}
