<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\VendorResource;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'product_id' => $this->product_id,
            'user_id' => $this->user_id,
            // 'user_firstname'=> $this->user->first_name,
            // 'user_lastname'=> $this->user->last_name,
            'vendor_id' => $this->vendor_id,
            'rating' => $this->rating,
            'review_status' => $this->review_status,
            'review_comment' => $this->review_comment,
            'review_reply' => $this->review_reply,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'product' => new ProductResource($this->whenLoaded('product')),
            'user' => new UserResource($this->whenLoaded('user')),
            'vendor' => new VendorResource($this->whenLoaded('vendor')),
        ];
    }
}
