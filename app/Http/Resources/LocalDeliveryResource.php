<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocalDeliveryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [  
            'id' => $this->id,
            'vendor_id' => $this->vendor_id,
            'local_delivery_company' => $this->local_delivery_company,
            'local_delivery_address' => $this->local_delivery_address,
            'local_delivery_country_name' => $this->local_delivery_country_name,
            'local_delivery_city' => $this->local_delivery_city,
            'local_delivery_state' => $this->local_delivery_state,
            'local_delivery_apartment' => $this->local_delivery_apartment,
            'local_delivery_zipcode' => $this->local_delivery_zipcode,
            'local_delivery_phone_number' => $this->local_delivery_phone_number,
        ];

    }
    
}
