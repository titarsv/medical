<?php

use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::table('order_status')->insert([
            [
                'status' => 'Новый'
            ],
            [
                'status' => 'В обработке'
            ],
            [
                'status' => 'Оплачен'
            ],
            [
                'status' => 'Потерян'
            ],
            [
                'status' => 'В пути'
            ],
            [
                'status' => 'Закрыт'
            ],
        ]);
    }
}
