<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;

// テストケースID8:　いいね機能
class Id8LikeFeatureTest extends TestCase
{
    use RefreshDatabase;

    //　いいねの登録、カウント増加
    public function test_user_can_like_a_item()
    {
        $item = Item::factory()->create();
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $item->categories()->attach($category);

        // ログイン
        $this->actingAs($user);

        $response = $this->get(route('detail', $item->id));
        $response->assertStatus(200);

        $this->post(route('like', $item->id));
        // DBに登録されているか確認
        $this->assertDatabaseHas('likes', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        // いいね数が1になっていることを確認
        $this->assertEquals(1, $item->likes()->count());
    }

    //　いいねアイコンの色変化
    public function test_icon_change_when_user_like_a_item()
    {
        $item = Item::factory()->create();
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $item->categories()->attach($category);

        // ログイン
        $this->actingAs($user);

        $response = $this->get(route('detail', $item->id));
        $response->assertStatus(200);
        //　いいね前のアイコン（無色）
        $response->assertSee('like-off.png');
        // いいね済のアイコン（黄色）
        $response->assertDontSee('like-on.png');

        $this->post(route('like', $item->id));

        $response = $this->get(route('detail', $item->id));
        $response->assertStatus(200);

        $response->assertSee('like-on.png');
        $response->assertDontSee('like-off.png');
    }

    // いいねの解除
    public function test_user_can_unlike_a_item()
    {
        $item = Item::factory()->create();
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $item->categories()->attach($category);

        // ログイン
        $this->actingAs($user);

        $response = $this->get(route('detail', $item->id));
        $response->assertStatus(200);

        // いいね済にする
        $this->post(route('like', $item->id));

        $this->assertDatabaseHas('likes', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);
        // いいね数が1になっていることを確認
        $this->assertEquals(1, $item->likes()->count());

        // いいね解除する
        $this->delete(route('unlike', $item->id));

        $this->assertDatabaseMissing('likes', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('detail', $item->id));
        $response->assertStatus(200);

        //キャッシュクリア
        $item->refresh();
        // いいね数が0になっていることを確認
        $this->assertEquals(0, $item->likes()->count());
    }
}
