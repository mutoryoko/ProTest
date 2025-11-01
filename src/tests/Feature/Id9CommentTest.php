<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Str;


// テストケースID8:　いいね機能
class Id9CommentTest extends TestCase
{
    use RefreshDatabase;

    //　ログインしたユーザーはコメントを送信できる
    public function test_auth_user_can_send_comment()
    {
        $item = Item::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user);

        $commentContent = 'これはテスト用のコメントです。';

        $response = $this->post(route('comment', $item->id), [
            'content' => $commentContent,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'content' => $commentContent,
        ]);

        // アイテムに紐づくコメントが1件であることを確認
        $this->assertCount(1, $item->fresh()->comments);
    }

    //　未ログインユーザーはコメントを送信できない
    public function test_guest_can_not_send_comment()
    {
        $item = Item::factory()->create();

        $commentContent = 'これはテスト用のコメントです。';

        $response = $this->post(route('comment', $item->id), [
            'content' => $commentContent,
        ]);
        $response->assertRedirect(route('loginForm'));

        $this->assertDatabaseCount('comments', 0);

        // アイテムに紐づくコメントが0件であることを確認
        $this->assertCount(0, $item->fresh()->comments);
    }

    // コメントが空の場合のエラー表示
    public function test_show_error_when_comment_is_missing()
    {
        $item = Item::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('comment', $item->id), [
            'content' => '',
        ]);

        $response->assertSessionHasErrors([
            'content' => 'コメントを入力してください',
        ]);

        $this->assertDatabaseCount('comments', 0);
    }

    // コメントが255文字以上の場合のエラー表示
    public function test_comment_must_not_exceed_255_characters()
    {
        $item = Item::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user);

        $tooLongComment = Str::random(256);

        $response = $this->post(route('comment', $item->id), [
            'content' => $tooLongComment,
        ]);

        $response->assertSessionHasErrors([
            'content' => 'コメントは255文字以内で入力してください',
        ]);

        $this->assertDatabaseCount('comments', 0);
    }
}
