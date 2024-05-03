<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingAddressResource extends JsonResource
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
            'user_id'=> $this->user_id,
            'full_name' => $this->shipping_full_name,
            'email' => $this->shipping_email,
            'phone_number' => $this->shipping_phone_number,
            'shipping_address' => $this->shipping_address,
            'shipping_city' => $this->shipping_city,
            'shipping_region' => $this->shipping_region,
            'shipping_postal_code' => $this->shipping_postal_code,
            'shipping_country' => $this->shipping_country,
            'created' => $this->created_at,
            
            // 'first_name' => $this->user?->first_name,
            // 'last_name' => $this->user?->last_name,
            // 'email' => $this->user?->email,
            // 'phone number' => $this->user?->phone_number,
            // 'product' => new ProductResource($this->product), 

            
        ];
    }
}
