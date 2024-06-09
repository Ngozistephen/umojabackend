<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductStockNotification extends Notification
{
    use Queueable;

    protected $product;
    protected $remainingStock;

    /**
     * Create a new notification instance.
     */
    public function __construct($product, $remainingStock)
    {
        $this->product = $product;
        $this->remainingStock = $remainingStock;
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
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'product_photo' => $this->product->photo,
            'remaining_stock' => $this->remainingStock,
            'mini_stock' => $this->product->mini_stock,
            'message' => "'{$this->product->name}' is almost out of stock. Only {$this->remainingStock} items left.",
        ];
    }
}
