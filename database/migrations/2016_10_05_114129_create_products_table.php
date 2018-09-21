<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->longText('description');
            $table->string('meta_title');
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->integer('image_id');
            $table->string('gallery');
            $table->string('url_alias');
            $table->float('price');
            $table->float('old_price');
            $table->string('articul');
            $table->string('catalog_id')->nullable();
            $table->integer('quantity');
            $table->boolean('stock');
            $table->float('rating')->nullable();
            $table->string('analogs')->default('[]');
            $table->integer('views')->default(0);
            $table->softDeletes();
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
        Schema::drop('products');
    }
}
