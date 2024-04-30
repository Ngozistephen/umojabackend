<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('product-manage');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
         return  [
            // 'user_id' => 'required|integer|exists:users,id',
            'sku' => 'nullable|string|unique:products,sku',
            'unit' => 'required|string',
            'unit_per_item' => 'required|numeric',
            'material' => 'required|string',
            'condition' => 'required|string',
            'sell_online' => 'required|boolean',
            'name' => 'required|string',
            'description' => 'required|string|min:20',
            'product_spec' => 'required|string|min:20',
            'status' => 'nullable|string',
            'category_id' => 'required|string|exists:categories,id',
            'sub_category_id' => 'required|string|exists:sub_categories,id', 
            'gender' => 'nullable|string',
            'photo' => 'nullable',
            'slug' => 'nullable|string',
            'ust_index' => 'nullable|string', 
            'price' => 'required|numeric', 
            'commission' => 'required|numeric', 
            'compare_at_price' => 'required|numeric',
            'tax_charge_on_product' => 'nullable|boolean',
            'cost_per_item' => 'required|numeric', 
            'profit' => 'required|numeric',
            'margin' => 'nullable|numeric', 
            'sales_count' => 'nullable|numeric', 
            'track_quantity' => 'required|boolean',
            'made_with_ghana_leather' => 'nullable|numeric',
            'mini_stock' => 'required|numeric',
            'sell_out_of_stock' => 'nullable|boolean',
            'has_sku' => 'required|boolean',
            'storage_location' => 'required|string',
            'product_ship_internationally' => 'nullable|boolean',
            'gross_weight' => 'nullable|numeric',
            'net_weight' => 'nullable|numeric',
            'length' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'shipping_method' => 'nullable|string',
            'digital_product_or_service' => 'nullable|boolean',
            'tags' => 'nullable|array', 
            'tags.*' => 'string',
            'sizes' => 'nullable|array', 
            'sizes.*' => 'string',
            'colors' => 'nullable|array', 
            'colors.*' => 'string',
            'materials' => 'nullable|array', 
            'materials.*' => 'string',
            'styles' => 'nullable|array', 
            'styles.*' => 'string',

        ];
        // if ($this->has('variations')) {
        //     $rules['variations'] = 'array';
        //     $rules['variations.*.name'] = 'required|string';
        //     $rules['variations.*.options'] = 'array';
        //     $rules['variations.*.options.*.name'] = 'required|string';
        //     $rules['variations.*.options.*.price'] = 'required|numeric';
        // }

        
    }

    public function attributes()
    {
        return [
            'category_id' => 'category',
            'sub_category_id' => 'sub_category' 
        ];
    }

    public function messages(): array
    {
        return [
            'sku' => "SkU Name must be Uniue",
           
        ];
    }
}
