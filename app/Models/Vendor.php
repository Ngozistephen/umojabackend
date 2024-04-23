<?php

namespace App\Models;

use App\Models\Role;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Variation;
use App\Models\VariationsOption;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


    class Vendor extends Model 
    {
        use  HasFactory,Notifiable, SoftDeletes;

        protected $guarded = ['id'];
        

        public function user(): BelongsTo
        {
            return $this->belongsTo(User::class);
        }

    


    

    
    }
