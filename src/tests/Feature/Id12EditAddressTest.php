<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;
use App\Models\Transaction;
use Stripe\Checkout\Session as StripeSession;

// テストケースID:12 配送先変更機能
class Id12EditAddressTest extends TestCase
{
    use RefreshDatabase;

    // 登録した住所が購入画面に反映される
    public function test_buyer_can_edit_and_reflect_on_purchase_page()
    {
        $item = Item::factory()->create();
        $buyer = User::factory()->create();

        //変更前の住所
        $profile = Profile::factory()->create([
            'user_id' => $buyer->id,
            'postcode' => '123-4567',
            'address' => '変更前の住所',
            'building' => '変更前のマンション',
        ]);

        $beforeAddress = $profile->address;

        //変更後の住所
        $updatedAddressData = [
            'postcode' => '890-0000',
            'address' => '変更後の住所',
            'building' => '変更後のマンション',
        ];

        $this->actingAs($buyer);

        $response = $this->get(route('address.edit', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response->assertSee($profile->postcode);
        $response->assertSee($profile->address);
        $response->assertSee($profile->building);

        // 住所を更新するリクエストを送信
        $updateResponse = $this->put(route('address.update', ['item_id' => $item->id]), $updatedAddressData);
        $updateResponse->assertRedirect(route('purchase', ['item_id' => $item->id]));

        $profile->refresh();

        $this->assertDatabaseHas('profiles', [
            'id' => $profile->id,
            'postcode' => '890-0000',
            'address' => '変更後の住所',
            'building' => '変更後のマンション',
        ]);

        $this->assertDatabaseMissing('profiles', [
            'id' => $profile->id,
            'address' =>  $beforeAddress,
        ]);

        $purchasePageResponse = $this->get(route('purchase', ['item_id' => $item->id]));
        $purchasePageResponse->assertStatus(200);

        $purchasePageResponse->assertSee($updatedAddressData['postcode']);
        $purchasePageResponse->assertSee($updatedAddressData['address']);
        $purchasePageResponse->assertSee($updatedAddressData['building']);
        $purchasePageResponse->assertDontSee($beforeAddress);
    }

    // 購入商品に送付先住所が紐づいて登録される
    public function test_shipping_address_is_linked_to_the_purchased_item_and_store()
    {
        $item = Item::factory()->create();
        $buyer = User::factory()->create();

        $addressData = [
            'postcode' => '999-0000',
            'address' => '更新した住所',
            'building' => '更新した建物名'
        ];

        $this->actingAs($buyer);

        $this->put(route('address.update', ['item_id' => $item->id]), $addressData)
            ->assertRedirect(route('purchase', ['item_id' => $item->id]));

        $this->mock('alias:'.StripeSession::class, function ($mock) {
            // ダミーのURLを返すように設定する
            $mock->shouldReceive('create')
                ->once()
                ->andReturn((object)[
                    'id' => 'cs_test_a1b2c3d4e5', // ダミーのセッションID
                    'url' => 'https://dummy.stripe.checkout.url' // ダミーのリダイレクト先URL
                ]);
        });

        // 購入処理を実行
        $checkoutResponse = $this->post(route('checkout'), [
            'item_id' => $item->id,
            'payment_method' => 'konbini',
            'shipping_postcode' => $addressData['postcode'],
            'shipping_address' => $addressData['address'],
            'shipping_building' => $addressData['building'],
        ]);

        $this->assertDatabaseHas('transactions', [
            'item_id' => $item->id,
            'buyer_id' => $buyer->id,
            'payment_method' => 1, // コンビニ支払い
            'shipping_postcode' => '999-0000',
            'shipping_address' => '更新した住所',
            'shipping_building' => '更新した建物名',
        ]);

        $checkoutResponse->assertRedirect('https://dummy.stripe.checkout.url');

        $this->assertCount(1, Transaction::all());
    }
}
