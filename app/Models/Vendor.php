<?php

namespace App\Models;

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use App\Models\Order;
use App\Models\Review;
use App\Models\Article;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Variation;
use App\Models\ShippingZone;
use App\Models\VariationsOption;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


    class Vendor extends Model 
    {
        use  HasFactory,Notifiable, SoftDeletes,Notifiable;

        protected $guarded = ['id'];
        

        public function user(): BelongsTo
        {
            return $this->belongsTo(User::class);
        }

        // public function products()
        // {
        //     return $this->hasMany(Product::class);
        // }

        public function orders(): HasMany
        {
            return $this->hasMany(Order::class);
        }


        public function posts()
        {
            return $this->hasMany(Post::class);
        }
        

        public function articles()
        {
            return $this->hasMany(Article::class);
        }

        public function reviews()
        {
            return $this->hasMany(Review::class);
        }


        public function followers()
        {
            return $this->belongsToMany(User::class, 'user_vendor_follow', 'vendor_id', 'user_id');
        }

        public function followersCount()
        {
            return $this->followers()->count();
        }

        public function shippingZones()
        {
            return $this->hasMany(ShippingZone::class);
        }
        
    

    


    

    
    }
