<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ZoneRateResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingZoneResource extends JsonResource
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
            'user_id' => $this->user_id,
            'vendor_id' => $this->vendor_id,
            'rates' => ZoneRateResource::collection($this->whenLoaded('zoneRates')),
            'shipping_method_id' => $this->shipping_method_id,
            'shippingMethod' => $this->shippingMethod?->type,
            'name' => $this->name,
            'continent' => $this->contient,
            'countries' => $this->countries,
            // 'local_delivery_company' => $this->local_delivery_company,
            // 'local_delivery_address' => $this->local_delivery_address,
            // 'local_delivery_country_name' => $this->local_delivery_country_name,
            // 'local_delivery_city' => $this->local_delivery_city,
            // 'local_delivery_state' => $this->local_delivery_state,
            // 'local_delivery_apartment' => $this->local_delivery_apartment,
            // 'local_delivery_zipcode' => $this->local_delivery_zipcode,
            // 'local_delivery_phone_number' => $this->local_delivery_phone_number,
            // 'local_pickup_company' => $this->local_pickup_company,
            // 'local_pickup_address' => $this->local_pickup_address,
            // 'local_pickup_country_name' => $this->local_pickup_country_name,
            // 'local_pickup_city' => $this->local_pickup_city,
            // 'local_pickup_state' => $this->local_pickup_state,
            // 'local_pickup_apartment' => $this->local_pickup_apartment,
            // 'local_pickup_zipcode' => $this->local_pickup_zipcode,
            // 'local_pickup_phone_number' => $this->local_pickup_phone_number,
            'delivery_date_range' => $this->delivery_date_range,
            'created_at' => $this->created_at,
           
            // 'updated_at' => $this->updated_at,
            // 'deleted_at' => $this->deleted_at,
        ];
    }
}
