<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

//　テストケースID:15　出品商品情報登録
class Id15SellItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_sell_and_store_item()
    {
        // ストレージをテスト用に切り替える
        Storage::fake('public');

        $user = User::factory()->create();
        $categories = Category::factory()->count(2)->create();
        // 送信データ用にカテゴリIDの配列を作成
        $categoryIds = $categories->pluck('id')->toArray();

        $this->actingAs($user);

        $response = $this->get(route('sellForm'));
        $response->assertStatus(200);

        $itemData = [
            // 'test_image.jpg'という名前で、100キロバイトのダミーファイルを作成
            'item_image' => UploadedFile::fake()->create('test_image.jpg', 100),
            'item_name' => 'テスト商品',
            'brand' => 'testing',
            'categories' => $categoryIds,
            'condition' => 1,
            'description' => 'テスト商品です。',
            'price' => 1000,
        ];

        $response = $this->post(route('sell'), $itemData);

        $response->assertRedirect(route('mypage.index'));

        $this->assertDatabaseHas('items',[
            'user_id' => $user->id,
            'item_name' => 'テスト商品',
            'brand' => 'testing',
            'condition' => 1,
            'description' => 'テスト商品です。',
            'price' => 1000,
        ]);

        $item = Item::where('item_name', 'テスト商品')->first();
        $this->assertNotNull($item); // 商品がDBに存在することを確認

        // 中間テーブル(category_item)にデータが正しく保存されているか確認
        foreach ($categories as $category) {
            $this->assertDatabaseHas('category_item', [
                'item_id' => $item->id,
                'category_id' => $category->id,
            ]);
        }
        $this->assertNotNull($item->item_image);
        // ストレージにファイルが存在しているか確認 （VSCodeの赤線は無視してOK）
        Storage::disk('public')->assertExists($item->item_image);
    }
}