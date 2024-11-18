<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('company_id')->nullable();
            $table->string('virtual_store_id')->nullable();
            $table->string('so_number', 200)->nullable();
            $table->string('address', 500)->nullable();
            $table->string('date')->nullable();
            $table->string('due_date')->nullable();
            $table->string('document_file')->nullable();
            $table->string('terms_condition', 2000)->nullable();
            $table->string('total_quantity', 200)->nullable();
            $table->string('rest_quantity', 200)->nullable();
            $table->string('total_amount', 200)->nullable();
            $table->string('total_price', 200)->nullable();
            $table->string('status', 200)->nullable();
            $table->string('match_position', 100)->default('open');
            $table->string('so_user_id', 200);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {


        Schema::dropIfExists('sales_orders');

    }
};
