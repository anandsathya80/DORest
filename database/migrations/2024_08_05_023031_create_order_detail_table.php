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
        Schema::create('order_detail', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreignUuid('food_id')->references('id')->on('foods')->onDelete('cascade');
            $table->integer('food_qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_detail');
    }
};
