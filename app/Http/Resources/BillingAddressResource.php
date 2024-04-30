<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillingAddressResource extends JsonResource
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
            'billing_phone_number' => $this->billing_phone_number,
            'billing_address' => $this->billing_address,
            'billing_city' => $this->billing_city,
            'billing_region' => $this->billing_region,
            'billing_postal_code' => $this->billing_postal_code,
            'billing_country' => $this->billing_country,
            'created' => $this->created_at,
            
            // 'first_name' => $this->user?->first_name,
            // 'last_name' => $this->user?->last_name,
            // 'email' => $this->user?->email,
            // 'phone number' => $this->user?->phone_number,
  

            
        ];
    }
}
