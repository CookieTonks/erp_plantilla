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
        Schema::table('materials', function (Blueprint $table) {
            $table->string('unidad', 50)->nullable()->after('precio_unitario'); // Unidad (e.g., caja, pieza)
            $table->string('medida', 50)->nullable()->after('unidad'); // Medida (e.g., kg, litros)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn(['unidad', 'medida']);
        });
    }
};
