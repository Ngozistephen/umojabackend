<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->makeHidden([
                'change_amount', 'distance', 'duration', 'grand_total',
                'service_charge', 'delivery_fee', 'delivered_time',
                'recieved_time', 'reject_reason', 'rejected_time',
                'served_time', 'transit_time', 'total'
            ])

            
        ];

    }
}
