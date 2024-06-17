<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\FulfillmentStatus;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            // 'vendor_id'  => ['required', 'integer', 'exists:vendors,id'],
            'shipping_address_id'  => ['required', 'integer', 'exists:shipping_addresses,id'],
            'shipping_method_id'  => ['nullable', 'integer', 'exists:shipping_methods,id'],
            'payment_method_id'  => ['required', 'integer', 'exists:payment_methods,id'],
            'discount_code_id' => ['nullable', 'integer', 'exists:discount_codes,id' ],
            'order_number'  => ['nullable', 'integer' ],
            'products' => ['required', 'array'],
            'products.*.name' => ['required', 'string'],
            'products.*.price' => ['required', 'numeric'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
            'read'  => ['nullable', 'boolean'],
            'fulfillment_status'  => ['nullable', new Enum (FulfillmentStatus::class)],
            'sub_total' => ['nullable', 'numeric'],
            'total_amount' => ['nullable', 'numeric'],
            'delivery_charge' => ['nullable', 'numeric'],
            'payment_status' => ['nullable', new Enum (PaymentStatus::class)],
            'order_status' => ['nullable', new Enum (OrderStatus::class)],
            'tracking_number' => ['nullable', 'string' ],
            'cancelled_at' =>['nullable', 'date' ],
            'delivered_at' =>['nullable', 'date' ],
            'paid_at' =>['nullable', 'date' ],
        ];
    }
}
