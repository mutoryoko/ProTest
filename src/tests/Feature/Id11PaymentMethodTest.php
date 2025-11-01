<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

// テストケースID:11 支払い方法選択機能
class Id11PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    // 小計画面に支払い方法が反映される
    public function test_reflect_the_payment_method()
    {
        $item = Item::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('purchase', $item->id));
        $response->assertStatus(200);

        //　コンビニ支払いの場合
        $response = $this->get(route('purchase', [
            'item_id' => $item->id,
            'payment_method' => 'konbini'
        ]));
        $response->assertStatus(200);
        $response->assertSeeInOrder(['支払い方法', 'コンビニ支払い']);

        // カード支払いの場合
        $response = $this->get(route('purchase', [
            'item_id' => $item->id,
            'payment_method' => 'card'
        ]));
        $response->assertStatus(200);
        $response->assertSeeInOrder(['支払い方法', 'カード支払い']);
    }
}
