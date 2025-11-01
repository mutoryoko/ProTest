<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Notifications\VerifyEmailCustom;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use Tests\TestCase;

//　テストケースID:16　メール認証機能
class Id16MailTest extends TestCase
{
    use RefreshDatabase;

    //会員登録後、メールが送信される　
    public function test_a_verification_mail_is_sent_upon_registration(): void
    {
        Notification::fake();

        $response = $this->post(route('register', [
            'name' => 'test-user',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]));
        $response->assertRedirect(route('verification.notice'));

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);

        Notification::assertSentTo(
            [$user],
            VerifyEmailCustom::class
        );
    }

    // メール認証サイトに遷移する
    public function test_redirect_to_the_email_verification_site()
    {
        $response = $this->get(route('verification.notice'));
        $response->assertStatus(200);

        $response->assertSeeText('認証はこちらから');

        $expectedLinkHtml = '<a class="verify__btn" href="https://mailtrap.io/inboxes" target="_blank">';

        $response->assertSee($expectedLinkHtml, false);
    }

    // メール認証後、商品一覧ページへ遷移
    public function test_page_transition_after_email_verification()
    {
        $user = User::factory()->unverified()->create();

        Event::fake();

        // メール認証用の署名付きURLを生成
        $verificationUrl = URL::signedRoute(
            'verification.verify',
            [
                'id' => $user->id,
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        $response = $this->actingAs($user)->get($verificationUrl);
        // ユーザーのメール認証日時 (email_verified_at) の更新を確認
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        // Verifiedイベントが正しく発行されたか確認
        Event::assertDispatched(Verified::class);

        $response->assertRedirect(route('index', ['tab' => 'mylist']));
    }
}
