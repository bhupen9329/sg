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
        Schema::create('inwards', function (Blueprint $table) {
            $table->id();
            $table->string('inward_number', 50)->nullable();
            $table->string('supplier_id', 100)->nullable();
            $table->string('po_document_number', 100)->nullable();
            $table->string('inw_remarks', 100)->nullable();
            $table->string('date', 50)->nullable();
            $table->string('total_weight', 100)->nullable();
            $table->string('total_amount', 100)->nullable();
            $table->string('status', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inwards');
    }
};
