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
            $table->unsignedBigInteger('client_user_id')->nullable(); // You can add `nullable()` if the user is optional
            $table->foreign('client_user_id')->references('id')->on('client_users')->onDelete('set null'); // Create foreign key
        });
    }

    public function down()
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropForeign(['client_user_id']);
            $table->dropColumn('client_user_id');
        });
    }
};
