<?php

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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('kode_product');
            $table->string('price');
            $table->string('price_discount')->nullable();
            $table->string('purchase_price');
            $table->string('size')->nullable();
            $table->integer('stock')->default(0);
            $table->string('thumbnail'); // thumbnail url
            $table->enum('status', ['published', 'unpublished'])->default('unpublished');
            $table->timestamps();
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
