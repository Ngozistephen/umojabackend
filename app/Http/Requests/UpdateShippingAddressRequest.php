<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShippingAddressRequest extends FormRequest
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
            'shipping_address' => 'nullable|string',
            'shipping_city' => 'nullable|string',
            'shipping_region' => 'nullable|string',
            'shipping_postal_code' => 'nullable|string', 
            'shipping_country' => 'nullable|string', 
            'shipping_full_name' => 'nullable|string', 
            'shipping_email' => 'nullable|email', 
            'shipping_phone_number' => 'nullable|string',
        ];
    }
}
