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
        Schema::create('outward_items', function (Blueprint $table) {
   
            $table->id();
            $table->string('outward_id', 50)->nullable();
            $table->string('category_id', 100)->nullable();
            $table->string('sub_category_id', 100)->nullable();
            $table->string('length', 100)->nullable();
            $table->string('weight', 100)->nullable();
            $table->string('uom_type', 50)->nullable();
            $table->string('piece', 100)->nullable();
            $table->string('quantity', 100)->nullable();
            $table->string('so_item_id', 100)->nullable();
            $table->string('exceed_pcs', 100)->default('0');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outward_items');
    }
};
