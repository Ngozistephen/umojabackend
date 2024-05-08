<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
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
            'payment_method' => $this->payment_method,
            'last_card_digits' => $this->last_card_digits,
            'last_card_brand' => $this->last_card_brand,
            'expiry_month' => $this->expiry_month,
            'expiry_year' => $this->expiry_year,
            'created' => $this->created_at,
            
            // 'first_name' => $this->user?->first_name,
            // 'last_name' => $this->user?->last_name,
            // 'email' => $this->user?->email,
            // 'phone number' => $this->user?->phone_number,
  

            
        ];
    }
}
