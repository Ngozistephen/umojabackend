<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreShippingAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('order-manage');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_region' => 'required|string',
            'shipping_postal_code' => 'required|string', 
            'shipping_country' => 'required|string', 
            'shipping_full_name' => 'required|string', 
            'shipping_email' => 'required|email', 
            'shipping_phone_number' => 'required|string',



        
    
        ];
    }
}
