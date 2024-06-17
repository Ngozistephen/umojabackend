<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShippingZoneRequest extends FormRequest
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
            'vendor_id' => 'nullable|exists:vendors,id',
            'shipping_method_id' => 'nullable|exists:shipping_methods,id',
            'name' => 'nullable|string|max:255',
            'continent' => 'nullable|string|max:255',
            'countries' => 'nullable|array', 
            'countries.*' => 'string',
            'delivery_date_range' => 'nullable|numeric|max:50', 
            
            // 'local_delivery_company' => 'nullable|string|max:255',
            // 'local_delivery_address' => 'nullable|string|max:255',
            // 'local_delivery_country_name' => 'nullable|string|max:255',
            // 'local_delivery_city' => 'nullable|string|max:255',
            // 'local_delivery_state' => 'nullable|string|max:255',
            // 'local_delivery_apartment' => 'nullable|string|max:255',
            // 'local_delivery_zipcode' => 'nullable|string|max:255',
            // 'local_delivery_phone_number' => 'nullable|string|max:255',
            // 'local_pickup_company' => 'nullable|string|max:255',
            // 'local_pickup_address' => 'nullable|string|max:255',
            // 'local_pickup_country_name' => 'nullable|string|max:255',
            // 'local_pickup_city' => 'nullable|string|max:255',
            // 'local_pickup_state' => 'nullable|string|max:255',
            // 'local_pickup_apartment' => 'nullable|string|max:255',
            // 'local_pickup_zipcode' => 'nullable|string|max:255',
            // 'local_pickup_phone_number' => 'nullable|string|max:255',
        ];
    }
}
