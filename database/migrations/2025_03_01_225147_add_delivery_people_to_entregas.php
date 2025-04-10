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
        Schema::table('entregas', function (Blueprint $table) {
            $table->string('persona_entrega')->nullable(); // Persona que lleva la entrega
            $table->string('persona_recibe')->nullable();  // Persona que recibe la entrega
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('entregas', function (Blueprint $table) {
            $table->dropColumn(['persona_entrega', 'persona_recibe']);
        });
    }
};
