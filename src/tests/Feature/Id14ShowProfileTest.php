<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;

//　テストケースID:14　ユーザー情報変更
class Id14ShowProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_edit_page_shows_default_values()
    {
        $user = User::factory()->create();

        $profile = Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        // ログイン
        $this->actingAs($user);

        $response = $this->get(route('mypage.profile.edit'));
        $response->assertStatus(200);

        $response->assertSee($profile->user_image);
        $response->assertSee('value="' . $user->user_name . '"', false);
        $response->assertSee('value="'.$profile->postcode.'"', false);
        $response->assertSee('value="' . $profile->address . '"', false);
    }
}
