<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        
        return $this->user()->id === $this->route('user')->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' =>  'nullable|email',
            'phone_number' => 'nullable|string',
            'profile_photo' => 'nullable',
            'country_name' => 'nullable|string',
            'company' => 'nullable|string',
            'address' => 'nullable|string',
            'rep_country' => 'nullable|string',
            'state' => 'nullable|string',
            'city' => 'nullable|string', 
            'business_bio' => 'nullable|string|min:20',
            'twitter_handle' => 'nullable|string', 
            'facebook_handle' => 'nullable|string',
            'instagram_handle' => 'nullable|string', 
            'youtube_handle' => 'nullable|string', 
            'building_name' => 'nullable|string', 
            'bank_name' => 'nullable|string', 
            'bank_account_number' => 'nullable|string', 
            'name_on_account' => 'nullable|string', 
            'sort_code' => 'nullable|string', 
            'swift_code' => 'nullable|string', 
            'iban' => 'nullable|string',

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
