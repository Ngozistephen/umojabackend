<?php

namespace App\Models;

use App\Models\User;
use App\Models\Vendor;
use App\Models\ZoneRate;
use App\Models\AdminShipping;
use App\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingZone extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'vendor_id',
        'admin_shipping_id',
        'name',
        'continent',
        'countries',
        // 'local_delivery_company',
        // 'local_delivery_address',
        // 'local_delivery_country_name',
        // 'local_delivery_city',
        // 'local_delivery_state',
        // 'local_delivery_apartment',
        // 'local_delivery_zipcode',
        // 'local_delivery_phone_number',
        // 'local_pickup_company',
        // 'local_pickup_address',
        // 'local_pickup_country_name',
        // 'local_pickup_city',
        // 'local_pickup_state',
        // 'local_pickup_apartment',
        // 'local_pickup_zipcode',
        // 'local_pickup_phone_number',
        'delivery_date_range' 
    ];

    protected $casts = [
        'countries' => 'json',
        
    ];
   

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }
    
    public function adminShipping()
    {
        return $this->belongsTo(AdminShipping::class);
    }


    public function zoneRates()
    {
        return $this->hasMany(ZoneRate::class);
    }

}
