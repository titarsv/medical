<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('attributes')->insert([
            [
                'name' => 'Бренд',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
       ]);
    }
}
