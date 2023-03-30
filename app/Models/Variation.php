<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Variation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function productVariation(): HasMany {
        return $this->hasMany(ProductVariation::class);
    }

    public function varitionValues(): HasMany
    {
        return $this->hasMany(VariationValues::class);
    }
}
