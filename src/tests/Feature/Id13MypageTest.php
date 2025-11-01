<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;
use App\Models\Transaction;

// テストケースID:13 ユーザー情報取得
class Id13MypageTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_mypage()
    {
        $user = User::factory()->create();

        $profile = Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $soldItems = Item::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $boughtItems = Transaction::factory()->count(3)->create([
            'buyer_id' => $user->id,
        ]);

        // ログイン
        $this->actingAs($user);

        // マイページ（デフォルト）
        $response = $this->get(route('mypage.index'));
        $response->assertStatus(200);

        $response->assertSee($profile->user_image);
        $response->assertSeeText($user->name);

        foreach($soldItems as $soldItem){
            $response->assertSeeText($soldItem->name);
        }
        foreach($boughtItems as $boughtItem){
            $response->assertDontSeeText($boughtItem->name);
        }

        //　出品商品タブ
        $response = $this->get(route('mypage.index', ['page' => 'sell']));
        $response->assertStatus(200);

        $response->assertSee($profile->user_image);
        $response->assertSeeText($user->name);

        foreach ($soldItems as $soldItem) {
            $response->assertSeeText($soldItem->name);
        }
        foreach ($boughtItems as $boughtItem) {
            $response->assertDontSeeText($boughtItem->name);
        }

        // 購入商品タブ
        $response = $this->get(route('mypage.index', ['page' => 'buy']));
        $response->assertStatus(200);

        $response->assertSee($profile->user_image);
        $response->assertSeeText($user->name);

        foreach ($boughtItems as $boughtItem) {
            $response->assertSeeText($boughtItem->name);
        }
        foreach ($soldItems as $soldItem) {
            $response->assertDontSeeText($soldItem->name);
        }
    }
}
