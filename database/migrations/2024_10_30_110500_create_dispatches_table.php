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
        Schema::create('dispatches', function (Blueprint $table) {
          
            $table->id();
            $table->string('date');
            $table->string('dispatch_number');
            $table->string('po_company_id');
            $table->string('so_company_id');
            $table->string('po_id');
            $table->string('so_id');
            $table->string('po_item_id');
            $table->string('so_item_id');
            $table->string('category_id');
            $table->string('subcategory_id');
            $table->decimal('dispatched_quantity', 10, 2)->nullable();
            $table->decimal('dispatch_unit_price', 10, 2)->nullable();
            $table->decimal('conv_rate', 10, 2)->nullable();
            $table->decimal('dispatch_freight', 10, 2)->nullable();
            $table->decimal('dispatch_other', 10, 2)->nullable();
            $table->decimal('dispatch_total', 10, 2)->nullable();
            $table->decimal('dispatch_so_unit_price', 10, 2)->nullable();
            $table->decimal('dispatch_so_freight', 10, 2)->nullable();
            $table->decimal('dispatch_so_other', 10, 2)->nullable();
            $table->decimal('dispatch_so_total', 10, 2)->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('receiver_person')->nullable();
            $table->string('dispatch_type')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispatches');
    }
};
