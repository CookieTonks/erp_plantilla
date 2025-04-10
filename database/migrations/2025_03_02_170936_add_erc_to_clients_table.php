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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('rfc')->nullable(); // Agrega el campo erc
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('rfc'); // Elimina el campo si se revierte la migración
        });
    }
};
