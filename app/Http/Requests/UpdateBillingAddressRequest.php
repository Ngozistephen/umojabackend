<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBillingAddressRequest extends FormRequest
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
            'billing_phone_number' => 'nullable|string',
            'billing_address' => 'nullable|string',
            'billing_city' => 'nullable|string',
            'billing_region' => 'nullable|string', 
            'billing_postal_code' => 'nullable|string', 
            'billing_country' => 'nullable|string', 
            



        
    
        ];
    }
}
