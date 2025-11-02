<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfilesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('profiles')->insert([
            [
                'user_id' => 1,
                'user_image' => 'profile-images/user1.png',
                'postcode' => '123-4567',
                'address' => '北海道目梨郡羅臼町松法町591-4',
                'building' => 'ABCマンション201',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'user_image' => 'profile-images/user2.png',
                'postcode' => '111-2233',
                'address' => '埼玉県熊谷市市ノ坪632-4',
                'building' => 'コーポ熊谷101',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'user_image' => 'profile-images/user3.png',
                'postcode' => '223-4455',
                'address' => '兵庫県西宮市津門綾羽町601-20',
                'building' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
