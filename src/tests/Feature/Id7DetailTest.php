<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Category;
use App\Models\Comment;

//　テストケースID:7　商品詳細情報取得
class Id7DetailTest extends TestCase
{
    use RefreshDatabase;
    //　必要な情報を表示
    public function test_guest_can_access_detail()
    {
        $item = Item::factory()->create();
        $users = User::factory()->count(3)->create();

        foreach($users as $user){
            Profile::factory()->create([
                'user_id' => $user->id,
            ]);

            $comment = Comment::factory()->create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
        }

        $item->likes()->attach(
            User::factory()->count(3)->create()->pluck('id')
        );

        $category = Category::factory()->create();
        $item->categories()->attach($category->pluck('id'));

        $response = $this->get(route('detail', $item->id));

        $response->assertStatus(200);

        $response->assertSeeText($item->item_name);
        $response->assertSeeText($item->brand);
        $response->assertSeeText(number_format($item->price));
        $response->assertSeeText((string)$item->likes()->count());
        $response->assertSeeText((string)$comment->count());
        $response->assertSeeText($category->name);
        $response->assertSee($item->condition);
        $response->assertSee($comment->user->profile->user_image);
        $response->assertSeeText($comment->user->user_name);
        $response->assertSeeText($comment->content);
    }

    public function test_some_categories_are_shown()
    {
        $item = Item::factory()->create();

        $categories = Category::factory()->count(5)->create();

        $item->categories()->attach($categories->pluck('id'));

        $response = $this->get(route('detail', $item->id));

        $response->assertStatus(200);

        foreach ($categories as $category) {
            $response->assertSeeText($category->name);
        }
    }
}