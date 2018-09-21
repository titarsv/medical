<?php

use Illuminate\Database\Seeder;

class ProductAttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_attributes')->insert([
            [
                'product_id' => 1,
                'attribute_id' => 1,
                'attribute_value_id' => 1
            ],
            [
                'product_id' => 2,
                'attribute_id' => 1,
                'attribute_value_id' => 1
            ],
            [
                'product_id' => 3,
                'attribute_id' => 1,
                'attribute_value_id' => 1
            ],
            [
                'product_id' => 4,
                'attribute_id' => 1,
                'attribute_value_id' => 1
            ],
            [
                'product_id' => 5,
                'attribute_id' => 1,
                'attribute_value_id' => 1
            ],
            [
                'product_id' => 6,
                'attribute_id' => 1,
                'attribute_value_id' => 1
            ],
            [
                'product_id' => 7,
                'attribute_id' => 1,
                'attribute_value_id' => 1
            ],
            [
                'product_id' => 8,
                'attribute_id' => 1,
                'attribute_value_id' => 1
            ],
            [
                'product_id' => 9,
                'attribute_id' => 1,
                'attribute_value_id' => 1
            ],
            [
                'product_id' => 10,
                'attribute_id' => 1,
                'attribute_value_id' => 1
            ]
        ]);
    }
}
