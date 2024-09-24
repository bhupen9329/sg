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
        Schema::create('stock_adjustment_items', function (Blueprint $table) {
            $table->id();
            $table->string('adjustment_id', 50)->nullable();
            $table->string('warehouse_id', 50)->nullable();
            $table->string('category_id', 50)->nullable();
            $table->string('sub_category_id', 50)->nullable();
            $table->string('length', 50)->nullable();
            $table->string('weight', 50)->nullable();
            $table->string('type', 50)->nullable();
            $table->string('piece', 50)->nullable();
            $table->string('quantity', 50)->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustment_items');
    }
};
