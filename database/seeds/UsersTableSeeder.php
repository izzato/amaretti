<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = bcrypt('secret');
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => $password,
                'role' => 'admin'
            ],
            [
                'name' => 'Owner',
                'email' => 'owner@example.com',
                'password' => $password,
                'role' => 'owner'
            ]
        ];
        foreach ($users as $user) {
            $role = array_pop($user);
            $user = User::create($user);
            $user->assignRole($role);
        }
    }
}
