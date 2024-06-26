<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingMethodResource extends JsonResource
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
            'vendor_id'=> $this->vendor_id,
            'name' => $this->name,
            'admin_shipping_id' => $this->admin_shipping_id,
            'admin_shipping_name' => $this->admin_shipping_name,
            // 'duration' => $this->duration,
            // 'amount' => $this->amount,
            'created' => $this->created_at,
            
          

            
        ];
    }
}
