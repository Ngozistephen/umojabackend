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
            // 'vendor_details' => VendorResource::collection($this->whenLoaded('vendor')),
            'vendor_details' => new VendorResource($this->vendor),
           
        ];
    }
}
