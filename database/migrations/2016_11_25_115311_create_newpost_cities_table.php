<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewpostCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newpost_cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('city_id');
            $table->string('name_ua');
            $table->string('name_ru');
            $table->string('region_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('newpost_cities');
    }
}
