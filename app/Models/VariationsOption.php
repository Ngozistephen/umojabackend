<?php

namespace App\Models;

use App\Models\Variation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariationsOption extends Model
{
    use HasFactory;
    protected $fillable = ['name','variation_id']; 


    public function variation(): BelongsTo
    {
        return $this->belongsTo(Variation::class);
    }
}
