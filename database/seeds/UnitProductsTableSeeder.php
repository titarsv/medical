<?php

use Illuminate\Database\Seeder;

class UnitProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('unit_products')->insert([
            [
                'unit_id'              => '1',
                'product_id'          => '1'
            ],
            [
                'unit_id'              => '1',
                'product_id'          => '2'
            ],
            [
                'unit_id'              => '1',
                'product_id'          => '3'
            ],
            [
                'unit_id'              => '1',
                'product_id'          => '4'
            ],
            [
                'unit_id'              => '1',
                'product_id'          => '5'
            ],
            [
                'unit_id'              => '1',
                'product_id'          => '6'
            ],
            [
                'unit_id'              => '1',
                'product_id'          => '7'
            ],
            [
                'unit_id'              => '1',
                'product_id'          => '8'
            ],
            [
                'unit_id'              => '1',
                'product_id'          => '9'
            ],
            [
                'unit_id'              => '1',
                'product_id'          => '10'
            ],
            [
                'unit_id'              => '1',
                'product_id'          => '11'
            ]
        ]);
    }
}
