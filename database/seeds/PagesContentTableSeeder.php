<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pages')->insert([
            [
                'name' => 'О нас',
                'url_alias' => 'about',
                'meta_title' => 'О нас',
                'meta_keywords' => 'ключевые, слова',
                'meta_description' => 'Мета описание',
                'content' => '&lt;h1&gt;О нас&lt;/h1&gt;',
                'status' => 1,
                'sort_order' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Доставка и оплата',
                'url_alias' => 'delivery',
                'meta_title' => 'Доставка и оплата',
                'meta_keywords' => 'ключевые, слова',
                'meta_description' => 'Мета описание',
                'content' => '&lt;h1&gt;Доставка и оплата&lt;/h1&gt;',
                'status' => 1,
                'sort_order' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Контакты',
                'url_alias' => 'contact',
                'meta_title' => 'Контакты',
                'meta_keywords' => 'ключевые, слова',
                'meta_description' => 'Мета описание',
                'content' => '&lt;h1&gt;Контакты&lt;/h1&gt;',
                'status' => 1,
                'sort_order' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
