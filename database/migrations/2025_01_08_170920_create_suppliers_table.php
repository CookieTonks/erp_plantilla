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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id(); // Columna ID (primary key, auto increment)
            $table->string('nombre'); // Nombre del proveedor
            $table->string('razon_social'); // Razón social del proveedor
            $table->string('direccion')->nullable(); // Dirección del proveedor (puede ser null)
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
