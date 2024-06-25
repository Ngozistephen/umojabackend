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
            '14_days' => $this->{'14_days'},
            '30_days' => $this->{'30_days'},
            '90_days' => $this->{'90_days'},
            'unlimited' => $this->unlimited,
            'custom_days' => $this->custom_days,
            'customer_provides_return_shipping' => $this->customer_provides_return_shipping,
            'free_return_shipping' => $this->free_return_shipping,
            'flat_rate_return_shipping' => $this->flat_rate_return_shipping,
            'no_refund' => $this->no_refund,
            'full_refund' => $this->full_refund,
            '50%_refund' => $this->{'50%_refund'},
            'restocking_fee' => $this->restocking_fee,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
