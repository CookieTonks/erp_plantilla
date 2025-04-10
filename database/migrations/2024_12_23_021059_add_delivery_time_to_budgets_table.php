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
        Schema::table('budgets', function (Blueprint $table) {
            $table->string('delivery_time')->nullable(); // You can use string or text, depending on your needs
        });
    }

    public function down()
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropColumn('delivery_time');
        });
    }
};
