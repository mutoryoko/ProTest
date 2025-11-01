<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

//　テストケースID:6　商品検索機能
class Id6SearchTest extends TestCase
{
    use RefreshDatabase;

    //　部分一致検索でヒットした商品を表示
    public function test_search_items_by_item_name()
    {
        $itemHit1 = Item::factory()->create(['item_name' => 'テストA']);
        $itemHit2 = Item::factory()->create(['item_name' => 'テストB']);
        $itemNoHit = Item::factory()->create(['item_name' => 'サンプルC']);

        $response = $this->get('/?search=テスト');
        $response->assertStatus(200);

        $response->assertSeeText($itemHit1->item_name);
        $response->assertSee($itemHit1->item_image);
        $response->assertSeeText($itemHit2->item_name);
        $response->assertSee($itemHit2->item_image);
        $response->assertDontSeeText($itemNoHit->item_name);
        $response->assertDontSee($itemNoHit->item_image);
    }

    // マイリストでも検索状態が保持される
    public function test_search_state_is_maintained_in_mylist_tab()
    {
        $itemHitOnly = Item::factory()->create(['item_name' => 'テストA']);
        $itemHitAndLiked = Item::factory()->create(['item_name' => 'テストB']);
        $itemNoHit = Item::factory()->create(['item_name' => 'サンプルC']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $user->likes()->attach($itemHitAndLiked->id);

        $response = $this->get('/?search=テスト');
        $response->assertStatus(200);

        $response->assertSee($itemHitOnly->item_name);
        $response->assertSee($itemHitOnly->item_image);
        $response->assertSee($itemHitAndLiked->item_name);
        $response->assertSee($itemHitAndLiked->item_image);
        $response->assertDontSee($itemNoHit->item_name);
        $response->assertDontSee($itemNoHit->item_image);

        $response = $this->get('/?tab=mylist&search=テスト');
        $response->assertStatus(200);

        $response->assertSee($itemHitAndLiked->item_name);
        $response->assertSee($itemHitAndLiked->item_image);
        $response->assertDontSee($itemHitOnly->item_name);
        $response->assertDontSee($itemHitOnly->item_image);
        $response->assertDontSee($itemNoHit->item_name);
        $response->assertDontSee($itemNoHit->item_image);
    }
}
