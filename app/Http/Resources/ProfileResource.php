<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'email' => $this->user->email,
            'language' => $this->language,
            'gender' => $this->gender,   
            'date_birth' => $this->date_birth,
            'phone_number' => $this->phone_number,
            'company' => $this->company,
            'address' => $this->address,
            'apartment_suite' => $this->apartment_suite,
            'rep_country' => $this->rep_country,
            'profile_photo' => $this->profile_photo,
            'vendor_id_number' => $this->vendor_id_number,
            'state' => $this->state,
            'city' => $this->city,
            'zipcode' => $this->zipcode,
            'business_bio' => $this->business_bio,
            'twitter_handle' => $this->twitter_handle,
            'facebook_handle' => $this->facebook_handle,
            'instagram_handle' => $this->instagram_handle,
            'youtube_handle' => $this->youtube_handle,
            'building_name' => $this->building_name,
            'business_type' => $this->business_type,
            'business_name' => $this->business_name,
            'business_website' => $this->business_website,
            'business_image' => $this->business_image,
            'business_number' => $this->business_number,
            'tax_id_number' => $this->tax_id_number,
            'utility_photo' => $this->utility_photo,
            'business_number_photo' => $this->business_number_photo,
            'bank_name' => $this->bank_name,
            'bank_account_number' => $this->bank_account_number,
            'name_on_account' => $this->name_on_account,
            'sort_code' => $this->sort_code,
            'swift_code' => $this->swift_code,
            'iban' => $this->iban,
           
        ];
    }
}
