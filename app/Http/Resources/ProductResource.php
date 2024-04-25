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
            'category_name' => $this->category?->name,
            'sub_category_name' => $this->subCategory?->name, 
            'photo' => $this->photo,
            'price' => $this->price,
            'material' => $this->material,
            'name' => $this->name,
            'description' => $this->description,
            'mini_stock' => $this->mini_stock,
            'tags' => $this->tags,
            // 'unit' => $this->unit,
            // 'unit_per_item' => $this->unit_per_item,
            // 'condition' => $this->condition,   
            // 'sell_online' => $this->sell_online,
            // 'product_spec' => $this->product_spec,
            // 'gender' => $this->gender,
            // 'ust_index' => $this->ust_index,
            // 'commission' => $this->commission,
            // 'compare_at_price' => $this->compare_at_price,
            // 'tax_charge_on_product' => $this->tax_charge_on_product,
            // 'cost_per_item' => $this->cost_per_item,
            // 'profit' => $this->profit,
            // 'margin' => $this->margin,
            // 'track_quantity' => $this->track_quantity,
            // 'made_with_ghana_leather' => $this->made_with_ghana_leather,
            // 'sell_out_of_stock' => $this->sell_out_of_stock,   
            // 'has_sku' => $this->has_sku,
            // 'storage_location' => $this->storage_location,
            // 'product_ship_internationally' => $this->product_ship_internationally,
            // 'gross_weight' => $this->gross_weight,
            // 'net_weight' => $this->net_weight,
            // 'length' => $this->length,
            // 'weight' => $this->weight,
            // 'height' => $this->height,
            // 'shipping_method' => $this->shipping_method,
            // 'digital_product_or_service' => $this->digital_product_or_service,

            // uncomment later
             'variations' => ProductVariationResource::collection($this->whenLoaded('variations')),
          
            
        ];
    }
}
