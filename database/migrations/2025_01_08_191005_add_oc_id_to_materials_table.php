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
            $table->foreignId('oc_id')->nullable()->constrained('ocs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropForeign(['oc_id']);
            $table->dropColumn('oc_id');
        });
    }
};
