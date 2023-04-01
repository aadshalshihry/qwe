<?php

namespace App\Models;

use App\Enum\ProductStatusEnum;
use App\Services\VariationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "id", "name", "sku", "price", "currency", "variations", "quantity", "status", 'deleted'
    ];

    protected $casts = [
        'status' => ProductStatusEnum::class
    ];

    public static function boot()
    {
        parent::boot();
    }

    public function productVariation(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

}
