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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 300)->nullable();
            $table->string('address', 1000)->nullable();
            $table->string('mobile', 50)->nullable();
            $table->string('custom_due_date', 50)->nullable();
            $table->string('email', 30)->nullable();
            $table->string('type', 30)->nullable();
            $table->string('virtual_store', 200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
