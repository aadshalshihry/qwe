<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     CREATE TABLE `products` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(255) DEFAULT NULL,
        `sku` varchar(255) DEFAULT NULL UNIQUE,
        `status` varchar(255) DEFAULT NULL,
        `variations` text DEFAULT NULL,
        `price` decimal(7,2) DEFAULT NULL,
        `currency` varchar(20) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;


     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->string('sku', 255)->unique()->nullable();
            $table->string('status', 255)->default(0);
            $table->text('variations', 255)->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->string('currency', 20)->nullable();
            $table->unsignedBigInteger('quantity')->default(0);
            $table->boolean('deleted')->default(false);
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
