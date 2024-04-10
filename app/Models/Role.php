<?php

namespace App\Models;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];


    const ROLE_ADMIN = 1;
    const ROLE_VENDOR = 2;
    const ROLE_CUSTOMER = 3;

    
    public function permissions():BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }
}
