<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ArticlesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('articles')->insert([
            [
                'user_id' => 1,
                'url_alias' => 'nazvanie-statii-1',
                'title' => 'Название статьи 1',
                'subtitle' => 'Название статьи 1',
                'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud',
                'image_id' => 2,
                'meta_title' => 'Название статьи 1',
                'meta_keywords' => '',
                'meta_description' => '',
                'published' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'user_id' => 1,
                'url_alias' => 'nazvanie-statii-2',
                'title' => 'Название статьи 2',
                'subtitle' => 'Название статьи 2',
                'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud',
                'image_id' => 2,
                'meta_title' => 'Название статьи 2',
                'meta_keywords' => '',
                'meta_description' => '',
                'published' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'user_id' => 1,
                'url_alias' => 'nazvanie-statii-3',
                'title' => 'Название статьи 3',
                'subtitle' => 'Название статьи 3',
                'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud',
                'image_id' => 2,
                'meta_title' => 'Название статьи 3',
                'meta_keywords' => '',
                'meta_description' => '',
                'published' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'user_id' => 1,
                'url_alias' => 'nazvanie-statii-4',
                'title' => 'Название статьи 4',
                'subtitle' => 'Название статьи 4',
                'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud',
                'image_id' => 2,
                'meta_title' => 'Название статьи 4',
                'meta_keywords' => '',
                'meta_description' => '',
                'published' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'user_id' => 2,
                'url_alias' => 'nazvanie-statii-5',
                'title' => 'Название статьи 5',
                'subtitle' => 'Название статьи 5',
                'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud',
                'image_id' => 2,
                'meta_title' => 'Название статьи 5',
                'meta_keywords' => '',
                'meta_description' => '',
                'published' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
