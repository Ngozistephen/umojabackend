<?php

namespace App\Models;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Policy extends Model
{
    use HasFactory,SoftDeletes;

  


    protected $fillable = [
        'vendor_id',
        // '14_days',
        // '30_days',
        // '90_days',
        // 'unlimited',
        // 'custom_days',
        // 'customer_provides_return_shipping',
        // 'free_return_shipping',
        // 'flat_rate_return_shipping',
      
        'return_window',
        'return_window_cost',
        'refund_policy'
    ];


    // protected $casts = [
    //     'restocking_fee' => 'boolean',
    // ];

    
    
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }


}
