<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('entregas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->integer('cantidad'); 
            $table->enum('tipo_documento', ['factura', 'remision']); 
            $table->string('numero_documento'); 
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('entregas');
    }
};
