<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
            'title' => 'sometimes|nullable|string|max:100',
            'description' => 'sometimes|nullable|string|min:25',
            'slug' => 'nullable|string|unique:posts,slug',
            'views' => 'nullable|integer|min:0',
            'likes' => 'nullable|integer|min:0',
            'featured_img' => 'nullable',
            'location' => 'nullable|string',
            'product_ids' => 'array',
            'product_ids.*' => 'exists:products,id',
            'vendor_id' => 'nullable|integer|exists:vendors,id',
            'is_draft' => 'sometimes|nullable|boolean',
            'category_id' => 'sometimes|nullable|exists:categories,id',
            'scheduled_at' => 'nullable|date|after_or_equal:now',
            'published_at' => 'nullable|date',
        ];


       
    }
}
