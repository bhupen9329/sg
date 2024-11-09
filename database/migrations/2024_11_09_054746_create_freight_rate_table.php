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
        Schema::create('freight_rate', function (Blueprint $table) {
            $table->id();
            $table->date('freight_rate_date');
            $table->unsignedBigInteger('freight_rate')->nullable();
            $table->unsignedBigInteger('insurance_rate')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freight_rate');
    }
};
