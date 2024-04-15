<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Electronics' => ['Smartphones', 'Laptops', 'Cameras', 'Tablets', 'Headphones'],
            'Clothing' => ['Men', 'Women', 'Kids', 'Accessories', 'Shoes'],
            'Home & Kitchen' => ['Furniture', 'Appliances', 'Cookware', 'Bedding', 'Decor'],
            'Books & Stationery' => ['Fiction', 'Non-Fiction', 'School Supplies', 'Art & Craft', 'Office Supplies'],
            'Sports & Outdoors' => ['Fitness', 'Camping', 'Cycling', 'Hiking', 'Water Sports'],
            'Beauty & Personal Care' => ['Skincare', 'Haircare', 'Makeup', 'Fragrance', 'Personal Hygiene'],
            'Toys & Games' => ['Action Figures', 'Board Games', 'Outdoor Toys', 'Puzzles', 'Educational Toys'],
            'Automotive' => ['Car Accessories', 'Tools & Equipment', 'Maintenance', 'Interior Accessories', 'Exterior Accessories'],
            'Health & Wellness' => ['Vitamins & Supplements', 'Fitness Equipment', 'Personal Care', 'Health Monitors', 'Medical Supplies'],
            'Pets' => ['Dogs', 'Cats', 'Birds', 'Fish', 'Small Animals'],
            
        ];


        foreach ($categories as $categoryName => $subcategories) {
    
            $category = Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
                'user_id' => 1,
               
            ]);
            foreach ($subcategories as $subcategoryName) {
                SubCategory::create([
                    'name' => $subcategoryName,
                    'category_id' => $category->id
                ]);
            }
        }
    }
}
