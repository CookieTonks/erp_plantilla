<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade'); // Relación con la tabla de presupuestos
            $table->string('descripcion');  // Nombre del material
            $table->integer('cantidad'); // Cantidad solicitada
            $table->decimal('precio_unitario', 10, 2); // Precio unitario
            $table->enum('estatus', ['pendiente', 'en proceso', 'entregado'])->default('pendiente'); // Estatus de la solicitud
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materiales');
    }
};
