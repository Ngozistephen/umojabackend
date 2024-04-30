<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiscountCode extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['code', 'discount_amount', 'valid_from', 'valid_to', 'usage_limit'];

    



}