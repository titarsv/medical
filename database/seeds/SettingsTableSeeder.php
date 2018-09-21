<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            [
                'key'   => 'meta_title',
                'value' => 'Lab Oborud',
                'autoload'  => 1
            ],
            [
                'key'   => 'meta_description',
                'value' => 'Интернет-магазин ВерхАгро',
                'autoload'  => 1
            ],
            [
                'key'   => 'meta_keywords',
                'value' => 'запчасти',
                'autoload'  => 1
            ],
            [
                'key'   => 'about',
                'value' => 'Все для вашей техники<br>
					Сельскохозяйственные запчасти со склада в Харькове<br>
					Добро пожаловать!<br>
					Представляем Вашему вниманию склад-магазин сельскохозяйственных запчастей, где Вы можете найти и
					приобрести любую интересующую деталь на сельхоз технику. ООО ИнтерАгро - одна из крупных компаний,
					работающая в сфере запчастей для сельскохозяйственной техники и поставляющая их во все регионы
					центральной и юго-восточной Украины. Основанная в 1999 году, фирма занимается реализацией запчастей к сельхозтехнике уже более 16 лет и за весь период работы ',
                'autoload'  => 0
            ],
            [
                'key'   => 'terms',
                'value' => 'Политика безопасности',
                'autoload'  => 0
            ],
            [
                'key'   => 'branches',
                'value' => json_encode([
                    ['phones' => ['050-417-96-65','099-999-99-99','067-000-00-00'], 'city' => 'Харьков', 'address' => 'ул. Дудинской, 1А'],
//                    ['phones' => ['050-417-96-65','055-555-555-555','067-000-00-00'], 'city' => 'Киев', 'address' => 'ул. Владимирская 39'],
//                    ['phones' => ['050-417-96-65','033-333-33-33','067-000-00-00'], 'city' => 'Одесса', 'address' => 'ул. Большая Арнаутская 14'],
//                    ['phones' => ['050-417-96-65','011-111-11-11','067-000-00-00'], 'city' => 'Днепр', 'address' => 'ул. Каруны 27']
                ]),
                'autoload'  => 1
            ],
            [
                'key'   => 'site_email',
                'value' => 'titarsv@yandex.ru',
                'autoload'  => 0
            ],
            [
                'key'   => 'notify_emails',
                'value' => json_encode(['admin@laravel.com', 'manager@laravel.com']),
                'autoload'  => 0
            ],
            [
                'key'   => 'socials',
                'value' => json_encode([
                    'facebook' => '',
                    'vkontakte' => '',
                    'instagram' => '',
                    'youtube'   => '',
                ]),
                'autoload'  => 0
            ]
        ]);
    }
}
