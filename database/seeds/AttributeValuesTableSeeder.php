<?php

use Illuminate\Database\Seeder;

class AttributeValuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('attribute_values')->insert([
            [
                'attribute_id' => 1,
                'name' => 'Claas'
            ]
        ]);
    }
}