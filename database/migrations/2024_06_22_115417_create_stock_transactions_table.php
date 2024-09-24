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
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('ref_id');
            $table->string('category_id', 20);
            $table->string('subcategory_id', 50);
            $table->string('warehouse_id', 100)->default(0);
            $table->string('length', 100);
            $table->string('pcs', 100)->default(0);
            $table->string('type', 200);
            $table->string('operation', 200);
            $table->string('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
