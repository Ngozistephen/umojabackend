<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocalPickupResource extends JsonResource
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
            'local_pickup_company' => $this->local_pickup_company,
            'local_pickup_address' => $this->local_pickup_address,
            'local_pickup_country_name' => $this->local_pickup_country_name,
            'local_pickup_city' => $this->local_pickup_city,
            'local_pickup_state' => $this->local_pickup_state,
            'local_pickup_apartment' => $this->local_pickup_apartment,
            'local_pickup_zipcode' => $this->local_pickup_zipcode,
            'local_pickup_phone_number' => $this->local_pickup_phone_number,
        ];
    }
}
