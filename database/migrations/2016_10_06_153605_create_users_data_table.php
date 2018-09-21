<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_data', function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('image_id');
            $table->text('address')->nullable();
            $table->string('company')->nullable();
            $table->text('other_data')->nullable();
            $table->text('wishlist')->nullable();
            $table->boolean('subscribe');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users_data');
    }
}
