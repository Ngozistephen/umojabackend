<?php

namespace App\Models;

use App\Models\Post;
use App\Models\User;
use App\Models\Order;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use App\Models\ProductVariation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model 
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
   
    protected $casts = [
        'tags' => 'json',
        'sizes' => 'json',
        'colors' => 'json',
        'materials' => 'json',
        'styles' => 'json',
    ];

    public function getPriceAttribute($value)
    {
        return $value / 100;
    }


    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value * 100;
    }
    
    public static function boot(){

        parent::boot();

        static::creating(function($product){
            $product->slug = Str::slug($product->name);
        });

        static::updating(function($product) {
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

   

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class, 'product_id');
    }

    // public function registerMediaConversions(Media $media = null): void
    // {
    //     $this->addMediaConversion('thumbnail')->width(800);
    // }

    public function order(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)->withPivot(['qty', 'tracking_id', 'price']);
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_product');
    }

   

    // public function cartItems(): HasMany
    // {
    //     return $this->hasMany(CartItem::class);
    // }

    public function cartItems(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'cart_items')->withPivot('quantity');
    }

   
}
