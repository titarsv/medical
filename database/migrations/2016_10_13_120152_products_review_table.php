<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductsReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_review', function(Blueprint $table){
            $table->increments('id');
            $table->integer('parent_review_id')->nullable();
            $table->integer('user_id');
            $table->integer('product_id');
            $table->integer('grade')->nullable();
            $table->text('review');
            $table->text('like')->nullable();
            $table->text('dislike')->nullable();
            $table->boolean('published');
            $table->boolean('new');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products_review');
    }
}
