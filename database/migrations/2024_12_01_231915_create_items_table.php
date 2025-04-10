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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained()->onDelete('cascade'); // Relación con Budget
            $table->string('descripcion'); // Descripción de la partida
            $table->integer('cantidad'); // Cantidad de ítems
            $table->decimal('precio_unitario', 10, 2); // Precio por unidad
            $table->decimal('subtotal', 10, 2); // Subtotal (cantidad * precio_unitario)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
