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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('company_id', 100)->nullable();
            $table->string('document_number', 100)->nullable();
            $table->string('document_file', 200)->nullable();
            $table->string('status', 100)->nullable();
            $table->string('quotation_date', 100)->nullable();
            $table->string('loading_point', 400)->nullable();
            $table->string('total_weight', 200)->nullable();
            $table->string('total_pcs', 200)->nullable();
            $table->string('payment_term', 2000)->nullable();
            $table->string('gst_type', 200)->nullable();
            $table->string('sub_total', 400)->nullable();
            $table->string('loading_cutting', 400)->nullable();
            $table->string('additional_charges', 200)->nullable();
            $table->string('freight_charges', 400)->nullable();
            $table->string('total_sgst', 200)->nullable();
            $table->string('total_cgst', 200)->nullable();
            $table->string('total_igst', 200)->nullable();
            $table->string('grand_total', 200)->nullable();
            $table->string('term_and_condition', 2000)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};


