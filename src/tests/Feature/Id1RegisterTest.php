<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// テストケースID:1　会員登録機能
class Id1RegisterTest extends TestCase
{
    use RefreshDatabase;

    //　名前のエラー
    public function test_show_error_when_name_is_missing()
    {
        $response = $this->post(route('register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]));

        $response->assertSessionHasErrors(['name']);

        $this->get(route('registerForm'))->assertSee('お名前を入力してください');
    }

    //　メールアドレスのエラー
    public function test_show_error_when_email_is_missing()
    {
        $response = $this->post(route('register', [
            'name' => 'test-user',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]));

        $response->assertSessionHasErrors(['email']);

        $this->get(route('registerForm'))->assertSee('メールアドレスを入力してください');
    }

    //　パスワードのエラー
    public function test_show_error_when_password_is_missing()
    {
        $response = $this->post(route('register', [
            'name' => 'test-user',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => 'password',
        ]));

        $response->assertSessionHasErrors(['password']);

        $this->get(route('registerForm'))->assertSee('パスワードを入力してください');
    }

    //　パスワードのエラー（7文字以下）
    public function test_register_fails_with_short_password()
    {
        $response = $this->post(route('register', [
            'name' => 'test-user',
            'email' => 'test@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]));

        $response->assertSessionHasErrors(['password']);

        $this->get(route('registerForm'))->assertSee('パスワードは8文字以上で入力してください');
    }

    //　パスワードが一致しないエラー
    public function test_register_fails_with_wrong_password()
    {
        $response = $this->post(route('register', [
            'name' => 'test-user',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'wrong-password',
        ]));

        $response->assertSessionHasErrors(['password']);

        $this->get(route('registerForm'))->assertSee('パスワードと一致しません');
    }

    //　会員登録
    public function test_user_can_register()
    {
        $response = $this->post(route('register', [
            'name' => 'test-user',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]));
        // メール機能ありのため、メール認証誘導画面へ変更
        $response->assertRedirect('/email/verify');

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }
}
