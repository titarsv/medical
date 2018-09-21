<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id');
            $table->longText('products');
            $table->integer('total_quantity');
            $table->float('total_price');
            $table->text('user_info');
            $table->text('delivery')->nullable();
            $table->text('payment')->nullable();
            $table->boolean('company')->nullable();
            $table->boolean('nds')->nullable();
            $table->text('company_info')->nullable();
            $table->integer('status_id');
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
        Schema::drop('orders');
    }
}
