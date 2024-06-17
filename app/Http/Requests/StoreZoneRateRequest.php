<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreZoneRateRequest extends FormRequest
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
            // 'shipping_zone_id' => 'required|exists:shipping_zones,id',
            'name' => 'nullable|string|max:255',
            'custom_rate_name' => 'nullable|string|max:255',
            'condition' => 'nullable|string|max:255',
            'custom_delivery_description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'based_on_item_weight' => 'nullable|boolean',
            'based_on_order_price' => 'nullable|boolean',
            'minimum_weight' => 'nullable|integer|min:0',
            'maximum_weight' => 'nullable|integer|min:0',
            'minimum_price' => 'nullable|integer|min:0',
            'maximum_price' => 'nullable|integer|min:0',
        ];
    }
}
