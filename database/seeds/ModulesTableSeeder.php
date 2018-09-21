<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('modules')->insert([
            [
                'name'              => 'Слайдшоу на главной',
                'alias_name'        => 'slideshow',
                'status'            => 1,
                'settings'          => json_encode([
                    'slides' => [
                        [
                            'image_id'  => 3,
                            'link'      => '/',
                            'enabled'    => 1,
                            'sort_order' => 1,
                        ],
                        [
                            'image_id'  => 3,
                            'link'      => '/',
                            'enabled'    => 1,
                            'sort_order' => 1,
                        ],
                        [
                            'image_id'  => 3,
                            'link'      => '/',
                            'enabled'    => 1,
                            'sort_order' => 1,
                        ]
                    ],
                    'quantity'  => 3]),
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'name'              => 'Акции',
                'alias_name'        => 'actions',
                'status'            => 1,
                'settings'          => json_encode([
                    'actions' => [
                        [
                            'image_id'  => 6,
                            'link'      => '/',
                            'text'       => 'Короткий текст об условии акции. Lorem ipsum dolor sit amet, consectetur adipiscing elit et dolore magna aliqua. Ut enim ad minim.',
                        ],
                        [
                            'image_id'  => 6,
                            'link'      => '/',
                            'text'       => 'Короткий текст об условии акции. Lorem ipsum dolor sit amet, consectetur adipiscing elit et dolore magna aliqua. Ut enim ad minim.',
                        ],
                        [
                            'image_id'  => 6,
                            'link'      => '/',
                            'text'       => 'Короткий текст об условии акции. Lorem ipsum dolor sit amet, consectetur adipiscing elit et dolore magna aliqua. Ut enim ad minim.',
                        ]
                    ],
                    'quantity'  => 3]),
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'name'              => 'Баннеры',
                'alias_name'        => 'banners',
                'status'            => 1,
                'settings'          => json_encode([
                    'banners' => [
                        [
                            'image_id'  => 4,
                            'link'      => '/about',
                            'enabled'    => 1,
                            'sort_order' => 1,
                        ]
                    ]
                ]),
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'name'              => 'Новинки товаров',
                'alias_name'        => 'latest',
                'status'            => 1,
                'settings'          => json_encode(['products' => [4, 6, 7, 10], 'quantity' => 4]),
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'name'              => 'Лидеры продаж',
                'alias_name'        => 'bestsellers',
                'status'            => 1,
                'settings'          => json_encode(['products' => [4, 6, 7, 10], 'quantity' => 4]),
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ]
        ]);
    }
}
