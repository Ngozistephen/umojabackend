<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Post;
use App\Models\Role;
use App\Models\Order;
use App\Models\Review;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Category;
use App\Models\Variation;
use App\Models\ReviewLike;
use App\Models\BusinessType;
use App\Models\ShippingZone;
use App\Models\AdminShipping;
use App\Models\PaymentMethod;
use Laravel\Cashier\Billable;
use App\Models\ShippingMethod;
use App\Models\ShippingAddress;
use App\Models\VariationsOption;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'terms_accepted',
        'role_id',
        'google_id',
        'apple_id',
        'oauth_type',
        'status',
        'phone_number',
        'user_profile',
        'password_setup_token',
        'email_verified_at',
        'business_profile_complete',
        'is_verified',
        'complete_setup',
        'user_bio',
        'user_country',
        'user_city',
        'user_state',
        'user_postal_code',
        'user_tax_id',

        
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function role()
    {
        return $this->belongsTo(Role::class);
    }


    public function vendor():HasOne
    {
        return $this->hasOne(Vendor::class);
    }
    
  
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function business_types()
    {
        return $this->hasMany(BusinessType::class);
    }

    public function variations()
    {
        return $this->hasMany(Variation::class);
    }
    public function variation_options()
    {
        return $this->hasMany(VariationsOption::class);
    }


    public function orders()
    {
        return $this->hasMany(Order::class);
    }


    public function shippingAddresses(): HasMany
    {
        return $this->hasMany(ShippingAddress::class);
    }

    public function shippingMethods(): HasMany
    {
        return $this->hasMany(ShippingMethod::class);
    }
    public function paymentMethods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class);
    }
    
    public function hasLiked(Post $post)
    {
        return $this->likes()->where('post_id', $post->id)->exists();
    }

    public function likes()
    {
        return $this->belongsToMany(Post::class, 'user_likes', 'user_id', 'post_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function likedReviews()
    {
        return $this->belongsToMany(Review::class, 'review_likes');
    }

    public function followingVendors()
    {
        return $this->belongsToMany(Vendor::class, 'user_vendor_follow', 'user_id', 'vendor_id');
    }

    public function followingCount()
    {
        return $this->followingVendors()->count();
    }

    public function hasFollowed(Vendor $vendor)
    {
        return $this->followingVendors()->where('vendor_id', $vendor->id)->exists();
    }

    public function shippingZones()
    {
        return $this->hasMany(ShippingZone::class);
    }



    // public function recentlyViewedProducts()
    // {
    //     return $this->belongsToMany(Product::class, 'recently_viewed_products')
    //                 ->withTimestamps()
    //                 ->orderBy('recently_viewed_products.created_at', 'desc');
    // }


    public function adminShippings(): HasMany
    {
        return $this->hasMany(AdminShipping::class);
    }

    
}
