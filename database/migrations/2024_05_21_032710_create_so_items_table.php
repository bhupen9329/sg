<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('so_items', function (Blueprint $table) {
            $table->id();
            $table->string('so_id', 20);
            $table->string('so_item_no', 20);
            $table->string('item_category', 20);
            $table->string('item_subcategory', 50);
            $table->string('qty', 10);
            $table->string('so_rest_qty', 10)->nullable();
            $table->string('so_dispatch_rest_qty', 10)->nullable();
            $table->string('unit_price')->nullable();
            $table->string('price')->nullable();
            $table->string('so_item_status')->default('Open');
            $table->string('so_dispatch_item_status')->default('Open');
            $table->string('so_item_status_date')->nullable();
            $table->string('so_item_status_remarks')->nullable();
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('so_items');
    }
};
