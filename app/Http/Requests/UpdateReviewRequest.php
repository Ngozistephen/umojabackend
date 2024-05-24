<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
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
            'product_id' => 'sometimes|required|exists:products,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'vendor_id' => 'sometimes|required|exists:vendors,id',
            'rating' => 'nullable|integer|min:1|max:5',
            'review_status' => 'nullable|string|in:pending,approved,rejected',
            'review_comment' => 'nullable|string',
            'review_reply' => 'nullable|string',
            'published_at' => 'nullable|date',
        ];
    }
}
