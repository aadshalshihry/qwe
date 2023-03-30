<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'additional_price', 'quantity', 'product_id', 'variation_id'
    ];

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class)->withDefault();
    }

    public function variation(): BelongsTo {
        return $this->belongsTo(Variation::class)->withDefault();
    }

    public function variationValue(): BelongsTo
    {
        return $this->belongsTo(VariationValues::class)->withDefault();
    }
}
