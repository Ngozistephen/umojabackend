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
            'category_name' => $this->category->name,
            'sub_category_name' => $this->subCategory->name, 
            'photo' => $this->photo,
            'price' => $this->price,
            // 'material' => $this->material,
            'name' => $this->name,
            'description' => $this->description,
            // 'mini_stock' => $this->mini_stock,
        ];
    }
}
