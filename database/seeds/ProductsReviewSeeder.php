<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProductsReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::table('products_review')->insert([
            [
                'parent_review_id' => null,
                'user_id'       => 1,
                'product_id'    => 1,
                'grade'         => 4,
                'review'        => 'Хороший товар',
                'like'          => json_encode([2,3]),
                'dislike'       => null,
                'published'     => 1,
                'new'           => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'parent_review_id' => 1,
                'user_id'       => 2,
                'product_id'    => 1,
                'grade'         => null,
                'review'        => 'Полезный комментарий',
                'like'          => null,
                'dislike'       => null,
                'published'     => 1,
                'new'           => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'parent_review_id' => null,
                'user_id'       => 2,
                'product_id'    => 2,
                'grade'         => 5,
                'review'        => 'Еще один отзыв',
                'like'          => json_encode([4]),
                'dislike'       => json_encode([1,3]),
                'published'     => 1,
                'new'           => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'parent_review_id' => 3,
                'user_id'       => 3,
                'product_id'    => 1,
                'grade'         => null,
                'review'        => 'Какой-то комментарий',
                'like'          => null,
                'dislike'       => null,
                'published'     => 0,
                'new'           => 1,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
        ]);
    }
}
