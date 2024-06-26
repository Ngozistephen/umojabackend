<?php

namespace App\Models;

use App\Models\User;
use App\Models\ShippingZone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminShipping extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','name'];



    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function shippingZones()
    {
        return $this->hasMany(ShippingZone::class);
    }
}
