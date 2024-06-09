<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorFollowedNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $followersCount;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $followersCount)
    {
        $this->user = $user;
        $this->followersCount = $followersCount;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
                'message' => "{$this->user->first_name} {$this->user->last_name} has followed you.",
                'user_photo' => $this->user->user_profile,
                'followers_count' => $this->followersCount,
           
        ];
    }
}
