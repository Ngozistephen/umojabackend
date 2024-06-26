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
        'return_window',
        'return_shipping_cost',
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
