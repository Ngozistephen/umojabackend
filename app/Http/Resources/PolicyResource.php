<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PolicyResource extends JsonResource
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
            'return_window' => $this->return_window,
            'return_shipping_cost' => $this->return_shipping_cost,
            'refund_policy' => $this->refund_policy,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
