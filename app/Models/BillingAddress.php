<?php

namespace App\Models;

use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BillingAddress extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','billing_phone_number','billing_address','billing_city', 'billing_region', 'billing_postal_code', 'billing_country'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

 
    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'billing_address_id');
    }

}
