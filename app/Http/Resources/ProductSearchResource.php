<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductSearchResource extends JsonResource
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
            'sku' => $this->sku,
            // 'status' => $this->status,
            'vendor_firstname'=> $this->user?->first_name,
            'vendor_lastname'=> $this->user?->last_name,
            'vendor_profile_photo'=> $this->user?->vendor->profile_photo,
            'vendor_id'=> $this->user?->vendor->id,
            'vendor_state'=> $this->user?->vendor->state,
            'vendor_country'=> $this->user?->vendor->country_name,
            'category_name' => $this->category->name,
            'sub_category_name' => $this->subCategory->name,
            'product_spec' => $this->product_spec,
            'photo' => $this->photo,
            'price' => $this->price,
            // 'material' => $this->material,
            'name' => $this->name,
            'made_with_ghana_leather' => $this->made_with_ghana_leather,
            'description' => $this->description,
            'variations' => ProductVariationResource::collection($this->whenLoaded('variations')),
            // 'mini_stock' => $this->mini_stock,

        ];
    }
}
