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
        Schema::create('ocs', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('codigo'); // Unique code
            $table->string('moneda'); // Currency (e.g., USD, MXN)
            $table->foreignId('supplier_id')->constrained('suppliers'); // Foreign key for suppliers
            $table->string('estatus'); // Status (e.g., pending, completed)
            $table->timestamps(); // Created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ocs');
    }
};
