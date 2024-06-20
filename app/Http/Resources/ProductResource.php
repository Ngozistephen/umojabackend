<?php

namespace App\Http\Resources;

use App\Http\Resources\ProductAttributeResource;
use App\Http\Resources\ProductVariationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'sku' => $this->sku,
            'status' => $this->status,
            'vendor_firstname'=> $this->user?->first_name,
            'vendor_lastname'=> $this->user?->last_name,
            'vendor_profile_photo'=> $this->user?->vendor->profile_photo,
            'vendor_business_name' => $this->user?->vendor->business_name,
            'vendor_id'=> $this->user?->vendor->id,
            'vendor_state'=> $this->user?->vendor->state,
            'vendor_country'=> $this->user?->vendor->rep_country,
            'category_name' => $this->category?->name,
            'sub_category_name' => $this->subCategory?->neted_subcategories, 
            // 'sub_category_name' => $this->subCategory?->name, 
            // 'product_rating' => $this->reviews?->rating, 
            'product_rating' => $this->reviews->avg('rating'),
            'photo' => $this->photo,
            'price' => $this->price,
            'material' => $this->material,
            'name' => $this->name,
            'description' => $this->description,
            'mini_stock' => $this->mini_stock,
            'tags' => $this->tags,
            'sizes' => $this->sizes,
            'colors' => $this->colors,
            'materials' => $this->materials,
            'styles' => $this->styles,
            'unit' => $this->unit,
            'product_spec' => $this->product_spec,
            'made_with_ghana_leather' => $this->made_with_ghana_leather,
            'variations' => ProductVariationResource::collection($this->whenLoaded('variations')),
            'unit_per_item' => $this->unit_per_item,
            'condition' => $this->condition,   
            'sell_online' => $this->sell_online,
            'gender' => $this->gender?->name,
            'ust_index' => $this->ust_index,
            'commission' => $this->commission,
            'compare_at_price' => $this->compare_at_price,
            'tax_charge_on_product' => $this->tax_charge_on_product,
            'cost_per_item' => $this->cost_per_item,
            'profit' => $this->profit,
            'margin' => $this->margin,
            'track_quantity' => $this->track_quantity,      
            'sell_out_of_stock' => $this->sell_out_of_stock,   
            'has_sku' => $this->has_sku,
            'storage_location' => $this->storage_location,
            'product_ship_internationally' => $this->product_ship_internationally,
            'gross_weight' => $this->gross_weight,
            'net_weight' => $this->net_weight,
            'length' => $this->length,
            'weight' => $this->weight,
            'height' => $this->height,
            'shipping_method' => $this->shipping_method,
            'digital_product_or_service' => $this->digital_product_or_service,

            // uncomment later
            
          
            
        ];
    }
}
