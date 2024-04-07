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
        Schema::create('user_drink', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('drink_id')->nullable();
            $table->foreign('drink_id')->references('id')->on('drinks');
            $table->unsignedBigInteger('custom_drink_id')->nullable();
            $table->foreign('custom_drink_id')->references('id')->on('custom_drinks');
            $table->integer('volume');
            $table->integer('caffeine');
            $table->float('sugar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_drink');
    }
};
