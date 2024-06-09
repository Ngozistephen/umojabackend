<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorOrderNotification extends Notification
{
    use Queueable;

    public $product;
    public $quantity;
    public $orderNumber;
    public $shippingFullName;
    public $productPhoto;

    /**
     * Create a new notification instance.
     */
    public function __construct($product, $quantity, $orderNumber, $shippingFullName, $productPhoto)
    {
        $this->product = $product;
        $this->quantity = $quantity;
        $this->orderNumber = $orderNumber;
        $this->shippingFullName = $shippingFullName;
        $this->productPhoto = $productPhoto;
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
            'product' => $this->product,
            'quantity' => $this->quantity,
            'order_number' => $this->orderNumber,
            'shipping_full_name' => $this->shippingFullName,
            'product_photo' => $this->productPhoto,
            'message' => "New order for product {$this->product} with quantity {$this->quantity}"
        ];
    }
}
