<?php

namespace App\Models;

use App\Enum\ProductStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "id", "name", "sku", "price", "currency", "variations", "quantity", "status"
    ];

    // protected $casts = [
    //     'status' => ProductStatusEnum::class
    // ];

}
