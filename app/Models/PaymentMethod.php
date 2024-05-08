<?php

namespace App\Models;

use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id','payment_method','last_card_digits','last_card_brand', 'expiry_month', 'expiry_year', 'email'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

 
    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'payment_method_id');
    }

}
