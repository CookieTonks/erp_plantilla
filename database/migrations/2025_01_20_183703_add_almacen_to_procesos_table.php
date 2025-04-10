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
        Schema::table('procesos', function (Blueprint $table) {
            $table->boolean('compras')->default(0)->after('ordenes'); // Cambia 'ultimo_campo' al nombre del último campo existente en tu tabla.
            $table->boolean('almacen')->default(0)->after('compras'); // Cambia 'ultimo_campo' al nombre del último campo existente en tu tabla.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('procesos', function (Blueprint $table) {
            $table->dropColumn('compras');
            $table->dropColumn('almacen');
        });
    }
};
