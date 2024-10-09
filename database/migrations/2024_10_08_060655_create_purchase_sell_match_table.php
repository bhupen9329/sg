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
            $table->unsignedBigInteger('po_id');
            $table->unsignedBigInteger('so_id');

        
          
            $table->decimal('matched_quantity', 10, 2)->nullable(); 
            $table->decimal('po_rest_quantity', 10, 2)->nullable(); 
            $table->decimal('so_rest_quantity', 10, 2)->nullable(); 
            $table->timestamps();

            // Foreign key for purchase_item_id
            $table->foreign('po_id')->references('id')->on('purchase_orders')->onDelete('cascade');

            // Foreign key for sell_item_id
            $table->foreign('so_id')->references('id')->on('sales_orders')->onDelete('cascade');
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
