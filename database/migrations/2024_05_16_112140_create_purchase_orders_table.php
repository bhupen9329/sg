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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_id', 200)->nullable();
            $table->string('category', 200)->nullable();
            $table->string('sub_category_id', 200)->nullable();
            $table->string('document_number', 200)->nullable();
            $table->string('date', 100)->nullable();
            $table->string('due_date', 100)->nullable();
            $table->string('no_of_due_date', 100)->nullable();
            $table->string('quantity', 200)->nullable();
            $table->string('rest_quantity', 200)->nullable();
            $table->string('price', 200)->nullable();
            $table->string('mode', 200)->nullable();
            $table->string('broker', 200)->nullable();
            $table->string('remark', 400)->nullable();
            $table->string('status', 100)->nullable();
            $table->string('match_position', 100)->default('open');
            $table->string('order_age', 400)->nullable();
            $table->string('close_date', 200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
