<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Category;
use App\Models\GenderSubcategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category_id', 'photo','neted_subcategories', 'gender_subcategory', 'gender_subcategory_id']; 

    protected $casts = [
        'neted_subcategories' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function genderSubcategory()
    {
        return $this->belongsTo(GenderSubcategory::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
