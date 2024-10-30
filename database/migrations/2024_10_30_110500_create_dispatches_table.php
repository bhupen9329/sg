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
            $table->string('po_company_id')->nullable();
            $table->string('po_id')->nullable();
            $table->string('po_item_id')->nullable();

            $table->string('so_id')->nullable();
            $table->string('so_company_id')->nullable();
            $table->string('so_item_category_id')->nullable();
            $table->string('so_item_sub_category_id')->nullable();


          
            $table->decimal('dispatched_quantity', 10, 2)->nullable();
            $table->decimal('po_item_qty', 10, 2)->nullable();
            $table->decimal('po_item_rest_quantity', 10, 2)->nullable();
            $table->decimal('so_item_qty', 10, 2)->nullable();
            $table->decimal('so_item_rest_quantity', 10, 2)->nullable();

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
