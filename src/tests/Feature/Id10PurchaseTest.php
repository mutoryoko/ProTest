<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\Profile;
use Stripe\Checkout\Session as StripeSession;
use Symfony\Component\DomCrawler\Crawler;
use App\Http\Livewire\TransactionTabs;
use Livewire\Livewire;

// テストケースID10:商品購入機能
class Id10PurchaseTest extends TestCase
{
    use RefreshDatabase;
    // 購入ボタンを押すと購入完了
    public function test_user_can_purchase_an_item()
    {
        $item = Item::factory()->create();
        $user = User::factory()->create();
        $profile = Profile::factory()->create();
        $shippingAddressData = [
            'shipping_postcode' => $profile->postcode,
            'shipping_address' => $profile->address,
            'shipping_building' => $profile->building,
        ];

        $this->actingAs($user);

        $response = $this->get(route('purchase', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $this->mock('alias:' . StripeSession::class, function ($mock) {
            // ダミーのURLを返すように設定する
            $mock->shouldReceive('create')
                ->once()
                ->andReturn((object)[
                    'id' => 'cs_test_a1b2c3d4e5', // ダミーのセッションID
                    'url' => 'https://dummy.stripe.checkout.url' // ダミーのリダイレクト先URL
                ]);
        });

        // 購入処理を実行
        $checkoutResponse = $this->post(route('checkout'), array_merge(
            ['item_id' => $item->id, 'payment_method' => 'konbini'],
            $shippingAddressData
        ));

        $this->assertDatabaseHas('transactions', array_merge(
            [
                'item_id' => $item->id,
                'buyer_id' => $user->id,
                'payment_method' => 1, // コンビニ支払い
            ],
            $shippingAddressData
        ));

        $checkoutResponse->assertRedirect('https://dummy.stripe.checkout.url');

        $this->assertCount(1, Transaction::all());
    }

    // 購入した商品はsold表示
    public function test_purchased_item_is_displayed_as_sold()
    {
        $soldItem = Item::factory()->create();
        $availableItem = Item::factory()->create();

        $user = User::factory()->create();
        $profile = Profile::factory()->create();
        $shippingAddressData = [
            'shipping_postcode' => $profile->postcode,
            'shipping_address' => $profile->address,
            'shipping_building' => $profile->building,
        ];

        $this->actingAs($user);

        // 購入画面を表示
        $PurchaseResponse = $this->get(route('purchase', ['item_id' => $soldItem->id]));
        $PurchaseResponse->assertStatus(200);

        $this->mock('alias:' . StripeSession::class, function ($mock) {
            // ダミーのURLを返すように設定する
            $mock->shouldReceive('create')
                ->once()
                ->andReturn((object)[
                    'id' => 'cs_test_a1b2c3d4e5', // ダミーのセッションID
                    'url' => 'https://dummy.stripe.checkout.url' // ダミーのリダイレクト先URL
                ]);
        });

        // 購入処理を実行
        $checkoutResponse = $this->post(route('checkout'), array_merge(
            ['item_id' => $soldItem->id, 'payment_method' => 'konbini'],
            $shippingAddressData
        ));

        $this->assertDatabaseHas('transactions', array_merge(
            [
                'item_id' => $soldItem->id,
                'buyer_id' => $user->id,
                'payment_method' => 1, // コンビニ支払い
            ],
            $shippingAddressData
        ));

        $checkoutResponse->assertRedirect('https://dummy.stripe.checkout.url');

        $this->assertCount(1, Transaction::all());

        // 商品一覧画面を表示
        $response = $this->get(route('index'));
        $response->assertStatus(200);

        // DomCrawlerでHTMLを解析
        $crawler = new Crawler($response->getContent());

        // .soldクラスを持つ要素が1つ存在することを確認
        $this->assertCount(
            1, $crawler->filter("#item-{$soldItem->id} .sold")
        );
        // .soldクラスを持つ要素が存在しないことを確認
        $this->assertCount(
            0, $crawler->filter("#item-{$availableItem->id} .sold")
        );
        // soldのテキスト確認
        $this->assertEquals(
            'sold', $crawler->filter("#item-{$soldItem->id} .sold")->text()
        );
    }

    // 購入した商品一覧に表示
    public function test_purchased_item_is_shown_on_mypage()
    {
        $soldItem = Item::factory()->create();
        $availableItem = Item::factory()->create();

        $user = User::factory()->create();
        $profile = Profile::factory()->create();
        $shippingAddressData = [
            'shipping_postcode' => $profile->postcode,
            'shipping_address' => $profile->address,
            'shipping_building' => $profile->building,
        ];

        $this->actingAs($user);

        // 購入画面を表示
        $PurchaseResponse = $this->get(route('purchase', ['item_id' => $soldItem->id]));
        $PurchaseResponse->assertStatus(200);

        $this->mock('alias:' . StripeSession::class, function ($mock) {
            // ダミーのURLを返すように設定する
            $mock->shouldReceive('create')
                ->once()
                ->andReturn((object)[
                    'id' => 'cs_test_a1b2c3d4e5', // ダミーのセッションID
                    'url' => 'https://dummy.stripe.checkout.url' // ダミーのリダイレクト先URL
                ]);
        });

        // 購入処理を実行
        $checkoutResponse = $this->post(route('checkout'), array_merge(
            ['item_id' => $soldItem->id, 'payment_method' => 'konbini'],
            $shippingAddressData
        ));

        $this->assertDatabaseHas('transactions', array_merge(
            [
                'item_id' => $soldItem->id,
                'buyer_id' => $user->id,
                'payment_method' => 1, // コンビニ支払い
            ],
            $shippingAddressData
        ));

        $checkoutResponse->assertRedirect('https://dummy.stripe.checkout.url');

        $this->assertCount(1, Transaction::all());

        Livewire::test(TransactionTabs::class)
            ->set('tab', 'buying')
            ->assertSeeText($soldItem->item_name)
            ->assertSee('storage/'.$soldItem->item_image)
            ->assertDontSeeText($availableItem->item_name)
            ->assertDontSee('storage/'.$availableItem->item_image);
    }
}

