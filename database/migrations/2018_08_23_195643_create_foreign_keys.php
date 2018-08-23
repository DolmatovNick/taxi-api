<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->foreign('operator_id')->references('id')->on('operators');
            $table->foreign('car_id')->references('id')->on('cars');
            $table->foreign('status_id')->references('id')->on('statuses');
        });

        Schema::table('cars', function (Blueprint $table) {
            $table->foreign('car_model_id')->references('id')->on('car_models');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Schema::dropIfExists('orders');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
