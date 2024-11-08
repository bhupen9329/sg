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
        Schema::create('conv_rates', function (Blueprint $table) {
           
        $table->id();
        $table->unsignedBigInteger('category_id'); 
        $table->unsignedBigInteger('subcategory_id'); 
        $table->date('selected_date'); 
        $table->decimal('item_price', 8, 2); 
        $table->decimal('item_freight', 8, 2); 
        $table->decimal('item_insurance', 8, 2); 
        $table->string('remarks')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conv_rates');
    }
};
