<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariation extends Model
{
    use HasFactory;

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class)->withDefault();
    }

    public function variation(): BelongsTo {
        return $this->belongsTo(Variation::class)->withDefault();
    }
}
