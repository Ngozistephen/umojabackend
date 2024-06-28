<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\FulfillmentStatus;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
            // 'shipping_address_id'  => ['sometimes', 'integer', 'exists:shipping_addresses,id'],
            // 'shipping_method_id'  => ['sometimes', 'integer', 'exists:shipping_methods,id'],
            // 'payment_method_id'  => ['sometimes', 'integer', 'exists:payment_methods,id'],
            // 'discount_code_id' => ['sometimes', 'integer', 'exists:discount_codes,id' ],
            // 'order_number'  => ['sometimes', 'integer' ],
            // 'products' => ['sometimes', 'array'],
            // 'products.*.name' => ['required_with:products', 'string'],
            // 'products.*.price' => ['required_with:products', 'numeric'],
            // 'products.*.quantity' => ['required_with:products', 'integer', 'min:1'],
            // 'read'  => ['sometimes', 'boolean'],
            'fulfillment_status'  => ['sometimes', new Enum (FulfillmentStatus::class)],
            // 'sub_total' => ['sometimes', 'numeric'],
            // 'total_amount' => ['sometimes', 'numeric'],
            // 'delivery_charge' => ['sometimes', 'numeric'],
            // 'payment_status' => ['sometimes', new Enum (PaymentStatus::class)],
            'order_status' => ['sometimes', new Enum (OrderStatus::class)],
            'tracking_number' => ['sometimes', 'string' ],
            // 'cancelled_at' =>['sometimes', 'date' ],
            // 'delivered_at' =>['sometimes', 'date' ],
            // 'paid_at' =>['sometimes', 'date' ],
        ];
    }
}
