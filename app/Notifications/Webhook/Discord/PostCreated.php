<?php

namespace App\Notifications\Webhook\Discord;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Channels\DiscordMessage;
use App\Channels\DiscordWebhookChannel;

class PostCreated extends Notification
{
    use Queueable;

    protected $post;

    public function __construct($post)
    {
        $this->post = $post;
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
                    "title" => __('post.webhook.created', ['title' => $this->post->title]),
                    "description" => $this->post->content,
                    "url" => config('app.url_frontend') . '/posts/' . $this->post->id,
                    "author" => [
                        "name" => $this->post->user->name,
                        "icon_url" => $this->post->user->profile_picture
                    ]
                ]
            ])
            ->tts(false);
    }
}
