<?php

namespace App\Models;

use App\Models\Post;
use App\Models\Article;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model 
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'photo', 'user_id']; 

    public static function boot(){

        parent::boot();

        static::creating(function($category){
            $category->slug = Str::slug($category->name);
        });

        static::updating(function($category) {
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    
    public function subcategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
