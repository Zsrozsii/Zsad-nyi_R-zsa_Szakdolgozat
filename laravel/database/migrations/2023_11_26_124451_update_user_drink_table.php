<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_drink', function (Blueprint $table) {
            $table->dropForeign(['drink_id']);
            $table->dropForeign(['custom_drink_id']);
            $table->dropColumn('drink_id');
            $table->dropColumn('custom_drink_id');
            $table->string('drink_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_drink', function (Blueprint $table) {
            $table->unsignedBigInteger('drink_id')->nullable();
            $table->foreign('drink_id')->references('id')->on('drinks');
            $table->unsignedBigInteger('custom_drink_id')->nullable();
            $table->foreign('custom_drink_id')->references('id')->on('custom_drinks');
            $table->dropColumn('drink_name');
        });
    }
};
