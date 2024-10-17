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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date')->nullable();
            $table->string('item_name')->nullable();
            $table->string('company_name')->nullable();
            $table->enum('transaction_type', ['purchase', 'sell'])->nullable();
            $table->decimal('unit_price', 10, 2)->nullable(); 
            $table->decimal('quantity')->nullable(); 
            $table->string('position')->nullable();

      
          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
