<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        return [
            // 'user_id' => 'required|integer|exists:users,id',
            'sku' => 'nullable|string|unique:products,sku',
            'unit' => 'required|string',
            'unit_per_item' => 'required|string',
            'material' => 'nullable|string',
            'condition' => 'nullable|string',
            'sell_online' => 'nullable|boolean',
            'name' => 'nullable|string',
            'description' => 'nullable|string|min:20',
            'product_spec' => 'nullable|string|min:20',
            'status' => 'nullable|string',
            'category_id' => 'nullable|string|exists:categories,id',
            'sub_category_id' => 'nullable|string|exists:sub_categories,id', 
            'gender' => 'nullable|string',
            'photo' => 'nullable',
            'slug' => 'nullable|string',
            'ust_index' => 'nullable|string', 
            'price' => 'nullable|numeric', 
            'commission' => 'nullable|numeric', 
            'compare_at_price' => 'nullable|numeric',
            'tax_charge_on_product' => 'nullable|boolean',
            'cost_per_item' => 'nullable|numeric', 
            'profit' => 'nullable|numeric',
            'margin' => 'nullable|numeric', 
            'sales_count' => 'nullable|numeric', 
            'track_quantity' => 'nullable|boolean',
            'made_with_ghana_leather' => 'nullable|numeric',
            'mini_stock' => 'nullable|numeric',
            'sell_out_of_stock' => 'nullable|boolean',
            'has_sku' => 'nullable|boolean',
            'storage_location' => 'nullable|string',
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

        ];
    }
}
