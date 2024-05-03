<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','type','duration','amount'];

    public function getAmountAttribute($value)
    {
        return $value / 100;
    }


    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = $value * 100;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

}
