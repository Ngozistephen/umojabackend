<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\VendorResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name, 
            'last_name' => $this->last_name,
            'email' => $this->email,
            'status' => $this->status,
            'picture' => $this->user_profile,
            'phone_number' => $this->phone_number,
            'role ' => $this->role->name,
            'created' => $this->created_at,
            'complete_setup' => $this->complete_setup,
            'user_country' => $this->user_country,
            'user_bio' => $this->user_bio,
            'user_city' => $this->user_city,
            'user_state' => $this->user_state,
            'user_postal_code' => $this->user_postal_code,
            'user_tax_id' => $this->user_tax_id,
            // 'vendor_details' => VendorResource::collection($this->whenLoaded('vendor')),
            'vendor_details' => new VendorResource($this->vendor),
            'following_count' => $this->followingCount(),
           
        ];
    }
}
