<?php

namespace App\Models;

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use App\Models\Order;
use App\Models\Policy;
use App\Models\Review;
use App\Models\Article;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Variation;
use App\Models\LocalPickup;
use App\Models\BusinessType;
use App\Models\ShippingZone;
use App\Models\AdminShipping;
use App\Models\LocalDelivery;
use App\Models\ShippingMethod;
use App\Models\StripeStateToken;
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
        protected $appends = ['total_ratings'];
        

        protected $casts = ['completed_stripe_onboarding' => 'bool'];

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

        public function getTotalRatingsAttribute()
        {
            return $this->reviews()->sum('rating');
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
        
        public function localDeliveries()
        {
            return $this->hasMany(LocalDelivery::class);
        }
        
        public function localPickups()
        {
            return $this->hasMany(LocalPickup::class);
        }

        public function shippingMethods()
        {
            return $this->hasMany(ShippingMethod::class);
        }
        
        public function adminShipping(): HasOneThrough
        {
            return $this->hasOneThrough(AdminShipping::class, ShippingZone::class, 'vendor_id', 'id', 'id', 'admin_shipping_id');
        }
    

        public function business_type()
        {
            return $this->belongsTo(BusinessType::class);
        }

    

        public function policy()
        {
            return $this->hasOne(Policy::class);
        }


        public function stripeStateTokens()
        {
            return $this->hasMany(StripeStateToken::class);
        }
    

    
    }
