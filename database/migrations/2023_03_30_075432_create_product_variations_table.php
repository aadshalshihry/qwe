<?php

use App\Models\Product;
use App\Models\Variation;
use App\Models\VariationValues;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->decimal('additional_price', 15, 2)->nullable();
            $table->unsignedBigInteger('quantity')->default(0);

            $table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Variation::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(VariationValues::class)->constrained()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variations');
    }
};
