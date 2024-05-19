<?php
namespace App\Models;

use App\Models\Vendor;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'slug',
        'views',
        'likes',
        'featured_img',
        'location',
        'vendor_id',
        'category_id',
        'scheduled_at',
        'published_at',
        'updated_at'
    ];

    public static function boot(){

        parent::boot();

        static::creating(function($post){
            $post->slug = Str::slug($post->title);
        });

        static::updating(function($post) {
            if ($post->isDirty('title')) {
                $post->slug = Str::slug($post->title);
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

    protected $dates = ['scheduled_at', 'published_at'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'post_product');
    }

    public function shouldPublish()
    {
        return $this->scheduled_at && $this->scheduled_at <= Carbon::now();
    }

    public function publish()
    {
        $this->update(['published_at' => Carbon::now()]);
    }

   

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

}
