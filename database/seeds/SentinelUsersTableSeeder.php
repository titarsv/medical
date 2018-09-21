<?php

use Illuminate\Database\Seeder;

class SentinelUsersTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->truncate();
        DB::table('roles')->truncate();
        DB::table('role_users')->truncate();

        $adminRole = Sentinel::getRoleRepository()->createModel()->create([
            'name' => 'Admin',
            'slug' => 'admin',
            'permissions' => [
                'admin' => true,
            ]
        ]);

        $managerRole = Sentinel::getRoleRepository()->createModel()->create([
            'name' => 'Manager',
            'slug' => 'manager',
            'permissions' => [
                'manager' => true,
            ]
        ]);

        $userRole = Sentinel::getRoleRepository()->createModel()->create([
            'name' => 'User',
            'slug' => 'user',
            'permissions' => [
                'user' => true,
            ]
        ]);
        $unRegUserRole = Sentinel::getRoleRepository()->createModel()->create([
            'name' => 'UnRegisterUser',
            'slug' => 'unregister_user',
            'permissions' => [
                'unregister_user' => true,
            ]
        ]);

        $admin = [
            'email'    => 'admin@laravel.com',
            'phone'    => '+38 (050) 000-00-00',
            'password' => 'pass',
            'permissions' => [
                'admin' => true,
            ],
            'first_name' => 'John',
            'last_name'  => 'Doe'
        ];

        $manager = [
            'email'     => 'manager@laravel.com',
            'phone'    => '+38 (050) 000-00-01',
            'password'  => 'pass',
            'permissions' => [
                'manager' => true,
            ],
            'first_name' => 'Alex',
            'last_name'  => 'Linpus'
        ];

        $users = [
            [
                'email'    => 'user1@laravel.com',
                'phone'    => '+38 (050) 000-00-02',
                'password' => 'pass',
                'permissions' => [
                    'user' => true
                ],
                'first_name' => 'Иван',
                'last_name'  => 'Иванов'
            ]
        ];
        $unRegusers = [
            [
                'email'    => 'unReguser1@laravel.com',
                'phone'    => '+38 (050) 000-00-07',
                'password' => 'null',
                'permissions' => [
                    'unregistered' => true
                ]
            ]
        ];

        $adminRole->users()->attach(Sentinel::registerAndActivate($admin));
        $managerRole->users()->attach(Sentinel::registerAndActivate($manager));

        foreach ($users as $user)
        {
            $userRole->users()->attach(Sentinel::registerAndActivate($user));
        }
        foreach ($unRegusers as $user)
        {
            $unRegUserRole->users()->attach(Sentinel::registerAndActivate($user));
        }
    }
}