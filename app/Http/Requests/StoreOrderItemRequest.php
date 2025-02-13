<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderItemRequest extends FormRequest
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
            // 'user_id' => 'required|integer|exists:users,id',
            'order_id' => 'required|integer|exists:orders,id',
            'vendor_id' => 'required|integer|exists:vendors,id',
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
           
        ];
    }
}
