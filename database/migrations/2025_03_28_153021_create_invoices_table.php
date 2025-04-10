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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->string('codigo')->unique();
            $table->string('empresa');
            $table->string('cliente');
            $table->enum('estatus', ['Pendiente', 'Cliente', 'Portal', 'Pagada', 'Cancelada',])->default('pendiente'); // Estatus de la factura
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
