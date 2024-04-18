<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class SubcategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        // return true;
        return Gate::allows('all-access');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:sub_categories,name',
            'category_id' => 'required|exists:categories,id', 
            'photo' => 'nullable', 
        ];
    }

    public function attributes()
    {
        return ['category_id' => 'category'];
    }

    public function messages(): array
    {
        return [
            'name' => "Sub Cateegory Name must be Uniue",
           
        ];
    }
}
