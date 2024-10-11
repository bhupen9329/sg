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
            $table->string('po_id')->nullable();
            $table->string('so_id')->nullable();
            $table->string('po_item_id')->nullable();
            $table->string('so_item_id')->nullable();
          
            $table->decimal('matched_quantity', 10, 2)->nullable();
            $table->decimal('po_item_qty', 10, 2)->nullable();
            $table->decimal('po_item_rest_quantity', 10, 2)->nullable();
            $table->decimal('so_item_qty', 10, 2)->nullable();
            $table->decimal('so_item_rest_quantity', 10, 2)->nullable();
            $table->timestamps();

            // $table->foreign('po_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            // $table->foreign('so_id')->references('id')->on('sales_orders')->onDelete('cascade');


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
