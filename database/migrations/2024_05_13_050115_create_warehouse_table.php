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
        Schema::create('warehouse', function (Blueprint $table) {
            $table->id();
            $table->string('warehouse_title', 100)->nullable();
            $table->string('mobile', 100)->nullable();
            $table->string('address', 200)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('pincode', 100)->nullable();
            $table->string('gstn', 100)->nullable();
            $table->string('pan', 100)->nullable();
            $table->string('tan', 100)->nullable();
            $table->string('cin_no', 100)->nullable();
            $table->string('registration_no', 100)->nullable();
            $table->string('store_manager_id', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse');
    }
};
