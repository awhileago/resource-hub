<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostingFullNotification extends Notification
{
    use Queueable;

    public $posting;
    /**
     * Create a new notification instance.
     */
    public function __construct($posting)
    {
        $this->posting = $posting;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'data' => [
                'title' => 'Posting Application Full',
                'message' => "The posting '{$this->posting->title}' has reached full application capacity.",
                'url' => url("/postings/{$this->posting->id}"),
            ]
        ];
    }

    public function toBroadcast(object $notifiable): array
    {
        return [
            'data' => [
                'title' => 'Posting Application Full',
                'message' => "The posting '{$this->posting->title}' has reached full application capacity.",
                'url' => url('/postings/' . $this->posting->id),
            ],
        ];
    }
}
