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
        Schema::create('ageings', function (Blueprint $table) {
            $table->id();
            $table->string('category_id', 20);
            $table->string('subcategory_id', 50);
            $table->string('length', 100);
            $table->string('warehouse_id', 100)->default(0);
            $table->string('qty', 100)->default(0);
            $table->string('balance', 200);
            $table->string('age', 200)->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ageings');
    }
};
