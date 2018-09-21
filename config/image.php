<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver' => 'gd',

    /**
     *  Размеры изображений
     */
    'sizes' => [
        'product_list' => [
            'description' => 'Размер изображения товара в категории',
            'width' => 195,
            'height' => 207
        ],
        'product' => [
            'description' => 'Размер главного изображения в карточке товара',
            'width' => 495,
            'height' => 390
        ],
        'product_thumb' => [
            'description' => 'Изображения-ссылки для галереи товара',
            'width' => 110,
            'height' => 84
        ],
        'article' => [
            'description' => 'Размер изображения блога',
            'width' => 555,
            'height' => 370
        ],
        'slide' => [
            'description' => 'Размер изображения слайда',
            'width' => 848,
            'height' => 367
        ],
        'banner' => [
            'description' => 'Размер изображения слайда',
            'width' => 262,
            'height' => 495
        ],
        'scheme' => [
            'description' => 'Размер изображения схемы',
            'width' => 640,
            'height' => 480
        ]
    ],

    /**
     *  Типы изображений
     */
    'types' => [
        'default' => [
            'description' => 'Тип по умолчанию',
        ],
        'product' => [
            'description' => 'Изображение для продукта',
            'sizes' => [
                'product',
                'product_list',
                'product_thumb'
            ]
        ],
        'news' => [
            'description' => 'Изображение для постов',
            'sizes' => [
                'news'
            ]
        ],
        'article' => [
            'description' => 'Изображение для постов',
            'sizes' => [
                'article'
            ]
        ],
        'slide' => [
            'description' => 'Изображение для слайда',
            'sizes' => [
                'slide'
            ]
        ],
        'banner'  => [
            'description' => 'Изображение баннера',
            'sizes' => [
                'banner'
            ]
        ],
        'scheme'  => [
            'description' => 'Изображение схемы',
            'sizes' => [
                'scheme'
            ]
        ]
    ]

);
