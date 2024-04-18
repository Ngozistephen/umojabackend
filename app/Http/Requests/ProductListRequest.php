<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProductListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'priceFrom' => 'numeric',
            'priceTo' => 'numeric',
            'search_global' => 'nullable',
            'sortBy' =>Rule::in(['price', 'name', 'category_id', 'subcategory_id']),
            'sortOrder' =>Rule::in(['asc', 'desc']),
            'status' => Rule::in(['active', 'draft']),

        ];
    }

    public function messages(): array
    {
        return [
            'sortBy' => "the 'sortBy' parameter accepts only 'price' or 'name' value",
            'sortOrder' => "the 'sortBy' parameter accepts only 'asc' or 'desc'",
        ];
    }
}
