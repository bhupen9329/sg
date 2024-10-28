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
        Schema::create('average_transaction_stacks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('average_transaction_id');            
            $table->unsignedBigInteger('inventory_transaction_id');
            $table->date('purchase_date');        
            $table->decimal('average_transaction_stacks_bal_qty', 15, 2);
            $table->decimal('average_transaction_stacks_bal_unit_price', 15, 2);
            $table->decimal('average_transaction_stacks_bal_value', 15, 2);
       
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('average_transaction_stacks');
    }
};
