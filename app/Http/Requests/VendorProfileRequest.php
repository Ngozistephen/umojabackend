<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class VendorProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            
            'profile_photo' => 'nullable',
            'language' => 'nullable|string',
            'verified' => 'nullable|boolean',
            'rep_country' => 'nullable|string',
            'gender' => 'nullable|string',
            'date_birth' => 'nullable|date',
            'country_name' => 'nullable|string',
            'vendor_id_form_type' => 'nullable|string',
            'vendor_id_number' => 'nullable|string',
            'company' => 'nullable|string',
            'address' => 'nullable|string',
            'apartment_suite' => 'nullable|string',
            'status' => 'nullable|string',
            'state' => 'nullable|string',
            'city' => 'nullable|string', 
            'business_bio' => 'nullable|string|min:20',
            'busniess_email' => 'nullable| string|email',
            'busniess_phone_number' => 'nullable|string|unique:vendors',
            'office_country' => 'nullable|string',
            'office_state' => 'nullable|string',
            'office_city' => 'nullable|string',
            'office_address' => 'nullable|string',
            'complex_building_address' => 'nullable|string',
            'accept_mail_marketing' => 'nullable|boolean', 
            'tax_exempt' => 'nullable|boolean', 
            'twitter_handle' => 'nullable|string', 
            'facebook_handle' => 'nullable|string',
            'instagram_handle' => 'nullable|string', 
            'youtube_handle' => 'nullable|string', 
            'building_name' => 'nullable|string', 
            'business_type_id' => 'nullable|integer|exists:business_types,id',
            'business_name' => 'nullable|string',
            'business_website' => 'nullable|url', 
            'bank_name' => 'nullable|string', 
            'bank_account_number' => 'nullable|string', 
            'name_on_account' => 'nullable|string', 
            'business_number' => 'nullable|string',
            'tax_id_number' => 'nullable|string',
            'utility_bill_type' => 'nullable|string',
            'sort_code' => 'nullable|string', 
            'swift_code' => 'nullable|string', 
            'iban' => 'nullable|string',
            'picture_vendor_id_number' => 'nullable',
            'business_image' => 'nullable',
            'utility_photo' => 'nullable',
            'business_number_photo' => 'nullable',
            'cover_image' => 'nullable',
            'postal_code' => 'nullable|string|max:20'
        ];
    }

    // public function messages()
    // {
    //     return [
    //         'email.required' => 'The email field is required.',
    //         'email.email' => 'Please enter a valid email address.',
    //         'email.unique' => 'The email address has already been taken.',
    //         'phone_number.required' => 'The Phone Number field is required.',
    //         'phone_number.unique' => 'The Phone Number has already been taken.',
    //     ];
    // }
}
