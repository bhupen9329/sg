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
            $table->string('sale_id', 20);
            $table->string('item_category', 20);
            $table->string('item_subcategory', 50);
            $table->string('qty', 10);
            $table->string('amount')->nullable();
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
