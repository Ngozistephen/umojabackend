<?php

namespace App\Models;

use App\Models\Role;
use App\Models\User;
use Spatie\MediaLibrary\HasMedia;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vendor extends Model 
{
    use  HasApiTokens, HasFactory,Notifiable, SoftDeletes;

    protected $guarded = ['id'];
    


    public function users():BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
