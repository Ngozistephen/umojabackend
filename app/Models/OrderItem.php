<?php

namespace App\Models;

use App\Models\User;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['order_id', 'user_id', 'vendor_id', 'product_id', 'quantity', 'price'];


    public function getPriceAttribute($value)
    {
        return $value / 100;
    }


    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value * 100;
    }
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
