<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryItemTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('category_item')->insert([
            [
                'category_id' => 1,
                'item_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 5,
                'item_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'item_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 10,
                'item_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 1,
                'item_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 5,
                'item_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'item_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'item_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 1,
                'item_id' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 4,
                'item_id' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 10,
                'item_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 10,
                'item_id' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 6,
                'item_id' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
