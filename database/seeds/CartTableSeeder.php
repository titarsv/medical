<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CartTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::table('cart')->insert([
            [
                'user_id'       => 1,
                'user_cart_id'  => Session::put('user_cart_id', md5(rand(0,5000000))),
                'user_ip'       => '82.117.238.181',
                'products'      => json_encode([['id' => 1, 'quantity' => 15]]),
                'total_quantity' => 15,
                'total_price'   => 890,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]
        ]);

    }
}
