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
        Schema::create('outwards', function (Blueprint $table) {
            $table->id();
            $table->string('outward_number', 50)->nullable();
            $table->string('company_id', 100)->nullable();
            $table->string('supplier_id', 100)->nullable();
            $table->string('so_number', 100)->nullable();
            $table->string('type', 100)->nullable();
            $table->string('status', 100)->nullable();
            $table->string('date', 50)->nullable();
            $table->string('vehicle_number', 50)->nullable();
            $table->string('total_weight', 100)->nullable();
            $table->string('loading_charges', 100)->nullable();
            $table->string('additional_charges', 100)->nullable();
            $table->string('freight', 100)->nullable();
            $table->string('so_id', 100)->nullable();
            $table->string('supervisor', 100)->nullable();
            $table->string('bill_status', 100)->nullable();
            $table->string('remarks', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outwards');
    }
};
