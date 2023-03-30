<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariationValues extends Model
{
    use HasFactory;

    protected $fillable = [
        'value', 'variation_id'
    ];

    public function variation(): BelongsTo
    {
        return $this->belongsTo(Variation::class)->withDefault();
    }
}
