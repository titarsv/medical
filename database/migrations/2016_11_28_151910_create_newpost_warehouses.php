<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewpostWarehouses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newpost_warehouses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('warehouse_id');
            $table->string('address_ua');
            $table->string('address_ru');
            $table->integer('number');
            $table->string('city_id');
            $table->string('phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('newpost_warehouses');
    }
}
