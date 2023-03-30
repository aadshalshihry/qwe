<?php

use App\Models\Variation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->foreignIdFor(Product::class)->constrained()->onDelete();
            $table->foreignIdFor(Variation::class)->constrained()->onDelete();
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
