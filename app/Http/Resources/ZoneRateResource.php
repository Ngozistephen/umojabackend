<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ZoneRateResource extends JsonResource
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
            'shipping_zone_id' => $this->shipping_zone_id,
            'name' => $this->name,
            'custom_rate_name' => $this->custom_rate_name,
            'condition' => $this->condition,
            'custom_delivery_description' => $this->custom_delivery_description,
            'price' => $this->price,
            'based_on_item_weight' => $this->based_on_item_weight,
            'based_on_order_price' => $this->based_on_order_price,
            'minimum_weight' => $this->minimum_weight,
            'maximum_weight' => $this->maximum_weight,
            'minimum_price' => $this->minimum_price,
            'maximum_price' => $this->maximum_price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
