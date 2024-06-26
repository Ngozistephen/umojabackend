<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Vendor;
use App\Models\ShippingZone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = ['vendor_id','name', 'admin_shipping_id'];

    // public function getAmountAttribute($value)
    // {
    //     return $value / 100;
    // }


    // public function setAmountAttribute($value)
    // {
    //     $this->attributes['amount'] = $value * 100;
    // }

    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(User::class);
    // }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'shipping_method_id');
    }

    public function shippingZones()
    {
        return $this->hasMany(ShippingZone::class);
    }

}
