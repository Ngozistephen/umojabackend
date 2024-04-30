<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductVariationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('product-manage');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            // 'product_id' => 'required|string|exists:products,id',
            'sku' => 'nullable|string|max:255',
            'no_available' => 'nullable|numeric|min:0',
            'price' => 'nullable|numeric|min:0', 
            'color_image' => 'nullable|string|max:255', 
        ];
    }
}
