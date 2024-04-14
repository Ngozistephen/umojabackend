<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class VendorRegistrationRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required| string|max:255',
            'email' =>  'required|email|unique:vendors,email',
            'password' => 'nullable| confirmed|Password::defaults()',
            'language' => 'nullable|string',
            'verified' => 'nullable|boolean',
            'rep_country' => 'nullable|string',
            'gender' => 'required|string',
            'date_birth' => 'required|date',
            'country_name' => 'required|string',
            'vendor_id_form_type' => 'required|string',
            'vendor_id_number' => 'required|string',
            'phone_number' => 'required|string|unique:vendors,phone_number',
            'company' => 'required|string',
            'address' => 'required|string',
            'apartment_suite' => 'nullable|string',
            'rep_country' => 'nullable|string',
            'status' => 'nullable|string',
            'state' => 'nullable|string',
            'city' => 'nullable|string', 
            'business_bio' => 'nullable|string|min:20',
            'accept_mail_marketing' => 'nullable|boolean', 
            'tax_exempt' => 'nullable|boolean', 
            'twitter_handle' => 'nullable|string', 
            'facebook_handle' => 'nullable|string',
            'instagram_handle' => 'nullable|string', 
            'youtube_handle' => 'nullable|string', 
            'building_name' => 'nullable|string', 
            'business_type' => 'required|string',
            'business_name' => 'required|string',
            'business_website' => 'nullable|url', 
            'business_number' => 'required|string',
            'tax_id_number' => 'required|string',
            'utility_bill_type' => 'required|string',
            'bank_name' => 'nullable|string', 
            'bank_account_number' => 'nullable|string', 
            'name_on_account' => 'nullable|string', 
            'sort_code' => 'nullable|string', 
            'swift_code' => 'nullable|string', 
            'iban' => 'nullable|string',
            'role_id' => 'nullable|integer',
            'profile_photo' => 'nullable',
            'picture_vendor_id_number' => 'nullable',
            'business_image' => 'nullable',
            'utility_photo' => 'nullable',
            'business_number_photo' => 'nullable'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'The email address has already been taken.',
            'phone_number.required' => 'The Phone Number field is required.',
            'phone_number.unique' => 'The Phone Number has already been taken.',
        ];
    }
}
