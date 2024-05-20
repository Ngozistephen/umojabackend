<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
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
            'title' => 'sometimes|nullable|string|max:255',
            'content' => 'sometimes|nullable|string|min:25',
            'slug' => 'nullable|string|unique:articles,slug',
            'cover_image' => 'nullable',
            'vendor_id' => 'nullable|integer|exists:vendors,id',
            'category_id' => 'sometimes|nullable|exists:categories,id',
            'published_at' => 'nullable|date',
        ];
    }
}
