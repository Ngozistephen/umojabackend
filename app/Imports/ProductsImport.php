<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user = Auth::user();
        $vendor_id = $user->vendor->id;
        $randomCategoryId = rand(1, 10);
        $randomSubCategoryId = rand(1, 10);
        $randomUnit = rand(1, 10);

        return new Product([
            'user_id' => $user->id,
            'vendor_id' => $vendor_id,
            'name' => $row['title'],
            'description' => $row['description'],
            'material' => $row['material'],
            'product_spec' => $row['features'],
            'price' => intval($row['price']),
            'sku' => $row['sku'],
            'weight' => $row['weight'],
            'cost_per_item' => $row['units_per_item'],
            'shipping_method' => $row['shipping_method'],
            'category_id' => $randomCategoryId,
            'sub_category_id' =>  $randomSubCategoryId,
            'unit' =>   $randomUnit
        ]);
    }
}
