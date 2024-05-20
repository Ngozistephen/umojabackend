<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'slug' => $this->slug,
            'views' => $this->views,
            'likes' => $this->likes,
            'featured_img' => $this->featured_img,
            'location' => $this->location,
            'scheduled_at' => $this->scheduled_at,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'vendor_id' => $this->vendor_id,
            'vendor_firstname'=> $this->vendor?->user->first_name,
            'vendor_lastname'=> $this->vendor?->user->last_name,
            'vendor_profile_photo'=> $this->vendor?->profile_photo,
            'vendor_business_type'=> $this->vendor?->business_type,
            'is_draft' => $this->is_draft,
            'category_id' => $this->category_id,
            'category_name' => $this->category?->name,
            'products' => ProductResource::collection($this->whenLoaded('products')), // Nested resource for products
        ];

    }
}
