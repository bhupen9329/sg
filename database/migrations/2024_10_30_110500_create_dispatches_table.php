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
            $table->string('po_company_id');
            $table->string('so_company_id');
            $table->string('po_id');
            $table->string('so_id');
            $table->string('po_item_id');
            $table->string('so_item_id');
            $table->string('category_id');
            $table->string('subcategory_id');
            $table->decimal('dispatched_quantity', 10, 2)->nullable();
            $table->decimal('conv_rate', 10, 2)->nullable();
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
