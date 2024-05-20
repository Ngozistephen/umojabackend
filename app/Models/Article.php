<?php

namespace App\Models;

use App\Models\Vendor;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'vendor_id', 'category_id', 'content', 'cover_image', 'published_at'
    ];

    public static function boot(){

        parent::boot();

        static::creating(function($article){
            $article->slug = Str::slug($article->title);
        });

        static::updating(function($article) {
            if ($article->isDirty('title')) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    public function update(array $attributes = [], array $options = [])
    {
        if (array_key_exists('title', $attributes)) {
            $attributes['slug'] = Str::slug($attributes['title']);
        }

        return parent::update($attributes, $options);
    }

    protected $dates = ['published_at'];


    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
