<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Transaction;
use App\Http\Livewire\RecommendMylistTabs;
use Livewire\Livewire;
use Illuminate\Support\Collection;

//　テストケースID:5　マイリスト一覧取得
class Id5MyListTest extends TestCase
{
    use RefreshDatabase;

    // いいねした商品の表示
    public function test_show_items_liked_by_user()
    {
        $likedItem = Item::factory()->create([
            'item_name' => 'Test Item',
            'item_image' => 'item-images/test.jpg',
        ]);
        $notLikedItem = Item::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user);

        $user->likes()->attach($likedItem->id);

        Livewire::test(RecommendMylistTabs::class)
            ->set('activeTab', 'mylist')
            ->assertSeeText($likedItem->item_name)
            ->assertSee($likedItem->item_image)
            ->assertDontSeeText($notLikedItem->item_name)
            ->assertDontSee($notLikedItem->item_image);
    }

    // soldの表示
    public function test_sold_label_is_shown()
    {
        $user = User::factory()->create();

        // いいね済み & 購入済み商品
        $soldItem = Item::factory()->create();
        $soldItem->likes()->attach($user->id);
        Transaction::factory()->create([
            'item_id' => $soldItem->id,
            'buyer_id' => $user->id,
        ]);

        // いいね済み & 未購入商品
        $availableItem = Item::factory()->create();
        $availableItem->likes()->attach($user->id);

        // いいねしていない商品
        $notLikedItem = Item::factory()->create();

        $this->actingAs($user);

        Livewire::test(RecommendMylistTabs::class)
            ->set('activeTab', 'mylist')
            ->assertSee($soldItem->name)
            ->assertSee('sold')
            ->assertSee($availableItem->name)
            ->assertDontSee($notLikedItem->name);
    }

    // 未認証の場合はマイリスト非表示
    public function test_guest_user_sees_empty_my_list()
    {
        // 誰かが「いいね」している商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $item->likes()->attach($user->id);

        Livewire::test(RecommendMylistTabs::class)
            ->set('activeTab', 'mylist')
            ->assertViewHas('items', function ($items) {
                // $items が Collection のインスタンス、かつ中身が空である
            return $items instanceof Collection && $items->isEmpty();
            })
            ->assertDontSee($item->name);
    }
}
