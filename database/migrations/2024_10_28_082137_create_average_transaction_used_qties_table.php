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
        Schema::create('average_transaction_used_qties', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('average_transaction_id');  
                      
            $table->unsignedBigInteger('inventory_transaction_id');
            $table->decimal('average_transaction_used_bal_qty', 15, 2);
            $table->decimal('average_transaction_used_bal_unit_price', 15, 2);
            $table->decimal('average_transaction_used_bal_value', 15, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('average_transaction_used_qties');
    }
};
