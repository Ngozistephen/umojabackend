<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Variation;
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
        'profile_photo',
        'password_setup_token',
        'email_verified_at',
        
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

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
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
    


}
