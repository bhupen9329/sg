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
        Schema::create('qt_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_category', 20);
            $table->string('item_subcategory', 50);
            $table->string('qty', 10);
            $table->string('length', 100);
            $table->string('uom_type', 100)->nullable(0);
            $table->string('pcs', 100)->default(0);
            $table->string('weight', 100)->default(0);
            $table->string('price', 200); 
            $table->string('gst_percent', 200);
            $table->string('sgst', 100);
            $table->string('cgst', 20);
            $table->string('igst', 20);
            $table->string('amount')->nullable();
            $table->string('qt_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qt_items');
    }
};
