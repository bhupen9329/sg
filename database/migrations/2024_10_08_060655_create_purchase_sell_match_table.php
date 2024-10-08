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
        Schema::create('purchase_sell_match', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_item_id');
            $table->unsignedBigInteger('sell_item_id');
            $table->unsignedInteger('matched_quantity');
            $table->timestamps();

            // Foreign key for purchase_item_id
            $table->foreign('purchase_item_id')->references('id')->on('purchase_items')->onDelete('cascade');

            // Foreign key for sell_item_id
            $table->foreign('sell_item_id')->references('id')->on('sell_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_sell_match');
    }
};
