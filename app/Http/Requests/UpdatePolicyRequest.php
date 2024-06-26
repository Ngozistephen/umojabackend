<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePolicyRequest extends FormRequest
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
            // '14_days' => 'nullable|string|max:255',
            'return_window' => 'nullable|string|max:255',
            'return_shipping_cost' => 'nullable|string|max:255',
            'refund_policy' => 'nullable|string|max:255',
            // '90_days' => 'nullable|string|max:255',
            // 'unlimited' => 'nullable|string|max:255',
            // 'custom_days' => 'nullable|string|max:255',
            // 'customer_provides_return_shipping' => 'nullable|string|max:255',
            // 'free_return_shipping' => 'nullable|string|max:255',
            // 'flat_rate_return_shipping' => 'nullable|string|max:255',
            // 'no_refund' => 'nullable|string|max:255',
            // 'full_refund' => 'nullable|string|max:255',
            // '50%_refund' => 'nullable|string|max:255',
            // 'restocking_fee' => 'boolean',
        ];
    }
}
