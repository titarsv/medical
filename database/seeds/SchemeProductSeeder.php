<?php

use Illuminate\Database\Seeder;

class SchemeProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('scheme_products')->insert([
            [
                'scheme_id'           => '1',
                'product_id'          => '1',
                'position_id'         => '3'
            ],
            [
                'scheme_id'           => '1',
                'product_id'          => '2',
                'position_id'         => '2'
            ],
            [
                'scheme_id'           => '1',
                'product_id'          => '3',
                'position_id'         => '1'
            ],
            [
                'scheme_id'           => '1',
                'product_id'          => '4',
                'position_id'         => '4'
            ],
            [
                'scheme_id'           => '1',
                'product_id'          => '5',
                'position_id'         => '5'
            ],
            [
                'scheme_id'           => '1',
                'product_id'          => '6',
                'position_id'         => '8'
            ],
            [
                'scheme_id'           => '1',
                'product_id'          => '7',
                'position_id'         => '7'
            ],
            [
                'scheme_id'           => '1',
                'product_id'          => '8',
                'position_id'         => '6'
            ],
            [
                'scheme_id'           => '1',
                'product_id'          => '9',
                'position_id'         => '9'
            ],
            [
                'scheme_id'           => '1',
                'product_id'          => '10',
                'position_id'         => '12'
            ]
        ]);
    }
}
