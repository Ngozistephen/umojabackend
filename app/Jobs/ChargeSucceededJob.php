<?php

namespace App\Jobs;

use Log;
use App\Models\User;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\WebhookClient\Models\WebhookCall;

class ChargeSucceededJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /** @var \Spatie\WebhookClient\Models\WebhookCall */
    public $webhookCall;

    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    public function handle()
    {
        $charge = $this->webhookCall->payload['data']['object'];
        $user = User::where('stripe_id', $charge['customer'])->first();
    
        if ($user) {
            $orderNumber = $charge['metadata']['order_number'];
            $order = Order::where('order_number', $orderNumber)->first();
    
            if ($order) {
                $order->update(['paid_at' => now() , 'payment_status' => 'paid']);
                $paymentMethod = $user->defaultPaymentMethod;
                if ($paymentMethod && $paymentMethod->email) {
                    Mail::to($paymentMethod->email)->send(new OrderConfirmation($order));
                    $order->update(['delivered_at' => now() ]);
                } else {
                    Log::warning('Payment method email is not available or invalid.');
                }
                
            } else {
                \Log::error('Order not found for payment: ' . $orderNumber);
            }
        }

        // you can access the payload of the webhook call with `$this->webhookCall->payload`
    }
}