<?php

namespace App\Models;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\ReviewLike;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'user_id',
        'vendor_id',
        'rating',
        'review_status',
        'review_comment',
        'review_reply',
        'published_at',
    ];

    protected $attributes = [
        'review_status' => 'pending',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function likes()
    {
        return $this->hasMany(ReviewLike::class);
    }
}
