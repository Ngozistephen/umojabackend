<?php

namespace App\Models;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LocalPickup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        
        'vendor_id',  
        'local_pickup_company',
        'local_pickup_address',
        'local_pickup_country_name',
        'local_pickup_city',
        'local_pickup_state',
        'local_pickup_apartment',
        'local_pickup_zipcode',
        'local_pickup_phone_number',
        
    ];





    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
