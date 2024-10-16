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
        Schema::create('sell_purchase_matches', function (Blueprint $table) {
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


            $table->decimal('so_item_unit_price', 10, 2)->default(0);
            $table->decimal('so_item_price', 10, 2)->default(0);
            $table->decimal('po_item_unit_price', 10, 2)->default(0);
            $table->decimal('po_item_price', 10, 2)->default(0);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sell_purchase_matches');
    }
};
