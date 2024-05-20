<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'slug' => $this->slug,
            'vendor_id' => $this->vendor_id,
            'vendor_firstname'=> $this->vendor?->user->first_name,
            'vendor_lastname'=> $this->vendor?->user->last_name,
            'vendor_profile_photo'=> $this->vendor?->profile_photo,
            'vendor_business_type'=> $this->vendor?->business_type,
            'vendor_office_state' => $this->vendor?->state,
            'vendor_office_city' => $this->vendor?->city,
            'vendor_office_address' => $this->vendor?->office_address,
            'category_id' => $this->category_id,
            'category_name' => $this->category?->name,
            'content' => $this->content,
            'cover_image' => $this->cover_image,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
