<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubcategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return  [
            'id' => $this->id,
            'subcategory_name' => $this->name,
            'category' => $this->category?->name,
            'photo' => $this->photo,
            'category_id' => $this->category?->id,
            // 'gender_subcategory' => $this->genderSubcategory->name,
            // 'gender_subcategory_id' => $this->genderSubcategory->id,
            // 'gender_subcategory' => $this->gender_subcategory,
            // 'neted_subcategories' => $this->neted_subcategories,
        ];
    }
}
