<?php

use Illuminate\Database\Seeder;

class GalleriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('galleries')->insert([
            [
                'images'    => json_encode([8,9,10,11])
            ],
            [
                'images'    => json_encode([8,9,10,11])
            ],
            [
                'images'    => json_encode([8,9,10,11])
            ],
            [
                'images'    => json_encode([8,9,10,11])
            ],
            [
                'images'    => json_encode([8,9,10,11])
            ],
            [
                'images'    => json_encode([8,9,10,11])
            ],
            [
                'images'    => json_encode([8,9,10,11])
            ],
            [
                'images'    => json_encode([8,9,10,11])
            ]
        ]);
    }
}
