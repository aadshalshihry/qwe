<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('sku')->unique()->nullable();
            $table->string('status')->default(0);
            $table->decimal('price', 15, 2)->nullable();
            $table->string('currency', 20)->nullable();
            $table->unsignedBigInteger('quantity')->nullable();
            $table->string('deleted_hint')->nullable()->comment("This is a hint for the deleted record");
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
