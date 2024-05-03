<?php

namespace App\Models;

use App\Models\User;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\ShippingMethod;
use App\Models\ShippingAddress;
use App\Enums\FulfillmentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    // protected $guarded = ['id'];
    protected $fillable = [
            'user_id', 
            'shipping_address_id', 
            'shipping_method_id', 
            'billing_address_id', 
            'order_number', 
            'fulfillment_status', 
            'sub_total', 
            'total_amount', 
            'delivery_charge', 
            'payment_status', 
            'order_status',
            'tracking_number',
            'discount_code',
            'read',
            'paid_at',
            'cancelled_at',
            'delivered_at',
        ];


        protected $casts = [
            'fulfillment_status' => FulfillmentStatus::class,
            'payment_status' => PaymentStatus::class,
            'order_status' => OrderStatus::class,
              //   $order->fulfillment_status->isFulfilled(), how to use it in my view 
        ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // public function payment(): HasOne
    // {
    //     return $this->hasOne(Payment::class);
    // }

    public function shippingAddress(): HasOne
    {
        return $this->hasOne(ShippingAddress::class);
    }

    public function shippingMethod()
    {
        return $this->hasOne(ShippingMethod::class);
    }

    public function billingAddress()
    {
        return $this->hasOne(BillingAddress::class);
    }
}

