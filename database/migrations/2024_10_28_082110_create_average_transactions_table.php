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
        Schema::create('average_transactions', function (Blueprint $table) {
          
            $table->id();
            // $table->string('transaction_type');
            $table->unsignedBigInteger('inventory_transaction_id');
            $table->string('item_id');
            $table->decimal('stock_bal_qty', 15, 2);
            $table->decimal('stock_bal_value', 15, 2);
            $table->decimal('stock_bal_unit_price', 15, 2);
            $table->string('stock_position');

            $table->decimal('cogs_qty', 15, 2);
            $table->decimal('cogs_unit_price', 15, 2);
            $table->decimal('cogs_bal_value', 15, 2);

            
            $table->decimal('actual_sales_qty', 15, 2);
            $table->decimal('actual_sales_unit_price', 15, 2);
            $table->decimal('actual_sales_value', 15, 2);



            $table->decimal('profit_loss', 15, 2);
            $table->string('status');


         

            $table->timestamps();
       
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('average_transactions');
    }
};
