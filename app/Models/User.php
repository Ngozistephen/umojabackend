<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\Variation;
use App\Models\VariationsOption;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

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
        'image'
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


    public function vendors():BelongsToMany
    {
        return $this->belongsToMany(Vendor::class);
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
}
