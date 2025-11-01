<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    public function run()
    {
        // condition1良好, 2:目立った傷や汚れなし, 3:やや傷や汚れあり 4:状態が悪い

        DB::table('items')->insert([
            [
                'user_id' => 1,
                'item_name' => '腕時計',
                'item_image' => 'item-images/Clock.jpg',
                'condition' => 1,
                'price' => 15000,
                'brand' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'item_name' => 'HDD',
                'item_image' => 'item-images/HDD.jpg',
                'condition' => 2,
                'price' => 5000,
                'brand' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'item_name' => '玉ねぎ3束',
                'item_image' => 'item-images/onions.jpg',
                'condition' => 3,
                'price' => 300,
                'brand' => 'なし',
                'description' => '新鮮な玉ねぎ3束のセット',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'item_name' => '革靴',
                'item_image' => 'item-images/LeatherShoes.jpg',
                'condition' => 4,
                'price' => 4000,
                'brand' => null,
                'description' => 'クラシックなデザインの革靴',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'item_name' => 'ノートPC',
                'item_image' => 'item-images/Laptop.jpg',
                'condition' => 1,
                'price' => 45000,
                'brand' => null,
                'description' => '高性能なノートパソコン',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'item_name' => 'マイク',
                'item_image' => 'item-images/Mic.jpg',
                'condition' => 2,
                'price' => 8000,
                'brand' => 'なし',
                'description' => '高音質のレコーディング用マイク',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'item_name' => 'ショルダーバッグ',
                'item_image' => 'item-images/bag.jpg',
                'condition' => 3,
                'price' => 3500,
                'brand' => null,
                'description' => 'おしゃれなショルダーバッグ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'item_name' => 'タンブラー',
                'item_image' => 'item-images/Tumbler.jpg',
                'condition' => 4,
                'price' => 500,
                'brand' => 'なし',
                'description' => '使いやすいタンブラー',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'item_name' => 'コーヒーミル',
                'item_image' => 'item-images/Coffee.jpg',
                'condition' => 1,
                'price' => 4000,
                'brand' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'item_name' => 'メイクセット',
                'item_image' => 'item-images/makeup.jpg',
                'condition' => 2,
                'price' => 2500,
                'brand' => null,
                'description' => '便利なメイクアップセット',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
