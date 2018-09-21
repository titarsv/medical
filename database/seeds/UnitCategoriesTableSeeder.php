<?php

use Illuminate\Database\Seeder;

class UnitCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('unit_categories')->insert([
            [
                'unit_id'              => '1',
                'category_id'          => '12'
            ]
        ]);
    }
}
