<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewNotification extends Notification
{
    use Queueable;

    public $review;
    public $user;
    public $rating;
    public $productName;
    public $reviewComment;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $review, $rating, $productName, $reviewComment)
    {
        $this->review = $review;
        $this->user = $user;
        $this->rating = $rating;
        $this->productName = $productName;
        $this->reviewComment = $reviewComment;
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
            'review_id' => $this->review->id,
            'rating' => $this->rating,
            'product_name' => $this->productName,
            'review_comment' => $this->reviewComment,
            'user_photo' => $this->user->user_profile,
            'message' => "{$this->user->first_name} {$this->user->last_name} has reviewed your product {$this->productName} with rating {$this->rating}",
        ];
    }
}
