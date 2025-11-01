<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => '鈴木一郎',
                'email' => 'ichiro@seeder.com',
                'password' => Hash::make('password1'),
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
            ],
            [
                'name' => '佐藤二郎',
                'email' => 'jiro@seeder.com',
                'password' => Hash::make('password2'),
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
            ],
            [
                'name' => '北島三郎',
                'email' => 'saburo@seeder.com',
                'password' => Hash::make('password3'),
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
            ],
            [
                'name' => '伊藤四郎',
                'email' => 'shiro@seeder.com',
                'password' => Hash::make('password4'),
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => null,
            ],
            [
                'name' => '稲垣五郎',
                'email' => 'goro@seeder.com',
                'password' => Hash::make('password5'),
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => null,
            ],
        ]);
    }
}
