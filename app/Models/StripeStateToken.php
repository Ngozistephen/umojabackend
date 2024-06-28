<?php

namespace App\Models;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StripeStateToken extends Model
{
    use HasFactory;

    protected $table = 'stripe_state_tokens';

    protected $fillable = [
        'created_at',
        'updated_at',
        'vendor_id',
        'token',
    ];


    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }


}
