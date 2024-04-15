<?php

namespace App\Http\Resources;

use App\Http\Resources\VariationOptionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class VariationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'options' => VariationOptionResource::collection($this->whenLoaded('variationOptions')),
    
        ];
    }
}
