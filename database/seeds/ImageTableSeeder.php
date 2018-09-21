<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ImageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('images')->insert([
            [
                'href' => 'no_image.jpg',
                'title' => 'Изображение не выбрано',
                'type' => 'default',
                'sizes' => '[]',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'href' => 'xJZLR4X019.jpeg',
                'title' => '_article-1.jpg',
                'type' => 'news',
                'sizes' => '[]',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'href' => 'wY5zMoy9hB.jpeg',
                'title' => 'slide1.jpg',
                'type' => 'slide',
                'sizes' => '[]',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'href' => 'QzOPMmXElq.jpeg',
                'title' => 'sidebar_banner.jpg',
                'type' => 'banner',
                'sizes' => '[]',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'href' => 'bqqf8AgSaE.jpeg',
                'title' => 'thumb-tiff-3326x0-4290309A.jpeg',
                'type' => 'unit',
                'sizes' => '[]',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'href' => 'item_prev_big.jpg',
                'title' => 'item_prev_big.jpg',
                'type' => 'action',
                'sizes' => '[]',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'href' => 'wa9z1ScJjz.jpeg',
                'title' => '1.jpg',
                'type' => 'product',
                'sizes' => '[]',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'href' => 'e8rWkTjrhB.jpeg',
                'title' => '2.jpg',
                'type' => 'product',
                'sizes' => '[]',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'href' => 'jIGHMd3ywO.jpeg',
                'title' => '3.jpg',
                'type' => 'product',
                'sizes' => '[]',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'href' => 'bWUeoC96TP.jpeg',
                'title' => '4.jpg',
                'type' => 'product',
                'sizes' => '[]',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'href' => 'JRrOwUEMYI.jpeg',
                'title' => 'thumb-tiff-3282x0-3980008.jpeg',
                'type' => 'unit',
                'sizes' => '[]',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}

