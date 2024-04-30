<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreBillingAddressRequest extends FormRequest
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
            'billing_phone_number' => 'required|string',
            'billing_address' => 'required|string',
            'billing_city' => 'required|string',
            'billing_region' => 'required|string', 
            'billing_postal_code' => 'required|string', 
            'billing_country' => 'required|string', 
            



        
    
        ];
    }
}
