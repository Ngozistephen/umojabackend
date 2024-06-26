<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePolicyRequest extends FormRequest
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
        return  [
            'return_window' => 'required|string|max:255',
            'return_shipping_cost' => 'required|string|max:255',
            'refund_policy' => 'required|string|max:255',
            
       
        ];
    }
}
