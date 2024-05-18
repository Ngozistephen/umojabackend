<?php

namespace App\Models;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    // protected $guarded = ['id'];
    protected $fillable = [
            'user_id', 
            // 'product_id',
            'vendor_id',
            'shipping_address_id', 
            'shipping_method_id', 
            'payment_method_id', 
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

   
   

    // public function payment(): HasOne
    // {
    //     return $this->hasOne(Payment::class);
    // }

    // public function shippingAddress(): HasOne
    // {
    //     return $this->hasOne(ShippingAddress::class);
    // }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(ShippingAddress::class, 'shipping_address_id');
    }

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }


    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot(['qty', 'tracking_id', 'price', 'vendor_id' ]);
    }

   

}

