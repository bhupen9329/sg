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
     
        Schema::create('po_items', function (Blueprint $table) {
            $table->id();
            $table->string('po_id', 20);
            $table->string('item_category', 20);
            $table->string('po_item_no', 20)->nullable();

            $table->string('item_subcategory', 50);
            $table->string('qty', 10);
            $table->string('unit_price')->nullable();
            $table->string('price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_items');
    }
};
