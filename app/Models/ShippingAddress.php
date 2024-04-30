<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingAddress extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id','shipping_full_name','shipping_email','shipping_phone_number', 'shipping_address', 'shipping_city', 'shipping_region', 'shipping_postal_code', 'shipping_country'];



    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
