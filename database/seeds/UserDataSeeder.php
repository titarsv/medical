<?php

use Illuminate\Database\Seeder;

class UserDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::table('users_data')->insert([
            [
                'user_id'   => 1,
                'image_id'  => 1,
                'subscribe' => 0
            ],
            [
                'user_id'   => 2,
                'image_id'  => 1,
                'subscribe' => 0
            ],
            [
                'user_id'   => 3,
                'image_id'  => 1,
                'subscribe' => 1
            ],
            [
                'user_id'   => 4,
                'image_id'  => 1,
                'subscribe' => 1
            ],
        ]);
    }
}
