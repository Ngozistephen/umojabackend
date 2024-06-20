<?php

namespace App\Models;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessType extends Model
{
    use HasFactory, SoftDeletes;

   

    protected $fillable = ['name', 'user_id']; 


    public function vendors()
    {
        return $this->hasMany(Vendor::class);
    }
}
